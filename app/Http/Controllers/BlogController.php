<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Jenssegers\Agent\Agent;

//use App\User;

class BlogController extends Controller {

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
            $exBlog['where'] = array("slug" => $slug);
            if (!isset($_GET['preview']) or ( $_GET['preview'] != 1)) {
                $exBlog['where']["is_draft"] = 0;
            }
            $blog = \App\Blog::get_data($exBlog);
            $data['blog'] = (@$blog[0]) ? @$blog[0] : array();
            if (!empty(@$data['blog'])) {
                
                $order = "DESC";
                $orderby = "updated_at";
                $blogParam['order'] = $order;
                $blogParam['orderby'] = $orderby;
                $blogParam['limit'] = "5";
                $blogParam['where'] = array("is_draft" => 0);
                $data['blogs'] = \App\Blog::get_data($blogParam);
                
                $data["blog_gallery"] = \App\BlogImageGallery::where("blog_id",@$data['blog']->id)->get();
                
                // Experiences
                $cnd = " and (";
                if (@$data['blog']->tags && (!empty(@$data['blog']->tags))) {
                    foreach(explode(",",@$data['blog']->tags) as $tag) {
                        $cnd .= " `e`.`tags` like '%".$tag."%' OR ";
                    }
                    $cnd = trim($cnd, " OR ").")";
                    $data['experiences'] = \App\Experiences::get_exp_price_data($cnd, $orderby, $order, 5, 0);
                }
                $data['objAdverts'] = \App\Advert::where("is_draft",0)->where("position","Topbar")->orderBy(DB::raw('RAND()'))->get();
                $data['objSideAdverts'] = \App\Advert::where("is_draft",0)->where("position","Sidebar")->orderBy(DB::raw('RAND()'))->get();
                $data['objMarquee'] = \App\Marquee::where("is_draft",0)->where("id",1)->get();
                $agent = new Agent();
                if (!$agent->isDesktop()) {
                    return view('blog_details_amp', $data);    
                } else {
                    return view('blog_details', $data);
                }
            } else {
                return redirect("/blog");
            }
        } else {
            $data = array();
            $order = "DESC";
            $orderby = "updated_at";
            $blogParam['order'] = $order;
            $blogParam['orderby'] = $orderby;
            $blogParam['limit'] = "10";
            $blogParam['where'] = array("is_draft" => 0);
            $data['blogs'] = \App\Blog::get_data($blogParam);
            return view('blog', $data);
        }
    }

    /**
     * Display a listing of the resource on Load more.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadDataAjax(Request $request) {
        $data = array();

        $sdest = (!empty($request['sdestination'])) ? $request['sdestination'] : "";
        $scat = (!empty($request['scategory'])) ? $request['scategory'] : "";
        $sedate = (!empty($request['sexp_date'])) ? \Carbon\Carbon::parse($request['sexp_date'])->format("Y-m-d") : "";
        $objDestinations = \App\ExperienceCategory::select("experience_id")->where("category_id", '36')->distinct()->get();
        $objCategories = \App\ExperienceCategory::select("experience_id")->whereIn('experience_id', @$objDestinations)->where("category_id", 1)->distinct()->get();

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
        $limit = 10;
        if ($request['page']) {
            $offset = ($request['page'] * 10) - 10;
            $limit = $request['page'] * 10;
        }
        $objExp = \App\Experiences::whereIn('id', @$objCategories);
        if ($sedate) {
            $sedateto = \Carbon\Carbon::parse($request['sexp_date'])->addDays(15)->format("Y-m-d");
            $objExp = $objExp->whereBetween("start_date_time", array($sedate, $sedateto));
        }
        $resExp = $objExp->where("is_draft", 0)
                ->skip($offset)
                ->take($limit)
                ->orderBy($orderby, $order)
                ->distinct()
                ->get();
        $data['experiences'] = $resExp;
        $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
        $view = view('content-experience-ajax', $data)->render();
        return response()->json(['html' => $view]);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blog_list($catgorySlug = "") {
        $data = array();
        $data['objCategory'] = array();
        $objBlog = \App\Blog::where("is_draft", 0);
        if ($catgorySlug) {
            $objCategory = \App\Category::where("slug", $catgorySlug)->first();
            $data['objCategory'] = $objCategory; 
            $objBlog->where("category_id", $objCategory->id);
        }
        
        $objBlog = $objBlog->orderBy("updated_at", "DESC")->limit(10)->get();
        $data['blogs'] = $objBlog;
        return view('blog_list', $data);
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
        $objDestinations = \App\ExperienceCategory::select("experience_id")->where("category_id", @$sdest)->distinct()->get();
        if (!empty($scat)) {
            $objCategories = \App\ExperienceCategory::select("experience_id")->whereIn('experience_id', @$objDestinations)->where("category_id", @$scat)->distinct()->get();
        } else {
            $objCategories = $objDestinations;
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
        $limit = 10;
        if ($request['page']) {
            $offset = ($request['page'] * 10) - 10;
            $limit = $request['page'] * 10;
        }
        $objExp = \App\Experiences::whereIn('id', @$objCategories);
        if ($sedate) {
            $sedateto = \Carbon\Carbon::parse($request['sexp_date'])->addDays(15)->format("Y-m-d");
            $objExp = $objExp->whereBetween("start_date_time", array($sedate, $sedateto));
        }
        $resExp = $objExp->where("is_draft", 0)
                ->skip($offset)
                ->take($limit)
                ->orderBy($orderby, $order)
                ->distinct()
                ->get();
        $data['experiences'] = $resExp;
        $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
        $data['sdest'] = $sdest;
        $data['scat'] = $scat;
        $data['sedate'] = $sedate;
        return view('search', $data);
    }
    
    /**
     * Visit website on ads click
     *
     * @return \Illuminate\Http\Response
     */
    public function click_ads(Request $request) {
        if (!$request->filled('url')) {
            return redirect("/");
        }
        try {
            $url = \Crypt::decrypt($request->input("url"));
        } catch (\Exception $ex) {
            $url = '';
        }
        $id = $request->input("id");
        $position = $request->input("position");
        $ip = \Request::getClientIp(true);
        if ($ip) {
            $response = geoip($ip);
            $insertData = array(
                "ip_address" => $ip,
                "blog_id" => $id,
                "position" => $position,
                "website" => @$url,
                "country" => $response['country'],
                "city" => $response['city'],
                "state" => $response['state'],
                "state_name" => $response['state_name'],
                "postal_code" => $response['postal_code'],
                "currency" => $response['currency'],
                "lat" => $response['lat'],
                "lon" => $response['lon'],
                "timezone" => $response['timezone'],
                "iso_code" => $response['iso_code'],
            );
             DB::table('ads_click')->insert($insertData);
             
            
             if (filter_var($url, FILTER_VALIDATE_URL)) {
                return redirect()->to(\App\Http\Helpers\CommonHelper::addhttp($url));
             } else {
                return redirect()->back();
             }
        }
        return redirect()->back();
        exit;
     }

}
