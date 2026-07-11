<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Experiences;
use Carbon\Carbon;
use Storage;
use DB;

class ExperiencesController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource - Experiences.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
        if ($request->ajax()) {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $sbookable = $request->input('sbookable');
            $sdestination = $request->input('sdestination');
            $search = $request->input('search')['value'];


            $experiences = Experiences::select("experiences.id", "experiences.name", "experiences.slug", "centers.name as CenterName", "experiences.updated_at as LastUpdatedAt")
                ->leftJoin("centers", "centers.id", "=", "experiences.center_id");

            if ($search) {
                $experiences->where(function ($query) use ($search) {
                    $query->where('experiences.name', 'like', "%{$search}%")->orWhere('centers.name', 'like', "%{$search}%");
                });
            }

            if ($sbookable != '') {
                $experiences->where(function ($query) use ($sbookable) {
                    $query->where('is_bookable', $sbookable);
                });
            }
            if ($sdestination != '') {
                $experiences->join('experience_category',"experience_category.experience_id", "experiences.id")->where("experience_category.category_id", $sdestination);
            }

            $experienceTotal = $experiences->count();
            $experiences = $experiences->offset($start)->limit($length)
                ->orderBy("name", "ASC")
                ->get();

            $data = array();
            if ($experiences) {
                foreach ($experiences as $experience) {
                    $data[] = [
                        'name' => "<a href=" . url('bbadmin/experiences/edit/' . $experience->id) . ">" . $experience->name . "</a>",
                        'slug' => $experience->slug,
                        'CenterName' => $experience->CenterName,
                        'LastUpdatedAt' => \Carbon\Carbon::parse($experience->LastUpdatedAt)?->format('d M, Y h:i A'),
                        'action' => '<div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                            <a class="dropdown-item" href="' . url('bbadmin/experiences/edit/' . $experience->id) . '">Edit</a>
                                            <a href="' . url('bbadmin/experiences/clone/' . $experience->id) . '" class="dropdown-item text-warning">Clone</a>
                                            <a href="' . url('/experience/' . (@$experience->slug) . '?preview=1') . '" target="_blank" class="dropdown-item text-info">Preview</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger experience-delete" href="#" data-rel="' . $experience->id . '" >Delete</a>
                                        </div>
                                    </div>'
                    ];
                }
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $experienceTotal,
                'recordsFiltered' => $experienceTotal,
                'data' => $data,
            ];

            return response()->json($response);
        }
        /*$data['experiences'] = Experiences::select("experiences.id", "experiences.name", "experiences.slug", "centers.name as CenterName")
                ->leftJoin("centers", "centers.id", "=", "experiences.center_id")
                ->orderBy("name", "ASC")
                ->get();*/
        $data['destinations'] = \App\Category::where("type", 1)->where("parent", 0)->get();
        return view("admin.experiences.index", $data);
    }

    /**
     * Show the form for creating a new Experience.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['categories'] = \App\Category::get_data($param);
        $paramC['select'] = array("id", "name", "have_accomodation");
        $paramC['order'] = "ASC";
        $paramC['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($paramC);
        $data['teachers'] = \App\Teachers::get_data($param);
        //$data['accomodations'] = \App\Accomodation::get_data($param);
        return view('admin.experiences.create', $data);
    }

    /**
     * Store a newly created Experience in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ajax = $request->ajax() ? true : false;
        if ($ajax == false) {
            $this->validate($request, [
                'name' => 'required',
                'slug' => 'required',
            ]);
        }
        $name = $request['name'];
        $slug = $request['slug'];
        $meta_title = $request['meta_title'];
        $meta_description = $request['meta_description'];
        $keywords = $request['keywords'];
        $experience_category_ids = ($request['experience_category_id']) ? $request['experience_category_id'] : array();
        $center_id = $request['center_id'];
        $teacher_ids = (is_array($request['teacher_id'])) ? $request['teacher_id'] : array();
        $price_per_person = $request['price_per_person'];
        $currency = $request['exp_currency'];
        $avg_price = $request['avg_price'];
        $batch_size = $request['batch_size'];
        $experience_summary = $request['experience_summary'];
        $date_time = ($request['date_time']) ? Carbon::parse($request['date_time'])->format("Y-m-d H:i:s") : NULL;
        $start_date_time = ($request['start_date_time']) ? Carbon::parse($request['start_date_time'])->format("Y-m-d H:i:s") : NULL;
        $end_date_time = ($request['end_date_time']) ? Carbon::parse($request['end_date_time'])->format("Y-m-d H:i:s") : NULL;
        $is_full_day_event = ($request['is_full_day_event']) ? $request['is_full_day_event'] : 0;
        $is_recurring = !empty($request['recurring_type']) ? 1 : 0;
        $experience_certification = (is_array($request['experience_certification'])) ? implode("||", $request['experience_certification']) : NULL;
        $language_spoken = (is_array($request['language_spoken'])) ? implode("||", $request['language_spoken']) : NULL;
        $experience_overview = $request['experience_overview'];
        $styles_taught = $request['styles_taught'];
        $food = $request['food'];
        $skill_level = $request['skill_level'];
        $area = $request['area'];
        $atmosphere = $request['atmosphere'];
        $experience_details = $request['experience_details'];
        $experience_highlights = $request['experience_highlights'];
        $food_overview = $request['food_overview'];
        $experience_schedule = $request['experience_schedule'];
        $what_is_included = $request['what_is_included'];
        $what_is_not_included = ($request['what_is_not_included']);
        $how_to_get_here = ($request['how_to_get_here']);
        $booking_info = ($request['booking_info']);
        $cancellation_policy = ($request['cancellation_policy']);
        $deposit_policy = ($request['deposit_policy']) ? $request['deposit_policy'] : 0;
        $deposit_amount = ($request['deposit_amount']) ? $request['deposit_amount'] : NULL;
        $cancellation_policy_condition = ($request['cancellation_policy_condition']) ? $request['cancellation_policy_condition'] : 0;
        $cancellation_policy_days = ($request['cancellation_policy_days']) ? $request['cancellation_policy_days'] : NULL;
        $rest_of_payment = ($request['rest_of_payment']) ? $request['rest_of_payment'] : 0;
        $rest_of_payment_days = ($request['rest_of_payment_days']) ? $request['rest_of_payment_days'] : NULL;
        $commission = ($request['commission']) ? $request['commission'] : NULL;
        $tax = ($request['tax']) ? $request['tax'] : NULL;
        $is_draft = $request['is_draft'];
        $is_bookable = $request['is_bookable'];
        $display_on_home = $request['display_on_home'];
        $tags = $request['tags'];
        $gps = $request['gps'];

        $eirly_bird_before_days = ($request['eirly_bird_before_days']) ? $request['eirly_bird_before_days'] : NULL;
        $eirly_bird_discount = ($request['eirly_bird_discount']) ? $request['eirly_bird_discount'] : NULL;
        $eirly_bird_discount_type = ($request['eirly_bird_discount_type']) ? $request['eirly_bird_discount_type'] : NULL;

        $offer_start_date = ($request['offer_start_date']) ? Carbon::parse($request['offer_start_date'])->format("Y-m-d") : NULL;
        $offer_end_date = ($request['offer_end_date']) ? Carbon::parse($request['offer_end_date'])->format("Y-m-d") : NULL;
        $offer_discount = ($request['offer_discount']) ? $request['offer_discount'] : NULL;
        $offer_discount_type = ($request['offer_discount_type']) ? $request['offer_discount_type'] : NULL;

        $featured_experiences = ($request['featured_experiences_on_home']) ? $request['featured_experiences_on_home'] : (($request['featured_experiences']) ? $request['featured_experiences'] : 0);
        $best_off_season_deals = ($request['best_off_season_deals_on_home']) ? $request['best_off_season_deals_on_home'] : (($request['best_off_season_deals']) ? $request['best_off_season_deals'] : 0);
        $deals_of_the_month = ($request['deals_of_the_month_on_home']) ? $request['deals_of_the_month_on_home'] : (($request['deals_of_the_month']) ? $request['deals_of_the_month'] : 0);
        $advance_trainings = ($request['advance_trainings_on_home']) ? $request['advance_trainings_on_home'] : (($request['advance_trainings']) ? $request['advance_trainings'] : 0);
        $weekend_retreats = ($request['weekend_retreats_on_home']) ? $request['weekend_retreats_on_home'] : (($request['weekend_retreats']) ? $request['weekend_retreats'] : 0);
        $couple_retreats = ($request['couple_retreats_on_home']) ? $request['couple_retreats_on_home'] : (($request['couple_retreats']) ? $request['couple_retreats'] : 0);
        $blessed_by_the_sea = ($request['blessed_by_the_sea_on_home']) ? $request['blessed_by_the_sea_on_home'] : (($request['blessed_by_the_sea']) ? $request['blessed_by_the_sea'] : 0);
        $retreats_by_the_mountains = ($request['retreats_by_the_mountains_on_home']) ? $request['retreats_by_the_mountains_on_home'] : (($request['retreats_by_the_mountains']) ? $request['retreats_by_the_mountains'] : 0);
        $luxury_retreats = ($request['luxury_retreats_on_home']) ? $request['luxury_retreats_on_home'] : (($request['luxury_retreats']) ? $request['luxury_retreats'] : 0);
        $budget_retreats = ($request['budget_retreats_on_home']) ? $request['budget_retreats_on_home'] : (($request['budget_retreats']) ? $request['budget_retreats'] : 0);

        $duration = $request['duration'];
        $videoUrl = $request['video'];

        if ($request->file('thumbnail_image')) {
            $thumbnailUrl = $this->upload_image($request->file('thumbnail_image'));
        }

        if ($request->file('banner_image')) {
            $imageUrl = $this->upload_image($request->file('banner_image'));
            $imageTitle = $request->file('banner_image')->getClientOriginalName();
        }

        if ($request->file('food_banner_image')) {
            $foodimageUrl = $this->upload_image($request->file('food_banner_image'));
            $foodimageTitle = $request->file('food_banner_image')->getClientOriginalName();
        }
        $image_galleries = (!empty($request['image_gallery_ids'])) ? $request['image_gallery_ids'] : "";
        $food_image_galleries = (!empty($request['food_image_gallery_ids'])) ? $request['food_image_gallery_ids'] : "";

        if (!empty($request['id'])) {
            $objExperience = Experiences::find($request['id']);
            if ($objExperience) {
                $objExperience->name = $name;
                $objExperience->slug = $slug;
                $objExperience->meta_title = $meta_title;
                $objExperience->meta_description = $meta_description;
                $objExperience->keywords = $keywords;
                $objExperience->tags = $tags;
                $objExperience->gps = $gps;
                //$objExperience->experience_category = $experience_category;
                $objExperience->center_id = $center_id;
                $objExperience->price_per_person = $price_per_person;
                $objExperience->currency = $currency;
                $objExperience->avg_price = $avg_price;
                $objExperience->batch_size = $batch_size;
                $objExperience->experience_summary = $experience_summary;
                $objExperience->date_time = $date_time;
                $objExperience->start_date_time = $start_date_time;
                $objExperience->end_date_time = $end_date_time;
                $objExperience->is_full_day_event = $is_full_day_event;
                $objExperience->is_recurring = $is_recurring;
                $objExperience->experience_certification_id = $experience_certification;
                $objExperience->language_spoken = $language_spoken;
                $objExperience->experience_overview = $experience_overview;
                $objExperience->styles_taught = $styles_taught;
                $objExperience->food = $food;
                $objExperience->skill_level = $skill_level;
                $objExperience->area = $area;
                $objExperience->atmosphere = $atmosphere;
                $objExperience->experience_details = $experience_details;
                $objExperience->experience_highlights = $experience_highlights;
                $objExperience->food_overview = $food_overview;
                $objExperience->schedule = $experience_schedule;
                $objExperience->what_is_included = $what_is_included;
                $objExperience->what_is_not_included = $what_is_not_included;
                $objExperience->how_to_get_here = $how_to_get_here;
                $objExperience->booking_info = $booking_info;
                $objExperience->cancellation_policy = $cancellation_policy;

                $objExperience->deposit_policy = $deposit_policy;
                $objExperience->deposit_amount = $deposit_amount;
                $objExperience->cancellation_policy_condition = $cancellation_policy_condition;
                $objExperience->cancellation_policy_days = $cancellation_policy_days;
                $objExperience->rest_of_payment = $rest_of_payment;
                $objExperience->rest_of_payment_days = $rest_of_payment_days;
                $objExperience->commission = $commission;
                $objExperience->tax = $tax;
                $objExperience->duration = $duration;

                $objExperience->is_draft = $is_draft;
                $objExperience->is_bookable = $is_bookable;
                $objExperience->display_on_home = $display_on_home;

                $objExperience->eirly_bird_before_days = $eirly_bird_before_days;
                $objExperience->eirly_bird_discount = $eirly_bird_discount;
                $objExperience->eirly_bird_discount_type = $eirly_bird_discount_type;

                $objExperience->offer_start_date = $offer_start_date;
                $objExperience->offer_end_date = $offer_end_date;
                $objExperience->offer_discount = $offer_discount;
                $objExperience->offer_discount_type = $offer_discount_type;

                $objExperience->featured_experiences = $featured_experiences;
                $objExperience->best_off_season_deals = $best_off_season_deals;
                $objExperience->deals_of_the_month = $deals_of_the_month;
                $objExperience->advance_trainings = $advance_trainings;
                $objExperience->weekend_retreats = $weekend_retreats;
                $objExperience->couple_retreats = $couple_retreats;
                $objExperience->blessed_by_the_sea = $blessed_by_the_sea;
                $objExperience->retreats_by_the_mountains = $retreats_by_the_mountains;
                $objExperience->luxury_retreats = $luxury_retreats;
                $objExperience->budget_retreats = $budget_retreats;
                $objExperience->video_url = $videoUrl;
                if ($request['thumbnail_image']) {
                    $objExperience->thumbnail_image_url = $thumbnailUrl;
                }
                if ($request['banner_image']) {
                    $objExperience->banner_image_title = $imageTitle;
                    $objExperience->banner_image_url = $imageUrl;
                }
                if ($request['food_banner_image']) {
                    $objExperience->food_banner_image_title = $foodimageTitle;
                    $objExperience->food_banner_image_url = $foodimageUrl;
                }
                try {
                    $objExperience->save();
                    $experience_id = $request['id'];

                    // Experience Recurring
                    if (!empty(@$request['recurring_type'])) {
                        if ((!empty(@$request['recurring_type'])) && (@$request['recurring_type'] == "Manually")) {
                            if ((!empty(@$request['manually_start_date_time'])) && (sizeof(@$request['manually_start_date_time']) > 0)) {
                                $mstdates = @$request['manually_start_date_time'];
                                \App\ExperienceRecurring::where(array("experience_id" => $experience_id))->delete();
                                \App\ExperienceRecurringManually::where(array("experience_id" => $experience_id))->delete();
                                foreach ($mstdates as $mk => $mstdate) {
                                    if (!empty($mstdate)) {
                                        $menddate = @$request['manually_end_date_time'][$mk];
                                        $objExperienceRecurringManually = new \App\ExperienceRecurringManually();
                                        $objExperienceRecurringManually->experience_id = $experience_id;
                                        $objExperienceRecurringManually->start_date = (!empty(@$mstdate)) ? Carbon::parse($mstdate)->format("Y-m-d H:i:s") : NULL;
                                        $objExperienceRecurringManually->end_date = (!empty(@$menddate)) ? Carbon::parse(@$menddate)->format("Y-m-d H:i:s") : NULL;
                                        $objExperienceRecurringManually->save();
                                    }
                                }
                            }
                        } else {
                            \App\ExperienceRecurringManually::where(array("experience_id" => $experience_id))->delete();
                            $objExperienceRecurring = \App\ExperienceRecurring::firstOrNew(array("experience_id" => $experience_id));
                            $objExperienceRecurring->experience_id = $experience_id;
                            $objExperienceRecurring->recurring_type = (!empty(@$request['recurring_type'])) ? $request['recurring_type'] : NULL;
                            $objExperienceRecurring->day_of_week = (!empty(@$request['day_of_week'])) ? (implode(",", $request['day_of_week'])) : NULL;
                            $objExperienceRecurring->week_of_month = (!empty(@$request['week_of_month'])) ? (implode(",", $request['week_of_month'])) : NULL;
                            $objExperienceRecurring->day_of_month = (!empty(@$request['day_of_month'])) ? (implode(",", $request['day_of_month'])) : NULL;
                            $objExperienceRecurring->month_of_year = (!empty(@$request['month_of_year'])) ? (implode(",", $request['month_of_year'])) : NULL;
                            $objExperienceRecurring->recurring_end_date = (!empty(@$request['recurring_end_date'])) ? Carbon::parse($request['recurring_end_date'])->format("Y-m-d") : NULL;
                            $objExperienceRecurring->save();
                        }
                    }

                    // Move Images from tmp to src
                    if (!empty(@$image_galleries)) {
                        $image_galleries_array = explode("|@|@|", @$image_galleries);
                        foreach ($image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            Storage::disk('s3')->move($galimage, $dest);
                            $objExperienceImageGallery = new \App\ExperienceImageGallery();
                            $objExperienceImageGallery->experience_id = $experience_id;
                            $objExperienceImageGallery->image_title = basename($dest);
                            $objExperienceImageGallery->image_url = $dest;
                            $objExperienceImageGallery->save();
                        }
                    }

                    // Move Images from tmp to src
                    if (!empty(@$food_image_galleries)) {
                        $food_image_galleries_array = explode("|@|@|", @$food_image_galleries);
                        foreach ($food_image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            Storage::disk('s3')->move($galimage, $dest);
                            $objExperienceImageGallery = new \App\ExperienceFoodImageGallery();
                            $objExperienceImageGallery->experience_id = $experience_id;
                            $objExperienceImageGallery->image_title = basename($dest);
                            $objExperienceImageGallery->image_url = $dest;
                            $objExperienceImageGallery->save();
                        }
                    }

                    // Experience Category Mapping
                    $existexpcatIds = (@$request['hdn_exp_cat_id']) ? explode("||", $request['hdn_exp_cat_id']) : array();
                    if (sizeof(@$existexpcatIds) > 0) {
                        foreach (@$existexpcatIds as $hdn_exp_cat_id) {
                            if (!in_array($hdn_exp_cat_id, @$experience_category_ids) or empty(@$experience_category_ids)) {
                                $objExperienceCategory = \App\ExperienceCategory::select("id")->where(array("experience_id" => $experience_id, "category_id" => $hdn_exp_cat_id))->first();
                                if (!empty($objExperienceCategory)) {
                                    $objExperienceCategory->delete();
                                }
                            }
                        }
                    }

                    if (sizeof((array)@$experience_category_ids) > 0) {
                        foreach (@$experience_category_ids as $experience_category_id) {
                            if (!in_array($experience_category_id, @$existexpcatIds)) {
                                $objExperienceCategory = new \App\ExperienceCategory();
                                $objExperienceCategory->experience_id = $experience_id;
                                $objExperienceCategory->category_id = $experience_category_id;
                                $objExperienceCategory->save();
                            }
                        }
                    }

                    // Experience Teacher Mapping
                    $existTeacherIds = (@$request['hdn_teacher_id']) ? explode("||", $request['hdn_teacher_id']) : array();
                    if (sizeof((array)$existTeacherIds) > 0) {
                        foreach (@$existTeacherIds as $hdn_teacher_id) {
                            if (!in_array($hdn_teacher_id, @$teacher_ids) or empty(@$teacher_ids)) {
                                $objExperienceTeachers = \App\ExperienceTeachers::select("id")->where(array("teacher_id" => $hdn_teacher_id, "experience_id" => $experience_id))->first();
                                if (!empty($objExperienceTeachers)) {
                                    $objExperienceTeachers->delete();
                                }
                            }
                        }
                    }

                    if (sizeof((array)@$teacher_ids) > 0) {
                        foreach ($teacher_ids as $teacher_id) {
                            if (!in_array($teacher_id, @$existTeacherIds)) {
                                $objExperienceTeachers = new \App\ExperienceTeachers();
                                $objExperienceTeachers->experience_id = $experience_id;
                                $objExperienceTeachers->teacher_id = $teacher_id;
                                $objExperienceTeachers->save();
                            }
                        }
                    }
                    // Center Accomodation
                    if (sizeof((array)@$request['accomodation_title']) > 0) {
                        if (@$request['accomodation_default'] && (@$request['accomodation_default'] > 0)) {
                            list($acmdefaultvalue, $acmdefaultkey) = explode("-", $request['accomodation_default']);
                        }
                        foreach ($request['accomodation_title'] as $acm_key => $accomodation_title) {
                            if (!empty($request['accomodation_title'][$acm_key])) {
                                if (!empty($request['center_accomodation_id'][$acm_key])) {
                                    $objExperienceAccomodations = \App\ExperienceAccomodations::find($request['center_accomodation_id'][$acm_key]);
                                } else {
                                    $objExperienceAccomodations = new \App\ExperienceAccomodations();
                                }
                                $objExperienceAccomodations->experience_id = $experience_id;
                                $objExperienceAccomodations->title = $request['accomodation_title'][$acm_key];
                                // $objExperienceAccomodations->about = $request['about_accomodation'][$acm_key];
                                $objExperienceAccomodations->price_per_night_per_guest = $request['price_per_night_per_guest'][$acm_key];
                                $objExperienceAccomodations->currency = $request['currency'][$acm_key];
                                //$objExperienceAccomodations->max_guest_in_room = $request['max_guest_in_room'][$acm_key];
                                if (@$acmdefaultkey == $acm_key) {
                                    $objExperienceAccomodations->accomodation_default = 1;
                                } else {
                                    $objExperienceAccomodations->accomodation_default = 0;
                                }
                                $objExperienceAccomodations->save();

                                if (!empty($request['accomodation_daterange'][$acm_key]) && !empty($request['accomodation_price'][$acm_key])) {
                                    foreach ($request['accomodation_daterange'][$acm_key] as $acpkey => $accomodation_date) {
                                        if (!empty($accomodation_date) && !empty($request['accomodation_price'][$acm_key][$acpkey])) {
                                            if (!empty($request['experience_accomodation_price_id'][$acm_key][$acpkey])) {
                                                $objExperienceAccomodationPrices = \App\ExperienceAccomodationPrices::find($request['experience_accomodation_price_id'][$acm_key][$acpkey]);
                                                if (empty($objExperienceAccomodationPrices)) {
                                                    $objExperienceAccomodationPrices = new \App\ExperienceAccomodationPrices();
                                                }
                                            } else {
                                                $objExperienceAccomodationPrices = new \App\ExperienceAccomodationPrices();
                                            }
                                            $objExperienceAccomodationPrices->experience_id = $experience_id;
                                            $objExperienceAccomodationPrices->accomodation_id = $request['accomodation_title'][$acm_key];
                                            $araccomodation_date = explode(" - ", @$accomodation_date);
                                            $startDate = @$araccomodation_date[0];
                                            $endDate = @$araccomodation_date[1];
                                            $objExperienceAccomodationPrices->duration = @$request['accomodation_durations'][$acm_key][$acpkey];
                                            $objExperienceAccomodationPrices->start_date = ($startDate) ? Carbon::parse($startDate)->format("Y-m-d") : NULL;
                                            $objExperienceAccomodationPrices->end_date = ($endDate) ? Carbon::parse($endDate)->format("Y-m-d") : NULL;
                                            $objExperienceAccomodationPrices->price_per_night_per_guest = @$request['accomodation_price'][$acm_key][$acpkey];
                                            $objExperienceAccomodationPrices->promotional_price = @$request['accomodation_promotional_price'][$acm_key][$acpkey];
                                            $objExperienceAccomodationPrices->promotional_discount = @$request['accomodation_promotional_discount'][$acm_key][$acpkey];
                                            $objExperienceAccomodationPrices->currency = @$request['currency'][$acm_key];
                                            $objExperienceAccomodationPrices->save();
                                        } else if (!empty($request['experience_accomodation_price_id'][$acm_key][$acpkey])) {
                                            \App\ExperienceAccomodationPrices::find($request['experience_accomodation_price_id'][$acm_key][$acpkey])->delete();
                                        }
                                    }
                                }
                                /* $accomodation_galleries = (!empty($request['accomodation_gallery_ids'][$acm_key])) ? $request['accomodation_gallery_ids'][$acm_key] : "";

                                  // Move Images from tmp to src
                                  if (!empty(@$accomodation_galleries)) {
                                  $accomodation_galleries_array = explode("|@|@|", @$accomodation_galleries);
                                  foreach ($accomodation_galleries_array as $galimage) {
                                  $dest = str_replace("tmp/", "", $galimage);
                                  Storage::disk('s3')->move($galimage, $dest);
                                  $objExperienceAccomodationImageGallery = new \App\ExperienceAccomodationImageGallery();
                                  $objExperienceAccomodationImageGallery->experience_id = $experience_id;
                                  $objExperienceAccomodationImageGallery->accomodation_id = $request['accomodation_title'][$acm_key];
                                  $objExperienceAccomodationImageGallery->image_title = basename($dest);
                                  $objExperienceAccomodationImageGallery->image_url = $dest;
                                  $objExperienceAccomodationImageGallery->save();
                                  }
                                  } */
                            } else if (empty($request['accomodation_title'][$acm_key]) && !empty($request['center_accomodation_id'][$acm_key])) {
                                $objExperienceAccomodations = \App\ExperienceAccomodations::find($request['center_accomodation_id'][$acm_key]);
                                $objExperienceAccomodations->delete();
                            }
                        }
                    }

                    // Experience Duration Accomodation
                    \App\ExperienceDurationPrices::where("experience_id", $experience_id)->delete();
                    if ($request['durations']) {
                        foreach ($request['durations'] as $dur_key => $accomodation_title) {
                            if (!empty($request['durations'][$dur_key])) {
                                $objExperienceDurationPrices = new \App\ExperienceDurationPrices();
                                $objExperienceDurationPrices->experience_id = $experience_id;
                                $objExperienceDurationPrices->duration = $request['durations'][$dur_key];
                                $objExperienceDurationPrices->price = $request['duration_price'][$dur_key];
                                $objExperienceDurationPrices->promo_price = $request['promo_price'][$dur_key];
                                $objExperienceDurationPrices->currency = @$request['duration_currency'][$dur_key];
                                $objExperienceDurationPrices->save();
                            }
                        }
                    }
                } catch (Exception $e) {
                    if ($ajax == false) {
                        return redirect('bbadmin/experiences')
                            ->with('flash_error_message', 'Something went wrong');
                    }
                }
            } else {
                if ($ajax == false) {
                    return redirect('bbadmin/experiences')
                        ->with('flash_error_message', 'Something went wrong');
                }
            }
            if ($ajax == false) {
                return redirect('bbadmin/experiences/edit/' . $objExperience->id)
                    ->with('flash_message', 'Experience ' . $objExperience->name . ' updated');
            }
        } else {
            try {
                $objExperience = new \App\Experiences();
                $objExperience->name = $name;
                $objExperience->slug = $slug;
                $objExperience->tags = $tags;
                $objExperience->gps = $gps;
                $objExperience->meta_title = $meta_title;
                $objExperience->meta_description = $meta_description;
                $objExperience->keywords = $keywords;
                //$objExperience->experience_category = $experience_category;
                $objExperience->center_id = $center_id;
                $objExperience->price_per_person = $price_per_person;
                $objExperience->currency = $currency;
                $objExperience->avg_price = $avg_price;
                $objExperience->batch_size = $batch_size;
                $objExperience->experience_summary = $experience_summary;
                $objExperience->date_time = $date_time;
                $objExperience->start_date_time = $start_date_time;
                $objExperience->end_date_time = $end_date_time;
                $objExperience->is_full_day_event = $is_full_day_event;
                $objExperience->is_recurring = $is_recurring;
                $objExperience->experience_certification_id = $experience_certification;
                $objExperience->language_spoken = $language_spoken;
                $objExperience->experience_overview = $experience_overview;
                $objExperience->styles_taught = $styles_taught;
                $objExperience->food = $food;
                $objExperience->skill_level = $skill_level;
                $objExperience->area = $area;
                $objExperience->atmosphere = $atmosphere;
                $objExperience->experience_details = $experience_details;
                $objExperience->experience_highlights = $experience_highlights;
                $objExperience->food_overview = $food_overview;
                $objExperience->schedule = $experience_schedule;
                $objExperience->what_is_included = $what_is_included;
                $objExperience->what_is_not_included = $what_is_not_included;
                $objExperience->how_to_get_here = $how_to_get_here;
                $objExperience->booking_info = $booking_info;
                $objExperience->cancellation_policy = $cancellation_policy;

                $objExperience->deposit_policy = $deposit_policy;
                $objExperience->deposit_amount = $deposit_amount;
                $objExperience->cancellation_policy_condition = $cancellation_policy_condition;
                $objExperience->cancellation_policy_days = $cancellation_policy_days;
                $objExperience->rest_of_payment = $rest_of_payment;
                $objExperience->rest_of_payment_days = $rest_of_payment_days;
                $objExperience->commission = $commission;
                $objExperience->tax = $tax;
                $objExperience->duration = $duration;

                $objExperience->is_draft = $is_draft;
                $objExperience->is_bookable = $is_bookable;
                $objExperience->display_on_home = $display_on_home;

                $objExperience->eirly_bird_before_days = $eirly_bird_before_days;
                $objExperience->eirly_bird_discount = $eirly_bird_discount;
                $objExperience->eirly_bird_discount_type = $eirly_bird_discount_type;

                $objExperience->offer_start_date = $offer_start_date;
                $objExperience->offer_end_date = $offer_end_date;
                $objExperience->offer_discount = $offer_discount;
                $objExperience->offer_discount_type = $offer_discount_type;

                $objExperience->featured_experiences = $featured_experiences;
                $objExperience->best_off_season_deals = $best_off_season_deals;
                $objExperience->deals_of_the_month = $deals_of_the_month;
                $objExperience->advance_trainings = $advance_trainings;
                $objExperience->weekend_retreats = $weekend_retreats;
                $objExperience->couple_retreats = $couple_retreats;
                $objExperience->blessed_by_the_sea = $blessed_by_the_sea;
                $objExperience->retreats_by_the_mountains = $retreats_by_the_mountains;
                $objExperience->luxury_retreats = $luxury_retreats;
                $objExperience->budget_retreats = $budget_retreats;
                $objExperience->video_url = $videoUrl;
                if ($request['thumbnail_image']) {
                    $objExperience->thumbnail_image_url = $thumbnailUrl;
                }
                if ($request['banner_image']) {
                    $objExperience->banner_image_title = $imageTitle;
                    $objExperience->banner_image_url = $imageUrl;
                }
                if ($request['food_banner_image']) {
                    $objExperience->food_banner_image_title = $foodimageTitle;
                    $objExperience->food_banner_image_url = $foodimageUrl;
                }
                $resExperience = $objExperience->save();
                $experience_id = $objExperience->id;

                // Experience Teacher Mapping
                if (sizeof($teacher_ids) > 0) {
                    foreach ($teacher_ids as $teacher_id) {
                        $objExperienceTeachers = new \App\ExperienceTeachers();
                        $objExperienceTeachers->teacher_id = $teacher_id;
                        $objExperienceTeachers->experience_id = $experience_id;
                        $objExperienceTeachers->save();
                    }
                }

                // Experience Recurring
                if (!empty(@$request['recurring_type'])) {
                    if ((!empty(@$request['recurring_type'])) && (@$request['recurring_type'] == "Manually")) {
                        if ((!empty(@$request['manually_start_date_time'])) && (sizeof(@$request['manually_start_date_time']) > 0)) {
                            $mstdates = @$request['manually_start_date_time'];
                            foreach ($mstdates as $mk => $mstdate) {
                                if (!empty($mstdate)) {
                                    $menddate = @$request['manually_end_date_time'][$mk];
                                    $objExperienceRecurringManually = new \App\ExperienceRecurringManually();
                                    $objExperienceRecurringManually->experience_id = $experience_id;
                                    $objExperienceRecurringManually->start_date = (!empty(@$mstdate)) ? Carbon::parse($mstdate)->format("Y-m-d H:i:s") : NULL;
                                    $objExperienceRecurringManually->end_date = (!empty(@$menddate)) ? Carbon::parse(@$menddate)->format("Y-m-d H:i:s") : NULL;
                                    $objExperienceRecurringManually->save();
                                }
                            }
                        }
                    } else {
                        $objExperienceRecurring = new \App\ExperienceRecurring();
                        $objExperienceRecurring->experience_id = $experience_id;
                        $objExperienceRecurring->recurring_type = (!empty(@$request['recurring_type'])) ? $request['recurring_type'] : NULL;
                        $objExperienceRecurring->day_of_week = (!empty(@$request['day_of_week'])) ? (implode(",", $request['day_of_week'])) : NULL;
                        $objExperienceRecurring->week_of_month = (!empty(@$request['week_of_month'])) ? (implode(",", $request['week_of_month'])) : NULL;
                        $objExperienceRecurring->day_of_month = (!empty(@$request['day_of_month'])) ? (implode(",", $request['day_of_month'])) : NULL;
                        $objExperienceRecurring->month_of_year = (!empty(@$request['month_of_year'])) ? (implode(",", $request['month_of_year'])) : NULL;
                        $objExperienceRecurring->recurring_end_date = (!empty(@$request['recurring_end_date'])) ? Carbon::parse($request['recurring_end_date'])->format("Y-m-d") : NULL;
                        $objExperienceRecurring->save();
                    }
                }

                // Move Images from tmp to src
                if (!empty(@$image_galleries)) {
                    $image_galleries_array = explode("|@|@|", @$image_galleries);
                    foreach ($image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        Storage::disk('s3')->move($galimage, $dest);
                        $objExperienceImageGallery = new \App\ExperienceImageGallery();
                        $objExperienceImageGallery->experience_id = $experience_id;
                        $objExperienceImageGallery->image_title = basename($dest);
                        $objExperienceImageGallery->image_url = $dest;
                        $objExperienceImageGallery->save();
                    }
                }

                // Move Images from tmp to src
                if (!empty(@$food_image_galleries)) {
                    $food_image_galleries_array = explode("|@|@|", @$food_image_galleries);
                    foreach ($food_image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        Storage::disk('s3')->move($galimage, $dest);
                        $objExperienceImageGallery = new \App\ExperienceFoodImageGallery();
                        $objExperienceImageGallery->experience_id = $experience_id;
                        $objExperienceImageGallery->image_title = basename($dest);
                        $objExperienceImageGallery->image_url = $dest;
                        $objExperienceImageGallery->save();
                    }
                }

                if (sizeof((array)@$experience_category_ids) > 0) {
                    foreach (@$experience_category_ids as $experience_category_id) {
                        $objExperienceCategory = new \App\ExperienceCategory();
                        $objExperienceCategory->experience_id = $experience_id;
                        $objExperienceCategory->category_id = $experience_category_id;
                        $objExperienceCategory->save();
                    }
                }

                // Center Accomodation
                if (sizeof((array)@$request['accomodation_title']) > 0) {
                    if (@$request['accomodation_default'] && (@$request['accomodation_default'] > 0)) {
                        list($acmdefaultvalue, $acmdefaultkey) = explode("-", $request['accomodation_default']);
                    }
                    foreach ($request['accomodation_title'] as $acm_key => $accomodation_title) {
                        if (!empty($request['accomodation_title'][$acm_key])) {
                            $objExperienceAccomodations = new \App\ExperienceAccomodations();
                            $objExperienceAccomodations->experience_id = $experience_id;
                            $objExperienceAccomodations->title = $request['accomodation_title'][$acm_key];
                            // $objExperienceAccomodations->about = $request['about_accomodation'][$acm_key];
                            $objExperienceAccomodations->price_per_night_per_guest = $request['price_per_night_per_guest'][$acm_key];
                            $objExperienceAccomodations->currency = $request['currency'][$acm_key];
                            // $objExperienceAccomodations->max_guest_in_room = $request['max_guest_in_room'][$acm_key];
                            if (@$acmdefaultkey == $acm_key) {
                                $objExperienceAccomodations->accomodation_default = 1;
                            } else {
                                $objExperienceAccomodations->accomodation_default = 0;
                            }
                            $objExperienceAccomodations->save();

                            if (!empty($request['accomodation_daterange'][$acm_key]) && !empty($request['accomodation_price'][$acm_key])) {
                                foreach ($request['accomodation_daterange'][$acm_key] as $acpkey => $accomodation_date) {
                                    if (!empty($accomodation_date) && ($request['accomodation_daterange'][$acm_key][$acpkey]) && !empty($request['accomodation_price'][$acm_key])) {
                                        $objExperienceAccomodationPrices = new \App\ExperienceAccomodationPrices();
                                        $objExperienceAccomodationPrices->experience_id = $experience_id;
                                        $objExperienceAccomodationPrices->accomodation_id = $request['accomodation_title'][$acm_key];

                                        $araccomodation_date = explode(" - ", @$accomodation_date);
                                        $startDate = @$araccomodation_date[0];
                                        $endDate = @$araccomodation_date[1];

                                        $objExperienceAccomodationPrices->duration = @$request['accomodation_durations'][$acm_key][$acpkey];
                                        $objExperienceAccomodationPrices->start_date = ($startDate) ? Carbon::parse($startDate)->format("Y-m-d") : NULL;
                                        $objExperienceAccomodationPrices->end_date = ($endDate) ? Carbon::parse($endDate)->format("Y-m-d") : NULL;
                                        $objExperienceAccomodationPrices->price_per_night_per_guest = @$request['accomodation_price'][$acm_key][$acpkey];
                                        $objExperienceAccomodationPrices->promotional_price = @$request['accomodation_promotional_price'][$acm_key][$acpkey];
                                        $objExperienceAccomodationPrices->promotional_discount = @$request['accomodation_promotional_discount'][$acm_key][$acpkey];
                                        $objExperienceAccomodationPrices->currency = @$request['currency'][$acm_key];
                                        $objExperienceAccomodationPrices->save();
                                    }
                                }
                            }

                            /* $accomodation_galleries = (!empty($request['accomodation_gallery_ids'][$acm_key])) ? $request['accomodation_gallery_ids'][$acm_key] : "";

                              // Move Images from tmp to src
                              if (!empty(@$accomodation_galleries)) {
                              $accomodation_galleries_array = explode("|@|@|", @$accomodation_galleries);
                              foreach ($accomodation_galleries_array as $galimage) {
                              $dest = str_replace("tmp/", "", $galimage);
                              Storage::disk('s3')->move($galimage, $dest);
                              $objExperienceAccomodationImageGallery = new \App\ExperienceAccomodationImageGallery();
                              $objExperienceAccomodationImageGallery->experience_id = $experience_id;
                              $objExperienceAccomodationImageGallery->accomodation_id = $request['accomodation_title'][$acm_key];
                              $objExperienceAccomodationImageGallery->image_title = basename($dest);
                              $objExperienceAccomodationImageGallery->image_url = $dest;
                              $objExperienceAccomodationImageGallery->save();
                              }
                              } */
                        }
                    }
                }

                // Experience Duration Accomodation
                \App\ExperienceDurationPrices::where("experience_id", $experience_id)->delete();
                if (@$request['durations']) {
                    foreach ($request['durations'] as $dur_key => $accomodation_title) {
                        if (!empty($request['durations'][$dur_key])) {
                            $objExperienceDurationPrices = new \App\ExperienceDurationPrices();
                            $objExperienceDurationPrices->experience_id = $experience_id;
                            $objExperienceDurationPrices->duration = $request['durations'][$dur_key];
                            $objExperienceDurationPrices->price = $request['duration_price'][$dur_key];
                            $objExperienceDurationPrices->promo_price = $request['promo_price'][$dur_key];
                            $objExperienceDurationPrices->currency = @$request['duration_currency'][$dur_key];
                            $objExperienceDurationPrices->save();
                        }
                    }
                }
            } catch (Exception $e) {
                if ($ajax == false) {
                    return redirect('bbadmin/experiences')
                        ->with('flash_error_message', 'Something went wrong');
                }
            }
            if ($ajax == false) {
                return redirect('bbadmin/experiences/edit/' . $objExperience->id)
                    ->with('flash_message', 'Experience ' . $objExperience->name . ' created');
            }
        }

        if ($ajax == true) {
            echo json_encode($objExperience);
        }
    }

    /**
     * Show the form for ediing a Experience.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '')
    {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/experiences");
        }
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['categories'] = \App\Category::get_data($param);

        $paramC['select'] = array("id", "name", "have_accomodation");
        $paramC['order'] = "ASC";
        $paramC['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($paramC);

        $data['teachers'] = \App\Teachers::get_data($param);
        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['eexperience'] = Experiences::get_data($param);
        $data['eexperience'] = (@$data['eexperience'][0]) ?: array();

        // Get Experience Gallery Images
        $paramEGI['select'] = array('id', 'experience_id', 'image_url', 'image_title');
        $paramEGI['where'] = array("experience_id" => $id);
        $data['imagegalleries'] = \App\ExperienceImageGallery::get_data($paramEGI);

        // Get Experience Food Gallery Images
        $paramEFGI['select'] = array('id', 'experience_id', 'image_url', 'image_title');
        $paramEFGI['where'] = array("experience_id" => $id);
        $data['foodimagegalleries'] = \App\ExperienceFoodImageGallery::get_data($paramEFGI);

        // Get Experience Accomodation Gallery Images
        /* $paramEGI['select'] = array('id', 'experience_id', 'accomodation_id', 'image_url', 'image_title');
          $paramEGI['where'] = array("experience_id" => $id);
          $data['accomodationgalleries'] = \App\ExperienceAccomodationImageGallery::get_data($paramEGI); */

        // Get Experience Category
        $paramEC['select'] = array('id', 'category_id', 'experience_id');
        $paramEC['where'] = array("experience_id" => $id);
        $experience_categories = \App\ExperienceCategory::get_data($paramEC);
        $data['experience_categories'] = array();
        if (!empty(@$experience_categories)) {
            foreach (@$experience_categories as $experience_category) {
                $data['experience_categories'][$experience_category->id] = $experience_category->category_id;
            }
        }

        // Get Experience Teachers
        $paramET['select'] = array('id', 'teacher_id');
        $paramET['where'] = array("experience_id" => $id);
        $experience_teachers = \App\ExperienceTeachers::get_data($paramET);
        $data['experience_teachers'] = array();
        if (!empty($experience_teachers)) {
            foreach ($experience_teachers as $experience_teacher) {
                $data['experience_teachers'][$experience_teacher->id] = $experience_teacher->teacher_id;
            }
        }

        // Get Center Accomodations
        $data['center_accomodations'] = array();
        if (@$data['eexperience']->center_id) {
            $center_accomodations = \App\Accomodation::select("accomodation.id", "accomodation.name")->Join("center_accomodations", "accomodation.id", "=", "center_accomodations.accomodation_id")
                ->where("center_accomodations.center_id", @$data['eexperience']->center_id)->get();
            $data['center_accomodations'] = $center_accomodations;
        }

        // Get Experience Recurring
        $paramER['where'] = array("experience_id" => $id);
        $paramER['order'] = "ASC";
        $paramER['orderby'] = "id";
        $paramER['limit'] = 1;
        $experience_recurring = \App\ExperienceRecurring::get_data($paramER);
        $data['experience_recurring'] = @$experience_recurring[0];

        // Get Experience Recurring Manually
        $paramERM['where'] = array("experience_id" => $id);
        $paramERM['order'] = "ASC";
        $paramERM['orderby'] = "id";
        $experience_recurring_manually = \App\ExperienceRecurringManually::get_data($paramERM);
        $data['experience_recurring_manually'] = @$experience_recurring_manually;

        // Get Experience Accomodations
        $paramEA['where'] = array("experience_id" => $id);
        $paramEA['order'] = "ASC";
        $paramEA['orderby'] = "id";
        $data['experience_accomodations'] = \App\ExperienceAccomodations::get_data($paramEA);


        // Get Experience Accomodation Prices
        $paramEAP['where'] = array("experience_id" => $id);
        $paramEAP['order'] = "ASC";
        $paramEAP['orderby'] = "id";
        $data['experience_accomodation_prices'] = \App\ExperienceAccomodationPrices::get_data($paramEAP);

        // Get Experience Duration Prices
        $data['experience_duration_prices'] = \App\ExperienceDurationPrices::where("experience_id", $id)->orderBy("id", "ASC")->get();

        return view('admin.experiences.edit', $data);
    }

    /**
     * Remove the specified Experience from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request['id'];
        try {
            $objExperience = Experiences::find($id);
            if (!empty($objExperience)) {
                /* if (!empty($objExperience->banner_image_url)) {
                  Storage::disk('s3')->delete($objExperience->banner_image_url);
                  } */
                $objExperience->delete();
                \App\ExperienceAccomodations::where("experience_id", $id)->delete();
                \App\ExperienceAccomodationPrices::where("experience_id", $id)->delete();
                \App\ExperienceCategory::where("experience_id", $id)->delete();
                \App\ExperienceTeachers::where("experience_id", $id)->delete();

                \App\ExperienceRecurring::where("experience_id", $id)->delete();
                \App\ExperienceRecurringManually::where("experience_id", $id)->delete();

                \App\ExperienceImageGallery::where("experience_id", $id)->delete();
                \App\ExperienceFoodImageGallery::where("experience_id", $id)->delete();

                /* $paramEGI['where'] = array("experience_id" => $id);
                  $imagegalleries = \App\ExperienceImageGallery::get_data($paramEGI);
                  if (!empty(@$imagegalleries)) {
                  foreach (@$imagegalleries as $imagegallery) {
                  Storage::disk('s3')->delete(@$imagegallery->image_url);
                  $imagegallery->delete();
                  }
                  } */
            } else {
                return redirect('bbadmin/experiences')
                    ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/experiences')
                ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/experiences')
            ->with('flash_message', 'Experience deleted successfully');
    }

    /**
     * Upload Image
     *
     * @param  int  $image
     * @return \Illuminate\Http\Response
     */
    public function upload_image($file)
    {
        // check mime type
        if (
            $file->getClientMimeType() == ("image/png") ||
            $file->getClientMimeType() == ("image/jpeg") ||
            $file->getClientMimeType() == ("image/gif") ||
            $file->getClientMimeType() == ("image/jpg") ||
            $file->getClientMimeType() == ("image/webp")
        ) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'experiences' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    /**
     * Delete Thumbnail Image
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_thumbnail_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperience = Experiences::find($id);
            if (!empty($objExperience)) {
                Storage::disk('s3')->delete($objExperience->thumbnail_image_url);
                $objExperience->thumbnail_image_url = null;
                $objExperience->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Delete Image
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperience = Experiences::find($id);
            if (!empty($objExperience)) {
                Storage::disk('s3')->delete($objExperience->banner_image_url);
                $objExperience->banner_image_title = null;
                $objExperience->banner_image_url = null;
                $objExperience->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    public function delete_food_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperience = Experiences::find($id);
            if (!empty($objExperience)) {
                Storage::disk('s3')->delete($objExperience->food_banner_image_url);
                $objExperience->food_banner_image_title = null;
                $objExperience->food_banner_image_url = null;
                $objExperience->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Delete Food Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_food_gallery_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperienceFoodImageGallery = \App\ExperienceFoodImageGallery::find($id);
            if (!empty($objExperienceFoodImageGallery)) {
                Storage::disk('s3')->delete($objExperienceFoodImageGallery->image_url);
                $objExperienceFoodImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Upload Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_gallery_image(Request $request)
    {
        $file = $request->file('file');
        if (
            $file->getClientMimeType() == ("image/png") ||
            $file->getClientMimeType() == ("image/jpeg") ||
            $file->getClientMimeType() == ("image/gif") ||
            $file->getClientMimeType() == ("image/jpg") ||
            $file->getClientMimeType() == ("image/webp")
        ) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/experiences' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            $saveFileName = $folderName . "/" . $renamefile;
            echo (json_encode(array('success' => true, 'filename' => $saveFileName)));
        } else {
            echo (json_encode(array('success' => false, 'message' => 'Either file is not valid or file not found')));
        }
    }

    /**
     * Delete Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_gallery_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperienceImageGallery = \App\ExperienceImageGallery::find($id);
            if (!empty($objExperienceImageGallery)) {
                Storage::disk('s3')->delete($objExperienceImageGallery->image_url);
                $objExperienceImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Upload ACM Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_accomodation_gallery_image(Request $request)
    {
        $file = $request->file('file');
        if (
            $file->getClientMimeType() == ("image/png") ||
            $file->getClientMimeType() == ("image/jpeg") ||
            $file->getClientMimeType() == ("image/gif") ||
            $file->getClientMimeType() == ("image/jpg") ||
            $file->getClientMimeType() == ("image/webp")
        ) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/experiences/accomodations' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            $saveFileName = $folderName . "/" . $renamefile;
            echo (json_encode(array('success' => true, 'filename' => $saveFileName)));
        } else {
            echo (json_encode(array('success' => false, 'message' => 'Either file is not valid or file not found')));
        }
    }

    /**
     * Delete Accomodation Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_accomodation_gallery_image(Request $request)
    {
        try {
            $id = $request['id'];
            $objExperienceImageGallery = \App\ExperienceImageGallery::find($id);
            if (!empty($objExperienceImageGallery)) {
                Storage::disk('s3')->delete($objExperienceImageGallery->image_url);
                $objExperienceImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Clone Exp
     *
     * @return \Illuminate\Http\Response
     */
    public function clone_exp($id = '')
    {
        if (!empty($id)) {
            $objExperience = Experiences::find($id);
            if ($objExperience) {
                try {
                    // Experience Copy
                    $objCloneExperience = $objExperience->replicate();
                    $objCloneExperience->save();

                    $experience_id = $id;
                    $cloneEperienceId = $objCloneExperience->id;

                    // Experience Recurring Copy
                    if (!empty($objExperience->is_recurring)) {
                        $objExperienceRecurring = \App\ExperienceRecurring::where(array("experience_id" => $experience_id))->first();
                        if (sizeof((array)$objExperienceRecurring) > 0) {
                            $objCloneExperienceRecurring = $objExperienceRecurring->replicate();
                            $objCloneExperienceRecurring->experience_id = $cloneEperienceId;
                            $objCloneExperienceRecurring->save();
                        }

                        $objExperienceRecurringManually = \App\ExperienceRecurringManually::where(array("experience_id" => $experience_id))->first();
                        if (sizeof((array)$objExperienceRecurringManually) > 0) {
                            $objCloneExperienceRecurringManually = $objExperienceRecurringManually->replicate();
                            $objCloneExperienceRecurringManually->experience_id = $cloneEperienceId;
                            $objCloneExperienceRecurringManually->save();
                        }
                    }

                    // Copy Exp Image Galleries
                    $objExperienceImageGallery = \App\ExperienceImageGallery::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceImageGallery) > 0) {
                        foreach (@$objExperienceImageGallery as $objExpImgGlry) {
                            $objCloneExpImgGlry = $objExpImgGlry->replicate();
                            $objCloneExpImgGlry->experience_id = $cloneEperienceId;
                            $objCloneExpImgGlry->save();
                        }
                    }

                    // Copy Exp Food Image Galleries
                    $objExperienceFoodImageGallery = \App\ExperienceFoodImageGallery::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceFoodImageGallery) > 0) {
                        foreach (@$objExperienceFoodImageGallery as $objExpFdImgGlry) {
                            $objCloneExpFdImgGlry = $objExpFdImgGlry->replicate();
                            $objCloneExpFdImgGlry->experience_id = $cloneEperienceId;
                            $objCloneExpFdImgGlry->save();
                        }
                    }

                    // Copy Exp Categories
                    $objExperienceCategories = \App\ExperienceCategory::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceCategories) > 0) {
                        foreach (@$objExperienceCategories as $objExpCat) {
                            $objCloneExpCat = $objExpCat->replicate();
                            $objCloneExpCat->experience_id = $cloneEperienceId;
                            $objCloneExpCat->save();
                        }
                    }

                    // Copy Exp Teachers
                    $objExperienceTeachers = \App\ExperienceTeachers::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceTeachers) > 0) {
                        foreach (@$objExperienceTeachers as $objExpTeach) {
                            $objCloneExpTeach = $objExpTeach->replicate();
                            $objCloneExpTeach->experience_id = $cloneEperienceId;
                            $objCloneExpTeach->save();
                        }
                    }

                    // Copy Exp Accomodations                    
                    $objExperienceAccomodations = \App\ExperienceAccomodations::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceAccomodations) > 0) {
                        foreach (@$objExperienceAccomodations as $objExpAccom) {
                            $objCloneExpAccom = $objExpAccom->replicate();
                            $objCloneExpAccom->experience_id = $cloneEperienceId;
                            $objCloneExpAccom->save();
                        }
                    }

                    // Copy Exp Accomodations Price                 
                    $objExperienceAccomodationPrices = \App\ExperienceAccomodationPrices::where(array("experience_id" => $experience_id))->get();
                    if (sizeof(@$objExperienceAccomodationPrices) > 0) {
                        foreach (@$objExperienceAccomodationPrices as $objExpAccomPrice) {
                            $objCloneExpAccomPrice = $objExpAccomPrice->replicate();
                            $objCloneExpAccomPrice->experience_id = $cloneEperienceId;
                            $objCloneExpAccomPrice->save();
                        }
                    }

                    // Experience Duration Accomodation
                    $objExperienceDurationPrices = \App\ExperienceDurationPrices::where("experience_id", $experience_id)->get();
                    if (sizeof(@$objExperienceDurationPrices) > 0) {
                        foreach (@$objExperienceDurationPrices as $objExperienceDurationPrice) {
                            $objExperienceDurationPrice = $objExperienceDurationPrice->replicate();
                            $objExperienceDurationPrice->experience_id = $cloneEperienceId;
                            $objExperienceDurationPrice->save();
                        }
                    }
                } catch (Exception $e) {
                    return redirect('bbadmin/experiences')
                        ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/experiences')
                    ->with('flash_error_message', 'No Experience Found');
            }
            return redirect('bbadmin/experiences/edit/' . $cloneEperienceId)
                ->with('flash_message', 'Experience ' . $objExperience->name . ' Copied');
        } else {
            return redirect('bbadmin/experiences')
                ->with('flash_message', 'No Experience Found');
        }
    }


    /**
     * Show the form for Upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return view('admin.experiences.upload');
    }

    /**
     * Store a newly Experience in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt',
        ]);
        DB::beginTransaction();
        try {
            if ($_FILES['file']['name']) {
                $existRecord = fopen("exist_records.txt", "w") or die("Unable to open file!");
                $handle = fopen($_FILES['file']['tmp_name'], "r");
                $i = 0;
                $insert = 0;
                $update = 0;
                $exists = 0;
                
                // Get headers and map to column indexes
                $headers = fgetcsv($handle);
                $headerMap = array_flip($headers);
                
                // Define all possible fields from experiences table
                $experienceFields = [
                    'name', 'slug', 'meta_title', 'meta_description', 'keywords',
                    'gps', 'price_per_person', 'currency', 'avg_price', 'price_shared',
                    'price_private', 'website', 'batch_size', 'experience_summary',
                    'date_time', 'start_date_time', 'end_date_time', 'is_full_day_event',
                    'is_recurring', 'experience_certification_id', 'language_spoken',
                    'experience_overview', 'video_url', 'thumbnail_image_url',
                    'banner_image_title', 'banner_image_url', 'banner_image_path',
                    'styles_taught', 'food', 'skill_level', 'area', 'atmosphere',
                    'experience_details', 'experience_highlights', 'food_overview',
                    'food_banner_image_title', 'food_banner_image_url', 'schedule',
                    'what_is_included', 'what_is_not_included', 'how_to_get_here',
                    'booking_info', 'cancellation_policy', 'deposit_policy',
                    'deposit_amount', 'cancellation_policy_condition',
                    'cancellation_policy_days', 'rest_of_payment', 'rest_of_payment_days',
                    'commission', 'tax', 'duration', 'is_bookable', 'is_draft',
                    'display_on_home', 'eirly_bird_before_days', 'eirly_bird_discount',
                    'eirly_bird_discount_type', 'offer_start_date', 'offer_end_date',
                    'offer_discount', 'offer_discount_type', 'featured_experiences',
                    'best_off_season_deals', 'deals_of_the_month', 'advance_trainings',
                    'weekend_retreats', 'couple_retreats', 'blessed_by_the_sea',
                    'retreats_by_the_mountains', 'luxury_retreats', 'budget_retreats',
                    'tags', 'ref_link'
                ];
                
                while ($data = fgetcsv($handle)) {
                    //if ($i > 0) {
                        $id = $data[$headerMap['id']] ?? null;
                        if (!$id) continue;
    
                        // CENTER HANDLING
                        $centerId = null;
                        if (isset($headerMap['center_name'])) {
                            $centerName = trim($data[$headerMap['center_name']] ?? '');
                            
                            if ($centerName) {
                                $centerData = [
                                    'name' => $centerName
                                ];
                                
                                // Add center website if column exists
                                if (isset($headerMap['center_website'])) {
                                    $centerData['website'] = $data[$headerMap['center_website']];
                                }
                                
                                $objCenter = \App\Centers::updateOrCreate(
                                    ['name' => $centerName],
                                    $centerData
                                );
                                $centerId = $objCenter->id;
                            }
                        }
    
                        $objExperience = \App\Experiences::find($id);
                        
                        if (!$objExperience) {
                            // INSERT NEW EXPERIENCE
                            $objExperience = new \App\Experiences();
                            $objExperience->id = $id;
                            
                            // Set center if available
                            if ($centerId) {
                                $objExperience->center_id = $centerId;
                            }
                            
                            // Set all other fields that exist in CSV
                            foreach ($experienceFields as $field) {
                                if (isset($headerMap[$field])) {
                                    $objExperience->$field = $data[$headerMap[$field]];
                                }
                            }
                            
                            $objExperience->save();
                            $insert++;
                        } else {
                            // UPDATE EXISTING EXPERIENCE
                            $updateData = [];
                            
                            // Update center only if center_name exists in CSV
                            if (isset($headerMap['center_name'])) {
                                $updateData['center_id'] = $centerId;
                            }
                            
                            // Update other fields that exist in CSV
                            foreach ($experienceFields as $field) {
                                if (isset($headerMap[$field])) {
                                    $updateData[$field] = $data[$headerMap[$field]];
                                }
                            }
                            
                            if (!empty($updateData)) {
                                $objExperience->update($updateData);
                                $update++;
                            }
                        }
    
                        // PROCESS CATEGORIES if column exists
                        if (isset($headerMap['categories'])) {
                            $this->processCategories(
                                $objExperience,
                                $data[$headerMap['categories']] ?? '',
                                $data[$headerMap['subcategories']] ?? ''
                            );
                        }
                        
                        // PROCESS DESTINATIONS if column exists
                        if (isset($headerMap['destinations'])) {
                            $this->processDestinations(
                                $objExperience,
                                $data[$headerMap['destinations']] ?? '',
                                $data[$headerMap['subdestinations']] ?? ''
                            );
                        }
    
                        $exists++;
                        fwrite($existRecord, $id . "\n");
                    //}
                    $i++;
                }
                fclose($handle);
                fclose($existRecord);
            }
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('bbadmin/experiences')->with('flash_error_message', 'Import failed: ' . $e->getMessage());
        }
        $message = [];
        if ($insert > 0) $message[] = "$insert new experiences inserted";
        if ($update > 0) $message[] = "$update experiences updated";
        if ($exists > 0) $message[] = "$exists records processed";
        
        return redirect('bbadmin/experiences')->with('flash_message',count($message) ? implode(', ', $message) : 'No changes made');
    }
    
    /**
     * Process categories and subcategories for an experience
     * 
     * @param \App\Experiences $experience
     * @param string $categoriesString  Comma-separated categories
     * @param string $subcategoriesString  Comma-separated subcategories
     */
    protected function processCategories($experience, $categoriesString, $subcategoriesString = '')
    {
        // Clear existing category relationships (type=0 only)
        \App\ExperienceCategory::where('experience_id', $experience->id)
            ->whereHas('category', function($query) {
                $query->where('type', 0);
            })->delete();
    
        // Process main categories
        $parentCategories = [];
        $mainCategories = array_filter(array_map('trim', explode(',', $categoriesString)));
        
        foreach ($mainCategories as $name) {
            $category = \App\Category::firstOrCreate(
                ['name' => $name, 'type' => 0],
                ['parent' => null]
            );
            
            \App\ExperienceCategory::create([
                'experience_id' => $experience->id,
                'category_id' => $category->id
            ]);
            
            $parentCategories[$name] = $category->id;
        }
    
        // Process subcategories if provided
        if (!empty($subcategoriesString)) {
            $subCategories = array_filter(array_map('trim', explode(',', $subcategoriesString)));
            
            foreach ($subCategories as $name) {
                // Find matching parent (first word match)
                $parentId = null;
                foreach ($parentCategories as $parentName => $id) {
                    if (str_contains($name, $parentName)) {
                        $parentId = $id;
                        break;
                    }
                }
                
                $category = \App\Category::firstOrCreate(
                    ['name' => $name, 'type' => 0],
                    ['parent' => $parentId]
                );
                
                \App\ExperienceCategory::create([
                    'experience_id' => $experience->id,
                    'category_id' => $category->id
                ]);
            }
        }
}

    /**
     * Process destinations and subdestinations for an experience
     * 
     * @param \App\Experiences $experience
     * @param string $destinationsString  Comma-separated destinations
     * @param string $subdestinationsString  Comma-separated subdestinations
     */
    protected function processDestinations($experience, $destinationsString, $subdestinationsString = '')
    {
        if (empty($destinationsString)) return;
        
        // Delete only destination relationships (type=1)
        \App\ExperienceCategory::where('experience_id', $experience->id)
            ->whereHas('category', function($query) {
                $query->where('type', 1); // 1 = destinations
            })->delete();
        
        // Process main destinations
        $parentDestinations = [];
        foreach (explode(',', $destinationsString) as $name) {
            $name = trim($name);
            if (empty($name)) continue;
            
            $destination = \App\Category::firstOrCreate(
                ['name' => $name, 'type' => 1],
                ['parent' => null]
            );
            
            \App\ExperienceCategory::create([
                'experience_id' => $experience->id,
                'category_id' => $destination->id
            ]);
            
            $parentDestinations[$name] = $destination->id;
        }
        
        // Process subdestinations if provided
        if (!empty($subdestinationsString)) {
            foreach (explode(',', $subdestinationsString) as $name) {
                $name = trim($name);
                if (empty($name)) continue;
                
                // Find matching parent destination (first word match)
                $parentId = null;
                foreach ($parentDestinations as $parentName => $id) {
                    if (str_contains($name, $parentName)) {
                        $parentId = $id;
                        break;
                    }
                }
                
                $destination = \App\Category::firstOrCreate(
                    ['name' => $name, 'type' => 1],
                    ['parent' => $parentId]
                );
                
                \App\ExperienceCategory::create([
                    'experience_id' => $experience->id,
                    'category_id' => $destination->id
                ]);
            }
        }
    }
    public function storeUpload_bk(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt',
        ]);
        try {
            if ($_FILES['file']['name']) {
                $existRecord = fopen("exist_records.txt", "w") or die("Unable to open file!");
                $filename = explode(".", $_FILES['file']['name']);
                $handle = fopen($_FILES['file']['tmp_name'], "r");
                $i = 0;
                $insert = 0;
                $exists = 0;
                while ($data = fgetcsv($handle)) {

                    if ($i > 0) {

                        $objExperience = \App\Experiences::where('id', $data[0])->first();
                        if (!$objExperience) {

                            $centre_id = '';
                            if ($data[1]) {
                                $objCentre = \App\Centers::firstOrNew(['name' => trim($data[1])]);
                                $objCentre->name = trim($data[1]);
                                $objCentre->website = @$data[2];
                                $objCentre->save();;

                                $centre_id = $objCentre->id;
                            }

                            $objExperience = new \App\Experiences();
                            $objExperience->id = $data[0];
                            $objExperience->center_id = $centre_id;
                            $objExperience->name = $data[3];
                            $objExperience->meta_title = $data[4];
                            $objExperience->meta_description = $data[5];
                            $objExperience->website = $data[6];
                            $objExperience->duration = $data[10];
                            $objExperience->price_shared = $data[11];
                            $objExperience->price_private = $data[12];
                            $objExperience->currency = $data[13];
                            $objExperience->avg_price = $data[14];
                            $objExperience->experience_overview = $data[15];
                            $objExperience->styles_taught = $data[16];
                            $objExperience->experience_details = $data[17];
                            $objExperience->food_overview = $data[18];
                            $objExperience->schedule = $data[19];
                            $objExperience->what_is_included = $data[20];
                            $objExperience->what_is_not_included = $data[21];
                            $objExperience->how_to_get_here = $data[22];
                            $objExperience->booking_info = $data[23];
                            $objExperience->cancellation_policy = $data[24];
                            $objExperience->ref_link = $data[25];
                            $objExperience->save();
                            $exp_id = $objExperience->id;

                            if ($data[26]) {
                                foreach (explode(",", $data[26]) as $destination) {
                                    if (trim($destination)) {
                                        $objDestination = \App\Category::firstOrNew(["name" => trim($destination), 'type' => 1]);
                                        if (!$objDestination->exists) {
                                            $objDestination->name = trim($destination);
                                            $objDestination->type = 1;
                                            $objDestination->save();
                                        }
                                        $destination_id = $objDestination->id;

                                        $objExperienceCategory = new \App\ExperienceCategory();
                                        $objExperienceCategory->category_id = $destination_id;
                                        $objExperienceCategory->experience_id = $exp_id;
                                        $objExperienceCategory->save();
                                    }
                                }
                                
                                if ($data[27]) {
                                    foreach (explode(",", $data[27]) as $subdestination) {
                                        if (trim($subdestination)) {
                                            $objSubDestination = \App\Category::firstOrNew(["name" => trim($subdestination), 'type' => 1]);
                                            if (!$objSubDestination->exists) {
                                                $objSubDestination->name = trim($subdestination);
                                                $objSubDestination->type = 1;
                                                $objSubDestination->parent = @$destination_id;
                                                $objSubDestination->save();
                                            }
                                            $subdestination_id = $objSubDestination->id;
    
                                            $objExperienceCategory = new \App\ExperienceCategory();
                                            $objExperienceCategory->category_id = $subdestination_id;
                                            $objExperienceCategory->experience_id = $exp_id;
                                            $objExperienceCategory->save();
                                        }
                                    }
                                }
                            }
                            

                            if ($data[8]) {
                                foreach (explode(",", $data[8]) as $category) {
                                    if (trim($category)) {
                                        $objCategory = \App\Category::firstOrNew(["name" => trim($category), 'type' => 0]);
                                        if (!$objCategory->exists) {
                                            $objCategory->name = trim($category);
                                            $objCategory->type = 0;
                                            $objCategory->save();
                                        }
                                        $category_id = $objCategory->id;

                                        $objExperienceCategory = new \App\ExperienceCategory();
                                        $objExperienceCategory->category_id = $category_id;
                                        $objExperienceCategory->experience_id = $exp_id;
                                        $objExperienceCategory->save();
                                    }
                                }
                                
                                if ($data[9]) {
                                    foreach (explode(",", $data[9]) as $subcategory) {
                                        if (trim($subcategory)) {
                                            $objSubCategory = \App\Category::firstOrNew(["name" => trim($subcategory), 'type' => 0]);
                                            if (!$objSubCategory->exists) {
                                                $objSubCategory->name = trim($subcategory);
                                                $objSubCategory->type = 0;
                                                $objSubCategory->parent = @$category_id;
                                                $objSubCategory->save();
                                            }
                                            $subcategory_id = $objSubCategory->id;
    
                                            $objExperienceCategory = new \App\ExperienceCategory();
                                            $objExperienceCategory->category_id = $subcategory_id;
                                            $objExperienceCategory->experience_id = $exp_id;
                                            $objExperienceCategory->save();
                                        }
                                    }
                                }
                            }

                            $insert++;
                        } else {
                            $exists++;
                            $txt = $data[0] . "\n";
                            fwrite($existRecord, $txt);
                        }
                    }
                    $i++;
                }
            }
        } catch (Exception $e) {
            return redirect('bbadmin/experiences')->with('flash_error_message', 'Something went wrong');
        }
        $txtExist = "";
        if ($exists > 0) {
            $txtExist = " And " . $exists . " Already Exist";
        }
        return redirect('bbadmin/experiences')->with('flash_message', ($insert) . ' Experiences Inserted!' . $txtExist);
    }
}
