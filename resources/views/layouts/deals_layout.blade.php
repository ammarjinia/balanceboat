<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"> <![endif]-->
<!--[if IE 9]><html class="ie ie9"> <![endif]-->
<html lang="{{ config('app.locale')}}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow" />
  <meta name="keywords" content="@yield('keywords')" />
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
  <title>@yield('title', "Booking website for holiday retreats and professional trainings in Yoga, Meditation, Ayurveda, Detox and Wellness")</title>
  <link rel="icon" href="{{ url('public/deals/images/favicon.png') }}" type="image/png" sizes="16x16" />
  <link rel="preconnect" href="https://www.google-analytics.com" />
  <link rel="preconnect" href="https://acobot.ai" />
  <link rel="preconnect" href="https://chat.acobot.ai" />
  <link rel="preconnect" href="https://stats.g.doubleclick.net" />
  <link rel="canonical" href="{!! url()->current() !!}" />
  <!--<link rel="stylesheet" href="{{ asset('public/deals/css/jquery-ui.min.css?v=1.8.2') }}">-->

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{csrf_token()}}">

  <!--link rel="stylesheet" href="{{ asset('public/deals/css/bootstrap-grid.min.css?v=1.1') }}" /-->

  <link href="{{ asset('public/basicfront/css/bootstrap-grid.min.css?v=2') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
  <link href="{{ asset('public/basicfront/css/styles.css?v=3') }}" rel="stylesheet">
  @yield('head')
  <script type="text/javascript">
    APP_URL = '<?php echo url("/"); ?>';
  </script>
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

  <!-- Meta Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '3992138751070383');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=3992138751070383&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
</head>

