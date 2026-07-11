@extends('layouts.blog_inner_layout_amp')
@section('title', @$blog->name)
@section('meta_title', @$blog->meta_title)
<?php if (!empty(@$blog->meta_description)) { ?> 
    @section('description', strip_tags(@$blog->meta_description))
<?php } ?>
<?php if (!empty(@$blog->meta_keywords)) { ?> 
    @section('keywords', strip_tags(@$blog->meta_keywords))
<?php } ?>
<?php if (!empty(@$blog->banner_image_url)) { ?> 
    @section('image', Storage::disk('s3')->url(@$blog->banner_image_url))
<?php }
$arImgAr = array(
    @$blog->name,
);
?>
<?php
if($objAdverts) {?>
@section('top-ad')
<section class="banner-ad">
    <span class="close bgLightGallery__Close"></span>
    <div class="container">
        <amp-carousel width="400" height="400" layout="responsive" type="slides" autoplay delay="5000" aria-label="Ads.">
            <?php
            foreach ($objAdverts as $objAdvert) {
            $url_top_img = Storage::disk('s3')->url($objAdvert->banner_image_url);
            $url = \Crypt::encrypt($objAdvert->website);
            ?>
            <div>
                  <div class="bg-row align-center">
                    <div class="bg-col-xl-50">
                        <h2>{!! $objAdvert->banner_image_title !!}</h2>
                        <h3 class="fs-14 line-2">{!! $objAdvert->sub_title !!}</h3>
                        <a class="call-back btn btn--primary" href="{{ url('click-ads?position=top&id='.$blog->id.'&url='.$url) }}" target='_blank'>Enquire</a>
                    </div>
                    <div class="bg-col-xl-50 mt-20">
                      <div class="mb-60 c-pointer" tabindex="1" role="Book Online" >
                          <a class="call-back btn btn--primary" href="{{ url('click-ads?position=top&id='.$blog->id.'&url='.$url) }}" target='_blank'>
                          <amp-img src="<?php echo $url_top_img;?>" width="500" height="300" layout="intrinsic" alt="Logo"></amp-img>
                          </a>
                      </div>
                    </div>
                  </div>
            </div>
            <?php }?>
        </div>
    </div>
</section>
@endsection 
<?php }?>
<!-- Meta Info End -->
@section('content')
<?php
$imgsrc = asset('public/basicfront/images/bg/1.jpg');
if (!empty($blog->banner_image_url)) {
    $imgsrc = Storage::disk('s3')->url(@$blog->banner_image_url);
}?>
<script type="application/ld+json">
{
  "datePublished":"<?php echo \Carbon\Carbon::parse(@$blog->created_at)->format('Y-m-d');?>",
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo @$blog->id;?>"
  },
  "headline": "<?php echo @$blog->name;?>",
  "image": {
       "@type": "ImageObject",
       "url": "<?php echo $imgsrc;?>",
       "height": 800,
       "width": 800
     },    
  "author": {
    "@type": "Organization",
    "name": "Balance Boat"
  },  
  "publisher": {
    "@type": "Organization",
    "name": "BalanceBoat",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo url('/public/blog_assets/images/logo/balanceboat-logo.png');?>"
    }
  }
}
</script>
<section class="blog">
            <div class="container">
                <?php
                if($blog->topbar_adv_image) {?>
                <div class="mb-60 c-pointer" on="tap:quote-lb" tabindex="1" role="Book Online" >
                <!--<a href="!#" style="margin:0 auto 20px;display:block;text-align:center;" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url("/blog/".@$blog->slug) }}">-->
                    <amp-img src="{{ Storage::disk('s3')->url(@$blog->topbar_adv_image) }}" width="750" height="500" layout="intrinsic" alt="{{ @$blog->name }}"></amp-img>
                <!--</a>-->
                </div>
                <?php }?>
                <div class="bg-row">
                    <div class="bg-col-12 bg-col-lg-75 bg-col-xl-66">
                        <!-- Start Banner Area -->
                        <div class="banner banner-single-post post-formate post-layout axil-section-gapBottom">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Start Single Slide  -->
                                        <div class="content-block">
                                            <!-- Start Post Content  -->
                                            <div class="post-content">
                                                <!--div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="!#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="{{ @$bannerYoga->category->name }}">{{ @$bannerYoga->category->name }}</span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div-->
                                                <h1 class="title">{{ @$blog->name }}</h1>
                                                <!-- Post Meta  -->
                                                <div class="post-meta-wrapper">
                                                    <div class="post-meta">
                                                        <div class="content">
                                                            <ul class="post-meta-list">
                                                                <li>{{ (@$blog->total_views) ? "(".@$blog->total_views." views)": "" }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Post Content  -->
                                        </div>
                                        <!-- End Single Slide  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Banner Area -->
                        <div class="axil-post-details">
                            <?php
                                $imgsrc = asset('public/basicfront/images/bg/1.jpg');
                                if (!empty($blog->banner_image_url)) {
                                    $imgsrc = Storage::disk('s3')->url(rawurlencode(@$blog->banner_image_url));
                                }
                                ?>
                                <amp-img src="<?php echo $imgsrc;?>" width="750" height="500" layout="intrinsic" alt=""></amp-img>
                            </figure>
                            <?php /*{!! \App\Http\Helpers\CommonHelper::ampify_img(@$blog->description) !!}*/?>
                            <?php
                            $description = str_replace("<img",'<amp-img width="750" height="500" layout="intrinsic"', @$blog->description);
                            $description1 = str_replace("<iframe",'<amp-iframe width="750" height="500" layout="intrinsic"', $description);
                            ?>
                            {!! $description1 !!}
                            <?php
                            if($blog->slug == "most-popular-indian-female-spiritual-yogi-s-you-should-know-and-follow") {?>
                            <div class="mb-60 c-pointer" on="tap:quote-lb" tabindex="1" role="Book Online" >
                            <!--<a href="!#" style="margin:0 auto 20px;display:block;text-align:center;" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url("/blog/".@$blog->slug) }}">-->
                                <amp-img width="750" height="500" layout="intrinsic"  src="{{ url('public/blog_assets/images/most-popular-indian-female-spiritual-yogis-you-should-know-and-follow-2.JPG') }}" alt="Most Popular Indian Female spiritual Yogi's you should know and follow"></amp-img>
                            <!--</a>-->
                            </div>
                            <?php }?>
                            @if(@$blog_gallery)
                            <!--div class="list-single-main-item fl-wrap">
                                <div class="list-single-main-item-title fl-wrap">
                                    <h3>Gallery</h3>
                                </div>
                                <div class="gallery-items grid-small-pad  list-single-gallery three-coulms lightgallery" style="position: relative; height: 341.766px;">
                                    @foreach (@$blog_gallery as $objGallery)
                                    <div class="gallery-item" style="position: absolute; left: 0px; top: 0px;">
                                        <div class="grid-item-holder">
                                            <div class="box-item">
                                                <amp-img src="{{ Storage::disk('s3')->url($objGallery->image_url) }}" alt="{{ $arImgAr[array_rand($arImgAr,1)] }}"></amp-img>
                                                <a href="{{ Storage::disk('s3')->url($objGallery->image_url) }}" class="gal-link popup-image"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>                              
                            </div-->
                            @endif
                            <p></p>
                </div>
            </div>
                    <div class="bg-col-12 bg-col-lg-25 bg-col-xl-33">
                        <?php /*
                        <!-- Start Sidebar Area  -->
                        <div class="sidebar-inner">
                            <!-- Start Single Widget  -->
                            @if(@$blog->tags)
                            <div class="axil-single-widget widget widget_categories mb--30">
                                <ul>
                                    @foreach(explode(",", @$blog->tags) as $tag)
                                    <li class="cat-item">
                                        <a href="!#" class="inner">
                                            <div class="content">
                                                <h5 class="title">{{ $tag }}</h5>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <!-- End Single Widget  -->
                            */?>
                           <?php /* @if(!@$blog_listings->isEmpty())
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
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode(@$listing->banner_image_url));
                                    }
                                    ?>
                                    <a href="{{ url("location/".\App\Http\Helpers\CommonHelper::create_slug($listing->country)."/".\App\Http\Helpers\CommonHelper::create_slug($listing->city)."/".$listing->slug) }}">
                                        <amp-img src="{{ $imgsrc }}" alt="{{ @$listing->name.' '.@$listing->country.' '.@$listing->city }}" class="img-tile lazy"></amp-img>
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
                    @if(@experiences && !empty(@$experiences))
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_postlist mb--30">
                                <h5 class="widget-title">Popular on Experiences</h5>
                                <!-- Start Post List  -->
                                <div class="post-medium-block">
                                    @foreach(@$experiences as $experience)
                                    <!-- Start Single Post  -->
                                    <div class="content-block post-medium mb--20">
                                        <div class="post-thumbnail">
                                            <a href="{{ "https://balanceboat.com/experience/".$experience->slug }}" class="widget-posts-img">
                                                <amp-img src="{{ ($experience->thumbnail_image_url) ? Storage::disk('s3')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('s3')->url(rawurlencode($experience->banner_image_url)) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy"></amp-img>
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <h6 class="title">
                                                <a href="{{ "https://balanceboat.com/experience/".$experience->slug }}" title="">{{ $experience->name }}</a>
                                            </h6>
                                            <div class="post-meta">
                                                <ul class="post-meta-list">
                                                    <?php
                                                    $pay = @$experience->room_price*1;
                                                    if (!empty(@$experience->totaldiscount)) {?>
                                                    <li>
                                                    <del class="text-default">
                                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), 'USD') }}
                                                    </del>
                                                    </li>
                                                    <?php }?>
                                                    <li>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->final_room_price, 'USD') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Single Post  -->
                                    @endforeach
                                </div>
                                <!-- End Post List  -->
                                <div class="text-center">
                                    <a href="{{ "https://balanceboat.com/experiences?stags=".@$blog->tags }}" class="cerchio axil-button button-rounded" style="transform: matrix(1, 0, 0, 1, 0, 0);"><span>View All</span></a>
                                </div>
                            </div>
                            <!-- End Single Widget  -->
                            @endif
                            */?>
                            <?php /*
                            @if(@$blogs)
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_postlist mb--30">
                                <h5 class="widget-title">Popular on Posts</h5>
                                <!-- Start Post List  -->
                                <div class="post-medium-block">
                                    @foreach(@$blogs as $objBlog)
                                    <!-- Start Single Post  -->
                                    <div class="content-block post-medium mb--20">
                                        <div class="post-thumbnail">
                                            <a href="{{ url("/blog/".@$objBlog->slug) }}" class="widget-posts-img">
                                                <amp-img width="750" height="500" layout="intrinsic" src="{{ Storage::disk('s3')->url(@$objBlog->banner_image_url) }}" alt="{{ $arImgAr[array_rand($arImgAr,1)] }}"></amp-img>
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <h6 class="title">
                                                <a href="{{ url("/blog/".@$objBlog->slug) }}" title="">{{ @$objBlog->name }}</a>
                                            </h6>
                                            <div class="post-meta">
                                                <ul class="post-meta-list">
                                                    <li>{{ \Carbon\carbon::parse(@$objBlog->created_at)->format("F d,Y") }}</li>
                                                    <li>{{ (@$objBlog->total_views) ? "(".@$objBlog->total_views." views)": "" }}</li>
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
                            */?>

                            <!-- Start Single Widget  -->
                            <?php /*<div class="axil-single-widget widget widget_newsletter mb--30">
                                <!-- Start Post List  -->
                                <div class="newsletter-inner text-center">
                                    <h4 class="title mb--15">Never Miss A Post!</h4>
                                    <p class="b2 mb--30">Sign up for free and be the first to <br> get notified about updates.</p>
                                        <form method="post" id="frmSubscription">
                                            <div class="form-group">
                                                <input type="email" name="email" id="email" placeholder="Enter Your Email" class="required" required />
                                            </div>
                                            <div class="form-submit">
                                                <button type="submit" class="cerchio axil-button button-rounded btnSubscription" style="transform: matrix(1, 0, 0, 1, 0, 0);"><span>Subscribe</span></button>
                                            </div>
                                        </form>
                                </div>
                                <!-- End Post List  -->
                            </div>
                            <!-- End Single Widget  -->*/?>
                            <?php /*
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_social mb--30">
                                <h5 class="widget-title">Stay In Touch</h5>
                                <!-- Start Post List  -->
                                <ul class="social-icon md-size justify-content-center">
                                    <li><a href="https://www.facebook.com/balancegurus"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://in.pinterest.com/balancegurus"><i class="fab fa-pinterest"></i></a></li>
                                    <li><a href="https://www.instagram.com/balancegurus"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                                <!-- End Post List  -->
                            </div>
                            <!-- End Single Widget  -->
                            */?>

                            <!-- Start Single Widget  -->
                            <?php /*<div class="axil-single-widget widget widget_instagram mb--30">
                                <h5 class="widget-title">Instagram</h5>
                                <!-- Start Post List  -->
                                <div id="instagram-feed" class="instagram_feed">
                                    
                                </div>
                                <!-- End Post List  -->
                            </div>*/?>
                            <!-- End Single Widget  -->
                            
                            <?php /*
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_video mb--30">
                                <h5 class="widget-title">Featured Videos</h5>
                                <!-- Start Post List  -->
                                <div class="video-post-wrapepr">

                                    <!-- Start Post List  -->
                                    <div class="content-block image-rounded">
                                        <div class="post-content">
                                            <div class="post-thumbnail">
                                                <iframe width="100%" height="220px" src="https://www.youtube.com/embed/_yobVs3d2uo" frameborder="0"  allowfullscreen></iframe>
                                            </div>
                                            <h6 class="title mt--10"><a href="!#">What is Ayurveda and what are its benifits?</a>
                                            </h6>
                                        </div>

                                    </div>
                                    <!-- End Post List  -->

                                    <!-- Start Post List  -->
                                    <div class="content-block image-rounded mt--20">
                                        <div class="post-content">
                                            <div class="post-thumbnail">
                                                <iframe width="100%" height="220px" src="https://www.youtube.com/embed/P2jkZN3QhCE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                            <h6 class="title mt--10"><a href="!#">Learn Nauli Kriya from an expert</a></h6>
                                        </div>

                                    </div>
                                    <!-- End Post List  -->

                                    <!-- Start Post List  -->
                                    <div class="content-block image-rounded mt--20">
                                        <div class="post-content">
                                            <div class="post-thumbnail">
                                                <iframe width="100%" height="220px" src="https://www.youtube.com/embed/X4TvW46DQcI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                            <h6 class="title mt--10"><a href="!#">Top 10 Ultra Luxurious Wellness Retreats India</a></h6>
                                        </div>
                                    </div>
                                    <!-- End Post List  -->
                                    
                                    <!-- Start Post List  -->
                                    <div class="content-block image-rounded mt--20">
                                        <div class="post-content">
                                            <div class="post-thumbnail">
                                                <iframe width="100%" height="220px" src="https://www.youtube.com/embed/qsa2b5OVd94" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                            <h6 class="title mt--10"><a href="!#">Top 7 Luxury Ayurveda Retreats In Sri Lanka</a></h6>
                                        </div>
                                    </div>
                                    <!-- End Post List  -->
                                    
                                    <!-- Start Post List  -->
                                    <div class="content-block image-rounded mt--20">
                                        <div class="post-content">
                                            <div class="post-thumbnail">
                                                <iframe width="100%" height="220px" src="https://www.youtube.com/embed/FqxrasMdbzM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                            <h6 class="title mt--10"><a href="!#">Top 5 Female Spiritual Gurus in India</a></h6>
                                        </div>
                                    </div>
                                    <!-- End Post List  -->

                                </div>
                                <!-- End Post List  -->
                            </div>
                            <!-- End Single Widget  -->
                            */?>
                            <?php
                            if(@$blog->sidebar_adv_image) {?>
                            <div class="mb-60 c-pointer" on="tap:quote-lb" tabindex="1" role="Book Online" >
                            <!--<a href="!#" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url("/blog/".@$blog->slug) }}">-->
                                <amp-img width="750" height="500" layout="intrinsic" src="{{ Storage::disk('s3')->url(@$blog->sidebar_adv_image) }}" alt="{{ @$blog->name }}"></amp-img>
                            <!--
                            </div></a>-->
                            <?php }?>
                            <?php
                            if($objSideAdverts) {?>
                            <amp-carousel width="400" height="400" layout="responsive" type="slides" autoplay delay="5000" aria-label="Ads.">
                                <?php
                                foreach ($objSideAdverts as $objAdvert) {
                                $url_top_img = Storage::disk('s3')->url($objAdvert->banner_image_url);
                                $url = \Crypt::encrypt($objAdvert->website);
                                ?>
                                <div class="item">
                                    <a href="{{ url('click-ads?position=side&id='.$blog->id.'&url='.$url) }}" style="display:block;text-align:center;" target='_blank'>
                                        <img src="<?php echo $url_top_img;?>" alt="{{ @$objAdvert->banner_image_title }}" class='advertisement img-contain mt-4 mt-lg-0' />
                                    </a>
                                </div>
                                <?php }?>
                            </amp-carousel>
                            <?php }?>
                        </div>
                        <!-- End Sidebar Area  -->
                    </div>
                </div>
            </div>    
        </div>
    </div>
