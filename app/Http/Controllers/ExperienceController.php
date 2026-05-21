<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Mail;
use Session;
use Config;

//use App\User;

class ExperienceController extends Controller {

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
            $exParam['where'] = array("slug" => $slug);
            if (!isset($_GET['preview']) or ( $_GET['preview'] != 1)) {
                $exParam['where']["is_draft"] = 0;
            }
            $experience = \App\Experiences::get_data($exParam);
            $data['experience'] = (@$experience[0]) ? @$experience[0] : array();
            if (!empty(@$data['experience'])) {
                
                $cnd = ' AND e.id = '.@$data['experience']->id;
                $experienceList = \App\Experiences::get_exp_deal_price_data($cnd, 'e.id', 'ASC', 1);
                $data['experienceList'] = @$experienceList[0];
                

                $paramCRT['order'] = "ASC";
                $paramCRT['orderby'] = "name";
                $data['certificates'] = \App\Certificates::get_data($paramCRT);

                $paramT['order'] = "ASC";
                $paramT['orderby'] = "name";
                $data['teachers'] = \App\Teachers::get_data($paramT);

                // Get Experience Gallery Images
                $paramEGI['select'] = array('id', 'experience_id', 'image_url', 'image_title');
                $paramEGI['where'] = array("experience_id" => $data['experience']->id);
                $data['imagegalleries'] = \App\ExperienceImageGallery::get_data($paramEGI);

                // Get experience Teachers
                $data['experience_teachers'] = \App\Teachers::select("teachers.id", "teachers.name", "teachers.profile_image_url", "teachers.short_description", "teachers.slug")
                                ->join("experience_teachers", "teachers.id", "=", "experience_teachers.teacher_id")
                                ->where("experience_teachers.experience_id", $data['experience']->id)->distinct()->get();

                // Get Experience Schedules
                $paramCA['where'] = array("experience_id" => $data['experience']->id);
                $paramCA['order'] = "ASC";
                $paramCA['orderby'] = "id";
                $data['experience_schedules'] = \App\ExperienceSchedules::get_data($paramCA);

                $data['experience_categories'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title", "category.parent")
                                ->Join('category', function($join) {
                                    $join->on('category.id', '=', 'experience_category.category_id');
                                    $join->where("type", "0");
                                })->where("experience_category.experience_id", $data['experience']->id)->orderBy("name", "ASC")->distinct()->get();
                                
                $data['experience_destination'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title", "category.parent")
                                ->Join('category', function($join) {
                                    $join->on('category.id', '=', 'experience_category.category_id');
                                    $join->where("type", "1");
                                })->where("experience_category.experience_id", $data['experience']->id)->orderBy("name", "ASC")->distinct()->get();                
                                

                $param = array("where" => array("id" => $data['experience']->center_id), "limit" => 1);
                $data['ecenter'] = \App\Centers::get_data($param);
                $data['center'] = (@$data['ecenter'][0]) ? : array();
                      
                
                // Get Listing Amenities
                if (@$data['center']->amenities) {
                    $data['amenities'] = \App\Amenities::select("id","name","image_url")->whereIn("id",explode("||",@$data['center']->amenities))->orderBy("name","ASC")->get();
                }

                // Get Center Locations
                $data['center_locations'] = \App\Category::select("category.*")
                                ->join("center_locations", "center_locations.location_id", "=", "category.id")
                                ->where("center_id", $data['experience']->center_id)->get();

                $data['experience_accomodations'] = \App\Experiences::get_exp_acm_data($data['experience']->id);
                $data['accomodationimagegalleries'] = array();
                if (sizeof($data['experience_accomodations']) > 0) {
                    $acmIds = array();
                    foreach ($data['experience_accomodations'] as $ex_acm_image) {
                        array_push($acmIds, $ex_acm_image->id);
                    }
                    if ($acmIds) {
                        // Get Accomodation Gallery Images
                        $data['accomodationimagegalleries'] = \App\AccomodationImageGallery::select('id', 'accomodation_id', 'image_url', 'image_title')
                                        ->whereIn("accomodation_id", $acmIds)->get();
                    }
                }

                $data['experience_recurring'] = array();
                if ($data['experience']->is_recurring == 1) {
                    $data['experience_recurring_manually'] = \App\ExperienceRecurringManually::where("experience_id", $data['experience']->id)->where("start_date", ">", \Carbon\Carbon::now())->get();
                    $data['experience_recurring'] = \App\ExperienceRecurring::where("experience_id", $data['experience']->id)->first();
                }

                // Get Food Gallery Images
                $data['foodimagegalleries'] = \App\ExperienceFoodImageGallery::select('id', 'image_url', 'image_title')
                                ->where("experience_id", $data['experience']->id)->get();
                                
                $data['experience_durations'] = \App\ExperienceDurationPrices::where("experience_id", $data['experience']->id)->get();
                $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
                if (!isset($_GET['test']) or ( $_GET['test'] != 1)) {
                return view('experience_detail', $data);
                    
                }else{
                return view('experience_detail(2)', $data);
                }
            } else {
                return redirect("/experiences");
            }
        } else {
            $data = array();
            $order = "DESC";
            $orderby = "e.updated_at";
            if (@$_GET['sort_by']) {
                switch (@$_GET['sort_by']) {
                    case "price_asc":
                        $order = "ASC";
                        $orderby = "final_room_price";
                        break;
                    case "price_desc":
                        $order = "DESC";
                        $orderby = "final_room_price";
                        break;
                    case "ranking":
                        $order = "DESC";
                        $orderby = "e.updated_at";
                        break;
                    default :
                        $order = "DESC";
                        $orderby = "e.updated_at";
                        break;
                }
            }
            
            $cnd = "";

            $limit = 50; // Fixed number of records per page
            $page = request()->input('page') ?? 1; // Default to page 1 if not provided
            $offset = ($page - 1) * $limit;

            $data['popular'] = array();
            if (!empty(request()->input('popular'))) {
                $arPopulars = request()->input('popular');
                $data['popular'] = $arPopulars;
                $cnd .= " and (";
                foreach ($arPopulars as $arPopular) {
                    $cnd .= " e." . $arPopular . " >= 1 OR ";
                }
                $cnd = rtrim($cnd, "OR ");
                $cnd .= ")";
            }
            $data['scategory'] = "";
            $cat = array();
            if (!empty(request()->input('scategory'))) {
                $scategory = request()->input('scategory');
                $data['scategory'] = $scategory;
                $cat[] = $scategory;
            }
            $data['sdestination'] = "";
            if (!empty(request()->input('sdestination'))) {
                $sdestination = request()->input('sdestination');
                $data['sdestination'] = $sdestination;
                $cat[] = $sdestination;   
            }
            
            $data['stags'] = array();
            if (!empty(request()->input('stags'))) {
                $sbtags = request()->input('stags');
                if(is_array(@$sbtags)) {
                    $stags = $sbtags;
                } else {
                    $stags = array_map('trim', explode(",", $sbtags));
                }
                $data['stags'] = $stags;
                $cnd .= " and (";
                $tcnd = "";
                foreach($stags as $tag) {
                    $tcnd .= " `e`.`tags` like '%".$tag."%' OR ";
                }
                $cnd .= trim($tcnd, " OR ").") ";
            }
            
            if ($cat) {
                $cnd .= " and (";
                $cnd .= " ec.category_id IN (".implode(',', $cat).")";
                $cnd .= ")";
            }
            $having = "";
            if (!empty(request()->input('price_range'))) {
                $price_range = request()->input('price_range');
                $arPriceRange = explode(";", $price_range);
                $having = " HAVING (";
                $having .= " final_room_price BETWEEN " . $arPriceRange[0] . " AND " . $arPriceRange[1];
                $having .= ") ";
            }

            $data['ssearch'] = '';
            if (request()->filled('search')) {
                $search = request()->input('search');
                $cnd .= " and (";
                $cnd .= ' e.name like "%'.$search.'%"';
                $cnd .= ")";
                $data['ssearch'] = $search;
            }

            if (request()->ajax()) {
                $data['experiences'] = \App\Experiences::get_exp_price_data($cnd, $orderby, $order, $limit, $offset, $having);
                $view = view('content-experience-ajax', $data)->render();
                return response()->json(['html' => $view]);
            }
            
            $max_experience_price = \App\Experiences::get_exp_price_data($cnd, "final_room_price", "DESC", 1, 0, $having);
            $data['max_experience_price'] = @$max_experience_price[0]->final_room_price;
            $min_experience_price = \App\Experiences::get_exp_price_data($cnd, "final_room_price", "ASC", 1, 0, $having);
            $data['min_experience_price'] = @$min_experience_price[0]->final_room_price;
            $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
            
            $predefinedTags = collect([
                'Short Duration', 
                'Long Duration', 
                'Near the Beach', 
                'Lakeside Views', 
                'Mountain Vibes', 
                'City Vibes', 
                'Forest Energy', 
                'Weight-Loss', 
                'De-stress', 
                'Detox'
            ]);

            // Get tags from database and process them properly
            /*$dbTags = \App\Experiences::select("tags")
                ->where("is_draft", 0)
                ->get()
                ->pluck('tags')
                ->filter() // Remove null/empty values
                ->flatMap(function($tagString) {
                    return array_map('trim', explode(",", $tagString));
                })
                ->unique();

            // Merge with predefined tags and ensure uniqueness
            $data['tags'] = $predefinedTags->merge($dbTags)->unique()->values();*/
            $data['tags'] = $predefinedTags->values();

            $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
            return view('experiences', $data);
        }
    }

