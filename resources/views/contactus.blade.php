@extends('layouts.front')
@section('title', 'Contact Us')
<!-- Meta Info Start-->
@section('meta_title', "Contact Us")
@section('description', "Contact Us page for BalanceBoat, a booking platform for retreats and professional courses in Yoga and Ayurveda")
@section('keywords', "BalanceBoat, Booking platform, Yoga teacher Training booking website, Ayurveda packages booking, BalanceBoat contact us page, contact us page")
<!-- Meta Info End -->
@section('head')
<link href="{{asset('public/basicfront/css/contactus.css')}}" rel="stylesheet" />
@endsection
@section('content')
<section class="promo_full" id="contact_us">
    <div class="promo_full_wp magnific row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box_style_1 nwbx-style2">
                @if ($errors->any())
                <div class="alert alert-danger text-left">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(Session::has('flash_message'))
                <div class="alert alert-success text-left">
                    <em> {!! session('flash_message') !!}</em>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                @endif 
                @if(Session::has('flash_error_message'))
                <div class="alert alert-danger text-left">
                    <em> {!! session('flash_error_message') !!}</em>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                @endif 
                <h1>Contact Us</h1>
                <form id="frmContact" name="frmContact" action="{{ url("/send-contact-us-email") }}" method="post">
                    @honeypot
                    {{ csrf_field() }}
                    <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
                    <div class="form-group text-left">
                        <label>Name</label>
                        <input id="name" name="name" class="form-control required" required="" type="text" value="{{ old('name') }}" />
                    </div>
                    <div class="form-group text-left">
                        <label>Email Address</label>
                        <input id="email" name="email" class="form-control required email" required="" type="email" value="{{ old('email') }}"  />
                    </div>
                    <div class="form-group text-left">
                        <label>Phone</label>
                        <input id="phone" name="phone" class="form-control" type="tel" value="{{ old('phone') }}" />
                    </div>
                    <div class="form-group text-left">
                        <label>Message</label>
                        <textarea id="message" name="message" class="form-control required" required="">{!! old('message') !!}</textarea>
                    </div>
                    <div class="form-group text-left">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>
                    <input type="submit" name="submit" id="submit" class="btn_1  medium" value="Contact Us" />
                </form>
                <div class="form-group text-left">
                    <a href="javascript:void(0);" id="contactaddress">68 Maqbara Road, Hazrathganj,<br /> Lucknow - 226001, India</a>
                    <a href="tel://+917800080808" id="phone">+91-7800080808</a>
                    <a href="mailto:zen@balanceboat.com" id="email_footer">zen@balanceboat.com</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End section -->

<div id="overlay"></div>
<!-- Mask on input focus -->
@endsection
@section('footer')
<script src="{{ asset('public/basicfront/js/jquery.validate.min.js') }}"></script>
 {!! NoCaptcha::renderJs() !!}

<script src="{{ asset('public/basicfront/js/contactus.js') }}"></script>
@endsection