</section>
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
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objBlog->banner_image_url));
                        }
                        ?>
                        <a href="{{ url("/blog/".@$objBlog->slug) }}">
                            <amp-img width="750" height="500" layout="intrinsic"src="{{ $imgsrc }}" alt="Post Images"></amp-img>
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="!#">{{ @$objBlog->category->name }}</a>
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
@if(count((array)@$gurus))
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
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$teacher->profile_image_url));
                        }
                        ?>
                        <a href="{{ url("teacher/".$teacher->slug) }}"><amp-img width="750" height="500" layout="intrinsic" src="{{ $imgsrc }}" alt=""></amp-img></a>
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
@if(count((array)@$courses))
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
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objCourse->image_url));
                        }
                        ?>
                        <a href="{{ url("course/".$objCourse->slug) }}"><amp-img width="750" height="500" layout="intrinsic" src="{{ $imgsrc }}" alt=""></amp-img></a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="!#">{{ @$objCourse->categoryname }}</a>
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
@if(count((array)@$videos))
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
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objVideo->image_url));
                        }
                        ?>
                        <a href="{{ url("video/".$objVideo->slug) }}"><amp-img width="750" height="500" layout="intrinsic" src="{{ $imgsrc }}" alt="" ></amp-img></a>
                    </div>
                    <div class="post-content">
                        <div class="post-cat">
                            <div class="post-cat-list">
                                <a href="!#">{{ @$objVideo->categoryname }}</a>
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
<amp-lightbox id="requstcontactPopup" class="modal" layout="nodisplay">
      <div class="bg-modal-wrapper">
        <form id="frmRequestCall" name="bg-modal-container frmRequestCall" method="post" action-xhr="#">
          <div class="m-head p-10 fs-24 flex-between">
            <span><strong>
                @if(@$blog->popup_heading)
                    <h4>{{ @$blog->popup_heading }}</h4>
                @else
                    <h4>Contact Us<</h4>
                @endif
                </strong></span>
            <span class="close c-pointer" on="tap:quote-lb.close" tabindex="1" role="close"><i class="fa fa-times"></i></span>
          </div>
          <div class="m-body p-10 pb-0">
            <div>
              @if(@$blog->popup_description)
                            <p class="mb-3">{{ @$blog->popup_description }}</p>
                @endif
            </div>
            <input type="hidden" name="inquiry_for" id="inquiry_for" value="{{ url("/blog/".@$blog->slug) }}" />
            <input type="hidden" name="inquiry_subject" id="inquiry_subject" value="{{ @$blog->name }}" />
            <input type="hidden" name="ref_url" id="ref_url" value="{{ url()->current() }}" />
            <div class="bg-form-el">
              <input placeholder="Name" type="text" name="name" id="name" required />
            </div>
            <div class="bg-form-el">
              <input placeholder="Email Address" type="email" name="email" id="email" required />
            </div>
            <div class="bg-form-el">
              <select name="country_code" id="country_code" class="">
                                            <option data-countryCode="IN" value="91" selected>India (+91)</option>
                                    	    <option data-countryCode="US" value="1">USA (+1)</option>
                                    		<option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                    		<option data-countryCode="AD" value="376">Andorra (+376)</option>
                                    		<option data-countryCode="AO" value="244">Angola (+244)</option>
                                    		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                    		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                    		<option data-countryCode="AR" value="54">Argentina (+54)</option>
                                    		<option data-countryCode="AM" value="374">Armenia (+374)</option>
                                    		<option data-countryCode="AW" value="297">Aruba (+297)</option>
                                    		<option data-countryCode="AU" value="61">Australia (+61)</option>
                                    		<option data-countryCode="AT" value="43">Austria (+43)</option>
                                    		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                    		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                    		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                    		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                    		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                    		<option data-countryCode="BY" value="375">Belarus (+375)</option>
                                    		<option data-countryCode="BE" value="32">Belgium (+32)</option>
                                    		<option data-countryCode="BZ" value="501">Belize (+501)</option>
                                    		<option data-countryCode="BJ" value="229">Benin (+229)</option>
                                    		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                    		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                    		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                    		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                    		<option data-countryCode="BW" value="267">Botswana (+267)</option>
                                    		<option data-countryCode="BR" value="55">Brazil (+55)</option>
                                    		<option data-countryCode="BN" value="673">Brunei (+673)</option>
                                    		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                    		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                    		<option data-countryCode="BI" value="257">Burundi (+257)</option>
                                    		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                    		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                    		<option data-countryCode="CA" value="1">Canada (+1)</option>
                                    		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                    		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                    		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                    		<option data-countryCode="CL" value="56">Chile (+56)</option>
                                    		<option data-countryCode="CN" value="86">China (+86)</option>
                                    		<option data-countryCode="CO" value="57">Colombia (+57)</option>
                                    		<option data-countryCode="KM" value="269">Comoros (+269)</option>
                                    		<option data-countryCode="CG" value="242">Congo (+242)</option>
                                    		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                    		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                    		<option data-countryCode="HR" value="385">Croatia (+385)</option>
                                    		<option data-countryCode="CU" value="53">Cuba (+53)</option>
                                    		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                    		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                    		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                    		<option data-countryCode="DK" value="45">Denmark (+45)</option>
                                    		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                    		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                    		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                    		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                    		<option data-countryCode="EG" value="20">Egypt (+20)</option>
                                    		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                    		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                    		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                    		<option data-countryCode="EE" value="372">Estonia (+372)</option>
                                    		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                    		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                    		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                    		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                    		<option data-countryCode="FI" value="358">Finland (+358)</option>
                                    		<option data-countryCode="FR" value="33">France (+33)</option>
                                    		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                    		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                    		<option data-countryCode="GA" value="241">Gabon (+241)</option>
                                    		<option data-countryCode="GM" value="220">Gambia (+220)</option>
                                    		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                    		<option data-countryCode="DE" value="49">Germany (+49)</option>
                                    		<option data-countryCode="GH" value="233">Ghana (+233)</option>
                                    		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                    		<option data-countryCode="GR" value="30">Greece (+30)</option>
                                    		<option data-countryCode="GL" value="299">Greenland (+299)</option>
                                    		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                    		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                    		<option data-countryCode="GU" value="671">Guam (+671)</option>
                                    		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                    		<option data-countryCode="GN" value="224">Guinea (+224)</option>
                                    		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                    		<option data-countryCode="GY" value="592">Guyana (+592)</option>
                                    		<option data-countryCode="HT" value="509">Haiti (+509)</option>
                                    		<option data-countryCode="HN" value="504">Honduras (+504)</option>
                                    		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                    		<option data-countryCode="HU" value="36">Hungary (+36)</option>
                                    		<option data-countryCode="IS" value="354">Iceland (+354)</option>
                                    		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                    		<option data-countryCode="IR" value="98">Iran (+98)</option>
                                    		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                    		<option data-countryCode="IE" value="353">Ireland (+353)</option>
                                    		<option data-countryCode="IL" value="972">Israel (+972)</option>
                                    		<option data-countryCode="IT" value="39">Italy (+39)</option>
                                    		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                    		<option data-countryCode="JP" value="81">Japan (+81)</option>
                                    		<option data-countryCode="JO" value="962">Jordan (+962)</option>
                                    		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                    		<option data-countryCode="KE" value="254">Kenya (+254)</option>
                                    		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                    		<option data-countryCode="KP" value="850">Korea North (+850)</option>
                                    		<option data-countryCode="KR" value="82">Korea South (+82)</option>
                                    		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                    		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                    		<option data-countryCode="LA" value="856">Laos (+856)</option>
                                    		<option data-countryCode="LV" value="371">Latvia (+371)</option>
                                    		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                    		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                    		<option data-countryCode="LR" value="231">Liberia (+231)</option>
                                    		<option data-countryCode="LY" value="218">Libya (+218)</option>
                                    		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                    		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                    		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                    		<option data-countryCode="MO" value="853">Macao (+853)</option>
                                    		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                    		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                    		<option data-countryCode="MW" value="265">Malawi (+265)</option>
                                    		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    		<option data-countryCode="MV" value="960">Maldives (+960)</option>
                                    		<option data-countryCode="ML" value="223">Mali (+223)</option>
                                    		<option data-countryCode="MT" value="356">Malta (+356)</option>
                                        		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                        		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                        		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                        		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                        		<option data-countryCode="MX" value="52">Mexico (+52)</option>
                                        		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                        		<option data-countryCode="MD" value="373">Moldova (+373)</option>
                                        		<option data-countryCode="MC" value="377">Monaco (+377)</option>
                                        		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                        		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                        		<option data-countryCode="MA" value="212">Morocco (+212)</option>
                                        		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                        		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                        		<option data-countryCode="NA" value="264">Namibia (+264)</option>
                                        		<option data-countryCode="NR" value="674">Nauru (+674)</option>
                                        		<option data-countryCode="NP" value="977">Nepal (+977)</option>
                                        		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                        		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                        		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                        		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                        		<option data-countryCode="NE" value="227">Niger (+227)</option>
                                        		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                        		<option data-countryCode="NU" value="683">Niue (+683)</option>
                                        		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                        		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                        		<option data-countryCode="NO" value="47">Norway (+47)</option>
                                        		<option data-countryCode="OM" value="968">Oman (+968)</option>
                                        		<option data-countryCode="PW" value="680">Palau (+680)</option>
                                        		<option data-countryCode="PA" value="507">Panama (+507)</option>
                                        		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                        		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                        		<option data-countryCode="PE" value="51">Peru (+51)</option>
                                        		<option data-countryCode="PH" value="63">Philippines (+63)</option>
                                        		<option data-countryCode="PL" value="48">Poland (+48)</option>
                                        		<option data-countryCode="PT" value="351">Portugal (+351)</option>
                                        		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                        		<option data-countryCode="QA" value="974">Qatar (+974)</option>
                                        		<option data-countryCode="RE" value="262">Reunion (+262)</option>
                                        		<option data-countryCode="RO" value="40">Romania (+40)</option>
                                        		<option data-countryCode="RU" value="7">Russia (+7)</option>
                                        		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                        		<option data-countryCode="SM" value="378">San Marino (+378)</option>
                                        		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                        		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                        		<option data-countryCode="SN" value="221">Senegal (+221)</option>
                                        		<option data-countryCode="CS" value="381">Serbia (+381)</option>
                                        		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                        		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                        		<option data-countryCode="SG" value="65">Singapore (+65)</option>
                                        		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                        		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                        		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                        		<option data-countryCode="SO" value="252">Somalia (+252)</option>
                                        		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                        		<option data-countryCode="ES" value="34">Spain (+34)</option>
                                        		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                        		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                        		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                        		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                        		<option data-countryCode="SD" value="249">Sudan (+249)</option>
                                        		<option data-countryCode="SR" value="597">Suriname (+597)</option>
                                        		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                        		<option data-countryCode="SE" value="46">Sweden (+46)</option>
                                        		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                        		<option data-countryCode="SI" value="963">Syria (+963)</option>
                                        		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                        		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                        		<option data-countryCode="TH" value="66">Thailand (+66)</option>
                                        		<option data-countryCode="TG" value="228">Togo (+228)</option>
                                        		<option data-countryCode="TO" value="676">Tonga (+676)</option>
                                        		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                        		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                        		<option data-countryCode="TR" value="90">Turkey (+90)</option>
                                        		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                        		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                        		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                        		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                        		<option data-countryCode="UG" value="256">Uganda (+256)</option>
                                        		<option data-countryCode="GB" value="44">UK (+44)</option>
                                        		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                        		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                        		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                        		<option data-countryCode="US" value="1">USA (+1)</option>
                                        		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                        		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                        		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                        		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                        		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                        		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                        		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                        		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                        		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                        		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                        		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                        		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                        </select>
            </div>
            <div class="bg-form-el">
              <input placeholder="Mobile Number" type="tel" name="phone" id="phone" maxlength="10" required />
            </div>
            <div class="bg-form-el">
              <textarea class="mb-0" placeholder="Message for us (Optional)..." name="message" id="message" rows="3" ></textarea>
            </div>
          </div>
          <div class="bg-form-el m-footer p-10">
            <button class="fs-14" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </amp-lightbox>

