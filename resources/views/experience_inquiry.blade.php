@extends('layouts.front')
@section('title', @$experience->name)
@section('head')
<link href="{{ asset('public/basicfront/css/contactus.css') }}" rel="stylesheet" />
@endsection   
@section('content')

<div class="collapse" id="collapseMap">
    <div id="map" class="map">test</div>
</div>
<!-- End Map -->

<div class="container margin_80">
    <div class="row">
        <div class="col-md-12">
            <h2>Inquiry</h2>
        </div>
    </div>
    <section>
        <div class="container">
            <div class="row add_bottom_45">
                <div class="strip_all_tour_list wow fadeIn" data-wow-delay="0.1s">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="img_list">
                                <a href="{{ url("/experience/".$experience->slug) }}">
                                    <img src="{{ Storage::disk('s3')->url($experience->banner_image_url) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive"> 
                                </a>
                            </div>
                        </div>
                        <div class="clearfix visible-xs-block"></div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <div class="tour_list_desc">
                                <a href="{{ url("/experience/".$experience->slug) }}"><h3>{{ $experience->name }}</h3></a>
                                <p><i class="fa fa-calender"></i> {{ ($experience->start_date_time) ? \Carbon\Carbon::parse($experience->start_date_time)->format("M d, Y h:i a") : "" }}</p>
                                <?php if (@$experience->experience_summary) { ?>
                                    <ul class="list_ok">
                                        <?php
                                        $lmt = 0;
                                        foreach (explode(",", @$experience->experience_summary) as $experience_summary) {
                                            if ($lmt < 4) {
                                                ?>
                                                <li>{{ @$experience_summary }}</li>
                                                <?php
                                            }
                                            $lmt++;
                                        }
                                        ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container -->
    </section>
    <!-- End section -->

    <div class="row">
        <div class="col-md-8 col-md-offset-2 fntlrg">
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
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h2>Enter your details <span class="fntsml">(we will share these with the centre)</span></h2>
            <form id="frmInquiry" name="frmInquiry" action="{{ url("/send-inquiry-email") }}" method="post">
                <input type="hidden" id="exp_id" name="exp_id" value="{{ @$experience->id }}" />
                <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6 col-sm-6 form-group">
                        <label>First Name </label>
                        <input type="text" class="form-control required" required="" id="firstname" name="firstname" value="" />
                    </div>
                    <div class="col-md-6 col-sm-6 form-group">
                        <label>Last Name </label>
                        <input type="text" class="form-control required" required="" id="lastname" name="lastname" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 form-group">
                        <label>Email Address</label>
                        <input type="email" id="email" name="email" class="form-control required email" required="" />
                        <small>Reply will be sent to this address</small>
                    </div>
                    <div class="col-md-6 col-sm-6 form-group">
                        <label>Conﬁrm email address </label>
                        <input type="email" id="email_confirmation" name="email_confirmation" class="form-control required email" data-rule-equalto="#email" required="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> <label>Telephone number (optional)</label></div>
                    <div class="col-md-2 col-sm-6 form-group">
                        <select class="form-control">
                            <option>India (+91)</option>
                        </select>
                    </div>
                    <div class="col-md-10 col-sm-6 form-group">
                        <input type="tel" id="phone" name="phone" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>What can our support team help you with</label>
                        <textarea id="message" name="message" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="row frm-btn">
                    <div class="col-md-12">
                        <input type="submit" name="submit" id="btnInquiry" class="btn_1 medium" value="Send" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--End row -->
</div>
<!--End container -->
@endsection
@section('footer')
<script src="{{ asset('public/basicfront/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/basicfront/js/inquiry.js') }}"></script>
@endsection