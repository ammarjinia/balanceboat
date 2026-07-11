<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Balanceboat | Retreat & Teacher Training Booking and Marketing website</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name=keywords content="yoga retreat marketing website, Yoga teacher training marketing website, yoga retreats 2020, yoga teacher training 2020" />
        <meta name="description" content="Balanceboat is a booking and marketing website for retreats and professionally organized trainings be it a wellness retreat or yoga teacher trainings in india, bali, costa rica, cambodia, sri lanka &. We offer the best package prices and a variety of yoga retreats and teacher trainings to choose from." />
        <meta itemprop=name content="Balanceboat | Retreat & Teacher Training Booking and Marketing website" />
        <meta itemprop=description content="Balanceboat is a booking and marketing website for retreats and professionally organized trainings be it a wellness retreat or yoga teacher trainings in india, bali, costa rica, cambodia, sri lanka &. We offer the best package prices and a variety of yoga retreats and teacher trainings to choose from." />
        <meta itemprop=image content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta name=twitter:card content="summary" />
        <meta name=twitter:title content="@yield('meta_title','BalanceBoat.com &#x7C; Holiday Retreats and Professional trainings Worldwide')" />
        <meta name=twitter:description content="Balanceboat is a booking and marketing website for retreats and professionally organized trainings be it a wellness retreat or yoga teacher trainings in india, bali, costa rica, cambodia, sri lanka &. We offer the best package prices and a variety of yoga retreats and teacher trainings to choose from." />
        <meta name=twitter:site content="https://twitter.com/book_yoga" />
        <meta name=twitter:image:src content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta property=og:title content="@yield('meta_title','BalanceBoat.com &#x7C; Holiday Retreats and Professional trainings Worldwide')" />
        <meta property=og:description content="Balanceboat is a booking and marketing website for retreats and professionally organized trainings be it a wellness retreat or yoga teacher trainings in india, bali, costa rica, cambodia, sri lanka &. We offer the best package prices and a variety of yoga retreats and teacher trainings to choose from." />
        <meta property=og:image content="@yield('image',asset('basicfront/img/social-share-image-1.jpg'))" />
        <meta property=og:url content="{{ url()->current() }}" />
        <meta property=og:site_name content="{{ url("/") }}" />
        <meta property=og:type content="website" />
        <meta name="csrf-token" content="{{csrf_token()}}">
        <link rel="canonical" href="{!! url()->current() !!}" />
        <link rel="icon" type="image/x-icon" href="{{asset('basicfront/img/favicon.ico')}}">
        <link href="{{asset('basicfront/img/favicon.ico')}}" rel="apple-touch-icon" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="//widget.trustpilot.com" />
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
        <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
        <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
        <link rel="dns-prefetch" href="//widget.trustpilot.com" />
        <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Rubik:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/font-awesome.min.css?v=2') }}" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/icofont.min.css?v=2') }}" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/bootstrap.min.css?v=2') }}" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/gijgo.css?v=2') }}" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/owl.carousel.min.css?v=2') }}" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/slicknav.css?v=2') }}" />
        <link rel="preload" href="{{ asset('basicfront/jquery-ui/jquery-ui.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="{{ asset('basicfront/jquery-ui/jquery-ui.min.css') }}"></noscript>
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/style.css') }}"  />
        <style type="text/css">
            .chat-box {
                align-self: flex-end;
                animation-duration: 5s;
                animation-iteration-count: infinite;
                margin: 0 auto 0 auto;
                transform-origin: bottom;
                position: fixed;
                right: 50px;
                bottom: 50px;
                display: block;
                text-align: center;
            }
            .bounce {
                animation-name: bounce;
                animation-timing-function: ease;
            }
            @keyframes bounce {
                0%   {
                    transform: translateY(0);
                }
                50%  {
                    transform: translateY(-50px);
                }
                100% {
                    transform: translateY(0);
                }
            }

        </style>
        <!-- <link rel="stylesheet" href="css/responsive.css?v=2"> -->
        <script type="text/javascript">
            APP_URL = '<?php echo url("/"); ?>';
        </script>
        @include('layouts.ga_code')
        <!-- TrustBox script -->
        <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
        <!-- End TrustBox script -->
    </head>

    <body>
        <!--[if lte IE 9]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="">upgrade your browser</a> to improve your experience and security.</p>
            <![endif]-->

        <!-- header-start -->
        <header>
            <div class="header-area ">
                <div id="sticky-header" class="main-header-area">
                    <div class="container-fluid">
                        <div class="header_bottom_border">
                            <div class="row align-items-center">
                                <div class="col-xl-2 col-lg-2">
                                    <div class="logo">
                                        <a href="{{ url("/") }}">
                                            <img data-src="{{ asset('basicfront/home/img/balanceboat-header-logo.png') }}" class="lazy" alt="Balanceboat" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xl-10 col-lg-10">
                                    <div class="main-menu  d-none d-lg-block text-right">
                                        <nav>
                                            <ul id="navigation">
                                                <li><a href="{{ url("/experiences") }}">Experiences</a></li>
                                                <li><a href="{{ url('/best-deals') }}">Best Deals</a></li>
                                                <li><a href="{{ url("/blog") }}">Articles</a></li>
                                                <li><a href="{{ url("/contact-us") }}">Contact Us</a></li>
                                                @if (Auth::guest())
                                                <li>
                                                    <a href="{{ url("/login") }}" class="">Login</a>
                                                </li>
                                                @else
                                                <li>
                                                    <a href="{{ url("/myaccount") }}" class="">My Bookings</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url("/logout") }}" class="">Logout</a>
                                                </li>
                                                @endif
                                                <li class="pull-right">
                                                    <a href="#requstcallPopup" data-bs-toggle="modal" style="background: #f36;
                                                       color: #fff;
                                                       display: inline-block;
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
                                                       cursor: pointer;" class="d-none d-lg-block">Request a Call Back</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <a href="#requstcallPopup" title="Request a Call Back" data-bs-toggle="modal" style="background: #f36;color: #fff;padding: 2px 8px;-webkit-border-radius: 8px;-moz-border-radius: 10px;border-radius: 10px;cursor: pointer;position: absolute;top: -48px;right: 60px;" class="d-block d-lg-none"><i class="fa fa-phone"></i></a>
                                    <div class="mobile_menu d-block d-lg-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header-end -->


        <!-- slider_area_start -->
        <div class="slider_area">

            <!-- where_togo_area_start  -->

            <div class="where_togo_area mob">
                <div class="container">
                    <section class="mb-4"><h1 class="text-white font-weight-bold">Soul pampering<br />retreats</h1></section>
                </div>
            </div>

            <div class="where_togo_area mob2">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <section class="mb-4"><h1 class="text-white font-weight-bold">Soul pampering<br />retreats</h1></section>
                            <div class="search_wrap">
                                <form id="frmSearchbar" name="frmSearchbar" action="{{ url("experiences") }}" method="get" class="search_form">
                                    <div class="input_field">								
                                        <input type="text" class="form-control" placeholder="Search Experience" id="ssearch" name="search" style="" />
                                    </div>
                                    <div class="input_field">								
                                        <select class="form-select" id="sdestination" name="sdestination" style="">                        
                                            <option value="">Destinations</option>
                                            @foreach(\App\Http\Helpers\CommonHelper::get_site_destinations() as $destination)
                                            <option value="{{ $destination->id }}" {{ (@$sdest == $destination->id) ? "selected":"" }}>{{ $destination->name }}</option>
                                            @endforeach
                                        </select>                             
                                    </div>
                                    <div class="input_field">								
                                        <select class="form-select" id="scategory" name="scategory" style="">                        
                                            <option value="">Category</option>
                                            @foreach(\App\Http\Helpers\CommonHelper::get_site_categories() as $category)
                                            <option value="{{ $category->id }}" {{ (@$scat == $category->id) ? "selected":"" }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input_field">								
                                        <input id="datepicker" placeholder="Date" class="form-control" />
                                    </div>

                                    <div class="search_btn">								
                                        <button class="boxed-btn4 " type="submit" >Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- where_togo_area_end  -->
            <div class="slider_active owl-carousel">
                <div class="single_slider  d-flex align-items-center slider_bg_1 overlay"></div>
                <div class="single_slider  d-flex align-items-center slider_bg_2 overlay"></div>
                <div class="single_slider  d-flex align-items-center slider_bg_3 overlay"></div>
                <div class="single_slider  d-flex align-items-center slider_bg_4 overlay"></div>
                <div class="single_slider  d-flex align-items-center slider_bg_5 overlay"></div>
            </div>
        </div>
        <!-- slider_area_end -->

        <div class="container pb-4">
            <div class="mt-4 mb-4">
                <div class="section_title text-left">
                    <h3>Experience authentic wellness retreats</h3>
                </div>
                <div>BalanceBoat Bookings has everything you need for a truly authentic Ayurveda, Yoga, Surfing, Diving, Hiking and Weight-Loss retreat experience, with Panchakarma and Ayurveda treatments, top-notch services, and experienced staff. Make your health vacation memorable!</div>
            </div>
            <?php /*<div class="_1ank4ue">
                <d32iv class="_1letgsj">
                    <div>
                        <div class="_9ld1z5">Experience authentic wellness retreats</div>
                    </div>
                    <div>
                        <div class="_xm3x8u">BalanceBoat Bookings has everything you need for a truly authentic Ayurveda, Yoga, Surfing, Diving, Hiking and Weight-Loss retreat experience, with Panchakarma and Ayurveda treatments, top-notch services, and experienced staff. Make your health vacation memorable!</div>			
                    </div>
                </div>
            </div>*/?>


            <div class="gallery">
                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/india/kerala")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" data-original-uri="img/experience/Balanceboat-Kerala-Retreats.png" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" data-original-uri="img/experience/Balanceboat-Kerala-Retreats.png" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_j84k5n">
                        <div class="_138jip5">							
                            <div class="_ford54" title="Kerala"><a href="{{ url("/location/india/kerala")}}">Kerala</a></div>
                        </div>	
                    </div>
                </figure>
                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e" style="padding-top: 100%;">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/india/karnataka")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Karnataka-Retreats.JPG') }}" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_j84k5n">
                        <div class="_138jip5">							
                            <div class="_ford54" title="Bangalore"><a href="{{ url("/location/india/karnataka")}}">Karnataka</a></div>
                        </div>
                    </div>
                </figure>

                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e" style="padding-top: 100%;">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/indonesia")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_j84k5n">
                        <div class="_138jip5">						
                            <div class="_ford54" title="Bali"><a href="{{ url("/location/indonesia")}}">Bali</a></div>
                        </div>
                    </div>
                </figure>

                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e" style="">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/sri-lanka")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_j84k5n">
                        <div class="_138jip5">							
                            <div class="_ford54" title="Sri Lanka"><a href="{{ url("/location/sri-lanka")}}">Sri Lanka</a></div>
                        </div>
                    </div>
                </figure>

                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e" style="padding-top: 100%;">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/thailand")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_j84k5n">
                        <div class="_138jip5">							
                            <div class="_ford54" title="Thailand"><a href="{{ url("/location/thailand")}}">Thailand</a></div>
                        </div>
                    </div>
                </figure>

                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e" style="padding-top: 100%;">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/costa-rica")}}">
                                        <?php /*<picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>*/?>
                                        <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" style="object-fit: cover;" />
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>	
                    <div class="_j84k5n">
                        <div class="_138jip5">							
                            <div class="_ford54" title="Costa Rica"><a href="{{ url("/location/costa-rica")}}">Costa Rica</a></div>
                        </div>
                    </div>
                </figure>

            </div>
        </div>

        <div class="container">
            <div class="popular_places_area bg-white mt-4 mt-md-5 etd">
                <div class="row justify-content-start">
                    <div class="col-lg-12">
                        <div class="section_title text-left mb-3 mb-md-4">
                            <h3>Experience the difference</h3>     
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-award icofont icofont-1x"></i><div class="_1xi9wvy">Award winning</div><div class="_4gelgl">Get best offers from Award winning and certified retreats with best in class amenities with amazing locations</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-diamond icofont icofont-1x"></i><div class="_1xi9wvy">Authentic</div><div class="_4gelgl">Authentic retreats with highly trained & experienced staff that will accommodate all your requests</div></div> 
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-safety icofont icofont-1x"></i><div class="_1xi9wvy">Safe &amp; sanitized</div><div class="_4gelgl">All listed retreats follow strict distancing &amp; sanitization protocols to keep you safe</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-taxi icofont icofont-1x"></i><div class="_1xi9wvy">Effortless arrivals</div><div class="_4gelgl">Private airport pick-up & drop can be arranged to give you a hassle free arrival</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-ebook icofont icofont-1x"></i><div class="_1xi9wvy">Customized itinerary</div><div class="_4gelgl">Our planners will help you customize & plan your itinerary to the very last detail</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-life-bouy icofont icofont-1x"></i><div class="_1xi9wvy">Flexible cancellation</div><div class="_4gelgl">We follow all the cancellation policies of the retreats & are here to help you when you need</div></div>
                    </div>

                </div>	  
            </div>
        </div>


        <section class="container">
            @if((count(@$featured_experiences) > 0) && !empty(@$featured_experiences))
            <div class="popular_places_area bg-white ftco-section ftco-section py-5">
                <div class="justify-content-start">
                    <div class="col-lg-12">
                        <div class="section_title text-left mb_70">
                            <h3>Curated Experiences</h3>     
                        </div>
                    </div>
                </div>
                <div class="budget-retreats owl-carousel">		
                    @foreach (@$featured_experiences as $experience)
                    <div class="single_place">
                        <div class="thumb">
                            <a href="{{ url("/experience/".$experience->slug) }}"><img data-src="{{ Storage::disk('s3')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" style="box-sizing: content-box;" class="lazy" /></a>
                        </div>
                        <div class="place_info">
                            <h3><a href="{{ url("/experience/".$experience->slug) }}">{{ @$experience->location }}</a></h3>
                            <p><a href="{{ url("/experience/".$experience->slug) }}">{{ $experience->name }}</a></p>
                            <div class="rating_days d-flex justify-content-between">
                                <div class="days">
                                    <i class="fa fa-clock-o"></i>
                                    <a href="{{ url("/experience/".$experience->slug) }}">{{ @$experience->duration }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>  
            </div>			
        </div>
        @endif

        <div class="popular_places_area bg-white">
            <div class="pp-destination bg-white">			
                <div class="row justify-content-start">
                    <div class="col-lg-12">
                        <div class="section_title text-left mb_70">
                            <h3>Other popular destinations</h3>     
                        </div>
                    </div>
                </div>
                <div class="row mobile-scrollb-bar">
                    <div class="col-12 col-md-3">
                        <div class="single_place rounded-0 shadow-none">
                            <a href="{{ url("/location/india/goa") }}">
                                <div class="thumb" style="background-image:url({{ asset('basicfront/home/img/destination/goa-balanceboat-popular-destination.JPG')}})"></div>
                                <div class="place_info px-0"><h3>Goa, India</h3></div>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="single_place rounded-0 shadow-none">
                            <a href="{{ url("/location/cambodia") }}">
                                <div class="thumb" style="background-image:url({{ asset('basicfront/home/img/destination/Siem-Reap-balanceboat-desitination.JPG')}})"></div>
                                <div class="place_info px-0"><h3>Siem Reap, Cambodia</h3></div>
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="single_place rounded-0 shadow-none">
                            <a href="{{ url("/location/india/rishikesh") }}">
                                <div class="thumb" style="background-image:url({{ asset('basicfront/home/img/destination/Rishikesh-Balanceboat-Destination.JPG')}})"></div>
                                <div class="place_info px-0"><h3>Rishikesh, India</h3></div>
                            </a>    
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="single_place rounded-0 shadow-none">
                            <a href="{{ url("/location/maldives") }}">
                                <div class="thumb" style="background-image:url({{ asset('basicfront/home/img/destination/maalhos-balanceboat-popular-destination.JPG')}})"></div>
                                <div class="place_info px-0"><h3>Maalhos, Maldives</h3></div>
                            </a>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--    @if(@$deal)-->
    <!--    <section class="container">-->
    <!--    <div class="popular_places_area bg-white">-->
    <!--        <div class="pp-destination bg-white">			-->
    <!--            <div class="row justify-content-start">-->
    <!--                <div class="col-lg-12">-->
    <!--                    <div class="section_title text-left mb_70">-->
    <!--                        <h3>Deals</h3>     -->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="row mobile-scrollb-bar">-->
    <!--                @foreach(@$deal as $deal_d)-->
    <!--                <div class="col-12 col-md-3">-->
    <!--                    <div class="single_place rounded-0 shadow-none">-->
    <!--                        <a href="{{ url("/deal/".@$deal_d['slug']) }}">-->
    <?php
    //                             $imagegalleries = \App\ExperienceImageGallery::where("experience_id", @$experience_d['id'])->get();
    ?>
    <!--                            @if(@$imagegalleries)-->
    <!--<div class="thumb"><img src="{{ Storage::disk('s3')->url(rawurlencode(@$imagegalleries['image_url'])) }}" /></div>-->
    <!--                            @endif-->
    <!--                            <div class="place_info px-0"><h3>{{ @$deal_d['name'] }}</h3></div>-->
    <!--                        </a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                @endforeach-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    <!--@endif-->

    @if(count(@$featured_countries) > 0 && !empty(@$featured_countries))
    <section class="ftco-section pt-5 pb-2 py-md-5">
        <div class="container-950">
            <div class="col-12 justify-content-center pb-4">
                <div class="section_title text-center mb_70">
                    <h3>Designed &amp; Customized for you</h3>
                    <p>When you book an experience via BalanceBoat a dedicated experience planner is there to help you customize and craft your experience </p>
                </div>
            </div>
            <div class="col-12">
                <div class="mobile-scrollb-bar row">
                    {{-- Loop thru categories type=0 and display_on_home=1 --}}
                    @foreach ($featured_countries as $val_catesDestinations_top)
                    <div class="col-md-4 ftco-animate">
                        <div class="project-destination">
                            <a href="{{ url("/location/".$val_catesDestinations_top->slug) }}" class="img" style="background-image: url({{ Storage::disk('s3')->url(rawurlencode($val_catesDestinations_top->image_url)) }})">
                            </a>
                            <div class="text">
                                <a href="{{ url("/location/".$val_catesDestinations_top->slug) }}"><span>{{ $val_catesDestinations_top->name }}</span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
    <div class="video_area">       
        <video controlslist="nodownload" preload="none" class="_1kffseb" style="object-fit: cover; width: 100%; height: 480px;" controls="" class="lazy">
            <source type="video/mp4" src="{{ Storage::disk('s3')->url('video/Balanceboat.mp4') }}" ></video>
    </div>
    <footer>
        <div class="container">
            <div class="_1gw6tte">
                <div class="my-4">
                    <div class="_f9hxdp">
                        <div class="_1m9vrfa">
                            <div data-role="heading" aria-level="2" class="_72ul4s">Need help?</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><i class="icofont-location-pin"></i> 68 Maqbara Road, Hazrathganj,<br> Lucknow - 226001, India</div>
                                <div class="_edda6d"><a href="tel://+917800080808" id="phone"><i class="icofont-ui-call"></i> +91-7800080808</a></div>
                                <div class="_edda6d"><a href="mailto:zen@balanceboat.com" id="email_footer"><i class="icofont-envelope"></i> zen@balanceboat.com</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div data-role="heading" aria-level="2" class="_72ul4s">About</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><a href="{{ url("/about-us") }}">About BalanceBoat</a></div>
                                <div class="_edda6d"><a href="{{ url("/contact-us#contact_us") }}">Contact Us</a></div>
                                <div class="_edda6d"><a href="{{ url("/contact-us") }}">List on BalanceBoat</a></div>
                                <div class="_edda6d"><a href="{{ url("/terms-and-conditions") }}">Terms and condition</a></div>
                                <div class="_edda6d"><a href="{{ url("/privacy-policy") }}">Privacy Policy</a></div>
                                <div class="_edda6d"><a href="{{ url("/cookie-policy") }}">Cookie Policy</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div data-role="heading" aria-level="2" class="_72ul4s">Discover</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><a href="{{ url("/category/yoga") }}">Yoga</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/ayurveda") }}">Ayurveda</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/meditation") }}">Meditation</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/yoga-teacher-training") }}">Yoga Teacher Training</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div data-role="heading" aria-level="2" class="_72ul4s">Currency</div>
                            <div class="_1iz3pi3">
                                <form id="frmGlobalCurrency" method="get" class="search_form" action="#">
                                    {{ csrf_field() }}
                                    <div class="input_field">	
                                        <select class="global_site_currency form-select" name="global_site_currency" id="global_site_currency">
                                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                            <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                                            @endforeach                                    
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="_1iz3pi3 mt-3">
                                <img alt="Cards" class="cards img-responsive lazy" data-src="{{ asset('basicfront/img/cards.png') }}" style="display: block;">
                            </div>
                            <!-- TrustBox widget - Micro Review Count -->
                            <div class="trustpilot-widget mt-3" data-locale="en-US" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="6050872e00c5e50001df4be1" data-style-height="24px" data-style-width="100%" data-theme="light" data-min-review-count="10" data-style-alignment="center">
                              <a href="https://www.trustpilot.com/review/balanceboat.com" target="_blank" rel="noopener">Trustpilot</a>
                            </div>
                            <!-- End TrustBox widget -->
                        </div>								
                    </div>
                </div>
            </div>
        </div>		
    </footer>
    <footer class="footer">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-lg-4 ">
                        <div class="footer_widget">
                            <h3 class="footer_title">Yoga Teacher Training</h3>
                            <ul class="links">
                                <li><a href="{{ url("/location/india?category=yoga-teacher-training") }}">Yoga Teacher Training in India</a></li>
                                <li><a href="{{ url("/location/thailand?category=yoga-teacher-training") }}">Yoga Teacher Training in Thailand</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="footer_widget">
                            <h3 class="footer_title">Yoga &amp; Wellness Retreats</h3>
                            <ul class="links">
                                <li><a href="{{ url("/location/maldives?category=yoga") }}">Yoga Retreats in Maldives</a></li>
                                <li><a href="{{ url("/location/maldives?category=yoga-diving") }}">Yoga & Wellness Retreats in Thailand</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="footer_widget">
                            <h3 class="footer_title">Ayurveda Experiences</h3>
                            <ul class="links">
                                <li><a href="{{ url("/category/ayurveda/ayurveda-massage-training") }}">Ayurveda Massage Training</a></li>
                                <li><a href="{{ url("/category/ayurveda/ayurvedic-panchakarma") }}">Ayurvedic Panchakarma</a></li>
                                <li><a href="{{ url("/category/ayurveda/ayurvedic-rejuvenation") }}">Ayurvedic Rejuvenation</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="footer_widget">
                            <h3 class="footer_title">Partner Websites</h3>
                            <ul class="links">
                                <li><a href="http://balancegurus.com/">BalanceGurus - World's Largest Wellness Listing Website</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right_text">
            <div class="container">
                <div class="footer_border"></div>
                <div class="row">
                    <div class="col-xl-12">
                        <div id="social_footer">
                            <ul>
                                <li><a href="https://www.facebook.com/balanceboat"><i class="icofont-facebook"></i></a></li>
                                <li><a href="https://www.pinterest.com/balanceboat"><i class="icofont-pinterest"></i></a></li>
                                <li><a href="https://www.instagram.com/thebalanceboat"><i class="icofont-instagram"></i></a></li>
                            </ul>                         
                        </div>
                        <p class="copy_right text-center">©{{ date("Y") }} balanceboat.com</p>
                    </div>
                </div>
            </div>
        </div>
        <?php /*<a href="#chatPopup" data-bs-toggle="modal" class="chat-box bounce">
            <span style="font-weight: bold;color: black;text-shadow: 0px 0px white;">Ask Me</span>
            <img alt="Ask Me" data-src="<?php echo url('basicfront/img/ask-chat.png');?>" style="height: 75px; width: auto;border-radius: 50%; background: rgba(255, 255, 255, 0.3); display: block;" class="lazy" />
        </a>*/?>
    </footer>

    <div id="chatPopup" class="modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Ask BalanceBoat
                        <p>Got a question about retreats or treatment?</p>
                    </h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <ul>
                                <li style="margin-bottom:15px;display:flex;"><i class="fa fa-2x fa-question" style="
                                                                                /*background: #EAE6FF;*/
                                                                                color: #8777D9;
                                                                                padding: 10px;
                                                                                border-radius: 10%;
                                                                                margin-right: 10px;width:52px;text-align:center;flex:none;
                                                                                "></i> Not able to find the necessary retreats? Got a question about a retreat, treatment or pricing?</li>
                                <li style="margin-bottom:15px;display:flex;"><i class="fa fa-2x fa-comments" style="
                                                                                /*background: #D4FAE6;*/
                                                                                color: #19B24D;
                                                                                padding: 10px;
                                                                                border-radius: 10%;
                                                                                margin-right: 10px;width:52px;text-align:center;flex:none;
                                                                                "></i> You may ask any questions - anytime you want.</li>
                                <li style="margin-bottom:15px;display:flex;"><i class="fa fa-clock-o fa-2x" style="
                                                                                /*background: #D0EEF2;*/
                                                                                color: #00B8D9;
                                                                                padding: 10px;
                                                                                border-radius: 10%;
                                                                                margin-right: 10px;width:52px;text-align:center;flex:none;
                                                                                "></i> Our team of consultants will help you answer your questions which will help you accelerate your decisions.</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <form id="frmChat" name="frmChat" method="post">
                                @honeypot
                                @csrf
                                <input type="hidden" name="ref_url" value="{{ url()->current() }}" />
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" required=""  placeholder="Name" />
                                </div>
                                <div class="form-group mt-2">
                                    <input type="email" class="form-control required email" name="email" required=""  placeholder="Email Address" />
                                </div>
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <select name="country_code" class="form-select pe-0">
                                                <option data-countryCode="IN" value="91" Selected>India (+91)</option>
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
                                        <div class="col-8">
                                            <input type="number" class="form-control required" name="phone" required="" maxlength="10" placeholder="Phone" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <textarea class="form-control" name="message" placeholder="Enter your message..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btnChatForm mt-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Qualify Form Modal Component -->
    @include('components.qualify_form_modal')

    <!-- JS here -->
    <script src="{{asset('basicfront/home/js/vendor/jquery-3.6.1.min.js') }}"></script>	
    <script defer src="{{asset('basicfront/home/js/vendor/modernizr-3.5.0.min.js?v=2') }}"></script>
    <script defer src="{{asset('basicfront/home/js/popper.min.js?v=2') }}"></script>
    <script defer src="{{asset('basicfront/home/js/bootstrap.min.js?v=2') }}"></script>
    <script defer src="{{asset('basicfront/home/js/owl.carousel.min.js?v=2') }}"></script>
    <script defer src="{{asset('basicfront/home/js/jquery.slicknav.min.js?v=2') }}"></script>
    <script defer src="{{asset('basicfront/home/js/gijgo.min.js?v=2') }}"></script>
    <script defer src="{{ asset('basicfront/js/jquery.lazy.min.js?v=2') }}"></script>
    <!--contact js-->
    <script defer src="{{asset('basicfront/js/jquery.validate.min.js?v=2') }}"></script>
    <script defer src="{{ asset('basicfront/jquery-ui/jquery-ui.min.js?v=1') }}"></script>
    <script defer src="{{asset('basicfront/home/js/main.js?v=2.1') }}"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js?v=2" crossorigin="anonymous"></script>
    <script type="text/javascript">
            $(document).ready(function () {

                $(".lazy").lazy();

                $.Lazy('av', ['audio', 'video'], function (element, response) {
                    // this plugin will automatically handle '<audio>' and '<video> elements,
                    // even when no 'data-loader' attribute was set on the elements
                });

                $('#datepicker').datepicker({
                    iconsLibrary: 'fontawesome',
                    icons: {
                        rightIcon: '<span class="fa fa-caret-down"></span>'
                    }
                });

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
                                            resultdisp += value;
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
                if (!$.cookie('chat-popup')) {

                    setTimeout(function () {
                        $("#chatPopup").show();
                    }, 10000);

                    var date = new Date();
                    date.setTime(date.getTime() + (190 * 1000));

                    $.cookie('chat-popup', 'yes', {expires: date, path: '/'});
                }
                $("#chatPopup .close").on("click", function () {
                    $("#chatPopup").hide();
                });

                // Old form handler removed - now using new qualify_form_modal component

                $("#ssearch").autocomplete({
                    source: function (request, response) {
                        // Fetch data
                        $.ajax({
                            url: APP_URL + "/search-auto-experiences",
                            type: 'GET',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    select: function (event, ui) {
                        // Set selection
                        $('#ssearch').val(ui.item.label); // display the selected text
                        //$('#listing_search_id').val(ui.item.value); // save selected id to input
                        return false;
                    }
                });
            });
    </script>
  
 {!! NoCaptcha::renderJs() !!}
  @include('layouts.whatsapp_float')
  @includeIf('layouts.partials.ActiveCampaign')
</body>
</html>