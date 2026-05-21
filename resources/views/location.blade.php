@extends('layouts.front')
@section('title', @$destination->name)
@section('meta_title', @$destination->name)
<?php if (!empty(@$destination->description)) { ?> 
    @section('description', @$destination->description)
<?php } ?>
<?php if (!empty(@$destination->keywords)) { ?> 
    @section('keywords', @$destination->keywords)
<?php } ?>
<?php if (!empty(@$destination->banner_image_url)) { ?> 
    @section('image', Storage::disk('azure')->url(@$destination->banner_image_url))
<?php } ?>

@section('head')
<link href="{{ url("/public/basicfront/css/skins/square/grey.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.skinFlat.css") }}" rel="stylesheet">
@endsection
<?php
$qs = "";
if (!empty(request()->getQueryString())) {
    $qs = "?" . request()->getQueryString();
}
?>
@section('banner')
<section id="hero" style="<?php if (!empty(@$destination->banner_image_url)) { ?> background-image: url('{{ Storage::disk('azure')->url(@$destination->banner_image_url) }}') !important; <?php } ?>">
    <div class="intro_title">
        <h3 class="animated fadeInDown">{{ @$destination->name }}</h3>
        <p class="animated fadeInDown"></p>
    </div>
    <?php /*@include('layouts.searchbar')*/?>
    <!-- /search_bar-->
</section>
@endsection
@section('content')

@if(count((array)$categories) > 0 && !empty(@$categories))
<section class="container padding_t_30 margin_b_30">
    <div class="main_title">
        <h2>Experiences Categories</h2>
    </div>
    <div class="row">
        <?php $i = 1; ?>  
        @foreach ($categories as $category)   
        @if($category->image_url)
        <div class="col-md-4 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
            <div class="tour_container category_hover">
                <div class="img_container">
                    <a href="{{ url()->current()."?category=".$category->slug.$qs }}">
                        @if($category->image_url)
                        <img src="{{ Storage::disk('azure')->url($category->image_url) }}" class="img-responsive" style="height:170px" alt="{{ $category->image_title }}">
                        @endif
                        <div class="short_info"> {{ $category->name }} </div>
                    </a>
                </div>
            </div>
            <!-- End box tour -->
        </div>
        <!-- End col-md-4 -->
        <?php $i++; ?>
        @endif
        @endforeach
    </div>
    <!-- End row -->
