@extends('layouts.front')

@section('title', 'About Us')

<!-- Meta Info Start-->
@section('meta_title', "About Us")
@section('description', "About Us page for BalanceBoat, a booking platform for retreats and professional courses in Yoga and Ayurveda")
@section('keywords', "BalanceBoat, Booking platform, Yoga teacher Training booking website, BalanceBoat about us page, about us page, Ayurveda packages booking ")
<!-- Meta Info End -->

@section('banner')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{ url("public/basicfront/img/about_us_banner.jpg") }}" data-natural-width="1400" data-natural-height="470">
    <div class="parallax-content-1">
        <div class="animated fadeInDown">
            <h1>About Us</h1>
        </div>
    </div>
</section>
@endsection
@section('content')

<main>
    <div id="position">
        <div class="container">
            <ul>
                <li><a href="{{ url("/") }}">Home</a></li>
                <li>About Us</li>
            </ul>
        </div>
    </div>
    <!-- End Position -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">What is BalanceBoat?</h2>
                <div class="row">
                    <div class="col-md-12">
                        <p>BalanceBoat is booking website for professional courses and retreats in Yoga, Ayurveda, Meditation, Pilates, Surfing, Hiking and much more. The retreats and courses are conducted by organizers around the world. Our business model is commission based but we are always open to interesting ideas how we could work together, feel free to drop us an email at <a href="mailto:zen@balanceboat.com">zen@balanceboat.com.</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">Who are we?</h2>
                <div class="row">
                    <div class="col-md-12">
                        <p>We are a small but growing team of Marathon runners, Yoga practitioners, Technology enthusiasts that love to Travel. We work completely remote as it helps us be closer to our customers and value each team members time.</p>
                        <p>Passion of health, fitness,travel and technology in in our DNA. We embrace constant change and are always open to conflicting ideas and testing their merit.</p>
                        <p>We firmly believe</p>
                        <blockquote>
                            "It's better to beg for forgiveness than to ask for permission."
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">Our Mission</h2>
                <div class="row">
                    <div class="col-md-12">
                        <p>Our mission is to collaborate with organizers all around the world and help positively impact people’s health and lives.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">Our Other Ventures</h2>
                <div class="row">
                    <div class="col-md-12">
                        <p>Our other ventures include</p>
                        <p><a href="http://balancegurus.com/" target="_blank">www.balancegurus.com</a> the worlds largest listing website for Yoga, Ayurveda, Meditation, Pilates and Tai chi centres.</p>
                        <p><a href="http://balanceflash.com/" target="_blank">www.balanceflash.com</a> a website that curates the best blogs related to Yoga, Ayurveda, Meditation, Pilates, Motivation, Empowerment.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">Our CSR Initiative</h2>
                <div class="row">
                    <div class="col-md-12">
                        <p>We are a part of Plant A Tree initiative and for every successful booking on BalanceBoat we plant a tree. We do this on the 15th of August every year which is celebrated as India’s Independence day.</p>
                        <p>We are always open to working with NGO and other initiatives.</p>
                    </div>
                </div>
            </div>
        </div>
        <!--End row -->        
    </div>
    <!--End container -->
    <br />
</main>
<!-- End section -->
@endsection
@section('footer')
@endsection
