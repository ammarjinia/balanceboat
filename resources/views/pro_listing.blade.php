<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name=keywords content="Retreat listing and marketing website"/>
    <meta name="description" content="Retreat listing and marketing plaform Yoga retreats, Meditation retreats, Ayurveda retreats, Detox retreats, Wellness retreats and much more " />
    <meta itemprop=name content="BalanceBoat | List your retreats for free" />
    <meta itemprop=description content="Balanceboat is a retreat listing, marketing and booking platform. You can list your retreats for free and market them to clients worldwide." />
    <meta itemprop=image content="https://balanceboat.com/public/basicfront/img/social-share-image-1.jpg" />
    <meta name=twitter:card content="summary" />
    <meta name=twitter:title content="BalanceBoat | List your retreats for free" />
    <meta name=twitter:description content="Balanceboat is a retreat listing, marketing and booking platform. You can list your retreats for free and market them to clients worldwide." />
    <meta name=twitter:image:src content="https://balanceboat.com/public/basicfront/img/social-share-image-1.jpg" />
    <meta property=og:title content="BalanceBoat | List your retreats for free" />
    <meta property=og:description content="Balanceboat is a retreat listing, marketing and booking platform. You can list your retreats for free and market them to clients worldwide." />
    <meta property=og:image content="https://balanceboat.com/public/basicfront/img/social-share-image-1.jpg" />
    <meta property="og:image:url" content="https://balanceboat.com/public/basicfront/img/social-share-image-1.jpg" />
    <meta property="og:image:secure_url" content="https://balanceboat.com/public/basicfront/img/social-share-image-1.jpg" />
    <meta property=og:url content="https://balanceboat.com" />
    <meta property=og:site_name content="https://balanceboat.com" />
    <meta property=og:type content="website" />
    <meta name="yandex-verification" content="58770b522722e36d" />
    <title>List your retreats get more sales</title>

    <!-- Favicon -->
    
    <link rel="icon" type="image/x-icon" href="{{asset('public/basicfront/img/favicon.ico')}}">
    <link href="{{asset('public/basicfront/img/favicon.ico')}}" rel="apple-touch-icon" />
    
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('public/pro_listing/css/style.css') }}">
    <style type="text/css">
        .form-control.error, .form-text-area.error {margin-bottom:5px;}
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader-area">
        <div class="lds-dual-ring"></div>
    </div>
    <!--Preloader-->
    <script defer src="https://www.googletagmanager.com/gtag/js?id=G-WPPP45GGLF"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WPPP45GGLF');
        </script>
    <!-- header-start -->
    <header>
        <div class="header-area">
            <div id="sticky-header" class="main-header-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="header_wrap d-flex justify-content-between align-items-center">
                                <div class="header_left">
                                    <div class="logo">
                                        <a href="">
                                            <img src="{{ asset('public/pro_listing/img/balanceboat-header-logo.png') }}" alt="BalanceBoat" />
                                        </a>
                                    </div>
                                </div>
                                <div class="header_right d-flex align-items-center">
                                    <div class="main-menu d-none d-lg-block">
                                        <nav>
                                            <ul id="navigation">
                                                <li class="submenu">
                                                    <a href="{{ url("/experiences") }}" class="show-submenu">Experiences</a>
                                                </li>
                                                <li class="submenu">
                                                    <a href="{{ url("/blog") }}">Articles</a>
                                                </li>
                                                <li class="submenu"><a href="{{ url("/contact-us") }}">Contact Us</a></li>
                                                @if (Auth::guest())
                                                <li class="submenu">
                                                    <a href="{{ url("/login") }}" class="">Login</a>
                                                </li>
                                                @else
                                                <li class="submenu">
                                                    <a href="{{ url("/myaccount") }}" class="">My Bookings</a>
                                                </li>
                                                <li class="submenu">
                                                    <a href="{{ url("/logout") }}" class="">Logout</a>
                                                </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <!-- Welcome-area-start -->
    <div class="welcome-area one bg-primary-cu" id="home">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-sm-7 col-md-7">
                    <div class="welcome-content mt-sm-100">
                        <h3 class="wow fadeInUp" data-wow-delay="0.4s">Boost Your Sales</h3>
                        <p class="wow fadeInUp" data-wow-delay="0.6s">Get more sales & verified leads - List your Retreats on BalanceBoat an invite only completely managed retreat listing and selling platform.</p>
                        <div class="hero-button-area">
                            <a class="btn sosa-btn-2" href="javascript:void(0);" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url('list-your-retreats-get-more-sales') }}">List Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 col-md-5">
                    <div class="welcome-thumb-2 text-center">
                        <img src="{{ asset('public/pro_listing/img/all-img/banner.png') }}" alt="Retreat booking, listing and marketing website.">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Welcome-area-end -->

    <!-- We Offer area -->
    <div class="we-offer-area section-padding-100-50" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="we-offer-thumb mb-50">
                        <img src="{{ asset('public/pro_listing/img/all-img/balancegurusboost.png') }}" alt="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="we-offer-content mb-50">
                        <h3>Amazing Benefits</h3>
                        <p class="mb-30">Here are some of the amazing benefits that will set your business apart once you list your retreats on Balanceboat.com</p>

                        <ul>
                            <li><i class='bx bx-check-circle'></i> <span>Zero Listing &amp; Maintenance fees</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>More Sales &amp; verified Leads</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>Cross promotions on BalanceGurus.com world largest vedic wellness platform</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>International Exposure for your retreats &amp; trainings</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>Expertly managed, updated &amp; optimized listings</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>Better Ranking for your retreats &amp; trainings on search engines</span></li>
                            <li><i class='bx bx-check-circle'></i> <span>Better Ranking for your retreats &amp; trainings on image search </span></li>
                            <li><i class='bx bx-check-circle'></i> <span>Verification &amp; Verified Badge on Balancegurus.com Listing</span></li>
                        </ul>
                        <!-- Button Area -->
                        <!--<div class="button mt-50">
                            <a class="btn theme-btn-two" href="#">Read more</a>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Counter up area -->
    <div class="counter-up-area bg-primary-cu section-padding-100-50">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="single-counter-2 bg-primary-cu text-center mb-50 aos-init aos-animate" data-aos="flip-up" data-aos-delay="400">
                        <div class="counter-number-2">
                            <h3><span class="counter">12</span><span>+</span></h3>
                            <p>Locations</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="single-counter-2 bg-primary-cu text-center mb-50 aos-init aos-animate" data-aos="flip-up" data-aos-delay="500">
                        <div class="counter-number-2">
                            <h3><span class="counter">250</span><span>+</span></h3>
                            <p>Retreats</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="single-counter-2 bg-primary-cu text-center mb-50 aos-init aos-animate" data-aos="flip-up" data-aos-delay="600">
                        <div class="counter-number-2">
                            <h3><span class="counter">48</span><span>+</span></h3>
                            <p>Listed Businesses</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="single-counter-2 bg-primary-cu text-center mb-50 aos-init aos-animate" data-aos="flip-up" data-aos-delay="600">
                        <div class="counter-number-2">
                            <h3><span class="counter">8</span><span>+</span></h3>
                            <p>Categories</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Counter up area -->

    <!-- Our Service Area -->
    <div class="our-service-area section-padding-100-50" id="service">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="section-title t_center">
                        <h2>Inclusions</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="row justify-content-center">
                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box"> <svg id="s-1" xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award">
                                        <circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                    </svg></div>
                                <div class="single-hosting-text">
                                    <h4>Completely Managed.</h4>
                                    <p class="text mb-30">Our expert content and marketing team will list and optimize all your retreats. So you don't have to worry about SEO and content creation.</p>
                                    <!--<a href="#">Read more <i class="fas fa-chevron-right"></i></a>-->
                                </div>
                            </div>
                        </div>

                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box"> <svg id="s-2" xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                                        <circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                    </svg></div>
                                <div class="single-hosting-text">
                                    <h4>Cross Promotions.</h4>
                                    <p class="text mb-30">We leverage our partnersip with BalanceGurus the world largest vedic wellness listing and promoting website to promote your retreats and offerings.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box">
                                    <div class="service-icon">
                                        <svg id="service-1" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout icon-service">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="9" y1="21" x2="9" y2="9"></line>
                                        </svg>
                                    </div>
                                        <!--<circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>-->
                                    </svg></div>
                                <div class="single-hosting-text">
                                    <h4>Retreats Calendar.</h4>
                                    <p class="text mb-30">Your Retreats will be a part of the retreat calendar on BalanceGurus.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box">
                                    <svg id="service-1" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout icon-service">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="3" y1="9" x2="21" y2="9"></line>
                                        <line x1="9" y1="21" x2="9" y2="9"></line>
                                    </svg>
                                </div>
                                <div class="single-hosting-text">
                                    <h4>Global Exposure.</h4>
                                    <p class="text mb-30">BalanceBoat has a global reach and our patrons come from a wide demographic. Tech entrepreneur, Fashion designers, Lawyers, Artists, Bureaucrats, Social media influencers and more..</p>
                                </div>
                            </div>
                        </div>


                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box">
                                    <svg id="icon-home" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                                <div class="single-hosting-text">
                                    <h4>Free Listings.</h4>
                                    <p class="text mb-30">Not only are the retreat listings free even updates and maintenance is free and completely managed.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Single Card Item -->
                        <div class="col-md-6 col-lg-4">
                            <div class="single-item-hosting">
                                <div class="icon-box"> 
                                    <svg id="service-2" xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                </div>
                                <div class="single-hosting-text">
                                    <h4>Social Media Shout-Outs.</h4>
                                    <p class="text mb-30">As a Listed Business our social media marketing team will mention and tag you in our marketing campaigns whenever possible.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Service Area -->

    <!-- Video Area -->
    <!--<div class="video-area bg-img bg-overlay section-padding-200" style="background-image: url(img/all-img/48.jpg)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="video-content-area">
                        <div class="video-icon home-6">
                            <a href="https://www.youtube.com/watch?v=gbXEPHsTkgU" class="video-btn white" id="videobtn2"><i class="fa fa-play"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Video Area -->

    <!-- Feature Area -->
    <div class="feature-area bg-white section-padding-100-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="section-title t_center">
                        <h2>Amazing Benifits</h2>
                        <p>BalanceBoat is an invite only Retreat listing, marketing and booking platform.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="content-box">
                        <div class="inner-box">
                            <div class="row justify-content-center">
                                <!-- Single Card Item -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="single-item-3 text-center">
                                        <div class="icon-box"><i class="fas fa-award"></i></div>
                                        <div class="single-item-text-3">
                                            <h4>Badges.</h4>
                                            <p class="text mb-0">As Partner we help you get your business verified &amp; approved so that you can get the Verified Bagde &amp; on BalanceGurus which will help increase trust and conversion rates.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Card Item -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="single-item-3 text-center">
                                        <div class="icon-box"><i class="fas fa-bullhorn"></i></div>
                                        <div class="single-item-text-3">
                                            <h4>Social Media.</h4>
                                            <p class="text mb-0">Our social media marketing team will tag &amp; include your business in our posts whenever possible giving you higher exposure and gobal impressions consistently</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Card Item -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="single-item-3 text-center">
                                        <div class="icon-box"><i class="fas fa-rocket"></i></div>
                                        <div class="single-item-text-3">
                                            <h4>Completely Managed Solution.</h4>
                                            <p class="text mb-0">Our expert content and marketing team will list and optimize all your retreats. So you don't have to worry about SEO and content creation. Not only are the retreat listings free even updates and maintenance is free and completely managed.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature Area -->

    <!-- Download  Area -->
    <section class="download-app-area bg-primary-cu">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Download Content Text -->
                    <div class="download-content section-padding-100 text-center">
                        <h4>List your Retreat Today </h4>
                        <p class="mb-50">Improve sales, visibility and get verified Inquires</p>
                        <div class="button-area">
                            <a class="btn sosa-btn-2" href="javascript:void(0);" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url('list-your-retreats-get-more-sales') }}">List Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-shape-two">
            <img src="{{ asset('public/pro_listing/img/all-img/pattern-4.png') }}" alt="">
        </div>
    </section>
    <!-- Download  Area -->

    <section class="contact-our-area bg-white section-padding-100-0" id="contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="section-title t_center">
                        <h2>Contact us</h2>
                        <p>Feel free to contact us</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <!-- Card Contact Area-->
                    <div class="single-conatct-area mb-100">
                        <!-- Single Contact -->
                        <div class="single-card-contact d-flex">
                            <div class="icon-area">
                                <i class="bx bx-mail-send"></i>
                            </div>
                            <div class="card-content-text">
                                <h5>Email &amp; Whatsapp</h5>
                                <p>zen@balanceboat.com</p>
                                <p class="mb-0">+917800080808</p>
                            </div>
                        </div>

                        <!-- Single Contact 
                        <div class="single-card-contact d-flex">
                            <div class="icon-area">
                                <i class="bx bx-location-plus"></i>
                            </div>
                            <div class="card-content-text">
                                <h5>Our Location</h5>
                                <p>27 Street, Melbourne City</p>
                                <p class="mb-0">example@gmail.com</p>
                            </div>
                        </div>-->

                        <!-- Single Contact 
                        <div class="single-card-contact d-flex">
                            <div class="icon-area">
                                <i class="bx bx-wifi-2"></i>
                            </div>
                            <div class="card-content-text">
                                <h5>Get In Touch</h5>
                                <p>example@gmail.com</p>
                                <p class="mb-0">+8821546854</p>
                            </div>
                        </div>-->
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Contact Form -->
                    <div class="contact_from_area clearfix mb-100">
                        <div class="contact_form">
                            @if(Session::has('flash_message'))
                            <div class="alert alert-success" id="success_fail_info">
                                <em> {!! session('flash_message') !!}</em>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            @endif 
                            @if(Session::has('flash_error_message'))
                            <div class="alert alert-danger"id="success_fail_info">
                                <em> {!! session('flash_error_message') !!}</em>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            @endif 
                            <form action="{{ url('send_pro_listing_contact_us_email') }}" method="post" id="main_contact_form">
                                <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
                                {{ csrf_field() }}
                                <div class="contact_input_area">
                                    <div class="row">
                                        <!-- Form Group -->
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-30 required" name="firstname" id="firstname" placeholder="First Name" />
                                            </div>
                                        </div>
                                        <!-- Form Group -->
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-30" name="lastname" id="lastname" placeholder="Last Name" />
                                            </div>
                                        </div>
                                        <!-- Form Group -->
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control mb-30 required email" name="email" id="email" placeholder="E-mail" />
                                            </div>
                                        </div>
                                        <!-- Form Group -->
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control mb-30 number" name="phone" id="phone" placeholder="Phone Number" />
                                            </div>
                                        </div>
                                        <!-- Form Group -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <textarea name="message" class="form-text-area mb-30 required" id="message" cols="30" rows="6" placeholder="Your Message *"></textarea>
                                            </div>
                                        </div>
                                        <!-- Button -->
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn sosa-btn">Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Copy Right Area -->
    <div class="copy-right-area bg-primary-cu">
        <div class="container">
            <div class="row">
                <!-- Copy Right Content -->
                <div class="col-12 text-center">
                    <div class="copy-right-content">
                        <p>Copyright © BalanceBoat {{ date("Y") }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="requstcontactPopup" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header pb-0">
                    <h4>Contact Us</h4>
                    <button type="button" class="close" data-dismiss="modal" style="text-align:right;font-size:30px;width:auto;"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="frmRequestCall" name="frmRequestCall" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control required" id="name" name="name" required=""  placeholder="Your Name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control required email" id="email" name="email" required=""  placeholder="Email*" />  
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <select name="country_code" id="country_code" class="form-control" style="height:50px;">
                                            <option data-countryCode="IN" value="91" selected>India (+91)</option>
                                    	    <option data-countryCode="US" value="1">USA (+1)</option>
                                    		<option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                    		<option data-countryCode="AD" value="376">Andorra (+376)</option>
                                    		<option data-countryCode="AO" value="244">Angola (+244)</option>
                                    		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                    		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                    		<option data-countryCode="AR" value="54">Argentina (+54)</option>
                                    		<option data-countryCode="AM" value="374">Armenia (+374)</option>
                                    		<option data-countryCode="AW" value="297">Aruba (+297)</option>
                                    		<option data-countryCode="AU" value="61">Australia (+61)</option>
                                    		<option data-countryCode="AT" value="43">Austria (+43)</option>
                                    		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                    		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                    		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                    		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                    		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                    		<option data-countryCode="BY" value="375">Belarus (+375)</option>
                                    		<option data-countryCode="BE" value="32">Belgium (+32)</option>
                                    		<option data-countryCode="BZ" value="501">Belize (+501)</option>
                                    		<option data-countryCode="BJ" value="229">Benin (+229)</option>
                                    		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                    		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                    		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                    		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                    		<option data-countryCode="BW" value="267">Botswana (+267)</option>
                                    		<option data-countryCode="BR" value="55">Brazil (+55)</option>
                                    		<option data-countryCode="BN" value="673">Brunei (+673)</option>
                                    		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                    		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                    		<option data-countryCode="BI" value="257">Burundi (+257)</option>
                                    		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                    		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                    		<option data-countryCode="CA" value="1">Canada (+1)</option>
                                    		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                    		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                    		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                    		<option data-countryCode="CL" value="56">Chile (+56)</option>
                                    		<option data-countryCode="CN" value="86">China (+86)</option>
                                    		<option data-countryCode="CO" value="57">Colombia (+57)</option>
                                    		<option data-countryCode="KM" value="269">Comoros (+269)</option>
                                    		<option data-countryCode="CG" value="242">Congo (+242)</option>
                                    		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                    		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                    		<option data-countryCode="HR" value="385">Croatia (+385)</option>
                                    		<option data-countryCode="CU" value="53">Cuba (+53)</option>
                                    		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                    		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                    		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                    		<option data-countryCode="DK" value="45">Denmark (+45)</option>
                                    		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                    		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                    		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                    		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                    		<option data-countryCode="EG" value="20">Egypt (+20)</option>
                                    		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                    		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                    		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                    		<option data-countryCode="EE" value="372">Estonia (+372)</option>
                                    		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                    		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                    		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                    		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                    		<option data-countryCode="FI" value="358">Finland (+358)</option>
                                    		<option data-countryCode="FR" value="33">France (+33)</option>
                                    		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                    		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                    		<option data-countryCode="GA" value="241">Gabon (+241)</option>
                                    		<option data-countryCode="GM" value="220">Gambia (+220)</option>
                                    		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                    		<option data-countryCode="DE" value="49">Germany (+49)</option>
                                    		<option data-countryCode="GH" value="233">Ghana (+233)</option>
                                    		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                    		<option data-countryCode="GR" value="30">Greece (+30)</option>
                                    		<option data-countryCode="GL" value="299">Greenland (+299)</option>
                                    		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                    		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                    		<option data-countryCode="GU" value="671">Guam (+671)</option>
                                    		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                    		<option data-countryCode="GN" value="224">Guinea (+224)</option>
                                    		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                    		<option data-countryCode="GY" value="592">Guyana (+592)</option>
                                    		<option data-countryCode="HT" value="509">Haiti (+509)</option>
                                    		<option data-countryCode="HN" value="504">Honduras (+504)</option>
                                    		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                    		<option data-countryCode="HU" value="36">Hungary (+36)</option>
                                    		<option data-countryCode="IS" value="354">Iceland (+354)</option>
                                    		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                    		<option data-countryCode="IR" value="98">Iran (+98)</option>
                                    		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                    		<option data-countryCode="IE" value="353">Ireland (+353)</option>
                                    		<option data-countryCode="IL" value="972">Israel (+972)</option>
                                    		<option data-countryCode="IT" value="39">Italy (+39)</option>
                                    		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                    		<option data-countryCode="JP" value="81">Japan (+81)</option>
                                    		<option data-countryCode="JO" value="962">Jordan (+962)</option>
                                    		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                    		<option data-countryCode="KE" value="254">Kenya (+254)</option>
                                    		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                    		<option data-countryCode="KP" value="850">Korea North (+850)</option>
                                    		<option data-countryCode="KR" value="82">Korea South (+82)</option>
                                    		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                    		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                    		<option data-countryCode="LA" value="856">Laos (+856)</option>
                                    		<option data-countryCode="LV" value="371">Latvia (+371)</option>
                                    		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                    		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                    		<option data-countryCode="LR" value="231">Liberia (+231)</option>
                                    		<option data-countryCode="LY" value="218">Libya (+218)</option>
                                    		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                    		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                    		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                    		<option data-countryCode="MO" value="853">Macao (+853)</option>
                                    		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                    		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                    		<option data-countryCode="MW" value="265">Malawi (+265)</option>
                                    		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    		<option data-countryCode="MV" value="960">Maldives (+960)</option>
                                    		<option data-countryCode="ML" value="223">Mali (+223)</option>
                                    		<option data-countryCode="MT" value="356">Malta (+356)</option>
                                        		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                        		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                        		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                        		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                        		<option data-countryCode="MX" value="52">Mexico (+52)</option>
                                        		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                        		<option data-countryCode="MD" value="373">Moldova (+373)</option>
                                        		<option data-countryCode="MC" value="377">Monaco (+377)</option>
                                        		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                        		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                        		<option data-countryCode="MA" value="212">Morocco (+212)</option>
                                        		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                        		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                        		<option data-countryCode="NA" value="264">Namibia (+264)</option>
                                        		<option data-countryCode="NR" value="674">Nauru (+674)</option>
                                        		<option data-countryCode="NP" value="977">Nepal (+977)</option>
                                        		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                        		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                        		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                        		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                        		<option data-countryCode="NE" value="227">Niger (+227)</option>
                                        		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                        		<option data-countryCode="NU" value="683">Niue (+683)</option>
                                        		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                        		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                        		<option data-countryCode="NO" value="47">Norway (+47)</option>
                                        		<option data-countryCode="OM" value="968">Oman (+968)</option>
                                        		<option data-countryCode="PW" value="680">Palau (+680)</option>
                                        		<option data-countryCode="PA" value="507">Panama (+507)</option>
                                        		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                        		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                        		<option data-countryCode="PE" value="51">Peru (+51)</option>
                                        		<option data-countryCode="PH" value="63">Philippines (+63)</option>
                                        		<option data-countryCode="PL" value="48">Poland (+48)</option>
                                        		<option data-countryCode="PT" value="351">Portugal (+351)</option>
                                        		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                        		<option data-countryCode="QA" value="974">Qatar (+974)</option>
                                        		<option data-countryCode="RE" value="262">Reunion (+262)</option>
                                        		<option data-countryCode="RO" value="40">Romania (+40)</option>
                                        		<option data-countryCode="RU" value="7">Russia (+7)</option>
                                        		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                        		<option data-countryCode="SM" value="378">San Marino (+378)</option>
                                        		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                        		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                        		<option data-countryCode="SN" value="221">Senegal (+221)</option>
                                        		<option data-countryCode="CS" value="381">Serbia (+381)</option>
                                        		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                        		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                        		<option data-countryCode="SG" value="65">Singapore (+65)</option>
                                        		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                        		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                        		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                        		<option data-countryCode="SO" value="252">Somalia (+252)</option>
                                        		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                        		<option data-countryCode="ES" value="34">Spain (+34)</option>
                                        		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                        		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                        		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                        		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                        		<option data-countryCode="SD" value="249">Sudan (+249)</option>
                                        		<option data-countryCode="SR" value="597">Suriname (+597)</option>
                                        		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                        		<option data-countryCode="SE" value="46">Sweden (+46)</option>
                                        		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                        		<option data-countryCode="SI" value="963">Syria (+963)</option>
                                        		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                        		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                        		<option data-countryCode="TH" value="66">Thailand (+66)</option>
                                        		<option data-countryCode="TG" value="228">Togo (+228)</option>
                                        		<option data-countryCode="TO" value="676">Tonga (+676)</option>
                                        		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                        		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                        		<option data-countryCode="TR" value="90">Turkey (+90)</option>
                                        		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                        		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                        		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                        		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                        		<option data-countryCode="UG" value="256">Uganda (+256)</option>
                                        		<option data-countryCode="GB" value="44">UK (+44)</option>
                                        		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                        		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                        		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                        		<option data-countryCode="US" value="1">USA (+1)</option>
                                        		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                        		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                        		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                        		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                        		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                        		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                        		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                        		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                        		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                        		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                        		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                        		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                        </select>
                                        </div>
                                    </div>  
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Mobile Number*" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="message" name="message" placeholder="Message for us (Optional)..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btnRequestCallForm p-3" style="font-size:14px;">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Contact Area -->

    <!-- JS here -->
    <script src="{{ asset('public/pro_listing/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.nav.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/scrollIt.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/wow.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/slick.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/jquery.animatedheadline.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/date-time.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/bundle.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/vivus.min.js') }}"></script>
    <script src="{{ asset('public/pro_listing/js/svg.animation.js') }}"></script>
    <script src="{{ asset('public/basicfront/js/jquery.validate.min.js') }}"></script>

    <!-- Custom js-->
    <script src="{{ asset('public/pro_listing/js/main.js?v=1') }}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $(".btnRequestCallForm").on("click", function () {
            if ($("#frmRequestCall").valid()) {
                $(".btnRequestCallForm").attr("disabled", true);
                $("#frmRequestCall .alert-danger, #frmRequestCall .alert-success").remove();
                $.ajax({
                    url: "{{ url('/send-request-call-back') }}",
                    method: "post",
                    data: $("#frmRequestCall").serialize()+"&_token={{ csrf_token() }}",
                    success: function (result) {
                        if (result.success) {
                            $("#frmRequestCall")[0].reset();
                            $("#frmRequestCall").after('<div class="alert alert-success" align="left">'+(result.success)+'</div>');
                        } else if (result.errors) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function (key, value) {
                                    resultdisp +=  value;
                                });
                            } else {
                                resultdisp = result;
                            }
                            $("#frmRequestCall").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                        } else {
                            $("#frmRequestCall").after(' <div class="alert alert-danger" align="left">Something Went Wrong!</div>');
                        }
                        $(".btnRequestCallForm").attr("disabled", false);
                    }
                });
            }
            return false;
        });
        
        $("#requstcontactPopup").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var inquiry_for = button.data('url') // Extract info from data-* attributes
            $("#requstcontactPopup #inquiry_for").val(inquiry_for);
        });
        $("#requstcontactPopup .close").on("click", function(){
            $("#requstcontactPopup #inquiry_for").val("");
            $("#frmRequestCall")[0].reset();
           $("#requstcontactPopup").hide();
        });
    });
</script>

</body>

</html>