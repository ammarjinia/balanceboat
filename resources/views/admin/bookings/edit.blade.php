@extends('admin.layouts.admin')
@section('title', 'Edit Booking')

@section('head')
<link href="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Booking</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/bookings') }}">Bookings</a></li>
<li class="breadcrumb-item active">Edit Booking</li>
@endsection
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('flash_message'))
                    <div class="container">      
                        <div class="alert alert-success">
                            <em> {!! session('flash_message') !!}</em>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                    </div>
                    @endif 
                    @if(Session::has('flash_error_message'))
                    <div class="container">      
                        <div class="alert alert-danger">
                            <em> {!! session('flash_error_message') !!}</em>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                    </div>
                    @endif 
                    <form id="frmCenter" action="{{ url("bbadmin/booking/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo @$ebooking->id; ?>" />
                        <h3 class="box-title">Booking Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Booking ID</label>
                                    <p>{{ @$ebooking->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Booking Amount</label>
                                    <p>{{ @$ebooking->currency." ".@$ebooking->booking_amount }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Booking Date</label>
                                    <p>{{ \Carbon\Carbon::parse(@$ebooking->start_date_time)->format("d M,Y") }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Booking Status</label>
                                    <p>{{ @$booking_transaction_info->order_status }}</p>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title mt-3">Payment Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <p>{{ @$booking_transaction_info->currency." ".@$booking_transaction_info->amount }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <p>{{ @$booking_transaction_info->payment_mode }}</p>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title mt-3">Center Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="img_list">
                                    <img src="{{ Storage::disk('azure')->url(@$center->banner_image_url) }}" alt="{{ @$center->banner_image_title }}" class="img-thumbnail"> 
                                </div>
                            </div>
                            <div class="clearfix visible-xs-block"></div>
                            <div class="col-lg-10 col-md-10">
                                <div class="tour_list_desc">
                                    <a href="{{ url("/experience/".@$center->slug) }}"><h4>{{ @$center->name }}</h4></a>
                                    <?php if (@$center->center_highlights) { ?>
                                        <p>
                                        <ul class="list_ok">
                                            <?php
                                            $lmt = 0;
                                            foreach (explode(",", @$center->center_highlights) as $center_highlight) {
                                                if ($lmt < 4) {
                                                    ?>
                                                    <li>{{ $center_highlight }}</li>
                                                    <?php
                                                }
                                                $lmt++;
                                            }
                                            ?>
                                        </ul>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title  mt-3">Experience Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="img_list">
                                    <img src="{{ Storage::disk('azure')->url(@$experience->banner_image_url) }}" alt="{{ @$experience->banner_image_title }}" class="img-thumbnail"> 
                                </div>
                            </div>
                            <div class="clearfix visible-xs-block"></div>
                            <div class="col-lg-8 col-md-8">
                                <div class="tour_list_desc">
                                    <a href="{{ url("/experience/".@$experience->slug) }}"><h4>{!! @$experience->name !!}</h4></a>
                                    <?php if (@$experience->experience_summary) {?>
                                        <p>
                                            <?php
                                            foreach (explode(",", @$experience->experience_summary) as $experience_summary) {
                                                    ?>
                                                    {!! $experience_summary !!}
                                                    <?php
                                                }
                                            ?>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="price_list">
                                    <?php
                                    if (!empty(@$experience->duration)) {
                                        ?>
                                        <p><br /></p>
                                        <small>For:</small><br />
                                        <span class="text-days">
                                            <?php
                                            echo @$experience->duration;
                                            ?>
                                        </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title  mt-3">Accommodation Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="img_list">
                                    @if(@$accommodation->banner_image_url)
                                    <img src="{{ Storage::disk('azure')->url(@$accommodation->banner_image_url) }}" alt="{{ @$accommodation->banner_image_title }}" class="img-responsive"> 
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix visible-xs-block"></div>
                            <div class="col-lg-10 col-md-10">
                                <div class="tour_list_desc">
                                    <a href="{{ url("/experience/".@$accommodation->slug) }}"><h4>{{ @$accommodation->name }}</h4></a>
                                    <p>{!! @$accommodation->description !!}</p>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title  mt-3">Reservation Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Arrival Date</label>
                                    <input type="text" class="form-control required datepicker" required="" id="arrival_date" name="arrival_date" value="{{ \Carbon\Carbon::parse(@$ebooking->arrival_date)->format("m/d/Y") }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>First name</label>
                                    <input type="text" class="form-control required" required="" id="firstname" name="firstname" value="{{ @$booking_user_info->firstname }}" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" class="form-control required" required="" id="lastname" name="lastname" value="{{ @$booking_user_info->lastname }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control required email" required="" value="{{ @$booking_user_info->email }}" />
                                    <small>Conﬁrmation email sent to this address</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Telephone number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" value="{{ @$booking_user_info->phone }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Message for the experience organizer (optional)</label>
                                    <textarea id="message" name="message" class="form-control" rows="5">{{ @$booking_user_info->message }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <a href="{{ url('bbadmin/bookings') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/js/booking-create.js') }}"></script>
@endsection