<body class="@yield('body-class','interactive-list deal-page')">

  @yield('top-ad')
  <header>
    <div class="top">
      <div class="container">
        <div class="row bg-header">
          <div class="col-auto col-lg-4 bg-header-left">
            <a class="logo" href="{{ url("/") }}">
              <img class="d-none d-lg-flex" src="{{ asset('public/deals/images/logo-1.png') }}" alt="Balanceboat" width="220" height="65" />
              <img class="d-lg-none" src="{{ asset('public/deals/images/logo-icon-1.png') }}" alt="Balanceboat" width="35" height="35" />
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
            <button type="button" data-popup="popup-call-back" class="show-bg-modal call-back" style="border:0;">Request Call Back</button>
          </div>
        </div>
      </div>
    </div>
    @yield('header')
  </header>
  @yield('content')
  <footer>
    <div class="container">
      <div class="row d-none d-lg-flex">
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
              <h6 class="mb-3">Currency</h6>
              <form id="frmGlobalCurrency" method="get" class="search_form" action="#">
                {{ csrf_field() }}
                <div class="bg-form-el-cont">
                  <select class="global_site_currency form-control" name="global_site_currency" id="global_site_currency">
                    @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                    <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                    @endforeach
                  </select>
                </div>
              </form>

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
                <li class=" c-white">
                    <a href="" data-popup="popup-call-back" class="show-bg-modal call-back mt-4">Request Call Back</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row copyright">
        <div class="container">
          <p class="fs-14">&copy; Balanceboat {{ date("Y") }}. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
  @yield('mob_link')
  <!-- Other Popups -->
  <section class="bg-modal hidden">
    <div class="bg-box bg-modal-wrapper">
      <span class="popup-close bg-btn bg-btn-secondary btn-medium btn-round pt-1">&#x2715;</span>
      <div class="modal-content">
        @yield('model_content')
        <div id="popup-call-back" class="container-fluid d-none">
          <h2 class="mb-4 ellipsis">How shall BalanceBoat Contact You?</h2>
          <form name="frmRequestCall" method="POST" class="bg-form-el-cont fw-500" id="frmRequestCall">
            @honeypot
            @csrf
            <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
            <input type="hidden" name="exp_id" id="exp_id" value="" />
            <div class="bg-form-el-wrap">
              <div class="bg-form-el">
                <input type="text" class="form-control required" id="name" name="name" required="" placeholder="Name">
              </div>
              <div class="bg-form-el">
                <input type="email" class="form-control required email" id="email" name="email" required=""
                  placeholder="Email Address">
              </div>
              <div class="bg-form-el bg-select">
                <select name="country_code" id="country_code" class="form-control">
                  <option data-countrycode="IN" value="91" selected="">India (+91)</option>
                  <option data-countrycode="US" value="1">USA (+1)</option>
                  <option data-countrycode="DZ" value="213">Algeria (+213)</option>
                  <option data-countrycode="AD" value="376">Andorra (+376)</option>
                  <option data-countrycode="AO" value="244">Angola (+244)</option>
                  <option data-countrycode="AI" value="1264">Anguilla (+1264)</option>
                  <option data-countrycode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                  <option data-countrycode="AR" value="54">Argentina (+54)</option>
                  <option data-countrycode="AM" value="374">Armenia (+374)</option>
                  <option data-countrycode="AW" value="297">Aruba (+297)</option>
                  <option data-countrycode="AU" value="61">Australia (+61)</option>
                  <option data-countrycode="AT" value="43">Austria (+43)</option>
                  <option data-countrycode="AZ" value="994">Azerbaijan (+994)</option>
                  <option data-countrycode="BS" value="1242">Bahamas (+1242)</option>
                  <option data-countrycode="BH" value="973">Bahrain (+973)</option>
                  <option data-countrycode="BD" value="880">Bangladesh (+880)</option>
                  <option data-countrycode="BB" value="1246">Barbados (+1246)</option>
                  <option data-countrycode="BY" value="375">Belarus (+375)</option>
                  <option data-countrycode="BE" value="32">Belgium (+32)</option>
                  <option data-countrycode="BZ" value="501">Belize (+501)</option>
                  <option data-countrycode="BJ" value="229">Benin (+229)</option>
                  <option data-countrycode="BM" value="1441">Bermuda (+1441)</option>
                  <option data-countrycode="BT" value="975">Bhutan (+975)</option>
                  <option data-countrycode="BO" value="591">Bolivia (+591)</option>
                  <option data-countrycode="BA" value="387">Bosnia Herzegovina (+387)</option>
                  <option data-countrycode="BW" value="267">Botswana (+267)</option>
                  <option data-countrycode="BR" value="55">Brazil (+55)</option>
                  <option data-countrycode="BN" value="673">Brunei (+673)</option>
                  <option data-countrycode="BG" value="359">Bulgaria (+359)</option>
                  <option data-countrycode="BF" value="226">Burkina Faso (+226)</option>
                  <option data-countrycode="BI" value="257">Burundi (+257)</option>
                  <option data-countrycode="KH" value="855">Cambodia (+855)</option>
                  <option data-countrycode="CM" value="237">Cameroon (+237)</option>
                  <option data-countrycode="CA" value="1">Canada (+1)</option>
                  <option data-countrycode="CV" value="238">Cape Verde Islands (+238)</option>
                  <option data-countrycode="KY" value="1345">Cayman Islands (+1345)</option>
                  <option data-countrycode="CF" value="236">Central African Republic (+236)</option>
                  <option data-countrycode="CL" value="56">Chile (+56)</option>
                  <option data-countrycode="CN" value="86">China (+86)</option>
                  <option data-countrycode="CO" value="57">Colombia (+57)</option>
                  <option data-countrycode="KM" value="269">Comoros (+269)</option>
                  <option data-countrycode="CG" value="242">Congo (+242)</option>
                  <option data-countrycode="CK" value="682">Cook Islands (+682)</option>
                  <option data-countrycode="CR" value="506">Costa Rica (+506)</option>
                  <option data-countrycode="HR" value="385">Croatia (+385)</option>
                  <option data-countrycode="CU" value="53">Cuba (+53)</option>
                  <option data-countrycode="CY" value="90392">Cyprus North (+90392)</option>
                  <option data-countrycode="CY" value="357">Cyprus South (+357)</option>
                  <option data-countrycode="CZ" value="42">Czech Republic (+42)</option>
                  <option data-countrycode="DK" value="45">Denmark (+45)</option>
                  <option data-countrycode="DJ" value="253">Djibouti (+253)</option>
                  <option data-countrycode="DM" value="1809">Dominica (+1809)</option>
                  <option data-countrycode="DO" value="1809">Dominican Republic (+1809)</option>
                  <option data-countrycode="EC" value="593">Ecuador (+593)</option>
                  <option data-countrycode="EG" value="20">Egypt (+20)</option>
                  <option data-countrycode="SV" value="503">El Salvador (+503)</option>
                  <option data-countrycode="GQ" value="240">Equatorial Guinea (+240)</option>
                  <option data-countrycode="ER" value="291">Eritrea (+291)</option>
                  <option data-countrycode="EE" value="372">Estonia (+372)</option>
                  <option data-countrycode="ET" value="251">Ethiopia (+251)</option>
                  <option data-countrycode="FK" value="500">Falkland Islands (+500)</option>
                  <option data-countrycode="FO" value="298">Faroe Islands (+298)</option>
                  <option data-countrycode="FJ" value="679">Fiji (+679)</option>
                  <option data-countrycode="FI" value="358">Finland (+358)</option>
                  <option data-countrycode="FR" value="33">France (+33)</option>
                  <option data-countrycode="GF" value="594">French Guiana (+594)</option>
                  <option data-countrycode="PF" value="689">French Polynesia (+689)</option>
                  <option data-countrycode="GA" value="241">Gabon (+241)</option>
                  <option data-countrycode="GM" value="220">Gambia (+220)</option>
                  <option data-countrycode="GE" value="7880">Georgia (+7880)</option>
                  <option data-countrycode="DE" value="49">Germany (+49)</option>
                  <option data-countrycode="GH" value="233">Ghana (+233)</option>
                  <option data-countrycode="GI" value="350">Gibraltar (+350)</option>
                  <option data-countrycode="GR" value="30">Greece (+30)</option>
                  <option data-countrycode="GL" value="299">Greenland (+299)</option>
                  <option data-countrycode="GD" value="1473">Grenada (+1473)</option>
                  <option data-countrycode="GP" value="590">Guadeloupe (+590)</option>
                  <option data-countrycode="GU" value="671">Guam (+671)</option>
                  <option data-countrycode="GT" value="502">Guatemala (+502)</option>
                  <option data-countrycode="GN" value="224">Guinea (+224)</option>
                  <option data-countrycode="GW" value="245">Guinea - Bissau (+245)</option>
                  <option data-countrycode="GY" value="592">Guyana (+592)</option>
                  <option data-countrycode="HT" value="509">Haiti (+509)</option>
                  <option data-countrycode="HN" value="504">Honduras (+504)</option>
                  <option data-countrycode="HK" value="852">Hong Kong (+852)</option>
                  <option data-countrycode="HU" value="36">Hungary (+36)</option>
                  <option data-countrycode="IS" value="354">Iceland (+354)</option>
                  <option data-countrycode="ID" value="62">Indonesia (+62)</option>
                  <option data-countrycode="IR" value="98">Iran (+98)</option>
                  <option data-countrycode="IQ" value="964">Iraq (+964)</option>
                  <option data-countrycode="IE" value="353">Ireland (+353)</option>
                  <option data-countrycode="IL" value="972">Israel (+972)</option>
                  <option data-countrycode="IT" value="39">Italy (+39)</option>
                  <option data-countrycode="JM" value="1876">Jamaica (+1876)</option>
                  <option data-countrycode="JP" value="81">Japan (+81)</option>
                  <option data-countrycode="JO" value="962">Jordan (+962)</option>
                  <option data-countrycode="KZ" value="7">Kazakhstan (+7)</option>
                  <option data-countrycode="KE" value="254">Kenya (+254)</option>
                  <option data-countrycode="KI" value="686">Kiribati (+686)</option>
                  <option data-countrycode="KP" value="850">Korea North (+850)</option>
                  <option data-countrycode="KR" value="82">Korea South (+82)</option>
                  <option data-countrycode="KW" value="965">Kuwait (+965)</option>
                  <option data-countrycode="KG" value="996">Kyrgyzstan (+996)</option>
                  <option data-countrycode="LA" value="856">Laos (+856)</option>
                  <option data-countrycode="LV" value="371">Latvia (+371)</option>
                  <option data-countrycode="LB" value="961">Lebanon (+961)</option>
                  <option data-countrycode="LS" value="266">Lesotho (+266)</option>
                  <option data-countrycode="LR" value="231">Liberia (+231)</option>
                  <option data-countrycode="LY" value="218">Libya (+218)</option>
                  <option data-countrycode="LI" value="417">Liechtenstein (+417)</option>
                  <option data-countrycode="LT" value="370">Lithuania (+370)</option>
                  <option data-countrycode="LU" value="352">Luxembourg (+352)</option>
                  <option data-countrycode="MO" value="853">Macao (+853)</option>
                  <option data-countrycode="MK" value="389">Macedonia (+389)</option>
                  <option data-countrycode="MG" value="261">Madagascar (+261)</option>
                  <option data-countrycode="MW" value="265">Malawi (+265)</option>
                  <option data-countrycode="MY" value="60">Malaysia (+60)</option>
                  <option data-countrycode="MV" value="960">Maldives (+960)</option>
                  <option data-countrycode="ML" value="223">Mali (+223)</option>
                  <option data-countrycode="MT" value="356">Malta (+356)</option>
                  <option data-countrycode="MH" value="692">Marshall Islands (+692)</option>
                  <option data-countrycode="MQ" value="596">Martinique (+596)</option>
                  <option data-countrycode="MR" value="222">Mauritania (+222)</option>
                  <option data-countrycode="YT" value="269">Mayotte (+269)</option>
                  <option data-countrycode="MX" value="52">Mexico (+52)</option>
                  <option data-countrycode="FM" value="691">Micronesia (+691)</option>
                  <option data-countrycode="MD" value="373">Moldova (+373)</option>
                  <option data-countrycode="MC" value="377">Monaco (+377)</option>
                  <option data-countrycode="MN" value="976">Mongolia (+976)</option>
                  <option data-countrycode="MS" value="1664">Montserrat (+1664)</option>
                  <option data-countrycode="MA" value="212">Morocco (+212)</option>
                  <option data-countrycode="MZ" value="258">Mozambique (+258)</option>
                  <option data-countrycode="MN" value="95">Myanmar (+95)</option>
                  <option data-countrycode="NA" value="264">Namibia (+264)</option>
                  <option data-countrycode="NR" value="674">Nauru (+674)</option>
                  <option data-countrycode="NP" value="977">Nepal (+977)</option>
                  <option data-countrycode="NL" value="31">Netherlands (+31)</option>
                  <option data-countrycode="NC" value="687">New Caledonia (+687)</option>
                  <option data-countrycode="NZ" value="64">New Zealand (+64)</option>
                  <option data-countrycode="NI" value="505">Nicaragua (+505)</option>
                  <option data-countrycode="NE" value="227">Niger (+227)</option>
                  <option data-countrycode="NG" value="234">Nigeria (+234)</option>
                  <option data-countrycode="NU" value="683">Niue (+683)</option>
                  <option data-countrycode="NF" value="672">Norfolk Islands (+672)</option>
                  <option data-countrycode="NP" value="670">Northern Marianas (+670)</option>
                  <option data-countrycode="NO" value="47">Norway (+47)</option>
                  <option data-countrycode="OM" value="968">Oman (+968)</option>
                  <option data-countrycode="PW" value="680">Palau (+680)</option>
                  <option data-countrycode="PA" value="507">Panama (+507)</option>
                  <option data-countrycode="PG" value="675">Papua New Guinea (+675)</option>
                  <option data-countrycode="PY" value="595">Paraguay (+595)</option>
                  <option data-countrycode="PE" value="51">Peru (+51)</option>
                  <option data-countrycode="PH" value="63">Philippines (+63)</option>
                  <option data-countrycode="PL" value="48">Poland (+48)</option>
                  <option data-countrycode="PT" value="351">Portugal (+351)</option>
                  <option data-countrycode="PR" value="1787">Puerto Rico (+1787)</option>
                  <option data-countrycode="QA" value="974">Qatar (+974)</option>
                  <option data-countrycode="RE" value="262">Reunion (+262)</option>
                  <option data-countrycode="RO" value="40">Romania (+40)</option>
                  <option data-countrycode="RU" value="7">Russia (+7)</option>
                  <option data-countrycode="RW" value="250">Rwanda (+250)</option>
                  <option data-countrycode="SM" value="378">San Marino (+378)</option>
                  <option data-countrycode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                  <option data-countrycode="SA" value="966">Saudi Arabia (+966)</option>
                  <option data-countrycode="SN" value="221">Senegal (+221)</option>
                  <option data-countrycode="CS" value="381">Serbia (+381)</option>
                  <option data-countrycode="SC" value="248">Seychelles (+248)</option>
                  <option data-countrycode="SL" value="232">Sierra Leone (+232)</option>
                  <option data-countrycode="SG" value="65">Singapore (+65)</option>
                  <option data-countrycode="SK" value="421">Slovak Republic (+421)</option>
                  <option data-countrycode="SI" value="386">Slovenia (+386)</option>
                  <option data-countrycode="SB" value="677">Solomon Islands (+677)</option>
                  <option data-countrycode="SO" value="252">Somalia (+252)</option>
                  <option data-countrycode="ZA" value="27">South Africa (+27)</option>
                  <option data-countrycode="ES" value="34">Spain (+34)</option>
                  <option data-countrycode="LK" value="94">Sri Lanka (+94)</option>
                  <option data-countrycode="SH" value="290">St. Helena (+290)</option>
                  <option data-countrycode="KN" value="1869">St. Kitts (+1869)</option>
                  <option data-countrycode="SC" value="1758">St. Lucia (+1758)</option>
                  <option data-countrycode="SD" value="249">Sudan (+249)</option>
                  <option data-countrycode="SR" value="597">Suriname (+597)</option>
                  <option data-countrycode="SZ" value="268">Swaziland (+268)</option>
                  <option data-countrycode="SE" value="46">Sweden (+46)</option>
                  <option data-countrycode="CH" value="41">Switzerland (+41)</option>
                  <option data-countrycode="SI" value="963">Syria (+963)</option>
                  <option data-countrycode="TW" value="886">Taiwan (+886)</option>
                  <option data-countrycode="TJ" value="7">Tajikstan (+7)</option>
                  <option data-countrycode="TH" value="66">Thailand (+66)</option>
                  <option data-countrycode="TG" value="228">Togo (+228)</option>
                  <option data-countrycode="TO" value="676">Tonga (+676)</option>
                  <option data-countrycode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                  <option data-countrycode="TN" value="216">Tunisia (+216)</option>
                  <option data-countrycode="TR" value="90">Turkey (+90)</option>
                  <option data-countrycode="TM" value="7">Turkmenistan (+7)</option>
                  <option data-countrycode="TM" value="993">Turkmenistan (+993)</option>
                  <option data-countrycode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                  <option data-countrycode="TV" value="688">Tuvalu (+688)</option>
                  <option data-countrycode="UG" value="256">Uganda (+256)</option>
                  <option data-countrycode="GB" value="44">UK (+44)</option>
                  <option data-countrycode="UA" value="380">Ukraine (+380)</option>
                  <option data-countrycode="AE" value="971">United Arab Emirates (+971)</option>
                  <option data-countrycode="UY" value="598">Uruguay (+598)</option>
                  <option data-countrycode="US" value="1">USA (+1)</option>
                  <option data-countrycode="UZ" value="7">Uzbekistan (+7)</option>
                  <option data-countrycode="VU" value="678">Vanuatu (+678)</option>
                  <option data-countrycode="VA" value="379">Vatican City (+379)</option>
                  <option data-countrycode="VE" value="58">Venezuela (+58)</option>
                  <option data-countrycode="VN" value="84">Vietnam (+84)</option>
                  <option data-countrycode="VG" value="84">Virgin Islands - British (+1284)</option>
                  <option data-countrycode="VI" value="84">Virgin Islands - US (+1340)</option>
                  <option data-countrycode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                  <option data-countrycode="YE" value="969">Yemen (North)(+969)</option>
                  <option data-countrycode="YE" value="967">Yemen (South)(+967)</option>
                  <option data-countrycode="ZM" value="260">Zambia (+260)</option>
                  <option data-countrycode="ZW" value="263">Zimbabwe (+263)</option>
                </select>
              </div>
              <div class="bg-form-el">
                <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10"
                  placeholder="Phone">
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
            <button type="submit" class="mt-4 bg-form-submit-1 btn btn-danger btnRequestCallForm">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <div class="success" id="snackbar">Message Sent Successfully..</div>
  <!-- Search Popup -->
  <script src="{{asset('public/basicfront/js/jquery-2.2.4.min.js')}}"></script>
  <script src="{{ asset('public/deals/js/script.js?v=2.3') }}"></script>
  <script src="{{asset('public/basicfront/js/merge.js')}}"></script><!-- Scripts -->
  <script src="{{asset('public/basicfront/js/jquery.validate.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" crossorigin="anonymous"></script>

  @yield('js')
  <script type="text/javascript">
    $(document).ready(function() {
      if ($('[data-popup="popup-call-back"]').length > 0) {
        $('[data-popup="popup-call-back"]').on("click", function() {
          if ($(this).attr("data-inquiry")) {
            $("#frmRequestCall #ref_url").val($(this).attr("data-inquiry"));
          } else {
            $("#frmRequestCall #ref_url").val('<?php echo url()->current() ?>');
          }

          if ($(this).attr("data-exp-id")) {
            $("#frmRequestCall #exp_id").val($(this).attr("data-exp-id"));
          }
        });
      }
      $(".btnRequestCallForm").on("click", function() {
        if ($("#frmRequestCall").valid()) {
          $(".btnRequestCallForm").attr("disabled", true);
          $("#frmRequestCall .alert-danger, #frmRequestCall .alert-success").remove();
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
      });

      $(".slocation").on("change", function() {
        $(this).parents("form").submit();
        return false;
      });
      $(".stags").on("change", function() {
        $(this).parents("form").submit();
        return false;
      });

      $(".global_site_currency").on("change", function() {
        $(this).parents("form").submit();
        return false;
      });
    });
  </script>
  <script>
    // Snackbar
    function snackbar() {
      var x = document.getElementById("snackbar");
      x.className = "show success";
      setTimeout(function() {
        x.className = x.className.replace("show", "");
      }, 3000);
    }

    const searchInputOne = document.getElementById("search-one");
    const searchInputTwo = document.getElementById("search-two");
    const searchInputThree = document.getElementById("search-three");
    const listsOne = document.querySelectorAll(".input-1>li");
    const listsTwo = document.querySelectorAll(".input-2>li");
    const listsThree = document.querySelectorAll(".input-3>li");
    for (let i = 0; i < listsOne.length; i++) {

      if (listsOne.length === 1) {
        searchInputOne.value = listsOne[i].textContent;
        searchInputOne.disabled = true
      } else {
        listsOne[i].addEventListener("mouseover", function() {
          searchInputOne.value = listsOne[i].textContent;
        });
      }
    }

    for (let i = 0; i < listsTwo.length; i++) {

      if (listsTwo.length === 1) {
        searchInputTwo.value = listsTwo[i].textContent;
        searchInputTwo.setAttribute('data-value', listsTwo[i].getAttribute('data-value'));
        searchInputTwo.disabled = true
        console.log(typeof(searchInputTwo.getAttribute('data-value')));
      } else {
        listsTwo[i].addEventListener("mouseover", function() {
          searchInputTwo.value = listsTwo[i].textContent;
          searchInputTwo.setAttribute('data-value', listsTwo[i].getAttribute('data-value'));
          console.log(typeof(Number(searchInputTwo.getAttribute('data-value'))));
          console.log(Number(searchInputTwo.getAttribute('data-value')));
        });
      }
    }

    for (let i = 0; i < listsThree.length; i++) {
      listsThree[i].addEventListener("mouseover", function() {
        searchInputThree.value = listsThree[i].textContent;
      });
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
          slides[0].classList.add("active-slide");
          dots[0].classList.add("active");
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
        $('.select2').select2();
    });
  </script>

  {!! NoCaptcha::renderJs() !!}
  @include('layouts.whatsapp_float')
  @includeIf('layouts.partials.ActiveCampaign')

</body>

</html>