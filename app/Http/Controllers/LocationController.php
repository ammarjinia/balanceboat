<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use DB;

//use App\User;

class LocationController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($parent = '', $subdest = '') {
        $request = request();
        $data = array();
        
        $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
        $slug = (!empty($subdest)) ? $subdest : $parent;
        $data['parent'] = $parent;
        $data['subdest'] = $subdest;
        $data['destination'] = array();

        // Fetch Destination Details
        $destination = \App\Category::get_data(array("where" => array('slug' => $slug), 'type' => '1', "limit" => 1));
        $data['destination'] = @$destination[0];
        $destId = @$destination[0]->id;
        if (empty($destId)) {
            return redirect("/");
        }

        $categories = \App\Category::select("id")->where(function($query) use($destId) {
                    $query->where("parent", @$destId);
                    $query->orWhere("id", @$destId);
                })->get();

        $arCat = array();
        foreach ($categories as $category) {
            array_push($arCat, $category->id);
        }
        $data['categories'] = array();
        $having = " HAVING find_in_set($destId,groupcat) ";
        if (request()->has('category') && request()->input('category')) {
            $subCatSlug = request()->input('category');
            $objSubCat = \App\Category::select("id")->where(['slug' => $subCatSlug, 'type' => '0'])->first();
            $subCatId = $objSubCat?->id;
            if (@$subCatId) {
            $having .= "  and find_in_set($subCatId,groupcat) ";
            array_push($arCat, $subCatId);
            $data['categories'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title", "experience_category.experience_id", DB::raw("group_concat(" . implode(',', $arCat) . ") as groupcat"))->Join('category', function($join) use($subCatId) {
                        $join->on('category.id', '=', 'experience_category.category_id');
                        $join->where("type", "0");
                    })->whereIn("experience_category.category_id", $arCat)->groupBy("experience_category.experience_id")->havingRaw("find_in_set($subCatId,groupcat)")->orderBy("name", "ASC")->take(5)->distinct()->get();
            }
        } else {
            $agent = new Agent();
            if ($agent->isDesktop()) {
            $data['categories'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title")->join('experience_category as expcat', function($join) use($arCat) {
                        $join->on('expcat.experience_id', '=', 'experience_category.experience_id');
                    })->Join('category', function($join) {
                        $join->on('category.id', '=', 'expcat.category_id');
                        $join->where("type", "0");
                        $join->where("parent", "0");
                    })->whereIn("experience_category.category_id", $arCat)->orderBy("name", "ASC")->take(5)->distinct()->get();
            } else {
                $data['categories'] = array();
            }
            
        }

        $data['subdestinations'] = array();
        if (empty($subdest)) {
            $data['subdestinations'] = \App\Category::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title")->leftJoin('experience_category', function($join) {
                        $join->on('category.id', '=', 'experience_category.category_id');
                    })->where(function($query) use ($arCat) {
                        $query->whereIn("category_id", $arCat);
                        $query->where("type", "1");
                        $query->where("parent", "!=", "0");
                    })->orderBy("category.name", "ASC")->take(5)->distinct()->get();
        }

        $objCatExps = DB::select("SELECT 
                    `experience_category`.`experience_id`,
                        GROUP_CONCAT(`experience_category`.`category_id`) AS groupcat
                    FROM
                        `experience_category`
                            INNER JOIN
                        `category` ON `category`.`id` = `experience_category`.`category_id`
                    where `experience_category`.`category_id` IN (" . implode(",", (array) $arCat) . ")
                    GROUP BY `experience_category`.`experience_id`
                    $having 
                    ORDER BY `experience_category`.`experience_id` ASC");
        
        $arExpId = array();
        foreach ($objCatExps as $objCatExp) {
            array_push($arExpId, $objCatExp->experience_id);
        }
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
        if (!empty(request()->input('scategory'))) {
            $scategory = request()->input('scategory');
            $data['scategory'] = $scategory;
            $cnd .= " and (";
            $cnd .= " `ec`.`category_id` = '" . $scategory . "'";
            $cnd .= ")";
        }
        /*$data['sdestination'] = "";
        if (!empty(request()->input('sdestination'))) {
            $sdestination = request()->input('sdestination');
            $data['sdestination'] = $sdestination;
            $cnd .= " and (";
            $cnd .= " `experience_category`.`category_id` = '" . $sdestination . "'";
            $cnd .= ")";
        }*/
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
        $cnd .= ' AND e.id IN ("' . implode('","', (array) $arExpId) . '")';
        if($request->ajax()){ 
            $offset = 0;
            $limit = 20;
            if ($request['page']) {
                $offset = ($request['page'] * $limit) - $limit;
                $limit = $request['page'] * $limit;
            }
            $data['experiences'] = \App\Experiences::get_exp_deal_price_data($cnd, $orderby, $order, $limit, $offset, $having);
            $view = view('content-experience-ajax', $data)->render();
            return response()->json(['html' => $view]);
        } else {
            $data['experiences'] = \App\Experiences::get_exp_deal_price_data($cnd, $orderby, $order, 20, 0, $having);
            $max_experience_price = \App\Experiences::get_exp_deal_price_data($cnd, "final_room_price", "DESC", 1, 0, $having);
            $data['max_experience_price'] = @$max_experience_price[0]->final_room_price;
            $min_experience_price = \App\Experiences::get_exp_deal_price_data($cnd, "final_room_price", "ASC", 1, 0, $having);
            $data['min_experience_price'] = @$min_experience_price[0]->final_room_price;
            $data['sort_by'] = (!empty(@$_GET['sort_by'])) ? @$_GET['sort_by'] : "newest";
        }
        return view('location', $data);
    }

}
