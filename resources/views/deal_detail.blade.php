@extends('layouts.deals_layout')
@section('title', @$deal->name)
@section('meta_title', (!empty(@$deal->meta_title)) ? @$deal->meta_title : @$deal->name)
<?php if (!empty(@$deal->meta_description)) { ?>
    @section('description', strip_tags(@$deal->meta_description))
<?php } ?>
<?php if (!empty(@$deal->keywords)) { ?>
    @section('keywords', strip_tags(@$deal->keywords))
<?php } ?>
<?php if (!empty(@$deal->image_url)) { ?>
    @section('image', Storage::disk('azure')->url(rawurlencode(@$deal->image_url)))
<?php } ?>
@section('head')
<style>
    .head-price a {
        text-decoration: none;
    }

    #bg-gallery-popup ul {
        list-style: initial;
        padding-left: 17px;
    }
</style>
@endsection
@section('header')
<div class="middle">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="row w-100">
            <div class="col-12 d-flex justify-content-between">
                <ul class="bg-sort">
                    <li class="bg-btn bg-btn-primary {{ (@$sortby == "popular") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=popular-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "popular") ? "active ":" " }}" data-value="popular">Popular</a> </li>
                    <li class="bg-btn bg-btn-primary {{ (@$sortby == "price") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=price-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "price") ? "active ":" " }}" data-value="price">Price</a> </li>
                </ul>
                <div class="d-flex justify-content-between">
                    <span id="close-middle-menu" class="fs-14 c-pointer">Close</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<section class="bg-listing-place pt-4 title">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <h1 class="secondary">{!! $deal->name !!}</h1>
                {!! $deal->description !!}
            </div>
        </div>
    </div>
