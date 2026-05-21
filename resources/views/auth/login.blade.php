@extends('layouts.front')
@section('title', 'Registerd user login page')
<!-- Meta Info Start-->
@section('meta_title', "Registerd user login page")
@section('description', "Registerd user login page for BalanceBoat  booking platform")
@section('keywords', "BalanceBoat, Booking platform, Yoga teacher Training booking website, Ayurveda packages booking, BalanceBoat Login page, Login account page, user name and password entry")
<!-- Meta Info End -->
@section('banner')
<section id="hero">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                <div id="login">
                    <div class="text-center"><h3 style="margin: 0;">Login</h3></div>
                    <hr>
                    <form class="form-horizontal form-material" id="loginform" role="form" method="POST" action="{{ url('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>Username</label>
                            <input type="text" id="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="E-Mail Address" />
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <small>{{ $errors->first('email') }}</small>
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control" name="password" required="" placeholder="Password" />
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <small>{{ $errors->first('password') }}</small>
                            </span>
                            @endif
                        </div>
                        <!--p class="small">
                            <a href="#">Forgot Password?</a>
                        </p-->
                        <button class="btn_full" type="submit">Log In</button>
                        <a href="{{ url("register")}}" class="btn_full_outline">Signup</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="container" style="padding-top:170px;">
    <div class="main_title">
        <h1>In case you would like to join balanceboat.com as an Organizer we would recommend you to click <a href="{{ url("contact-us") }}" class="text-pink">List on BalanceBoat</a></h1>
    </div>
</section>
@endsection
@section('footer')
@endsection
