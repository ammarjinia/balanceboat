<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Bookings;
use Storage;
use DB;
use Auth;

class BookingsController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $param['order'] = "DESC";
        $param['orderby'] = "id";
        $data['bookings'] = Bookings::select("bookings.id", "bookings.start_date_time", "bookings.end_date_time", "experiences.id as exp_id", "experiences.name", "bookings.created_at", "users.id as user_id", "users.first_name as user_first_name", "users.last_name as user_last_name")->with('userInfo:id,firstname,lastname,booking_id')
                ->Join("experiences", "experiences.id", "bookings.experience_id")
                ->leftJoin("users", "bookings.user_id", "users.id")
                ->orderBy("bookings.created_at", "DESC")
                ->get();
        return view("admin.bookings.index", $data);
    }

    /**
     * Show the form for creating a new Bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        return view('admin.bookings.create', $data);
    }

    /**
     * Store a newly created Bookings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {

            $objExp = \App\Experiences::where("id", $request->experience_id)->first();
            $objExpAcm = \App\ExperienceAccomodations::where("id", $request->experience_accomodation_id)->first();
            
            
            $objBooking = new \App\Bookings();
            $objBooking->experience_id = @$objExp->id;
            $objBooking->experience_accomodation_id = $request->input('experience_accomodation_id',null);
            $objBooking->duration = $request->input('duration',null);
            $objBooking->price_per_person = @$objExpAcm->room_price;
            $objBooking->booking_amount = $request->input('booking_amount',null);
            //$objBooking->discount_amount = @$objExp->discount;
            //$objBooking->commission_amount = session('experience_info')['exp_commission'];
            $objBooking->pay_amount = $request->input('paid_amount',null);
            $objBooking->arrival_date = $request->filled('arrival_date') ? \Carbon\Carbon::parse($request->input('arrival_date'))->format("Y-m-d") : null;
            $objBooking->start_date_time = $request->filled('booking_date') ? \Carbon\Carbon::parse($request->input('booking_date'))->format("Y-m-d") : null;
            //$objBooking->end_date_time = (!empty(@$exp_booking_end)) ? \Carbon\Carbon::parse(trim(@$exp_booking_end))->format("Y-m-d H:i:s") : "";
            //$objBooking->currency = @$data['experience_accomodations']->currency;
            
            //$objCurrency = \App\Currency::select("rate")->where("name", @$data['experience_accomodations']->currency)->first();
            //$objBooking->currency_rate = $objCurrency->rate;
            
            //$objBooking->booking_currency = \App\Http\Helpers\CommonHelper::get_site_currency();
            //$objBooking->booking_currency_rate = \App\Http\Helpers\CommonHelper::get_site_currency_rate();
            //$objBooking->transaction_id = @$payment_id;
            $objBooking->user_id = Auth::user()->id;
            $objBooking->save();
            $bookingId = $objBooking->id;
    
            // Insert Booking Exp Info
            $objBookingExperienceInfo = new \App\BookingExperienceInfo();
            $objBookingExperienceInfo->booking_id = @$bookingId;
            $objBookingExperienceInfo->experience_id = @$objExp->id;
            $objBookingExperienceInfo->name = @$objExp->name;
            $objBookingExperienceInfo->slug = @$objExp->slug;
            $objBookingExperienceInfo->center_id = @$objExp->center_id;
            $objBookingExperienceInfo->price_per_person = @$objExp->price_per_person;
            $objBookingExperienceInfo->currency = @$objExp->currency;
            $objBookingExperienceInfo->date_time = @$objExp->date_time;
            $objBookingExperienceInfo->start_date_time = @$objExp->start_date_time;
            $objBookingExperienceInfo->end_date_time = @$objExp->end_date_time;
            $objBookingExperienceInfo->is_full_day_event = @$objExp->is_full_day_event;
            $objBookingExperienceInfo->is_recurring = @$objExp->is_recurring;
            $objBookingExperienceInfo->deposit_policy = @$objExp->deposit_policy;
            $objBookingExperienceInfo->deposit_amount = @$objExp->deposit_amount;
            $objBookingExperienceInfo->cancellation_policy_condition = @$objExp->cancellation_policy_condition;
            $objBookingExperienceInfo->cancellation_policy_days = @$objExp->cancellation_policy_days;
            $objBookingExperienceInfo->rest_of_payment = @$objExp->rest_of_payment;
            $objBookingExperienceInfo->rest_of_payment_days = @$objExp->rest_of_payment_days;
            $objBookingExperienceInfo->commission = @$objExp->commission;
            $objBookingExperienceInfo->tax = @$objExp->tax;
            $objBookingExperienceInfo->duration = @$objExp->duration;
            $objBookingExperienceInfo->eirly_bird_before_days = @$objExp->eirly_bird_before_days;
            $objBookingExperienceInfo->eirly_bird_discount = @$objExp->eirly_bird_discount;
            $objBookingExperienceInfo->save();
    
            // Insert Booking User Info
            $objBookingUserInfo = new \App\BookingUserInfo();
            $objBookingUserInfo->booking_id = @$bookingId;
            $objBookingUserInfo->firstname = $request->input('firstname',null);
            $objBookingUserInfo->lastname = $request->input('lastname',null);
            $objBookingUserInfo->email = $request->input('email',null);
            $objBookingUserInfo->phone = $request->input('phone',null);
            $objBookingUserInfo->message = $request->input('message',null);
            $objBookingUserInfo->save();

        } catch (Exception $e) {
            return redirect('bbadmin/bookings')
                            ->with('flash_error_message', 'Something went wrong');
        }

        return redirect('bbadmin/bookings')
                            ->with('flash_message', 'Booking created');
    }

    /**
     * Show the form for ediing a Bookings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/bookings");
        }

        $param = array("where" => array("id" => $id), "limit" => 1);
        $param['order'] = "DESC";
        $param['orderby'] = "id";
        $objbooking = Bookings::get_data($param);

        $data['ebooking'] = $data['booking_user_info'] = $data['booking_experience'] = $data['booking_center_info'] = $data['center'] = $data['booking_transaction_info'] = array();
        if (sizeof($objbooking) > 0) {
            $data['ebooking'] = $objbooking[0];
            $data['booking_user_info'] = \App\BookingUserInfo::where("booking_id", $data['ebooking']->id)->first();
            
            $data['experience'] = \App\Experiences::where("id", $data['ebooking']->experience_id)->first();  

            $data['center'] = \App\Centers::where("id", $data['experience']->center_id)->first();          
            
            $data['booking_experience'] = \App\BookingExperienceInfo::where("experience_id", $data['ebooking']->experience_id)
                    ->where("booking_id", $data['ebooking']->id)
                    ->first();

            $data['accommodation'] = \App\Accomodation::where("id", $data['ebooking']->experience_accomodation_id)->first();
            
            $data['booking_center_info'] = \App\BookingUserInfo::where("booking_id", $data['ebooking']->id)->first();
            
            $data['booking_transaction_info'] = \App\BookingTransactionInfo::where("booking_id", $data['ebooking']->id)->first();
        }
        return view('admin.bookings.edit', $data);
    }

    public function getExperienceDetails(Request $request)
    {
        $experienceId = $request->get('experience_id');

        // Get durations
        $durations = \App\ExperienceDurationPrices::select("id","duration")->where('experience_id', $experienceId)
            ->get();

        // Get accommodations (using your model method)
        $accommodations = \App\Experiences::get_exp_acm_data($experienceId);

        return response()->json([
            'durations' => $durations,
            'accommodations' => $accommodations,
        ]);
    }

}
