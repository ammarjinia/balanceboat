@extends('layouts.front')
@section('title', "Best Deals")
@section('meta_title', "Best Deals")
@section('content')
<section class="white_bg" style="padding-top:50px;">
    <div class="container margin_60">
        <div class="main_title">
            <h2>Best Deals</h2>
            <p><i>Checkout some of the most popular deals on BalanceBoat</i></p>
        </div>
        <div id="experience_data">
            @if(@$objDeals)
                @foreach($objDeals as $objDeal)
                <div class="strip_all_tour_list wow fadeIn item">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            @if($objDeal->image_url)
                            <div class="img_list">
                                <a href="{!! url('/deal/'.$objDeal->slug) !!}">
                                    <img data-src="{!! Storage::disk('s3')->url($objDeal->image_url) !!}" alt="{{ $objDeal->image_title }}" class="img-responsive lazy" /> 
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="clearfix visible-xs-block"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-left">
                            <div class="tour_list_desc" style="height:auto;margin-top:10px;">
                                <a href="{!! url('/deal/'.$objDeal->slug) !!}"><h3 style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">{{ $objDeal->name }}</h3></a>
                                <?php if (@$objDeal->description) { ?>
                                <div style="height:146px;overflow:hidden;">
                                    {!! @$objDeal->description !!}
                                </div>    
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs hidden-sm">
                            <div class="price_list" style="margin-top:10px;height:210px;">
                                <a href="{!! url('/deal/'.$objDeal->slug) !!}" class="btn btn_1 btn-block text-center">View Deal</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    <!--End container -->
</section>
<!--End section -->

@endsection