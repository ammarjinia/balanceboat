<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Mail;
use Excel;
use Config;
use \Carbon\Carbon;
use DB;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a sitemap form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.reports.experiences.index');
    }

    public function experiences() {
        return view('admin.reports.experiences.index');
    }

    public function generate_experience_report(Request $request) {
        $objExp = \App\Experiences::select('id', 'name', 'slug', 'meta_title', 'meta_description',
                    'gps', 'price_per_person', 'currency', 'avg_price', 'website', 'batch_size', 'experience_summary',
                    'language_spoken', 'experience_overview',
                    'styles_taught', 'food', 'skill_level', 'area', 'atmosphere',
                    'experience_details', 'experience_highlights', 'food_overview','schedule',
                    'what_is_included', 'what_is_not_included', 'how_to_get_here',
                    'booking_info', 'cancellation_policy', 'deposit_policy', 'duration', 'is_bookable', 'is_draft',
                    'tags', 'ref_link');
        if ($request->has("is_draft") && (!empty($request->input("is_draft")))) {
            $objExp->where("is_draft", $request->input("is_draft"));
        }
        if ($request->has("start") && (!empty($request->input("start")))) {
            $objExp->where("updated_at", ">=", Carbon::parse($request->input("start"))->format("Y-m-d"));
        }
        if ($request->has("end") && (!empty($request->input("end")))) {
            $objExp->where("updated_at", "<=", Carbon::parse($request->input("end"))->format("Y-m-d"));
        }
        $data = $objExp->get()->toArray();
        $newdata = array();
        foreach ($data as $key => $value) {
            $value['published_url'] = url("/experience/" . $value['slug']);
            $newdata[] = $value;
        }

        // Define column headings
        $headings = [
                    'id', 'name', 'slug', 'meta_title', 'meta_description',
                    'gps', 'price_per_person', 'currency', 'avg_price', 'website', 'batch_size', 'experience_summary',
                    'language_spoken', 'experience_overview',
                    'styles_taught', 'food', 'skill_level', 'area', 'atmosphere',
                    'experience_details', 'experience_highlights', 'food_overview','schedule',
                    'what_is_included', 'what_is_not_included', 'how_to_get_here',
                    'booking_info', 'cancellation_policy', 'deposit_policy', 'duration', 'is_bookable', 'is_draft',
                    'tags', 'ref_link'
                ];

        Config::set('excel.csv.delimiter', ',');
        $filename = 'Balanceboat_Experiences_' . date("dmY") . ".xls";
        return Excel::download(
            new \App\Exports\ExperienceExport($data,$headings), 
            $filename
        );        
    }

    public function teachers() {
        return view('admin.reports.teachers.index');
    }

    public function generate_teachers_report(Request $request) {
        $objTeach = \App\Teachers::Query();
        if ($request->has("start") && (!empty($request->input("start")))) {
            $objTeach->where("updated_at", ">=", Carbon::parse($request->input("start"))->format("Y-m-d"));
        }
        if ($request->has("end") && (!empty($request->input("end")))) {
            $objTeach->where("updated_at", "<=", Carbon::parse($request->input("end"))->format("Y-m-d"));
        }
        $data = $objTeach->get()->toArray();
        $newdata = array();
        foreach ($data as $key => $value) {
            unset($value["profile_image_path"]);
            unset($value["image_gallery"]);
            $value['Experiences'] = "";
            $objExp = \App\ExperienceTeachers::select("experiences.name")->join("experiences", "experience_id", "=", "experiences.id")->where("teacher_id", $value['id'])->distinct()->get()->pluck("name")->toArray();
            if (!empty($objExp) && (count($objExp) > 0)) {
                $value['Experiences'] = implode("\n", $objExp);
            }
            $value['centers'] = "";
            $objCent = \App\CenterTeachers::select("centers.name")->join("centers", "center_id", "=", "centers.id")->where("teacher_id", $value['id'])->distinct()->get()->pluck("name")->toArray();
            if (!empty($objCent) && (count($objCent) > 0)) {
                $value['centers'] = implode("\n", $objCent);
            }
            $value['expertise'] = "";
            $objExpert = \App\Expertise::select("name")->whereRaw("find_in_set(id, '" . str_replace('||', ',', $value['expertise_id']) . "')")->get()->pluck("name")->toArray();
            if (!empty($objExpert) && (count($objExpert) > 0)) {
                $value['expertise'] = implode("\n", $objExpert);
            }
            $value['certificate'] = "";
            $objExpert = \App\Certificates::select("name")->whereRaw("find_in_set(id, '" . str_replace('||', ',', $value['certificate_id']) . "')")->get()->pluck("name")->toArray();
            if (!empty($objExpert) && (count($objExpert) > 0)) {
                $value['certificate'] = implode("\n", $objExpert);
            }
            $value['published_url'] = url("/teacher/" . $value['slug']);
            $newdata[] = $value;
        }
        return Excel::create('Balanceboat_Teachers_' . date("dmY"), function($excel) use ($newdata) {
                    $excel->sheet('Sheet-1', function($sheet) use ($newdata) {
                        $sheet->fromArray($newdata);
                    });
                })->download("xls");
    }

}
