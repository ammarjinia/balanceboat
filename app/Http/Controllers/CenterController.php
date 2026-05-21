<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\User;

class CenterController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = '') {
        $data = array();
        if ($slug) {
            $cParam['where'] = array("slug" => $slug);
            if (!isset($_GET['preview']) or ( $_GET['preview'] != 1)) {
                $cParam['where']["is_draft"] = 0;
            }
            $center = \App\Centers::get_data($cParam);
            $data['center'] = (@$center[0]) ? : array();

            if (@$data['center']) {

                $paramCRT['order'] = "ASC";
                $paramCRT['orderby'] = "name";
                $data['certificates'] = \App\Certificates::get_data($paramCRT);

                // Get Center Type
                $data['center_type'] = "";
                if ($data['center']->center_type) {
                    $paramCT['order'] = "ASC";
                    $paramCT['orderby'] = "id";
                    $paramCT['where'] = array("id" => $data['center']->center_type);
                    $data['center_type'] = \App\CenterTypes::get_data($paramCT)[0]->name;
                }

                // Get Center Gallery Images
                $paramCGI['select'] = array('id', 'center_id', 'image_url', 'image_title','bg_exp_id');
                $paramCGI['where'] = array("center_id" => $data['center']->id);
                $data['imagegalleries'] = \App\CenterImageGallery::get_data($paramCGI);

                // Get Center Accomodations
                $data['center_accomodations'] = \App\CenterAccomodations::select("accomodation_id")
                                ->where("center_id", $data['center']->id)->get();

                $data['accomodationimagegalleries'] = array();
                if (sizeof($data['center_accomodations']) > 0) {
                    $acmIds = array();
                    foreach ($data['center_accomodations'] as $ca_acm_image) {
                        array_push($acmIds, $ca_acm_image->accomodation_id);
                    }

                    if ($acmIds) {
                        // Get Accomodation Gallery Images
                        $data['accomodationimagegalleries'] = \App\AccomodationImageGallery::select('id', 'accomodation_id', 'image_url', 'image_title')->whereIn("accomodation_id", $acmIds)->get();
                    }
                }

                // Get Center Specialities
                $data['center_specialities'] = array();
                if (!empty($data['center']->speciality_id)) {
                    $data['center_specialities'] = \App\Expertise::select('name')->whereIn("id", explode("||", $data['center']->speciality_id))->get();
                }

                // Get experience Teachers
                $data['experience_teachers'] = \App\Teachers::select("teachers.id", "teachers.name", "teachers.profile_image_url", "teachers.short_description", "teachers.slug", "teachers.certificate_id")
                                ->join("center_teachers", "teachers.id", "=", "center_teachers.teacher_id")
                                ->where("center_teachers.center_id", $data['center']->id)->distinct()->get();

                // Get Center Locations
                $data['center_locations'] = \App\Category::select("category.*")
                                ->join("center_locations", "center_locations.location_id", "=", "category.id")
                                ->where("center_id", $data['center']->id)->get();

                // Get Center Experiences
                $cnd = ' AND e.center_id = "' . @$data['center']->id . '" ';
                $data["center_experiences"] = \App\Experiences::get_exp_deal_price_data($cnd, 'e.updated_at', 'DESC', '100');
                return view('center_detail', $data);
            } else {
                return redirect("/experiences");
            }
        } else {
            $data = array();
            $order = "DESC";
            $orderby = "updated_at";
            if (@$_GET['sort_by']) {
                switch (@$_GET['sort_by']) {
                    case "recent-asc":
                        $order = "ASC";
                        $orderby = "created_at";
                        break;
                    case "popular-asc":
                        $order = "DESC";
                        $orderby = "updated_at";
                        break;
                    case "rating-asc":
                        $order = "ASC";
                        $orderby = "updated_at";
                        break;
                    case "price-asc":
                        $order = "DESC";
                        $orderby = "created_at";
                        break;
                    default :
                        $order = "ASC";
                        $orderby = "updated_at";
                        break;
                }
            }
            $limit = "10";
            $data['experiences'] = \App\Centers::get_data($orderby, $order,$limit);
            return view('centers', $data);
        }
    }
    /***
     * Get Booking Dates and Accomodation filter values dynamically based on Experience
     */
    public function get_ajax_filter_values(Request $request){
        $experience_id = $request->input("exp_id");
        $data=array();
        $data['experience_durations'] = '';
        $data['experience_accomodations_options'] = '';
        $data['experience_dates']='';
        $data['experience_date_type']='';
        if(!empty($experience_id) && $experience_id!=null){
            $exParam['where'] = array("id" => $experience_id);
            $experience = \App\Experiences::get_data($exParam);
            if(!empty($experience)){
                $experience=$experience[0];
                
                #### Get Days ####
                $experience_durations = \App\ExperienceDurationPrices::where("experience_id", $experience_id)->get();
                if(@$experience_durations or @$experience->duration) {
                    //$data['experience_durations'] .= "<option value='".(int)@$experience->duration."' data-price='0' selected>".(int)@$experience->duration."</option>";
                    if(@$experience_durations) {
                        foreach($experience_durations as $experience_duration) {
                            //if (trim($experience_duration->duration) != trim((int)@$experience->duration)) {
                                $data['experience_durations'] .= "<option value='".$experience_duration->duration."' data-price='".(@$experience_duration->price)."'>".$experience_duration->duration."</option>";
                            //}
                        }
                    }
                }
                
                #### Get Accomodations #####
                
                $experience_accomodations = \App\Experiences::get_exp_acm_data($experience_id);
                
                if(sizeof($experience_accomodations)>0){
                    foreach($experience_accomodations as $exp_acd){
                        $data['experience_accomodations_options'] .="<option value='".$exp_acd->id."'>".$exp_acd->name ."</option>";       
                    }
                }

                ##### Get Booking Dates #####
                $data['experience_recurring'] = array();
                
                if ($experience->is_recurring == 1) {
                    $data['experience_recurring_manually'] = \App\ExperienceRecurringManually::where("experience_id", $experience_id)->where("start_date", ">", \Carbon\Carbon::now())->get();
                    $data['experience_recurring'] = \App\ExperienceRecurring::where("experience_id", $experience_id)->first();
                }
                
                if(!empty($data['experience_recurring']) && $data['experience_recurring']->recurring_type == 'Daily'){
                    $data['experience_date_type']='daily';
                }else{
                    if(\Carbon\Carbon::parse($experience->start_date_time)->subDays(2) >=  \Carbon\Carbon::now()){
                        $temp_val='';
                        $temp_val .=($experience->start_date_time) ? \Carbon\Carbon::parse($experience->start_date_time)->format("d M, Y") : "";
                        $temp_val .=($experience->end_date_time) ? " - ".\Carbon\Carbon::parse($experience->end_date_time)->format("d M, Y") : "";

                        $data['experience_dates'] .="<option value='". ($experience->start_date_time." - ". $experience->end_date_time)."'>". $temp_val ."</option>";
                    }
                    if(@$data['experience_recurring_manually']){
                        foreach(@$data['experience_recurring_manually'] as $exp_recurr){
                            $temp_val='';
                            $temp_val .= ($exp_recurr->start_date) ? \Carbon\Carbon::parse($exp_recurr->start_date)->format("d M, Y") : "" ;
                            $temp_val .=  ($exp_recurr->end_date) ? " - ".\Carbon\Carbon::parse($exp_recurr->end_date)->format("d M, Y") : "" ;
                            $data['experience_dates'] .="<option value='".($exp_recurr->start_date." - ". @$exp_recurr->end_date)."'>".$temp_val. "</option>"; 
                        }
                    }
                }
            }
        }
        echo json_encode($data);
    }
   /***
     * Get Booking Price from selected Experiencec, date and accomodation
     */
    public function get_booking_price(Request $request){
        $experience_id = !empty($request->input("exp_id"))?$request->input("exp_id"):'';
        $booking_date = !empty($request->input("booking_date"))?$request->input("booking_date"):'';
        $acm_id = !empty($request->input("acc_id"))?$request->input("acc_id"):'';
        $days = !empty($request->input("days"))?$request->input("days"):0;
        $data=array();
        $data['accomodation_html_price']='';
        $data['accomodation_price']='';
        if(!empty($experience_id) && !empty($booking_date) && !empty($acm_id)){
            /*$arexp_booking_date = explode(" - ", $booking_date);
            $exp_booking_start = \Carbon\Carbon::parse(@$arexp_booking_date[0])->format("Y-m-d");
            $exp_booking_end = \Carbon\Carbon::parse(@$arexp_booking_date[1])->format("Y-m-d");*/

            
            $exp_booking_start = \Carbon\Carbon::parse(@$booking_date)->format("Y-m-d");
            $exp_booking_end = \Carbon\Carbon::parse(@$booking_date)->addDays((int)$days)->format("Y-m-d");
                        
            $experience_accomodations = \App\Experiences::get_exp_acm_data($experience_id,$acm_id, $exp_booking_start, $exp_booking_end, $days);
            if (sizeof(@$experience_accomodations) > 0) {
                $exParam['where'] = array("id" => $experience_id);
                $exParam['where']["is_draft"] = 0;
                $experience = \App\Experiences::get_data($exParam);
                $experience = (@$experience[0]) ? @$experience[0] : array();

                $experience_accomodation=$experience_accomodations[0];
                $discount = 0;
                $experience_accomodation->htmlPrice = "";
                $pay = @$experience_accomodation->room_price;
                $org_price = @$experience_accomodation->price_per_night_per_guest;
                if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                    if (@$experience->eirly_bird_discount_type == "amt") {
                        $discount += @$experience->eirly_bird_discount;
                    } else {
                        $discount += (@$pay * @$experience->eirly_bird_discount) / 100;
                    }
                }

                if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                    //$now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                    $now = $exp_booking_start;
                    if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $now) && $pay > 0) {
                        if (@$experience->offer_discount_type == "amt") {
                            $discount += @$experience->offer_discount;
                        } else {
                            $discount += (@$pay * @$experience->offer_discount) / 100;
                        }
                    }
                }

                if ((!empty(@$discount)) && ($pay > 0)) {
                    $experience_accomodation->htmlPrice = '<del class="text-default me-1 c-pink fs-16">' .
                            \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency)
                            . '</del>';
                }
                $experience_accomodation->htmlPrice .= \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);   
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
                $data['deposit_amount'] = @$deposit_amount;
                $data['booking_amount_html_price'] = \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience_accomodation->currency);   
            }
        }
        echo json_encode($data);
    }

}