</section>
<!-- End section -->
@endif
<section class="white_bg">
    <div class="container margin_60">
        <div class="main_title">
            <h2>Popular Experiences</h2>
            <p><i>Checkout some of the most popular experiences on BalanceBoat</i></p>
        </div>
        <form id="frmExperience" action="" method="GET">
        <div class="row">
            <aside class="col-lg-3 col-md-3">
                <div id="filters_col">
                    <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt"><i class="icon_set_1_icon-65"></i>Filters <i class="icon-plus-1 pull-right"></i></a>
                    <div class="collapse" id="collapseFilters">
                        <div class="text-right"><a href="{{ url("/location/".$parent.(($subdest) ? "/".$subdest : "")) }}" class="label label-default search-clear">Clear</a></div>
                        <div class="filter_type">
                            <h6>Search</h6>
                            <input type="text" class="form-control" id="ssearch" name="search" value="{!! @$ssearch !!}" />
                        </div>
                        <?php if (!empty($max_experience_price) && ($max_experience_price != $min_experience_price)) { ?>
                            <div class="filter_type">
                                <h6>Avg Price Per Day</h6>
                                <input type="text" id="price_range" name="price_range" data-type="double" data-min="{{ \App\Http\Helpers\CommonHelper::get_currency_rate($min_experience_price, \App\Http\Helpers\CommonHelper::get_site_currency(), false) }}" data-max="{{ \App\Http\Helpers\CommonHelper::get_currency_rate($max_experience_price, \App\Http\Helpers\CommonHelper::get_site_currency(), false) }}" data-prefix="{{ \App\Http\Helpers\CommonHelper::get_currency_symbol() }}" data-grid="false" value="" />
                            </div>
                        <?php } ?>
                        <?php /*<div class="filter_type">
                            <h6>Popular</h6>
                            <ul class="slim-scroll">*/?>
                                
                                <?php 
                                /*$objPopCategories = \App\Http\Helpers\CommonHelper::get_popular_categories();
                                foreach ($objPopCategories as $objCategory) { ?>
                                    <li>
                                        <label><input type="radio" id="scategory" name="scategory" value="{{ $objCategory->id }}" {{ ($objCategory->id == @$scategory) ? "checked" : "" }}>{{ $objCategory->name }}</label>
                                    </li>
                                <?php } ?>
                                <?php*/?>
                                <?php /*<li>
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
                            <div class="collapse" id="collapseFilters">
                                <div class="filter_type">
                                    <h6>Category</h6>
                                    <ul class="slim-scroll">
                                        <?php foreach ($objCategories as $objCategory) { ?>
                                            <li>
                                                <label><input type="radio" id="scategory" name="scategory" value="{{ $objCategory->id }}" {{ ($objCategory->id == @$scategory) ? "checked" : "" }}>{{ $objCategory->name }}</label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <!--End collapse -->
                        <?php } ?>
                        <?php
                        //$objDests = \App\Http\Helpers\CommonHelper::get_site_destinations();
                        //if ($objDests) {
                        if ($subdestinations) {
                            ?>
                            <div class="collapse" id="collapseFilters">
                                <div class="filter_type">
                                    <h6>Location</h6>
                                    <ul class="slim-scroll">
                                        <li>
                                            <label><input type="radio" id="sdestination" name="sdestination" value="" checked="">All</label>
                                        </li>
                                        <?php foreach ($subdestinations as $objDest) { ?>
                                            <li>
                                                <label><input type="radio" id="sdestination" name="sdestination" value="{{ $objDest->id }}" {{ ($objDest->id == @$sdestination) ? "checked" : "" }}>{{ $objDest->name }}</label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <!--End collapse -->
                        <?php } ?>
                    </div>
                    <!--End filters col-->
            </aside>
            <!--End aside -->

            <div class="col-lg-9 col-md-9">
                <div id="tools">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6"></div>
                        <div class="col-md-3 col-sm-3 hidden-xs text-right">
                            <div class="styled-select-filters">
                                    <select class="form-select global_site_currency" name="global_site_currency" id="global_inline_site_currency">
                                        @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                        <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                                        @endforeach                                    
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 hidden-xs text-right">
                            <div class="styled-select-filters">
                                <select name="sort_by" id="sort_by" class='form-control'>
                                    <option value="newest" {{ (@$sort_by == "newest") ? "selected":"" }}>Newest First</option>
                                    <option value="ranking" {{ (@$sort_by == "ranking") ? "selected":"" }}>Popularity</option>
                                    <option value="price_asc" {{ (@$sort_by == "price_asc") ? "selected":"" }}>Daily Price - Low to High</option>
                                    <option value="price_desc" {{ (@$sort_by == "price_desc") ? "selected":"" }}>Daily Price - High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/tools -->

                <div id="experience_data">
                {{-- Loop thru experiences --}}                            
                @foreach (@$experiences as $experience) 
                @include('content-experience', (array)$experience)
                @endforeach
                @if(count((array)@$experiences) > 100)
                <p class="text-right add_bottom_10">
                    <a href="{{ url("experiences")}}" class="button_intro">VIEW ALL EXPERIENCES >> </a>
                </p>
                @endif
                <!--End strip -->
            </div>
                <p class="text-center add_bottom_10 ajax-load text-center" style="display:none">
                    <!-- a href="#" class="button_intro btn-load-more">Load More </a -->
                </p>
                <!--End strip -->
            </div>
            <!-- End col lg-9 -->
        </div>
    </form>
        @if(count((array)$subdestinations) > 1 && !empty($subdestinations))
        <div class="row">
            <div class="col-md-12">
                <div class="main_title">
                    <h2>{{ $destination->name }} Popular <span>Destinations</span></h2>
                    <p>{{ $destination->description }}</p>
                </div>
            </div>  
            @foreach ($subdestinations as $val_catesDestinations_top)
            <div class="col-md-3 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
                <div class="tour_container tour_container1">
                    <div class="img_container">
                        <a href = "{{ url("/location/".$destination->slug."/".$val_catesDestinations_top->slug).$qs }}">
                            @if($val_catesDestinations_top->image_url)
                            <img src = "{{ Storage::disk('azure')->url($val_catesDestinations_top->image_url) }}" class = "img-responsive" alt = "{{ $val_catesDestinations_top->image_title }}" style = "max-height:173px;" />
                            @endif
                        </a>
                    </div>
                </div>
                <!--End box tour -->
                <h4 class = "text-center"><a href = "{{ url("/location/".$destination->slug."/".$val_catesDestinations_top->slug) }}">{{ $val_catesDestinations_top->name
                        }}</a></h4>
            </div>
            <!--End col-md-4 -->
            @endforeach
        </div>
        @endif
        <!--End row -->
    </div>
    <!--End container -->
</section>
<!--End section -->
<form id="frmBooking" name="frmBooking" action="" method="POST">
<input type="hidden" id="hdn_experience_id" name="hdn_experience_id" value="" />
{{ csrf_field() }}
</form>
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
                    <input name="tour_name" id="tour_name" type="hidden" value="Paris Arch de Triomphe Tour">
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
                    <input type="submit" value="Popularity" class="btn_1" id="submit_enquiry" />
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal review --> 
@endsection
@section('footer')

<!-- Check and radio inputs -->
<script src="{{ url("/public/basicfront/js/icheck.min.js") }}"></script>
<script src="{{ url("/public/basicfront/js/jquery.slimscroll.min.js") }}"></script>
<script>
$('input').iCheck({
    checkboxClass: 'icheckbox_square-grey',
    radioClass: 'iradio_square-grey'
});
</script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on("change", "#sort_by", function () {
        $("#frmExperience").submit();
    });
    

    $(document).on("blur", "#frmExperience input[name^=search]", function () {
        $("#frmExperience").submit();
    });

    $(document).on("keydown", "#frmExperience input[name^=search]", function (e) {
        if (e.type === "keydown" && e.keyCode === 13) {
            $("#frmExperience").submit();
        }
    });

    $(document).on("ifChanged", "#frmExperience input[name^=popular]", function () {
        $("#frmExperience").submit();
    });

    $(document).on("ifChecked", "#frmExperience input[name^=scategory], #frmExperience input[name^=sdestination]", function () {
        $("#frmExperience").submit();
    });

    $(".slim-scroll").slimScroll({
        height: '220px'
    });


    var price_range = $("#price_range");
    price_range.ionRangeSlider({
        onFinish:function() {
            $("#frmExperience").submit();
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on("click", "a[id^=btnListReserve]", function () {
        bkfrmAction = APP_URL + '/reservation';
        if($(this).attr("data-exp-name") == "AyurYoga Eco-Ashram Mysore India") {
            bkfrmAction = APP_URL + '/redirect-to-portal';
        }
        $("#frmBooking").attr("action", bkfrmAction);
        $("#frmBooking #hdn_experience_id").val($(this).attr("data-exp-id"));
        $("#frmBooking").submit();
        
    });
    $(document).on("click", "a.btn-send-inquiry", function () {
        $("#frmInquiry #experience_id").val($(this).attr("data-exp-id"));
    });
    $("#frmInquiry").validate()
    $("#frmInquiry").on("submit", function () {
        if ($("#frmInquiry").valid()) {
            experienceId = $("#frmInquiry #experience_id").val();
            $("#submit_enquiry").attr("disabled", true);
            $.ajax({
                url: APP_URL + "/store-inquiry",
                method: "POST",
                data: $("#frmInquiry").serialize() + "&experienceId=" + experienceId,
                success: function (result) {
                    if (result) {
                        $("#submit_enquiry").after(' <div class="alert alert-danger">' + result + '</div>');
                    } else {
                        $("#frmInquiry")[0].reset();
                        $("#submit_enquiry").after('<div class="alert alert-success">Your Inquiry has been saved successfully!</div>');
                    }
                    $("#submit_enquiry").attr("disabled", false);
                }
            });
        }
        return false;
    });
    
    var page = 1;
    var ajaxLoading = false;
    var stop = 0;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= ($(document).height()-200) - $(window).height() - $('footer').height()) {
            if (!ajaxLoading && stop == 0) {
                ajaxLoading = true;
                page++;
                loadMoreData(page);
            }
        }
    });

    /*$(document).on("click", ".btn-load-more", function () {
     page++;
     loadMoreData(page);
     });*/

    function loadMoreData(page) {
        $.ajax({
            url: '<?php echo URL::current();?>?page=' + page,
            type: "get",
            data: $("#frmExperience").serialize(),
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
</script>
@endsection