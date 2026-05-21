@extends('layouts.experience_details')
@section('title', @$experience->name)

<?php
$category = $subcategory = "";
foreach ($experience_categories as $ecat) {
    if ($ecat->parent == 0) {
        $category = $ecat->name;
    }
    if ($ecat->parent != 0) {
        $subcategory .= $ecat->name . ", ";
    }
}
?>

<?php
$country = $city = "";
foreach ($experience_destination as $edest) {
    if ($edest->parent == 0) {
        $country = $edest->name;
    }
    if ($edest->parent != 0) {
        $city .= $edest->name . ", ";
    }
}
?>


<!-- Meta Info Start-->
<?php if (!empty(@$experience->meta_title)) { ?>
    @section('meta_title', @$experience->meta_title)
<?php } else { ?>
    @section('meta_title', @$experience->name." in ".$city.", ".$country)
<?php } ?>
<?php if (!empty(@$experience->meta_description)) { ?>
    @section('description', strip_tags(@$experience->meta_description))
<?php } else { ?>
    @section('description', \App\Http\Helpers\CommonHelper::excerpt(strip_tags(html_entity_decode(@$experience->experience_overview)),160))
<?php } ?>
<?php if (!empty(@$experience->keywords)) { ?>
    @section('keywords', strip_tags(@$experience->keywords))
<?php } else { ?>
    @section('keywords', "learn ".$subcategory." in ".$country.", learn and find ".$subcategory.", learn and find ".$category.", ".$subcategory." in ".$city.", ".$category." in ".$country." ".$city.", course in ".$country." ".$city.", ".$category." retreat in ".$country." ".$city)
<?php } ?>
<?php if (!empty(@$experience->banner_image_url)) { ?>
    @section('image', Storage::disk('azure')->url(rawurlencode(@$experience->banner_image_url)))
<?php } ?>
<!-- Meta Info End -->
@section('head')
<style type="text/css">
    .date_price_acm {
        color: #ff3366;
    }

    .text-success {
        color: #3c763d;
    }
