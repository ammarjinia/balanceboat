<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

//use App\User;

class BookingController extends Controller {

    public function __construct() {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($parent = '', $subcat = '') {
        $data = array();
        $userId = Auth::user()->id;
        $resExp = \App\Bookings::select("experiences.*","bookings.*" ,"bookings.id as booking_id")
                        ->join("experiences", "experiences.id", "=", "bookings.experience_id")
                        ->where("bookings.user_id", $userId)->get();
        $data['experiences'] = $resExp;
        return view('booking/mybookings', $data);
    }

    /**
     * Display a Detail of the Booking.
     *
     * @return \Illuminate\Http\Response
     */
    public function booking($booking_id) {
        $data = array();
        $userId = Auth::user()->id;

        $objBooking = \App\Bookings::where("id", $booking_id)->where("user_id", $userId)->first();
        $data['booking'] = $objBooking;

        $objBookingExp = \App\BookingExperienceInfo::select("experiences.*")
                        ->join("experiences", "experiences.slug", "=", "booking_experience_info.slug")
                        ->where("booking_id", $booking_id)->first();
        $data['booking_experience'] = $objBookingExp;

        $data['booking_experience_accm'] = array();
        $data['booking_user_info'] = array();
        $data['booking_transaction'] = array();
        // if (sizeof($data['booking']) > 0) {
            $objBookingExpAcm = \App\Accomodation::where("id", @$data['booking']->experience_accomodation_id)->first();
            $data['booking_experience_accm'] = $objBookingExpAcm;

            $objBookingUserInfo = \App\BookingUserInfo::where("booking_id", @$booking_id)->first();
            $data['booking_user_info'] = $objBookingUserInfo;

            $objBookingTransaction = \App\BookingTransactionInfo::select("payment_mode")->where("booking_id", @$booking_id)->first();
            $data['booking_transaction'] = $objBookingTransaction;
        // }
        return view('booking/booking_details', $data);
    }

}
