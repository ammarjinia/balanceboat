@extends('layouts.front')
@section('title', 'Teachers')
@section('head')
<!-- Radio and check inputs -->
<link href="{{ url("/public/basicfront/css/skins/square/grey.css") }}" rel="stylesheet">

<!-- Range slider -->
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.css") }}" rel="stylesheet">
<link href="{{ url("/public/basicfront/css/ion.rangeSlider.skinFlat.css") }}" rel="stylesheet">
@endsection
@section('banner')
<section class="home" id="hero" style="height: 300px !important;">
    <div class="intro_title">
        <h3 class="animated fadeInDown">Teachers</h3>
    </div>
    <!-- /search_bar-->
</section>
<!-- End hero -->
@endsection

@section('content')
<div class="container">
    <div class="margin_30"></div>
    <form id="frmExperience" action="" method="GET">
        <div class="row">
            <aside class="col-lg-3 col-md-3">
                <div id="filters_col">
                    <a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt"><i class="icon_set_1_icon-65"></i>Filters <i class="icon-plus-1 pull-right"></i></a>
                    <div class="collapse" id="collapseFilters">
                        <div class="text-right"><a href="{{ url("/teachers") }}" class="label label-default search-clear">Clear</a></div>
                    </div>
                    <!--End filters col-->
            </aside>
            <!--End aside -->

            <div class="col-lg-9 col-md-9">
                <div id="tools">
                    <div class="row">
                        <div class="col-md-8 col-sm-8 col-xs-8"></div>
                        <div class="col-md-4 col-sm-4 hidden-xs text-right">
                            <div class="styled-select-filters">
                                <select name="sort_by" id="sort_by" class='form-control'>
                                    <option value="newest" {{ (@$sort_by == "newest") ? "selected":"" }}>Newest First</option>
                                    <option value="ranking" {{ (@$sort_by == "ranking") ? "selected":"" }}>Ranking</option>
                                    <option value="price_asc" {{ (@$sort_by == "price_asc") ? "selected":"" }}>Price - Low to High</option>
                                    <option value="price_desc" {{ (@$sort_by == "price_desc") ? "selected":"" }}>Price - High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/tools -->

                <div id="experience_data">
                    @foreach (@$teachers as $teacher) 
                    @include('content-teachers', (array)$teacher)
                    @endforeach
                </div>
                <p class="text-center add_bottom_10 ajax-load text-center" style="display:none">
                    <!-- a href="#" class="button_intro btn-load-more">Load More </a -->
                </p>
                <!--End strip -->
            </div>
            <!-- End col lg-9 -->
        </div>
    </form>
    <!-- End row -->
</div>
<!-- End container -->
@endsection
<!-- Inquiry -->

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
<script type="text/javascript" src="{{ url("/public/basicfront/js/teachers.js?v=1") }}"></script>
@endsection