</section>
<section class="pt-5">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-8">
                <h4><strong>{{ count($experience) }}</strong> Results Found</h4>
            </div>

            <div class="col-4 d-lg-none">
                <lable>Currency</lable>
                <form id="frmGlobalCurrency" method="get" class="search_form" action="#">
                    {{ csrf_field() }}
                    <div class="bg-form-el-cont">
                        <select class="global_site_currency form-control" name="global_site_currency" id="global_site_currency" style="border:1px solid #000; min-height:auto;">
                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                            <option value="{{ $currency }}" <?php echo $site_currency == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="row d-none d-lg-flex">
            <div class="col-6">
                <ul class="bg-sort mt-4">
                    <!--<li class="bg-btn bg-btn-primary {{ (@$sortby == "recent") ? "descend active":"ascend" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=recent' }}" class="sortby {{ (@$sortby == "recent") ? "active ":" " }}" data-value="recent">Recent</a></li>-->
                    <li class="bg-btn bg-btn-primary {{ (@$sortby == "popular") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=popular-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "popular") ? "active ":" " }}" data-value="popular">Popular</a> </li>
                    <!--<li class="bg-btn bg-btn-primary {{ (@$sortby == "rating") ? "descend active":"ascend" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=rating' }}" class="sortby {{ (@$sortby == "rating") ? "active ":" " }}" data-value="rating">Rating</a> </li>-->
                    <li class="bg-btn bg-btn-primary {{ (@$sortby == "price") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=price-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "price") ? "active ":" " }}" data-value="price">Price</a> </li>
                    <?php /*<li class="bg-btn bg-btn-primary {{ (@$sortby == "discount") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=discount-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "discount") ? "active ":" " }}" data-value="discount">Discount</a> </li>*/ ?>
                </ul>
            </div>
            <div class="col-4">
                <form id="frmSearch" method="get" class="search_form form-inline" action="#">
                    {{ csrf_field() }}
                    <div class="row">
                        @if($locations)
                        <div class="col-6 bg-form-el-cont">
                            <lable>Location</lable>
                            <select class="form-control slocation select2" name="slocation" style="border:1px solid #000; min-height:auto;" data-placeholder="Select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" <?php echo  $location->id == @$_GET["slocation"] ? "selected" : ""; ?>>{!! $location->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(isset($tags) && $tags->isNotEmpty())
                        <div class="col-6 bg-form-el-cont">
                            <label>Tags</label>
                            <select class="form-control stags select2" name="stags" style="border:1px solid #000; min-height:auto;" data-placeholder="Select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach($tags as $tag)
                                <option value="{{ $tag }}" <?php echo  $tag == @$_GET["stags"] ? "selected" : ""; ?>>{!! $tag !!}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-2">
                <lable>Currency</lable>
                <form id="frmGlobalCurrency" method="get" class="search_form" action="#">
                    {{ csrf_field() }}
                    <div class="bg-form-el-cont">
                        <select class="global_site_currency form-control select2" name="global_site_currency" id="global_site_currency" style="border:1px solid #000; min-height:auto;">
                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                            <option value="{{ $currency }}" <?php echo $site_currency == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <!-- Loader -->
        @if(@$experience)
        <div class="intereactive-lists pb-lg-4 deal-gallery">
            <div class="row">
                @foreach(@$experience as $experience_d)
                <div class="col-12">
                    <div class="fw-500 il-wrapper bg-box">
                        <div class="in-container">
                            <div class="article-items">
                                <?php
                                $imagegalleries = \App\ExperienceImageGallery::where("experience_id", $experience_d->id)->limit(4)->get();
                                ?>
                                @if(@$imagegalleries)
                                <div class="left">
                                    <div class="container-fluid p-0">
                                        <div class="slideshow-container">
                                            @if(@$experience_d->banner_image_url)
                                            <div class="mySlides fade">
                                                <img data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$experience_d->banner_image_url)),'?') }}" class="lazy" />
                                            </div>
                                            @endif
                                            @foreach(@$imagegalleries as $gallery)
                                            @if($gallery)
                                            @if(@$gallery->image_url)
                                            <div class="mySlides fade">
                                                <img data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" class="lazy" />
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                            <a class="prev">&#10094;</a>
                                            <a class="next">&#10095;</a>
                                            <div class="thumnnails">
                                                @if(@$experience_d->banner_image_url)
                                                <span class="dot">
                                                    <img data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode(@$experience_d->banner_image_url)),'?') }}" class="lazy" />
                                                </span>
                                                @endif
                                                @foreach(@$imagegalleries as $gallery)
                                                @if($gallery)
                                                @if(@$gallery->image_url)
                                                <span class="dot">
                                                    <img data-src="{{ strtok(Storage::disk('azure')->url(rawurlencode($gallery->image_url)),'?') }}" class="lazy" />
                                                </span>
                                                @endif
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="right">
                                    <div class="c-medium fs-16">
                                        <small class="d-flex-center">
                                            <span class="d-flex-center"><span class="c-brand icon-location me-2"></span></span>
                                            <span class="me-2"><?php
                                                                echo \App\Experiences::get_state_country($experience_d->id);
                                                                ?></span>
                                        </small>
                                    </div>
                                    <div class="head-price mb-1">
                                        <h3>
                                            <a href="{{ url("/experience/".$experience_d->slug) }}" target="_blank">{{ $experience_d->name }}</a>
                                        </h3>
                                    </div>
                                    <h4 class="c-medium fw-400 mb-2">
                                        @if(@$experience_d->recurring_type == "Daily")
                                        Available all year round
                                        @else
                                        {{ @$experience_d->available_month }}
                                        @endif
                                    </h4>
                                    <?php if (@$experience_d->experience_summary) { ?>
                                        <div style="height:188px;overflow:hidden;">
                                            <ul class="list">
                                                {!! @$experience_d->experience_summary !!}
                                            </ul>
                                        </div>
                                    <?php } ?>

                                    <?php /*
                                        <div class="c-medium fs-16">
                                            <small class="d-flex-center">
                                            <span class="c-brand no-decoration">
                                                <span class="c-pointer exp-popup-large" data-rel="<?php echo $experience_d->id;?>">More Info</span>
                                            </span>
                                            </small>
                                        </div>*/ ?>
                                    <div class="bottom mt-3">
                                        <div class="text-right">
                                            <?php if (!empty(@$experience_d->duration)) { ?>
                                                <div class="c-medium">
                                                    <small>Total for <?php echo @$experience_d->duration; ?></small>
                                                </div>
                                            <?php } ?>

                                            @if(@$experience_d->min_duration_price > 0)
                                            <div>
                                                <small class="hidden-xs hidden-sm d-block" style="color:#484848;font-size:13px;margin:0px;">Offers From</small>
                                                <?php
                                                $discount = 0;
                                                $pay = @$experience_d->min_promo_price ? @$experience_d->min_promo_price : @$experience_d->min_duration_price;

                                                if ((!empty(@$experience_d->offer_start_date)) && (!empty(@$experience_d->offer_discount)) && (@$experience_d->offer_discount > 0)) {
                                                    $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                                                    if ((\Carbon\Carbon::parse(@$experience_d->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experience_d->offer_end_date)->format("Y-m-d") >= $now)) {
                                                        if (@$experience_d->offer_discount_type == "amt") {
                                                            $discount += @$experience_d->offer_discount;
                                                        } else {
                                                            $discount += (@$pay * @$experience_d->offer_discount) / 100;
                                                        }
                                                    }
                                                }
                                                if (!empty(@$discount)) { ?>
                                                    <del class="fs-14 c-pink" style="font-size:14px; color:#333;"> {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), $site_currency) }} </del>
                                                <?php } ?>
                                                @if((@$pay - $discount) > 0)
                                                <span class="fs-18  c-pink">{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency) }}</span>
                                                @endif
                                                <?php /*<span class="fs-18 c-pink">
                                                        @if(@$experience_d->min_promo_price) <del class="fs-16 c-medium"> @endif
                                                            {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience_d->min_duration_price, \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                                                        @if(@$experience_d->min_promo_price) </del> @endif
                                                    </span>
                                                    @if(@$experience_d->min_promo_price)
                                                    <span class="fs-18 c-pink">
                                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience_d->min_promo_price, \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                                                    </span>
                                                    @endif*/ ?>
                                            </div>
                                            @endif
                                            <div class="contact-center mt-4">
                                                <div class="contact d-flex pe-0">
                                                    <a href="{{ url("/experience/".$experience_d->slug) }}" class="bg-form-submit-1 btn-popup me-2 normalcase" style="height:40px;line-height:35px;">
                                                        Book Now
                                                    </a>
                                                    <button data-popup="popup-call-back" class="show-bg-modal bg-form-submit-1 send-inquiry" data-exp-id="{{ $experience_d->id }}" data-inquiry="{{ url("/experience/".$experience_d->slug) }}" style="height:40px;line-height:35px; padding:0 20px;">
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
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center mb-5">
            <h5>No Experience Found</h5>
        </div>
        @endif
    </div>
