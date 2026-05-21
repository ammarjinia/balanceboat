<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
//use App\User;

class DealController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = '')
    {
        $request = request();
        $data = array();
        if ($slug) {
            $data['deal'] = \App\Deals::where("slug", $slug)->first();
            if (@$data['deal']) {
                $cnd = '';
                // Get Center Experiences
                $objDealExp = DB::select('select GROUP_CONCAT(`experience_id`) as exp_ids from deal_experience where deal_id = "' . $data['deal']->id . '"');

                $cnd = '';
                $sortBy = "updated_at";
                $sort = "DESC";
                $data['sortby'] = "popular";
                if (!empty(request()->input('sortby'))) {
                    $sort = explode("-", request()->input('sortby'));
                    $data['sortby'] = @$sort[0];
                    $data['sortto'] = @$sort[1];
                    switch ($sort[0]) {
                        case "price":
                            $sortBy = "final_room_price";
                            $sort = ($sort[1] == "asc") ? "ASC" : "DESC";
                            break;

                        case "popular":
                            $sortBy = "created_at";
                            $sort = ($sort[1] == "asc") ? "ASC" : "DESC";
                            break;

                        case "discount":
                            $sortBy = "totaldiscount";
                            $sort = ($sort[1] == "asc") ? "ASC" : "DESC";
                            break;
                    }
                }
                $offset = 0;
                $limit = -1;
                if ($request->page) {
                    $offset = ($request->page * $limit) - $limit;
                }
                $data['locations'] = array();
                if ($objDealExp['0']->exp_ids) {
                    $cnd = " and ( e.`id` IN (" . $objDealExp['0']->exp_ids . ") )";

                    if (@$_GET["slocation"]) {
                        $cnd .= " and ec.category_id = '" . $_GET["slocation"] . "'";
                    }
                    if (@$_GET["stags"]) {
                        $cnd .= " and FIND_IN_SET('" . $_GET["stags"] . "', e.`tags`)";
                    }
                    $data['experience'] = \App\Experiences::get_exp_price_data($cnd, $sortBy, $sort, $limit, $offset);

                    $expIds = @$objDealExp[0]->exp_ids ? explode(',', $objDealExp[0]->exp_ids) : [];

                    $categoryIds = DB::table('experience_category')
                        ->whereIn('experience_id', $expIds)
                        ->pluck('category_id')
                        ->toArray();

                    $categories = DB::table('category')
                        ->select('id', 'name')
                        ->where('type', 1)
                        ->whereIn('id', $categoryIds)
                        ->orderByRaw('IF(parent = 0, id, parent), parent')
                        ->get();
                    $data['locations'] = $categories;
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
                    $data['tags'] = $predefinedTags->values();
                }
                $data['site_currency'] = \App\Http\Helpers\CommonHelper::get_site_currency();
                return view('deal_detail', $data);
            } else {
                return redirect("/");
            }
        } else {
            $objDeals = \App\Deals::where("status", 0)->orderBy("id", "DESC")->get();
            return view('deals', compact('objDeals'));
        }
    }
}
