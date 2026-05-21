<?php

namespace App\Http\Helpers;

use \Torann\GeoIP\Facades\GeoIP;
use Swap;
use DB;
use Illuminate\Database\Eloquent\Collection;

class CommonHelper {

    /**
     * @param null
     * 
     * @return Currency Array
     */
    public static function get_currency() {
        $currency = array();
        if (!session()->has("currency_available")) {
            $objCurrency = \App\Currency::select("name")->get()->toArray();
            foreach ($objCurrency as $cur) {
                array_push($currency, $cur['name']);
            }
            session(['currency_available' => $currency]);
        } else {
            $currency = session('currency_available');
        }
        return $currency;
    }

    /**
     * @param $currency
     * 
     * @return Currency Symbol
     */
    public static function get_currency_symbol($currency = "") {
        $currency_symbol = "";
        if (empty($currency)) {
            $currency = CommonHelper::get_site_currency();
        }
        
        if (!session()->has("currency_symbols_available")) {
            $objCurrency = \App\Currency::select("symbol","name")->get();
            $arCurrency = array();
            foreach ($objCurrency as $cur) {
                $arCurrency[$cur->name] = $cur->symbol;
            }
            session(['currency_symbols_available' => $arCurrency]);
        } else {
            $arCurrency = session('currency_symbols_available');
        }
        $currency_symbol = html_entity_decode($arCurrency[$currency]);
        return $currency_symbol;
    }

    /**
     * @param $currency
     * 
     * @return Currency Symbol
     */
    public static function get_currency_rate($amount = 0, $fromCurrency = "INR", $withsymbol = true) {
        $fromCurrency = (!empty($fromCurrency)) ? $fromCurrency : "INR";
        $toCurrency = CommonHelper::get_site_currency();
        if (!in_array($toCurrency, CommonHelper::get_currency())) {
            session(['site_currency' => "USD"]);
            $toCurrency = "USD";
        }
        $amount = (!empty($amount)) ? $amount : 0;
        if ($fromCurrency != $toCurrency) {
            //$objRate = Swap::latest($fromCurrency . "/" . $toCurrency);
            //$rate = $objRate->getValue();
            $fromCurrencyRate = 0;
            $toCurrencyRate = 0;
            $objCurrency = \App\Currency::select("name", "rate")->whereIn("name", [$fromCurrency, $toCurrency])->get();
            foreach ($objCurrency as $objCur) {
                if ($objCur->name == $fromCurrency) {
                    $fromCurrencyRate = $objCur->rate;
                }
                if ($objCur->name == $toCurrency) {
                    $toCurrencyRate = $objCur->rate;
                }
            }
            $cur_rate = ($toCurrencyRate * $amount) / $fromCurrencyRate;
        } else {
            $rate = 1;
            $cur_rate = ($rate * $amount);
        }
        if ($withsymbol) {
            return CommonHelper::get_currency_symbol($toCurrency) . number_format($cur_rate, 0, '.', ',');
        } else {
            return number_format($cur_rate, 0, '.', '');
        }
    }

    /**
     * 
     * @return Currency Symbol
     */
    public static function get_site_currency() {
        $siteCur = (!session()->has('site_currency')) ? "USD" : session('site_currency');
        if (@$_GET['global_site_currency']) {
            $siteCur = $_GET['global_site_currency'];
        } else if (!session()->has('site_currency')) {
            $arCur = CommonHelper::getLocationInfoByIp();
            if (isset($arCur['name']) && !empty($arCur['name'])) {
                $objCur = \App\Currency::select("rate")->where("name", $arCur['name'])->first();  
                if ($objCur) {
                    $siteCur = $arCur['name'];
                }
            }
        }
        session(['site_currency' => $siteCur]);
        return $siteCur;
    }

    /**
     * 
     * @return Currency rate
     */
    public static function get_site_currency_rate() {
        $siteCur = CommonHelper::get_site_currency();
        $objCurrency = \App\Currency::select("rate")->where("name", $siteCur)->first();
        session('site_currency_rate', $objCurrency->rate);
        return $objCurrency->rate;
    }