    /**
     * Display a listing of the resource on Load more.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadDataAjax(Request $request) {
        $data = array();
        $order = "DESC";
        $orderby = "e.updated_at";
        if (@$_GET['sort_by']) {
            switch (@$_GET['sort_by']) {
                case "price_asc":
                    $order = "ASC";
                    $orderby = "final_room_price";
                    break;
                case "price_desc":
                    $order = "DESC";
                    $orderby = "final_room_price";
                    break;
                case "ranking":
                    $order = "DESC";
                    $orderby = "e.updated_at";
                    break;
                default :
                    $order = "DESC";
                    $orderby = "e.updated_at";
                    break;
            }
        }
        $cnd = "";

        $limit = 50; // Fixed number of records per page
        $page = $request['page'] ?? 1; // Default to page 1 if not provided
        $offset = ($page - 1) * $limit;

        $data['popular'] = array();
        if (!empty(request()->input('popular'))) {
            $arPopulars = request()->input('popular');
            $data['popular'] = $arPopulars;
            $cnd .= " and (";
            foreach ($arPopulars as $arPopular) {
                $cnd .= " e." . $arPopular . " >= 1 OR ";
            }
            $cnd = rtrim($cnd, "OR ");
            $cnd .= ")";
        }
        $data['scategory'] = "";
        $cat = array();
        if (!empty(request()->input('scategory'))) {
            $scategory = request()->input('scategory');
            $data['scategory'] = $scategory;
            $cat[] = $scategory;
        }
        $data['sdestination'] = "";
        if (!empty(request()->input('sdestination'))) {
            $sdestination = request()->input('sdestination');
            $data['sdestination'] = $sdestination;
            $cat[] = $sdestination;   
        }
        
        $data['stags'] = array();
        if (!empty(request()->input('stags'))) {
            $sbtags = request()->input('stags');
            if(is_array(@$sbtags)) {
                $stags = $sbtags;
            } else {
                $stags = array_map('trim', explode(",", $sbtags));
            }
            $data['stags'] = $stags;
            $cnd .= " and (";
            $tcnd = "";
            foreach($stags as $tag) {
                $tcnd .= " `e`.`tags` like '%".$tag."%' OR ";
            }
            $cnd .= trim($tcnd, " OR ").") ";
        }
        
        if ($cat) {
            $cnd .= " and (";
            $cnd .= " ec.category_id IN (".implode(',', $cat).")";
            $cnd .= ")";
        }
        $having = "";
        if (!empty(request()->input('price_range'))) {
            $price_range = request()->input('price_range');
            $arPriceRange = explode(";", $price_range);
            $having = " HAVING (";
            $having .= " final_room_price BETWEEN " . $arPriceRange[0] . " AND " . $arPriceRange[1];
            $having .= ") ";
        }

        $data['ssearch'] = '';
        if (request()->filled('search')) {
            $search = request()->input('search');
            $cnd .= " and (";
            $cnd .= ' e.name like "%'.$search.'%"';
            $cnd .= ")";
            $data['ssearch'] = $search;
        }

        $data['experiences'] = \App\Experiences::get_exp_price_data($cnd, $orderby, $order, $limit, $offset, $having);
        $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
        $view = view('content-experience-ajax', $data)->render();
        return response()->json(['html' => $view]);
    }

    /**
     * Get Experience by Request Parameters
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $data = array();
        $sdest = (!empty($request['sdestination'])) ? $request['sdestination'] : "";
        $scat = (!empty($request['scategory'])) ? $request['scategory'] : "";
        $sedate = (!empty($request['sexp_date'])) ? \Carbon\Carbon::parse($request['sexp_date'])->format("Y-m-d") : "";
        $cnd = "";

        // Search by Category & Destination
        $categoryId = array();
        if (!empty($sdest)) {
            array_push($categoryId, $sdest);
        }
        if (!empty($scat)) {
            array_push($categoryId, $scat);
        }
        $expIds = array();
        $srchCatParam = 0;
        if (!empty($sdest) || !empty($scat)) {
            if (!empty($sdest)) {
                $objDestinationExp = \App\ExperienceCategory::select("experience_id")->where("category_id", $sdest)->distinct()->get();
            }
            if (!empty($scat)) {
                $objCategoriesExp = \App\ExperienceCategory::select("experience_id")->where("category_id", $scat);
                if (@$objDestinationExp) {
                    $objCategoriesExp->whereIn("experience_id", @$objDestinationExp);
                }
                $objCategories = $objCategoriesExp->distinct()->get();
            } else {
                $objCategories = $objDestinationExp;
            }
            foreach ($objCategories as $objCategory) {
                array_push($expIds, $objCategory->experience_id);
            }

            if ($expIds) {
                $cnd .= " and ( e.`id` IN (" . implode(",", $expIds) . ") )";
            } else {
                $srchCatParam = 1;
            }
        }

        // Search by Date
        if ($sedate) {
            $sedateto = \Carbon\Carbon::parse($request['sexp_date'])->addDays(30)->format("Y-m-d");
            $cnd .= " and ((e.`start_date_time` BETWEEN '$sedate' AND '$sedateto') ";
            $cnd .= " or (erm.`start_date` BETWEEN '$sedate' AND '$sedateto')) ";
        }

        $order = "DESC";
        $orderby = "updated_at";
        if (@$_GET['sort_by']) {
            switch (@$_GET['sort_by']) {
                case "price_asc":
                    $order = "ASC";
                    $orderby = "price_per_person";
                    break;
                case "price_desc":
                    $order = "DESC";
                    $orderby = "price_per_person";
                    break;
                case "ranking":
                    $order = "DESC";
                    $orderby = "updated_at";
                    break;
                default :
                    $order = "DESC";
                    $orderby = "updated_at";
                    break;
            }
        }
        $offset = 0;
        $limit = 100;
        if ($request['page']) {
            $offset = ($request['page'] * 100) - 100;
            $limit = $request['page'] * 100;
        }
        $resExp = array();

        $data['popular'] = array();
        if (!empty(request()->input('popular'))) {
            $arPopulars = request()->input('popular');
            $data['popular'] = $arPopulars;
            $cnd .= " and (";
            foreach ($arPopulars as $arPopular) {
                $cnd .= " e." . $arPopular . " >= 1 OR ";
            }
            $cnd = rtrim($cnd, "OR ");
            $cnd .= ")";
        }

        //if ($srchCatParam == 0) {
        $resExp = \App\Experiences::get_exp_price_data($cnd, $orderby, $order, $limit, $offset);
        //}
        $data['experiences'] = $resExp;
        $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
        $data['sdest'] = $sdest;
        $data['scat'] = $scat;
        $data['sedate'] = $sedate;
        return view('search', $data);
    }

    /**
     * Display a experience inquiry form with Exp detail
     *
     * @param str $slug
     * @return \Illuminate\Http\Response
     */
    public function inquiry($slug = '') {
        $data = array();
        if ($slug) {
            $exParam['where'] = array("slug" => $slug);
            $experience = \App\Experiences::get_data($exParam);
            $data['experience'] = (@$experience[0]) ? @$experience[0] : array();
            if (!empty(@$data['experience'])) {
                $paramCRT['order'] = "ASC";
                $paramCRT['orderby'] = "name";
                $data['certificates'] = \App\Certificates::get_data($paramCRT);

                $param = array("where" => array("id" => $data['experience']->center_id), "limit" => 1);
                $data['ecenter'] = \App\Centers::get_data($param);
                $data['center'] = (@$data['ecenter'][0]) ? : array();

                $data['experience_accomodations'] = array();
                $experience_accomodations = \App\Experiences::get_exp_acm_data($data['experience']->id);
                $data['experience_accomodations'] = (!empty($experience_accomodations[0]) ? $experience_accomodations[0] : array());

                $pay = @$data['experience_accomodations']->room_price;

                // Early Bird Discount
                if ((!empty(@$data['experience']->eirly_bird_before_days)) && (!empty(@$data['experience']->eirly_bird_discount))) {
                    $discount = (@$data['experience_accomodations']->room_price * @$data['experience']->eirly_bird_discount) / 100;
                    $pay = $pay - $discount;
                }

                // Calculate Commission
                $commission = ($pay * @$data['experience']->commission) / 100;
                if (!empty(@$data['experience']->deposit_policy)) {
                    switch (@$data['experience']->deposit_policy) {
                        case "2" :
                            // If Fixed Amount
                            $pay = @$data['experience']->deposit_amount + $commission;
                            break;
                        case "3" :
                            // If Percentage
                            $deposit_amount = (@$pay * @$data['experience']->deposit_amount) / 100;
                            $pay = $commission + $deposit_amount;
                            break;
                        default:
                            break;
                    }
                }
                return view('experience_inquiry', $data);
            } else {
                return redirect("/experiences");
            }
        } else {
            return redirect("/experiences");
        }
    }

