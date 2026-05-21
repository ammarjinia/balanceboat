@extends('admin.layouts.admin')
@section('title', 'Create Booking')

@section('head')
<link href="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create Booking</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/bookings') }}">Bookings</a></li>
<li class="breadcrumb-item active">Create Booking</li>
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
                        <h3 class="box-title  mt-3">Reservation Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Experience</label>
                                    <div class="controls">
                                        <select id="experience_id" name="experience_id" class="form-control select2" style="width: 100%" data-placeholder="">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Accommodation</label>
                                    <select id="experience_accomodation_id" id="experience_accomodation_id" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration</label>
                                    <select class="form-control required" required="" id="duration" name="duration">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Arrival Date</label>
                                    <input type="text" class="form-control required datepicker" required="" id="arrival_date" name="arrival_date" value="" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First name</label>
                                    <input type="text" class="form-control required" required="" id="firstname" name="firstname" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" class="form-control required" required="" id="lastname" name="lastname" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control required email" required="" value="" />
                                    <small>Conﬁrmation email sent to this address</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telephone number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control required" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Message for the experience organizer (optional)</label>
                                    <textarea id="message" name="message" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title">Booking Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Booking Amount</label>
                                    <input type="number" class="form-control required" name="booking_amount" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Booking Date</label>
                                    <input type="text" class="form-control required datepicker" required="" id="booking_date" name="booking_date" value="" autocomplete="off" />
                                </div>
                            </div>
                            <?php /*<div class="col-md-3">
                                <div class="form-group">
                                    <label>Booking Status</label>
                                    <select name="booking_status" class="form-control"></select>
                                </div>
                            </div>*/?>
                        </div>
                        <h3 class="box-title mt-3">Payment Info</h3>
                        <hr />
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="number" name="paid_amount" class="form-control required" />
                                </div>
                            </div>
                            <?php /*<div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Mode</label>
                                    <select id="payment_mode" name="payment_mode" class="form-control">
                                        
                                    </select>
                                </div>
                            </div>*/?>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
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
<script src="{{ asset('admin/js/booking-create.js?v=1') }}"></script>
<script>
    $(document).ready(function(){
        $('#experience_id').on('change', function() {
            const experienceId = $(this).val();
            
            // Clear existing options
            $('#duration').empty().append('<option value="">Select</option>');
            $('#experience_accomodation_id').empty().append('<option value="">Select</option>');

            if (experienceId) {
                $.ajax({
                    url: "{{ url('bbadmin/booking/experience-details') }}",
                    type: 'GET',
                    data: { experience_id: experienceId },
                    dataType: 'json',
                    success: function(response) {

                        // Populate durations
                        if (response.durations && response.durations.length > 0) {
                            $.each(response.durations, function(index, dur) {
                                $('#duration').append(`<option value="${dur.duration}">${dur.duration}</option>`);
                            });
                        }

                        // Populate accommodations
                        if (response.accommodations && response.accommodations.length > 0) {
                            $.each(response.accommodations, function(index, acc) {
                                $('#experience_accomodation_id').append(`<option value="${acc['id']}">${acc['name']}</option>`);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                        alert('Unable to load data for this experience.');
                    }
                });
            }
        });

    });
</script>
@endsection
