<div class="hotel_container">
   <div class="tour_container">
    <div class="img_container">
        <a href="{{ url("/experience/".$experience->slug) }}">
            <img data-src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy img-fluid"> 
            <img data-src="{{ ($experience->thumbnail_image_url) ? Storage::disk('azure')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('azure')->url(rawurlencode($experience->banner_image_url)) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy"> 
        </a>
    </div>
    </div>
    <div class="short_info hotel">
            
             <div class="tour_title hotel_title">
       <h3><a href="{{ url("/experience/".$experience->slug) }}" style="display: block;overflow: hidden;font-size: 1.4rem;text-transform: uppercase;font-weight: 500;word-break: break-all;line-height: 1.8rem;">
            {{ \App\Http\Helpers\CommonHelper::excerpt($experience->name,65) }}
        </a></h3>
        
    </div>
   <div class="hotel carslprc "> <span class="price">
   <div class="">
            <?php
                        $pay = @$experience->room_price * 1;
                        /*
                          $discount = 0;
                          if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                          if (@$experience->eirly_bird_discount_type == "amt") {
                          $discount += @$experience->eirly_bird_discount;
                          } else {
                          $discount = (@$experience->room_price * @$experience->eirly_bird_discount) / 100;
                          }
                          }

                          if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                          $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                          if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $now)) {
                          if (@$experience->offer_discount_type == "amt") {
                          $discount += @$experience->offer_discount;
                          } else {
                          $discount += (@$experience->room_price * @$experience->offer_discount) / 100;
                          }
                          }
                          } */

                        if (!empty(@$experience->totaldiscount)) {
                            ?>
                            <!--del class="text-default">
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                            </del-->
                            <?php
                        }
                        ?>
                        <strong> {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->final_room_price, \App\Http\Helpers\CommonHelper::get_site_currency()) }}</strong>
        </div>
</span>
</div>
    </div>
   
   
</div>