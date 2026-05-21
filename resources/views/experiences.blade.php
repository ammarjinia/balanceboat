@extends('layouts.front')
@section('title', 'Experiences')
@section('head')
<!-- Radio and check inputs -->
<link href="{{ url("/public/basicfront/css/skins/square/grey.css") }}" rel="stylesheet">

<!-- Range slider -->
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.skinFlat.css") }}" rel="stylesheet">
@endsection
@section('banner')
<section class="home" id="hero" style="height: 300px !important;">
    <div class="intro_title">
        <h3 class="animated fadeInDown">EXPERIENCE</h3>
        <p class="animated fadeInDown">The best of wellness, spirituality &amp; holistic health around the world </p>
    </div>
    <!-- /search_bar-->
</section>
<!-- End hero -->
@endsection

@section('content')
<div class="container">
    <div class="margin_30"></div>
    <form id="frmExperience" action="" method="GET">
        <div class="row">
            <aside class="col-lg-3 col-md-3">
                <div id="filters_col">
                    <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt"><i class="icon_set_1_icon-65"></i>Filters <i class="icon-plus-1 pull-right"></i></a>
                    <div class="collapse" id="collapseFilters">
                        <div class="text-right"><a href="{{ url("/experiences") }}" class="label label-default search-clear">Clear</a></div>
                        <div class="filter_type">
                            <h6>Search</h6>
                            <input type="text" class="form-control" id="ssearch" name="search" value="{!! @$ssearch !!}" />
                        </div>
                        <?php if (!empty($max_experience_price) && ($max_experience_price != $min_experience_price)) { ?>
                            <div class="filter_type">
                                <h6>Price</h6>
                                <input type="text" id="price_range" name="price_range" data-type="double" data-min="{{ \App\Http\Helpers\CommonHelper::get_currency_rate($min_experience_price, \App\Http\Helpers\CommonHelper::get_site_currency(), false) }}" data-max="{{ \App\Http\Helpers\CommonHelper::get_currency_rate($max_experience_price, \App\Http\Helpers\CommonHelper::get_site_currency(), false) }}" data-prefix="{{ \App\Http\Helpers\CommonHelper::get_currency_symbol() }}" data-grid="false" value="" />
                            </div>
                        <?php } ?>
                        <?php /*<div class="filter_type">
                            <h6>Popular</h6>
                            <ul class="slim-scroll">
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="featured_experiences" {{ in_array("featured_experiences", @$popular) ? "checked" : "" }}>Featured Experiences</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="luxury_retreats" {{ in_array("luxury_retreats", @$popular) ? "checked" : "" }}>Luxury Retreats</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="budget_retreats" {{ in_array("budget_retreats", @$popular) ? "checked" : "" }}>Budget Retreats</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="best_off_season_deals" {{ in_array("best_off_season_deals", @$popular) ? "checked" : "" }}>Best Off-Season Deals</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="deals_of_the_month" {{ in_array("deals_of_the_month", @$popular) ? "checked" : "" }}>Deals Of The Month</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="advance_trainings" {{ in_array("advance_trainings", @$popular) ? "checked" : "" }}>Advance Trainings</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="weekend_retreats" {{ in_array("weekend_retreats", @$popular) ? "checked" : "" }}>Weekend Retreats</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="couple_retreats" {{ in_array("couple_retreats", @$popular) ? "checked" : "" }}>Couple Retreats</label>
                                </li>
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="blessed_by_the_sea" {{ in_array("blessed_by_the_sea", @$popular) ? "checked" : "" }}>Blessed by the Sea</label>
                                </li>                                
                                <li>
                                    <label><input type="checkbox" id="popular[]" name="popular[]" value="retreats_by_the_mountains" {{ in_array("retreats_by_the_mountains", @$popular) ? "checked" : "" }}>Retreats by the Mountains</label>
                                </li>
                            </ul>
                        </div>*/?>
                        <?php
                        $objPopularCategories = \App\Http\Helpers\CommonHelper::get_popular_categories();
                        if ($objPopularCategories) {
                            ?>
                            <div class="filter_type">
                                <h6>Popular</h6>
                                <ul class="slim-scroll">
                                    <?php foreach ($objPopularCategories as $objCategory) { ?>
                                        <li>
                                            <label><input type="radio" id="scategory" name="scategory" value="{{ $objCategory->id }}" {{ ($objCategory->id == @$scategory) ? "checked" : "" }}>{{ $objCategory->name }}</label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <?php
                        $objCategories = \App\Http\Helpers\CommonHelper::get_site_categories();
                        if ($objCategories) {
                            ?>
                            <div class="filter_type">
                                <h6>Category</h6>
                                <ul class="slim-scroll">
                                    <li>
                                        <label><input type="radio" id="scategory" name="scategory" value="" checked>All</label>
                                    </li>
                                    <?php foreach ($objCategories as $objCategory) { ?>
                                        <li>
                                            <label><input type="radio" id="scategory" name="scategory" value="{{ $objCategory->id }}" {{ ($objCategory->id == @$scategory) ? "checked" : "" }}>{{ $objCategory->name }}</label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <?php
                        $objDests = \App\Http\Helpers\CommonHelper::get_site_destinations();
                        if ($objDests) {
                            ?>
                            <div class="filter_type">
                                <h6>Location</h6>
                                <ul class="slim-scroll">
                                    <li>
                                        <label><input type="radio" id="sdestination" name="sdestination" value="" checked="">All</label>
                                    </li>
                                    <?php foreach ($objDests as $objDest) { ?>
                                        <li>
                                            <label><input type="radio" id="sdestination" name="sdestination" value="{{ $objDest->id }}" {{ ($objDest->id == @$sdestination) ? "checked" : "" }}>{{ $objDest->name }}</label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <?php
                        if ($tags && $tags->isNotEmpty()) {
                        ?>
                            <div class="filter_type">
                                <h6>Tags</h6>
                                <ul class="slim-scroll">
                                    <?php foreach ($tags as $tag) { ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" id="stags" name="stags[]" value="<?php echo e($tag); ?>" 
                                                    <?php echo in_array(trim($tag), (array)@$stags) ? "checked" : ""; ?>>
                                                <?php echo e($tag); ?>
                                            </label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <!--End filters col-->
            </aside>
            <!--End aside -->

            <div class="col-lg-9 col-md-9">
                <div id="tools">
                    <div class="row">
                        <div class="col-md-8 col-sm-8 col-xs-8"></div>
                        <div class="col-md-4 col-sm-4 hidden-xs text-right">
                            <div class="styled-select-filters">
                                <select name="sort_by" id="sort_by" class='form-control'>
                                    <option value="newest" {{ (@$sort_by == "newest") ? "selected":"" }}>Newest First</option>
                                    <option value="ranking" {{ (@$sort_by == "ranking") ? "selected":"" }}>Ranking</option>
                                    <option value="price_asc" {{ (@$sort_by == "price_asc") ? "selected":"" }}>Price - Low to High</option>
                                    <option value="price_desc" {{ (@$sort_by == "price_desc") ? "selected":"" }}>Price - High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/tools -->

                <div id="experience_data">
                    <?php /*@foreach (@$experiences as $experience) 
                    @include('content-experience', (array)$experience)
                    @endforeach*/?>
                </div>
                <p class="text-center add_bottom_10 ajax-load text-center" style="display:none">
                    <!-- a href="#" class="button_intro btn-load-more">Load More </a -->
                </p>
                <!--End strip -->
            </div>
            <!-- End col lg-9 -->
        </div>
    </form>
    <!-- End row -->
</div>
<!-- End container -->
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
                <form class="add-comment custom-form contact-form" id="frmInquiry" method="post" novalidate="novalidate">
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
                    <div class="form-group" style="position:relative;">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>
                    <input type="submit" value="Send Inquiry" class="btn_1" id="submit_enquiry" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')

<!-- Check and radio inputs -->
<script type="text/javascript" src="{{ url('/public/basicfront/js/icheck.min.js') }}" defer></script>
<script type="text/javascript" src="{{ url('/public/basicfront/js/jquery.slimscroll.min.js') }}" defer></script>
<script type="text/javascript" src="{{ url('/public/basicfront/js/experience.js?v=1.31') }}" defer></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-grey',
            radioClass: 'iradio_square-grey'
        });
    });
</script>
 {!! NoCaptcha::renderJs() !!}
@endsection