@extends('layouts.front')
@section('title', 'Customer Support page')
<!-- Meta Info Start-->
@section('meta_title', "Customer Support page")
@section('description', "Customer Support page for BalanceBoat booking platform")
@section('keywords', "BalanceBoat, Booking platform, Support page, Customer support, Partner support, Send message, Request Support")
<!-- Meta Info End -->
@section('head')
<link href="{{asset('public/basicfront/css/contactus.css')}}" rel="stylesheet" />
@endsection
@section('banner')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ asset('public/basicfront/img/slide_heronw.jpg')}}" data-natural-width="1280" data-natural-height="780">
    <div class="parallax-content-2">
        <div class="container">
            <div class="row">
                <div class="col-md-12">                    
                    <div class="slid-txt">
                        <h1>Namaste</h1>
                        <span>How Can We Help? </span>
                    </div>

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
        </ul>
    </div>
</div>

<!-- End Position -->
<div class="container margin_60">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 fntlrg">
            <!--                        <h2>Shamballah Retreats Sintra, Portugal </h2>-->
            <p class="cls_new_p">Our support team will respond to your message shortly, you can expect to get a reply within 24 hrs. Incase you have a query that is speciﬁc to an experience offered by an organizer on BalanceBoat.com please click on the SEND ENQUIRY button on the experience page or the organizers proﬁle page, this way we can resolve your query faster.</p>

            <h2>Send Us a Message </h2>

            <div class="row">
                <div class="col-md-6 col-sm-6 form-group">
                    <label>First Name </label>
                    <input type="text" class="form-control"></input>
                </div>
                <div class="col-md-6 col-sm-6 form-group">
                    <label>Last Name </label>
                    <input type="text" class="form-control"></input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 form-group">
                    <label>Email Address</label>
                    <input type="text" class="form-control"></input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 form-group">
                    <label>Conﬁrm email address </label>
                    <input type="text" class="form-control"></input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"> <label>Telephone number (optional)</label></div>
                <div class="col-md-4 col-sm-6 form-group">
                    <input type="text" class="form-control"></input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>What can our support team help you with</label>
                    <textarea class="form-control" rows="5"></textarea>
                </div>
            </div>
            <?php /*<div class="row">
                <div class="col-md-12 form-group">
                    <img src="{{captcha_src('flat')}}" onclick="this.src = '/captcha/flat?' + Math.random()" id="captchaCode" alt="" class="captcha">
                    <a rel="nofollow" href="javascript:void(0);" onclick="document.getElementById('captchaCode').src = 'captcha/flat?' + Math.random()" class="refresh">
                        <button type="button" class="btn btn-info btn-refresh icon-spin3"></button>
                    </a>
                    <input type="text" name="captcha" class="form-control required" required="" />
                </div>
            </div>*/?>
            <div class="row frm-btn">
                <div class="col-md-12">
                    <a href="#" class="btn_1 medium">Send</a>
                </div>
            </div>
            <p class="cls_new_p">Our support team will respond to your message shortly. In case you have a question about a particular experience or center we would recommend clicking on the SEND ENQUIRY button on the page inorder to get a faster response.</p>
            <p class="cls_new_p">In case you would you like to join BalanceBoat.com as an Organizer we would recommend you to click <a href="{{ url("/contact-us") }}" class="text-pink">List on BalanceBoat</a></p>
        </div>
    </div>

    <!--End row -->
    <!--<div class="box_style_1 ">

        <div class="row">
            <div class="col-sm-4 text-center">
                <h4>Partner with Us</h4>
            </div>

            <div class="col-sm-4 text-center">
                <h4>Write for BalanceBoat</h4>
            </div>

            <div class="col-sm-4 text-center">
                <h4>List your center</h4>
            </div>

        </div>


    </div>-->

</div>
<!--End container -->

<!-- End section -->
@endsection
@section('footer')
@endsection