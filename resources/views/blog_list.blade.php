@extends('layouts.blog_inner_layout')
@section('title', "Balanceboat Blog | A collection of articles and weekly centre reviews")
<!-- Meta Info Start-->
<meta name="robots" content="noindex">
@section('meta_title', "Balanceboat Blog | A collection of articles and weekly centre reviews")
@section('description', "Collection of articles about yoga, ayurveda, meditation and pilates. Weekly centre reviews of popular centres and what they have to offer and much more")
@section('image', asset('public/basicfront/images/banner/blog.png'))

<!-- Meta Info End -->
@section('content')
@if ($objCategory)
<div class="axil-breadcrumb-area breadcrumb-style-1 bg-color-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="inner">
                    <h1 class="page-title">{{ $objCategory->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="post-single-wrapper axil-section-gap1 bg-color-white">
    <h1 class="m-5 text-center">Blog</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if(@$blogs)
                @foreach(@$blogs as $blog)
                <div class="content-block post-list-view mt--30">
                    <div class="post-thumbnail">
                        <?php
                        $imgsrc = asset('public/blog_assets/images/post-images/post-sm-01.jpg');
                        if (!empty(@$blog->banner_image_url)) {
                            $imgsrc = Storage::disk('azure')->url(rawurlencode(@$blog->banner_image_url));
                        }
                        ?>
                        <a href="{{ url("/blog/".@$blog->slug) }}">
                            <img src="{{ $imgsrc }}" alt="" />
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a class="hover-flip-item-wrapper" href="{{ url('blog-list/'.@$blog->category->slug) }}">
                                    <span class="hover-flip-item">
                                        <span data-text="{{ @$blog->category->name }}">{{ @$blog->category->name }}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <h4 class="title"><a href="{{ url("/blog/".@$blog->slug) }}">{{ @$blog->name }}</a></h4>
                        <div class="post-meta-wrapper">
                            <div class="post-meta">
                                <div class="content">
                                    <ul class="post-meta-list">
                                        <li>{{ \Carbon\Carbon::parse(@$blog->created_at)->format("F d,Y") }}</li>
                                        <li>{{ (@$blog->total_views) ? "(".@$blog->total_views." views)": "" }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="col-lg-4">
                <!-- Start Sidebar Area  -->
                <div class="sidebar-inner">
                    @if(@$blog_listings)
                    <!-- Start Single Widget  -->
                    <div class="axil-single-widget widget widget_postlist mb--30">
                        <h5 class="widget-title">Centres</h5>
                        <!-- Start Post List  -->
                        <div class="post-medium-block">
                            @foreach (@$blog_listings as $listing) 
                            <!-- Start Single Post  -->
                            <div class="content-block post-medium mb--20">
                                <div class="post-thumbnail">
                                    <?php
                                    $imgsrc = asset('public/basicfront/images/all/1.jpg');
                                    if (!empty($listing->banner_image_url)) {
                                        $imgsrc = Storage::disk('azure')->url(rawurlencode(@$listing->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("location/".\App\Http\Helpers\CommonHelper::create_slug($listing->country)."/".\App\Http\Helpers\CommonHelper::create_slug($listing->city)."/".$listing->slug) }}">
                                        <img src="{{ $imgsrc }}" alt="{{ @$listing->name.' '.@$listing->country.' '.@$listing->city }}" class="img-tile lazy" />
                                    </a>
                                </div>
                                <div class="post-content">
                                    <h6 class="title">
                                        <a href="{{ url("location/".\App\Http\Helpers\CommonHelper::create_slug($listing->country)."/".\App\Http\Helpers\CommonHelper::create_slug($listing->city)."/".$listing->slug) }}">
                                            {{ @$listing->name }}
                                        </a>
                                    </h6>
                                    <div class="listing-rating card-popup-rainingvis" data-starrating2="{{ round(@$listing->avg_rating * 2)/2 }}"> {{ (@$listing->total_review) ? "(".@$listing->total_review." reviews)": "" }}</div>
                                    <div class="post-meta">
                                        <ul class="post-meta-list">
                                            <li><i class="fas fa-map-marker-alt"></i>&nbsp;{{ @$listing->city.", ".@$listing->state }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Post  -->
                            @endforeach
                        </div>
                        <!-- End Post List  -->
        
                    </div>
                    <!-- End Single Widget  -->
                    @endif
                    
                    @if(@$blogs)
                    <!-- Start Single Widget  -->
                    <div class="axil-single-widget widget widget_postlist mb--30">
                        <h5 class="widget-title">Popular on Posts</h5>
                        <!-- Start Post List  -->
                        <div class="post-medium-block">
                            @foreach(@$blogs as $blog)
                            <!-- Start Single Post  -->
                            <div class="content-block post-medium mb--20">
                                <div class="post-thumbnail">
                                    <a href="{{ url("/blog/".@$blog->slug) }}" class="widget-posts-img">
                                        <img src="{{ Storage::disk('azure')->url(@$blog->banner_image_url) }}" alt="{{ @$blog->name }}">
                                    </a>
                                </div>
                                <div class="post-content">
                                    <h6 class="title">
                                        <a href="{{ url("/blog/".@$blog->slug) }}" title="">{{ @$blog->name }}</a>
                                    </h6>
                                    <div class="post-meta">
                                        <ul class="post-meta-list">
                                            <li>{{ \Carbon\carbon::parse(@$blog->created_at)->format("F d,Y") }}</li>
                                            <li>{{ (@$blog->total_views) ? "(".@$blog->total_views." views)": "" }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Post  -->
                            @endforeach
                        </div>
                        <!-- End Post List  -->
                    </div>
                    <!-- End Single Widget  -->
                    @endif
                    <!-- End Single Widget  -->
                </div>
                <!-- End Sidebar Area  -->
            </div>
        </div>
    </div>
</div>
<!-- End Post Single Wrapper  -->
@if(@$recent_blogs)
<div class="axil-more-stories-area axil-section-gap bg-color-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="title">Recent Posts</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach(@$recent_blogs as $objBlog)
            <!-- Start Stories Post  -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <!-- Start Post List  -->
                <div class="post-stories content-block mt--30">
                    <div class="post-thumbnail">
                        <?php
                        $imgsrc = asset('public/blog_assets/images/post-images/post-sm-01.jpg');
                        if (!empty(@$objBlog->banner_image_url)) {
                            $imgsrc = Storage::disk('azure')->url(rawurlencode(@$objBlog->banner_image_url));
                        }
                        ?>
                        <a href="{{ url("/blog/".@$objBlog->slug) }}">
                            <img src="{{ $imgsrc }}" alt="Post Images" />
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="javascript:void(0);">{{ @$objBlog->category->name }}</a>
                            </div>
                        </div>
                        <h5 class="title">
                            <a href="{{ url("/blog/".@$objBlog->slug) }}">{{ @$objBlog->name }}</a>
                        </h5>
                    </div>
                </div>
                <!-- End Post List  -->
            </div>
            <!-- Start Stories Post  -->
            @endforeach
        </div>
    </div>
</div>
@endif
@if(@$gurus)
<div class="axil-more-stories-area axil-section-gap bg-color-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="title">Popular Gurus</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach (@$gurus as $teacher)
            <!-- Start Stories Post  -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <!-- Start Post List  -->
                <div class="post-stories content-block mt--30">
                    <div class="post-thumbnail">
                        <?php
                        $imgsrc = asset('public/basicfront/images/team/1.jpg');
                        if (!empty($teacher->profile_image_url)) {
                            $imgsrc = Storage::disk('azure')->url(rawurlencode(@$teacher->profile_image_url));
                        }
                        ?>
                        <a href="{{ url("teacher/".$teacher->slug) }}"><img src="{{ $imgsrc }}" alt="" /></a>
                    </div>
                    <div class="post-content">
                        <h5 class="title">
                            <a href="{{ url("teacher/".$teacher->slug) }}" aria-label="{{ @$teacher->name }}" title="{{ @$teacher->name }}" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;display: block;">
                                {{ @$teacher->name }}
                            </a>
                        </h5>
                    </div>
                </div>
                <!-- End Post List  -->
            </div>
            <!-- Start Stories Post  -->
            @endforeach
        </div>
    </div>
</div>
@endif
@if(@$courses)
<div class="axil-more-stories-area axil-section-gap bg-color-extra03">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="title">Courses</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach (@$courses as $objCourse) 
            <!-- Start Stories Post  -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <!-- Start Post List  -->
                <div class="post-stories content-block mt--30">
                    <div class="post-thumbnail">
                        <?php
                        $imgsrc = asset('public/basicfront/images/all/1.jpg');
                        if (!empty($objCourse->image_url)) {
                            $imgsrc = Storage::disk('azure')->url(rawurlencode(@$objCourse->image_url));
                        }
                        ?>
                        <a href="{{ url("course/".$objCourse->slug) }}"><img src="{{ $imgsrc }}" alt="" /></a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="javascript:void(0);">{{ @$objCourse->categoryname }}</a>
                            </div>
                        </div>
                        <h5 class="title">
                            <a href="{{ url("course/".$objCourse->slug) }}">{{ @$objCourse->name }}</a>
                        </h5>
                    </div>
                </div>
                <!-- End Post List  -->
            </div>
            <!-- Start Stories Post  -->
            @endforeach
        </div>
    </div>
</div>
@endif
@if(@$videos)
<div class="axil-more-stories-area axil-section-gap bg-color-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="title">Videos</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach (@$videos as $objVideo) 
            <!-- Start Stories Post  -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <!-- Start Post List  -->
                <div class="post-stories content-block mt--30">
                    <div class="post-thumbnail">
                        <?php
                        $imgsrc = asset('public/basicfront/images/all/1.jpg');
                        if (!empty($objVideo->image_url)) {
                            $imgsrc = Storage::disk('azure')->url(rawurlencode(@$objVideo->image_url));
                        }
                        ?>
                        <a href="{{ url("video/".$objVideo->slug) }}"><img src="{{ $imgsrc }}" alt="" /></a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="javascript:void(0);">{{ @$objVideo->categoryname }}</a>
                            </div>
                        </div>
                        <h5 class="title">
                            <a href="{{ url("video/".$objVideo->slug) }}">{{ @$objVideo->name }}</a>
                        </h5>
                    </div>
                </div>
                <!-- End Post List  -->
            </div>
            <!-- Start Stories Post  -->
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
@section('footer')
<script src="{{ url('public/basicfront/js/jquery.validate.min.js') }}"></script>
@endsection