<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\session;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Redirect;
use Auth;

//use App\User;

class PaymentController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $data = array();
        $experienceId = session('experience_info')['exp_id'];
        $exp_accomodation_id = session('experience_info')['exp_acm_id'];
        $exp_booking_date = session('experience_info')['exp_booking_date'];
        $days = session('experience_info')['exp_booking_durations'];
        $data['exp_booking_durations'] = @$days;
        if ($experienceId) {
            session(['experience_id' => $experienceId, 'exp_accomodation_id' => $exp_accomodation_id]);
            $exParam['where'] = array("id" => $experienceId);
            $experiences = \App\Experiences::get_data($exParam);
            $experience = (@$experiences[0]) ? @$experiences[0] : array();
            $data['experience'] = @$experience;
            
            // Get Experience Gallery Images
            $paramEGI['select'] = array('id', 'experience_id', 'image_url', 'image_title');
            $paramEGI['where'] = array("experience_id" => $data['experience']->id);
            $data['imagegalleries'] = \App\ExperienceImageGallery::get_data($paramEGI);

            $param = array("where" => array("id" => $data['experience']->center_id), "limit" => 1);
            $data['ecenter'] = \App\Centers::get_data($param);
            $data['center'] = (@$data['ecenter'][0]) ? : array();

            $data['experience_accomodation'] = array();
            if ($exp_accomodation_id) {

                $arexp_booking_date = explode(" - ", @$exp_booking_date);
                $exp_booking_start = \Carbon\Carbon::parse(@$arexp_booking_date[0])->format("Y-m-d");
                $exp_booking_end = \Carbon\Carbon::parse(@$arexp_booking_date[1])->format("Y-m-d");
            
                $experience_accomodations = \App\Experiences::get_exp_acm_data(@$experience->id, @$exp_accomodation_id, @$exp_booking_start, $exp_booking_end, $days);
                $experience_accomodation = (!empty($experience_accomodations[0]) ? $experience_accomodations[0] : array());
                $data['experience_accomodation'] = $experience_accomodation;
                
                if ($experience_accomodation) {
                    
                    // Get Accomodation Gallery Images
                    $data['accomodationimagegalleries'] = \App\AccomodationImageGallery::select('id', 'accomodation_id', 'image_url', 'image_title')
                                ->where("accomodation_id", $exp_accomodation_id)->get();
                                
                    $discount = 0;
                    $experience_accomodation->htmlPrice = "";
                    $pay = @$experience_accomodation->room_price;
                    $org_price = @$experience_accomodation->price_per_night_per_guest;
                    $data['org_price'] = $org_price; 
                    if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                        if (@$experience->eirly_bird_discount_type == "amt") {
                            $discount += @$experience->eirly_bird_discount;
                        } else {
                            $discount += (@$pay * @$experience->eirly_bird_discount) / 100;
                        }
                    }
    
                    if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                        $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                        if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $now)) {
                            if (@$experience->offer_discount_type == "amt") {
                                $discount += @$experience->offer_discount;
                            } else {
                                $discount += (@$pay * @$experience->offer_discount) / 100;
                            }
                        }
                    }
    
                    if ((!empty(@$discount)) || (($pay != $org_price) && ($org_price > 0))) {
                        $experience_accomodation->htmlPrice = '<del class="text-default">' .
                                \App\Http\Helpers\CommonHelper::get_currency_rate((@$org_price), @$experience_accomodation->currency)
                                . '</del>';
                    }
                    $experience_accomodation->htmlPrice .= \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);   
                    $data['discount'] = $discount;
                    $data['accomodation_html_price']=$experience_accomodation->htmlPrice;
                    $data['accomodation_price'] = @$pay - $discount;
                    
                    
                    $deposit_amount = $data['accomodation_price'];    
                    $booking_amount = $data['accomodation_price'];

                    // Calculate Commission
                    $commission = (@$experience->commission) ? ((($pay  - $discount) * @$experience->commission) / 100) : 0;
                    if (!empty(@$experience->deposit_policy)) {
                        switch (@$experience->deposit_policy) {
                            case "2" :
                                // If Fixed Amount
                                $deposit_amount = @$experience->deposit_amount;
                                $booking_amount = @$experience->deposit_amount;
                                break;
                            case "3" :
                                // If Percentage
                                $deposit_amount = (@$pay * @$experience->deposit_amount) / 100;
                                $booking_amount = $deposit_amount;
                                break;
                            default:
                                $deposit_amount = @$pay;
                                $booking_amount = $deposit_amount;
                                break;
                        }
                    }
                    $data['booking_amount'] = @$booking_amount;
                    $data['booking_amount_html_price'] = \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience_accomodation->currency);   
                }
            }

            $data['reservation_info'] = session('reservation_info');
            if (@$_GET['test'] == 1) {
                return view('payment_newlayout', $data);
            } else {
                if ($data['booking_amount']) {
                    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                    $orderData = [
                        'receipt' => 'rcptbb_' . uniqid(),
                        'amount' => \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience_accomodation->currency, false) * 100, // ₹500 in paise
                        'currency' => \App\Http\Helpers\CommonHelper::get_site_currency(),
                        'payment_capture' => 1, // auto-capture
                    ];
                    $razorpayOrder = $api->order->create($orderData);
                    $data['order_id'] = $razorpayOrder->id;
                }
                return view('payment', $data);
            }
        } else {
            return Redirect('experiences');
        }
    }
    public function index_new(Request $request) {
        $data = array();
        $experienceId = session('experience_info')['exp_id'];
        $exp_accomodation_id = session('experience_info')['exp_acm_id'];
        $exp_booking_date = session('experience_info')['exp_booking_date'];
        if ($experienceId) {
            session(['experience_id' => $experienceId, 'exp_accomodation_id' => $exp_accomodation_id]);
            $exParam['where'] = array("id" => $experienceId);
            $experience = \App\Experiences::get_data($exParam);
            $data['experience'] = (@$experience[0]) ? : array();

            $param = array("where" => array("id" => $data['experience']->center_id), "limit" => 1);
            $data['ecenter'] = \App\Centers::get_data($param);
            $data['center'] = (@$data['ecenter'][0]) ? : array();

            $data['experience_accomodation'] = array();
            if ($exp_accomodation_id) {

                $arexp_booking_date = explode(" - ", @$exp_booking_date);
                $exp_booking_start = @$arexp_booking_date[0];
                $exp_booking_end = @$arexp_booking_date[1];

                $experience_accomodations = \App\Experiences::get_exp_acm_data($data['experience']->id, $exp_accomodation_id, @$exp_booking_start);
                $data['experience_accomodation'] = (!empty($experience_accomodations[0]) ? $experience_accomodations[0] : array());

                // Get Accomodation Gallery Images
                $data['accomodationimagegalleries'] = \App\AccomodationImageGallery::select('id', 'accomodation_id', 'image_url', 'image_title')
                                ->where("accomodation_id", $exp_accomodation_id)->get();
            }

            $data['reservation_info'] = session('reservation_info');
            return view('payment_new', $data);
        } else {
            return Redirect('payment_new');
        }
    }
    public function payment(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $payment->capture(array('amount'=>$payment['amount']));
            } catch (\Exception $e) {
                return  $e->getMessage();
                session()->put('error',$e->getMessage());
                return redirect()->back();
            }
        }
        $payInfo = [
                   'payment_id' => $request->razorpay_payment_id,
                   'user_id' => '1',
                   'amount' => $request->amount,
                ];
        Payment::insertGetId($payInfo);  
        session()->put('success', 'Payment successful');
        return response()->json(['success' => 'Payment successful']);
    }

    /**
     * Store a newly reservation info in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request) {
        /* All Required Parameters by your Gateway */
        $payment_id = $request->razorpay_payment_id;
        
        /*$api = new Api(config('app.razorpay_api_key'), config('app.seceret_key'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($payment_id);
        if (!empty($payment) && $payment['status'] == 'captured') {
            $paymentId = $payment['id'];
            $amount = $payment['amount'];
            $currency = $payment['currency'];
            $status = $payment['status'];
            $entity = $payment['entity'];
            $orderId = $payment['order_id'];
            $invoiceId = $payment['invoice_id'];
            $method = $payment['method'];
            $bank = $payment['bank'];
            $wallet = $payment['wallet'];
            $bankTranstionId = isset($payment['acquirer_data']['bank_transaction_id']) ? $payment['acquirer_data']['bank_transaction_id'] : '';
        } else {
            echo json_encode(array("error"=> "Something went wrong, Please try again later!"));
        }
        try {
            // Payment detail save in database
            $payment = new Payment;
            $payment->transaction_id = $paymentId;
            $payment->amount = $amount / 100;
            $payment->currency = $currency;
            $payment->entity = $entity;
            $payment->status = $status;
            $payment->order_id = $orderId;
            $payment->method = $method;
            $payment->bank = $bank;
            $payment->wallet = $wallet;
            $payment->bank_transaction_id = $bankTranstionId;
            $saved = $payment->save();
        } catch (Exception $e) {
            $saved = false;
        }
        if ($saved) {
            return redirect()->back()->with('success', __('Payment Detail store successfully!'));
        } else {
            return back()->withInput()->with('error', __('Something went wrong, Please try again later!'));
        }*/
        
        $experienceId = session('experience_info')['exp_id'];
        $exp_accomodation_id = session('experience_info')['exp_acm_id'];
        $exp_booking_date = session('experience_info')['exp_booking_date'];
        if ($experienceId) {
            session(['experience_id' => $experienceId, 'exp_accomodation_id' => $exp_accomodation_id]);
            $exParam['where'] = array("id" => $experienceId);
            $experience = \App\Experiences::get_data($exParam);
            $data['experience'] = (@$experience[0]) ? : array();

            $param = array("where" => array("id" => $data['experience']->center_id), "limit" => 1);
            $data['ecenter'] = \App\Centers::get_data($param);
            $data['center'] = (@$data['ecenter'][0]) ? : array();

            if ($exp_accomodation_id) {

                $arexp_booking_date = explode(" - ", @$exp_booking_date);
                $exp_booking_start = @$arexp_booking_date[0];
                $exp_booking_end = @$arexp_booking_date[1];

                $experience_accomodations = \App\Experiences::get_exp_acm_data($data['experience']->id, $exp_accomodation_id, @$exp_booking_start);
                $data['experience_accomodations'] = (!empty($experience_accomodations[0]) ? $experience_accomodations[0] : array());
            }

            $discount = 0;

            $pay = @$data['experience_accomodations']->room_price;
            // Early Bird Discount
            /*if ((!empty(@$data['experience']->eirly_bird_before_days)) && (!empty(@$data['experience']->eirly_bird_discount)) && (@$data['experience']->eirly_bird_discount > 0)) {
                if (@$data['experience']->eirly_bird_discount_type == "amt") {
                    $discount += @$data['experience']->eirly_bird_discount;
                } else {
                    $discount = (@$pay * @$data['experience']->eirly_bird_discount) / 100;
                }
            }

            if ((!empty(@$data['experience']->offer_start_date)) && (!empty(@$data['experience']->offer_discount)) && (@$data['experience']->offer_discount > 0)) {
                $now = \Carbon\Carbon::now()->format("Y-m-d");
                if ((\Carbon\Carbon::parse(@$data['experience']->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$data['experience']->offer_end_date)->format("Y-m-d") >= $now)) {
                    if (@$data['experience']->offer_discount_type == "amt") {
                        $discount += @$data['experience']->offer_discount;
                    } else {
                        $discount += (@$pay * @$data['experience']->offer_discount) / 100;
                    }
                }
            }*/

            $bookingAmount = $pay - $discount;
            $payAmount = $pay - $discount;

            // Calculate Commission
            $commission = 0;
            //if (!empty(@$data['experience']->commission) && (@$data['experience']->commission > 0)) {
            /*$commission = ($payAmount * @$data['experience']->commission) / 100;
            if (!empty(@$data['experience']->deposit_policy)) {
                switch (@$data['experience']->deposit_policy) {
                    case "2" :
                        // If Fixed Amount
                        $deposit_amount = @$data['experience']->deposit_amount;
                        $payAmount = @$data['experience']->deposit_amount;
                        break;
                    case "3" :
                        // If Percentage
                        $deposit_amount = (@$payAmount * @$data['experience']->deposit_amount) / 100;
                        $payAmount = $deposit_amount;
                        break;
                    default:
                        $deposit_amount = @$payAmount;
                        $booking_amount = $deposit_amount;
                        break;
                }
            }*/
            //}
            $booking_amount = $payAmount;
            $deposit_amount = $payAmount;

            // Insert Booking Info
            
            $arexp_booking_date = explode(" - ", session('experience_info')['exp_booking_date']);
            $exp_booking_start = @$arexp_booking_date[0];
            $exp_booking_end = @$arexp_booking_date[1];
            
            $objBooking = new \App\Bookings();
            $objBooking->experience_id = @$data['experience']->id;
            $objBooking->experience_accomodation_id = @$data['experience_accomodations']->id;
            $objBooking->duration = session('experience_info')['exp_booking_durations'];
            $objBooking->price_per_person = @$data['experience_accomodations']->room_price;
            $objBooking->booking_amount = session('experience_info')['exp_booking_deposit_amount'];
            $objBooking->discount_amount = session('experience_info')['exp_discount'];
            $objBooking->commission_amount = session('experience_info')['exp_commission'];
            $objBooking->pay_amount = session('experience_info')['exp_booking_pay_amount'];
            $objBooking->arrival_date = (!empty(@session('reservation_info')['arrival_date'])) ? \Carbon\Carbon::parse(trim(@session('reservation_info')['arrival_date']))->format("Y-m-d") : "";
            $objBooking->start_date_time = (!empty(@$exp_booking_start)) ? \Carbon\Carbon::parse(trim(@$exp_booking_start))->format("Y-m-d H:i:s") : "";
            $objBooking->end_date_time = (!empty(@$exp_booking_end)) ? \Carbon\Carbon::parse(trim(@$exp_booking_end))->format("Y-m-d H:i:s") : "";
            $objBooking->currency = @$data['experience_accomodations']->currency;
            
            $objCurrency = \App\Currency::select("rate")->where("name", @$data['experience_accomodations']->currency)->first();
            $objBooking->currency_rate = $objCurrency->rate;
            
            $objBooking->booking_currency = \App\Http\Helpers\CommonHelper::get_site_currency();
            $objBooking->booking_currency_rate = \App\Http\Helpers\CommonHelper::get_site_currency_rate();
            $objBooking->transaction_id = @$payment_id;
            $objBooking->user_id = (Auth::hasUser()) ? Auth::user()->id : "";
            $objBooking->save();
            $bookingId = $objBooking->id;
    
            // Insert Booking Exp Info
            $objBookingExperienceInfo = new \App\BookingExperienceInfo();
            $objBookingExperienceInfo->booking_id = @$bookingId;
            $objBookingExperienceInfo->experience_id = @$data['experience']->id;
            $objBookingExperienceInfo->name = @$data['experience']->name;
            $objBookingExperienceInfo->slug = @$data['experience']->slug;
            $objBookingExperienceInfo->center_id = @$data['experience']->center_id;
            $objBookingExperienceInfo->price_per_person = @$data['experience']->price_per_person;
            $objBookingExperienceInfo->currency = @$data['experience']->currency;
            $objBookingExperienceInfo->date_time = @$data['experience']->date_time;
            $objBookingExperienceInfo->start_date_time = @$data['experience']->start_date_time;
            $objBookingExperienceInfo->end_date_time = @$data['experience']->end_date_time;
            $objBookingExperienceInfo->is_full_day_event = @$data['experience']->is_full_day_event;
            $objBookingExperienceInfo->is_recurring = @$data['experience']->is_recurring;
            $objBookingExperienceInfo->deposit_policy = @$data['experience']->deposit_policy;
            $objBookingExperienceInfo->deposit_amount = @$data['experience']->deposit_amount;
            $objBookingExperienceInfo->cancellation_policy_condition = @$data['experience']->cancellation_policy_condition;
            $objBookingExperienceInfo->cancellation_policy_days = @$data['experience']->cancellation_policy_days;
            $objBookingExperienceInfo->rest_of_payment = @$data['experience']->rest_of_payment;
            $objBookingExperienceInfo->rest_of_payment_days = @$data['experience']->rest_of_payment_days;
            $objBookingExperienceInfo->commission = @$data['experience']->commission;
            $objBookingExperienceInfo->tax = @$data['experience']->tax;
            $objBookingExperienceInfo->duration = @$data['experience']->duration;
            $objBookingExperienceInfo->eirly_bird_before_days = @$data['experience']->eirly_bird_before_days;
            $objBookingExperienceInfo->eirly_bird_discount = @$data['experience']->eirly_bird_discount;
            $objBookingExperienceInfo->save();
    
            // Insert Booking User Info
            $objBookingUserInfo = new \App\BookingUserInfo();
            $objBookingUserInfo->booking_id = @$bookingId;
            $objBookingUserInfo->firstname = @session('reservation_info')['firstname'];
            $objBookingUserInfo->lastname = @session('reservation_info')['lastname'];
            $objBookingUserInfo->email = @session('reservation_info')['email'];
            $objBookingUserInfo->phone = @session('reservation_info')['phone'];
            $objBookingUserInfo->message = @session('reservation_info')['message'];
            $objBookingUserInfo->save();
            session(['booking_id' => $bookingId]);
        
            if($data['experience']->center_id != 2293 && $data['experience']->is_bookable == 1) {
                //$this->response($payment_id,$bookingId);
                
                session()->forget('reservation_info');
                session()->forget('experience_info');
                session()->get('success', 'Payment successful');
                
                
                $data['booking'] = $objBooking;
                $data['user'] = $objBookingUserInfo;
                
                // Mail Send to Admin
                Mail::send('emails.booking.admin', $data, function ($message) {
                    $message->subject("New Booking From BalanceBoat");
                    $message->to('kunal@balanceboat.com', 'Balanceboat');
                    $message->to('shivam@balanceboat.com', 'Balanceboat');
                });
                
                // Mail Send To Customer
                $customerEmail = @$objBookingUserInfo->email;
                if(@$customerEmail) {
                    Mail::send('emails.booking.customer', $data, function ($message) use($customerEmail) {
                        $message->subject("Booking Information");
                        $message->to($customerEmail);
                    });
                }
                
                echo json_encode(array("success"=> "Your experience has been booked Successfully!", "booking_id"=>$bookingId));
                exit;
            } else {
                session()->get('error', 'Payment Failed');
                echo json_encode(array("error"=> "Your experience booking Failed!"));
                exit;
            }
        }
        echo json_encode(array("error"=> "Something went wrong, Please try again later!"));
        exit;
    }

    public function response($payment_id,$bookingId) {
        // session()->forget('reservation_info');
        // session()->forget('experience_info');
        // For default Gateway
        // $response = Indipay::response($request);
        // if (!empty(@$response) || (sizeof(@$response) > 0)) {
            // try {
                // Insert Booking Transaction Info
                /*$objBookingTransactionInfo = new \App\BookingTransactionInfo();
                $objBookingTransactionInfo->booking_id = @$bookingId;;
                $objBookingTransactionInfo->tracking_id = @$payment_id;
                $objBookingTransactionInfo->bank_ref_no = @session('reservation_info')["bank_ref_no"];
                $objBookingTransactionInfo->order_status = @session('reservation_info')["order_status"];
                $objBookingTransactionInfo->failure_message = @session('reservation_info')["failure_message"];
                $objBookingTransactionInfo->payment_mode = @$payment_method;
                $objBookingTransactionInfo->card_name = @session('reservation_info')["card_name"];
                $objBookingTransactionInfo->status_code = @session('reservation_info')["status_code"];
                $objBookingTransactionInfo->status_message = @session('reservation_info')["status_message"];
                $objBookingTransactionInfo->currency = @session('reservation_info')["currency"];
                $objBookingTransactionInfo->amount = @session('reservation_info')["amount"];
                $objBookingTransactionInfo->vault = @session('reservation_info')["vault"];
                $objBookingTransactionInfo->offer_type = @session('reservation_info')["offer_type"];
                $objBookingTransactionInfo->offer_code = @session('reservation_info')["offer_code"];
                $objBookingTransactionInfo->discount_value = @session('reservation_info')["discount_value"];
                $objBookingTransactionInfo->mer_amount = @session('reservation_info')["mer_amount"];
                $objBookingTransactionInfo->eci_value = @session('reservation_info')["eci_value"];
                $objBookingTransactionInfo->retry = @session('reservation_info')["retry"];
                $objBookingTransactionInfo->response_code = @session('reservation_info')["response_code"];
                $objBookingTransactionInfo->billing_notes = @session('reservation_info')["billing_notes"];
                $objBookingTransactionInfo->trans_date = (!empty(@session('reservation_info')["trans_date"]) && (@session('reservation_info')["trans_date"] != "null" )) ? \Carbon\Carbon::parse(@session('reservation_info')["trans_date"])->format("Y-m-d H:i:s") : "";
                $objBookingTransactionInfo->save();

                // Insert Booking Transaction Address Info
                $objBookingTransactionAddressInfo = new \App\BookingTransactionAddressInfo();
                $objBookingTransactionAddressInfo->booking_id = @session('reservation_info')["order_id"];
                $objBookingTransactionAddressInfo->billing_name = @session('reservation_info')["billing_name"];
                $objBookingTransactionAddressInfo->billing_address = @session('reservation_info')["billing_address"];
                $objBookingTransactionAddressInfo->billing_city = @session('reservation_info')["billing_city"];
                $objBookingTransactionAddressInfo->billing_state = @session('reservation_info')["billing_state"];
                $objBookingTransactionAddressInfo->billing_zip = @session('reservation_info')["billing_zip"];
                $objBookingTransactionAddressInfo->billing_country = @session('reservation_info')["billing_country"];
                $objBookingTransactionAddressInfo->billing_tel = @session('reservation_info')["billing_tel"];
                $objBookingTransactionAddressInfo->billing_email = @session('reservation_info')["billing_email"];
                $objBookingTransactionAddressInfo->delivery_name = @session('reservation_info')["delivery_name"];
                $objBookingTransactionAddressInfo->delivery_address = @session('reservation_info')["delivery_address"];
                $objBookingTransactionAddressInfo->delivery_city = @session('reservation_info')["delivery_city"];
                $objBookingTransactionAddressInfo->delivery_state = @session('reservation_info')["delivery_state"];
                $objBookingTransactionAddressInfo->delivery_zip = @session('reservation_info')["delivery_zip"];
                $objBookingTransactionAddressInfo->delivery_country = @session('reservation_info')["delivery_country"];
                $objBookingTransactionAddressInfo->delivery_tel = @session('reservation_info')["delivery_tel"];
                $objBookingTransactionAddressInfo->save();*/

                // $objBooking = \App\Bookings::find(@session('reservation_info')["order_id"]);
                // if (!empty($objExperience) || sizeof($objBooking) > 0) {
                //     $objBooking->order_status = @session('reservation_info')["order_status"];
                //     $objBooking->transaction_id = @session('reservation_info')["tracking_id"];
                //     $objBooking->payment_status = @session('reservation_info')["status_code"];
                //     $objBooking->save();
                // }

                // if (@session('reservation_info')["order_status"] != "Success") {
                //     return redirect('payment/cancel')
                //                     ->with('flash_error_message', 'Booking Failed');
                // }
            // } catch (Exception $ex) {
            //     return redirect('payment/cancel')
            //                     ->with('flash_error_message', 'Booking Failed');
            // }
            session()->get('success', 'Payment successful');
            echo json_encode(array("success"=> "Your experience has been booked Successfully!"));
           // return redirect('/payment_new')->with('success', 'Your experience has been booked Successfully!');
        // } else {
        //     return redirect('payment/cancel')
        //                     ->with('flash_error_message', 'Booking Failed');
        // }
    }

    public function success(Request $request) {
        $data = array();
        $bookingId = request()->filled('booking_id') ? request()->input('booking_id') : '';
        if ($bookingId) {
            try {
                $data['booking'] = \App\Bookings::where("id", $bookingId)->first();
                $data['experience'] = \App\Experiences::where("id", $data['booking']->experience_id)->first();
                return view('payment-success', $data);
            } catch (\Exception $e) {
                return Redirect("/");
            }
        } else {
            return Redirect("/");
        }
    }

    public function cancel(Request $request) {
        $data = array();
        return view('payment-cancel', $data);
    }

}
