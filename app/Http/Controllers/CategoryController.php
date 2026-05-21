<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

//use App\User;

class CategoryController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index($parent = '', $subcat = '') {
        $data = array();
        $slug = (!empty($subcat)) ? $subcat : $parent;
        $data['category'] = array();

        // Fetch category Details
        $category = \App\Category::get_data(array("where" => array('slug' => $slug), 'type' => '0', "limit" => 1));
        $data['category'] = @$category[0];
        $catId = @$category[0]->id;
        if (empty($catId)) {
            return redirect("/");
        }

        $categories = \App\Category::select("id")->where(function($query) use($catId) {
                    $query->where("parent", @$catId);
                    $query->orWhere("id", @$catId);
                })->get();

        $arCat = array();
        foreach ($categories as $category) {
            array_push($arCat, $category->id);
        }
        $having = " HAVING find_in_set($catId,groupcat) ";
        if (request()->has('destination')) {
            $subCatSlug = request()->input('destination');
            $objSubCat = \App\Category::get_data(array("select" => array("id"), "where" => array('slug' => $subCatSlug), 'type' => '1', "limit" => 1));
            $subCatId = '';
            if ($objSubCat) {
                $subCatId = $objSubCat[0]->id;
                $having .= "  and find_in_set($subCatId,groupcat) ";
                array_push($arCat, $subCatId);
            }
            $data['categories'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title", "experience_category.experience_id", DB::raw("group_concat(" . implode(',', $arCat) . ") as groupcat"))->Join('category', function($join) use($subCatId) {
                        $join->on('category.id', '=', 'experience_category.category_id');
                        $join->where("type", "1");
                    })->whereIn("experience_category.category_id", $arCat)->groupBy("experience_category.experience_id")->havingRaw("find_in_set($subCatId,groupcat)")->orderBy("name", "ASC")->take(5)->distinct()->get();
        } else {
            $data['destinations'] = \App\ExperienceCategory::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title")->join('experience_category as expcat', function($join) use($arCat) {
                        $join->on('expcat.experience_id', '=', 'experience_category.experience_id');
                    })->Join('category', function($join) {
                        $join->on('category.id', '=', 'expcat.category_id');
                        $join->where("type", "1");
                        $join->where("parent", "0");
                    })->whereIn("experience_category.category_id", $arCat)->orderBy("name", "ASC")->take(5)->distinct()->get();
        }

        $data['subcategories'] = array();
        if (empty($subcat)) {
            $data['subcategories'] = \App\Category::select("category.id", "category.name", "category.slug", "category.image_url", "category.image_title", "category.banner_image_url", "category.banner_image_title")->leftJoin('experience_category', function($join) {
                        $join->on('category.id', '=', 'experience_category.category_id');
                    })->where(function($query) use ($arCat) {
                        $query->whereIn("category_id", $arCat);
                        $query->where("type", "0");
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
        $cnd = ' AND e.id IN ("' . implode('","', (array) $arExpId) . '")';
        $data['experiences'] = \App\Experiences::get_exp_price_data($cnd, $orderby, $order, 100);
        $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
        return view('category', $data);
    }

}
