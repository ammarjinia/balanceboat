<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"> <![endif]-->
<!--[if IE 9]><html class="ie ie9"> <![endif]-->
<html lang="{{ config('app.locale')}}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta itemprop=image content="@yield('image')" />
  <meta name=twitter:image:src content="@yield('image')" />
  <meta property=og:image content="@yield('image')" />
  <meta property="og:image:url" content="@yield('image')" />
  <meta property="og:image:secure_url" content="@yield('image')" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow" />
  <meta name="keywords" content="@yield('keywords')" />
  <meta name="description" content="@yield('description')" />
  <meta itemprop="name" content="@yield('meta_title')" />
  <meta itemprop="description" content="@yield('description')" />
  <meta itemprop="image" content="@yield('image')" />
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:title" content="@yield('meta_title')" />
  <meta name="twitter:description" content="@yield('description')" />
  <meta name="twitter:image:src" content="@yield('image')" />
  <meta property="og:title" content="@yield('meta_title')" />
  <meta property="og:description" content="@yield('description')" />
  <meta property="og:image" content="@yield('image')" />
  <meta property="og:image:url" content="@yield('image')" />
  <meta property="og:image:secure_url" content="@yield('image')" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:site_name" content="{{ url("/") }}" />
  <meta property="og:type" content="website" />
  <meta name="yandex-verification" content="58770b522722e36d" />
  <meta name="csrf-token" content="PzhrHm113Vu0CzJ7v8WDRteHx6o0x9Rh4C5nN0lM">
  <meta name="robots" content="index, follow" />
  <title>@yield('title', "name")</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" />
  <link href="{{ asset('basicfront/css/bootstrap-grid.min.css') }}" rel="stylesheet">
  <link href="{{ asset('basicfront/css/styles.css?v=1') }}" rel="stylesheet">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{csrf_token()}}">
  <link rel="icon" type="image/x-icon" href="{{asset('basicfront/img/favicon.ico')}}">
  <link href="{{asset('basicfront/img/favicon.ico')}}" rel="apple-touch-icon" />
  <link rel="canonical" href="{!! strtolower(url()->current()) !!}" />

  <link href="https://fonts.googleapis.com/css?family=Istok+Web:400,700&display=swap" rel="stylesheet">

  <script type="text/javascript">
    APP_URL = '<?php echo url("/"); ?>';
  </script>
  @yield('head')
  @include('layouts.ga_code')
  <style>
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
    }
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }
  </style>
</head>

