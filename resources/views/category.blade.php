@extends('layouts.front')
@section('title', $category->name)
<!-- Meta Info Start-->
@section('meta_title', $category->name)
<?php if (!empty(@$category->description)) { ?> 
    @section('description', @$category->description)
<?php } ?>
<?php if (!empty(@$category->keywords)) { ?> 
    @section('keywords', @$category->keywords)
<?php } ?>
<?php if (!empty(@$category->banner_image_url)) { ?> 
    @section('image', Storage::disk('azure')->url(@$category->banner_image_url))
<?php } ?>
<!-- Meta Info End -->

@section('banner')
<section id="hero" style="<?php if (!empty(@$category->banner_image_url)) { ?> background-image: url('{{ Storage::disk('azure')->url(@$category->banner_image_url) }}'); <?php } ?>">
    <div class="intro_title">
        <h3 class="animated fadeInDown">{{ @$category->name }}</h3>
        <p class="animated fadeInDown"></p>
    </div>
    @include('layouts.searchbar')
    <!-- /search_bar-->
</section>
<!-- End hero -->
@endsection
@section('content')

<section class="container padding_t_30 margin_b_20">
    <div class="main_title margin_b_20">
        <h2>{{ $category->name }} Experiences</h2>
        <!--p><i>{{ $category->description }}</i></p-->
    </div>
    @if(count($subcategories) > 0 && !empty($subcategories))
    <div class="row">      
        <div class="col-md-12 wow zoomIn text-center txt-up" data-wow-delay="0.1s">
            <?php $i = 1; ?>   
            @foreach ($subcategories as $subcategory) 
            <a href="{{ url("category/".$category->slug."/".$subcategory->slug)}}" class="btn_1 medium font18 btn-border">{{ $subcategory->name }}</a>
            <?php $i++; ?>
            @endforeach
        </div>
    </div>
    @endif
    <!-- End row -->
</section>
<!-- End section -->

<section class="white_bg">
    <div class="container">
        <div class="main_title">
            <h2>Popular Experiences</h2>
            <p><i>Checkout some of the most popular experiences on BalanceBoat</i></p>
        </div>
        <div class="row  ">
            <div class="col-lg-12 col-md-12">
                @foreach (@$experiences as $experience) 
                @include('content-experience', (array)$experience)
                @endforeach
                @if(count(@$experiences) > 100)
                <p class="text-right add_bottom_10">
                    <a href="{{ url("experiences")}}" class="button_intro">VIEW ALL EXPERIENCES >> </a>
                </p>
                @endif
                <!--End strip -->
            </div>
        </div>
        @if(count((array)@$destinations) > 1 && !empty(@$destinations))
        <div class="row">
            <div class="col-md-12">
                <div class="main_title">
                    <h2>Popular <span>Destinations</span></h2>
                    <p>Checkout some of the most polpular destination on BalanceBoat</p>
                </div>
            </div>
            {{-- Loop thru categories type=0 and display_on_home=1 --}}     
            @foreach ($destinations as $val_catesDestinations_top)
            <div class="col-md-3 col-sm-6 wow zoomIn" data-wow-delay="0.1s">
                <div class="tour_container tour_container1">
                    <div class="img_container">
                        <a href="{{ url()->current()."?destination=".$val_catesDestinations_top->slug }}">
                            @if($val_catesDestinations_top->image_url)
                            <img src="{{ Storage::disk('azure')->url($val_catesDestinations_top->image_url) }}" class="img-responsive" alt="{{ $val_catesDestinations_top->image_title }}" />
                            @endif
                        </a>
                    </div>
                </div>
                <!-- End box tour -->
                <h4 class="text-center"><a href="{{ url()->current()."?destination=".$val_catesDestinations_top->slug }}">{{ $val_catesDestinations_top->name }}</a></h4>
            </div>
            <!-- End col-md-4 -->
            @endforeach
        </div>
        <!-- End row -->
        @endif
    </div>
    <!-- End container -->
</section>
<!-- End section -->
@endsection
@section('footer')
@endsection