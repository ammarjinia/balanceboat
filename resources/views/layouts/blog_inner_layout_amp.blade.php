<!DOCTYPE HTML>
<html amp lang="{{ config('app.locale')}}">
    <head>
        <!--=============== basic  ===============-->
        <meta charset="UTF-8">
        <title>@yield('title') {{ config('app.name') }}</title>
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!--<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0.js"></script>-->
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-iframe"src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.2.js"></script>
    <style amp-boilerplate>
      body {
        -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
        -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
        -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
        animation: -amp-start 8s steps(1, end) 0s 1 normal both;
      }
      @-webkit-keyframes -amp-start {
        from {
          visibility: hidden;
        }
        to {
          visibility: visible;
        }
      }
      @-moz-keyframes -amp-start {
        from {
          visibility: hidden;
        }
        to {
          visibility: visible;
        }
      }
      @-ms-keyframes -amp-start {
        from {
          visibility: hidden;
        }
        to {
          visibility: visible;
        }
      }
      @-o-keyframes -amp-start {
        from {
          visibility: hidden;
        }
        to {
          visibility: visible;
        }
      }
      @keyframes -amp-start {
        from {
          visibility: hidden;
        }
        to {
          visibility: visible;
        }
      }
    </style>
    <noscript
      ><style amp-boilerplate>
        body {
          -webkit-animation: none;
          -moz-animation: none;
          -ms-animation: none;
          animation: none;
        }
      </style></noscript>
      
    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;500;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    />
    <style amp-custom>
      /* CSS Variables */
      :root {
        --pm-f: "Red Hat Display", sans-serif;
        --pm-c: #000;
        --pm-cm: #65676b;
        --pm-cl: #878787;
        --pm-cl1: #f0f2f5;
        --se-c: #ff3366;
        --se-c1: #3858f6;
        --se-c2: #dc3545;
        --se-c3: #25aae1;
        --border: #ced0d4;
        --cw: #fff;
        --fs: 18px;
        --fs-12: 12px;
        --fs-14: 14px;
        --fs-16: 16px;
        --fs-24: 24px;
        --pm-sh: 0px 4px 10px rgba(37, 47, 63, 0.1);
        --transition: all 0.3s;
      }
      /* Common Styles */
      * {
        box-sizing: border-box;
      }
      body {
        font-family: var(--pm-f);
        font-size: var(--fs);
        line-height: 1.67;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }
      a,
      a:link,
      a:hover,
      a:visited {
        text-decoration: none;
      }
      a {
        color: inherit;
      }
      h1 {
        font-size: 44px;
        line-height: 1.19;
      }
      h2 {
        font-size: 30px;
        line-height: 1.14;
      }
      h1,
      h2 {
        margin: 0 0 20px;
      }
      p {
        margin: 0 0 40px;
      }
      ul {
        padding-left: 30px;
        list-style: square;
    }
      .fs-12 {
        font-size: var(--fs-12);
      }
      .fs-14 {
        font-size: var(--fs-14);
      }
      .fs-16 {
        font-size: var(--fs-16);
      }
      .fs-24 {
        font-size: var(--fs-24);
      }
      .p-10 {
        padding: 10px;
      }
      hr {
        border-top: 1px solid var(--border);
      }
      .br-10 {
        border-radius: 10px;
      }
      .mt-60 {
        margin-top: 60px;
      }
      .mb-60 {
        margin-bottom: 60px;
      }
      .mt-150 {
        margin-top: 150px;
      }
      .mtb-60 {
        margin: 60px 0;
      }
      .flex-between {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      blockquote {
        margin: 40px 0;
        font-size: 30px;
        line-height: 1.35;
        color: var(--pm-cm);
      }
      .bg-list-number {
        margin: 40px 0;
        list-style-type: auto;
        padding-left: 20px;
        line-height: 1.67;
      }
      .bg-list-number li {
        margin: 10px 0;
      }
      .c-pointer {
        cursor: pointer;
      }
      /* bg-grid */
      .container {
        width: 100%;
        max-width: 1200px;
        padding: 0 15px;
        margin: 0 auto;
      }
      .container-fluid {
        width: 100%;
        padding: 0 15px;
        margin: 0 auto;
      }
      .bg-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
      }
      [class^="bg-col-"] {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
      }
      .bg-col-12 {
        flex: 0 0 100%;
        max-width: 100%;
      }

      /* Button */
      .btn {
        display: inline-flex;
        padding: 0px 20px;
        font-size: 16px;
        border-radius: 5px;
      }
      .btn--primary {
        background-color: var(--se-c);
        color: var(--cw);
      }
      .bg-padding {
        padding: 80px 0;
      }

      /* Header */
      header {
        box-shadow: var(--pm-sh);
        position: sticky;
        top: 0;
        background-color: var(--cw);
        z-index: 10;
      }
      .header-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .header-wrapper ul {
        display: flex;
        align-items: center;
      }
      .header-wrapper ul li a {
        padding: 15px;
        display: flex;
        align-items: center;
        height: 50px;
        min-width: 200px;
        color: var(--pm-cl);
        font-weight: 500;
        font-size: var(--fs-16);
        transition: all 0.3s;
      }
      .header-wrapper ul li a:hover,
      .header-wrapper ul li a:focus {
        background-color: var(--se-c3);
        color: var(--cw);
        transition: all 0.3s;
      }
      .header-wrapper.side-menu {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
      }
      .header-wrapper.side-menu ul {
        flex-direction: column;
        margin-top: 50px;
      }
      .hamburger,
      .close-sidebar {
        width: 40px;
        height: 40px;
        align-items: center;
        display: inline-flex;
        justify-content: center;
        font-size: 30px;
        color: var(--se-c3);
      }
      .close-menu {
        position: absolute;
        top: 10px;
        right: 15px;
      }

        /* Banner Advertisement: */
        .banner-ad {
            background-color: var(--pm-c);
            padding-top: 30px;
            padding-bottom: 30px;
            color: var(--cw);
        }

        /* Footer Ad */

        @media screen and (max-width:991px) {
            .bottom-footer {
                background-color: var(--pm-c);
                color: var(--cw);
                padding: 20px 40px;
                position: sticky;
                bottom: 0;
            }

            .bottom-footer p {
                margin: 0;
                line-height: 1.2;
                font-size: 15px;
            }
        }
      /* Blog */
      .blog {
        counter-reset: nos;
      }
      .blog-content h2::before {
        counter-increment: nos;
        content: counter(nos) ". ";
      }

      /* Footer */
      .follow-us {
        padding: 15px 0;
        border-bottom: 1px solid var(--border);
      }
      .follow-us a {
        height: 40px;
        width: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--pm-cl1);
        margin-left: 10px;
        font-size: var(--fs-14);
        transition: var(--transition);
      }
      .follow-us a:hover {
        background-color: var(--se-c1);
        transition: var(--transition);
        color: var(--cw);
      }

      .bg-footer-bottom {
        padding: 15px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .bg-footer-bottom ul,
      .bg-footer-1 {
        display: flex;
        align-items: center;
      }
      .bg-footer-bottom ul li a {
        margin-right: 30px;
        color: var(--pm-cl);
        font-weight: 500;
        font-size: var(--fs-14);
      }
      .bg-footer-bottom .bg-logo {
        margin-right: 50px;
      }
      input,
      textarea {
        width: 100%;
        border: 0;
        padding: 15px 25px;
        min-height: 50px;
        background-color: var(--pm-cl1);
        margin: 10px 0;
        font-family: var(--pm-f);
        font-size: var(--fs-16);
        border-radius: 4px;
      }
      .bg-form-el button {
        min-height: 40px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--se-c2);
        opacity: 0.9;
        transition: all 0.3s;
        border-radius: 2px;
        border: 0;
        color: var(--cw);
        cursor: pointer;
      }
      .bg-form-el button:hover {
        opacity: 1;
        transition: all 0.3s;
      }

      /* Scroll Button */
      .scrollToTop {
        position: fixed;
        z-index: 1;
        bottom: 15px;
        right: 15px;
        height: 50px;
        width: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: var(--se-c1);
        color: var(--cw);
        transition: all 0.3s;
        font-size: var(--fs-24);
        border: 0;
        border-radius: 50%;
        cursor: pointer;
      }

      /* Modal */
      .bg-modal-wrapper {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.6);
      }
      .bg-modal-container {
        width: 85vw;
        max-height: 85vh;
        overflow-y: auto;
        max-width: 500px;
        background-color: var(--cw);
        border-radius: 2px;
      }
      .bg-modal-container .close {
        color: var(--pm-cm);
      }
      .m-head {
        border-bottom: 1px solid var(--border);
      }
      .bg-modal-container .close:hover {
        opacity: 0.85;
        transition: all 0.3s;
      }
      .mb-0 {
        margin-bottom: 0;
      }
      .pb-0 {
        padding-bottom: 0;
      }
      .btn-contact {
        background: #f36;color: #fff;display: inline-block;padding:0px 20px;font-family: poppins,sans-serif;
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
        cursor: pointer;line-height:auto;height:auto;
    }

      /* Responsive */
      @media (max-width: 1199.98px) {
        .header-wrapper ul {
          flex-wrap: wrap;
        }
        .header-wrapper ul li a {
          height: 50px;
        }
        .bg-padding {
          padding: 40px 0;
        }
        .mt-150 {
          margin-top: 40px;
        }
        .mt-20 {
            margin-top: 20px;
        }
      }
      @media (max-width: 991.98px) {
        .mt-150 {
          margin-top: 40px;
        }
        h1 {
          font-size: 38px;
        }
        h2 {
          font-size: 28px;
        }
        .bg-footer-bottom,
        .bg-footer-bottom ul,
        .bg-footer-1 {
          flex-direction: column;
          display: flex;
          align-items: center;
          justify-content: space-between;
        }

        .bg-footer-bottom ul,
        .bg-footer-1 {
          display: flex;
          align-items: center;
        }
        .bg-footer-bottom ul li a {
          margin-right: 0;
        }
        .bg-footer-bottom .bg-logo {
          margin-right: 0;
        }
      }
      @media (max-width: 767.98px) {
        h1 {
          font-size: 34px;
        }
        h2 {
          font-size: 24px;
        }
      }

      @media (min-width: 992px) {
        .container {
          max-width: 960px;
        }
        .bg-col-lg-75 {
          flex: 0 0 75%;
          max-width: 75%;
        }
        .bg-col-lg-25 {
          flex: 0 0 25%;
          max-width: 25%;
        }
      }
      @media (min-width: 1200px) {
        .container {
          max-width: 1260px;
        }
        .bg-col-xl-66 {
          flex: 0 0 66.666666%;
          max-width: 66.666666%;
        }
        .bg-col-xl-33 {
          flex: 0 0 33.333333%;
          max-width: 33.333333%;
        }
      }
      .marquee-slider {
                background-color: black;
                color: #fff;
            }

            .marquee-container {
                height: 30px;
                overflow: hidden;
                line-height: 30px;
                position: relative;
                font-size: 14px;
            }

            .marquee-container .marquee {
                top: 0;
                left: 100%;
                min-width: max-content;
                overflow: hidden;
                position: absolute;
                white-space: nowrap;
                animation: marquee 30s linear infinite;
            }

            .marquee-container .marquee2 {
                animation-delay: 15s;
            }

            .marquee-container b {
                padding-left: 10px;
            }

            @keyframes marquee {
                0% {
                    left: 100%;
                }

                100% {
                    left: -100%
                }
            }
    </style>

