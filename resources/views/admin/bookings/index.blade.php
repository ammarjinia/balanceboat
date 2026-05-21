@extends('admin.layouts.admin')
@section('title', 'Bookings')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Bookings</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Bookings</li>
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
                    <div class="text-right">
                        <a href="{{ url('bbadmin/booking/create')}}" class="btn btn-info btn-rounded">Add New Booking</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblBookings" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Booking Date</th>
                                    <th>Customer</th>
                                    <th>Experience</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$bookings) {
                                    foreach (@$bookings as $booking) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo url('bbadmin/booking/edit/' . $booking->id); ?>"><?php echo $booking->id; ?></a></td>
                                            <td>
                                                {{ (@$booking->start_date_time) ? \Carbon\Carbon::parse(trim(@@$booking->start_date_time))->format("M d, Y") : "" }}
                                            </td>
                                            <td>
                                                @if(@$booking->user_id)
                                                <a href="<?php echo url('bbadmin/users/' . $booking->user_id . "/edit"); ?>">
                                                    <?php echo $booking->user_first_name . " " . $booking->user_last_name; ?>
                                                </a>
                                                @else
                                                {!! $booking?->userInfo?->firstname." ".$booking?->userInfo?->lastname !!}
                                                @endif
                                            </td>
                                            <td><?php echo $booking->name; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/booking/edit/' . @$booking->id); ?>">Edit</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="frmExperiences" name="frmExperiences" action="{{ url('bbadmin/booking/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/bookings.js') }}"></script>
@endsection