</style>
<script defer src="https://cdn.razorpay.com/widgets/affordability/affordability.js"></script>
@endsection
@section('content')
<section class="bg-listing-place pt-4">
    <div class="container">
        <div class="row">
            <div class="col-9">
                <h1>{{ @$experience->name }}</h1>
            </div>
            <?php
            $discount = $razorPayAmount = 0;
            ?>
            @if(@$experienceList->min_duration_price)
            <?php
            $pay = @$experienceList->min_promo_price ? @$experienceList->min_promo_price : @$experienceList->min_duration_price;
            ?>
            <div class="col-3">
                <div class="text-right">
                    <small>Offers From</small>
                    <h2 class="c-pink d-inline">
                    <?php
                        if ((!empty(@$experienceList->offer_start_date)) && (!empty(@$experienceList->offer_discount)) && (@$experienceList->offer_discount > 0)) {
                            $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                            if ((\Carbon\Carbon::parse(@$experienceList->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experienceList->offer_end_date)->format("Y-m-d") >= $now)) {
                                if (@$experienceList->offer_discount_type == "amt") {
                                    $discount += @$experienceList->offer_discount;
                                } else {
                                    $discount += (@$pay * @$experienceList->offer_discount) / 100;
                                }
                            }
                        }
                        if (!empty(@$discount)) { ?>
                            <del class="fs-16 text-default" style="color:#333;"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), $site_currency) }} </del>
                        <?php } ?>
                        <?php
                        $razorPayAmount = \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency, false);
                        ?>
                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency) }}
                    </h2>
                    <?php /*{!! @$experience->duration ? "/ ".@$experience->duration." Days" : '' !!}*/?>
                </div>
            </div>
            @endif
            <div class="col-12 mt-2">
                <div class="bg-places-rating-location">
                    <div>
                        @if(@$center->address_of_center || @$experience->location)
                        <span>
                            <span class="icon-location c-brand me-2">{{ @$center->address_of_center }}</span>
                            <span>{{ @$experience->location }}</span>
                        </span>
                        @endif
                    </div>
                    <div class="d-inline-flex align-item-center">
                        <span class="icon-share c-brand me-2"></span>
                        <div class="bg-menu-list">
                            <span> Share </span>
                            <ul class="bg-box horiz">
                                <li>
                                    <!--<li><a href="https://www.instagram.com/thebalanceboat"><i class="icon-instagram"></i></a></li>-->
                                    <a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a>
                                </li>
                            </ul>
                        </div>
                        <span class="icon-compass c-brand ms-2 me-2"></span>
                        <a href="#enquire-panel">Enquire</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-listing-gallery-cont pt-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bg-listing-gallery main">
                    <?php $i = 0;?>
                    @if(@$experience->banner_image_url)  
                    <div class="bg-listing-gallery-items one">
                        <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$experience->banner_image_url)),'?') }}" alt="{!! $experience->banner_image_url !!}" />
                    </div>
                    @else
                        <?php $i = -1;?>
                    @endif
                    <?php 
                    if ($i == -1) {
                        $gi = array("one","two", "three", "four", "five"); 
                    } else {
                        $gi = array("two", "three", "four", "five"); 
                    }?>
                    @foreach(@$imagegalleries as $gallery)
                    <?php if ($i < 4) {
                        $i++;
                        ?>
                        <div class="bg-listing-gallery-items <?php echo ($gi[$i - 1]) ?? ''; ?>">
                            @if(@$gallery->image_url)
                            @if($gallery->bg_exp_id)
                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure_bg')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" />
                            @else
                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" />
                            @endif
                            @endif
                        </div>
                    <?php } ?>
                    @endforeach
                    <span id="bg-gallery-all" class="show-all-btn">
                        <span class="icon-arrows1 me-2"></span> Show All
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /*if(@$_GET['preview'] == 1) {*/?>
<section class="deal-search mt-5 bg-grey-dark">
    <div class="container">
        <div class="row home-banner-container">
            <div class="col-12">
                <form id="frmBooking" name="frmBooking" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking bg-form-el-cont">
                    <input type="hidden" id="hdn_experience_id" name="hdn_experience_id" value="{{ @$experience->id }}" />
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-3 col-md-1">
                            <div class="form-group mb-2">
                                <label class="form-label">Days</label><br />
                                <select id="durations" name="durations" class="form-control">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-9 col-md-3">
                            <div class="form-group mb-2">
                                <label class="form-label">Accommodation</label>
                                <select id="exp_accomodation_id" name="exp_accomodation_id" class="form-control">
                                    <option value="">Please Select Accomodation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="form-group mb-2">
                                <input type="hidden" name="booking_date" id="booking_date" value="">
                                <label class="form-label">Start Date</label>
                                <div class="filter_daily_exp">
                                    <input type="text" class="bkdate" name="booking_date" value="" min="<?php echo date("Y-m-d");?>" onfocus="(this.type = 'date')" id="booking_date" />
                                    <!--<input type="text" class="form-control bkdate" id="booking_date" name="booking_date" value="" data-date-format="mm/dd/yyyy" readonly/>-->
                                </div>
                                <div class="filter_dates_radio_group" style="display:none;">
                                    <select name="filter_dates_dd" id="filter_dates_dd">
                                        <option value="">Please Select a Value</option>
                                    </select>
                                </div>
                                <div class="no_booking_date" style="display:none;">
                                    <p>Not Available</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="form-group mb-2">
                                <label class="form-label">Price</label><br />
                                <h3 class='date_price_acm c-pink d-inline-flex flex-wrap fw-500 mt-2' style="">-</h3>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="form-group mb-2">
                                <label class="form-label">Booking Amount</label><br />
                                <h3 class='booking_amount text-success c-pink d-inline-flex flex-nowrap fw-500 mt-2' style="color: #3c763d;">-</h3>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="cls-bk-submit">
                                @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
                                <button class="btn-reservation bg-form-submit-1 me-2 normalcase" id="quickReserverBtn" disabled="disabled">Book Now</button>
                                @endif
                                <button data-popup="checkAvailability" class="show-bg-modal bg-form-submit-1 btn-popup mt-2">Check Availability</button>
                                <!--<button data-popup="requstcallPopup" class="show-bg-modal bg-form-submit-1 mt-2">Send Inquiry</button>-->
                                <!--<a href="#mdlInquiry" data-toggle="modal" target="_blank" class="btn_2 btn-block medium text-center btn-send-inquiry show-bg-modal bg-form-submit-1">Send Inquiry</a>    -->
                            </div>
                        </div>
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </form>
                <hr />
            </div>
            <div class="col-12 col-xl-10 offset-xl-1">
                <div class="bb-exp-suits mt-3">
                    <?php $xp = 0; ?>
                    @foreach(@$experience_accomodations as $experience_accomodation)
                    <div class="suits {{ ($xp==0) ? 'active' :'' }}">{{ @$experience_accomodation->name }}</div>
                    <?php $xp++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<?php //}?>