</head>

<body>
    <amp-sidebar id="sidebar1" layout="nodisplay" side="left">
      <div
        role="button"
        aria-label="close sidebar"
        on="tap:sidebar1.toggle"
        tabindex="0"
        class="close-sidebar c-pointer close-menu"
      >
        ✕
      </div>
      <div class="header-wrapper side-menu">
        <ul>
            <li><a href="{{ url("/") }}">Home</a></li>
            <li><a href="{{ url("/blog") }}">Experiences</a></li>
            <li><a href="{{ url("/teachers") }}" class="@yield('mnuGurus')">Articles</a></li>
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
    </amp-sidebar>
    @yield('top-ad')
    <header>
      <div class="container-fluid">
        <div class="header-wrapper">
          <div
            role="button"
            on="tap:sidebar1.toggle"
            tabindex="0"
            class="hamburger c-pointer"
          >
            ☰
          </div>
          <a href="{{ url("/") }}">
              <amp-img width="300" height="100" layout="intrinsic" src="{{ url('/public/blog_assets/images/logo/balanceboat-logo.png') }}" alt="BalanceBoat" style="max-height: none;" /></amp-img></a>          </amp-img>
        </div>
      </div>
    </header>
    <section class="marquee-slider">
        <div class="position-relative marquee-container d-none d-sm-block">
            <div class="marquee">
                Get thr best offers upto 50% on Yoga and ayurveda retreats.
            </div>
            <div class="marquee marquee2">
                Get thr best offers upto 50% on Yoga and ayurveda retreats.
            </div>
        </div>
    </section>
    @yield('content')
    <br />
    <footer>
      <div class="container">
        <div class="bg-row">
          <div class="bg-col-12">
            <div class="follow-us">
              <small><strong>Follow Us</strong></small>
              &nbsp;
                <a href="https://www.facebook.com/balanceboat" target="_blank" ><i class="fab fa-facebook-f"></i></a>
                <a href="https://in.pinterest.com/balanceboat" target="_blank"><i class="fab fa-pinterest"></i></a>
                <a href="https://www.instagram.com/balanceboat" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
          <div class="bg-col-12">
            <div class="bg-footer-bottom">
              <div class="bg-footer-1">
                <strong class="bg-logo">BalanceBoat</strong>
                <?php /*<ul>
                  <li>
                        <a class="hover-flip-item-wrapper" href="{{ url("/pro-listing-info") }}">
                            <span class="hover-flip-item">
                        <span data-text="Terms of Use">Pro Listing</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="hover-flip-item-wrapper" href="{{ url("/create-profile") }}">
                            <span class="hover-flip-item">
                        <span data-text="AdChoices">Create Profile</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="hover-flip-item-wrapper" href="{{ url("/advertise") }}">
                            <span class="hover-flip-item">
                        <span data-text="Advertise with Us">Advertise with Us</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="hover-flip-item-wrapper" href="{{ url("/contact-us") }}">
                            <span class="hover-flip-item">
                                <span data-text="Contact Us">Contact Us</span>
                            </span>
                        </a>
                    </li>
                </ul>*/?>
              </div>
              <span class="bg-footer-2 fs-12">&copy; {{ date("Y") }} Balanceboat. All rights reserved.</span>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- Booking Popup - AMP Code -->
    <amp-lightbox id="quote-lb" layout="nodisplay">
      <div class="bg-modal-wrapper">
        <form
          method="post"
          action-xhr="https://www.lananceguru.com"
          class="bg-modal-container"
        >
          <div class="m-head p-10 fs-24 flex-between">
            <span><strong>Save15% or more</strong></span>
            <span
              class="close c-pointer"
              on="tap:quote-lb.close"
              tabindex="1"
              role="close"
              ><i class="fa fa-times"></i
            ></span>
          </div>
          <div class="m-body p-10 pb-0">
            <div>
              Book a Yoga & Meditation Retreat in India and get the BEST offers
              & prices
            </div>
            <div class="bg-form-el">
              <input placeholder="Name" type="text" name="" id="" required />
            </div>
            <div class="bg-form-el">
              <input
                placeholder="Email Address"
                type="email"
                name=""
                id=""
                required
              />
            </div>
            <div class="bg-form-el">
              <input placeholder="Phone" type="tel" name="" id="" required />
            </div>
            <div class="bg-form-el">
              <textarea
                class="mb-0"
                placeholder="Enter your message..."
                name=""
                id=""
                rows="3"
              ></textarea>
            </div>
          </div>
          <div class="bg-form-el m-footer p-10">
            <button class="fs-14" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </amp-lightbox>
    <!-- END: Booking Popup - AMP Code -->
    <!-- JS
============================================ -->
    
<amp-analytics type="gtag" data-credentials="include">
<script type="application/json">
{
  "vars" : {
    "gtag_id": "G-WPPP45GGLF",
    "config" : {
      "G-WPPP45GGLF": { "groups": "default" }
    }
  }
}
</script>
</amp-analytics>
</body>
</html>