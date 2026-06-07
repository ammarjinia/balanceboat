<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"> <![endif]-->
<!--[if IE 9]><html class="ie ie9"> <![endif]-->
<html lang="{{ config('app.locale')}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name=keywords content="@yield('keywords','yoga on the beach, yoga holiday, yoga retreats, yoga teacher training, YTT, 200hrs YTT, 300hrs YTT, yoga vacation, yoga teacher training goa, yoga teacher training dharamsala, yoga teacher training bali, yoga teacher training rishikesh, maldives yoga retreat, yoga and diving holiday, yoga retreat in maldives, yoga retreat in goa, Ayurvedic massage training, yoga therapy teacher training')" />
        <meta name="description" content="@yield('description','Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness')" />
        <meta itemprop=name content="@yield('meta_title','BalanceBoat.com &#x7C; Holiday Retreats and Professional trainings Worldwide')" />
        <meta itemprop=description content="@yield('description','Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness')" />
        <meta itemprop=image content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta name=twitter:card content="summary" />
        <meta name=twitter:title content="@yield('meta_title','BalanceBoat.com &#x7C; Holiday Retreats and Professional trainings Worldwide')" />
        <meta name=twitter:description content="@yield('description', 'Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness')" />
        <meta name=twitter:site content="https://twitter.com/book_yoga" />
        <meta name=twitter:image:src content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta property=og:title content="@yield('meta_title','BalanceBoat.com &#x7C; Holiday Retreats and Professional trainings Worldwide')" />
        <meta property=og:description content="@yield('description', 'Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness')" />
        <meta property=og:image content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta property=og:url content="{{ url()->current() }}" />
        <meta property=og:site_name content="{{ url("/") }}" />
        <meta property=og:type content="website" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}">
        <link rel="icon" type="image/x-icon" href="{{asset('basicfront/img/favicon.ico')}}">
        <link href="{{asset('basicfront/img/favicon.ico')}}" rel="apple-touch-icon" />
        <title>@yield('title', "Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness")</title>
        <link rel="preconnect" href="https://balanceboatblob.blob.core.windows.net" />
        <link rel="preconnect" href="https://cdnbbendpoint.azureedge.net" />
        <link rel="preconnect" href="https://www.googletagmanager.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
        <link rel="dns-prefetch" href="https://balanceboatblob.blob.core.windows.net" />
        <link rel="dns-prefetch" href="https://cdnbbendpoint.azureedge.net" />
        <link rel="dns-prefetch" href="https://www.googletagmanager.com" />
        <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
        <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
        <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
        <link rel="canonical" href="{!! strtolower(url()->current()) !!}" />
        <!--<link href="https://static.balanceboat.com/basicfront/css/critical.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/bootstrap.min.css" rel="stylesheet" media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/animate.min.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/style.css" rel="stylesheet" media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/menu.min.css" rel="stylesheet" defer media="all"/>
        <link href="https://static.balanceboat.com/basicfront/css/responsive.min.css" rel="stylesheet" media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/fontello/css/all-fontello.min.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/new_icons/css/icon_set_all.min.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/magnific-popup.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/css/pop_up.css" rel="stylesheet" defer media="all" />
        <link href="https://static.balanceboat.com/basicfront/fonts.css" rel="stylesheet" defer media="all"  />
        <link href="https://static.balanceboat.com/basicfront/css/custom.min.css" rel="stylesheet" defer media="all"  />-->

        <!--<link href="{{asset('basicfront/css/critical.css')}}" rel="stylesheet" defer media="all" />
        <link href="{{asset('basicfront/css/bootstrap.min.css')}}" rel="stylesheet" media="all" />
        <link href="{{asset('basicfront/css/animate.min.css')}}" rel="stylesheet" defer media="all" />
        <link href="{{asset('basicfront/css/style.css')}}" rel="stylesheet" media="all" />
        <link href="{{asset('basicfront/css/menu.min.css')}}" rel="stylesheet" defer media="all"/>
        <link href="{{asset('basicfront/css/responsive.min.css')}}" rel="stylesheet" media="all" />-->
        <!-- Google web fonts -->
        <link href="https://fonts.googleapis.com/css?family=Istok+Web:400,700&display=swap" rel="stylesheet">
        <link href="{{asset('basicfront/css/fontello/css/all-fontello.min.css')}}" rel="stylesheet" defer media="all" />
        <link href="{{asset('basicfront/css/new_icons/css/icon_set_all.min.css')}}" rel="stylesheet" defer media="all" />
        <!--<link href="{{asset('basicfront/css/magnific-popup.css')}}" rel="stylesheet" defer media="all" />
        <link href="{{asset('basicfront/css/pop_up.css')}}" rel="stylesheet" defer media="all" />
        <link href="{{asset('basicfront/css/custom.min.css')}}" rel="stylesheet" defer media="all"  />
        <link href="{{asset('basicfront/fonts.css')}}" rel="stylesheet" defer media="all"  />-->
        <link href="{{asset('basicfront/css/merge.css?v=13')}}" rel="stylesheet"  media="all"  />
        <link href="{{asset('basicfront/css/saj-custom.css?v=1.4')}}" rel="stylesheet" defer media="all"  />
        <style type="text/css">
            .chat-box {
                align-self: flex-end;
                animation-duration: 5s;
                animation-iteration-count: infinite;
                margin: 0 auto 0 auto;
                transform-origin: bottom;
                position: fixed;right: 50px;bottom: 50px;display: block;text-align: center;
            }
            .bounce {
                animation-name: bounce;
                animation-timing-function: ease;
            }
            @keyframes bounce {
                0%   { transform: translateY(0); }
                50%  { transform: translateY(-50px); }
                100% { transform: translateY(0); }
            }
            .modal::before {
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: #000;
                z-index: 1062;
                opacity: 1;
                position: fixed;
                content: '';
                opacity: .8;
                z-index: -1;
            }
            @media only screen and (max-width: 991px) {
                .btn-request-call-back {display:none !important;}    
            }
        </style>
        <!-- Google web fonts -->
        <!--link href="https://fonts.google.com/?query=PT+sans&selection.family=PT+Sans|PT+Sans+Narrow" rel="stylesheet"-->
        <!-- CSS -->
        <!--link href="{{asset('basicfront/css/all_merged.css')}}" rel="stylesheet" media="all" /-->            
        <!--link href="{{asset('basicfront/css/base.css')}}" rel="stylesheet" media="all" /-->

        <!--[if lt IE 9]>
              <script src="{{asset('basicfront/js/html5shiv.min.js')}}" defer></script>
              <script src="{{asset('basicfront/js/respond.min.js')}}" defer></script>
            <![endif]-->

        <!-- Common scripts -->
        @yield('head')
        <script type="text/javascript">
            APP_URL = '<?php echo url("/"); ?>';
        </script>
        @include('layouts.ga_code')
    </head>

    <body>
        <!-- Google Tag Manager (noscript) -->
    <!--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHF23B4"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>-->
        <!-- End Google Tag Manager (noscript) -->

        <div class="layer"></div>
        <!-- Mobile menu overlay mask -->

        <!-- Header================================================== -->
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-4">
                        <div id="logo_home">
                            <h2><a href="{{ url("/") }}" title="Balanceboat"></a></h2>
                        </div>
                    </div>
                    <nav class="col-md-9 col-sm-9 col-xs-8">
                        <div id="header_menu"></div>
                        <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
                        <a href="#requstcallPopup" title="Request a Call Back" data-toggle="modal" style="background: #f36;color: #fff;padding: 2px 8px;-webkit-border-radius: 8px;-moz-border-radius: 10px;border-radius: 10px;cursor: pointer;position: absolute;top: 6px;right: 70px;" class="d-block d-lg-none"><i class="icon-phone"></i></a>
                        <div class="main-menu">
                            <div id="header_menu"></div>
                            <a href="#" class="open_close" id="close_in"><i class="icon_set_1_icon-77"></i></a>
                            <ul>
                                <li class="submenu">
                                    <a href="{{ url("/experiences") }}" class="show-submenu">Experiences</a>
                                </li>
                                <li class="submenu">
                                    <a href="{{ url("/best-deals") }}">Best Deals</a>
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
                                <li>
                                    <a href="#requstcallPopup" data-toggle="modal" style="background: #f36;
    color: #fff;
    padding: 10px 20px;
    font-family: poppins,sans-serif;
    font-size: 16px;
    font-weight: 500;
    border: 0;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    text-align: center;
    text-transform: capitalize;
    -webkit-transition: .3s;
    -moz-transition: .3s;
    -o-transition: .3s;
    transition: .3s;
    cursor: pointer;" class="btn-request-call-back">Request a Call Back</a>
                                                </li>
                            </ul>
                        </div>
                        <!-- End main-menu -->
                    </nav>
                </div>
            </div>
            <!-- container -->
        </header>
        <!-- End Header -->
        @yield('banner')
        <main>
            @yield('content')
        </main>
        <div class="cleafix"></div>
        <!-- End main -->
        <footer class="">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-3">
                        <h3>Need help?</h3>
                        <a href="javascript:void(0);" id="contactaddress">68 Maqbara Road, Hazrathganj,<br /> Lucknow - 226001, India</a>
                        <a href="tel://+917800080808" id="phone">+91-7800080808</a>
                        <a href="mailto:zen@balanceboat.com" id="email_footer">zen@balanceboat.com</a>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>About</h3>
                        <ul>
                            <li><a href="{{ url("/about-us") }}">About BalanceBoat</a></li>
                            <li><a href="{{ url("/contact-us#contact_us") }}">Contact Us</a></li>
                            <li><a href="{{ url("/contact-us") }}">List on BalanceBoat</a></li>
                            <li><a href="{{ url("/terms-and-conditions") }}">Terms and condition</a></li>
                            <li><a href="{{ url("/privacy-policy") }}">Privacy Policy</a></li>
                            <li><a href="{{ url("/cookie-policy") }}">Cookie Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>Discover</h3>
                        <ul>
                            <li><a href="{{ url("/category/yoga") }}">Yoga</a></li>
                            <li><a href="{{ url("/category/ayurveda") }}">Ayurveda</a></li>
                            <li><a href="{{ url("/category/meditation") }}">Meditation</a></li>
                            <li><a href="{{ url("/category/yoga-teacher-training") }}">Yoga Teacher Training</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-3">
                        <h3>Currency</h3>
                        <div class="styled-select">
                            <form id="frmGlobalCurrency" method="get">
                                <select class="form-select global_site_currency" name="global_site_currency" id="global_site_currency">
                                    @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                    <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                                    @endforeach                                    
                                </select>
                            </form>
                        </div>
                        <div>
                            <img data-src="{{ asset('basicfront/img/cards.png') }}" alt="Cards" class="cards img-responsive lazy" />
                        </div>
                    </div>
                </div>
                <hr />
                <!-- End row -->
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <h3>Yoga Teacher Training</h3>
                        <ul>
                            <li><a href="{{ url("/location/india?category=yoga-teacher-training") }}">Yoga Teacher Training in India</a></li>
                            <li><a href="{{ url("/location/thailand?category=yoga-teacher-training") }}">Yoga Teacher Training in Thailand</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>Yoga & Wellness Retreats</h3>
                        <ul>
                            <li><a href="{{ url("/location/maldives?category=yoga") }}">Yoga Retreats in Maldives</a></li>
                            <li><a href="{{ url("/location/maldives?category=yoga-diving") }}">Yoga & Wellness Retreats in Thailand</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>Ayurveda Experiences</h3>
                        <ul>
                            <li><a href="{{ url("/category/ayurveda/ayurveda-massage-training") }}">Ayurveda Massage Training</a></li>
                            <li><a href="{{ url("/category/ayurveda/ayurvedic-panchakarma") }}">Ayurvedic Panchakarma</a></li>
                            <li><a href="{{ url("/category/ayurveda/ayurvedic-rejuvenation") }}">Ayurvedic Rejuvenation</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>Partner Websites</h3>
                        <p><a href="http://balancegurus.com/">BalanceGurus - World's Largest Wellness Listing Website</a></p>
                    </div>
                </div>
                <!-- End row -->
                <div class="row">
                    <div class="col-md-12">
                        <div id="social_footer">
                            <ul>
                                <li><a href="https://www.facebook.com/balanceboat"><i class="icon-facebook"></i></a></li>
                                <li><a href="https://www.pinterest.com/balanceboat"><i class="icon-pinterest"></i></a></li>
                                <li><a href="https://www.instagram.com/thebalanceboat"><i class="icon-instagram"></i></a></li>
                            </ul>
                            <p>©2021 balanceboat.com</p>
                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
        <?php /*<a href="#chatPopup" data-toggle="modal" class="chat-box bounce">
            <span style="font-weight: bold;color: black;text-shadow: 0px 0px white;">Ask Me</span>
            <img src="<?php echo url('/public/basicfront/img/ask-chat.png');?>" style="height: 75px; width: auto;border-radius: 50%; background: rgba(255, 255, 255, 0.3); display: block;">
        </a>*/?>
        </footer>
        <!-- End footer -->

        <div id="toTop"></div>
        <!-- Back to top button -->

        <!-- Search Menu -->
        <div class="search-overlay-menu">
            <span class="search-overlay-close"><i class="icon_set_1_icon-77"></i></span>
            <form role="search" id="searchform" method="get">
                <input value="" name="q" type="search" placeholder="Search..." />
                <button type="submit"><i class="icon_set_1_icon-78"></i>
                </button>
            </form>
        </div>
        <!-- Qualify Form Modal Component -->
        @include('components.qualify_form_modal')

        <div id="chatPopup" class="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:1000px;width:auto;">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:0px;">
                    <h3 style="margin-top:0px;display:inline-block;">Ask BalanceBoat<br /> 
                    <p>Got a question about retreats or treatment?</p></h3>
                    <button type="button" class="close" data-dismiss="modal"><i class="icon_close" style="font-size: 1.5em;font-weight: bold;color: #000;"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <ul style="margin:0px;padding:0px;">
                                <li style="margin-bottom:15px;display:flex;"><i class="icon_question" style="width:52px;font-size:2em;color: #8777D9;border-radius: 10%;text-align:center;flex:none;"></i> Not able to find the necessary retreats? Got a question about a retreat, treatment or pricing?</li>
                                <li style="margin-bottom:15px;display:flex;"><i class="icon-comment" style="width:52px;font-size:2em;color: #19B24D;border-radius: 10%;text-align:center;flex:none;"></i> You may ask any questions - anytime you want.</li>
                                <li style="margin-bottom:15px;display:flex;"><i class="icon-clock" style="width:52px;font-size:2em;color: #00B8D9;border-radius: 10%;text-align:center;flex:none;"></i> Our team of consultants will help you answer your questions which will help you accelerate your decisions.</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <form id="frmChat" name="frmChat" method="post">
                                @honeypot
                                @csrf
                                <input type="hidden" name="ref_url" id="ref_url" data-attr="a" value="{{ url()->current() }}" />
                                <div class="form-group">
                                    <input type="email" class="form-control required email" id="email" name="email" required=""  placeholder="Email Address" />
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Phone" />
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="message" name="message" placeholder="Enter your message..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btnChatForm">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- End Search Menu -->
        <!--<script src="{{asset('basicfront/js/jquery-2.2.4.min.js')}}"></script>
                                        <div style="flex: 1; height: 1px; background: rgba(201,147,58,0.28);"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;" id="gRet">
                                        <button type="button" class="tile" data-v="yoga_retreat" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">🧘</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Yoga Retreat</span>
                                        </button>
                                        <button type="button" class="tile" data-v="detox" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">🥗</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Detox Program</span>
                                        </button>
                                        <button type="button" class="tile" data-v="rejuvenation_meditation" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">🕉️</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Rejuvenation &amp; Meditation</span>
                                        </button>
                                        <button type="button" class="tile" data-v="weight_loss" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">⚖️</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Weight Loss</span>
                                        </button>
                                        <button type="button" class="tile" data-v="panchakarma" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">🌺</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Panchakarma</span>
                                        </button>
                                        <button type="button" class="tile" data-v="other_ayurvedic" style="padding: 14px 8px 12px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; color: #9A9080; font-size: 11.5px; cursor: pointer; text-align: center; transition: all 0.2s ease; line-height: 1.3;">
                                            <span style="font-size: 22px; display: block; margin-bottom: 7px;">🌿</span>
                                            <span style="display: block; color: #9A9080; font-size: 11.5px; line-height: 1.35;">Other Ayurvedic</span>
                                        </button>
                                    </div>
                                    <div class="err" id="eRet" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>

                                <div style="margin-bottom: 0;">
                                    <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #C9933A; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                        Preferred destination
                                        <div style="flex: 1; height: 1px; background: rgba(201,147,58,0.28);"></div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;" id="gDest">
                                        <button type="button" class="dest-btn" data-v="india" style="padding: 12px 6px 10px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                            <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇮🇳</span>
                                            <span style="font-size: 11px; color: #9A9080;">India</span>
                                        </button>
                                        <button type="button" class="dest-btn" data-v="thailand" style="padding: 12px 6px 10px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                            <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇹🇭</span>
                                            <span style="font-size: 11px; color: #9A9080;">Thailand</span>
                                        </button>
                                        <button type="button" class="dest-btn" data-v="indonesia" style="padding: 12px 6px 10px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                            <span style="font-size: 20px; display: block; margin-bottom: 5px;">🇮🇩</span>
                                            <span style="font-size: 11px; color: #9A9080;">Indonesia</span>
                                        </button>
                                        <button type="button" class="dest-btn" data-v="open" style="padding: 12px 6px 10px; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; background: #1D1D1D; font-size: 11px; cursor: pointer; text-align: center; transition: all 0.2s ease;">
                                            <span style="font-size: 20px; display: block; margin-bottom: 5px;">🌏</span>
                                            <span style="font-size: 11px; color: #9A9080;">Open to all</span>
                                        </button>
                                    </div>
                                    <div class="err" id="eDest" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>
                            </div>

                            <div class="step" id="s2" style="display: none;">
                                <div style="margin-bottom: 24px;">
                                    <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #C9933A; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                        Budget per day
                                        <div style="flex: 1; height: 1px; background: rgba(201,147,58,0.28);"></div>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="gBudget">
                                        <button type="button" class="pill" data-v="under_3k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Under ₹3,000</button>
                                        <button type="button" class="pill" data-v="3k_5k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹3,000 – 5,000</button>
                                        <button type="button" class="pill" data-v="5k_8k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹5,000 – 8,000</button>
                                        <button type="button" class="pill" data-v="8k_12k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹8,000 – 12,000</button>
                                        <button type="button" class="pill" data-v="12k_18k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹12,000 – 18,000</button>
                                        <button type="button" class="pill" data-v="18k_25k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹18,000 – 25,000 ⭐</button>
                                        <button type="button" class="pill" data-v="25k_40k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹25,000 – 40,000</button>
                                        <button type="button" class="pill" data-v="40k_60k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹40,000 – 60,000</button>
                                        <button type="button" class="pill" data-v="60k_100k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">₹60,000 – 1,00,000</button>
                                        <button type="button" class="pill" data-v="above_100k" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Above ₹1,00,000 👑</button>
                                        <button type="button" class="pill" data-v="flexible" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Flexible / Not sure</button>
                                    </div>
                                    <div class="err" id="eBudget" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>

                                <div style="margin-bottom: 0;">
                                    <div style="font-size: 10px; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: #C9933A; margin-bottom: 11px; display: flex; align-items: center; gap: 10px;">
                                        When are you planning to travel?
                                        <div style="flex: 1; height: 1px; background: rgba(201,147,58,0.28);"></div>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="gTime">
                                        <button type="button" class="pill" data-v="within_2w" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Within 2 weeks 🔥</button>
                                        <button type="button" class="pill" data-v="next_month" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Next month</button>
                                        <button type="button" class="pill" data-v="1_3m" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">1–3 months</button>
                                        <button type="button" class="pill" data-v="3_6m" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">3–6 months</button>
                                        <button type="button" class="pill" data-v="exploring" style="padding: 9px 14px; border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; background: #1D1D1D; color: #9A9080; font-size: 12px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease;">Just exploring</button>
                                    </div>
                                    <div class="err" id="eTime" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>
                            </div>

                            <div class="step" id="s3" style="display: none;">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #6E6659; font-weight: 500; margin-bottom: 8px;">Your Name *</label>
                                    <input type="text" id="iName" name="name" placeholder="Full name" autocomplete="name" style="width: 100%; padding: 13px 16px; background: #0C0C0C; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; color: #EAE3D8; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                    <div class="err" id="eName" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #6E6659; font-weight: 500; margin-bottom: 8px;">Phone Number *</label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" value="+91" disabled style="width: 62px; flex-shrink: 0; text-align: center; color: #6E6659 !important; background: #101010 !important; cursor: not-allowed; border: 1px solid rgba(255,255,255,0.05) !important; padding: 13px 16px; border-radius: 13px; font-size: 15px;">
                                        <input type="tel" id="iPhone" name="phone" placeholder="9876543210" maxlength="10" inputmode="numeric" autocomplete="tel" style="flex: 1; padding: 13px 16px; background: #0C0C0C; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; color: #EAE3D8; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                    </div>
                                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; cursor: pointer; color: #6E6659; font-size: 12px;">
                                        <input type="checkbox" id="iWA" style="width: 14px; height: 14px; accent-color: #C9933A; cursor: pointer;"> This number is on WhatsApp
                                    </label>
                                    <div class="err" id="ePhone" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #6E6659; font-weight: 500; margin-bottom: 8px;">Email Address *</label>
                                    <input type="email" id="iEmail" name="email" placeholder="you@email.com" autocomplete="email" style="width: 100%; padding: 13px 16px; background: #0C0C0C; border: 1px solid rgba(255,255,255,0.07); border-radius: 13px; color: #EAE3D8; font-size: 15px; outline: none; transition: border-color 0.22s;">
                                    <div class="err" id="eEmail" style="color: #D05858; font-size: 11px; margin-top: 6px; min-height: 14px;"></div>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px; margin-top: 24px;">
                                <button type="button" class="btn-back" id="backBtn" onclick="goBack()" style="display: none; flex: 0 0 48px; background: transparent; border: 1px solid rgba(255,255,255,0.09); color: #6E6659; font-size: 18px; padding: 15px 18px; border-radius: 13px; cursor: pointer; transition: all 0.22s ease;">←</button>
                                <button type="button" class="btn-cta" id="nextBtn" onclick="goNext()" style="flex: 1; padding: 15px 18px; border-radius: 13px; background: linear-gradient(135deg, #C9933A 0%, #7A4F14 100%); color: #0A0A0A; font-weight: 600; letter-spacing: 0.015em; border: none; font-size: 14px; cursor: pointer; transition: all 0.22s ease; box-shadow: 0 6px 22px rgba(201,147,58,0.28);">Continue →</button>
                            </div>

                            <div style="display: flex; justify-content: center; gap: 16px; margin-top: 18px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #6E6659;">🔒 100% private</div>
                                <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #6E6659;">✅ Completely free</div>
                                <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #6E6659;">⚡ Response in 2 hrs</div>
                                <div style="display: flex; align-items: center; gap: 5px; font-size: 10.5px; color: #6E6659;">🌿 Expert curated</div>
                            </div>

                            <input type="hidden" name="ref_url" value="{{ url()->current() }}" />
                            <input type="hidden" id="hRetreatType" name="retreat_type" />
                            <input type="hidden" id="hDestination" name="destination" />
                            <input type="hidden" id="hBudget" name="budget" />
                            <input type="hidden" id="hTimeline" name="timeline" />
                            <input type="hidden" id="hWhatsapp" name="whatsapp" value="0" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

            document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('#' + gridId + ' button[data-v]').forEach(e => e.classList.remove('on'));
                    el.classList.add('on');
                    el.style.borderColor = '#C9933A';
                    el.style.background = 'rgba(201,147,58,0.14)';
                    const lbl = el.querySelector('.lbl') || el.querySelector('.dlbl') || el.querySelector('span:last-child');
                    if (lbl) lbl.style.color = '#E2B86A';
                    
                    qualifyD[key] = el.dataset.v;
                    if (errId) document.getElementById(errId).textContent = '';
                });
            });
        }

        <script src="{{asset('basicfront/js/jquery-2.2.4.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('basicfront/js/bootstrap-datepicker.js') }}" defer></script>
        <script type="text/javascript" src="{{ asset('basicfront/js/jquery.lazy.min.js') }}"  defer></script>
        <script src="{{asset('basicfront/js/common_scripts_min.js')}}"  defer></script>
        <script src="{{asset('basicfront/js/functions.min.js')}}"  defer></script>-->
        
        <script src="{{asset('basicfront/js/merge.js?v=2')}}" ></script><!-- Scripts -->
        <script src="{{asset('basicfront/js/jquery.validate.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" crossorigin="anonymous"></script>
        @yield('footer')
        <!-- Start of HubSpot Embed Code -->
        <?php /*
          <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/4379104.js"></script>
         */ ?>
         
         <script type="text/javascript">
    $(document).ready(function () {

        $(".btnChatForm").on("click", function () {
            if ($("#frmChat").valid()) {
                $(".btnChatForm").attr("disabled", true);
                $("#frmChat .alert-danger, #frmChat .alert-success").remove();
                $.ajax({
                    url: "{{ url('/store-chat') }}",
                    method: "post",
                    data: $("#frmChat").serialize(),
                    success: function (result) {
                        if (result) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function (key, value) {
                                    resultdisp +=  value;
                                });
                            } else {
                                resultdisp = result;
                            }
                            $("#frmChat").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                        } else {
                            $("#frmChat")[0].reset();
                            $("#frmChat").after('<div class="alert alert-success" align="left">Our wellness expert has just sent you an email</div>');
                        }
                        $(".btnChatForm").attr("disabled", false);
                    }
                });
            }
            return false;
        });
        
        @if (Request::path() != 'find-your-retreat')
        if (!$.cookie('chat-popup')) {
            setTimeout(function(){ $("#chatPopup").show(); }, 10000);
        
            var date = new Date();
            date.setTime(date.getTime() + (190 * 1000));
            
            //$.cookie('chat-popup', 'yes', { expires: date, path: '/' }); 
        
            $.cookie('chat-popup', 'yes', {path: '/' }); 
        }
        $("#chatPopup .close").on("click", function(){
           $("#chatPopup").hide();
        });
        @endif
        
        
        $(".btnRequestCallForm").on("click", function () {
            if ($("#frmRequestCall").valid()) {
                $(".btnRequestCallForm").attr("disabled", true);
                $("#frmRequestCall .alert-danger, #frmRequestCall .alert-success").remove();
                $.ajax({
                    url: "{{ url('/send-request-call-back') }}",
                    method: "post",
                    data: $("#frmRequestCall").serialize(),
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
        
        $("#requstcallPopup .close").on("click", function(){
           $("#requstcallPopup").hide();
        });
    });
</script>
  
 {!! NoCaptcha::renderJs() !!}
@include('layouts.whatsapp_float')
@includeIf('layouts.partials.ActiveCampaign')
    </body>
</html>