<section class="bg-listing-details pt-5 mb-5 bg-grey-dark">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 mb-4 right-panel">

                <div class="bg-listing-right-wrapper">
                    <div class="mb-4">

                        <h2 class="ellipsis">
                            Package Price Details
                        </h2>
                    </div>
                    <!-- Enquire -->
                    <div id="enquire-panel" class="bg-box bg-white">
                        <?php $xp = 0; ?>
                        @foreach(@$experience_accomodations as $experience_accomodation)
                        <div class="suit-choose delux {{ ($xp>0) ? 'd-none' :'' }}">
                            <?php $xp++; ?>
                            <span class="fs-18">
                                <span>Thanks for Choosing <span class="fw-500">{{ @$experience_accomodation->name }}</span>
                                </span>
                                <span>for <span class="fw-500">
                                        <?php echo @$experience_accomodation->duration; ?>
                                    </span>
                                    <span>at a price of </span>
                                    <span class="c-pink d-inline-flex flex-nowrap fw-500">
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
                                        if (!empty(@$discount)) { ?>
                                            <del class="text-default"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }} </del>
                                        <?php } ?>
                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }}
                                    </span>
                                </span>
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <div id="razorpay-affordability-widget" class="mt-3" style="margin:auto; text-align:center;"></div>
                </div>
            </div> 
            <div class="col-12 col-lg-9 mb-4 order-lg-first left-panel" id="package">
                <div class="row">
                    <div class="col-12">
                        <?php /*<div class="">
                            <h2 class="ellipsis">
                                Package Summary
                            </h2>
                            <div class="bg-brand br-12 c-white p-2 d-none">
                                Starting Delux Villa at 15 Days / ₹ 244,510.00 Valued
                                up to Maharaja Suite at ₹ 563,962.00
                            </div>

                        </div>*/?>
                        <!-- Experience Summary -->
                        <div class="intereactive-lists-loader">
                            <div class="row">
                                <div class="col-12">
                                    <div class="il-wrapper bg-box">
                                        <div class="listing-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="il-wrapper bg-box">
                                        <div class="listing-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="il-wrapper bg-box">
                                        <div class="listing-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="il-wrapper bg-box">
                                        <div class="listing-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="il-wrapper bg-box">
                                        <div class="listing-loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="intereactive-lists pb-lg-4 deal-gallery no-loader d-none">
                            <div class="row mb-4">
                                <?php $xp = 0; ?>
                                @foreach(@$experience_accomodations as $experience_accomodation)
                                <div class="col-12 suits-details-wrapper {{ ($xp>0) ? 'd-none' :'' }}">
                                    <?php $xp++; ?>
                                    <div class="fw-500 il-wrapper bg-box">
                                        <div class="in-container">
                                            <div class="article-items full-width">
                                                <div class="left">
                                                    <div class="container-fluid p-0">
                                                        <div class="slideshow-container">
                                                            @if(@$accomodationimagegalleries)
                                                            @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                            @if ($accomodationimagegallery->accomodation_id == $experience_accomodation->id)
                                                            @if(@$accomodationimagegallery->image_url)
                                                            <div class="mySlides fade">
                                                                <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                            </div>
                                                            @endif
                                                            @endif
                                                            @endforeach
                                                            @endif
                                                            <a class="prev">&#10094;</a>
                                                            <a class="next">&#10095;</a>
                                                            <div class="thumnnails">
                                                                @if(@$accomodationimagegalleries)
                                                                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                                @if ($accomodationimagegallery->accomodation_id == $experience_accomodation->id)
                                                                @if(@$accomodationimagegallery->image_url)
                                                                <span class="dot">
                                                                    <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                                </span>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="right mt-2">
                                                    <div>
                                                        <span class="bg-brand br-12 c-white d-inline-flex p-2">
                                                            {{ $experience_accomodation->name }}
                                                        </span>
                                                    </div>
                                                    <div class="c-medium fs-16">
                                                        <small class="d-flex-center">
                                                            <span class="d-flex-center">
                                                                <span class="c-brand icon-location me-2"></span>
                                                            </span>
                                                            <span class="me-2"><?php echo \App\Experiences::get_state_country($experience->id); ?></span>
                                                        </small>
                                                    </div>
                                                    <div class="head-price mb-1">
                                                        <h3>
                                                            <a href="javascipt:void(0);" class="c-pointer popup-large more-info-deal">{{ $experience_accomodation->name }}</a>
                                                        </h3>
                                                    </div>

                                                    <h4 class="c-medium fw-400 mb-2">
                                                        @if(@$experience_accomodation->recurring_type == "Daily")
                                                        Available all year round
                                                        @else
                                                        {{ @$experience_accomodation->available_month }}
                                                        @endif
                                                    </h4>
                                                    <ul class="list">
                                                        {!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$experience_accomodation->description))) !!}
                                                    </ul>

                                                    <div class="c-medium fs-16">
                                                        <small class="d-flex-center">
                                                            <span class="c-brand no-decoration">
                                                                <span class="c-pointer popup-large more-info-deal">More Info</span>
                                                            </span>
                                                        </small>
                                                    </div>
                                                    <div class="bottom mt-3">
                                                        <div class="text-right">
                                                            <div class="c-medium">
                                                                <small>Total for <?php echo @$experience_accomodation->duration; ?></small>
                                                            </div>
                                                            <div>
                                                                <span class="fs-18 c-pink">
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
                                                                    if (!empty(@$discount)) { ?>
                                                                        <del class="text-default"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }} </del>
                                                                    <?php } ?>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <small>Valued up to {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="contact-center mt-4">
                                                            <div class="contact d-flex pe-0">
                                                                <a href="#" class="bg-form-submit-1 btn-popup me-2 normalcase">
                                                                    Instant Book
                                                                </a>
                                                                <button data-popup="requstcallPopup" class="show-bg-modal bg-form-submit-1">
                                                                    Send Inquiry
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if(@$experience->experience_summary)
                            <div class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">
                                        Experience Summary
                                    </h2>
                                </div>
                                <div class="bg-list-icon">
                                {!! @$experience->experience_summary !!}
                                </div>
                            </div>
                            @endif
    
                            @if(@$experience->experience_overview)
                            <div class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">
                                        Experience Overview
                                    </h2>
                                </div>
                                <div>
                                {!! @$experience->experience_overview !!}
                                </div>
                            </div>
                            @endif
                            
                            @if(@$experience->schedule)
                            <div class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">
                                        Experience Schedule
                                    </h2>
                                </div>
                                <div>
                                {!! @$experience->schedule !!}
                                </div>
                            </div>
                            @endif
                            
                            @if(@$experience->experience_details)
                            <div class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">
                                        Experience Details
                                    </h2>
                                </div>
                                <div>
                                {!! @$experience->experience_details !!}
                                </div>
                            </div>
                            @endif
    
                            @if(@$center->about_center)
                            <div id="about" class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">About {{ @$center->name }}</h2>
                                    <span class="view-website c-medium fs-14 fw-500">
                                        <span class="c-brand icon-globe me-2"></span>
                                        <a target="_blank" href="{{ url("center/".@$center->slug) }}">View
                                            Profile</a>
                                    </span>
                                </div>
                                {!! @$center->about_center !!}
                            </div>
                            @endif
                            <?php /*
                            @if(@$center->accomodation_overview)
                            <div id="centre-overview" class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2>Centre Overview</h2>
                                </div>
                                <div>
                                    {!! @$center->accomodation_overview !!}
                                </div>
                            </div>
                            @endif*/?>
                            
                            @if($experience->experience_certification_id)
                            <div class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <img alt="Image" class="bg-box p-0 lazy" width="100%" alt="" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$center->banner_image_url)),'?') }}" />
                                    <!--<img class="bg-box p-0" width="100%" src="images/centre-deal-1.jpg" alt="">-->
                                    <h2 class="mb-4">certification</h2>
                                    <?php $imagegallerie = \App\Certificates::where("id", $experience->experience_certification_id)->get(); ?>
                                    @if(@$imagegallerie)
                                    @foreach(@$imagegallerie as $gallery)
                                    @if(@$gallery && @$gallery->image_url)
                                    <img class="lazy" width="175px" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{!! @$gallery->image_url !!}">
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
    
                            @if((sizeof((array)@$accomodationimagegalleries)>0) OR (@$center->accomodation_banner_image_url) OR (@$center->accomodation_overview))
                            <div id="accomodation" class="deal-gallery deal-page bg-place-accomodation bg-border-bottom pt-4 pb-4 bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Accomodation</h2>
                                    <div class="article-items">
                                    <div class="left w-100">
                                        <div class="container-fluid p-0">
                                            <div class="slideshow-container">
                                                @if(@$center->accomodation_banner_image_url)
                                                <div class="mySlides fade">
                                                    <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)),'?') }}" />
                                                </div>
                                                @endif
                                                @if(sizeof((array)@$accomodationimagegalleries)>0)
                                                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                    @if(@$accomodationimagegallery->image_url)
                                                    <div class="mySlides fade">
                                                        <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    <a class="prev">&#10094;</a>
                                                    <a class="next">&#10095;</a>
                                                    <div class="thumnnails">
                                                        @if(@$center->accomodation_banner_image_url)
                                                        <span class="dot">
                                                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)),'?') }}" />
                                                        </span>
                                                        @endif
                                                        @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                        @if(@$accomodationimagegallery->image_url)
                                                        <span class="dot">
                                                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                        </span>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                @endif    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div>
                                    {!! @$center->accomodation_overview !!}
                                </div>
                            </div>
                            @endif
                            
                            @if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))
                            <div id="food-overview" class="deal-gallery deal-page bg-place-accomodation bg-border-bottom pt-4 pb-4 bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Food Overview</h2>
                                    <div class="article-items">
                                    <div class="left w-100">
                                        <div class="container-fluid p-0">
                                            <div class="slideshow-container">
                                                @if(@$experience->food_banner_image_url)
                                                <div class="mySlides fade">
                                                    <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$experience->food_banner_image_url)),'?') }}" />
                                                </div>
                                                @endif
                                                @if(sizeof(@$foodimagegalleries->toArray())>0)
                                                    @foreach(@$foodimagegalleries as $foodimagegallery)
                                                    @if(@$foodimagegallery->image_url)
                                                    <div class="mySlides fade">
                                                        <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(@$foodimagegallery->image_url),'?') }}" />
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    <a class="prev">&#10094;</a>
                                                    <a class="next">&#10095;</a>
                                                    <div class="thumnnails">
                                                        @if(@$experience->food_banner_image_url)
                                                        <span class="dot">
                                                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$experience->food_banner_image_url)),'?') }}" />
                                                        </span>
                                                        @endif
                                                        @foreach(@$foodimagegalleries as $foodimagegallery)
                                                        @if(@$foodimagegallery->image_url)
                                                        <span class="dot">
                                                            <img class="lazy" data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$foodimagegallery->image_url)),'?') }}" />
                                                        </span>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                {!! @$experience->food_overview !!}
                            </div>
                            @endif
                            
                            @if(@$experience->what_is_included)
                            <div class="bg-box mb-4 bg-white" id="what-is-included">
                                <div class="mb-4">
                                    <h2 class="ellipsis">What is Included</h2>
                                </div>    
                                <div class="bg-list-icon">
                                    {!! @$experience->what_is_included !!}
                                </div>
                            </div>
                            @endif
                            
                            @if(@$experience->what_is_not_included)
                            <div class="bg-box mb-4 bg-white" id="what-is-included">
                                <div class="mb-4"><h2 class="ellipsis">What is Not Included</h2></div>
                                <div class="bg-list-icon">
                                    {!! @$experience->what_is_not_included !!}
                                </div>
                            </div>
                            @endif
                            
                            @if(@$experience->styles_taught)
                            <div class="bg-box mb-4 bg-white" id="style">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Style</h2>
                                </div>    
                                <ul class="bg-list-icon">
                                    <?php
                                    foreach (explode(",", @$experience->styles_taught) as $styles_taught) {
                                    ?>
                                        <li>{{ $styles_taught }}</li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            @endif
                            
                            @if(@$experience->language_spoken)
                            <div class="bg-box mb-4 bg-white" id="languages">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Languages</h2>
                                </div>    
                                <ul class="bg-list-icon">
                                    <?php
                                    foreach (explode("||", @$experience->language_spoken) as $language) {
                                    ?>
                                        <li>{{ @$language }}</li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            @endif
                            
                            @if(@$center->how_to_get_there)
                            <div id="howtoreach" class="bg-box mb-4 bg-white">
                                <div class="mb-4">
                                    <h2 class="ellipsis">How to reach <span>{{ @$center->name }}</span></h2>
                                </div>  
                                <div>
                                    {!! @$center->how_to_get_there !!}
                                </div>
                            </div>
                            @endif

                            @if(@$experience->booking_info)
                            <div class="bg-box mb-4 bg-white" id="booking_info">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Booking Info</h2>
                                </div>
                                <div>
                                    {!! @$experience->booking_info !!}
                                </div>
                            </div>
                            @endif

                            @if(@$experience->cancellation_policy)
                            <div class="bg-box mb-4 bg-white" id="cancellation">
                                <div class="mb-4">
                                    <h2 class="ellipsis">Cancellation Policy</h2>
                                </div>
                                <div>
                                    {!! @$experience->cancellation_policy !!}
                                </div>
                            </div>
                            @endif
                            @if(@$center->bg_id)
                            <div class="bg-box mb-4 bg-white">
                                <iframe src="https://balancegurus.com/embed/reviews?listing_id=<?php echo @$center->bg_id;?>" target="_blank" 
  width="100%"
  height="600"
  style="border:none;"></iframe> 
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="bg-gallery-popup" class="bg-listing-gallery-cont gallery-popup hidden">
    <div class="container-fluid pos-sticky-top bg-white">
        <div class="row">
            <div class="col-12">
                <div class="head ps-0 pe-3 ps-md-4 pe-md-4">
                    <div id="bg-gallery-back" class="gallery-back-btn">
                        <div class="bg-btn bg-btn-secondary btn-medium btn-round">
                            <span class="fs-18 icon-arrow-left"></span>
                        </div>
                    </div>
                    <div class="right ms-auto">
                        <span class="icon-share c-brand me-2"></span>
                        <div class="bg-menu-list">
                            <span> Share </span>
                            <ul class="bg-box horiz">
                                <li>
                                    <a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a>
                                </li>
                                <?php /*<!--<li>-->
                                <!--    <a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&url=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school"><span class="icon-google-plus"></span></a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a target="_blank" href="https://twitter.com/share?via=in1.com&text=Kranti%20Yoga%20School%20Balancegurus"><span class="icon-twitter"></span></a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school&title=Kranti%20Yoga%20School%20Balancegurus&summary=Kranti%20Yoga%20School%20is%20located%20in%20Canacona,%20Goa.%20This%20Yoga%20Teacher%20Training%20Centre%20offers%20100%20Hour%20Yoga%20Teacher%20Training,%20200%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Yoga,%20200%20Hour%20Yin%20Yoga%20&%20Vinyasa%20Flow,%20300%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Adjustments,%20300%20Hour%20Y&source=in1.com"><span class="icon-linkedin"></span></a>-->
                                <!--</li>-->*/?>
                            </ul>
                        </div>
                        <span class="icon-love c-brand ms-2"></span>
                        <a href="">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-0 pt-md-3 pb-3">
        <div class="row gallery">
            <div class="col-12 mb-3">
                <div class="bg-gallery-popup">
                    @foreach(@$imagegalleries as $gallery)
                        @if($gallery->image_url)
                        <div class="bg-gallery-items">
                            <img class="gallery__Image" src="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" data-description="" data-large="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" />
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer')
<script src="{{asset('public/basicfront/js/jquery.validate.min.js')}}" defer></script>
<script src="{{ asset('public/basicfront/js/jquery.sliderPro.min.js') }}" defer></script>
<!-- Carousel -->
<script src="{{ asset('public/basicfront/js/owl.carousel.min.js') }}" defer></script>
<script src="{{ asset('public/basicfront/js/experience-detail.js?v=1.1') }}" defer></script>
<script type="text/javascript">
    $(document).ready(function() {
        var experienceId = $("#experience_id").val();
        $("#frmFooterInquiry").on("submit", function(e) {
            e.preventDefault();
            if ($("#frmFooterInquiry").valid()) {
                $("#submit_enquiry").attr("disabled", true);
                $(".cc-contactpop .alert-danger, .cc-contactpop .notification").remove();
                $.ajax({
                    url: APP_URL + "/store-inquiry",
                    method: "POST",
                    data: $("#frmFooterInquiry").serialize() + "&experience_id=" + experienceId,
                    success: function(result) {
                        if (result) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function(key, value) {
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

        $(".more-info-deal").on("click", function(){
            $(window).trigger('resize');
        });

        
        <?php 
        if ($razorPayAmount) {
            if (\App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - @$discount, $site_currency, false) > 0) {?>

                const key = "rzp_live_wp4Xjvh9X9GOYe"; //Replace it with your Test Key ID generated from the Dashboard
                const amount = <?php echo $razorPayAmount*100; ?> //in paise

                window.onload = function() {
                const widgetConfig = {
                    "key": key,
                    "amount": amount,
                };
                const rzpAffordabilitySuite = new RazorpayAffordabilitySuite(widgetConfig);
                rzpAffordabilitySuite.render();
                }
            <?php }
        }?>
    });
</script>
@endsection
