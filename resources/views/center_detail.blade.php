@extends('layouts.deals_layout')
<!-- Meta Info Start-->
@section('meta_title', (!empty(@$center->meta_title)) ? @$center->meta_title : @$center->name)
<?php if (!empty(@$center->meta_description)) { ?> 
    @section('description', strip_tags(@$center->meta_description))
<?php } ?>
<?php if (!empty(@$center->keywords)) { ?> 
    @section('keywords', strip_tags(@$center->keywords))
<?php } ?>
<?php if (!empty(@$center->banner_image_url)) { ?> 
    @section('image', Storage::disk('azure')->url(rawurlencode(@$center->banner_image_url)))
<?php } ?>
<!-- Meta Info End -->

@section('head')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
@endsection
@section('body-class', 'bg-page-listing deal-page')
@section('header')
<div class="middle">
    <div class="container d-flex align-items-center justify-content-between">
        <ul class="no-decoration">
            <li><a href="#package">Package</a></li>
            <!--<li><a href="#centre-overview">Overview</a></li>-->
            <li><a href="#accomodation">Accomodation</a></li>
            <!--<li><a href="#food-overview">Food</a></li>-->
            <!--<li><a href="#our-mission">Our Mission</a></li>-->
            <!--<li><a href="#howtoreach">How to Reach</a></li>-->
        </ul>
        <div>
            <!--<div class="d-flex justify-content-between d-none">-->
            <!--    <div>-->
            <!--        <div class="active-only-middle">-->
            <!--            <span class="fs-22 c-pink">-->
            <!--                <span>₹</span> 244,510.00</span>-->
            <!--            <span class="fs-14 c-medium">/ 29 days</span>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="d-flex align-items-center">-->
            <!--        <div class="active-only-middle">-->
            <!--            <span class="c-white">-->
            <!--                <a href="#" class="bg-form-submit-small ms-2">Book Now</a>-->
            <!--            </span>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <span id="close-middle-menu" class="fs-14 c-pointer">Close</span>-->
            <!--</div>-->
        </div>
    </div>
