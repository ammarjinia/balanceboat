<div class="strip_all_tour_list wow fadeIn item" <?php if(@$experience->featured_experiences > 0) {?> style='border:1px solid red;padding:5px;' <?php }?> >
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            @if($experience->thumbnail_image_url || $experience->banner_image_url)
            <div class="img_list">
                <a href="{{ url("/experience/".$experience->slug) }}">
                    <img data-src="{{ ($experience->thumbnail_image_url) ? Storage::disk('azure')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('azure')->url(rawurlencode($experience->banner_image_url)) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy" /> 
                </a>
            </div>
            @endif
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 text-left">
            <div class="tour_list_desc" style="height:auto;margin-top:10px;">
                <a href="{{ url("/experience/".$experience->slug) }}"><h3 style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">{{ $experience->name }}</h3></a>
                <?php /*<h5 class="hidden-xs hidden-sm">
                    <i class="fa fa-calender"></i> 
                    @if(@$experience->recurring_type == "Daily")
                    Available all year round
                    @else
                    {{ @$experience->available_month }}
                    @endif
                </h5>*/?>
                <?php if (@$experience->experience_summary) { ?>
                <div style="height:142px;overflow:hidden;">
                    {!! @$experience->experience_summary !!}
                </div>    
                <?php } ?>
                    @if($experience->center_id) 
                        <?php $amenities = \App\Experiences::amenities($experience->center_id);?>
                        @if($amenities)
                            <div style="height: 18px;overflow: hidden;margin-top: 5px;margin-bottom: 5px;">
                            <?php
                                foreach (@$amenities as $amenity) {
                                    if ($amenity->image_url && $amenity->name != "Bath Tub") {?>
                                        <img src="{{ 'https://balanceboatblob.blob.core.windows.net/balancegurus/'.rawurlencode(@$amenity->image_url) }}" alt=" {{ $amenity->name }}" title=" {{ $amenity->name }}" class="" width="18px" />&nbsp;
                                    <?php
                                    }
                                }
                                ?>
                            </div>    
                        @endif
                    @endif
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="price_list" style="margin-top:10px; text-align:left;">
                    <?php 
                    $discount = 0;
                    $pay = @$experience->min_promo_price ? @$experience->min_promo_price : @$experience->min_duration_price;
                    if ($pay) {
                    /*<small class="hidden-xs hidden-sm" style="color:#484848;font-size:14px;margin-bottom:5px;">{!! @$experience->final_room_price ? "Avg. Daily Price  " : "" !!}</small>*/?>
                    <small class="" style="color:#484848;font-size:1.4rem;margin:0px;">Offers From</small>
                    <span class="">
                        <?php
                        
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
                        if (!empty(@$discount)) { ?>
                            <del class="fs-14 c-pink" style="font-size:1.6rem;"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), \App\Http\Helpers\CommonHelper::get_site_currency()) }} </del>
                        <?php } ?>
                        @if((@$pay - $discount) > 0)
                        <span class="" style="color:#333; font-size:2rem;">{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, \App\Http\Helpers\CommonHelper::get_site_currency()) }}</span>
                        @endif
                        <?php
                        //$pay = @$experience->default_room_price*1;
                        //$pay = @$experience->room_price*1;
                        /*$discount = 0;                        
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
                        }*/


                        /*if (!empty(@$experience->totaldiscount) || (($experience->final_room_price != $pay) && (!empty($pay)))) {
                            ?>
                            <del class="text-default">
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                            </del>
                            <?php
                        }
                        ?>
                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->final_room_price, \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                        <?php */?>
                        
                    </span>
                    <?php
                    /*if (!empty(@$experience->duration)) {
                        ?>
                        <small class="hidden-xs hidden-sm">For:</small>
                        <span class="hidden-xs hidden-sm text-days">
                            <?php
                            echo @$experience->duration;
                            ?>
                        </span>
                        <?php
                    }*/
                        }
                    ?>
                    <a href="#mdlInquiry" data-toggle="modal" target="_blank" class="btn btn_2 btn-block text-center cls-send-inquiry" data-exp-name="{{ $experience->name }}" data-exp-id="{{ $experience->id }}" style="padding:7px 10px;white-space:break-spaces;margin-top:10px;">Inquire</a>
                    <?php $bkfrmAction = url("/reservation"); ?>
                    @if(@$center->name == "AyurYoga Eco-Ashram Mysore India")
                    <?php
                    $bkfrmAction = url("/redirect-to-portal");
                    ?>
                    @endif
                    <a href="{{ url('/experience/'.$experience->slug) }}" id="btnListReserve{{ @$experience->id }}" class="btn btn_1 btn-block cls-reservation" data-exp-name="{{ $experience->name }}" data-exp-id="{{ $experience->id }}" style="padding:7px 10px;white-space:break-spaces;">Reserve</a>
            </div>
            @if (!empty(@$discount)) 
            <div class="badge_save">Save<strong>{{ round((@$discount/@$pay) * 100) }}%</strong></div>
            @endif
        </div>
    </div>
</div>