</section>
@endsection

@section('mob_link')
<div class="row bg-mobile-bottom justify-content-center d-lg-none">
    <div class="col-5 pe-0">
        <ul class="bg-sort">
            <!--<li class="bg-btn bg-btn-primary {{ (@$sortby == "recent") ? "descend active":"ascend" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=recent' }}" class="sortby {{ (@$sortby == "recent") ? "active ":" " }}" data-value="recent">Recent</a></li>-->
            <li class="bg-btn bg-btn-primary {{ (@$sortby == "popular") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=popular-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "popular") ? "active ":" " }}" data-value="popular">Popular</a> </li>
            <!--<li class="bg-btn bg-btn-primary {{ (@$sortby == "rating") ? "descend active":"ascend" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=rating' }}" class="sortby {{ (@$sortby == "rating") ? "active ":" " }}" data-value="rating">Rating</a> </li>-->
            <li class="bg-btn bg-btn-primary {{ (@$sortby == "price") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=price-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "price") ? "active ":" " }}" data-value="price">Price</a> </li>
            <?php /*<li class="bg-btn bg-btn-primary {{ (@$sortby == "discount") ? "descend active":"ascend active" }}"> <a href="{{ url('/deal').'/'.$deal->slug.'?sortby=discount-'.((@$sortto == "asc") ? "desc":"asc") }}" class="sortby {{ (@$sortby == "discount") ? "active ":" " }}" data-value="discount">Discount</a> </li>*/ ?>
        </ul>
    </div>
    <div class="col-7">
        <form id="frmSearch" method="get" class="search_form" action="#">
            {{ csrf_field() }}
            <div class="justify-content-end d-flex align-items-end" style="gap:10px;">
                @if($locations)
                <div class="bg-form-el-cont">
                    <select class="form-control slocation" placeholder="Location" name="slocation" style="border:1px solid #000; min-height:auto;" data-placeholder="Location" data-allow-clear="true">
                        <option value="">Location</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" <?php echo  $location->id == @$_GET["slocation"] ? "selected" : ""; ?>>{!! $location->name !!}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(isset($tags) && $tags->isNotEmpty())
                <div class="bg-form-el-cont">
                    <select class="form-control stags" name="stags" placeholder="Tags" style="border:1px solid #000; min-height:auto;" data-placeholder="Tags" data-allow-clear="true">
                        <option value="">Tags</option>
                        @foreach($tags as $tag)
                        <option value="{{ $tag }}" <?php echo  $tag == @$_GET["stags"] ? "selected" : ""; ?>>{!! $tag !!}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<?php /*
<script type="text/javascript">
$(document).ready(function(){
    var page = 1;
    var ajaxLoading = false;
    var stop = 0;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - $('footer').height()) {
            if (!ajaxLoading && stop == 0) {
                ajaxLoading = true;
                page++;
                loadMoreData(page);
            }
        }
    });


    function loadMoreData(page) {
        $.ajax({
            url: APP_URL + '/deal/loadDataAjax?page=' + page,
            type: "get",
            data: [],
            beforeSend: function () {
                $('.ajax-load').show();
            }
        }).done(function (data) {
            if (data.html == "") {
                //$('.ajax-load').html("No more records found");
                stop = 1;
                return;
            }
            $('.ajax-load').hide();
            $("#experience_data").append(data.html);
            $('.lazy').Lazy();
            ajaxLoading = false;
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });
    }
});
</script
*/ ?>
@endsection