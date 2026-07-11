@extends('layouts.front')
@section('title', @$destination->name)
@section('meta_title', @$destination->name)
<?php if (!empty(@$destination->description)) { ?> 
    @section('description', @$destination->description)
<?php } ?>
<?php if (!empty(@$destination->keywords)) { ?> 
    @section('keywords', @$destination->keywords)
<?php } ?>
<?php if (!empty(@$destination->banner_image_url)) { ?> 
    @section('image', Storage::disk('s3')->url(@$destination->banner_image_url))
<?php } ?>

@section('head')
<link href="{{ url("/public/basicfront/css/skins/square/grey.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.skinFlat.css") }}" rel="stylesheet">
@endsection
<?php
$qs = "";
if (!empty(request()->getQueryString())) {
    $qs = "?" . request()->getQueryString();
}
?>
@section('banner')
<section id="hero" style="<?php if (!empty(@$destination->banner_image_url)) { ?> background-image: url('{{ Storage::disk('s3')->url(@$destination->banner_image_url) }}') !important; <?php } ?> background-blend-mode:overlay;">
    <div class="intro_title">
        <h3 class="animated fadeInDown">{!! $deal->name !!}</h3>
        <p class="animated fadeInDown">{!! $deal->description !!}</p>
    </div>
    <?php /*@include('layouts.searchbar')*/?>
    <!-- /search_bar-->
</section>
@endsection
@section('content')


<section class="white_bg">
    <div class="container margin_60">
        <div class="main_title">
            <h2>Popular Experiences</h2>
            <p><i>Checkout some of the most popular experiences on BalanceBoat</i></p>
        </div>
        <form id="frmExperience" action="" method="GET">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div id="tools">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            @if($locations)
                            <div class="styled-select-filters">
                            <?php /*<form id="frmSearch" method="get" class="search_form" action="#">*/?>
                                    <select class="form-select slocation" name="slocation" style="border:1px solid #000; min-height:auto;">
                                        <option value="">Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}" <?php echo  $location->id == @$_GET["slocation"] ? "selected" : ""; ?>>{!! $location->name !!}</option>
                                        @endforeach                                    
                                    </select>
                            <?php /*</form>*/?>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3 col-sm-3 hidden-xs text-right">
                            <div class="styled-select-filters">
                                <form id="frmGlobalCurrency" method="get">
                                    {{ csrf_field() }}
                                    <select class="form-select global_site_currency" name="global_site_currency" id="global_site_currency">
                                        @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                        <option value="{{ $currency }}" <?php echo \App\Http\Helpers\CommonHelper::get_site_currency() == $currency ? "selected" : ""; ?>>{{ $currency }}</option>
                                        @endforeach                                    
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 hidden-xs text-right">
                            <div class="styled-select-filters">
                                <select name="sort_by" id="sort_by" class='form-control'>
                                    <option value="newest" {{ (@$sort_by == "newest") ? "selected":"" }}>Newest First</option>
                                    <option value="ranking" {{ (@$sort_by == "ranking") ? "selected":"" }}>Popularity</option>
                                    <option value="price_asc" {{ (@$sort_by == "price_asc") ? "selected":"" }}>Daily Price - Low to High</option>
                                    <option value="price_desc" {{ (@$sort_by == "price_desc") ? "selected":"" }}>Daily Price - High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/tools -->

                <div id="experience_data">
                {{-- Loop thru experiences --}}                            
                @foreach (@$experience as $experience) 
                @include('content-deal-experience', (array)$experience)
                @endforeach
                <!--End strip -->
            </div>
                <p class="text-center add_bottom_10 ajax-load text-center" style="display:none">
                    <!-- a href="#" class="button_intro btn-load-more">Load More </a -->
                </p>
                <!--End strip -->
            </div>
            <!-- End col lg-9 -->
        </div>
    </form>
        
    </div>
    <!--End container -->
