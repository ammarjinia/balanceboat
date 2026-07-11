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

<div class="collapse" id="collapseMap">
    <div id="map" class="map">test</div>
</div>
<!-- End Map -->
@if($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Error!</strong> {{ $message }}
    </div>
@endif
<div class="container margin_80">
    <div class="row">
        <div class="col-md-12">
            <h2>Confirm Reservation Details </h2>
        </div>
    </div>
    @if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade {{ Session::has('success') ? 'show' : 'in' }}" role="alert" style="display: contents !important;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Success!</strong> {{ $message }}
    </div>
    @endif
    <section>
        <div class="container">
            <div class="row add_bottom_45">
                <div class="strip_all_tour_list wow fadeIn item">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="img_list">
                                <a href="{{ url("/experience/".$experience->slug) }}">
                                    <img data-src="{{ ($experience->thumbnail_image_url) ? Storage::disk('s3')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('s3')->url(rawurlencode($experience->banner_image_url)) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy" /> 
                                </a>
                            </div>
                        </div>
                        <div class="clearfix visible-xs-block"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-left">
                            <div class="tour_list_desc" style="height:auto;">
                                <a href="{{ url("/experience/".$experience->slug) }}"><h3 style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">{{ $experience->name }}</h3></a>
                                <h5 class="hidden-xs hidden-sm">
                                    <i class="fa fa-calender"></i> 
                                    @if(@$experience->recurring_type == "Daily")
                                    Available all year round
                                    @else
                                    {{ @$experience->available_month }}
                                    @endif
                                </h5>
                                <?php if (@$experience->experience_summary) { ?>
                                <div style="height:148px;overflow:hidden;">
                                    {!! @$experience->experience_summary !!}
                                </div>    
                                <?php } ?>
                                    <?php
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
                                    <small>Price:</small>
                                    <span>
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
                                    <?php
                                    if (!empty(@$experience->duration)) {
                                        ?>
                                        <p><br /></p>
                                        <small>For:</small>
                                        <span class="text-days">
                                            <?php
                                            echo @$experience->duration;
                                            ?>
                                        </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- End section -->
    <div class="row">
        <aside class="col-md-4">
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
                <h3 class="inner">Reservation details</h3>
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
                                    <img src="{{ Storage::disk('s3')->url(@$experience_accomodation->banner_image_url) }}" alt="" class="img-responsive img-thumbnail" />
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            @if(@$experience_accomodation->description)
                            {!! @$experience_accomodation->description !!} 
                            @endif
                        </div>
                        @endif
                        <?php
                        $pay = $pay - $discount;
                        ?>
                        <table class="table table_summary">
                            <tbody>
                                <tr class="total">
                                    <th>Pay:</th>
                                    <td class="text-right">
                                        <?php
                                        // Calculate Commission
                                        $commission = ($pay * @$experience->commission) / 100;
                                        if (!empty(@$experience->deposit_policy)) {
                                            switch (@$experience->deposit_policy) {
                                                case "2" :
                                                    // If Fixed Amount
                                                    $deposit_amount = @$experience->deposit_amount;
                                                    $pay = @$experience->deposit_amount + $commission;
                                                    break;
                                                case "3" :
                                                    // If Percentage
                                                    $deposit_amount = (@$pay * @$experience->deposit_amount) / 100;
                                                    $pay = $commission + $deposit_amount;
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                        ?>
                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay, @$experience_accomodation->currency) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/box_style_1 -->

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
                <div class="row hidden">
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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <b>
                                {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay, @$experience_accomodation->currency) }}
                            </b> will be charged to your credit card this includes reservation fee & bank credit card processing fee.
                        </div>
                        <div class="box_style_1 hidden">
                            <h5><i class="icon-certificate"></i> You are currently only paying 10% reservation fee to conﬁrm your seat, rest is payable on arrival</h5>
                        </div>
                        <p class="form-group"><i>I agree with the <a href="{{ url("/terms-and-conditions") }}">booking conditions</a> and <a href="{{ url("/privacy-policy") }}">privacy policy</a> buy making the above payment</i></p>
                    </div>
                </div>
                <a href="{{ url("reservation") }}" class="btn_1 outline form-group">Back</a>
                <a href="javascript:void(0);" id="btnPayment" class="btn_1 medium form-group pull-right">Request Reservation</a>
                <button id="rzp-button1" class="btn_1 medium form-group pull-right" style="  margin-right: 10px;">Pay</button>
            </form>
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
    @if(@$deposit_amount > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="box_style_1">
                <h5><i class="icon-certificate"></i> Only pay {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay, @$experience_accomodation->currency) }} now to reserve your spot, Rest pay once you arrive.</h5>
            </div>
        </div>
    </div>
    @endif
    <!--End row -->
</div>
<!--End  single_tour_desc-->
<!--End container -->
<!-- Modal Review -->
@if(@$experience_accomodation))
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
                        <a href="{{ Storage::disk('s3')->url(@$accomodationimagegallery->image_url) }}"><img src="{{ Storage::disk('s3')->url(@$accomodationimagegallery->image_url) }}" alt="Image"></a>
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
    "key": "rzp_test_nsXNhtKlnfBY9B",
    "amount": "{{ (@$experience_accomodation->room_price-@$discount)*100 }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{ @$experience_accomodation->currency }}",
    "name": "{{ @$reservation_info['firstname']." ".@$reservation_info['lastname'] }}",
    "description": "Thank You for Choosing Us",
    "image": "https://example.com/your_logo",
    // "order_id": "order_9A33XWu170gUtm", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        $.ajax({
            method: "POST",
            url: "/payment/process",
            data:{razorpay_payment_id:response.razorpay_payment_id},
            success:function(data){
                data = JSON.parse(data);
                if(data.success) {
                    self.location.href = '<?php echo url("/")."/payment/success";?>';
                } else {
                    self.location.href = '<?php echo url("/")."/payment/success";?>';
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