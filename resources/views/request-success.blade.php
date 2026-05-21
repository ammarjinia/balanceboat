@extends('layouts.front')
@section('title', 'Payment Information')
@section('content')
<main>
    <div id="position">
        <div class="container">
            <ul>
                <li><a href="{{ url("/") }}">Home</a></li>
                <li>Thank you</li>
            </ul>
        </div>
    </div>
    <!-- End position -->

    <div class="container margin_60">
        <div class="row">
            <div class="col-md-8 add_bottom_15">

                <div class="form_title">
                    <h3><strong><i class="icon-ok"></i></strong>Your reservation request was sent. </h3>
                </div>
                <div class="step">
                    <p>A copy was sent to your email address: test@test.com</p>
                    <p>If you don't find it in your inbox, please check your spam folder.</p>
                </div>
                <!--End step -->

                <div class="form_title hidden">
                    <h3><strong><i class="icon-tag-1"></i></strong>Booking summary</h3>
                    <p>
                       
                    </p>
                </div>
                <div class="step hidden">
                    <table class="table confirm">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    Item 1
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Olry airport</strong>
                                </td>
                                <td>
                                    2x
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Date</strong>
                                </td>
                                <td>
                                    25 Febraury 2015
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>To</strong>
                                </td>
                                <td>
                                    Jhon Doe
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Payment type</strong>
                                </td>
                                <td>
                                    Credit card
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--End step -->
            </div>
            <!--End col-md-8 -->

            <aside class="col-md-4">
                <div class="box_style_4">
                    <i class="icon_set_1_icon-89"></i>
                    <h4>Have <span>questions?</span></h4>
                    <a href="tel://07800080808" class="phone">+91 780 008 0808</a>
                </div>
            </aside>

        </div>
        <!--End row -->
    </div>
    <!--End container -->
</main>
<!-- End main -->
@endsection
@section('footer')
<script src="{{ asset('public/basicfront/js/payment.js') }}"></script>
@endsection