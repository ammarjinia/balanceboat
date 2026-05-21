<!DOCTYPE HTML>
<html lang="{{ config('app.locale')}}">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title') {{ config('app.name') }}</title>
        <meta charset="utf-8">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, follow"/>
        <meta name=keywords content="@yield('keywords')" />
        <meta name="description" content="@yield('description')" />
        <meta itemprop=name content="@yield('meta_title')" />
        <meta itemprop=description content="@yield('description')" />
        <meta itemprop=image content="@yield('image')" />
        <meta name=twitter:card content="summary" />
        <meta name=twitter:title content="@yield('meta_title')" />
        <meta name=twitter:description content="@yield('description')" />
        <meta name=twitter:image:src content="@yield('image')" />
        <meta property=og:title content="@yield('meta_title')" />
        <meta property=og:description content="@yield('description')" />
        <meta property=og:image content="@yield('image')" />
        <meta property="og:image:url" content="@yield('image')" />
        <meta property="og:image:secure_url" content="@yield('image')" />
        <meta property=og:url content="{{ url()->current() }}" />
        <meta property=og:site_name content="{{ url("/") }}" />
        <meta property=og:type content="website" />
        <meta name="yandex-verification" content="58770b522722e36d" />
        <link rel="shortcut icon" type="image/png" href="{{asset('basicfront/img/favicon.ico')}}" />
        <link rel="canonical" href="{!! url()->current() !!}" />
    <!-- Favicon -->

    <!-- CSS
    ============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url('blog_assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/vendor/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/vendor/slick.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/vendor/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/vendor/base.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/plugins/plugins.css') }}">
    <link rel="stylesheet" href="{{ url('blog_assets/css/style.css') }}">
        @include('layouts.ga_code')
</head>

<body>
    <div class="main-wrapper">
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>

        <!-- Start Header -->
        <header class="header axil-header header-style-3  header-light header-sticky ">
            <div class="header-top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="header-top-bar d-flex flex-wrap align-items-center justify-content-center justify-content-md-start">
                                <ul class="header-top-date liststyle d-flex flrx-wrap align-items-center mr--20">
                                    <li><a href="javascript:void(0);">{{ date("d F, Y") }}</a></li>
                                </ul>
                                <ul class="header-top-nav liststyle d-flex flrx-wrap align-items-center">
                                    <li><a href="#">Advertisement</a></li>
                                    <li><a href="#">About</a></li>
                                    <li><a href="#">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <ul class="social-share-transparent md-size justify-content-center justify-content-md-end">
                                <li><a href="https://www.facebook.com/balanceboat" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="https://in.pinterest.com/balanceboat" target="_blank"><i class="fab fa-pinterest"></i></a></li>
                                <li><a href="https://www.instagram.com/thebalanceboat" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-middle">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="logo">
                                <a href="{{ url("/") }}"><h4>Balance<span>Boat</span></h4></a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6">
                            <div class="banner-add text-right">
                                <a href="#">
                                    <img src="{{ url('assets/images/others/add-01.png') }}" alt="" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-bottom">
                <div class="container">
                    <div class="row justify-content-center justify-content-xl-between align-items-center">
                        <div class="col-xl-7 d-none d-xl-block">
                            <div class="mainmenu-wrapper">
                                <nav class="mainmenu-nav">
                                    <!-- Start Mainmanu Nav -->
                                    <ul class="mainmenu">
                                        <li><a href="{{ url("/experiences") }}">Experiences</a></li>
                                        <li><a href="{{ url("/best-deals") }}">Best Deals</a></li>
                                        <li><a href="{{ url("/blog") }}" class="show-submenu">Articles</a></li>
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
                                                
                                    </ul>
                                    <!-- End Mainmanu Nav -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Start Header -->

        <!-- Start Mobile Menu Area  -->
        <div class="popup-mobilemenu-area">
            <div class="inner">
                <div class="mobile-menu-top">
                    <div class="logo">
                        <a href="{{ url("/") }}"><h4>Balance<span>Boat</span></h4></a>
                    </div>
                    <div class="mobile-close">
                        <div class="icon">
                            <i class="fal fa-times"></i>
                        </div>
                    </div>
                </div>
                <ul class="mainmenu">
                    <li><a href="{{ url("/experiences") }}">Experiences</a></li>
                                                <li><a href="{{ url("/blog") }}" class="show-submenu">Articles</a></li>
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
                </ul>
            </div>
        </div>
        <!-- End Mobile Menu Area  -->


          @yield('content')
        <!-- Start Footer Area  -->
        <div class="axil-footer-area axil-footer-style-1 bg-color-white">
            <!-- Start Footer Top Area  -->
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Start Post List  -->
                            <div class="inner d-flex align-items-center flex-wrap">
                                <h5 class="follow-title mb--0 mr--20">Follow Us</h5>
                                <ul class="social-icon color-tertiary md-size justify-content-start">
                                    <li><a href="https://www.facebook.com/balanceboat" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://in.pinterest.com/balanceboat" target="_blank"><i class="fab fa-pinterest"></i></a></li>
                                    <li><a href="https://www.instagram.com/thebalanceboat" target="_blank"><i class="fab fa-instagram"></i></a></li
                                </ul>
                            </div>
                            <!-- End Post List  -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer Top Area  -->

            <!-- Start Copyright Area  -->
            <div class="copyright-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-md-12">
                            <div class="copyright-left">
                                <div class="logo">
                                    <a href="{{ url("/") }}"><h4>Balance<span>Boat</span></h4></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="copyright-right text-left text-lg-right mt_md--20 mt_sm--20">
                                <p class="b3">© {{ date("Y") }} Balanceboat. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Copyright Area  -->
        </div>
        <!-- End Footer Area  -->

        <!-- Start Back To Top  -->
        <a id="backto-top"></a>
        <!-- End Back To Top  -->

    </div>

    <!-- JS
============================================ -->
    <script type="text/javascript">APP_URL = '<?php echo url("/"); ?>';</script>
    <!-- Modernizer JS -->
    <script src="{{ url('blog_assets/js/vendor/modernizr.min.js') }}"></script>
    <!-- jQuery JS -->
    <script src="{{ url('blog_assets/js/vendor/jquery.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ url('blog_assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ url('blog_assets/js/vendor/slick.min.js') }}"></script>
    <script src="{{ url('blog_assets/js/vendor/tweenmax.min.js') }}"></script>
    <script src="{{ url('blog_assets/js/vendor/js.cookie.js') }}"></script>
    <script src="{{ url('blog_assets/js/vendor/jquery.style.switcher.js') }}"></script>


    <!-- Main JS -->
    <script src="{{ url('blog_assets/js/main.js') }}"></script>
    @yield('footer')
    @include('layouts.whatsapp_float')
  @includeIf('layouts.partials.ActiveCampaign')
</body>

</html>