@extends('layouts.front')
@section('title', @$experience->name) 

<?php
$category = $subcategory ="";
foreach ($experience_categories as $ecat) {
    if ($ecat->parent == 0) {
        $category = $ecat->name;
    }
    if ($ecat->parent != 0) {
        $subcategory .= $ecat->name.", ";
    }
}
?>

<?php
$country = $city ="";
foreach ($experience_destination as $edest) {
    if ($edest->parent == 0) {
        $country = $edest->name;
    }
    if ($edest->parent != 0) {
        $city .= $edest->name.", ";
    }
}
?>


<!-- Meta Info Start--> 
<?php if (!empty(@$experience->meta_title)) { ?>
@section('meta_title', @$experience->meta_title)
<?php } else {?> 
@section('meta_title', @$experience->name." in ".$city.", ".$country)
<?php }?>
<?php if (!empty(@$experience->meta_description)) { ?>
    @section('description', strip_tags(@$experience->meta_description))
<?php } else {?>
    @section('description', \App\Http\Helpers\CommonHelper::excerpt(strip_tags(html_entity_decode(@$experience->experience_overview)),160))
<?php }?>
<?php if (!empty(@$experience->keywords)) { ?>
    @section('keywords', strip_tags(@$experience->keywords))
<?php } else {?>
    @section('keywords', "learn ".$subcategory." in ".$country.", learn and find ".$subcategory.", learn and find ".$category.", ".$subcategory." in ".$city.", ".$category." in ".$country." ".$city.", course in ".$country." ".$city.", ".$category." retreat in ".$country." ".$city)
<?php }?>
<?php if (!empty(@$experience->banner_image_url)) { ?>
    @section('image', Storage::disk('azure')->url(rawurlencode(@$experience->banner_image_url)))
<?php } ?>
<!-- Meta Info End --> 
@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">z
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/slider-pro.min.css') }}" rel="stylesheet" />
<style type="text/css">
    #single_tour_desc{
        font-size: 14px;
    }
    .contact-overlay{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .contact-overlay.shown {
        opacity: .5;
    }

    .cc-contactpop { 
        background-color: #fff;
        max-width:260px;
        width:100%;
        bottom: 0;
        left: 30px;
        z-index:999;
        border:1px solid #f36;
    }

    .cc-contactpop h3 {
        text-align: left;
        color: #fff;
        text-shadow: none;
        font-size: 18px;
        font-weight: 600;
        background-color: #f36;
        margin: 0;
        padding:15px;
        cursor: pointer;
    }
    .cc-contactpop h3 span {
        font-style: oblique;
    }
    .cc-contactpop p {
        text-align:center;
        padding:10px 15px;
    }
    .cc-contactpop i.fa.fa-times {
        position: absolute;
        top: -12px;
        right: -13px;
        color: #333333;
        font-weight: 500;
        font-size: 24px;
        padding: 6px 8px;
        cursor: pointer;
        border-radius: 100%;
        background: #ffffff;
    }
    .cc-contactpop label.error { margin-bottom:5px;margin-top: -5px;}
    .cc-contactpop.fixed {
        position: fixed;
    }
    .custom-form{
        margin-top: 15px;
    }
    .cc-contactpop label.error {display: none !important;}
    header.sticky{
        z-index: 999999;
    }
    @media only screen and (max-width:1064px){
        .cc-contactpop {
            left:0px;
        }
    }

    @-webkit-keyframes swinging{
        0%{-webkit-transform: rotate(10deg);}
        50%{-webkit-transform: rotate(-5deg)}
        100%{-webkit-transform: rotate(10deg);}
    }

    @keyframes swinging{
        0%{transform: rotate(10deg);}
        50%{transform: rotate(-5deg)}
        100%{transform: rotate(10deg);}
    }


    .bounce3 {
        animation: bounce 4s infinite;
        -webkit-animation: bounce 4s infinite;
        -moz-animation: bounce 4s infinite;
        -o-animation: bounce 4s infinite;
    }


    @-webkit-keyframes bounce {
        0%, 20%, 50%, 80%, 100% {-webkit-transform: translateY(0);} 
        40% {-webkit-transform: translateY(-30px);}
        60% {-webkit-transform: translateY(-15px);}
    }

    @-moz-keyframes bounce {
        0%, 20%, 50%, 80%, 100% {-moz-transform: translateY(0);}
        40% {-moz-transform: translateY(-30px);}
        60% {-moz-transform: translateY(-15px);}
    }

    @-o-keyframes bounce {
        0%, 20%, 50%, 80%, 100% {-o-transform: translateY(0);}
        40% {-o-transform: translateY(-30px);}
        60% {-o-transform: translateY(-15px);}
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-30px);}
        60% {transform: translateY(-15px);}
    }

