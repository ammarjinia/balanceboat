<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Experiences;
use Carbon\Carbon;
use Storage;

class ExportController extends Controller {

    public function __construct() {
        
    }

    /**
     * Export Report Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['experiences'] = Experiences::select("experiences.*", "centers.name as CenterName")
                ->leftJoin("centers", "centers.id", "=", "experiences.center_id")
                ->orderBy("name", "ASC")
                ->get();
        return view("admin.export.index", $data);
    }

    /**
     * Export Experience Packages Report
     *
     * @return \Illuminate\Http\Response
     */
    public function experience_packages(Request $request) {

        $delimiter = ",";
        $filename = "experience_packages_" . date('Y-m-d') . ".csv";

        //create a file pointer
        $f = fopen('php://memory', 'w');

        //set column headers
        $fields = array('Cent_ID', 'Cent_Name', 'Cent_Email', 'Cent_Meta_Title', 'Cent_Meta_Keywords', 'Cent_Meta_Description', 'Cent_URL', 'Exp_ID', 'Exp_Name', 'Exp_Meta_Title', 'Exp_Meta_Keywords', 'Exp_Meta_Description', 'Exp_,URL', 'Acmd_Id', 'Acmd_Name', 'Acmd_Price');
        fputcsv($f, $fields, $delimiter);

        $order = "DESC";
        $orderby = "e.updated_at";
        $cnd = " e.`is_draft` >= 0";
        if ($request->has("is_draft") && (!empty($request->input("is_draft")))) {
            $cnd = " e.`is_draft` = " . $request->input("is_draft");
            if ($request->input("is_draft") == 0) {
                $cnd .=" AND 1 = (CASE
                                    WHEN
                                        (DATE_SUB(DATE(e.start_date_time),
                                            INTERVAL 2 DAY) >= DATE(NOW()))
                                    THEN
                                        1
                                    WHEN
                                        (erm.id IS NOT NULL
                                            AND DATE_SUB(DATE(erm.start_date),
                                            INTERVAL 2 DAY) >= DATE(NOW()))
                                    THEN
                                        1
                                    WHEN
                                        (er.id IS NOT NULL
                                            AND (DATE(er.recurring_end_date) >= DATE(NOW()) OR er.recurring_type='Daily'))
                                    THEN
                                        1
                                    ELSE 0
                                END)";
            }
        }
        $objExperiences = \App\Experiences::get_exp_price_data_report($cnd, $orderby, $order, $limit = 1000000000, $offset = 0);
        if ($objExperiences) {
            foreach ($objExperiences as $objExperience) {

                /* echo "<pre/>";
                  print_r($objExperience);
                  exit; */

                // Center Detail
                $param = array("select" => array("name", "slug", "meta_title", "keywords", "meta_description", "email_address"), "where" => array("id" => $objExperience->center_id), "limit" => 1);
                $ecenter = \App\Centers::get_data($param);
                $objCenter = (@$ecenter[0]) ? : array();

                $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
                $experience_accomodations = \App\Experiences::get_exp_acm_data($objExperience->id);
                if (sizeof(@$experience_accomodations) > 0) {
                    foreach (@$experience_accomodations as $experience_accomodation) {
                        $discount = 0;
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
                        $acm_price = \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);

                        $lineData = array(
                            $objExperience->center_id,
                            $objCenter->name,
                            $objCenter->email_address,
                            $objCenter->meta_title,
                            $objCenter->keywords,
                            $objCenter->meta_description,
                            url("/center/" . $objCenter->slug),
                            $objExperience->id,
                            $objExperience->name,
                            $objExperience->meta_title,
                            $objExperience->meta_description,
                            $objExperience->keywords,
                            url("/experience/" . $objExperience->slug),
                            $experience_accomodation->id,
                            $experience_accomodation->name,
                            html_entity_decode($acm_price)
                        );
                        fputcsv($f, $lineData, $delimiter);
                    }
                }
            }
            /* echo "<pre />";
              print_r($experience_accomodations);
              exit; */
        }



        //move back to beginning of file
        fseek($f, 0);

        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        //output all remaining data on a file pointer
        fpassthru($f);
    }

}
