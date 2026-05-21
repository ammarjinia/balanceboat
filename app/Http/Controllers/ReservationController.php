<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use Auth;
use Validator;

//use App\User;

class ReservationController extends Controller {

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
        $experienceId = (@$request["hdn_experience_id"]) ? $request["hdn_experience_id"] : ((!empty(Session('experience_info')['exp_id'])) ? Session('experience_info')['exp_id'] : "");
        $exp_accomodation_id = (@$request["exp_accomodation_id"]) ? $request["exp_accomodation_id"] : ((!empty(Session('experience_info')['exp_acm_id']) && (Session('experience_info')['exp_acm_id'])) ? Session('experience_info')['exp_acm_id'] : "");
        $exp_booking_date = (@$request["booking_date"]) ? $request["booking_date"] : ((!empty(Session('experience_info')['exp_booking_date']) && (Session('experience_info')['exp_booking_date'])) ? Session('experience_info')['exp_booking_date'] : "");
        $days = (@$request["durations"]) ? $request["durations"] : ((!empty(Session('experience_info')['exp_booking_durations']) && (Session('experience_info')['exp_booking_durations'])) ? Session('experience_info')['exp_booking_durations'] : "");
        
        $data['exp_booking_durations'] = @$days;
        if ($experienceId) {
            $exParam['where'] = array("id" => $experienceId);
            $experiences = \App\Experiences::get_data($exParam);
            $experience = (@$experiences[0]) ? @$experiences[0] : array();
            $data['experience'] = @$experience;

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
            
            session(
                    ['experience_info' => [
                            'exp_id' => $experienceId,
                            'exp_acm_id' => $exp_accomodation_id,
                            'exp_acm_price' => @$experience_accomodation->room_price,
                            'exp_cancellation_policy_condition' => @$data['experience']->cancellation_policy_condition,
                            'exp_cancellation_policy_days' => @$data['experience']->cancellation_policy_days,
                            
                            'exp_discount' => @$discount,
                            'exp_commission' => @$commission,
                            'exp_booking_date' => @$exp_booking_date,
                            'exp_booking_durations' => @$days,
                            'exp_booking_deposit_amount' => @$booking_amount,
                            'exp_booking_pay_amount' => @$data['accomodation_price']??0,
                            //'exp_booking_duration_price' => @$data['exp_booking_duration_price']
                        ]
            ]);
            $data['user_info'] = Auth::user();
            return view('reservation', $data);
        } else {
            return Redirect('experiences');
        }
    }

    /**
     * Store a newly reservation info in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'arrival_date' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'phone' => 'required',
                    'email' => 'required|email'
        ]);

        if (!Auth::check()) {
            if (!empty($request['password'])) {
                $validator->after(function ($validator) use ($request) {
                    $credentials = array("email" => $request['email'], 'password' => $request['password']);
                    if (!Auth::attempt($credentials)) {
                        $validator->errors()->add('password', 'Wrong password. Try again');
                    }
                });
            }
        }

        if ($validator->fails()) {
            return redirect('/reservation')
                            ->withErrors($validator)
                            ->withInput();
        }

        session(['reservation_info' => [
                "firstname" => $request['firstname'],
                "lastname" => $request['lastname'],
                "email" => $request['email'],
                "phone" => $request['phone'],
                "message" => $request['message'],
                "arrival_date" => $request['arrival_date']
            ]
        ]);
        return Redirect('payment');
    }
    
    /**
     * Reservation Success
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request) {
        $data = array();
        return view('request-success', $data);
    }

}
