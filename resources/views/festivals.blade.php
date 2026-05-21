@extends('layouts.front')
@section('title', @$experience->name)

<!-- Meta Info Start-->
@section('meta_title', @$experience->name)
<?php if (!empty(@$experience->experience_overview)) { ?> 
    @section('description', strip_tags(@$experience->experience_overview))
<?php } ?>
<?php if (!empty(@$experience->keywords)) { ?> 
    @section('keywords', strip_tags(@$experience->keywords))
<?php } ?>
<?php if (!empty(@$experience->banner_image_url)) { ?> 
    @section('image', Storage::disk('azure')->url(@$experience->banner_image_url))
<?php } ?>
<!-- Meta Info End -->

@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/slider-pro.min.css') }}" rel="stylesheet" />
@endsection    
@section('banner')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ url("/bali-spirit-festival/img/festivals-banner.jpg") }}" data-natural-width="1280" data-natural-height="780">
    <div class="parallax-content-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <h1>International Festivals Partners</h1>
                </div>
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
            <li>International Festivals</li>
        </ul>
    </div>
</div>
<!-- End Position -->

<div class="container margin_30">
    <div class="main_title">
        <h2>Check out these world renowned festivals in 2019</h2>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="tour_container">
                <div class="img_container">
                    <a href="{{ url("bali-spirit-festival")}}">
                        <img src="{{ url("/bali-spirit-festival/img/Bali-Spirit-Festival-Banner.jpg") }}" class="img-fluid" />
                    </a>
                </div>
                <div class="tour_title">
                    <h3><strong>Bali Spirit Festival</strong></h3>
                    <p>24th - 31st March 2019</p>
                </div>
            </div>
            <!-- End box tour -->
        </div>
        <!-- End col -->

        <div class="col-lg-6 col-md-6">
            <div class="tour_container">
                <div class="img_container">
                    <a href="{{ url("international-yoga-festival")}}">
                        <img src="{{ url("/bali-spirit-festival/img/international-yoga-festival-banner.jpg") }}" class="img-fluid" />
                    </a>
                </div>
                <div class="tour_title">
                    <h3><strong>International Yoga Festival</strong></h3>
                    <p>1st - 7th March 2019</p>
                </div>
            </div>
            <!-- End box tour -->
        </div>
        <!-- End col -->
        
        <div class="col-lg-6 col-md-6">
            <div class="tour_container">
                <div class="img_container">
                    <a href="{{ url("lamu-yoga-festival")}}">
                        <img src="{{ url("/bali-spirit-festival/img/lamu-yoga-festival-banner.jpg") }}" class="img-fluid" />
                    </a>
                </div>
                <div class="tour_title">
                    <h3><strong>Lamu Yoga Festival</strong></h3>
                    <p>18th - 22nd March 2019</p>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->

</div>
<!-- End main -->

<!-- Modal Review -->
@if(count((array)@$experience_accomodations))
@foreach(@$experience_accomodations as $experience_accomodation)
<div class="modal fade" id="mdlAccommodation{{$experience_accomodation->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    {{ @$experience_accomodation->name }}
                    <span class="price_list price_list-min">
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
                            <del class="text-default">
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }}
                            </del>
                            <?php
                        }
                        ?>
                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }}
                    </span>
                </h4>
            </div>
            <div class="modal-body">                
                {!! html_entity_decode(@$experience_accomodation->description) !!}
                @if(@$accomodationimagegalleries)
                <div class="carousel magnific-gallery">
                    @if(@$accomodationimagegalleries)
                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                    @if ($accomodationimagegallery->accomodation_id == $experience_accomodation->id)
                    <div class="item">
                        <a href="{{ Storage::disk('azure')->url(@$accomodationimagegallery->image_url) }}"><img src="{{ Storage::disk('azure')->url(@$accomodationimagegallery->image_url) }}" alt="Image"></a>
                    </div>
                    @endif
                    @endforeach
                    @endif                    
                </div>                    
                @endif                 
            </div>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myReviewLabel">Write your review</h4>
            </div>
            <div class="modal-body">
                <div id="message-review">
                </div>
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

@endsection
@section('footer')
<script src="{{ asset('public/basicfront/js/experience-detail.js') }}"></script>
<script src="{{ asset('public/basicfront/js/jquery.sliderPro.min.js') }}"></script>
<!-- Carousel -->
<script src="{{ asset('public/basicfront/js/owl.carousel.min.js') }}"></script>
<script>
$(document).ready(function () {
    $(".carousel").owlCarousel({
        items: 4,
        autoplay: true
                //itemsDesktop: [1199, 3],
                //itemsDesktopSmall: [979, 3]
    });
});
</script>
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.slider-pro').sliderPro({
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