<body class="bg-page-listing deal-page">
  <header>
    <div class="top">
      <div class="container">
        <div class="row bg-header">
          <div class="col-auto col-lg-4 bg-header-left">
            <a class="logo" href="{{ url("/") }}">
              <img class="d-none d-lg-flex" src="{{ asset('deals/images/logo-1.png') }}" alt="Balanceboat" width="220" height="65" />
              <img class="d-lg-none" src="{{ asset('deals/images/logo-icon-1.png') }}" alt="Balanceboat" width="35" height="35" />
            </a>
          </div>

          <div class="col col-lg-4 bg-header-right no-decoration-all ms-auto">
            <div id="bg-menu-list" class="bg-btn bg-btn-secondary btn-medium btn-round fs-20 bg-menu-list">
              <span class="fs-20">☰</span>
              <ul class="bg-box">
                <li><a href="{{ url("/experiences") }}">Experiences</a></li>
                <li><a href="{{ url("/best-deals") }}">Best Deals</a></li>
                <li><a href="{{ url("/blog") }}">Articles</a></li>
                @if (!Auth::guest())
                <li><a href="{{ url("/myaccount") }}">My Bookings</a></li>
                @endif
              </ul>
            </div>
            <div>
              @if (Auth::guest())
              <a href="{{ url("/login") }}" target="_blank" class="bg-btn bg-btn-secondary btn-medium btn-round">
                <span class="fs-28 icon-user-solid-circle c-medium"></span>
              </a>
              @else
              <a href="{{ url("/logout") }}" target="_blank" class="bg-btn bg-btn-secondary btn-medium btn-round">
                <span class="fs-28 icon-user-solid-circle c-medium"></span>
              </a>
              @endif
            </div>
            <div data-popup="requstcallPopup" class="show-bg-modal call-back">
              Request Call Back
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="middle">
      <div class="container d-flex align-items-center justify-content-between">
        <ul class="no-decoration">
          <li><a href="#package">Package</a></li>
          <li><a href="#centre-overview">Overview</a></li>
          <li><a href="#accomodation">Accomodation</a></li>
          <li><a href="#food-overview">Food</a></li>
          <li><a href="#what-is-included">What is included</a></li>
          <li><a href="#howtoreach">How to Reach</a></li>
        </ul>
        <div>
          <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
              <div class="active-only-middle">
                <span class="c-white">
                  <a href="#frmBooking" class="bg-form-submit-small ms-2">Book Now</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  @yield('banner')
  @yield('content')
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-3">
          <h6>Yoga &amp; Ayurveda</h6>
          <ul>
            <li><a href="{{ url("/location/india?category=yoga-teacher-training") }}">Yoga Teacher Training in India</a></li>
            <li><a href="{{ url("/location/thailand?category=yoga-teacher-training") }}">Yoga Teacher Training in Thailand</a></li>
            <li><a href="{{ url("/location/maldives?category=yoga") }}">Yoga Retreats in Maldives</a></li>
            <li><a href="{{ url("/location/maldives?category=yoga-diving") }}">Yoga & Wellness Retreats in Thailand</a></li>
            <li><a href="{{ url("/category/ayurveda/ayurveda-massage-training") }}">Ayurveda Massage Training</a></li>
            <li><a href="{{ url("/category/ayurveda/ayurvedic-panchakarma") }}">Ayurvedic Panchakarma</a></li>
            <li><a href="{{ url("/category/ayurveda/ayurvedic-rejuvenation") }}">Ayurvedic Rejuvenation</a></li>
            <li>
              <a href="http://balancegurus.com/">BalanceGurus - World's Largest Wellness Listing Website</a>
            </li>
          </ul>
          <?php /* <!--<h6>Popular Categories</h6>-->
          <!--<ul>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/yoga") }}" title="">Yoga</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/pilates") }}" title="">Pilates</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/meditation") }}" title="">Meditation</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/ayurveda") }}" title="">Ayurveda</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/tai-chi") }}" title="">Tai Chi</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/surf-retreat") }}" title="">Surf Retreat</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/wellness") }}" title="">Wellness</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/ashram") }}" title="">Ashram</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/detox") }}" title="">Detox</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/category/healing") }}" title="">Healing</a>-->
          <!--  </li>-->
          <!--</ul>-->*/ ?>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <h6>About</h6>
          <ul>
            <li><a href="{{ url("/about-us") }}">About BalanceBoat</a></li>
            <li><a href="{{ url("/contact-us#contact_us") }}">Contact Us</a></li>
            <li><a href="{{ url("/contact-us") }}">List on BalanceBoat</a></li>
            <li><a href="{{ url("/terms-and-conditions") }}">Terms and condition</a></li>
            <li><a href="{{ url("/privacy-policy") }}">Privacy Policy</a></li>
            <li><a href="{{ url("/cookie-policy") }}">Cookie Policy</a></li>
          </ul>
          <?php /*<!--<h6>Collections</h6>-->
          <!--<ul>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/yoga-retreat-bali") }}" title="">Yoga Retreat Bali</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/ayurveda-retreats-south-india") }}" title="">Ayurveda Retreats South India</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/detox-retreats-bali") }}" title="">Detox Retreats Bali</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/ultra-luxury-wellness-resorts-india") }}" title="">Ultra Luxury Wellness Resorts India</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/meditation-retreats-bali") }}" title="">Meditation Retreats Bali</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/ayurveda-retreats-bali") }}" title="">Ayurveda Retreats Bali</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/pilates-studios-jakarta") }}" title="">Pilates Studios Jakarta</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/vipassana-meditation-centres-india") }}" title="">Vipassana Meditation Centres India</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/200-hrs-yoga-teacher-training-bali") }}" title="">200 hrs Yoga Teacher Training Bali</a>-->
          <!--  </li>-->
          <!--  <li>-->
          <!--    <a href="{{ url("/collection/yoga-and-surf-retreats-sri-lanka") }}" title="">Yoga and Surf Retreats Sri Lanka</a>-->
          <!--  </li>-->
          <!--</ul>-->*/ ?>
        </div>
        <div class="col-12 col-lg-6">
          <div class="row">
            <div class="col-12 col-lg-6">
              <h6>Discover</h6>
              <ul>
                <li><a href="{{ url("/category/yoga") }}">Yoga</a></li>
                <li><a href="{{ url("/category/ayurveda") }}">Ayurveda</a></li>
                <li><a href="{{ url("/category/meditation") }}">Meditation</a></li>
                <li><a href="{{ url("/category/yoga-teacher-training") }}">Yoga Teacher Training</a></li>
              </ul>
            </div>
            <div class="col-12 col-lg-6">
              <h6>Need Help?</h6>
              <ul>
                <li>
                  <a href="javascript:void(0);"><i class="icon-location"></i> 68 Maqbara Road,
                    Hazratganj,<br />
                    Lucknow - 226001, India</a>
                </li>
                <li>
                  <a href="tel:+917800080808" id="phone"><i class="icon-phone"></i> +91-7800080808</a>
                </li>
                <li>
                  <a href="mailto:zen@balanceboat.com" id="email_footer"><span>&#9993;</span> zen@balanceboat.com</a>
                </li>
              </ul>
                <h6>Currency</h6>
                <form id="frmGlobalCurrency" method="get" class="bg-form-el-cont">
                    {{ csrf_field() }}
                    <div class="bg-form-el bg-select">
                        <select class="form-control global_site_currency" name="global_site_currency" id="global_site_currency">
                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                            <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                            @endforeach                                    
                        </select>
                    </div>
                </form>
            </div>
            <?php /* <!--<div class="col-12 col-lg-6">-->
            <!--  <h6>Contact Us</h6>-->
            <!--  <ul>-->
            <!--    <li><a href="{{ url("/Pro Listing") }}">Pro Listing</a></li>-->
            <!--    <li><a href="{{ url("/create-profile") }}">Create Profile</a></li>-->
            <!--    <li><a href="{{ url("/advertise") }}">Advertise With Us</a></li>-->
            <!--  </ul>-->
            <!--</div>-->
            <!--<div class="col-12 col-lg-6">-->
            <!--  <h6>Menu</h6>-->
            <!--  <ul>-->
            <!--    <li><a href="{{ url("/") }}">Home</a></li>-->
            <!--    <li><a href="{{ url("listings") }}">Listings</a></li>-->
            <!--    <li><a href="{{ url("contact-us") }}">Contact Us</a></li>-->
            <!--  </ul>-->
            <!--</div>-->*/ ?>
          </div>
          <div class="row">
            <div class="col-12">
              <p class="fs-14 bg-border-top pt-3 mt-3">
                {!! substr(@$center->about_center, 0, 220) !!}
              </p>
            </div>
            <div class="col-12 c-white d-flex justify-content-end">
              <a href="" data-popup="requstcallPopup" class="show-bg-modal call-back">Request Call Back</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row copyright">
        <div class="container">
          <p class="fs-14">&copy; BalanceBoat 2021. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
  <?php /*<!-- Popups -->
  <!--<section id="bg-gallery-popup" class="bg-listing-gallery-cont gallery-popup hidden">-->
  <!--  <div class="container-fluid pos-sticky-top bg-white">-->
  <!--    <div class="row">-->
  <!--      <div class="col-12">-->
  <!--        <div class="head ps-0 pe-3 ps-md-4 pe-md-4">-->
  <!--          <div id="bg-gallery-back" class="gallery-back-btn">-->
  <!--            <div class="bg-btn bg-btn-secondary btn-medium btn-round">-->
  <!--              <span class="fs-18 icon-arrow-left"></span>-->
  <!--            </div>-->
  <!--          </div>-->
  <!--          <div class="right ms-auto">-->
  <!--            <span class="icon-share c-brand me-2"></span>-->
  <!--            <div class="bg-menu-list">-->
  <!--              <span> Share </span>-->
  <!--              <ul class="bg-box horiz">-->
  <!--                <li>-->
  <!--                  <a target="_blank"-->
  <!--                    href="http://www.facebook.com/share.php?u=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school"><span-->
  <!--                      class="icon-facebook"></span></a>-->
  <!--                </li>-->
  <!--                <li>-->
  <!--                  <a target="_blank"-->
  <!--                    href="http://pinterest.com/pin/create/button/?url=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school&media=&description=Kranti%20Yoga%20School%20is%20located%20in%20Canacona,%20Goa.%20This%20Yoga%20Teacher%20Training%20Centre%20offers%20100%20Hour%20Yoga%20Teacher%20Training,%20200%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Yoga,%20200%20Hour%20Yin%20Yoga%20&%20Vinyasa%20Flow,%20300%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Adjustments,%20300%20Hour%20Y"><span-->
  <!--                      class="icon-pinterest"></span></a>-->
  <!--                </li>-->
  <!--                <li>-->
  <!--                  <a target="_blank"-->
  <!--                    href="https://plusone.google.com/_/+1/confirm?hl=en&url=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school"><span-->
  <!--                      class="icon-google-plus"></span></a>-->
  <!--                </li>-->
  <!--                <li>-->
  <!--                  <a target="_blank"-->
  <!--                    href="https://twitter.com/share?via=in1.com&text=Kranti%20Yoga%20School%20Balancegurus"><span-->
  <!--                      class="icon-twitter"></span></a>-->
  <!--                </li>-->
  <!--                <li>-->
  <!--                  <a target="_blank"-->
  <!--                    href="http://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fbalancegurus.com%2Flocation%2Findia%2Fgoa%2Fkranti-yoga-school&title=Kranti%20Yoga%20School%20Balancegurus&summary=Kranti%20Yoga%20School%20is%20located%20in%20Canacona,%20Goa.%20This%20Yoga%20Teacher%20Training%20Centre%20offers%20100%20Hour%20Yoga%20Teacher%20Training,%20200%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Yoga,%20200%20Hour%20Yin%20Yoga%20&%20Vinyasa%20Flow,%20300%20Hour%20Vinyasa%20Flow%20&%20Ashtanga%20Adjustments,%20300%20Hour%20Y&source=in1.com"><span-->
  <!--                      class="icon-linkedin"></span></a>-->
  <!--                </li>-->
  <!--              </ul>-->
  <!--            </div>-->
  <!--            <span class="icon-love c-brand ms-2"></span>-->
  <!--            <a href="">Save</a>-->
  <!--          </div>-->
  <!--        </div>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </div>-->
  <!--</section>-->
*/ ?>
  <!-- Other Popups -->
  <section class="bg-modal hidden">
    <div class="bg-box bg-modal-wrapper">
      <span class="popup-close bg-btn bg-btn-secondary btn-medium btn-round pt-1">&#x2715;</span>
      <div class="modal-content">
        <!-- Call Back -->
        <div id="requstcallPopup" class="container-fluid d-none">
          <h2 class="mb-4 ellipsis">How shall BalanceBoat Contact You?</h2>
          <form id="frmRequestCall" class="bg-form-el-cont fw-500" name="frmRequestCall" method="post">
             @honeypot
             @csrf
            <div class="bg-form-el-wrap">
              <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
              <input type="hidden" name="exp_id" id="exp_id" value="{!! @$experience->id !!}" />
              <div class="bg-form-el">
                <input type="text" class="form-control required" id="name" name="name" required="" placeholder="Name" />
              </div>
              <div class="bg-form-el">
                <input type="email" class="form-control required email" id="email" name="email" required="" placeholder="Email Address" />
              </div>
              <div class="bg-form-el bg-select">
                <select name="country_code" id="country_code" class="form-control">
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
              <div class="bg-form-el">
                <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Phone" />
              </div>
              <div class="bg-form-el">
                <textarea class="form-control" id="message" name="message" placeholder="Enter your message..."></textarea>
              </div>
              <div class="bg-form-el">
                  {!! NoCaptcha::display() !!}
                  @if ($errors->has('g-recaptcha-response'))
                      <span class="help-block">
                          <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                      </span>
                  @endif
              </div>
            </div>
            <button type="submit" class="btn btn-danger btnRequestCallForm mt-4 bg-form-submit-1">Submit</button>
          </form>
        </div>

        <div id="checkAvailability" class="container-fluid d-none">
          <h2 class="mb-4 ellipsis">Check Availability</h2>
          <form id="frmCheckAvailability" class="bg-form-el-cont fw-500" name="frmCheckAvailability" method="post">
            @honeypot
             @csrf
            <div class="bg-form-el-wrap">
              <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
              <input type="hidden" name="exp_id" id="exp_id" value="{!! @$experience->id !!}" />
              <div class="bg-form-el">
                <input type="text" class="form-control required" id="name" name="name" required="" placeholder="Name" />
              </div>
              <div class="bg-form-el">
                <input type="email" class="form-control required email" id="email" name="email" required="" placeholder="Email Address" />
              </div>
              <div class="row g-0">
                  <div class="col-3">
                    <div class="bg-form-el bg-select" style="border-bottom:1px solid var(--border-2);">
                      <select name="country_code" id="country_code" class="form-control">
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
                  </div>  
                  <div class="col-9">
                      <div class="bg-form-el" style="border-bottom:1px solid var(--border-2);">
                        <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Phone" />
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                    <div class="bg-form-el" style="border-bottom:1px solid var(--border-2);">
                      <input type="text" class="form-control required flatpikr" id="date" name="date" required="" placeholder="Date of Availability" />
                    </div>
                  </div>    
              </div>
              <div class="bg-form-el">
                <textarea class="form-control" id="message" name="message" placeholder="Enter your message..."></textarea>
              </div>
              <div class="bg-form-el">
                  {!! NoCaptcha::display() !!}
                  @if ($errors->has('g-recaptcha-response'))
                      <span class="help-block">
                          <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                      </span>
                  @endif
              </div>
            </div>
            <button type="submit" class="btn btn-danger btn-check-availability Form mt-4 bg-form-submit-1">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <div class="success" id="snackbar">Message Sent Successfully..</div>
  <!-- Search Popup -->
  <script src="{{asset('basicfront/js/jquery-2.2.4.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('basicfront/js/jquery.lazy.min.js') }}"  defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js" defer></script>
  <script defer src="{{ asset('basicfront/js/script.js?v=3.2.1') }}"></script>
  @yield('footer')
  <script>
    const suitsBlocks = document.querySelectorAll('.suits');
    const suitsDetailsBlocks = document.querySelectorAll('.suits-details-wrapper');
    const suitsChooseBlocks = document.querySelectorAll('.suit-choose');
    if (suitsBlocks) {
      for (let i = 0; i < suitsBlocks.length; i++) {
        suitsBlocks[i].addEventListener('click', function() {
          for (let j = 0; j < suitsBlocks.length; j++) {
            suitsBlocks[j].classList.remove('active');
            suitsDetailsBlocks[j].classList.add('d-none');
            suitsChooseBlocks[j].classList.add('d-none');
          }
          suitsBlocks[i].classList.add('active');
          suitsDetailsBlocks[i].classList.remove('d-none');
          suitsChooseBlocks[i].classList.remove('d-none');
        })
      }
    }
  </script>
  <script>
    (function() {
      init(); //on page load - show first slide, hidethe rest
      function init() {
        parents = document.getElementsByClassName("slideshow-container");
        for (j = 0; j < parents.length; j++) {
          var slides = parents[j].getElementsByClassName("mySlides");
          var dots = parents[j].getElementsByClassName("dot");
          if (slides[0]) {
            slides[0]?.classList?.add("active-slide");
            dots[0]?.classList?.add("active");
          }
        }
      }
      dots = document.getElementsByClassName("dot"); //dots functionality
      for (i = 0; i < dots.length; i++) {
        dots[i].onclick = function() {
          slides =
            this.parentNode.parentNode.getElementsByClassName("mySlides");
          for (j = 0; j < this.parentNode.children.length; j++) {
            this.parentNode.children[j].classList.remove("active");
            this.parentNode.children[j].classList.remove("to-show");
            slides[j].classList.remove("active-slide");
            if (this.parentNode.children[j] == this) {
              index = j;
            }
          }
          this.classList.add("active");
          this.previousElementSibling?.classList.add("to-show");
          this.previousElementSibling?.previousElementSibling?.classList.add("to-show");
          this.nextElementSibling?.classList.add("to-show");
          this.nextElementSibling?.nextElementSibling?.classList.add("to-show");
          slides[index].classList.add("active-slide");
          if (this.previousElementSibling === null) {
            this.nextElementSibling?.nextElementSibling?.nextElementSibling?.classList.add("to-show");
          }
          if (this.previousElementSibling?.previousElementSibling === undefined) {
            this.nextElementSibling?.nextElementSibling?.nextElementSibling?.nextElementSibling?.classList.add("to-show");
          }
          if (this.previousElementSibling?.previousElementSibling === null) {
            this.nextElementSibling?.nextElementSibling?.classList.add("to-show");
            this.nextElementSibling?.nextElementSibling?.nextElementSibling?.classList.add("to-show");
          }
          if (this.nextElementSibling === null) {
            this.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.add("to-show");
          }
          if (this.nextElementSibling?.nextElementSibling === undefined) {
            this.previousElementSibling?.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.add("to-show");
          }
          if (this.nextElementSibling?.nextElementSibling === null) {
            this.previousElementSibling?.previousElementSibling?.classList.add("to-show");
            this.previousElementSibling?.previousElementSibling?.previousElementSibling?.classList.add("to-show");
          }
        };
      }
      //prev/next functionality
      links = document.querySelectorAll(".slideshow-container a");
      for (i = 0; i < links.length; i++) {
        links[i].onclick = function() {
          current = this.parentNode;
          var slides = current.getElementsByClassName("mySlides");
          var dots = current.getElementsByClassName("dot");
          curr_slide = current.getElementsByClassName("active-slide")[0];
          curr_dot = current.getElementsByClassName("active")[0];
          curr_slide.classList.remove("active-slide");
          curr_dot.classList.remove("active");
          if (this.className == "next") {
            if (
              curr_slide.nextElementSibling.classList.contains("mySlides")
            ) {
              curr_slide.nextElementSibling.classList.add("active-slide");
              curr_dot.nextElementSibling.click();
            } else {
              slides[0].classList.add("active-slide");
              dots[0].click();
            }
          }
          if (this.className == "prev") {
            if (curr_slide.previousElementSibling) {
              curr_slide.previousElementSibling.classList.add("active-slide");
              curr_dot.previousElementSibling.click();
            } else {
              slides[slides.length - 1].classList.add("active-slide");
              dots[slides.length - 1].click();
            }
          }
        };
      }
    })();
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(".btnRequestCallForm").on("click", function() {
        if ($("#frmRequestCall").valid()) {
          $(".btnRequestCallForm").attr("disabled", true);
          $("#requstcallPopup .alert-danger, #requstcallPopup .alert-success").remove();
          $.ajax({
            url: "{{ url('/send-request-call-back') }}",
            method: "post",
            data: $("#frmRequestCall").serialize(),
            success: function(result) {
              if (result.success) {
                $("#frmRequestCall")[0].reset();
                $("#frmRequestCall").after('<div class="alert alert-success" align="left">' + (result.success) + '</div>');
              } else if (result.errors) {
                resultdisp = "";
                if (result.errors) {
                  jQuery.each(result.errors, function(key, value) {
                    resultdisp += value;
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
      $("#requstcallPopup .close").on("click", function() {
        $("#requstcallPopup").hide();
        $("#frmRequestCall").validate().resetForm();
      });

      // Check Availability
        $(".btn-check-availability").on("click", function() {
            if ($("#frmCheckAvailability").valid()) {
                $(".btn-check-availability").attr("disabled", true);
                $("#checkAvailability .alert-danger, #checkAvailability .alert-success").remove();
                $.ajax({
                    url: "{{ url('/send-check-availability') }}",
                    method: "post",
                    data: $("#frmCheckAvailability").serialize(),
                    success: function(result) {
                    if (result.success) {
                        $("#frmCheckAvailability")[0].reset();
                        $("#frmCheckAvailability").after('<div class="alert alert-success" align="left">' + (result.success) + '</div>');
                    } else if (result.errors) {
                        resultdisp = "";
                        if (result.errors) {
                        jQuery.each(result.errors, function(key, value) {
                            resultdisp += value;
                        });
                        } else {
                        resultdisp = result;
                        }
                        $("#frmCheckAvailability").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                    } else {
                        $("#frmCheckAvailability").after(' <div class="alert alert-danger" align="left">Something Went Wrong!</div>');
                    }
                    $(".btn-check-availability").attr("disabled", false);
                    }
                });
            }
            return false;
        });
        $("#checkAvailability .close").on("click", function() {
            $("#checkAvailability").hide();
            $("#frmCheckAvailability").validate().resetForm();
        });

      $('.lazy').Lazy();
      $(".flatpikr").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        minDate: "today"
      });
    });
    $(function(){$(".global_site_currency").on("change",function(){$("#frmGlobalCurrency").submit()})});
  </script>
  
 {!! NoCaptcha::renderJs() !!}
  @include('layouts.whatsapp_float')
  @includeIf('layouts.partials.ActiveCampaign')

</body>

</html>