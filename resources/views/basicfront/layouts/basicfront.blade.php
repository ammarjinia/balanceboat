<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"> <![endif]-->
<!--[if IE 9]><html class="ie ie9"> <![endif]-->
<html lang="{{ config('app.locale')}}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="Citytours - Premium site template for city tours agencies, transfers and tickets.">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}">

        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('public/basicfront/assets/img/favicon.png')}}">
        <title>@yield('title') | {{config('app.name', 'Balanceboat')}}</title>

        <!-- Google web fonts -->
        <!--        <link href="https://fonts.googleapis.com/css?family=Gochi+Hand|Lato:300,400|Montserrat:400,400i,700,700i" rel="stylesheet">-->
        <link href="https://fonts.google.com/?query=PT+sans&selection.family=PT+Sans|PT+Sans+Narrow" rel="stylesheet">

        <!-- CSS -->
        <link href="{{asset('public/basicfront/css/base.css')}}" rel="stylesheet">


        <!-- Radio and check inputs -->
        <link href="{{asset('public/basicfront/css/skins/square/grey.css')}}" rel="stylesheet">

        <!-- SPECIFIC CSS -->
        <link href="{{asset('public/basicfront/css/shop.css')}}" rel="stylesheet">

        <!-- CSS -->
        <link href="{{asset('public/basicfront/css/blog.css')}}" rel="stylesheet">

        <!--[if lt IE 9]>
              <script src="{{asset('public/basicfront/js/html5shiv.min.js')}}"></script>
              <script src="{{asset('public/basicfront/js/respond.min.js')}}"></script>
            <![endif]-->
        @yield('head')
    </head>

    <body>
        <div id="preloader">
            <div class="sk-spinner sk-spinner-wave">
                <div class="sk-rect1"></div>
                <div class="sk-rect2"></div>
                <div class="sk-rect3"></div>
                <div class="sk-rect4"></div>
                <div class="sk-rect5"></div>
            </div>
        </div>
        <!-- End Preload -->

        <div class="layer"></div>
        <!-- Mobile menu overlay mask -->

        <!-- Header================================================== -->
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div id="logo_home">
                            <h1><a href="index.html" title="City tours travel template"></a></h1>
                        </div>
                    </div>
                    <nav class="col-md-8 col-sm-8 col-xs-8">
                        <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
                        <div class="main-menu">

                            <a href="#" class="open_close" id="close_in"><i class="icon_set_1_icon-77"></i></a>
                            <ul id="top_tools">
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="show-submenu">List of BalanceBoat</a>

                                </li>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="show-submenu">Signup</a>

                                </li>

                                <li class="submenu">
                                    <a href="javascript:void(0);" class="">Login </a>

                                </li>

                                <li class="submenu">
                                    <a href="javascript:void(0);" class="show-submenu">Help</a>

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
        <section id="hero">
            <div class="intro_title">
                <h3 class="animated fadeInDown">EXPERIENCE</h3>
                <p class="animated fadeInDown">The best of wellness, spirituality &amp; holistic health around the world </p>
            </div>

            <div id="search_bar_container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-xs-6 form-group top_search">
                            <i class="icon-location-1"></i>
                            <select class="form-control ">

                                <option><i class="icon-location-1"></i>Destinations</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-xs-6 form-group top_search">
                            <i class=" icon-magic"></i>
                            <select class="form-control">
                                <option>Experiences</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-xs-6 form-group top_search">
                            <i class="icon-calendar"></i>
                            <select class="form-control">
                                <option>Arrival Date</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-6 form-group">
                            <button class="button_intro">GO</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /search_bar-->
        </section>
        <!-- End hero -->


        <main>
            @if(count($categories) > 1 && !empty($categories))
            <section class="container padding_t_30 margin_b_30">

                <div class="main_title">
                    <h2>Experiences Categories</h2>

                </div>

                <div class="row">
                    {{-- Loop thru categories type=0 and display_on_home=1 --}}     
                    @foreach ($categories as $val_cates_top)
                    <div class="col-md-4 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
                        <div class="tour_container category_hover">

                            <div class="img_container">
                                <a href="#">
                                    @if($val_cates_top->image_url)
                                    <img src="{{ Storage::disk('azure')->url($val_cates_top->image_url) }}" class="img-responsive" alt="{{ $val_cates_top->image_title }}">
                                    @endif
                                    <div class="short_info"> {{ $val_cates_top->name }} </div>
                                </a>
                            </div>

                        </div>
                        <!-- End box tour -->
                    </div>
                    <!-- End col-md-4 -->
                    @endforeach




                </div>
                <!-- End row -->


            </section>
            <!-- End section -->
            @endif
            <section class="white_bg">
                <div class="container margin_60">
                    <div class="main_title">
                        <h2>Popular Experiences</h2>
                        <p><i>Checkout some of the most popular experiences on BalanceBoat</i></p>
                    </div>

                    <div class="row  ">
                        <div class="col-lg-12 col-md-12">
                            {{-- Loop thru experiences --}}                            
                            @foreach (@$experiences as $val_experiences) 
                            <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="img_list">
                                            <a href="javascript:void(0);">
                                                <img src="{{ Storage::disk('azure')->url($val_experiences->banner_image_url) }}" alt="{{ $val_experiences->banner_image_title }}"> 
                                            </a>
                                        </div>
                                    </div>
                                    <div class="clearfix visible-xs-block"></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="tour_list_desc">

                                            <h3>{{ $val_experiences->name }}</h3>
                                            <p><i class="fa fa-calender"></i> {{ $val_experiences->name }}</p>

                                            <p><?php echo $val_experiences->experience_overview; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="price_list">
                                            <div>
                                                <p><span class="normal_price_list">From: <br><br></span></p>
                                                <p> &#x20B9; {{ $val_experiences->price_per_person }}</p>
                                                <p>
                                                    <span class="normal_price_list">
                                                        For: <br> <h5>{{ date("F j, Y",strtotime($val_experiences->date_time)) }}</h5>
                                                    </span>
                                                    <br>
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if(count(@$experiences) >1)
                            <p class="text-right add_bottom_10">
                                <a href="#" class="button_intro">VIEW ALL EXPERIENCES >> </a>
                            </p>
                            @endif
                            <hr>
                            <!--End strip -->
                        </div>
                    </div>
                    @if(count($Destinations) > 1 && !empty($Destinations))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main_title">
                                <h2>Popular <span>Destinations</span></h2>
                                <p>Checkout some of the most polpular destination on BalanceBoat</p>

                            </div>
                        </div>
                        {{-- Loop thru categories type=0 and display_on_home=1 --}}     
                        @foreach ($Destinations as $val_catesDestinations_top)
                        <div class="col-md-3 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
                            <div class="tour_container tour_container1">

                                <div class="img_container">
                                    <a href="#">
                                        @if($val_catesDestinations_top->image_url)
                                        <img src="{{ Storage::disk('azure')->url($val_cates_top->image_url) }}" class="img-responsive" alt="{{ $val_catesDestinations_top->image_title }}">
                                        @endif
                                    </a>
                                </div>

                            </div>
                            <!-- End box tour -->
                            <h4 class="text-center">{{ $val_catesDestinations_top->name }}</h4>
                        </div>
                        <!-- End col-md-4 -->

                        @endforeach
                    </div>
                    <!-- End row -->
                    @endif
                </div>
                <!-- End container -->
            </section>
            <!-- End section -->
        </main>
        <!-- End main -->


        <footer class="revealed">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-3">
                        <h3>Need help?</h3>
                        <a href="tel://0123456789" id="phone">+91-0123456789</a>
                        <a href="mailto:help@balanceboat.com" id="email_footer">support@balanboat.com</a>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>About</h3>
                        <ul>
                            <li><a href="#">About BalanceBoat</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">List Your Center</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Terms and condition</a></li>
                            <li><a href="#">Privacy & Cookies</a></li>
                            <li><a href="#">Write for BalanceBoat</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>Discover</h3>
                        <ul>
                            <li><a href="#">Yoga</a></li>
                            <li><a href="#">Ayurveda</a></li>
                            <li><a href="#">Meditation</a></li>
                            <li><a href="#">Yoga Teacher Training</a></li>
                            <li><a href="#">Pilates</a></li>
                            <li><a href="#">BalanceBoat Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-3">
                        <h3>Currency</h3>


                        <div class="styled-select">
                            <select class="form-control" name="currency" id="currency">
                                <option value="INR" selected>INR</option>
                                <option value="USD" >USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="RUB">RUB</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End row -->
                <div class="row">
                    <div class="col-md-12">
                        <div id="social_footer">
                            <ul>
                                <li><a href="#"><i class="icon-facebook"></i></a></li>
                                <li><a href="#"><i class="icon-twitter"></i></a></li>
                                <li><a href="#"><i class="icon-google"></i></a></li>
                                <li><a href="#"><i class="icon-instagram"></i></a></li>
                                <li><a href="#"><i class="icon-pinterest"></i></a></li>
                                <li><a href="#"><i class="icon-vimeo"></i></a></li>
                                <li><a href="#"><i class="icon-youtube-play"></i></a></li>
                                <li><a href="#"><i class="icon-linkedin"></i></a></li>
                            </ul>
                            <p>© balanceboat.com 2015 - 2017</p>
                        </div>
                    </div>
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
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
        <!-- End Search Menu -->



        <!-- Common scripts -->
        <script src="{{asset('public/basicfront/js/jquery-2.2.4.min.js')}}"></script>
        <script src="{{asset('public/basicfront/js/common_scripts_min.js')}}"></script>
        <script src="{{asset('public/basicfront/js/functions.js')}}"></script>

        <script type="text/javascript">
//Search bar
$(function () {
    "use strict";
    $("#searchDropdownBox").change(function () {
        var Search_Str = $(this).val();
        //replace search str in span value
        $("#nav-search-in-content").text(Search_Str);
    });
});

        </script>

    </body>

</html>
