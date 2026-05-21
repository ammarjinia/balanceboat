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
        <h1>My Account</h1>
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
            <li>My Account</li>
        </ul>
    </div>
</div>
<!-- End Position -->
<div class="container">
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
    <div id="tabs" class="tabs">
        <nav>
            <ul>
                <li><a href="#mybookings" class="icon-booking"><span>Bookings</span></a></li>
                <li><a href="#myprofile" class="icon-profile"><span>Profile</span></a></li>
            </ul>
        </nav>
        <div class="content">
            <section id="mybookings">
                @if(sizeof(@$experiences) > 0)
                @foreach (@$experiences as $experience) 
                <div class="strip_booking">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                            <div class="date">
                                <span class="month">{{ \Carbon\Carbon::parse(@$experience->start_date_time)->format("F") }}</span>
                                <span class="day"><strong>{{ \Carbon\Carbon::parse(@$experience->start_date_time)->format("d") }}</strong>{{ \Carbon\Carbon::parse(@$experience->start_date_time)->format("D") }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <a href="{{ url("/experience/".$experience->slug) }}">
                                <img src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive"> 
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <a href="{{ url("/experience/".$experience->slug) }}">
                                <p>{{ $experience->name }}</p>
                            </a>
                            <span>{{ $experience->duration }}</span>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <ul class="info_booking">
                                <li><strong>Booking id</strong> {{ $experience->booking_id }}</li>
                                <li><strong>Booked on</strong> {{ \Carbon\Carbon::parse(@$experience->created_at)->format("D. d F. Y") }}</li>
                            </ul>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="booking_buttons">
                                <a href="{{ url("/booking/".$experience->booking_id) }}" class="btn btn-pink">View Detail</a>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                </div>
                <!-- End strip booking -->
                @endforeach
                @else
                <h5 align="center">No Bookings Available</h5>
                @endif
            </section>
            <!-- End My Bookings -->

            <section id="myprofile">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Edit profile</h4>
                    </div>
                </div>
                <!-- End row -->
                <hr />
                <form id="frmUpdateProfile" name="frmUpdateProfile" action="{{ url("/user/update-profile") }}" method="post" enctype="multipart/form-data" >
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" id="email" type="email" disabled="" value="{{ Auth::user()->email }}" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>First name</label>
                                <input class="form-control required" name="first_name" id="first_name" type="text" value="{{ Auth::user()->first_name }}" required="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Last name</label>
                                <input class="form-control" name="last_name" id="last_name" type="text" value="{{ Auth::user()->last_name }}" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Phone number</label>
                                <input class="form-control required number" name="phone_number" id="phone_number" type="tel" value="{{ Auth::user()->phone_number }}" required="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Date of birth <small>(dd/mm/yyyy)</small></label>
                                <input class="form-control" name="date_of_birth" id="date_of_birth" type="date" value="{{ Auth::user()->date_of_birth }}" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <hr />
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Edit address</h4>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Street address</label>
                                <input class="form-control" name="street_address" id="street_address" type="text" value="{{ Auth::user()->street_address }}" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>City/Town</label>
                                <input class="form-control" name="city" id="city" type="text" value="{{ Auth::user()->city }}" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Zip code</label>
                                <input class="form-control" name="zipcode" id="zipcode" type="text" value="{{ Auth::user()->zipcode }}" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Country</label>
                                <select id="country" class="form-control" name="country">
                                    <option value="">Select</option>
                                    <option value="IN" {{ Auth::user()->country == "IN" ? "selected" : "" }}>India</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <hr />
                    <h4>Upload profile photo</h4>
                    <div class="form-inline upload_1">
                        <div class="form-group">
                            <input type="file" name="profile_image_url" id="profile_image_url" />
                            @if(Auth::user()->profile_image_url)
                            <br />
                            <div class="row" id="user_img_container">
                                <div class="col-md-3 m-t-10">
                                    <div class="card">
                                        <a href="{{ Storage::disk('azure')->url(Auth::user()->profile_image_url) }}" target="_blank">
                                            <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url(Auth::user()->profile_image_url) }}" alt="{{ Auth::user()->profile_image_title }}">
                                        </a>
                                        <div class="card-body">
                                            <a id="img_delete" href="{{ url('user/delete_image') }}" data-id="{{ Auth::user()->id }}" class="btn btn-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <hr />
                    <input type="submit" id="submit" class="btn_1 green" value="Update Profile" />
                </form>
            </section>
            <!-- End My Profile -->
        </div>
        <!-- End content -->
    </div>
    <!-- End tabs -->
    <br />
</div>
<!-- end container -->
<!-- End section -->
@endsection
@section('footer')
<!-- Specific scripts -->
<script src="{{ url("/public/basicfront/js/tabs.js") }}"></script>
<script type="text/javascript">
new CBPFWTabs(document.getElementById('tabs'));
</script>
<script src="{{ asset('public/basicfront/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/basicfront/js/myaccount.js') }}"></script>
@endsection