<?php if(in_array(@$category->id, array(23, 30, 78))) {?>
<div id="myModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content" style="background-image:url('<?php echo url('/public/basicfront/images/balancegurus-pop-up-ayurveda-1.jpg');?>');background-repeat: no-repeat;background-size: cover;background-position:center">
                    <div class="modal-header pb-0" style="background:transparent;border:0;">
                        <h5 class="modal-title">Get the best deal on Ayurveda Retreat Packages upto 40% OFF, VIP upgrades & inclusions. Leave in your details and we will get back to you.</h5>
                        <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <form id="frmWelcome" name="frmWelcome" method="post" action-xhr="#">
                            <div class="form-group">
                                <input type="email" class="form-control required email" id="email" name="email" required=""  placeholder="Email Address" />
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Phone" />
                            </div>
                            <button type="submit" class="btn  big-btn circle-btn color-bg flat-btn btnWelcomeForm">Subscribe</button>
                        </form>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                if(localStorage.getItem('popState') != 'shown'){
                    $("#myModal").appendTo("body").modal('show');
                }
                $('#myModal').on('hidden.bs.modal', function (e) {
                  localStorage.setItem('popState','shown');
                });
                $("#frmWelcome").on("submit", function () {
                    if ($("#frmWelcome").valid()) {
                        $(".btnWelcomeForm").attr("disabled", true);
                        $("#frmWelcome .alert-danger, #frmWelcome .alert-success").remove();
                        $.ajax({
                            url: APP_URL + "/store-subscription",
                            method: "POST",
                            data: $("#frmWelcome").serialize()+"&_token={{ csrf_token() }}",
                            success: function (result) {
                                if (result) {
                                    resultdisp = "";
                                    if (result.errors) {
                                        jQuery.each(result.errors, function (key, value) {
                                            resultdisp +=  value;
                                        });
                                    } else {
                                        resultdisp = result;
                                    }
                                    $("#frmWelcome").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                                } else {
                                    $("#frmWelcome")[0].reset();
                                    $("#frmWelcome").after('<div class="alert alert-success" align="left">Your Subscription has been submitted successfully!</div>');
                                }
                                $("#frmWelcome").attr("disabled", false);
                            }
                        });
                    }
                    return false;
                });
            });
        </script>
        <?php }?>
        
