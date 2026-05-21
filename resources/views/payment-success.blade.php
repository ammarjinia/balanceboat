@extends('layouts.front')
@section('title', 'Payment Information')
@section('content')
<div id="position">
    <div class="container">
        <ul>
            <li><a href="{{ url("/") }}">Home</a></li>
            <li>Thank you</li>
        </ul>
    </div>
</div>
<!-- End position -->

<div class="container margin_80">
    <div class="row">
        <div class="col-md-8 add_bottom_15">

            <div class="form_title">
                <h3><strong><i class="icon-ok"></i></strong>Thank you! </h3>
                <p>Thank you for booking with us.</p>
            </div>
            <div class="step"></div>
            <!--End step -->

            <div class="form_title">
                <h3><strong><i class="icon-tag-1"></i></strong>Booking summary</h3>
                <p>Please find the booking details below.</p>
            </div>
            <div class="step">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Booking ID</strong>
                            </td>
                            <td>
                                {!! @$booking->id !!}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Experience</strong>
                            </td>
                            <td>
                                <a href="{!! url("/experience/".$experience->slug) !!}">
                                    {!! @$experience->name !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Start Date</strong>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($booking->start_date)->format("F d, Y") }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Price</strong>
                            </td>
                            <td>{{ $booking->booking_currency." ".number_format((($booking->pay_amount * $booking->booking_currency_rate)/$booking->currency_rate), 0, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Paid Amount</strong>
                            </td>
                            <td>{{ $booking->booking_currency." ".number_format((($booking->booking_amount * $booking->booking_currency_rate)/$booking->currency_rate), 0, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Days</strong>
                            </td>
                            <td>{{ $booking->duration }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Arrival Date</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->arrival_date)->format("F d, Y") }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--End step -->
        </div>
        <!--End col-md-8 -->

        <aside class="col-md-4">
            <div class="box_style_2">
                <i class="icon_set_1_icon-89"></i>
                <h4>Have <span>questions?</span></h4>
                <a href="tel://07800080808" class="phone">+91 780 008 0808</a>
            </div>
        </aside>

    </div>
    <!--End row -->
</div>
<!--End container -->
@endsection