</section>
<!--End section -->
<form id="frmBooking" name="frmBooking" action="" method="POST">
<input type="hidden" id="hdn_experience_id" name="hdn_experience_id" value="" />
{{ csrf_field() }}
</form>
<!-- Inquiry -->
<div class="modal fade" id="mdlInquiry" tabindex="-1" role="dialog" aria-labelledby="mdlInquiryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="mdlInquiryLabel">Inquiry</h4>
            </div>
            <div class="modal-body">
                <div id="message-review"> </div>
                <form class="add-comment custom-form contact-form" id="frmInquiry" action="/test" method="post" novalidate="novalidate">
                    <input type="hidden" name="experience_id" id="experience_id" value="{{ @$experience->id }}">
                    {{ csrf_field() }}
                    <input name="tour_name" id="tour_name" type="hidden" value="Paris Arch de Triomphe Tour">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="name" id="name" placeholder="First Name" class="form-control required" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required" type="text" name="lastname" id="lastname" placeholder="Last Name" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required email" type="email" name="email" id="email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control required number" type="text" name="phone" id="phone" placeholder="Phone" />
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                    <div class="form-group">
                        <textarea name="comment" id="comment" class="form-control" style="height:100px" placeholder="Message"></textarea>
                    </div>
                    <input type="submit" value="Popularity" class="btn_1" id="submit_enquiry" />
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal review --> 
@endsection
@section('footer')

<!-- Check and radio inputs -->
<script src="{{ url("/public/basicfront/js/icheck.min.js") }}"></script>
<script src="{{ url("/public/basicfront/js/jquery.slimscroll.min.js") }}"></script>
<script>
$('input').iCheck({
    checkboxClass: 'icheckbox_square-grey',
    radioClass: 'iradio_square-grey'
});
</script>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on("change", "#sort_by", function () {
        $("#frmExperience").submit();
    });

    $(document).on("ifChanged", "#frmExperience input[name^=popular]", function () {
        $("#frmExperience").submit();
    });

    $(document).on("ifChecked", "#frmExperience input[name^=scategory], #frmExperience input[name^=sdestination]", function () {
        $("#frmExperience").submit();
    });

    $(".slim-scroll").slimScroll({
        height: '220px'
    });


    var price_range = $("#price_range");
    price_range.ionRangeSlider({
        onFinish:function() {
            $("#frmExperience").submit();
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on("click", "a[id^=btnListReserve]", function () {
        bkfrmAction = APP_URL + '/reservation';
        if($(this).attr("data-exp-name") == "AyurYoga Eco-Ashram Mysore India") {
            bkfrmAction = APP_URL + '/redirect-to-portal';
        }
        $("#frmBooking").attr("action", bkfrmAction);
        $("#frmBooking #hdn_experience_id").val($(this).attr("data-exp-id"));
        $("#frmBooking").submit();
        
    });
    $(document).on("click", "a.btn-send-inquiry", function () {
        $("#frmInquiry #experience_id").val($(this).attr("data-exp-id"));
    });
    $("#frmInquiry").validate()
    $("#frmInquiry").on("submit", function () {
        if ($("#frmInquiry").valid()) {
            experienceId = $("#frmInquiry #experience_id").val();
            $("#submit_enquiry").attr("disabled", true);
            $.ajax({
                url: APP_URL + "/store-inquiry",
                method: "POST",
                data: $("#frmInquiry").serialize() + "&experienceId=" + experienceId,
                success: function (result) {
                    if (result) {
                        $("#submit_enquiry").after(' <div class="alert alert-danger">' + result + '</div>');
                    } else {
                        $("#frmInquiry")[0].reset();
                        $("#submit_enquiry").after('<div class="alert alert-success">Your Inquiry has been saved successfully!</div>');
                    }
                    $("#submit_enquiry").attr("disabled", false);
                }
            });
        }
        return false;
    });
    
    /*var page = 1;
    var ajaxLoading = false;
    var stop = 0;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= ($(document).height()-200) - $(window).height() - $('footer').height()) {
            if (!ajaxLoading && stop == 0) {
                ajaxLoading = true;
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page) {
        $.ajax({
            url: '<?php echo URL::current();?>?page=' + page,
            type: "get",
            data: $("#frmExperience").serialize(),
            beforeSend: function () {
                $('.ajax-load').show();
            }
        }).done(function (data) {
            if (data.html == "") {
                //$('.ajax-load').html("No more records found");
                stop = 1;
                return;
            }
            $('.ajax-load').hide();
            $("#experience_data").append(data.html);
            $('.lazy').Lazy();
            ajaxLoading = false;
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });
    }*/
});
</script>
@endsection