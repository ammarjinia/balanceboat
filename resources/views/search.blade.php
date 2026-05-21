@extends('layouts.front')
@section('title', 'Experience')
@section('banner')
<section id="hero">
    <div class="intro_title">
        <h3 class="animated fadeInDown">EXPERIENCE</h3>
        <p class="animated fadeInDown">The best of wellness, spirituality &amp; holistic health around the world </p>
    </div>
    <?php /*@include('layouts.searchbar')*/?>
</section>
<!-- End hero -->
@endsection

@section('content')
<section class="white_bg">
    <div class="container">
        <form id="frmExperience" action="" method="GET">
            <div id="tools">
                <div class="row">
                    <div class="col-md-6 col-sm-6 hidden-xs text-right"></div>
                    <div class="col-md-3 col-sm-3 col-xs-6 text-right">
                        <label style="margin-top:8px;">Sort by</label>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <select name="sort_by" id="sort_by" class='form-control'>
                            <option value="newest" {{ (@$sort_by == "newest") ? "selected":"" }}>Newest First</option>
                            <option value="ranking" {{ (@$sort_by == "ranking") ? "selected":"" }}>Popularity</option>
                            <option value="price_asc" {{ (@$sort_by == "price_asc") ? "selected":"" }}>Price - Low to High</option>
                            <option value="price_desc" {{ (@$sort_by == "price_desc") ? "selected":"" }}>Price - High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <div class="row  ">
            <div class="col-lg-12 col-md-12">
                <div id="experience_data">
                    @foreach (@$experiences as $experience) 
                    @include('content-experience', (array)$experience)
                    @endforeach
                </div>
                <p class="text-center add_bottom_10 ajax-load text-center" style="display:{{ (sizeof(@$experiences) >= 10) ? "none" : "" }}">
                    @if(sizeof(@$experiences) >= 10)
                        <a href="#" class="button_intro btn-load-more">Load More </a>
                    @else
                        No more records found
                    @endif
                </p>
                <!--End strip -->
            </div>
        </div>
    </div>
    <!-- End container -->
</section>
<!-- End section -->
@endsection
@section('footer')
<script type="text/javascript" src="{{ url("/public/basicfront/js/search-experience.js") }}"></script>
@endsection