<!doctype html>
<html class="no-js" lang="en">
    <head>
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

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}">
        <link rel="icon" type="image/x-icon" href="{{asset('basicfront/img/favicon.ico')}}">
        <link href="{{asset('basicfront/img/favicon.ico')}}" rel="apple-touch-icon" />
        <title>Balanceboat | Retreat & Teacher Training Booking and Marketing website</title>
        

        <!-- CSS here -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Rubik:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/icofont.min.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/bootstrap.min.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/owl.carousel.min.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/font-awesome.min.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/nice-select.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/gijgo.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/slicknav.css?v=2') }}">
        <link rel="stylesheet" href="{{ asset('basicfront/home/css/style.css?v=2') }}">
        <!-- <link rel="stylesheet" href="css/responsive.css?v=2"> -->
        <script type="text/javascript">
            APP_URL = '<?php echo url("/"); ?>';
        </script>
        @include('layouts.ga_code')
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
                                                <li>
                                                    <a href="{{ url("/help") }}" class="show-submenu">Help</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>

                                <div class="col-12">
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
                                <form id="frmSearchbar" name="frmSearchbar" action="{{ url("search-experiences") }}" method="get" class="search_form">
                                    <div class="input_field">								
                                        <select class="form-control" id="sdestination" name="sdestination" style="border: 1px solid #fff;-webkit-appearance: none;-moz-appearance: none;text-indent: 0;">                        
                                            <option value="">Destinations</option>
                                            @foreach(\App\Http\Helpers\CommonHelper::get_site_destinations() as $destination)
                                            <option value="{{ $destination->id }}" {{ (@$sdest == $destination->id) ? "selected":"" }}>{{ $destination->name." (".@$destination->total.")" }}</option>
                                            @endforeach
                                        </select>                             
                                    </div>
                                    <div class="input_field">								
                                        <select class="form-control" id="scategory" name="scategory" style="border: 1px solid #fff;-webkit-appearance: none;-moz-appearance: none;text-indent: 0;">                        
                                            <option value="">Category</option>
                                            @foreach(\App\Http\Helpers\CommonHelper::get_site_categories() as $category)
                                            <option value="{{ $category->id }}" {{ (@$scat == $category->id) ? "selected":"" }}>{{ $category->name." (".@$category->total.")" }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input_field">								
                                        <input id="datepicker" placeholder="Date">
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
            <div class="_1ank4ue">
                <div class="_1letgsj">
                    <div>
                        <div class="_9ld1z5">Experience authentic soul pampering retreats</div>
                    </div>
                    <div>
                        <div class="_xm3x8u">A selection of authentic retreats, with amazing locations, best in class services, well-trained staff, and dedicated health vacation planners.</div>			
                    </div>
                </div>
            </div>


            <div class="gallery">
                <figure class="_rkbmnj">
                    <div class="_11odcm8a">
                        <div class="_7mus82a">
                            <div class="_hxt6u1e">
                                <div class="_4626ulj">
                                    <a href="{{ url("/location/india/kerala")}}">
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Kerala-Retreats.png') }}" data-original-uri="img/experience/Balanceboat-Kerala-Retreats.png" style="object-fit: cover;" />
                                        </picture>
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
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Karnataka Retreats.JPG') }}" style="object-fit: cover;" />
                                        </picture>
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
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Bali-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>
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
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Srilanka-retreats-balanceboat.JPG') }}" style="object-fit: cover;" />
                                        </picture>
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
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Thailand-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>
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
                                        <picture>
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(max-width: 743px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 743.1px) and (max-width: 1127px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 1127.1px) and (max-width: 1439px)">
                                            <source srcset="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" media="(min-width: 1439.1px)">
                                            <img class="_1cb9q3xq lazy" aria-hidden="true" alt="" decoding="async" data-src="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" data-original-uri="{{ asset('basicfront/home/img/experience/Balanceboat-Costa-Rica-Retreats.png') }}" style="object-fit: cover;" />
                                        </picture>
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
                        <div><i class="icofont-award icofont icofont-1x"></i><div class="_1xi9wvy">Award winning</div><div class="_4gelgl">Award winning retreats with best in class amenities with amazing locations</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-diamond icofont icofont-1x"></i><div class="_1xi9wvy">Authentic</div><div class="_4gelgl">Authentic retreats with highly trained & experienced staff that will accommodate all your requests</div></div> 
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-safety icofont icofont-1x"></i><div class="_1xi9wvy">Safe &amp; sanitized</div><div class="_4gelgl">All listed retreats follow strict distancing &amp; sanitization protocols to keep you safe</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-taxi icofont icofont-1x"></i><div class="_1xi9wvy">Effortless arrivals</div><div class="_4gelgl">Private airport pick-up & drop are included in the price to make sure your arrivals are hassle free and smooth</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-ebook icofont icofont-1x"></i><div class="_1xi9wvy">Customized itinerary</div><div class="_4gelgl">Our planners will help you customize & plan your itinerary to the very last detail</div></div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-4">
                        <div><i class="icofont-life-bouy icofont icofont-1x"></i><div class="_1xi9wvy">Flexible cancellation</div><div class="_4gelgl">When your plans change we have got you covered with an easy 15-day cancellation before arrival</div></div>
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
                            <a href="{{ url("/experience/".$experience->slug) }}"><img data-src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="lazy" /></a>
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
                            <a href="{{ url("/location/".$val_catesDestinations_top->slug) }}" class="img" style="background-image: url({{ Storage::disk('azure')->url($val_catesDestinations_top->image_url) }})">
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
        <video controlslist="nodownload" preload="metadata" class="_1kffseb" style="object-fit: cover; width: 100%; height: 480px;" controls="">
            <source type="video/mp4" src="{{ Storage::disk('azure')->url('video/Balanceboat.mp4') }}"></video>
    </div>
    <footer>
        <div class="container">
            <div class="_1gw6tte">
                <div class="my-4">
                    <div class="_f9hxdp">
                        <div class="_1m9vrfa">
                            <div role="heading" aria-level="2" class="_72ul4s">Need help?</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><a href="javascript:void(0);"><i class="icofont-location-pin"></i> 68 Maqbara Road, Hazrathganj,<br> Lucknow - 226001, India</a></div>
                                <div class="_edda6d"><a href="tel://+917800080808" id="phone"><i class="icofont-ui-call"></i> +91-7800080808</a></div>
                                <div class="_edda6d"><a href="mailto:zen@balanceboat.com" id="email_footer"><i class="icofont-envelope"></i> zen@balanceboat.com</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div role="heading" aria-level="2" class="_72ul4s">About</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><a href="{{ url("/about-us") }}">About BalanceBoat</a></div>
                                <div class="_edda6d"><a href="{{ url("/contact-us#contact_us") }}">Contact Us</a></div>
                                <div class="_edda6d"><a href="{{ url("/contact-us") }}">List on BalanceBoat</a></div>
                                <div class="_edda6d"><a href="{{ url("/help") }}">Help</a></div>
                                <div class="_edda6d"><a href="{{ url("/terms-and-conditions") }}">Terms and condition</a></div>
                                <div class="_edda6d"><a href="{{ url("/privacy-policy") }}">Privacy Policy</a></div>
                                <div class="_edda6d"><a href="{{ url("/cookie-policy") }}">Cookie Policy</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div role="heading" aria-level="2" class="_72ul4s">Discover</div>
                            <div class="_1iz3pi3">
                                <div class="_edda6d"><a href="{{ url("/category/yoga") }}">Yoga</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/ayurveda") }}">Ayurveda</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/meditation") }}">Meditation</a></div>
                                <div class="_edda6d"><a href="{{ url("/category/yoga-teacher-training") }}">Yoga Teacher Training</a></div>
                            </div>
                        </div>
                        <div class="_1m9vrfa">
                            <div role="heading" aria-level="2" class="_72ul4s">Currency</div>
                            <div class="_1iz3pi3">
                                <form id="frmGlobalCurrency" method="get" class="search_form" action="#">
                                    {{ csrf_field() }}
                                    <div class="input_field">	
                                        <select class="global_site_currency" name="global_site_currency" id="global_site_currency">
                                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                            <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                                            @endforeach                                    
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="_1iz3pi3">
                                <img alt="Cards" class="cards img-responsive lazy" data-src="{{ asset('basicfront/img/cards.png') }}" style="display: block;">
                            </div>
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
        <a href="#chatPopup" data-toggle="modal">
            <img class="lazy" data-src="<?php echo url('/public/basicfront/img/ask-chat.png');?>" style="height: 75px;width: auto;position: fixed;right: 10px;bottom: 10px;border-radius: 50%;background: rgba(255, 255, 255, 0.3);" />
        </a>
    </footer>
    
    <div id="chatPopup" class="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:1000px;">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h3>Ask BalanceBoat<br /> 
                    <p>Got a question about retreats or treatment?</p></h3>
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
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
                                <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
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
    
    <!-- JS here -->
    <script src="{{asset('basicfront/home/js/vendor/jquery-1.12.4.min.js?v=2') }}"></script>	
    <script src="{{asset('basicfront/home/js/vendor/modernizr-3.5.0.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/popper.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/bootstrap.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/owl.carousel.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/nice-select.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/jquery.slicknav.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js/gijgo.min.js?v=2') }}"></script>
    <script type="text/javascript" src="{{ asset('basicfront/js/jquery.lazy.min.js?v=2') }}"></script>
    <!--contact js-->
    <script defer src="{{asset('basicfront/js/jquery.validate.min.js?v=2') }}"></script>
    <script src="{{asset('basicfront/home/js//main.js?v=2') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js?v=2" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        
        $(".lazy").lazy();
        
        $.Lazy('av', ['audio', 'video'], function(element, response) {
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
                    data: $("#frmChat").serialize()+"&_token={{ csrf_token() }}",
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
        
        if (!$.cookie('chat-popup')) {
            
            setInterval(function(){ $("#chatPopup").show(); }, 10000);
        
            var date = new Date();
            date.setTime(date.getTime() + (90 * 1000));
            
            $.cookie('chat-popup', 'yes', { expires: date }); 
        }
        $("#chatPopup .close").on("click", function(){
           $("#chatPopup").hide();
        });
    });
</script>
</body>
</html>