</style>
@endsection    
@section('banner')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ Storage::disk('azure')->url(rawurlencode(@$experience->banner_image_url)) }}" data-natural-width="1280" data-natural-height="780">
    <div class="parallax-content-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <h1>{{ @$experience->name }}</h1>
                    <p class="text-italic"><i class="icon-location-2"></i>{{ @$center->address_of_center }}</p>
                    <span>{{ @$experience->location }}</span> </div>
                <div class="col-md-4 col-sm-4 text-right"> @if(sizeof(@$imagegalleries)>0) <a href="javascript:void(0);" class="btn_1 view_image">View Images</a>
                    <div class="carousel magnific-gallery"> @foreach(@$imagegalleries as $gallery)
                        <div class="item hidden"> <a href="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}"> <img src="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" /> </a> </div>
                        @endforeach </div>
                    @endif </div>
            </div>
        </div>
    </div>
</section>
<!-- End section --> 
@endsection
@section('content')
<div id="position">
    <div class="container">
        <ul>
            <li><a href="{{ url("/") }}">Home</a></li>
            <li><a href="{{ url("/experiences") }}">Experiences</a></li>
            <li>{{ @$experience->name }}</li>
        </ul>
    </div>
</div>
<!-- End Position -->

<div class="container margin_30">
    <form id="frmBooking" name="frmBooking" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking">
    <input type="hidden" id="hdn_experience_id" name="hdn_experience_id" value="{{ @$experience->id }}" />
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-1">
            <label class="form-label">Days</label><br/>
            <select id="durations" name="durations" class="form-control">
                <option value=""></option>
            </select>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Accommodation</label>
                <select id="exp_accomodation_id" name="exp_accomodation_id" class="form-control">                   
                    <option value="">Please Select Accomodation</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <input type="hidden" name="booking_date" id="booking_date" value="">
            <label class="form-label">Start Date</label>
                <div class="filter_daily_exp">
                    <input type="text" class="form-control bkdate" id="booking_date" name="booking_date" value="" data-date-format="mm/dd/yyyy" readonly/>
                </div>
                <div class="filter_dates_radio_group" style="display:none;">
                    <select name="filter_dates_dd" id="filter_dates_dd">
                        <option value="">Please Select a Value</option>
                    </select>
                </div>
                <div class="no_booking_date" style="display:none;"><p>Not Available</p></div>
        </div>
        <div class="col-md-2">
            <label class="form-label">Price</label><br/>
            <strong class='date_price_acm'></strong>
        </div>
        <div class="col-md-2">
            <label class="form-label">Booking Amount</label><br/>
            <strong class='booking_amount text-success'></strong>
        </div>        
        <div class="col-md-2">
                @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
                    <button class="btn_1 btn-block medium text-center btn-reservation" id="quickReserverBtn" disabled="disabled">Reserve Now</button>
                @endif
                <a href="#mdlInquiry" data-toggle="modal" target="_blank" class="btn_2 btn-block medium text-center btn-send-inquiry" style="margin-top:10px;">Send Inquiry</a>    
        </div>
    </div>
    </form>
    <hr />
    @if(@$accomodationimagegalleries)
        <div class="carousel magnific-gallery">
            @if(@$accomodationimagegalleries)
                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                    <div class="item"> <a href="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}">
                        <img src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" alt="Image"></a>
                    </div>
                @endforeach
            @endif 
        </div>
    @endif
    <br />
    <div class="row">
        <div class="col-md-8" id="single_tour_desc"> 
            <!--div class="row">
                      <div class="col-md-12">
                          <h2 class="nomargin_top">{{ @$experience->name }}</h2>
            <?php
            if (@$center->address_of_center) {
                ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <p class="text-italic"><i class="icon-location-2"></i>{{ @$center->address_of_center }}</p>
                <?php
            }
            ?>
                      </div>
                  </div--> 
           <?php
           /*
           @if(sizeof(@$experience_accomodations) > 0)
            <?php $bkfrmAction = url("/reservation"); ?>
            @if(@$center->name == "AyurYoga Eco-Ashram Mysore India")
            <?php
            $bkfrmAction = url("/redirect-to-portal");
            ?>
            @endif
            <form id="frmBooking" name="frmBooking" action="{{ $bkfrmAction }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <h3>Best Offer</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-inline text-right">
                                    <label class="form-label">Accommodation</label>
                                    <select id="exp_accomodation_id" name="exp_accomodation_id" class="form-control">

                                        @foreach(@$experience_accomodations as $experience_accomodation)

                                        <option value="{{ @$experience_accomodation->id }}" {{ (@$experience_accomodation->accomodation_default == 1) ? "checked='checked'" : "" }}>
                                            {{ @$experience_accomodation->name }} </option>

                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr style="margin:0;" />
                        <div class="table-responsive-sm mob_tbl">
                            <table class="table table-striped table-bordered cart-list add_bottom_30">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Price Per Person</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (@$experience_recurring->recurring_type == 'Daily') { ?>
                                        <tr>
                                            <td><input type="text" class="form-control required bkdate" id="booking_date" name="booking_date" value="{{ \Carbon\Carbon::now()->addDays(2)->format("m/d/Y")}}" data-date-format="mm/dd/yyyy" /></td>
                                            <td>
                                                <?php
                                                $experience_accomodations = \App\Experiences::get_exp_acm_data(@$experience->id, '', @$exp_recurr->start_date, @$exp_recurr->end_date);
                                                if (sizeof(@$experience_accomodations) > 0) {
                                                    foreach (@$experience_accomodations as $experience_accomodation) {
                                                        $discount = 0;
                                                        $experience_accomodation->htmlPrice = "";
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

                                                        if (!empty(@$discount)) {
                                                            $experience_accomodation->htmlPrice = '<del class="text-default">' .
                                                                    \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency)
                                                                    . '</del>';
                                                        }
                                                        $experience_accomodation->htmlPrice .= \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);
                                                        echo "<strong class='date_price_acm date_price_acm_" . $experience_accomodation->id . "' style='display:none;'>" . $experience_accomodation->htmlPrice . "</strong>";
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        @if (\Carbon\Carbon::parse(@$experience->start_date_time)->subDays(2) >=  \Carbon\Carbon::now())
                                        <tr>
                                            <td><div class="radio-option">
                                                    <label for="booking_date" class="rdbtn">
                                                        <input type="radio" class="required" id="booking_date" name="booking_date" value="{{ @$experience->start_date_time." - ". @$experience->end_date_time }}" /> 
                                                        <span class="checkmark"></span>
                                                        {{ (@$experience->start_date_time) ? \Carbon\Carbon::parse(@$experience->start_date_time)->format("d M, Y") : "" }}
                                                        {{ (@$experience->end_date_time) ? " - ".\Carbon\Carbon::parse(@$experience->end_date_time)->format("d M, Y") : "" }} </label>
                                                </div></td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @if (sizeof(@$experience_recurring_manually) > 0)
                                        @foreach($experience_recurring_manually as $exp_recurr)
                                        <tr>
                                            <td><div class="radio-option">
                                                    <label for="booking_date_{{ @$exp_recurr->id }}" class="rdbtn">
                                                        <input type="radio" class="required" id="booking_date_{{ @$exp_recurr->id }}" name="booking_date" value="{{ @$exp_recurr->start_date." - ". @$exp_recurr->end_date}}" />
                                                        <span class="checkmark"></span>
                                                        {{ (@$exp_recurr->start_date) ? \Carbon\Carbon::parse(@$exp_recurr->start_date)->format("d M, Y") : "" }}
                                                        {{ (@$exp_recurr->end_date) ? " - ".\Carbon\Carbon::parse(@$exp_recurr->end_date)->format("d M, Y") : "" }} </label>
                                                </div></td>
                                            <td><?php
                                                $experience_accomodations = \App\Experiences::get_exp_acm_data(@$experience->id, '', @$exp_recurr->start_date, @$exp_recurr->end_date);
                                                if (sizeof(@$experience_accomodations) > 0) {
                                                    foreach (@$experience_accomodations as $experience_accomodation) {
                                                        $discount = 0;
                                                        $experience_accomodation->htmlPrice = "";
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

                                                        if (!empty(@$discount)) {
                                                            $experience_accomodation->htmlPrice = '<del class="text-default">' .
                                                                    \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency)
                                                                    . '</del>';
                                                        }
                                                        $experience_accomodation->htmlPrice .= \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency);
                                                        echo "<strong class='date_price_acm date_price_acm_" . $experience_accomodation->id . "' style='display:none;'>" . $experience_accomodation->htmlPrice . "</strong>";
                                                    }
                                                }
                                                ?></td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
                <div class="col-md-6">
                    <div class="form-group"> <a href="#mdlInquiry" data-toggle="modal" target="_blank" class="btn_2 btn-block medium text-center btn-send-inquiry">Send Inquiry</a> </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"> <a href="javascript:void(0);" id="btnReserve{{ @$experience->id }}" class="btn_1 btn-block medium text-center btn-reservation">Request a reservation</a> </div>
                </div>
                <div class="clear"></div>
                @endif
                <div role="alert" class="form-group alert alert-info"> <strong>Want to book for 2 or more people?</strong> <br>
                    Contact <a href="mailto:zen@balanceboat.com" class="text-pink">zen@balanceboat.com</a> to get the best deal. </div>
            </form>
            @endif*/?>
            
                <div role="alert" class="form-group alert alert-info"> <strong>Want to book for 2 or more people?</strong> <br>
                    Contact <a href="mailto:zen@balanceboat.com" class="text-pink">zen@balanceboat.com</a> to get the best deal. </div>
            <?php
            if (@$experience->experience_overview) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Experience Overview</h3>
                        {!! @$experience->experience_overview !!} </div>
                </div>
                <hr>
                <?php
            }
            if (@$experience->experience_highlights) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Experience Highlights</h3>
                        <ul class="list_ok cls_new_list">
                            <?php
                            foreach (explode(",", @$experience->experience_highlights) as $experience_highlight) {
                                ?>
                                <li>{{ $experience_highlight }}</li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->batch_size) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Group Size</h3>
                        <p>The maximum size of the group is {{ (@$experience->batch_size) ?: "NA" }}</p>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->styles_taught) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Style</h3>
                        <div class="tags">
                            <?php
                            foreach (explode(",", @$experience->styles_taught) as $styles_taught) {
                                ?>
                                <span class="medium font18">{{ $styles_taught }}</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->language_spoken) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Language</h3>
                        <div class="tags">
                            <?php
                            foreach (explode("||", @$experience->language_spoken) as $language) {
                                ?>
                                <span class="medium font18">{{ @$language }}</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->schedule) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Schedule</h3>
                        <div style="display:block;overflow:auto;max-width:100%;">
                            {!! @$experience->schedule !!} 
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            @if(@$amenities)
                <div class="row">
                    <div class="col-md-12">
                        <h3>Amenities</h3>
                        <div>
                            <?php
                            foreach (@$amenities as $amenity) {
                                if ($amenity->image_url) {?>
                                    <img src="{{ "https://balanceboatblob.blob.core.windows.net/balancegurus/".rawurlencode(@$amenity->image_url) }}" alt=" {{ $amenity->name }}" title=" {{ $amenity->name }}" class="" width="18px" />&nbsp;
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <hr /> 
            @endif
            <?php if (@$center) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>About the center</h3>
                        <h4>{{ @$center->name }}</h4>
                        <?php if (@$center->year_of_foundation) { ?>
                            <p>Founded in {{ (@$center->year_of_foundation) ?: "N/A" }}</p>
                        <?php } ?>
                        {!! @$center->about_center !!}
                        <p><a href="{{ url("center/".@$center->slug) }}" class="text-pink">Visit Center’s Profile</a></p>
                    </div>
                </div>
                <hr>
                <?php if (@$center->accomodation_overview) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Accommodation Overview</h3>
                            @if ((@$center->accomodation_banner_image_url) or (sizeof(@$accomodationimagegalleries)>0))
                            <div id="Img_carousel" class="slider-pro">
                                <div class="sp-slides"> @if(@$center->accomodation_banner_image_url)
                                    <div class="sp-slide"> <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" /> </div>
                                    @endif
                                    @if(@$accomodationimagegalleries)
                                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                    <div class="sp-slide"> <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" /> </div>
                                    @endforeach
                                    @endif </div>
                                <div class="sp-thumbnails"> @if(@$center->accomodation_banner_image_url) <img alt="Image" class="sp-thumbnail" alt="" src="{{ Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" /> @endif
                                    @if(@$accomodationimagegalleries)
                                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery) <img alt="Image" class="sp-thumbnail" alt=""  data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" /> @endforeach
                                    @endif </div>
                            </div>
                            <br />
                            @endif
                            {!! @$center->accomodation_overview !!} </div>
                    </div>
                    <hr>
                <?php } ?>
            <?php } ?>
            <?php if (@$center->how_to_get_there) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>How to get there</h3>
                        {!! @$center->how_to_get_there !!} </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->food_overview) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Food Overview</h3>
                        <div class="fod-lst-stl">
                            @if ((!empty(@$experience->food_banner_image_url)) or (sizeof(@$foodimagegalleries) > 0) )
                            <div id="Img_carousel" class="slider-pro">
                                <div class="sp-slides"> @if(@$experience->food_banner_image_url)
                                    <div class="sp-slide"> <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$experience->food_banner_image_url)) }}" /> </div>
                                    @endif
                                    @if(@$foodimagegalleries)
                                    @foreach(@$foodimagegalleries as $foodimagegallery)
                                    <div class="sp-slide"> <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('azure')->url(@$foodimagegallery->image_url) }}" /> </div>
                                    @endforeach
                                    @endif </div>
                                <div class="sp-thumbnails"> @if(@$experience->food_banner_image_url) <img alt="Image" class="sp-thumbnail" alt="" src="{{ Storage::disk('azure')->url(rawurlencode(@$experience->food_banner_image_url)) }}" /> @endif
                                    @if(@$foodimagegalleries)
                                    @foreach(@$foodimagegalleries as $foodimagegallery) <img alt="Image" class="sp-thumbnail" alt="" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$foodimagegallery->image_url)) }}" /> @endforeach
                                    @endif </div>
                            </div>
                            <br />
                            @endif
                            {!! @$experience->food_overview !!} </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->what_is_included) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>What is Included</h3>
                        <div class="fod-lst-stl"> {!! @$experience->what_is_included !!} </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->what_is_not_included) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>What is not Included </h3>
                        <div class="fod-lst-stl">
                            {!! @$experience->what_is_not_included !!} </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$experience->cancellation_policy) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Cancellation Policy</h3>
                        {!! @$experience->cancellation_policy !!} </div>
                </div>
                <hr>
            <?php } ?>
            <?php if (@$center->things_to_do_around_the_center) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Things to do around</h3>
                        {!! @$center->things_to_do_around_the_center !!}
                    </div>
                </div>
                <hr>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-left add_bottom_10"> <a href="#" class="button_intro"> BACK TO TOP</a> </p>
                </div>
            </div>
        </div>
        <!--End  single_tour_desc-->

        <aside class="col-md-4">
            <?php if (@$experience->video_url) { ?>
                
                    <?php
                        $vidUrl = @$experience->video_url;
                        if (strstr(@$experience->video_url,"https://youtu.be/")) {
                            $vidUrl = "https://www.youtube.com/embed/".str_replace("https://youtu.be/","",@$experience->video_url);
                        } else {
                            $vidUrl = str_replace("watch?v=","embed/",@$experience->video_url);
                        }?>
                        <div class="box_style_1 expose">
                            <div class="row">
                                <div class="col-md-12 lststl_1">
                                    <iframe style="height:220px;position:initial;" width="100%" height="220" src="{{ @$vidUrl }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: relative;"></iframe>
                                </div>
                            </div>
                        </div>
            <?php } ?>
            <?php if (@$experience->experience_summary) { ?>
                <div class="box_style_1">
                    <h3 class="inner cls_new_h3">Experience Summary</h3>
                    <div class="row">
                        <div class="col-md-12 lststl_1"> {!! nl2br(@$experience->experience_summary) !!} </div>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group"> <a href="#mdlInquiry" data-toggle="modal" target="_blank" class="btn_full_outline medium text-center btn-send-inquiry">Send Inquiry</a> </div>
            @if(sizeof(@$experience_accomodations) > 0)
            <div class="box_style_1">
                <h3 class="inner">Accommodation</h3>
                <div class="row">
                    <div class="col-md-12">
                        <!--div class="form-group row">
                            <div class="text-right form-inline"> 
                                <small>Price</small> 
                                <span class="price_list price_list-min"></span> 
                                @if(@$experience->duration) 
                                    <small>For</small> <span class="text-days">
                                    <?php
                                    //echo @$experience->duration;
                                    ?>
                                </span>
                                @endif
                                @if(@$experience_durations or @$experience->duration)
                                <select id="experience_durations" name="experience_durations" class="form-control">
                                    <option value="{{ (int)@$experience->duration }}">{{ (int)@$experience->duration }} Days</option>
                                    @if(@$experience_durations)
                                        @foreach($experience_durations as $experience_duration)
                                            <option value="{{ $experience_duration->duration }}">{{ $experience_duration->duration }} Days (+{{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$experience_duration->price), @$experience_duration->currency) }})</option>
                                        @endforeach
                                    @endif    
                                </select>
                                @endif
                            </div>
                        </div-->
                        @foreach(@$experience_accomodations as $experience_accomodation)
                        <div class="form-group"> 
                            <!--label--> 
                            <!--input type="radio" id="exp_accomodation_id_{{ @$experience_accomodation->id }}" name="exp_accomodation_id" value="{{ @$experience_accomodation->id }}" {{ (@$experience_accomodation->accomodation_default == 1) ? "checked='checked'" : "" }}--> 
                            <a href="#mdlAccommodation{{ @$experience_accomodation->id }}"  data-toggle="modal"  class="text-info">{{ @$experience_accomodation->name }}</a> 
                            <!--/label--> 
                            <strong class="pull-right" id="accomodation_price_{{ @$experience_accomodation->id }}">
                                <?php
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


                                if (!empty(@$discount)) {
                                    ?>
                                    <del class="text-default"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }} </del>
                                    <?php
                                }
                                ?>
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }} </strong> @if(@$experience_accomodation->banner_image_url)
                            <div class="text-center"> <a href="#mdlAccommodation{{ @$experience_accomodation->id }}"  data-toggle="modal"  class="text-info"> <img src="{{ Storage::disk('azure')->url(rawurlencode(@$experience_accomodation->banner_image_url)) }}" alt="" class="img-responsive img-thumbnail" /> </a> </div>
                            @endif </div>
                        @endforeach 
                    </div>
                </div>
            </div>
            @endif 
            <div class="box_style_1 text-center"> <img src="{{ asset('public/basicfront/img/cards.png') }}" alt="Cards" class="cards" /> </div>
            <?php if (@$center->speciality) { ?>
                <div class="box_style_1 expose">
                    <h3 class="inner">Center Speciality</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list_ok">
                                <?php
                                foreach (explode(",", @$center->speciality) as $speciality) {
                                    ?>
                                    <li>{{ @$speciality }}</li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="box_style_1 expose">
                <h3 class="inner">Centre Overview</h3>
                <div class="row">
                    <div class="col-md-12"> <img src="{{ Storage::disk('azure')->url(rawurlencode(@$center->banner_image_url)) }}" alt="" class="img-responsive" />
                        <h3><a class="text-default" href="{{ url("center/".@$center->slug) }}">{{ @$center->name }}</a></h3>
                        <?php
                        if (@$center->address_of_center) {
                            ?>
                            <p> <i class="icon-location-2"></i>{{ @$center->address_of_center }} </p>
                            <hr />
                            <?php
                        }
                        ?>
                        <?php if (@$center->certificate_id) { ?>
                            <h4>Certiﬁcations</h4>
                            <?php if (@$certificates) { ?>
                                <div class="row">
                                    <?php
                                    foreach (@$certificates as $certificate) {
                                        if (in_array($certificate->id, explode("||", $center->certificate_id))) {
                                            ?>
                                            <div class="col-md-4"> <img src="{{ Storage::disk('azure')->url(rawurlencode(@$certificate->image_url)) }}" alt="{{ $certificate->name }}" title="{{ $certificate->name }}" class="img-responsive" /> </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php if (sizeof(@$experience_teachers) > 0) { ?>
                        <div class="col-md-12">
                            <hr>
                            <h4>Teachers</h4>
                            <?php
                            if (@$experience_teachers) {
                                foreach (@$experience_teachers as $teacher) {
                                    ?>
                                    <div class="review_strip_single">
                                        <?php
                                        $src = url("/public/basicfront/img/teacher_thumb.jpg");
                                        if (@$teacher->profile_image_url) {
                                            $src = Storage::disk('azure')->url(rawurlencode($teacher->profile_image_url));
                                        }
                                        ?>
                                        <img src="{{ $src }}" alt="" title="{{ $teacher->name }}" class="img-responsive img-circle"  style="width: 80px; height: 80px;" />
                                        <h4> {{ @$teacher->name }} <br>
                                            <?php
                                            if (@$teacher->certificate_id) {
                                                if (sizeof(@$certificates) > 0) {
                                                    ?>
                                                    <i class="icon-certificate-2"></i> <small>
                                                        <?php
                                                        foreach (@$certificates as $certificate) {
                                                            if (in_array($certificate->id, explode("||", $teacher->certificate_id))) {
                                                                ?>
                                                                {{ $certificate->name.", " }}
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </small>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <br />
                                            <?php }
                                            ?>
                                        </h4>
                                        {{ @$teacher->short_description }}
                                        <p class="text-right"><a href="{{ url("teacher/".@$teacher->slug) }}" class="text-pink">View Complete Profile</a></p>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </aside>
    </div>
    <!--End row --> 
</div>
<!--End container --> 
<!-- End main --> 

<!-- Modal Review --> 
@if(sizeof(@$experience_accomodations) > 0)
@foreach(@$experience_accomodations as $experience_accomodation)
<div class="modal fade" id="mdlAccommodation{{$experience_accomodation->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> {{ @$experience_accomodation->name }} <span class="price_list price_list-min">
                        <?php
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

                        if (!empty(@$discount)) {
                            ?>
                            <del class="text-default"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }} </del>
                            <?php
                        }
                        ?>
                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }} </span> </h4>
            </div>
            <div class="modal-body">
                {!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$experience_accomodation->description))) !!}
                @if(@$accomodationimagegalleries)
                <div class="carousel magnific-gallery"> @if(@$accomodationimagegalleries)
                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                    @if ($accomodationimagegallery->accomodation_id == $experience_accomodation->id)
                    <div class="item"> <a href="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}"><img src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" alt="Image"></a> </div>
                    @endif
                    @endforeach
                    @endif </div>
                @endif </div>
        </div>
    </div>
</div>
@endforeach
@endif 
<!-- End modal review --> 

<!-- Modal Review -->
<div class="modal fade" id="myReview" tabindex="-1" role="dialog" aria-labelledby="myReviewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="myReviewLabel">Write your review</h4>
            </div>
            <div class="modal-body">
                <div id="message-review"> </div>
                <form method="post" action="assets/review_tour.php" name="review_tour" id="review_tour">
                    <input name="tour_name" id="tour_name" type="hidden" value="Paris Arch de Triomphe Tour">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="name_review" id="name_review" type="text" placeholder="Your name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="lastname_review" id="lastname_review" type="text" placeholder="Your last name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="email_review" id="email_review" type="email" placeholder="Your email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Position</label>
                                <select class="form-control" name="position_review" id="position_review">
                                    <option value="">Please review</option>
                                    <option value="Low">Low</option>
                                    <option value="Sufficient">Sufficient</option>
                                    <option value="Good">Good</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Superb">Super</option>
                                    <option value="Not rated">I don't know</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tourist guide</label>
                                <select class="form-control" name="guide_review" id="guide_review">
                                    <option value="">Please review</option>
                                    <option value="Low">Low</option>
                                    <option value="Sufficient">Sufficient</option>
                                    <option value="Good">Good</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Superb">Super</option>
                                    <option value="Not rated">I don't know</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price</label>
                                <select class="form-control" name="price_review" id="price_review">
                                    <option value="">Please review</option>
                                    <option value="Low">Low</option>
                                    <option value="Sufficient">Sufficient</option>
                                    <option value="Good">Good</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Superb">Super</option>
                                    <option value="Not rated">I don't know</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quality</label>
                                <select class="form-control" name="quality_review" id="quality_review">
                                    <option value="">Please review</option>
                                    <option value="Low">Low</option>
                                    <option value="Sufficient">Sufficient</option>
                                    <option value="Good">Good</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Superb">Super</option>
                                    <option value="Not rated">I don't know</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="form-group">
                        <textarea name="review_text" id="review_text" class="form-control" style="height:100px" placeholder="Write your review"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" id="verify_review" class=" form-control" placeholder="Are you human? 3 + 1 =">
                    </div>
                    <input type="submit" value="Submit" class="btn_1" id="submit-review">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal review --> 

<!-- Inquiry -->
<div class="modal fade" id="mdlInquiry" tabindex="-1" role="dialog" aria-labelledby="mdlInquiryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="mdlInquiryLabel">Inquiry</h4>
            </div>
            <div class="modal-body">
                <div id="message-review"> </div>
                <form class="add-comment custom-form contact-form" id="frmInquiry" action="/test" method="post" novalidate="novalidate">
                    <input type="hidden" name="experience_id" id="experience_id" value="{{ @$experience->id }}">
                    {{ csrf_field() }}
                    <?php $currentURL = url()->current(); ?>
                    <input type="hidden" name="current_url" id="current_url" value="{{ @$currentURL }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="name" id="name" placeholder="First Name" class="form-control required" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" type="text" name="lastname" id="lastname" placeholder="Last Name" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required email" type="email" name="email" id="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required number" type="text" name="phone" id="phone" placeholder="Phone" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="form-group">
                        <textarea name="comment" id="comment" class="form-control" style="height:100px" placeholder="Message"></textarea>
                    </div>
                    <input type="submit" value="Send Inquiry" class="btn_1" id="submit_enquiry" />
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal review --> 
<?php /*
<div class="section cc-contactpop fixed">
    <h3>Contact Centre</h3>  
    <div class="renseignement">
        <i class="fa fa-times icon_close"></i>
    </div>
    <form class="add-comment custom-form contact-form" id="frmFooterInquiry" action="#" method="post" novalidate="novalidate">
        <input type="hidden" name="experience_id" id="experience_id" value="{{ @$experience->id }}">
        {{ csrf_field() }}
        <div class="rows">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea class="required form-control" name="comment" id="comment" placeholder="Message" maxlength="200" style="height:80px;"></textarea>
                </div>    
            </div>
        </div>
        <div class="rows">
            <div class="col-md-12">
                <div class="form-group">
                    <input class="required email form-control" type="email" name="email" id="email" placeholder="Email:" />
                </div>
            </div>
        </div>
        <div class="rows">
            <div class="col-md-12" align="left">
                <input class="btn btn-block btn-pink" type="submit" name="submit-contact" id="submit_enquiry" value="Submit">
            </div>
        </div>
    </form>
</div>
*/?>
@endsection
@section('footer') 
<script src="{{asset('public/basicfront/js/jquery.validate.min.js')}}"  defer></script> 
<script src="{{ asset('public/basicfront/js/jquery.sliderPro.min.js') }}"></script> 
<!-- Carousel --> 
<script src="{{ asset('public/basicfront/js/owl.carousel.min.js') }}"></script> 
<script src="{{ asset('public/basicfront/js/experience-detail.js?v=1.1') }}"></script> 
<script type="text/javascript">

    $(document).ready(function(){
    // let's Hide Items
        /*$(".cc-contactpop form").hide();
        $(".cc-contactpop i.fa.fa-times").hide();
        $(".cc-contactpop p").hide();
        //$(".contact-overlay").addClass("fade shown");
        //$(".contact-overlay").fadeIn();
        // Show on elements ".slideDown"
        $(".cc-contactpop").click(function () {
            $(".cc-contactpop form").slideDown(500);
            $(".cc-contactpop p").slideDown(500);
            $(".cc-contactpop i.fa.fa-times").slideDown(500);
            //$(".contact-overlay").fadeIn();
            $(".cc-contactpop").removeClass("bounce3");
        });
        // Close Hidden Part
        $(".cc-contactpop i.fa.fa-times").click(function () {
            $(this).slideDown(500)
            //$(".contact-overlay").fadeOut();
            $(".cc-contactpop").addClass("bounce3");
            $(".cc-contactpop p").slideUp(500);
            $(".cc-contactpop form").slideUp(500);
            $(".cc-contactpop form").slideUp(500);
            $(".cc-contactpop i.fa.fa-times").hide();
            return false
        });
        //$(".cc-contactpop").trigger("click");
		$(".cc-contactpop").addClass("bounce3");*/

        var experienceId = $("#experience_id").val();
        $("#frmFooterInquiry").on("submit", function (e) {
            e.preventDefault();
            if ($("#frmFooterInquiry").valid()) {
                $("#submit_enquiry").attr("disabled", true);
                $(".cc-contactpop .alert-danger, .cc-contactpop .notification").remove();
                $.ajax({
                    url: APP_URL + "/store-inquiry",
                    method: "POST",
                    data: $("#frmFooterInquiry").serialize() + "&experience_id=" + experienceId,
                    success: function (result) {
                        if (result) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function (key, value) {
                                    resultdisp += value;
                                });
                            } else {
                                resultdisp = result;
                            }
                            $("#submit_enquiry").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                        } else {
                            $("#frmFooterInquiry")[0].reset();
                            $("#submit_enquiry").after('<div class="notification success" align="left">Your Inquiry has been submitted successfully!</div>');
                        }
                        $("#submit_enquiry").attr("disabled", false);
                    }
                });
            }
            return false;
        });
    });
</script>
@endsection