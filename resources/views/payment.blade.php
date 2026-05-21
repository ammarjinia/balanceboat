@extends('layouts.front')
@section('title', 'Confirmation')
@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">-->
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
@if($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Error!</strong> {{ $message }}
    </div>
@endif
<div class="container margin_80">
    <h3>Confirm <span>Reservation</span> Details </h3>
    @if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade {{ Session::has('success') ? 'show' : 'in' }}" role="alert" style="display: contents !important;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ $message }}
    </div>
    @endif
    
    <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="img_list">
                            <a href="{{ url("/experience/".$experience->slug) }}">
                                <img src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive"> 
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
                                    {!! $booking_amount_html_price !!}
                                </span>
                                <small>Price:</small>
                                <span>
                                    <?php
                                    if (@$discount > 0) {
                                        ?>
                                        <del class="text-default">
                                            {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$org_price), @$experience_accomodation->currency) }}
                                        </del>
                                        <?php
                                    }
                                    ?>
                                    {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$accomodation_price, @$experience->currency) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    <?php /* @if(@$booking_amount > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h5><i class="icon-certificate"></i> Only pay <strong>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) }}</strong> now to reserve your spot, Rest pay once you arrive.</h5>
            </div>
        </div>
    </div>
    @endif
    */?>
    <div class="row">
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
            <div class="row">
                <div class="col-md-12">
                    <h3>Your details</h3>
                    <div class="form-group">
                        <label>Arrival Date : </label> {{ @$reservation_info['arrival_date'] }}
                    </div>
                    <div class="form-group">
                        <label>Name : </label> {{ @$reservation_info['firstname']." ".@$reservation_info['lastname'] }}
                    </div>
                    <div class="form-group">
                        <label>Email : </label> {{ @$reservation_info['email'] }}
                    </div>
                    <div class="form-group">
                        <label>Telephone : </label> {{ @$reservation_info['phone'] }}
                    </div>
                    <div class="form-group">
                        <label>Description : </label> {{ @$reservation_info['description'] }}
                    </div>
                </div>
            </div>

            <form id="frmPayment" name="frmPayment" method="post" action="{{ url("/payment/process") }}">
                {{ csrf_field() }}
                <input type="hidden" id="is_pay" name="is_pay" value="1" />
                <?php /*<div class="row hidden">
                    <div class="col-md-12 col-sm-12">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <h3><i class="icon-lock"></i> Enter payment details</h3>
                        <div class="form-group">
                            <label>Name on the card</label>
                            <input type="text" id="card_name" name="card_name" class="form-control required" required="" />
                        </div>
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" id="card_number" name="card_number" class="form-control required" required="" />
                        </div>
                        <div class="form-group">
                            <label>Expiration Date </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control required" id="card_expiration_month" name="card_expiration_month" required="">
                                        @for($m=1;$m<=12;$m++)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                        @endfor
                                    </select>
                                    <small>Month</small>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control required" id="card_expiration_year" name="card_expiration_year" required="">
                                        @for($y=date("Y");$y<=date("Y")+50;$y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                    <small>Year</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>CVC Number</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" id="card_cvv" name="card_cvv" class="form-control required" required="" size="4" maxlength="4" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>*/?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <b>
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience_accomodation->currency) }}
                            </b> will be charged to your credit card this includes reservation fee & bank credit card processing fee.
                        </div>
                        <div class="box_style_1 hidden">
                            <h5><i class="icon-certificate"></i> You are currently only paying 10% reservation fee to conﬁrm your seat, rest is payable on arrival</h5>
                        </div>
                        <p class="form-group"><i>I agree with the <a href="{{ url("/terms-and-conditions") }}">booking conditions</a> and <a href="{{ url("/privacy-policy") }}">privacy policy</a> buy making the above payment</i></p>
                    </div>
                </div>
                <a href="{{ url("reservation") }}" class="btn_1 outline form-group">Back</a>
                <?php /*<a href="javascript:void(0);" id="btnPayment" class="btn_1 medium form-group pull-right">Request Reservation</a>*/?>
                
            </form>
            @if(@$experience->is_bookable == 1)
                <button id="rzp-button1" class="btn_1 medium form-group pull-right" style="  margin-right: 10px;">Pay</button>
                @endif
            <!--<form action="{{ url('payment-new-store') }}" method="POST" >-->
            <!--    <script src="https://checkout.razorpay.com/v1/checkout.js"-->
            <!--        data-key="{{ env('RAZORPAY_KEY') }}"-->
            <!--        data-amount="1000"-->
            <!--        data-buttontext="Pay"-->
            <!--        data-name="balanceboat.com"-->
            <!--        data-description="{{ @$reservation_info['description'] }}"-->
            <!--        data-image="https://www.itsolutionstuff.com/frontTheme/images/logo.png"-->
            <!--        data-prefill.name="{{ @$reservation_info['firstname']." ".@$reservation_info['lastname'] }}"-->
            <!--        data-prefill.email="{{ @$reservation_info['email'] }}"-->
            <!--        data-prefill.contact="{{ @$reservation_info['phone'] }}"-->
            <!--        data-theme.color="#ff7529">-->
            <!--    </script>-->
            <!--</form>-->
        </div>
    </div>
    <!--End row -->
</div>
<!--End  single_tour_desc-->
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
                {!! html_entity_decode(strip_tags(@$experience_accomodation->description)) !!}
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
<script src="{{ asset('public/basicfront/js/payment.js') }}"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>-->
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
$("document").ready(function(){
    setTimeout(function(){
       $("div.alert-success").remove();
    }, 5000 ); // 5 secs

});
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    //"key": "rzp_test_nsXNhtKlnfBY9B",
    "key":"{!! env('RAZORPAY_KEY') !!}",
    "amount": "{{ @$booking_amount*100 }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{ @$experience_accomodation->currency }}",
    "name": "{{ @$reservation_info['firstname']." ".@$reservation_info['lastname'] }}",
    "description": "Thank You for Choosing Us",
    "image": "https://example.com/your_logo",
    "order_id": "{!! @$order_id !!}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        $.ajax({
            method: "POST",
            url: "/payment/process",
            data:{
                _token: '{{ csrf_token() }}',
                razorpay_payment_id:response.razorpay_payment_id},
            success:function(data){
                data = JSON.parse(data);
                if(data.success) {
                    self.location.href = '<?php echo url("/")."/payment/success";?>?booking_id='+data.booking_id;
                } else {
                    self.location.href = '<?php echo url("/")."/payment/cancel";?>';
                }
            }
        });
    },
    "prefill": {
        "name": "{{ @$reservation_info['firstname']." ".@$reservation_info['lastname'] }}",
        "email": "{{ @$reservation_info['email'] }}",
        "contact": "{{ @$reservation_info['phone'] }}"
    },
    "notes": {
        "name":"{{ $experience->name }}",
        "id": "{{ @$experience_accomodation->id }}",
        "Duration Day":"{{ @$experience->duration }}",
    },
    "theme": {
        "color": "#3399cc"
    }
};
var rzp1 = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
@endsection