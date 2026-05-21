@extends('layouts.front')
@section('title', 'Payment Information')
@section('banner')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ asset('public/basicfront/img/slide_hero2.jpg') }}" data-natural-width="1280" data-natural-height="780">
    <div class="parallax-content-2">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Payment Cancel</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End hero -->
@endsection
@section('content')
<main>
    <div id="position">
        <div class="container">
            <ul>
                <li><a href="{{ url("/") }}">Home</a></li>
                <li>Payment Cancel</li>
            </ul>
        </div>
    </div>
    <!-- End position -->

    <div class="container margin_60">
        <div class="row">
            <div class="col-md-8 add_bottom_15">

                <div class="form_title">
                    <h3><strong><i class="icon-ok"></i></strong>Payment Cancelled</h3>
                    
                </div>
                <!--End step -->
            </div>
            <!--End col-md-8 -->

            <aside class="col-md-4">
                <div class="box_style_2">
                    <i class="icon_set_1_icon-89"></i>
                    <h4>Have <span>questions?</span></h4>
                    <a href="tel://07800080808" class="phone">+91 780 008 0808</a>
                </div>
            </aside>

        </div>
        <!--End row -->
    </div>
    <!--End container -->
</main>
<!-- End main -->
@endsection
@section('footer')
<script src="{{ asset('public/basicfront/js/payment.js') }}"></script>
@endsection