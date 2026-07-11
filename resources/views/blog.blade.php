@extends('layouts.blog_layout')
@section('title', 'Articles')
@section('content')
<!-- Start Banner Area -->
        <h1 class="d-none">Articles</h1>
        <div class="axil-tech-post-banner pt--30 bg-color-grey">
            <div class="container">
                <div class="row">
                    <div class="row">
                        <div class="col-xl-6 col-md-12 col-12">
                            <!-- Start Post Grid  -->
                            <div class="content-block post-grid post-grid-transparent">
                                <div class="post-thumbnail">
                                    <?php
                                    $imgsrc = asset('basicfront/images/bg/1.jpg');
                                    if (!empty($blogs[0]->banner_image_url)) {
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode($blogs[0]->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("/blog/".@$blogs[0]->slug) }}">
                                        <img src="{{ $imgsrc }}" alt="">
                                    </a>
                                </div>
                                <div class="post-grid-content">
                                    <div class="post-content">
                                        <h3 class="title">
                                            <a href="{{ url("/blog/".@$blogs[0]->slug) }}">{{ @$blogs[0]->name }}</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <!-- Start Post Grid  -->
                        </div>

                        <div class="col-xl-3 col-md-6 mt_lg--30 mt_md--30 mt_sm--30 col-12">
                            <!-- Start Single Post  -->
                            <div class="content-block image-rounded">
                                <div class="post-thumbnail">
                                    <?php
                                    $imgsrc = asset('basicfront/images/bg/1.jpg');
                                    if (!empty($blogs[1]->banner_image_url)) {
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode($blogs[1]->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("/blog/".@$blogs[1]->slug) }}">
                                        <img src="{{ $imgsrc }}" alt="">
                                    </a>
                                </div>
                                <div class="post-content pt--20">
                                    <h5 class="title">
                                        <a href="{{ url("/blog/".@$blogs[1]->slug) }}">{{ @$blogs[1]->name }}</a>
                                    </h5>
                                </div>
                            </div>
                            <!-- End Single Post  -->
                            <!-- Start Single Post  -->
                            <div class="content-block image-rounded mt--30">
                                <div class="post-thumbnail">
                                    <?php
                                    $imgsrc = asset('basicfront/images/bg/1.jpg');
                                    if (!empty(@$blogs[2]->banner_image_url)) {
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode(@$blogs[2]->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("/blog/".@$blogs[2]->slug) }}">
                                        <img src="{{ $imgsrc }}" alt="">
                                    </a>
                                </div>
                                <div class="post-content pt--20">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                <span class="hover-flip-item">
                                                    <span data-text="{{ @$blogs[2]->category->name }}">{{ @$blogs[2]->category->name }}</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="title">
                                        <a href="{{ url("/blog/".@$blogs[2]->slug) }}">{{ @$blogs[2]->name }}</a>
                                    </h5>
                                </div>
                            </div>
                            <!-- End Single Post  -->
                        </div>

                        <div class="col-xl-3 col-md-6 mt_lg--30 mt_md--30 mt_sm--30 col-12">
                            <!-- Start Single Post  -->
                            <div class="content-block image-rounded">
                                <div class="post-thumbnail">
                                    <?php
                                    $imgsrc = asset('basicfront/images/bg/1.jpg');
                                    if (!empty(@$blogs[3]->banner_image_url)) {
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode(@$blogs[3]->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("/blog/".@$blogs[3]->slug) }}">
                                        <img src="{{ $imgsrc }}" alt="" style="height:195px;" />
                                    </a>
                                </div>
                                <div class="post-content pt--20">
                                    <h5 class="title">
                                        <a href="{{ url("/blog/".@$blogs[3]->slug) }}">{{ @$blogs[3]->name }}</a>
                                    </h5>
                                </div>
                            </div>
                            <!-- End Single Post  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Banner Area -->

        <!-- Start Post Grid Area  -->
        <div class="axil-post-grid-area axil-section-gap bg-color-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8 col-sm-8 col-12">
                        <div class="section-title">
                            <h2 class="title">Most Popular</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-4 col-12">
                        <div class="see-all-topics text-left text-sm-right mt_mobile--20">
                            <a class="axil-link-button" href="{{ url('blog-list/') }}">See All</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Start Tab Content  -->
                        <div class="grid-tab-content tab-content">

                            <!-- Start Single Tab Content  -->
                            <div class="single-post-grid tab-pane fade active show" id="gridone" role="tabpanel">
                                <div class="row  mt--40">
                                        <!-- Start Post Medium  -->
                                        @if(@$blogs)
                                        @foreach(@$blogs as $mpblog)
                                    <div class="col-sm-12 col-lg-6 col-md-6 col-12 mt--40">
                                        <div class="content-block post-medium post-medium-border border-thin">
                                            <div class="post-thumbnail">
                                                <?php
                                                $imgsrc = asset('blog_assets/images/post-images/post-sm-01.jpg');
                                                if (!empty(@$mpblog->banner_image_url)) {
                                                    $imgsrc = Storage::disk('s3')->url(rawurlencode(@$mpblog->banner_image_url));
                                                }
                                                ?>
                                                <a href="javascript:void(0);">
                                                    <img src="{{ $imgsrc }}" alt="" style="height:100px;" />
                                                </a>
                                            </div>
                                            <div class="post-content">
                                                <h4 class="title">
                                                    <a href="{{ url("/blog/".@$mpblog->slug) }}">{{ @$mpblog->name }}</a>
                                                </h4>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                        @endforeach
                                        @endif
                                        <!-- End Post Medium  -->
                                </div>
                            </div>
                            <!-- End Single Tab Content  -->

                        </div>
                        <!-- End Tab Content  -->
                    </div>
                </div>

            </div>
        </div>
        <!-- End Post Post Area  -->
        
@endsection
@section('footer')
@endsection