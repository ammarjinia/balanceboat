@extends('layouts.deals_layout')
@section('title', 'Review Information')
@section('content')
<section class="bg-listing-details pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <h1 class="secondary">Review Information</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 mb-4 right-panel">
                <div class="bg-listing-right-wrapper">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="ellipsis" title="Contact Sitaram Beach Retreat">
                            Summary
                        </h2>
                        <li class="bg-btn bg-menu-list bg-btn-secondary to-bottom">
                            {!! @$experience->currency !!}
                        </li>

                    </div>
                    <!-- Enquire -->
                    <div class="bg-box bg-white fs-14 p-0">
                        <div class="booking-summary p-3 bg-border-bottom">
                            <div class="mb-2 fw-500">Experience</div>
                            <div class="bg-brand c-white p-2 bg-box">
                                <a href="{{ url("/experience/".$experience->slug) }}" target="_blank">{!! $experience->name !!}</a>
                            </div>
                        </div>

                        @if (@$experience_accomodation)
                        <div class="booking-summary p-3 bg-border-bottom">
                            <div class="mb-2 fw-500">Choosen Accomodation</div>
                            <div class="bg-brand c-white p-2 bg-box">
                                {!! @$experience_accomodation->name !!}
                            </div>
                        </div>
                        @endif

                        <div class="p-3 bg-border-bottom">
                            <div class="mb-2 fw-500">Details</div>
                            <div class="booking-summary bg-brand c-white p-2 bg-box">
                                <?php /* <div>1 <b class="ms-1 fw-500">Person</b></div> */ ?>
                                <?php if (@$exp_booking_durations) { ?>
                                    <div><?php echo @$exp_booking_durations; ?> <b class="ms-1 fw-500">Days</b></div>
                                <?php } ?>
                                @if(Session('experience_info')['exp_booking_date'])
                                <?php
                                $arexp_booking_date = explode(" - ", Session('experience_info')['exp_booking_date']);
                                $exp_booking_start = @$arexp_booking_date[0];
                                $exp_booking_end = @$arexp_booking_date[1];
                                ?>

                                @if(@$exp_booking_start)
                                <div><span class="fw-500 mt-2">Check In</span> <span class="text-right">{!! \Carbon\Carbon::parse(trim(@$exp_booking_start))->format("M d, Y") !!}</span></div>
                                @endif    
                                @if(@$exp_booking_end)
                                <div><span class="fw-500">Check Out</span> <span class="text-right"> {!! \Carbon\Carbon::parse(trim(@$exp_booking_end))->format("M d, Y") !!}</span></div>
                                @endif    
                                @endif
                            </div>
                        </div>
                        <div class="p-3 bg-border-bottom">
                            <div class="mb-2 fw-500">Total</div>
                            <div class="booking-summary bg-brand c-white p-2 bg-box">
                                <div>
                                    <span class="fw-500">Price</span> <span class="text-right fs-16"> 
                                        <?php
                                        if (@$discount > 0) {
                                            ?>
                                            <del class="text-default">
                                                {!! \App\Http\Helpers\CommonHelper::get_currency_rate((@$org_price), @$experience_accomodation->currency) !!}
                                            </del>
                                            <?php
                                        }
                                        ?>
                                        {!! \App\Http\Helpers\CommonHelper::get_currency_rate(@$accomodation_price, @$experience->currency) !!}
                                    </span>
                                </div>
                                <div><span class="fw-500">Booking Amount</span> <span class="text-right"> {!! \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) !!}</span></div>
                            </div>
                        </div>
                        <div class="booking-summary p-3 bg-border-bottom">
                            <div class="c-brand mb-2 fw-500">Need Help?</div>
                            <div>Call: <a class="ms-2" href="tel:+917800080808">+91-7800080808</a></div>
                            <div>E-Mail: <a class="ms-2" href="mailto:zen@balanceboat.com">zen@balanceboat.com</a></div>
                        </div>
                        <div class="booking-summary why-booking p-3">
                            <div class="c-brand mb-2 fw-500">Why Booking with Us?</div>
                            <div> <img src="{{ url('public/deals/images/payment/handshake.png') }}" alt="" width="22px"> Best Deals</div>
                            <div> <img src="{{ url('public/deals/images/payment/debit-card.png') }}" alt="" width="22px"> Flexible Payment</div>
                            <div> <img src="{{ url('public/deals/images/payment/crown.png') }}" alt="" width="22px"> Customizations &amp; VIP upgrades</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9 mb-4 order-lg-first left-panel" id="package">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <h2 class="ellipsis" title="">
                                Package Info
                            </h2>
                        </div>
                        <div class="bg-box mb-4 bg-white p-3">
                            <span class="fs-18"></span>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-500">{{ $experience->name }}</span>
                                <span class="c-brand view-website c-medium fs-14 fw-500">
                                    <span class=" icon-edit me-2"></span>
                                    <a target="{{ url("/experience/".$experience->slug) }}" href="{{ url("/experience/".$experience->slug) }}">Edit</a>
                                </span>
                            </div>
                            <p>
                                <span class="c-pink">
                                    <span class="icon-location c-brand me-1"></span>
                                    <span>{{ @$center->address_of_center }}</span>
                                </span>
                            </p>

                            <p class="fw-500">{{ @$center->name }}</p>

                            <div class="payment-details-wrapper bg-grey-dark">
                                <?php if (@$exp_booking_durations) { ?>
                                    <div class="entries"> <?php echo @$exp_booking_durations; ?> &nbsp;<span class="fw-500"> Days</span></div>
                                <?php } ?>
                                @if(Session('experience_info')['exp_booking_date'])
                                <?php
                                $arexp_booking_date = explode(" - ", Session('experience_info')['exp_booking_date']);
                                $exp_booking_start = @$arexp_booking_date[0];
                                $exp_booking_end = @$arexp_booking_date[1];
                                ?>
                                <div class="entries check-in-out">
                                    @if(@$exp_booking_start)
                                    <div class="text-left">
                                        <div>Check In</div>
                                        <div class="fw-500">
                                            {!! \Carbon\Carbon::parse(trim(@$exp_booking_start))->format("M d, Y") !!}
                                        </div>
                                    </div>
                                    @endif    
                                    <div class="two">-</div>
                                    @if(@$exp_booking_end)
                                    <div class="text-right">
                                        <div>Check Out</div>
                                        <div class="fw-500">
                                            {!! \Carbon\Carbon::parse(trim(@$exp_booking_end))->format("M d, Y") !!}
                                        </div>
                                    </div>
                                    @endif 
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="">
                            <h2 class="ellipsis">
                                Selected Package
                            </h2>
                        </div>
                        <!-- Experience Summary -->


                        <!-- Loader -->
                        <div class="intereactive-lists-loader mb-4">
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
                                <div class="col-12">
                                    <div class="fw-500 il-wrapper bg-box">
                                        <div class="in-container">
                                            <div class="article-items full-width">
                                                <div class="left pb-3 pb-xxl-0">
                                                    <div class="container-fluid p-0">
                                                        <?php
                                                            if(@$imagegalleries) {?>
                                                        <div class="slideshow-container">
                                                            @foreach(@$imagegalleries as $gallery)
                                                            <div class="mySlides fade">
                                                                <img src="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" />
                                                            </div>
                                                            @endforeach
                                                            <a class="prev">&#10094;</a>
                                                            <a class="next">&#10095;</a>
                                                            
                                                            <div class="thumnnails">
                                                            @foreach(@$imagegalleries as $gallery)
                                                                <span class="dot">
                                                                    <img src="{{ Storage::disk('azure')->url(rawurlencode($gallery->image_url)) }}" alt="{{ $gallery->image_title }}" />
                                                                </span>
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                            <?php }?>
                                                    </div>
                                                </div>
                                                <div class="right">
                                                    <div>
                                                        <span class="bg-brand br-12 c-white d-inline-flex p-2">
                                                            @if (@$experience_accomodation)
                                                            {!! @$experience_accomodation->name !!}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="head-price mb-1">
                                                        <a target="{{ url("/experience/".$experience->slug) }}" href="{{ url("/experience/".$experience->slug) }}">
                                                            <h3>{!! $experience->name !!}</h3>
                                                        </a>
                                                    </div>

                                                    <h4 class="c-medium fw-400 mb-2">
                                                        @if(@$experience->recurring_type == "Daily")
                                                        Available all year round
                                                        @else
                                                        {{ @$experience->available_month }}
                                                        @endif
                                                    </h4>

                                                    <?php if (@$experience->experience_summary) { ?>
                                                        <div style="height:120px;overflow:hidden;">
                                                            {!! @$experience->experience_summary !!}
                                                        </div>    
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @if($experience->center_id != 2293 && $experience->is_bookable == 1)
                <form class="row">

                    <div class="col-12">
                        <div class="bg-box mb-4 bg-white p-0">

                            <div class="bg-border-bottom">
                                <div class="d-flex justify-content-between p-3">
                                    <span class="c-brand">Your Preferred Payment Method</span>
                                    <span>&#128274; SSL Secure</span>
                                </div>
                            </div>
                            <div class="p-3 fs-14 no-decoration-all">

                                <div class="fw-500 card-wrap">
                                    Cards
                                    <img src="{{ url('public/deals/images/payment/visa.png') }}" alt="" width="40">
                                    <img src="{{ url('public/deals/images/payment/mastero.png') }}" alt="" width="40">
                                    <img src="{{ url('public/deals/images/payment/master.png') }}" alt="" width="40">
                                </div>
                                <div class="fw-500 mt-2 mb-4">Pay with Debit Card | Credit Card <br><span class="fs-12">or</span>
                                    <br>UPI
                                    <br><span class="fs-12">or</span> <br>Netbanking
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="javascript:void(0);" class="bg-btn bg-btn-light" id="rzp-button1">
                                        Pay {!! \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) !!}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="fs-14">
                            By confirming this booking you are agreeing to the <a href="{{ url('terms-and-conditions') }}">Term and Conditions</a>
                            &amp; <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
@section('footer')
<?php echo \App\Http\Helpers\CommonHelper::excerpt(strip_tags(html_entity_decode(@$experience->experience_overview)), 160); ?>
@endsection


@section('mob_link')
<div class="bg-mobile-bottom d-lg-none">
    <div>
        <div>
            <span class="fs-22 c-pink">{!! \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking_amount, @$experience->currency) !!}</span>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <div class="active-only-middle">
            <span class="c-white">
                @if($experience->center_id != 2293 && $experience->is_bookable == 1)
                <button id="rzp-button1" class="btn_1 medium form-group bg-form-submit-small ms-2">Pay Now</button>
                @endif
            </span>
        </div>
    </div>
</div>
@endsection

@section('js')
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
    "key":"<?php echo env('RAZORPAY_KEY') ?>",
    "amount": "{{ @$booking_amount*100 }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
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