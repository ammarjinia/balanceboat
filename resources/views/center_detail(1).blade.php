@extends('layouts.front')
@section('title', @$center->name)

<!-- Meta Info Start-->
@section('meta_title', (!empty(@$center->meta_title)) ? @$center->meta_title : @$center->name)
<?php if (!empty(@$center->meta_description)) { ?> 
    @section('description', strip_tags(@$center->meta_description))
<?php } ?>
<?php if (!empty(@$center->keywords)) { ?> 
    @section('keywords', strip_tags(@$center->keywords))
<?php } ?>
<?php if (!empty(@$center->banner_image_url)) { ?> 
    @section('image', Storage::disk('s3')->url(rawurlencode(@$center->banner_image_url)))
<?php } ?>
<!-- Meta Info End -->

@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/slider-pro.min.css') }}" rel="stylesheet" />
<style type="text/css">
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
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ Storage::disk('s3')->url(rawurlencode(@$center->banner_image_url)) }}" data-natural-width="1280" data-natural-height="780">
    <div class="parallax-content-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <h1>{{ @$center->name }}</h1>
                    <span>{{ @$center->location }}</span>
                </div>
                <div class="col-md-4 col-sm-4 text-right">
                    @if(sizeof(@$imagegalleries)>0)  
                    <a href="javascript:void(0);" class="btn_1 view_image">View Images</a>
                    <div class="carousel magnific-gallery">
                        @foreach(@$imagegalleries as $gallery)
                        <div class="item hidden">
                            <a href="{{ Storage::disk('s3')->url(rawurlencode($gallery->image_url)) }}">
                                <img src="{{ Storage::disk('s3')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" />
                            </a>
                        </div>
                        @endforeach           
                    </div>
                    @endif             
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End section -->
@endsection
@section('content')
<main>
    <div id="position">
        <div class="container">
            <ul>
                <li><a href="{{ url("/") }}">Home</a></li>
                <li><a href="#">Centers</a></li>
                <li>{{ @$center->name }}</li>
            </ul>
        </div>
    </div>
    <!-- End Position -->

    <div class="collapse" id="collapseMap">
        <div id="map" class="map">test</div>
    </div>
    <!-- End Map -->

    <div class="container margin_30">
        <!---- Start Filters for Quick booking ---->
   @if(sizeof(@$center_experiences) > 0)
   <form id="frmBooking" name="frmBooking" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate">
       {{ csrf_field() }}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
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
            <label class="form-label">Days</label><br/>
            <select id="durations" name="durations" class="form-control">
                <option value=""></option>
            </select>
        </div>
        <div class="col-md-2">
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
            <button class="btn_1 btn-block medium text-center btn-reservation" id="quickReserverBtn" disabled="disabled" style="margin-top:15px;">Reserve Now</button>
        </div>
    </div>
    </form>
    @endif
    <!---- End Filters for Quick booking ---->
    <hr />
    
   @if(@$accomodationimagegalleries)
        <div class="carousel magnific-gallery">
            @if(@$accomodationimagegalleries)
                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                    <div class="item"> <a href="{{ Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}">
                        <img src="{{ Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" alt="Image"></a>
                    </div>
                @endforeach
            @endif 
        </div>
    @endif
    <br />
        
        <div class="row">
            <div class="col-md-8" id="single_tour_desc">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="nomargin_top">{{ @$center->name }}</h2>
                        <p class="text-italic"><i class="icon-location-2"></i>{{ @$center->address_of_center }}</p><br>
                    </div>
                </div>
                <?php
                if (@$center->about_center) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>About</h3>
                            {!! @$center->about_center !!}
                        </div>
                    </div>
                    <hr>
                    <?php
                }?>

                <?php
                if (@$center->center_highlights) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Highlights of {{ @$center->name }}</h3>
                            <ul class="list_ok">
                                <?php
                                foreach (explode(",", @$center->center_highlights) as $center_highlight) {
                                    ?>
                                    <li>{{ $center_highlight }}</li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <hr>
                <?php } ?>

                <?php
                if (@$center->center_features) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Features</h3>
                            <ul class="row list">
                                <?php
                                foreach (explode(",", @$center->center_features) as $center_feature) {
                                    ?>
                                    <li class="col-md-6">{{ $center_feature }}</li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <hr>
                <?php } ?>

                @if ((!empty(@$center->accomodation_banner_image_url)) or (sizeof(@$accomodationimagegalleries) > 0) or (@$center->accomodation_overview))    
                <div class="row">
                    <div class="col-md-12">
                        <h3>Accommodation Overview</h3>
                        @if ((@$center->accomodation_banner_image_url) or (@$accomodationimagegalleries))
                        <div id="Img_carousel" class="slider-pro">
                            <div class="sp-slides">
                                @if(@$center->accomodation_banner_image_url)
                                <div class="sp-slide">
                                    <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('s3')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" />
                                </div>
                                @endif
                                @if(sizeof(@$accomodationimagegalleries)>0)
                                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                <div class="sp-slide">
                                    <img alt="Image" class="sp-image" alt="" data-src="{{ Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" />
                                </div>
                                @endforeach
                                @endif                                                                    
                            </div>
                            <div class="sp-thumbnails">
                                @if(@$center->accomodation_banner_image_url)
                                <img alt="Image" class="sp-thumbnail" alt="" src="{{ Storage::disk('s3')->url(rawurlencode(@$center->accomodation_banner_image_url)) }}" />                                    
                                @endif
                                @if(sizeof(@$accomodationimagegalleries)>0)
                                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                <img alt="Image" class="sp-thumbnail" alt="" data-src="{{ Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)) }}" />
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @endif
                        <br />
                        {!! @$center->accomodation_overview !!}                            
                    </div>
                </div>
                <hr>
                @endif

                <?php if (@$center->how_to_get_there) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>How to get there</h3>
                            {!! @$center->how_to_get_there !!}
                        </div>
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
                <?php } ?>
                
                <?php if (@$center->what_sets_us_apart) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>What sets us apart</h3>
                            {!! @$center->what_sets_us_apart !!}
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                
                <?php if (@$center->our_philosophy) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Our Philosophy</h3>
                            {!! @$center->our_philosophy !!}
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                
                <?php if (@$center->our_mission) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Our Mission</h3>
                            {!! @$center->our_mission !!}
                        </div>
                    </div>
                    <hr>
                <?php } ?>
            </div>
            <!--End  single_tour_desc-->

            <aside class="col-md-4">
                <?php if (@$center->video_url) {
                        $vidUrl = @$center->video_url;
                        if (strstr(@$center->video_url,"https://youtu.be/")) {
                            $vidUrl = "https://www.youtube.com/embed/".str_replace("https://youtu.be/","",@$center->video_url);
                        } else {
                            $vidUrl = str_replace("watch?v=","embed/",@$center->video_url);
                        }?>
                        <div class="box_style_1 expose">
                            <div class="row">
                                <div class="col-md-12 lststl_1">
                                    <iframe width="100%" height="220px" src="{{ @$vidUrl }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: relative;"></iframe>
                                </div>
                            </div>
                        </div>    
                <?php } ?>

                <?php if (sizeof(@$center_specialities) > 0) { ?>
                    <div class="box_style_1 expose">
                        <h3 class="inner">- Center Speciality -</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="list_ok">
                                    <?php
                                    foreach (@$center_specialities as $speciality) {
                                        ?>
                                        <li>{{ @$speciality->name }}</li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!--/box_style_1 -->

                <div class="box_style_1 expose">
                    <!--h3 class="inner">- Center Overviews -</h3-->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (@$center->year_of_foundation) {
                                ?>
                                Founded in {{ @$center->year_of_foundation }}
                                <?php
                            }
                            ?>
                            <?php if (@$center->certificate_id) { ?>
                                <h4>Certiﬁcations</h4>
                                <?php if (sizeof(@$certificates) > 0) { ?>
                                    <div class="row">
                                        <?php
                                        foreach (@$certificates as $certificate) {
                                            if (in_array($certificate->id, explode("||", $center->certificate_id))) {
                                                ?>
                                                <div class="col-md-4">
                                                    <img src="{{ Storage::disk('s3')->url(rawurlencode(@$certificate->image_url)) }}" alt="{{ $certificate->name }}" title="{{ $certificate->name }}" class="img-responsive" />
                                                </div>
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
                                                $src = Storage::disk('s3')->url(rawurlencode($teacher->profile_image_url));
                                            }
                                            ?>
                                            <img src="{{ $src }}" alt="" title="{{ $teacher->name }}" class="img-responsive img-circle"  style="width: 80px; height: 80px;" />
                                            <h4>
                                                {{ @$teacher->name }}
                                                <br><br>
                                                <?php
                                                if (@$teacher->certificate_id) {
                                                    if (sizeof(@$certificates) > 0) {
                                                        ?>
                                                        <small>
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
                                                }
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

        @if(sizeof(@$center_experiences) > 0)
        <section>
            <div class="container">
                <div class="main_title">
                    <h2>Experiences Offered</h2>
                    <p><i>{{ $center->name }} offers the following</i> </p>
                </div>
                <div class="row add_bottom_45">
                    @foreach (@$center_experiences as $experience) 
                    @include('content-experience', (array)$experience)
                    @endforeach
                </div>
            </div>
            <!-- End container -->
        </section>
        <!-- End section -->
        @endif

    </div>
    <!--End container -->
    <div id="overlay"></div>
    <!-- Mask on input focus -->
</main>
<!-- End main -->
<?php /*
<div class="section cc-contactpop fixed">
    <h3>Contact Centre</h3>  
    <div class="renseignement">
        <i class="fa fa-times icon_close"></i>
    </div>
    <form class="add-comment custom-form contact-form" id="frmFooterInquiry" action="#" method="post" novalidate="novalidate">
        <input type="hidden" name="center_id" id="center_id" value="{{ @$center->id }}">
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
<!-- Carousel --> 
<script src="{{ asset('public/basicfront/js/owl.carousel.min.js') }}"></script> 
<script src="{{ asset('public/basicfront/js/jquery.sliderPro.min.js') }}"></script>
<script src="{{ asset('public/basicfront/js/center-detail.js?v=6.3') }}"></script>
<script type="text/javascript">
$(document).ready(function ($) {
    $('#Img_carousel').sliderPro({
        width: 960,
        height: 500,
        fade: true,
        arrows: true,
        buttons: false,
        fullScreen: true,
        smallSize: 500,
        startSlide: 0,
        mediumSize: 1000,
        largeSize: 3000,
        thumbnailArrows: true,
        autoplay: false
    });
});
</script>
@endsection