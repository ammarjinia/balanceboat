@extends('layouts.front')
@section('title', 'Customer Inquiry')
<!-- Meta Info Start-->
@section('meta_title', "Customer Inquiry")
@section('description', "Balancegurus, Customer Inquiry")
@section('keywords', "Balancegurus, Customer Inquiry")
<!-- Meta Info End -->
@section('head')
<link href="{{asset('public/basicfront/css/contactus.css')}}" rel="stylesheet" />
@endsection
@section('content')
<main>
    <div class="container margin_60">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Inquiry</h2>
                <div class="row">
                    <div class="col-md-8">
                        <div class="list-single-main-wrapper fl-wrap">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('flash_message'))
                            <div class="alert alert-success" id="message">
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
                            <h2>
                                @if($guestType =='2')
                                Reply to Customer
                                @else
                                Reply to {{ @$experienceId->name }}
                                @endif
                            </h2>
                            <div id="contact-form">
                                <form id="frmMessage" name="frmContact" action="{{ url("/savemessage") }}" method="post" class="contact-form custom-form">
                                    <input type="hidden" name="conversationId" value="{{$conversationid}}"/>
                                    <input type="hidden" name="experience_id" value="{{ @$experienceId->experienceId}}"/>
                                    <input type="hidden" name="message_type" value="{{$guestType}}"/>
                                    <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control required" required="" placeholder="Message" onclick="this.select()"></textarea>
                                    </div>
                                    <button class="btn btn-primary" type="submit" id="submit_message">Send Message <i class="fa fa-angle-right"></i></button>                                    
                                </form>
                            </div>
                            <!-- contact form  end--> 
                        </div>
                        <p><br /></p>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Indox</h2>
                                <hr />
                                @foreach($customerMessage as $message)  
                                <h5>{{ ($message->message_type =='2') ? @$experienceId->name : @$message->name." ".@$message->lastname}} - <span>{{ \Carbon\Carbon::parse(@$message->created_at)->format('F j, Y, g:i a')}}</span></h5>
                                <p>
                                    {{ $message->message }}
                                </p>
                                <hr />
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box_style_1 expose">
                            <h3 class="inner cls_new_h3">Contact Detail</h3>
                            <div class="form-group text-left">
                                <h4>{{ @$experienceId->name }}</h4>
                                <a href="javascript:void(0);" id="contactaddress">{{ (@$experienceId->address) ?: "N/A" }}</a>
                                <a href="mailto:support@balancegurus.com" id="email_footer">support@balancegurus.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End row -->        
    </div>
    <!--End container -->
    <div id="overlay"></div>
    <!-- Mask on input focus -->
</main>
<!-- End section -->
@endsection
@section('footer')
<script type="text/javascript">
    $(document).ready(function () {
        $("#frmMessage").validate();
    });
</script>
@endsection