@endsection
@section('footer')
<script defer src="{{ url('public/basicfront/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".btnSubscription").on("click", function () {
            if ($("#frmSubscription").valid()) {
                $(".btnSubscription").attr("disabled", true);
                $("#frmSubscription .text-danger, #frmSubscription .text-success").remove();
                $.ajax({
                    url: APP_URL + "/store-blog-subscription",
                    method: "POST",
                    data: $("#frmSubscription").serialize()+"&_token={{ csrf_token() }}",
                    success: function (result) {
                        if (result) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function (key, value) {
                                    resultdisp +=  value;
                                });
                            } else {
                                resultdisp = result;
                            }
                            $("#frmSubscription").after(' <div class="text-danger" align="left">' + resultdisp + '</div>');
                        } else {
                            $("#frmSubscription")[0].reset();
                            $("#frmSubscription").after('<div class="text-success" align="left">Your subscription has been submitted successfully!</div>');
                        }
                        $(".btnSubscription").attr("disabled", false);
                    }
                });
            }
            return false;
        });
        
        
        $(".btnRequestCallForm").on("click", function () {
            if ($("#frmRequestCall").valid()) {
                $(".btnRequestCallForm").attr("disabled", true);
                $("#frmRequestCall .alert-danger, #frmRequestCall .alert-success").remove();
                $.ajax({
                    url: "{{ url('/send-request-call-back-blog') }}",
                    method: "post",
                    data: $("#frmRequestCall").serialize()+"&_token={{ csrf_token() }}",
                    success: function (result) {
                        if (result.success) {
                            $("#frmRequestCall")[0].reset();
                            $("#frmRequestCall").after('<div class="alert alert-success" align="left">'+(result.success)+'</div>');
                        } else if (result.errors) {
                            resultdisp = "";
                            if (result.errors) {
                                jQuery.each(result.errors, function (key, value) {
                                    resultdisp +=  value;
                                });
                            } else {
                                resultdisp = result;
                            }
                            $("#frmRequestCall").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                        } else {
                            $("#frmRequestCall").after(' <div class="alert alert-danger" align="left">Something Went Wrong!</div>');
                        }
                        $(".btnRequestCallForm").attr("disabled", false);
                    }
                });
            }
            return false;
        });
        
        $("#requstcontactPopup").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var inquiry_for = button.data('url') // Extract info from data-* attributes
            //$("#requstcontactPopup #inquiry_for").val(inquiry_for);
        });
        $("#requstcontactPopup .close").on("click", function(){
            //$("#requstcontactPopup #inquiry_for").val("");
            $("#frmRequestCall")[0].reset();
           $("#requstcontactPopup").hide();
        });
    });
</script>
<!--script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery.instagramFeed/3.0.3/jquery.instagramFeed.min.js"></script>
<script type="text/javascript">
    $(window).on('load', function(){
        $.instagramFeed({
            'username': 'balancegurus',
            'container': "#instagram-feed",
            'lazy_load':true,
            'display_profile': false,
            'display_biography': false,
            'display_igtv': false,
            'items_per_row': 3,
            'items': 9
        });
    });
</script-->    
@endsection