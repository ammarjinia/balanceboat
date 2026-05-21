@extends('layouts.front')
@section('title', 'New user registration')
<!-- Meta Info Start-->
@section('meta_title', "New user registration")
@section('description', "New user registration page for BalanceBoat  booking platform for yoga teacher training and ayurveda packages")
@section('keywords', "BalanceBoat, Booking platform, Yoga teacher Training booking website, Ayurveda packages booking, BalanceBoat Registration page, new user registration, sign up page")
<!-- Meta Info End -->
@section('banner')
<section id="hero">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                <div id="login">
                    <div class="text-center"><h3 style="margin: 0;">Signup</h3></div>
                    <hr>
                    <form class="form-horizontal form-material" id="loginform" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus placeholder="First Name" />
                                @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <small>{{ $errors->first('first_name') }}</small>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus placeholder="Last Name" />
                                @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <small>{{ $errors->first('last_name') }}</small>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required />
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <small>{{ $errors->first('email') }}</small>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required  />
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <small>{{ $errors->first('password') }}</small>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required />
                            </div>
                        </div>
                        <!--div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox checkbox-success p-t-0 p-l-10">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup"> I agree to all <a href="#">Terms</a></label>
                                </div>
                            </div>
                        </div-->
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Sign Up</button>
                            </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>Already have an account? <a href="{{ url("login")}}" class="text-info m-l-5"><b>Login</b></a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="container padding_t_30">
    <div class="main_title">
        <h2>In case you would like to join balanceboat.com as an Organizer we would recommend you to click <a href="{{ url("contact-us") }}" class="text-pink">List on BalanceBoat</a></h2>
    </div>
</section>
@endsection
@section('footer')
@endsection
