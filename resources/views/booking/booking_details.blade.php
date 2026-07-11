@extends('layouts.front')
@section('title', 'My Account')
@section('head')
<!-- SPECIFIC CSS -->
<link href="{{ asset('public/basicfront/css/admin.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/jquery.switch.css') }}" rel="stylesheet">
@endsection
@section('banner')
<section id="hero_2">
    <div class="intro_title animated fadeInDown">
        <h3>Booking Detail</h3>
    </div>
    <!-- End intro-title -->
</section>
<!-- End hero -->
@endsection

@section('content')
<div id="position">
    <div class="container">
        <ul>
            <li><a href="{{ url("/") }}">Home</a></li>
            <li><a href="{{ url("/myaccount") }}">My Bookings</a></li>
            <li>{{ $booking->name }}</li>
        </ul>
    </div>
</div>
<!-- End Position -->
<div class="container margin_60">
    @if(Session::has('flash_message'))
    <div class="alert alert-success">
        <em> {!! session('flash_message') !!}</em>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
    @endif 
    @if(Session::has('flash_error_message'))
    <div class="alert alert-danger">
        <em> {!! session('flash_error_message') !!}</em>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
    @endif 

    <div class="row">
        <div class="col-md-12 add_bottom_15">
            <div class="form_title">
                <h3><strong><i class="icon-tag-1"></i></strong>Booking summary</h3>
                <p></p>
            </div>
            <div class="step row">
                <div class="col-md-6">
                    <table class="table confirm table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Booking Id</strong></td>
                                <td>{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrival Date</strong></td>
                                <td>{{ \Carbon\Carbon::parse(@$booking->arrival_date)->format("D. d F. Y") }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount</strong></td>
                                <td>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking->booking_amount, @$booking->currency) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Paid Amount</strong></td>
                                <td>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking->pay_amount, @$booking->currency) }}</td>
                            </tr>
                            <?php /*<tr>
                                <td><strong>Payment Type</strong></td>
                                <td>{{ @$booking_transaction->payment_mode }}</td>
                            </tr>*/ ?>
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>
            </div>
            <!--End step -->
            <div class="form_title">
                <h3><strong><i class="icon-tag-1"></i></strong>Reservation Info</h3>
                <p></p>
            </div>
            <div class="step row">
                <div class="col-md-8">
                    <table class="table confirm table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>First name</strong></td>
                                <td>{{ @$booking_user_info->firstname }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last name</strong></td>
                                <td>{{ @$booking_user_info->lastname }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email Address</strong></td>
                                <td>{{ @$booking_user_info->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telephone number</strong></td>
                                <td>{{ @$booking_user_info->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Message for the experience organizer (optional)</strong></td>
                                <td>{{ @$booking_user_info->message }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>
            </div>
            <!--End step -->
            <div class="form_title">
                <h3><strong><i class="icon-tag-1"></i></strong>Experience Info</h3>
                <p></p>
            </div>
            <div class="step">
                <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="img_list">
                                <a href="{{ url("/experience/".@$booking_experience->slug) }}">
                                    <img src="{{ Storage::disk('s3')->url(@$booking_experience->banner_image_url) }}" alt="{{ @$booking_experience->banner_image_title }}" class="img-responsive"> 
                                </a>
                            </div>
                        </div>
                        <div class="clearfix visible-xs-block"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="tour_list_desc">
                                <a href="{{ url("/experience/".@$booking_experience->slug) }}"><h3>{{ @$booking_experience->name }}</h3></a>
                                <?php if (@$booking_experience->experience_summary) { ?>
                                    <p>
                                    <div style="height:148px;overflow:hidden;">
                                        {!! @$booking_experience->experience_summary !!}
                                    </div>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="price_list">
                                <div>
                                <small>Price:</small>
                                    <span>
                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$booking->booking_amount, @$booking->currency) }}
                                    </span>
                                <?php
                                if (!empty(@$booking_experience->duration)) {
                                    ?>
                                    <p><br /></p>
                                    <small>For:</small>
                                    <span class="text-days">
                                        <?php
                                        echo @$booking_experience->duration;
                                        ?>
                                    </span>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End step -->
            <div class="form_title">
                <h3><strong><i class="icon-tag-1"></i></strong>Accommodation Info</h3>
                <p></p>
            </div>
            <div class="step">
                @if(!empty(@$booking_experience_accm))
                <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="img_list">
                                <a href="{{ url("/experience/".@$booking_experience->slug) }}">
                                    <img src="{{ Storage::disk('s3')->url(@$booking_experience->banner_image_url) }}" alt="{{ @$booking_experience->banner_image_title }}" class="img-responsive"> 
                                </a>
                            </div>
                        </div>
                        <div class="clearfix visible-xs-block"></div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <div class="tour_list_desc">
                                <a href="javascript:void(0);"><h3>{{ @$booking_experience_accm->name }}</h3></a>
                                <p>
                                    {!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$booking_experience_accm->description), 1000)) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!--End step -->
            </div>
            <!--End col-md-8 -->
        </div>
    </div>
    <!-- end container -->
    <!-- End section -->
    @endsection
    @section('footer')

    <script src="{{ asset('public/basicfront/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/basicfront/js/myaccount.js') }}"></script>
    @endsection