    public function get_ajax_exp_accomodation(Request $request) {
        $experience_id = $request->input("exp_id");

        $arexp_booking_date = explode(" - ", @$request->input("booking_date"));
        $exp_booking_start = \Carbon\Carbon::parse(@$arexp_booking_date[0])->format("Y-m-d");
        $exp_booking_end = \Carbon\Carbon::parse(@$arexp_booking_date[1])->format("Y-m-d");
        $experience_accomodations = \App\Experiences::get_exp_acm_data($experience_id, '', $exp_booking_start, $exp_booking_end);
        if (sizeof(@$experience_accomodations) > 0) {

            $exParam['where'] = array("id" => $experience_id);
            $exParam['where']["is_draft"] = 0;
            $experience = \App\Experiences::get_data($exParam);
            $experience = (@$experience[0]) ? @$experience[0] : array();

            foreach (@$experience_accomodations as $experience_accomodation) {
                $discount = 0;
                $experience_accomodation->htmlPrice = "";
                $pay = @$experience_accomodation->room_price;
                if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                    if (@$experience->eirly_bird_discount_type == "amt") {
                        $discount += @$experience->eirly_bird_discount;
                    } else {
                        $discount = (@$pay * @$experience->eirly_bird_discount) / 100;
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

                if (!empty(@$discount)) {
                    $experience_accomodation->htmlPrice = '<del class="text-default">' .
                            \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency)
                            . '</del>';
                }
                $experience_accomodation->htmlPrice .= \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);
            }
        }
        echo json_encode($experience_accomodations);
    }

    /**
     * Redirect page
     *
     * @param Request Parameters
     * @return \Illuminate\Http\Response
     */
    public function redirect_to_portal(Request $request) {
        $data = array();
        $experienceId = (@$request["hdn_experience_id"]) ? $request["hdn_experience_id"] : ((!empty(Session('experience_info')['exp_id'])) ? Session('experience_info')['exp_id'] : "");
        $exp_accomodation_id = (@$request["exp_accomodation_id"]) ? $request["exp_accomodation_id"] : ((!empty(Session('experience_info')['exp_acm_id']) && (Session('experience_info')['exp_acm_id'])) ? Session('experience_info')['exp_acm_id'] : "");
        $exp_booking_date = (@$request["booking_date"]) ? $request["booking_date"] : ((!empty(Session('experience_info')['exp_booking_date']) && (Session('experience_info')['exp_booking_date'])) ? Session('experience_info')['exp_booking_date'] : "");
        if ($experienceId) {

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
                $experience_accomodations = \App\Experiences::get_exp_acm_data($data['experience']->id, @$exp_accomodation_id, @$exp_booking_start);
                $data['experience_accomodation'] = (!empty($experience_accomodations[0]) ? $experience_accomodations[0] : array());

                // Get Accomodation Gallery Images
                $data['accomodationimagegalleries'] = \App\AccomodationImageGallery::select('id', 'accomodation_id', 'image_url', 'image_title')
                                ->where("accomodation_id", $exp_accomodation_id)->get();
            }


            $pay = @$data['experience_accomodation']->room_price;

            // Early Bird Discount
            if ((!empty(@$data['experience']->eirly_bird_before_days)) && (!empty(@$data['experience']->eirly_bird_discount))) {
                $discount = (@$data['experience_accomodation']->room_price * @$data['experience']->eirly_bird_discount) / 100;
                $pay = $pay - $discount;
            }

            // Calculate Commission
            $commission = ($pay * @$data['experience']->commission) / 100;
            if (!empty(@$data['experience']->deposit_policy)) {
                switch (@$data['experience']->deposit_policy) {
                    case "2" :
                        // If Fixed Amount
                        $pay = @$data['experience']->deposit_amount + $commission;
                        break;
                    case "3" :
                        // If Percentage
                        $deposit_amount = (@$pay * @$data['experience']->deposit_amount) / 100;
                        $pay = $commission + $deposit_amount;
                        break;
                    default:
                        break;
                }
            }
            return view('redirect_to_portal', $data);
        } else {
            return Redirect('experiences');
        }
    }

    /**
     * Save Exp Inquiry
     *
     * @param Request Parameters
     *
     * @return \Illuminate\Http\Response
     */
    public function store_inquiry(Request $request) {
        $rules = [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha'
        ];
        $message = ['g-recaptcha-response.required' => "The Captcha field is required."];
        $validator = validator()->make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $name = request()->input('name');
        $lastname = request()->input('lastname');
        $email = request()->input('email');
        $phone = request()->input('phone');
        $comment = request()->input('comment');
        $conversationId = 'enq' . rand() . time();
        $experience_id = request()->input('experience_id');
        $center_id = request()->input('center_id');
        if (empty($experience_id) && empty($center_id)) {
          $response = 'Unable to send message!';
          return $response;
        }
        // Exp Email Id
        $objExp = \App\Experiences::select("email_address","name")->join("centers", "centers.id", "=", "experiences.center_id");
        if (!empty($center_id)) {
            $objExp->where("centers.id", $center_id);
        } else {
            $objExp->where("experiences.id", $experience_id);
        }
        $objExp = $objExp->first();
        $expEmailID = trim($objExp->email_address);


        $objComments = new \App\Inquiry();
        $objComments->name = $name;
        $objComments->lastname = $lastname;
        $objComments->email = $email;
        $objComments->phone = $phone;
        $objComments->message = $comment;
        $objComments->experience_id = @$experience_id;
        $objComments->conversation_id = $conversationId;
        $objComments->save();
        $replyIdforcustomer = $conversationId . '-1';
        $replyIdforcenter = $conversationId . '-2';

        DB::table('customer_message')->insert(
                [
                    'conversation_id' => $conversationId,
                    'experience_id' => $experience_id,
                    'message_type' => '1',
                    'message' => $comment,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
        );


        try {
            Mail::send('emails.inquiry.admin', [
                'firstname' => @$name,
                'lastname' => @$lastname,
                'email' => @$email,
                'phone' => @$phone,
                'bodymessage' => @$comment,
                'conversation' => @$replyIdforcenter,
                    ], function ($message) use ($email) {
                $message->subject("Inquiry");
                $message->from(@$email);
                $message->to('zen@balanceboat.com', 'Balanceboat');
                $message->cc('kunal@balanceboat.com', 'Balanceboat');
                $message->cc('shivam@balanceboat.com', 'Balanceboat');
            });
            
            if ($objExp) {
                //ActiveCampaign Start
                $retreat_interest = $objExp->name ?? '';
                $preferred_location = $objExp->destinations?->pluck('name')->join(', ') ?? '';
                $eventData = array(
                    "firstName" => @$name,
                    "lastName" => @$lastname,
                    "phone" => @$phone,
                    "source" => @$objExp->name,
                    "retreat_interest" => @$retreat_interest,
                    "preferred_location" => @$preferred_location
                );
                (new \App\Services\ActiveCampaignService())->trackCartActivity($email, $eventData, "Interest: $retreat_interest");
                //Active Campaign End
            }
        } catch (Exception $ex) {
            return "Something went wrong!!";
        }

        try {
            if (!empty(@$email)) {
                Mail::send('emails.inquiry.customer', [
                    'firstname' => @$name,
                    'lastname' => @$lastname,
                    'bodymessage' => @$comment,
                    'conversation' => @$replyIdforcustomer,
                        ], function ($message) use ($email) {
                    $message->subject("Inquiry");
                    $message->from('zen@balanceboat.com');
                    $message->to(@$email);
                });
            }
        } catch (Exception $ex) {
            return "Something went wrong!!";
        }

        try {
            if (!empty($expEmailID)) {
                Mail::send('emails.inquiry.organizer', [
                    'firstname' => @$name,
                    'lastname' => @$lastname,
                    'email' => @$email,
                    'phone' => @$phone,
                    'bodymessage' => @$comment,
                    'conversation' => @$replyIdforcenter,
                        ], function ($message) use ($expEmailID) {
                    $message->subject("Inquiry");
                    $message->from('zen@balanceboat.com');
                    $message->to(@$expEmailID);
                });
            }
        } catch (Exception $ex) {
            return "Something went wrong!!";
        }
    }
    
    
     /**
     * Pro Listing
     *
     * @return \Illuminate\Http\Response
     */
    public function pro_listing() {
        
        $data = array();
        return view('pro_listing', $data);
    }

    /**
     * Search Exp Data
     *
     * @return \Illuminate\Http\Response
     */
    public function search_experience() {
        $term = request()->input('search');
        $results = array();
        if (!empty($term)) {
            $experiences = \App\Experiences::select('experiences.id', 'experiences.name')
                            ->where(function($query) use($term) {
                                $query->where('experiences.name', 'like', "%" . $term . "%");
                            });
            /*if (!empty(request()->input('location'))) {
                $location = request()->input('location');
                $listings->where(function($query) use($location) {
                    $query->whereIn('listings.country', (array)$location);
                    $query->orWhereIn('listings.city', (array)$location);
                });
            }*/               
            $experiences = $experiences->distinct()->limit(10)->get();
            if ($experiences) {
                foreach ($experiences as $experience) {
                    $results[] = [ 'id' => $experience->id, 'value' => $experience->name];
                }
            }
        }
        return response()->json($results);
    }
}
