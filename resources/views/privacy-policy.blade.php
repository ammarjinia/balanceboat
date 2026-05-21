@extends('layouts.front')
@section('title', 'Privacy Policy')
<!-- Meta Info Start-->
@section('meta_title', "Privacy Policy")
@section('description', "Privacy Policy page for BalanceBoat, a booking platform for retreats and professional courses in Yoga and Ayurveda")
@section('keywords', "BalanceBoat, Booking platform, Yoga teacher Training booking website, Ayurveda packages booking, BalanceBoat Privacy Policy page, Privacy Policy page")
<!-- Meta Info End -->
@section('banner')
<section id="hero" class="subtitle">
    <div class="intro_title">
        <div class="container">
            <div class="row  text-left">
                <div class="col-md-7 sp_txt" style="verticle-align:bottom; height:60vh;max-height:400px">

                </div>
            </div>
        </div>

    </div>
</section>
<!-- End hero -->
@endsection
@section('content')
<main>
    <div id="position">
        <div class="container">
            <ul>
                <li><a href="{{ url("/") }}">Home</a></li>
                <li>Privacy Policy</li>
            </ul>
        </div>
    </div>
    <!-- End Position -->

    <div class="collapse" id="collapseMap">
        <div id="map" class="map">test</div>
    </div>
    <!-- End Map -->

    <div class="container margin_60">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Privacy Policy</h1>
                <div class="row">
                    <div class="col-md-12">
                        <p>Last updated: 31st Jan 2018</p>
                        <p>Thank you for using BalanceBoat!</p>
                        <p>This Privacy Policy explains how we collect, use, process, and disclose your information across the BalanceBoat Platform.</p>
                        <h3>What information does BalanceBoat collect?</h3>
                        <p>
                            Payment Information. We collect and pass your financial information (like your bank account, debit card, credit card information) to our Payment Services provider to process payments.
                            Communications with BalanceBoat and members of the team. When you communicate with BalanceBoat or use the BalanceBoat Platform to communicate with other Members, we collect information about your communication and any information you choose to provide.
                            Other Information. You may otherwise choose to provide us information when you fill in a form, conduct a search, update or add information to your BalanceBoat Account, respond to surveys, post to community forums, participate in promotions, or use other features of the BalanceBoat Platform.
                        </p>
                        <h3>How does BalanceBoat use the information it collects?</h3>
                        <p>The information provided by the visitors BalanceBoat will use, store, and process to provide, understand, improve, and develop the BalanceBoat Platform, and to create and maintain a trusted and safer environment for the visitors. The provided information is also used to provide, Personalize, Measure, and Improve our Advertising and Marketing.</p>
                        <h3>What information does BalanceBoat share?</h3>
                        <p>Booking. At the time of booking an experience the information provided by you which has been requested by the experience organizer or the payment provider is shared with them respectively.
                            Payment.  Data provided at the time of making a payment is share with the payment processor. It is encrypted through the Payment Card Industry Data Security Standard (PCI-DSS). Your purchase transaction data is stored only as long as is necessary to complete your purchase transaction. After that is complete, your purchase transaction information is deleted. All direct payment gateways adhere to the standards set by PCI-DSS as managed by the PCI Security Standards Council, which is a joint effort of brands like Visa, MasterCard and American Express. PCI-DSS requirements help ensure the secure handling of credit card information by our store and its service providers.</p>
                        <h3>CHANGES TO POLICY</h3>
                        <p>We reserve the right to modify this privacy policy at any time, so please review it frequently. Changes and clarifications will take effect immediately upon their posting on the website. If we make material changes to this policy, we will notify you here that it has been updated, so that you are aware of what information we collect, how we use it, and under what circumstances, if any, we use and/or disclose it.</p>
                        <h3>CONSENT</h3>
                        <p>By using this site, you represent that you are at least the age of majority in your state or province of residence, or that you are the age of majority in your state or province of residence and you have given us your consent to allow any of your minor dependents to use this site.</p>
                    </div>
                </div>
            </div>
        </div>
        <!--End row -->        
    </div>
    <!--End container -->
    <div id="overlay"></div>
    <!-- Mask on input focus -->
</main>
<!-- End section -->
@endsection
@section('footer')
@endsection