    /**
     * 
     * @return get Destinations
     */
    public static function get_site_destinations() {

        $newDest = \App\Category::select("id","name")->whereIn("name", ["Mysore","Peru", "Costa Rica", "Spain", "Portugal","Mexico","Chaing Mai", "Thailand", "Phuket", "Pattaya", "Sri Lanka", "Koh Samui", "Koh Phangan", "Bangkok"])->get();
        $destinations = \App\Experiences::get_exp_category(" and category.type=1 ",'category.id', 'ASC', '10');
        /* \App\ExperienceCategory::select('category.id', 'category.name', DB::raw('count(experience_category.experience_id) as total'))
          ->join("category", function($join) {
          $join->on("category_id", "=", "category.id");
          $join->where('type', 1);
          })
          ->join("experiences", function($join) {
          $join->on("experiences.id", "=", "experience_category.experience_id");
          //$join->where("experiences.start_date_time", ">", \Carbon\Carbon::now());
          $join->where('is_draft', 0);
          })->orderBy('name', 'asc')->groupBy('name')->get(); */
        
        foreach ($newDest as $dest) {
            $destinations[] = (object)$dest;
        }
        // Add the new data to the existing array
        return $destinations;
    }

    /**
     * 
     * @return get Popular Categories
     */
    public static function get_popular_categories() {
        $categories = array(
            array("id"=>"340", "name" => "Yoga Retreat"),
            array("id"=>"341", "name" => "Ayurveda Retreat"),
            array("id"=>"345", "name" => "Meditation Retreat"),
            array("id"=>"94", "name" => "Yoga Teacher Training")
        );
        return json_decode(json_encode((object) $categories), FALSE);
    }

    /**
     * 
     * @return get Categories
     */
    public static function get_site_categories() {
        $categories = array(
            array("id"=>"334", "name" => "50 Hrs Yoga Teacher Training"),
            array("id"=>"336", "name" => "100 Hrs Yoga Teacher Training"),
            array("id"=>"337", "name" => "200 Hrs Yoga Teacher Training"),
            array("id"=>"338", "name" => "300 Hrs Yoga Teacher Training"),
            array("id"=>"339", "name" => "500 Hrs Yoga Teacher Training"),
            array("id"=>"4", "name" => "Panchakarma"),
            array("id"=>"309", "name" => "Stress Reduction"),
            array("id"=>"295", "name" => "Weight Loss")
        );
        /*$categories = \App\Experiences::get_exp_category(" and category.type=0 and category.parent=0");
         \App\ExperienceCategory::select('category.id', 'category.name', DB::raw('count(experience_category.experience_id) as total'))
          ->join("category", function($join) {
          $join->on("category_id", "=", "category.id");
          $join->where('type', 0);
          })
          ->join("experiences", function($join) {
          $join->on("experiences.id", "=", "experience_category.experience_id");
          $join->where('is_draft', 0);
          })->orderBy('name', 'asc')->groupBy('name')->get(); */
        return json_decode(json_encode((object) $categories), FALSE);
    }

    public static function excerpt($text, $max_length = 140, $cut_off = '...', $keep_word = false) {
        if (strlen($text) <= $max_length) {
            return $text;
        }

        if (strlen($text) > $max_length) {
            if ($keep_word) {
                $text = substr($text, 0, $max_length + 1);

                if ($last_space = strrpos($text, ' ')) {
                    $text = substr($text, 0, $last_space);
                    $text = rtrim($text);
                    $text .= $cut_off;
                }
            } else {
                $text = substr($text, 0, $max_length);
                $text = rtrim($text);
                $text .= $cut_off;
            }
        }

        return $text;
    }

    /**
     * @param $currency
     * 
     * @return currency convertion rate
     */
    public static function get_currency_convertion_rate($amount = 0, $fromCurrency = "USD", $toCurrency = "INR") {
        $fromCurrency = (!empty($fromCurrency)) ? $fromCurrency : "USD";
        $toCurrency = (!empty($toCurrency)) ? $toCurrency : "INR";
        if (!in_array($toCurrency, CommonHelper::get_currency())) {
            session(['site_currency' => "USD"]);
            $toCurrency = "USD";
        }
        $amount = (!empty($amount)) ? $amount : 0;
        if ($fromCurrency != $toCurrency) {
            $objRate = Swap::latest($fromCurrency . "/" . $toCurrency);
            $rate = $objRate->getValue();
        } else {
            $rate = 1;
        }
        $cur_rate = ($rate * $amount);
        return number_format($cur_rate, 2, '.', '');
    }

    /**
     * @param $currency
     * 
     * @return currency convertion rate
     */
    public static function getLocationInfoByIp() {
        $result = array();
        
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = '';
        
        if (!empty($ip)) {    
            try {
                $ip_data = geoip()->getLocation($ip);
                if ($ip_data && $ip_data->country != null) {
                    $result['country'] = $ip_data->iso_code;
                    $result['city'] = $ip_data->city;
                    $result['symbol'] = $ip_data->currency;
                    $result['name'] = $ip_data->currency;
                }
            } catch (\Exception $e) {
                
            }   
        }
        return $result;
    }

    public static function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

}

?>