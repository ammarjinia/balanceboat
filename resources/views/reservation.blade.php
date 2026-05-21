@extends('layouts.front')
@section('title', @$experience->name)
@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
@endsection
@section('content')

<!--div id="position">
    <div class="container">
        <ul>
            <li><a href="{{ url("/") }}">Home</a></li>
            <li><a href="{{ url("/experiences") }}">Experiences</a></li>
            <li>{{ @$experience->name }}</li>
        </ul>
    </div>
</div-->
<!-- End Position -->

<div class="collapse" id="collapseMap">
    <div id="map" class="map">test</div>
</div>
<!-- End Map -->

<div class="container margin_80">
    <div class="row">
        <div class="col-md-12">
            <h2>Reservation Details </h2>
        </div>
    </div>
    <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="img_list">
                            <a href="{{ url("/experience/".$experience->slug) }}">
                                <img src="{!! Storage::disk('azure')->url($experience->banner_image_url) !!}" alt="{{ $experience->banner_image_title }}" class="img-responsive"> 
                            </a>
                        </div>
                    </div>
                    <div class="clearfix visible-xs-block"></div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="tour_list_desc">
                            <a href="{{ url("/experience/".$experience->slug) }}"><h3>{{ $experience->name }}</h3></a>
                            <?php if (@$experience->experience_summary) { ?>
                            <div style="height:120px;overflow:hidden;">
                                {!! @$experience->experience_summary !!}
                            </div>    
                            <?php }
                            
                            $amenities = \App\Experiences::amenities($experience->center_id);?>
                            @if($amenities)
                                <div style="height: 18px;overflow: hidden;margin-top: 5px;margin-bottom: 5px;">
                                <?php
                                    foreach (@$amenities as $amenity) {
                                        if ($amenity->image_url && $amenity->name != "Bath Tub") {?>
                                            <img src="{{ "https://balanceboatblob.blob.core.windows.net/balancegurus/".rawurlencode(@$amenity->image_url) }}" alt=" {{ $amenity->name }}" title=" {{ $amenity->name }}" class="" width="18px" />&nbsp;
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>    
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <div class="price_list">
                            <div>
                                @if(Session('experience_info')['exp_booking_date'])
                                <?php
                                $arexp_booking_date = explode(" - ", Session('experience_info')['exp_booking_date']);
                                $exp_booking_start = @$arexp_booking_date[0];
                                $exp_booking_end = @$arexp_booking_date[1];
                                ?>
                                <small>Start Date:</small>
                                <p class="text-days">
                                    <i class="fa fa-calender"></i> 
                                    {{ (@$exp_booking_start) ? \Carbon\Carbon::parse(trim(@$exp_booking_start))->format("M d, Y") : "" }}
                                    {{ (@$exp_booking_end) ? " - ".\Carbon\Carbon::parse(trim(@$exp_booking_end))->format("M d, Y") : "" }}
                                </p>
                                @endif
                                <?php
                                if (!empty(@$exp_booking_durations)) {
                                    ?>
                                    <small>For:</small>
                                    <span class="text-days">
                                        <?php
                                        echo (@$exp_booking_durations) ? @$exp_booking_durations." Days" : "";
                                        ?>
                                    </span>
                                    <?php
                                }
                                ?>
                                <small>Booking Amount:</small>
                                <span class="text-success">
                                    <?php /*{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) }}*/?>
                                    {!! @$booking_amount_html_price !!}
                                </span>
                                <small>Price:</small>
                                <span>
                                    <?php
                                    if (@$discount > 0) {
                                        ?>
                                        <?php
                                    }
                                    ?>
                                    <?php /*{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$accomodation_price, @$experience->currency) }}*/?>
                                    {!! @$accomodation_html_price !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        @if(@$booking_amount > 0)
        @if(@$booking_amount != @$accomodation_price)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h5><i class="icon-certificate"></i> Only pay <strong>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) }}</strong> now to reserve your spot, Rest pay once you arrive.</h5>
            </div>
        </div>
    </div>
    @endif
    @endif

    <div class="row">
        <!--End  single_tour_desc-->
        <aside class="col-md-4">
            

            <div class="box_style_1 expose">
                <h3 class="inner">Accommodation Details</h3>
                <div class="row">
                    <div class="col-md-12">
                        @if (@$experience_accomodation)
                        <div class="form-group">
                            <label>
                                <a href="#mdlAccommodation{{ @$experience_accomodation->id }}"  data-toggle="modal"  class="text-info">{{ @$experience_accomodation->name }}</a>
                            </label>
                            <div class="pull-right" id="accomodation_price_{{ @$experience_accomodation->id }}">
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
                            </div>
                            @if(@$experience_accomodation->banner_image_url)
                            <div class="text-center">    
                                <a href="#mdlAccommodation{{ @$experience_accomodation->id }}"  data-toggle="modal"  class="text-info">
                                    <img src="{{ Storage::disk('azure')->url(@$experience_accomodation->banner_image_url) }}" alt="" class="img-responsive img-thumbnail" />
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            @if(@$experience_accomodation->description)
                            {!! html_entity_decode(strip_tags(@$experience_accomodation->description)) !!}
                            @endif
                        </div>
                        @endif
                        <table class="table table_summary">
                            <tbody>
                                <tr class="total">
                                    <th>Pay:</th>
                                    <td class="text-right">
                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience_accomodation->currency) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/box_style_1 -->
            <?php if (@$experience->what_is_included) { ?>
                <div class="box_style_1 expose">
                    <h3 class="inner">What is Included</h3>
                    <div class="row">
                        <div class="col-md-12">
                            {!! html_entity_decode(@$experience->what_is_included) !!}
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (@$experience->what_is_not_included) { ?>
                <div class="box_style_1 expose">
                    <h3 class="inner">What is not Included</h3>
                    <div class="row">
                        <div class="col-md-12">
                            {!! html_entity_decode(@$experience->what_is_not_included) !!}
                        </div>
                    </div>
                </div>            
            <?php } ?>

            <div class="box_style_1 expose">
                <h3 class="inner">Refund Details</h3>
                <ul class="list">
                    <?php
                    $refCond = "N/A";
                    if (!empty(@$experience->cancellation_policy_condition)) {
                        switch (@$experience->cancellation_policy_condition) {
                            case "1" :
                                // If Non Refundable
                                $refCond = "Non Refundable";
                                break;
                            case "2" :
                                // If Always refundable
                                $refCond = "Always refundable";
                                break;
                            case "3" :
                                // If Refundable before specified number of days before arrival date
                                if (@$experience->cancellation_policy_days) {
                                    $refCond = "Refundable before " . @$experience->cancellation_policy_days . " days before arrival date";
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    if ($refCond) {
                        ?>
                        <li><?php echo $refCond; ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </aside>
        <div class="col-md-8" id="single_tour_desc" class="single_tour_desc1">
            <form id="frmReservation" name="frmReservation" method="post" action="{{ url('/reservation/store') }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <p class="text-right">Almost done! Just fill in the required info</p>
                        <!--<div class="alert alert-success">-->
                        <!--    <small>This organizer normally answers within a day</small>-->
                        <!--    <button type="button" class="close" data-dismiss="alert" aria-label="Close">-->
                        <!--        <span aria-hidden="true">&times;</span>-->
                        <!--    </button>-->
                        <!--</div>-->
                        <!--<div class="alert alert-success">-->
                        <!--    <small>Prices and availability are indicative. The organizer will inform you of the exact prices and actual availability.</small>-->
                        <!--    <button type="button" class="close" data-dismiss="alert" aria-label="Close">-->
                        <!--        <span aria-hidden="true">&times;</span>-->
                        <!--    </button>-->
                        <!--</div>-->
                        <h3>Enter your details</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address<span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control required email" required="" value="{!! old('email', @$user_info->email) !!}" />
                                    <small>Conﬁrmation email sent to this address</small>
                                </div>
                            </div>
                            <?php
                            if (!Auth::check()) {
                                $pwddisplay = "none";
                                if ($errors->first('password')) {
                                    $pwddisplay = "";
                                }
                                ?>
                                <div id="dvRsvPass" class="col-md-6" style="display: {{ @$pwddisplay }};">
                                    <div class="form-group">
                                        <label>Password<span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password" class="form-control required" required="" />
                                        <small>You already have an account with us. Sign in or continue as guest.</small>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Arrival Date<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" required="" id="arrival_date" name="arrival_date" value="{!! old('arrival_date') !!}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>First name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" required="" id="firstname" name="firstname" value="{!! old('firstname') !!}" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Last name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" required="" id="lastname" name="lastname" value="{!! old('lastname') !!}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Contact Number<span class="text-danger">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="form-control required" data-rule-digits="true" data-rule-maxlength="10" value="{!! old('phone') !!}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Message for the experience organizer (optional)</label>
                                    <textarea id="message" name="message" class="form-control" rows="5">{!! old('message') !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <input type="submit" id="btnReservation" class="btn_1 medium" value="Continue" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--End row -->
</div>
<!--End container -->
<!-- Modal Review -->
@if(@$experience_accomodation)
<div class="modal fade" id="mdlAccommodation{{$experience_accomodation->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    {{ @$experience_accomodation->name }}
                    <span class="price_list price_list-min">{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience_accomodation->room_price, @$experience_accomodation->currency) }}</span>
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
@endif
<!-- End modal review -->
@endsection
@section('footer')
<script src="{{asset('public/basicfront/js/jquery.validate.min.js')}}"  defer></script>
<script src="{{ asset('public/basicfront/js/reservation.js') }}"></script>
<!-- Carousel -->
<script src="{{ asset('public/basicfront/js/owl.carousel.min.js') }}"></script>
<script>
$(document).ready(function () {
<?php if (!Auth::check()) { ?>
        $("#frmReservation").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: '<?php echo url('/user/check-email-exist'); ?>',
                        type: "post",
                        dataFilter: function (response) {
                            $("#dvRsvPass #password").val("");
                            if (response == 'false') {
                                $("#dvRsvPass").show().focus();
                            } else {
                                $("#dvRsvPass").hide();
                            }
                            return 'true'
                        }
                    }
                }
            }
        });
<?php } else { ?>
        $("#frmReservation").validate();
<?php } ?>

    $(".carousel").owlCarousel({
        items: 4,
        autoplay: true
//itemsDesktop: [1199, 3],
//itemsDesktopSmall: [979, 3]
    });
});
</script>
@endsection