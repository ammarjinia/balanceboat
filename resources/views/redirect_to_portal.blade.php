@extends('layouts.front')
@section('title', @$experience->name)
@section('head')
<link href="{{ asset('public/basicfront/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('public/basicfront/css/owl.theme.css') }}" rel="stylesheet">
<meta http-equiv="refresh" content="5; url=https://www.ayuryoga-ashram.com/book-now/">
@endsection
@section('content')
<div class="container margin_80">
    <section>
        <div class="container">
            <div class="margin_60">
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-5 strip_all_tour_list wow fadeIn text-left">
                        <h2>Namaste,</h2>
                        <p>You are being redirected to the Organizers website to complete this booking</p>
                        <a href="{{ url("/experience/".$experience->slug) }}">
                            <img src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="img-thumbnail"> 
                        </a>
                        <a href="{{ url("/experience/".$experience->slug) }}"><h4>{{ $experience->name }}</h4></a>
                        <p>Please wait...</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- End section -->
</div>
<!--End container -->
@endsection