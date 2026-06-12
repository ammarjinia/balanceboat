<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Swap;

class Experiences extends Model {

    protected $table = "experiences";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objExperience = Experiences::query();
        if (@$param['select']) {
            $objExperience = $objExperience->select($param['select']);
        }
        if (@$param['where']) {
            $objExperience = $objExperience->where($param['where']);
        }
        if (@$param['offset']) {
            $objExperience = $objExperience->skip($param['offset']);
        }
        if (@$param['limit']) {
            $objExperience = $objExperience->take($param['limit']);
        }
        $resExperience = $objExperience->orderBy($orderby, $order)->get();
        return $resExperience;
    }

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience with accommondation price
     */
    /*public static function get_exp_price_data_bk($cnd = '', $orderby = 'e.updated_at', $order = 'DESC', $limit = 10, $offset = 0, $having='') {
        $site_currency_rate = \App\Http\Helpers\CommonHelper::get_site_currency_rate();
        $newlimit = ($limit>=0) ? " LIMIT $offset, $limit" : "";
        $resExp = DB::select("SELECT 
                                e.id, e.name, e.slug, e.featured_experiences, e.thumbnail_image_url, e.banner_image_url, e.banner_image_title, e.experience_summary, e.center_id, e.updated_at, e.created_at,
                                e.start_date_time as experience_date,
                                cur.rate,
                                ('".$site_currency_rate."' * e.avg_price) / cur.rate AS final_room_price
                            FROM
                                experiences e
                                    LEFT JOIN
                                experience_category ON experience_category.experience_id = e.id
                                    LEFT JOIN
                                currency cur ON e.currency = cur.name
                            WHERE
                                e.`is_draft` = 0 $cnd 
                            GROUP BY e.id
                            $having 
                            ORDER BY $orderby $order, final_room_price ASC
                            $newlimit");
        
        return $resExp;
    }*/

    public static function get_exp_price_data($cnd = '', $orderby = 'e.updated_at', $order = 'DESC', $limit = 10, $offset = 0, $having='') {
        $site_currency_rate = \App\Http\Helpers\CommonHelper::get_site_currency_rate();
        $newlimit = ($limit >= 0) ? " LIMIT $offset, $limit" : "";
        
        /*$resExp = DB::select("
            SELECT 
                e.id, 
                e.name, 
                e.slug, 
                e.featured_experiences, 
                e.thumbnail_image_url, 
                e.banner_image_url, 
                e.banner_image_title, 
                e.experience_summary, 
                e.center_id, 
                e.updated_at, 
                e.created_at,
                e.start_date_time as experience_date,
                cur.rate,
                ('".$site_currency_rate."' * e.avg_price) / cur.rate AS final_room_price,
                ('".$site_currency_rate."' * min(dp.price)) / cur.rate AS min_duration_price
            FROM
                experiences e
            LEFT JOIN
                experience_category ec ON ec.experience_id = e.id
            LEFT JOIN
                currency cur ON e.currency = cur.name
            LEFT JOIN
                experience_duration_prices dp ON dp.experience_id = e.id
            WHERE
                e.is_draft = 0 $cnd 
            GROUP BY 
                e.id
            $having 
            ORDER BY 
                $orderby $order, min_duration_price ASC
            $newlimit
        ");*/
        

        $resExp = DB::select("
            SELECT 
                e.id, 
                e.name, 
                e.slug, 
                e.featured_experiences, 
                e.thumbnail_image_url, 
                e.banner_image_url, 
                e.banner_image_title, 
                e.experience_summary, 
                e.center_id, 
                e.updated_at, 
                e.created_at,
                e.offer_start_date,
                e.offer_end_date,
                e.offer_discount_type,
                e.offer_discount,
                e.start_date_time as experience_date,
                cur.rate,
                ('".$site_currency_rate."' * e.avg_price) / cur.rate AS final_room_price,
                ('".$site_currency_rate."' * subquery.min_price) / cur.rate AS min_duration_price,
                ('".$site_currency_rate."' * subquery.min_promo_price) / cur.rate AS min_promo_price
            FROM
                experiences e
            LEFT JOIN
                experience_category ec ON ec.experience_id = e.id
            LEFT JOIN
                currency cur ON e.currency = cur.name
            LEFT JOIN (
                SELECT 
                    experience_id,
                    MIN(price) AS min_price,
                    MIN(promo_price) AS min_promo_price
                FROM 
                    experience_duration_prices
                GROUP BY 
                    experience_id
            ) subquery ON subquery.experience_id = e.id
            WHERE
                e.is_draft = 0 $cnd 
            GROUP BY 
                e.id
            $having 
            ORDER BY 
                $orderby $order, min_duration_price ASC
            $newlimit
        ");
        return $resExp;
    }
    
    public static function get_exp_deal_price_data($cnd = '', $orderby = 'e.updated_at', $order = 'DESC', $limit = 10, $offset = 0, $having='') {
        $site_currency_rate = \App\Http\Helpers\CommonHelper::get_site_currency_rate();
        $newlimit = ($limit >= 0) ? " LIMIT $offset, $limit" : "";
        
        /*$resExp = DB::select("
            SELECT 
                e.id, 
                e.name, 
                e.slug, 
                e.featured_experiences, 
                e.thumbnail_image_url, 
                e.banner_image_url, 
                e.banner_image_title, 
                e.experience_summary, 
                e.center_id, 
                e.updated_at, 
                e.created_at,
                e.start_date_time as experience_date,
                cur.rate,
                ('".$site_currency_rate."' * e.avg_price) / cur.rate AS final_room_price,
                ('".$site_currency_rate."' * min(dp.price)) / cur.rate AS min_duration_price
            FROM
                experiences e
            LEFT JOIN
                experience_category ec ON ec.experience_id = e.id
            LEFT JOIN
                currency cur ON e.currency = cur.name
            LEFT JOIN
                experience_duration_prices dp ON dp.experience_id = e.id
            WHERE
                e.is_draft = 0 $cnd 
            GROUP BY 
                e.id
            $having 
            ORDER BY 
                $orderby $order, min_duration_price ASC
            $newlimit
        ");*/
        

        $resExp = DB::select("
            SELECT 
                e.id, 
                e.name, 
                e.slug, 
                e.featured_experiences, 
                e.thumbnail_image_url, 
                e.banner_image_url, 
                e.banner_image_title, 
                e.experience_summary, 
                e.center_id, 
                e.updated_at, 
                e.created_at,
                e.offer_start_date,
                e.offer_end_date,
                e.offer_discount_type,
                e.offer_discount,
                e.start_date_time as experience_date,
                cur.rate,
                ('".$site_currency_rate."' * e.avg_price) / cur.rate AS final_room_price,
                ('".$site_currency_rate."' * subquery.min_price) / cur.rate AS min_duration_price,
                ('".$site_currency_rate."' * subquery.min_promo_price) / cur.rate AS min_promo_price
            FROM
                experiences e
            LEFT JOIN
                experience_category ec ON ec.experience_id = e.id
            LEFT JOIN
                currency cur ON e.currency = cur.name
            LEFT JOIN (
                SELECT 
                    experience_id,
                    MIN(price) AS min_price,
                    MIN(promo_price) AS min_promo_price
                FROM 
                    experience_duration_prices
                GROUP BY 
                    experience_id
            ) subquery ON subquery.experience_id = e.id
            WHERE
                e.is_draft = 0 $cnd 
            GROUP BY 
                e.id
            $having 
            ORDER BY 
                $orderby $order, min_duration_price ASC
            $newlimit
        ");
        return $resExp;
    }
    
    

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience with accommondation price
     */
    public static function get_exp_price_data_report($cnd = '1', $orderby = 'e.updated_at', $order = 'DESC', $limit = 10, $offset = 0) {
        $resExp = DB::select("SELECT 
                                e.*,
                                e.start_date_time as experience_date,
                                erm.id as erm_id,
                                erm.start_date as recurring,
                                er.recurring_type,
                                (CASE
                                    WHEN
                                        (DATE_SUB(DATE(e.start_date_time),
                                            INTERVAL 2 DAY) >= DATE(NOW())
                                            AND e.is_recurring != 1)
                                    THEN
                                        CONCAT((CASE
                                                    WHEN
                                                        ((MONTHNAME(e.start_date_time) != MONTHNAME(e.end_date_time))
                                                            AND (YEAR(e.start_date_time) = YEAR(e.end_date_time)))
                                                    THEN
                                                        (DATE_FORMAT(e.start_date_time, '%d %b'))
                                                    WHEN (YEAR(e.start_date_time) != YEAR(e.end_date_time)) THEN (DATE_FORMAT(e.start_date_time, '%d %b %Y'))
                                                    ELSE EXTRACT(DAY FROM e.start_date_time)
                                                END),
                                                '-',
                                                DATE_FORMAT(e.end_date_time, '%d %b %Y'))
                                    WHEN
                                        (DATE_SUB(DATE(e.start_date_time),
                                            INTERVAL 2 DAY) >= DATE(NOW())
                                            AND e.is_recurring = 1)
                                    THEN
                                        CONCAT((CONCAT((CASE
                                                            WHEN
                                                                ((MONTHNAME(e.start_date_time) != MONTHNAME(e.end_date_time))
                                                                    AND (YEAR(e.start_date_time) = YEAR(e.end_date_time)))
                                                            THEN
                                                                (DATE_FORMAT(e.start_date_time, '%d %b'))
                                                            WHEN (YEAR(e.start_date_time) != YEAR(e.end_date_time)) THEN (DATE_FORMAT(e.start_date_time, '%d %b %Y'))
                                                            ELSE EXTRACT(DAY FROM e.start_date_time)
                                                        END),
                                                        '-',
                                                        (DATE_FORMAT(e.end_date_time, '%d %b %Y')))),
                                                ' | ',
                                                (CASE  WHEN (er.id IS NOT NULL AND er.recurring_type = 'Weekly') THEN (
                                                  CONCAT('Starts on Every ',DATE_FORMAT(CONCAT('2017-09-1',er.day_of_week),'%W'))
                                                    ) 
                                                                    ELSE
                                                GROUP_CONCAT(DISTINCT (CONCAT((CASE
                                                                WHEN
                                                                    ((MONTHNAME(erm.start_date) != MONTHNAME(erm.end_date))
                                                                        AND (YEAR(erm.start_date) = YEAR(erm.end_date)))
                                                                THEN
                                                                    (DATE_FORMAT(erm.start_date, '%d %b'))
                                                                WHEN (YEAR(erm.start_date) != YEAR(erm.end_date)) THEN (DATE_FORMAT(erm.start_date, '%d %b %Y'))
                                                                ELSE EXTRACT(DAY FROM erm.start_date)
                                                            END),
                                                            '-',
                                                            DATE_FORMAT(erm.end_date, '%d %b %Y')))
                                                    ORDER BY (erm.start_date)
                                                    SEPARATOR ' | ')
                                                                    END )
                                                )
                                    WHEN
                                        (erm.id IS NOT NULL
                                            AND DATE_SUB(DATE(erm.start_date),
                                            INTERVAL 2 DAY) >= DATE(NOW()))
                                    THEN
                                        GROUP_CONCAT(DISTINCT (CONCAT((CASE
                                                        WHEN (er.id IS NOT NULL AND e.is_recurring = 1) THEN (er.recurring_type)
                                                        WHEN
                                                            ((MONTHNAME(erm.start_date) != MONTHNAME(erm.end_date))
                                                                AND (YEAR(erm.start_date) = YEAR(erm.end_date)))
                                                        THEN
                                                            (DATE_FORMAT(erm.start_date, '%d %b'))
                                                        WHEN (YEAR(erm.start_date) != YEAR(erm.end_date)) THEN (DATE_FORMAT(erm.start_date, '%d %b %Y'))
                                                        ELSE EXTRACT(DAY FROM erm.start_date)
                                                    END),
                                                    '-',
                                                    DATE_FORMAT(erm.end_date, '%d %b %Y')))
                                            ORDER BY (erm.start_date)
                                            SEPARATOR ' | ')
                                            WHEN  (er.id IS NOT NULL) THEN (CASE 
                                    WHEN (er.id IS NOT NULL AND er.recurring_type = 'Weekly') THEN (
                                                 CONCAT('Starts on Every ',DATE_FORMAT(CONCAT('2017-09-1',er.day_of_week),'%W'))  
                                                    ) 
                                    END)
                                END) AS available_month,
                                ea.price_per_night_per_guest AS default_price,
                                eap.start_date,
                                eap.end_date,
                                eap.price_per_night_per_guest,
                                (CASE
                                    WHEN
                                        (MONTH(eap.start_date) <= MONTH(NOW())
                                            AND MONTH(eap.end_date) >= MONTH(NOW())
                                            AND EXTRACT(DAY FROM NOW()) >= EXTRACT(DAY FROM eap.start_date)
                                            AND EXTRACT(DAY FROM NOW()) <= EXTRACT(DAY FROM eap.end_date)
                                            AND eap.price_per_night_per_guest IS NOT NULL)
                                    THEN
                                        eap.price_per_night_per_guest
                                    ELSE ea.price_per_night_per_guest
                                END) AS room_price,
                                ea.currency AS accomodation_currency
                            FROM
                                experiences e
                                    LEFT JOIN
                                experience_accomodations ea ON e.id = ea.experience_id
                                    AND ea.accomodation_default = 1
                                    LEFT JOIN
                                experience_accomodation_prices eap ON ea.title = eap.accomodation_id
                                    AND eap.experience_id=ea.experience_id 
                                    AND eap.price_per_night_per_guest IS NOT NULL
                                    AND (MONTH(eap.start_date) <= MONTH(NOW())
                                    AND MONTH(eap.end_date) >= MONTH(NOW())
                                    AND EXTRACT(DAY FROM NOW()) >= EXTRACT(DAY FROM eap.start_date)
                                    AND EXTRACT(DAY FROM NOW()) <= EXTRACT(DAY FROM eap.end_date))
                                    LEFT JOIN
                                experience_category ON experience_category.experience_id = e.id
                                    LEFT JOIN
                                experience_recurring er ON er.experience_id = e.id
                                    LEFT JOIN
                                experience_recurring_manually erm ON erm.experience_id = e.id
                            WHERE
                                 $cnd 
                            GROUP BY ea.experience_id , accomodation_id 
                            ORDER BY $orderby $order
                            LIMIT $offset, $limit");
        return $resExp;
    }

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience accommondation with price
     */
    public static function get_exp_acm_data($expId = '', $acmId = '', $startdate = "NOW()", $enddate = "NOW()", $days = '') {
        if ($expId) {
            /*echo "SELECT 
                                a.*,
                                ea.id as experience_accomodations_id,
                                ea.currency,
                                ea.accomodation_default,
                                eap.start_date,
                                eap.end_date,
                                eap.price_per_night_per_guest,
                                (CASE
                                    WHEN
                                        (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate' AND eap.promotional_price IS NOT NULL) AND (eap.duration = '$days')
                                    THEN
                                        eap.promotional_price
                                    WHEN
                                        (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate' AND eap.price_per_night_per_guest IS NOT NULL) AND (eap.duration = '$days')
                                    THEN
                                        eap.price_per_night_per_guest    
                                    ELSE ea.price_per_night_per_guest
                                END) AS room_price
                            FROM
                                accomodation a
                                    LEFT JOIN
                                experience_accomodations ea ON a.id = ea.title
                                    LEFT JOIN
                                experience_accomodation_prices eap ON ea.title = eap.accomodation_id
                                    AND eap.experience_id = $expId
                                    AND eap.price_per_night_per_guest IS NOT NULL
                                    AND (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate')
                            WHERE
                                ea.experience_id = $expId " . ((!empty($acmId)) ? " and ea.title = $acmId " : "");exit;*/
            $resExpAcm = DB::select("SELECT 
                                a.*,
                                ea.id as experience_accomodations_id,
                                ea.currency,
                                ea.accomodation_default,
                                eap.start_date,
                                eap.end_date,
                                eap.price_per_night_per_guest,
                                (CASE
                                    WHEN
                                        (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate' AND eap.promotional_price IS NOT NULL  AND eap.duration = '$days')
                                    THEN
                                        eap.promotional_price
                                    WHEN
                                        (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate' AND eap.price_per_night_per_guest IS NOT NULL AND eap.duration = '$days')
                                    THEN
                                        eap.price_per_night_per_guest    
                                    ELSE '0'
                                END) AS room_price
                            FROM
                                accomodation a
                                    LEFT JOIN
                                experience_accomodations ea ON a.id = ea.title
                                    LEFT JOIN
                                experience_accomodation_prices eap ON ea.title = eap.accomodation_id
                                    AND eap.experience_id = $expId
                                    AND eap.price_per_night_per_guest IS NOT NULL
                                    AND (eap.start_date <= '$startdate' AND eap.end_date >= '$startdate') 
                                    AND (eap.duration = '$days')
                            WHERE
                                ea.experience_id = $expId " . ((!empty($acmId)) ? " and ea.title = $acmId " : ""));

            if ($resExpAcm) {
                return $resExpAcm;
            }
            return [];
        }
        return [];
    }

    /**
     * @param  array|null $param
     * @return mixed Fetch Experience Category
     */
    public static function get_exp_category($cnd = '', $orderby = 'category.id', $order = 'ASC', $limit = 10, $offset = 0) {
        /*$resExp = DB::select("SELECT 
                                    experience_category.category_id, category.id, category.name, count(DISTINCT e.id) as total
                                    from 
                                        experiences e
                                            LEFT JOIN
                                        experience_accomodations ea ON e.id = ea.experience_id
                                            AND ea.accomodation_default = 1
                                            LEFT JOIN
                                        experience_accomodation_prices eap ON ea.title = eap.accomodation_id
                                            AND eap.experience_id=ea.experience_id 
                                            AND eap.price_per_night_per_guest IS NOT NULL
                                            AND (MONTH(eap.start_date) <= MONTH(NOW())
                                            AND MONTH(eap.end_date) >= MONTH(NOW())
                                            AND EXTRACT(DAY FROM NOW()) >= EXTRACT(DAY FROM eap.start_date)
                                            AND EXTRACT(DAY FROM NOW()) <= EXTRACT(DAY FROM eap.end_date))
                                            LEFT JOIN
                                        experience_category ON experience_category.experience_id = e.id
                                            LEFT JOIN
                                        category ON experience_category.category_id = category.id 
                                            LEFT JOIN
                                        experience_recurring er ON er.experience_id = e.id
                                            LEFT JOIN
                                        experience_recurring_manually erm ON erm.experience_id = e.id
                                    WHERE
                                        e.`is_draft` = 0
                                            AND 1 = (CASE
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
                                        END) $cnd 
                                    GROUP BY experience_category.category_id 
                                    HAVING total > 0 
                                ORDER BY $orderby $order
                                LIMIT $offset, $limit");*/
                                
        $resExp = DB::select("SELECT 
                                    experience_category.category_id, category.id, category.name
                                    from experience_category
                                            LEFT JOIN
                                        category ON experience_category.category_id = category.id
                                    WHERE category.id != ''  $cnd group by experience_category.category_id
                                ORDER BY $orderby $order
                                LIMIT $offset, $limit");                        
        return $resExp;
    }
    
    
    
    public static function amenities($center_id) {
        $objCenter = \App\Centers::select("amenities")->where("id", $center_id)->first();
        $amenities = array();
        if ($objCenter) {
            $amenities = \App\Amenities::select("id","name","image_url")->whereIn("id",explode("||",@$objCenter->amenities))->orderBy("name","ASC")->get();
        }
        return $amenities;
    }
    
    public function image_galleries($expId = '')
    {
        if ($expId) {
            return ExperienceImageGallery::where("experience_id", $expId)->get();
        } else {
            return $this->hasMany(ExperienceImageGallery::class, 'experience_id', 'id');
        }
    }
    
    public static function get_state_country($expId = '') {
        $objLocations = \App\ExperienceCategory::select("category.name")
        ->Join("category", "experience_category.category_id", "category.id")
        ->where("category.type", 1)
        ->where('experience_id', $expId)
        ->groupBy("experience_category.category_id")
        ->orderBy("parent","desc")
        ->get()->pluck('name');
        if ($objLocations) {
            return implode(", ", $objLocations->toArray());
        } else {
            return null;
        }
    }

    /**
     * Get the center
     */
    public function center()
    {
        return $this->belongsTo('App\Centers','center_id')->select('id','name');
    }

    public function destinations()
    {
        return $this->belongsToMany(Category::class, 'experience_category', 'experience_id', 'category_id')
        ->select("category.name", "category.id")
                    ->where('category.type', 1)
                    ->orderBy('category.name', 'ASC')
                    ->distinct();
    }
}