</div>
@endsection
@section('content')
<section class="bg-listing-place pt-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>{{ @$center->name }}</h1>
            </div>
            <div class="col-12">
                <div class="bg-places-rating-location">
                    <div>
                        <span>
                            <span class="icon-location c-brand me-2"></span>
                            <span>{{ @$center->address_of_center }}</span>
                        </span>
                    </div>
                    <div class="d-inline-flex align-item-center">
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
                    @if(@$center->banner_image_url)  
                    <div class="bg-listing-gallery-items one">
                        <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$center->banner_image_url)) }}" alt="" class="lazy" />
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
                            @if($gallery->bg_exp_id && $gallery->image_url)
                            <img data-src="{{ Storage::disk('azure_bg')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" class="lazy" />
                            @elseif ($gallery->image_url)
                            <img data-src="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" class="lazy" />
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
<section class="bg-listing-details pt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4 order-lg-first left-panel" id="package">
                <div class="row">
                    <div class="col-12">
                        <?php if (@$center->about_center) {?>
                        <div id="packages" class="bg-place-about bg-place-packages pb-4 collapsed">
                            <span title="Expand/Collapse" class="active close-bottom bg-btn bg-btn-secondary btn-medium btn-round icon-arrow_back_ios"></span>
                            <div class="d-flex justify-content-between align-items-center">
                                <h2>About {{ @$center->name }}</h2>
                            </div>
                            <p class="mb-0">{!! @$center->about_center !!}</p>
                        </div>
                        <?php } ?>
                        @if(@$center_experiences)
                        <div class="experience-offered">
                            <h1 class="secondary">Experience Offered</h1>
                            <h4><strong><?php echo count($center_experiences); ?></strong> Results Found</h4>
                            <ul class="bg-sort mt-4 d-none d-lg-flex">
                                <li class="bg-btn bg-btn-primary {{ (@$sortby == "recent") ? "descend active":"ascend active" }}"> <a href="{{ url('/center').'/'.$center->slug.'?sortby=recent-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "recent") ? "active ":" " }}" data-value="recent">Recent</a> </li>
                                <li class="bg-btn bg-btn-primary {{ (@$sortby == "popular") ? "descend active":"ascend active" }}"> <a href="{{ url('/center').'/'.$center->slug.'?sortby=popular-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "popular") ? "active ":" " }}" data-value="popular">Popular</a> </li>
                                <li class="bg-btn bg-btn-primary {{ (@$sortby == "rating") ? "descend active":"ascend active" }}"> <a href="{{ url('/center').'/'.$center->slug.'?sortby=rating-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "rating") ? "active ":" " }}" data-value="rating">Rating</a> </li>
                                <li class="bg-btn bg-btn-primary {{ (@$sortby == "price") ? "descend active":"ascend active" }}"> <a href="{{ url('/center').'/'.$center->slug.'?sortby=price-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "price") ? "active ":" " }}" data-value="price">Price</a> </li>
                            </ul>
                            <!-- Loader -->
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

                            <div class="intereactive-lists pb-lg-4 deal-gallery d-none no-loader">
                                <div class="row">
                                    @foreach (@$center_experiences as $experience) 
                                    <?php  $expimagegalleries = \App\ExperienceImageGallery::select('id', 'image_url', 'image_title')->where("experience_id", $experience->id)->get(); ?>
                                    <div class="col-12">
                                        <div class="fw-500 il-wrapper bg-box">
                                            <div class="in-container">
                                                <div class="article-items" <?php if($experience->featured_experiences > 0) {?> style='border:1px solid red;padding:5px;' <?php }?> >
                                                    <div class="left">
                                                        <div class="container-fluid p-0">
                                                            <div class="slideshow-container">
                                                                <div class="mySlides fade">
                                                                    @if(@$experience->thumbnail_image_url || @$experience->banner_image_url)
                                                                    <img data-src="{{ ($experience->thumbnail_image_url) ? Storage::disk('azure')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('azure')->url(rawurlencode($experience->banner_image_url)) }}" class="lazy" />
                                                                    @endif
                                                                </div>
                                                                @if(@$expimagegalleries)
                                                                @foreach(@$expimagegalleries as $expimagegallerie)
                                                                <div class="mySlides fade">
                                                                    @if(@$expimagegallerie->image_url)
                                                                    <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$expimagegallerie->image_url)) }}" class="lazy" />
                                                                    @endif
                                                                </div>
                                                                @endforeach
                                                                @endif
                                                                <a class="prev">&#10094;</a>
                                                                <a class="next">&#10095;</a>
                                                                <div class="thumnnails">
                                                                    @if(@$experience->thumbnail_image_url || @$experience->banner_image_url)
                                                                    <span class="dot">
                                                                        <img data-src="{{ ($experience->thumbnail_image_url) ? Storage::disk('azure')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('azure')->url(rawurlencode($experience->banner_image_url)) }}" class="lazy" />
                                                                    </span>
                                                                    @endif
                                                                    @if(@$expimagegalleries)
                                                                    @foreach(@$expimagegalleries as $expimagegallerie)
                                                                    @if(@$expimagegallerie->image_url)
                                                                    <span class="dot">
                                                                        <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$expimagegallerie->image_url)) }}" class="lazy" />
                                                                    </span>
                                                                    @endif
                                                                    @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="right">
                                                        <div class="c-medium fs-16">
                                                            <small class="d-flex-center">
                                                                <span class="d-flex-center">
                                                                    <span class="c-brand icon-location me-2"><?php echo \App\Experiences::get_state_country($experience->id); ?></span>
                                                                </span>
                                                            </small>
                                                        </div>
                                                        <div class="head-price mb-1">
                                                            <a href="{{ url("/experience/".$experience->slug) }}"><h3>{{ $experience->name }}</h3></a>
                                                        </div>

                                                        <h4 class="c-medium fw-400 mb-2">
                                                            @if(@$experience->recurring_type == "Daily")
                                                            Available all year round
                                                            @else
                                                            {{ @$experience->available_month }}
                                                            @endif
                                                        </h4>
                                                        <?php if (@$experience->experience_summary) { ?>
                                                        <ul class="list">
                                                            <li>{!! @$experience->experience_summary !!}</li>
                                                        </ul>
                                                        <?php } ?>
                                                        <div class="c-medium fs-16">
                                                            <small class="d-flex-center">
                                                                <span class="c-brand no-decoration">
                                                                    <a href="{{ url("/experience/".$experience->slug) }}">
                                                                        <span class="c-pointer ">More Info</span>
                                                                    </a>    
                                                                </span>
                                                            </small>
                                                        </div>
                                                        <div class="bottom mt-3">
                                                            <div class="text-right">
                                                              <?php if (!empty(@$experience->duration)) { ?>
                                                              <div class="c-medium">
                                                                <small>Total for <?php echo @$experience->duration; ?></small>
                                                              </div>
                                                              <?php } ?>
                                                              @if(@$experience->min_duration_price > 0)
                                                              <div>
                                                                <small class="d-block" style="color:#484848;font-size:13px;margin:0px;">Offers From</small>
                                                                <?php
                                                                $discount = 0;
                                                                //$pay = @$experience->min_duration_price;
                                                                $pay = @$experience->min_promo_price ? @$experience->min_promo_price : @$experience->min_duration_price;
                                                                
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
                                                                    <del class="fs-14 c-pink"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), \App\Http\Helpers\CommonHelper::get_site_currency()) }} </del>
                                                                <?php } ?>
                                                                <span class="fs-18">{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, \App\Http\Helpers\CommonHelper::get_site_currency()) }}</span>
                                                            </div>
                                                            @endif
                                                            <div class="contact-center mt-4">
                                                                <div class="contact d-flex pe-0">
                                                                    <a href="#deal-search" class="bg-form-submit-1 btn-popup me-2 normalcase">Instant Book</a>
                                                                    <button onclick="self.location.href='{{ url("/experience/".$experience->slug) }}'" class="bg-form-submit-1">Send Inquiry</button>
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
                            </div>

                        </div>
                        @endif
                        
                        @if(sizeof(@$center_experiences) > 0)
                        <h2 class="secondary mt-5">Price Check</h2>
                        <section class="deal-search bg-grey-dark" id="deal-search">
                            <div class="container">
                                <form id="frmBooking" name="frmBooking" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking bg-form-el-cont">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Experience</label>
                                                <select id="hdn_experience_id" name="hdn_experience_id" class="form-control">
                                                    <option value="">Please Select Experience</option>
                                                    @foreach(@$center_experiences as $exp)
                                                        <option value="{{$exp->id}}">{{$exp->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Days</label><br />
                                                <select id="durations" name="durations" class="form-control">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Accommodation</label>
                                                <select id="exp_accomodation_id" name="exp_accomodation_id" class="form-control">
                                                    <option value="">Please Select Accomodation</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Start Date</label>
                                                <div class="filter_daily_exp">
                                                    <input type="date" class="form-control" name="booking_date" value="" id="booking_date" />
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
                                        <div class="col-md-2">
                                            <div class="form-group mb-2">
                                                <label class="form-label">Price</label><br />
                                                <h3 class='date_price_acm c-pink d-inline-flex flex-wrap fw-500 mt-2' style="">-</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <!--@if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)-->
                                            <button class="btn_1 btn-block medium text-center btn-reservation bg-form-submit-1 btn-popup me-2 normalcase" id="quickReserverBtn" disabled="disabled">Reserve Now</button>
                                            <!--@endif-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                        @endif
                        @if ((!empty(@$center->accomodation_banner_image_url)) or (sizeof(@$accomodationimagegalleries) > 0) or (@$center->accomodation_overview))    
                        <div id="accomodation" class="deal-gallery deal-page bg-place-accomodation pt-4 pb-4">
                            <!-- <h2 class="mb-4">Accomodation</h2> -->
                            <h1 class="secondary">Accomodations Overview</h1>
                            <p>{!! @$center->accomodation_overview !!}</p>
                            @if ((@$center->accomodation_banner_image_url) or (@$accomodationimagegalleries))
                            <div class="article-items">
                                <div class="left w-100">
                                    <div class="container-fluid p-0">
                                        <div class="slideshow-container mw-100">
                                            @if(@$center->accomodation_banner_image_url)
                                            <div class="mySlides fade">
                                                <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" class="lazy" />
                                            </div>
                                            @endif
                                            @if(sizeof(@$accomodationimagegalleries)>0)
                                            @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                            @if(@$accomodationimagegallery->image_url)
                                            <div class="mySlides fade">
                                                <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" class="lazy" />
                                            </div>
                                            @endif
                                            @endforeach
                                            @endif
                                            <a class="prev">&#10094;</a>
                                            <a class="next">&#10095;</a>
                                            <div class="thumnnails">
                                                @if(@$center->accomodation_banner_image_url)
                                                <span class="dot">
                                                    <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" class="lazy" />
                                                </span>
                                                @endif
                                                @if(sizeof(@$accomodationimagegalleries)>0)
                                                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                @if(@$accomodationimagegallery->image_url)
                                                <span class="dot">
                                                    <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" class="lazy" />
                                                </span>
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        <?php $centeraccomodation = \App\CenterAccomodations::where("center_id", $center->id)->get(); ?>
                        @if(@$centeraccomodation)
                        <div class="bg-swiper">
                            <div class="swiper bgSwiper-1">
                                <!-- <h1 class="secondary">Available Accomodations</h1> -->
                                <div class="swiper-wrapper pt-4 mb-4">
                                    <?php $i=0; ?>
                                    @foreach(@$centeraccomodation as $gallery)
                                    <?php $i++; ?>
                                    @if($gallery)
                                    <?php $accomodation = \App\Accomodation::where("id", $gallery->accomodation_id)->get(); ?>
                                    @if(@$accomodation[0]['banner_image_url'])
                                    <div class="swiper-slide">
                                        <div class="bg-form-el-cont fw-500">
                                            <a data-popup="popup-centre-<?php echo $i; ?>" class="show-bg-modal bg-form-el-wrap">
                                                <div class="bg-form-el bg-popular-places-container p-3">
                                                    <div class="bg-popular-places">
                                                        <img width="100%" height="390" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodation[0]['banner_image_url'])) }}" class="lazy" />
                                                        <h3 class="pt-2">{{ @$accomodation[0]['name'] }}</h3>
                                                        <div class="c-brand fs-14 d-inline-flex align-items-center">
                                                            <span class="icon-read me-1"></span>
                                                            Accommodation Options
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        @endif

                        @if($center->our_philosophy)
                        <div class="bg-border-bottom pt-4 pb-4" id="our-philosophy">
                            <h2>Our Philosophy</h2>
                            <p>{!! @$center->our_philosophy !!}</p>
                        </div>
                        @endif
                        @if($center->our_mission)
                        <div class="bg-border-bottom pt-4 pb-4" id="our-mission">
                            <h2>Our Mission</h2>
                            <p>{!! @$center->our_mission !!}</p>
                        </div>
                        @endif
                        @if($center->how_to_get_there)
                        <div id="howtoreach" class="bg-place-how-to-get bg-border-bottom pt-4 pb-4">
                            <h2 class="mb-4">
                                How to reach <span>{{ @$center->name }}</span>
                            </h2>
                            <div class="bg-item-entries">
                                {!! @$center->how_to_get_there !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Popups -->
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
                </ul>
              </div>
              <span class="icon-love c-brand ms-2"></span>
              <!--<a href="">Save</a>-->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container pt-0 pt-md-3 pb-3">
      <div class="row gallery">
        <div class="col-12 mb-3">
          <div class="bg-gallery-popup">
            @if(@$center->banner_image_url)  
            <div class="bg-gallery-items">
              <img data-src="{{ Storage::disk('azure')->url(rawurlencode(@$center->banner_image_url)) }}" alt="" class="lazy" />
            </div>
            @endif
            @foreach(@$imagegalleries as $gallery)
            <div class="bg-gallery-items">
                @if($gallery->bg_exp_id && $gallery->image_url)
                <img data-src="{{ Storage::disk('azure_bg')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" class="lazy" />
                @elseif($gallery->image_url)
                <img data-src="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" class="lazy" />
                @endif
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

        @section('model_content')
            <!-- Enquire -->
            <div id="popup-enquire" class="container-fluid d-none">
              <h2 class="mb-4 ellipsis">How shall the Centre contact you?</h2>
              <form action="" class="bg-form-el-cont fw-500">
                <div class="bg-form-el-wrap">
                  <div class="bg-form-el">
                    <textarea placeholder="Your Question" name="inquiry_message" id="inquiry_message" rows="3"></textarea>
                  </div>
                </div>
                <p class="mt-4 mb-0 fw-400 fs-12">
                  <span>*</span>
              Hi there, Your message will be sent directly to the teacher and
              the teacher will get in touch with you via the above-mentioned
              email address and phone number.
                </p>
                <button data-popup="popup-call-back" class="show-bg-modal mt-4 bg-form-submit-1 btn-popup btn-sidebar-enquiry">
                  Send Message
                </button>
              </form>
            </div>
    
            <!-- Enquire -->
            @if(@$centeraccomodation)
            <?php $i=0; ?>
            @foreach(@$centeraccomodation as $gallery)
            <?php $i++; ?>
            @if($gallery)
            <?php $accomodation = \App\Accomodation::where("id", $gallery->accomodation_id)->get();?>
            <div id="popup-centre-<?php echo $i; ?>" class="container-fluid d-none">
              <h2 class="mb-4 ellipsis">{{ @$accomodation[0]['name'] }}</h2>
              <div class="detailed-content">
                <p><?php echo $descrription = strip_tags(@$accomodation[0]['description']) ?></p>
                @if(@$accomodation[0]['banner_image_url'])
                    <div class="row">
                      <div class="col">
                        <img class="br-12 lazy" data-src="{{ Storage::disk('azure')->url(rawurlencode(@$accomodation[0]['banner_image_url'])) }}" height="150" width="250" alt="centre-details" />
                      </div>
                    </div>
                @endif    
              </div>
            </div>
            @endif
            @endforeach
            @endif
        @endsection        
@endsection
@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="{{ asset('public/basicfront/js/center-detail.js?v=6.5') }}"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    $(document).ready(function(){
        $(".btn-sidebar-enquiry").on("click", function() {
           $("#popup-call-back #frmRequestCall #message").val($("#inquiry_message").val());
        });
    });
</script>
<script>
    var swiper = new Swiper(".bgSwiper-1", {
      slidesPerView: "auto",
      spaceBetween: 20,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      keyboard: true,
    });
  </script>
<!--<script src="{{ asset('public/basicfront/js/script.js') }}"></script>-->
@endsection