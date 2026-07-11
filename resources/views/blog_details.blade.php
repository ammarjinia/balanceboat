@extends('layouts.blog_inner_layout')
@section('title', @$blog->name)
@section('meta_title', @$blog->meta_title)
<?php if (!empty(@$blog->meta_description)) { ?> 
    @section('description', strip_tags(@$blog->meta_description))
<?php } ?>
<?php if (!empty(@$blog->meta_keywords)) { ?> 
    @section('keywords', strip_tags(@$blog->meta_keywords))
<?php } ?>
<?php if (!empty(@$blog->banner_image_url)) { ?> 
    @section('image', Storage::disk('s3')->url(rawurlencode(@$blog->banner_image_url)))
<?php }
$arImgAr = array(
    @$blog->name,
);
?>
@section('head')
<link rel="amphtml" href="{{ url("/blog/".@$blog->slug) }}">
<link rel="stylesheet" href="{{ url('blog_assets/css/vendor/owl.carousel.min.css') }}" />
<style type="text/css">
.btn-contact {
    background: #f36;color: #fff !important;display: inline-block;padding:0px 20px;font-family: poppins,sans-serif;
    font-size: 16px;
    font-weight: 500;
    border: 0;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    text-align: center;
    text-transform: capitalize;
    -webkit-transition: .3s;
    -moz-transition: .3s;
    -o-transition: .3s;
    transition: .3s;
    cursor: pointer;line-height:auto;height:auto;
}
</style>   
<style>
  @font-face {
    font-display: block;
    font-family: Roboto;
    src: url(https://assets.sendinblue.com/font/Roboto/Latin/normal/normal/7529907e9eaf8ebb5220c5f9850e3811.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/normal/normal/25c678feafdc175a70922a116c9be3e7.woff) format("woff")
  }

  @font-face {
    font-display: fallback;
    font-family: Roboto;
    font-weight: 600;
    src: url(https://assets.sendinblue.com/font/Roboto/Latin/medium/normal/6e9caeeafb1f3491be3e32744bc30440.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/medium/normal/71501f0d8d5aa95960f6475d5487d4c2.woff) format("woff")
  }

  @font-face {
    font-display: fallback;
    font-family: Roboto;
    font-weight: 700;
    src: url(https://assets.sendinblue.com/font/Roboto/Latin/bold/normal/3ef7cf158f310cf752d5ad08cd0e7e60.woff2) format("woff2"), url(https://assets.sendinblue.com/font/Roboto/Latin/bold/normal/ece3a1d82f18b60bcce0211725c476aa.woff) format("woff")
  }

  #sib-container input:-ms-input-placeholder {
    text-align: left;
    font-family: "Helvetica", sans-serif;
    color: #c0ccda;
  }

  #sib-container input::placeholder {
    text-align: left;
    font-family: "Helvetica", sans-serif;
    color: #c0ccda;
  }

  #sib-container textarea::placeholder {
    text-align: left;
    font-family: "Helvetica", sans-serif;
    color: #c0ccda;
  }
</style>
<link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">
@endsection

<?php
if($objAdverts) {?>
@section('top-ad')
<section class="banner-ad">
    <span class="close bgLightGallery__Close"></span>
    <div class="container">
        <div class="owl-carousel">
            <?php
            foreach ($objAdverts as $objAdvert) {
            $url_top_img = Storage::disk('s3')->url($objAdvert->banner_image_url);
            $url = \Crypt::encrypt($objAdvert->website);
            ?>
            <div class="item">
                <div class="row align-items-center">
                <div class="col-5">
                    <h2 class="c-white line-2 fs-20">{!! $objAdvert->banner_image_title !!}</h2>
                    <p class="c-white fs-14 line-2">{!! $objAdvert->sub_title !!}</p>
                    @if($objAdvert->id == "2")
                        <a href="#mdlModiYoga" class="call-back btn btn-lg btn-danger ms-0 mt-3 w" data-toggle="modal" data-target='#mdlModiYoga'>Enquire</a>
                    @else
                        <a class="call-back btn btn-lg btn-danger ms-0 mt-3 w" href="{{ url('click-ads?position=top&id='.$blog->id.'&url='.$url) }}" target='_blank'>Enquire</a>
                    @endif
                </div>
                <div class="col-7 d-flex justify-content-center">
                    @if($objAdvert->id == "2")
                        <a href="#mdlModiYoga" data-toggle="modal" data-target='#mdlModiYoga' style="display:block;text-align:center;">
                            <img src="<?php echo $url_top_img;?>" alt="{{ @$objAdvert->banner_image_title }}" class='advertisement img-contain mt-4 mt-lg-0' />
                        </a>
                    @else
                        <a href="{{ url('click-ads?position=top&id='.$blog->id.'&url='.$url) }}" style="display:block;text-align:center;" target='_blank'>
                            <img src="<?php echo $url_top_img;?>" alt="{{ @$objAdvert->banner_image_title }}" class='advertisement img-contain mt-4 mt-lg-0' />
                        </a>
                    @endif    
                </div>
            </div>
            </div>
            <?php }?>
        </div>
    </div>
</section>
@endsection
<?php }?>

@section('content')
<?php
$imgsrc = asset('basicfront/images/bg/1.jpg');
if (!empty($blog->banner_image_url)) {
    $imgsrc = Storage::disk('s3')->url(rawurlencode(@$blog->banner_image_url));
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
      "url": "<?php echo url('blog_assets/images/logo/balanceboat-logo.png');?>"
    }
  }
}
</script>

<div class="post-single-wrapper axil-section-gap bg-color-white">
            <div class="container">
                <?php
                if($blog->topbar_adv_image) {?>
                <a href="javascript:void(0);" style="margin:0 auto 20px;display:block;text-align:center;" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url("/blog/".@$blog->slug) }}">
                    <img src="{{ Storage::disk('s3')->url(@$blog->topbar_adv_image) }}" alt="{{ @$blog->name }}" />
                </a>
                <?php }?>
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Start Banner Area -->
                        <div class="banner banner-single-post post-formate post-layout axil-section-gapBottom">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Start Single Slide  -->
                                        <div class="content-block">
                                            <!-- Start Post Content  -->
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="javascript:void(0);">
                                                            <span class="hover-flip-item">
                                                                <span data-text="{{ @$bannerYoga->category->name }}">{{ @$blog->category->name }}</span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h1 class="title">{{ @$blog->name }}</h1>
                                                <!-- Post Meta  -->
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
                                            <!-- End Post Content  -->
                                        </div>
                                        <!-- End Single Slide  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Banner Area -->
                        <div class="axil-post-details">
                            <figure class="wp-block-image">
                            <?php
                                $imgsrc = asset('basicfront/images/bg/1.jpg');
                                if (!empty($blog->banner_image_url)) {
                                    $imgsrc = Storage::disk('s3')->url(rawurlencode(@$blog->banner_image_url));
                                }
                                ?>
                                <img src="<?php echo $imgsrc;?>" alt="" />
                            </figure>
                            {!! html_entity_decode(@$blog->description) !!}
                            
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
                                                <img src="{{ Storage::disk('s3')->url($objGallery->image_url) }}" alt="{{ $arImgAr[array_rand($arImgAr,1)] }}">
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
                    <div class="col-lg-4">
                        <!-- Start Sidebar Area  -->
                        <div class="sidebar-inner">
                            <!-- Start Single Widget  -->
                            @if(@$blog->tags)
                            <div class="axil-single-widget widget widget_categories mb--30">
                                <ul>
                                    @foreach(explode(",", @$blog->tags) as $tag)
                                    <li class="cat-item">
                                        <a href="javascript:void(0);" class="inner">
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
                            
                            @if(@$blog_listings && (!@$blog_listings->isEmpty()))
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
                                    $imgsrc = asset('basicfront/images/all/1.jpg');
                                    if (!empty($listing->banner_image_url)) {
                                        $imgsrc = Storage::disk('s3')->url(rawurlencode(@$listing->banner_image_url));
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
                            @if(@$experiences && !empty(@$experiences))
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_postlist mb--30">
                                <h5 class="widget-title">Popular on Experiences</h5>
                                <!-- Start Post List  -->
                                <div class="post-medium-block">
                                    @foreach(@$experiences as $experience)
                                    <!-- Start Single Post  -->
                                    <div class="content-block post-medium mb--20">
                                        <div class="post-thumbnail">
                                            <a href="{{ url("/experience/".$experience->slug) }}" class="widget-posts-img">
                                                <img src="{{ ($experience->thumbnail_image_url) ? Storage::disk('s3')->url(rawurlencode($experience->thumbnail_image_url)) : Storage::disk('s3')->url(rawurlencode($experience->banner_image_url)) }}" alt="{{ $experience->banner_image_title }}" class="img-responsive lazy" /> 
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <h6 class="title">
                                                <a href="{{ url("/experience/".$experience->slug) }}" title="">{{ $experience->name }}</a>
                                            </h6>
                                            <div class="post-meta">
                                                <ul class="post-meta-list">
                                                    <?php
                                                    $pay = @$experience->room_price*1;
                                                    if (!empty(@$experience->totaldiscount)) {?>
                                                    <li>
                                                    <del class="text-default">
                                                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), \App\Http\Helpers\CommonHelper::get_site_currency()) }}
                                                    </del>
                                                    </li>
                                                    <?php }?>
                                                    <li>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->final_room_price, \App\Http\Helpers\CommonHelper::get_site_currency()) }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Single Post  -->
                                    @endforeach
                                </div>
                                <!-- End Post List  -->
                                <div class="text-center">
                                    <a href="{{ url("/experiences?stags=".@$blog->tags) }}" class="cerchio axil-button button-rounded" style="transform: matrix(1, 0, 0, 1, 0, 0);"><span>View All</span></a>
                                </div>
                            </div>
                            <!-- End Single Widget  -->
                            @endif
                            @if(@$blogs)
                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_postlist mb--30">
                                <h5 class="widget-title">Popular on Posts</h5>
                                <!-- Start Post List  -->
                                <div class="post-medium-block">
                                    @foreach(@$blogs as $pblog)
                                    <!-- Start Single Post  -->
                                    <div class="content-block post-medium mb--20">
                                        <div class="post-thumbnail">
                                            <a href="{{ url("/blog/".@$pblog->slug) }}" class="widget-posts-img">
                                                <img src="{{ Storage::disk('s3')->url(@$pblog->banner_image_url) }}" alt="{{ $arImgAr[array_rand($arImgAr,1)] }}">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <h6 class="title">
                                                <a href="{{ url("/blog/".@$pblog->slug) }}" title="">{{ @$pblog->name }}</a>
                                            </h6>
                                            <div class="post-meta">
                                                <ul class="post-meta-list">
                                                    <li>{{ \Carbon\carbon::parse(@$pblog->created_at)->format("F d,Y") }}</li>
                                                    <li>{{ (@$pblog->total_views) ? "(".@$pblog->total_views." views)": "" }}</li>
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

                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_newsletter mb--30">
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
                            <!-- End Single Widget  -->

                            <!-- Start Single Widget  -->
                            <div class="axil-single-widget widget widget_social mb--30">
                                <h5 class="widget-title">Stay In Touch</h5>
                                <!-- Start Post List  -->
                                <ul class="social-icon md-size justify-content-center">
                                    <li><a href="https://www.facebook.com/balanceboat"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://www.pinterest.com/balanceboat"><i class="fab fa-pinterest"></i></a></li>
                                    <li><a href="https://www.instagram.com/thebalanceboat"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                                <!-- End Post List  -->
                            </div>
                            <!-- End Single Widget  -->

                            <!-- Start Single Widget  -->
                            <?php
                            /*<div class="axil-single-widget widget widget_instagram mb--30">
                                <h5 class="widget-title">Instagram</h5>
                                <!-- Start Post List  -->
                                <div id="instagram-feed" class="instagram_feed">
                                    
                                </div>
                                <!-- End Post List  -->
                            </div>
                            */?>
                            <!-- End Single Widget  -->
                            <?php
                            if(@$blog->sidebar_adv_image) {
                            if(@$blog->popup_url){
                                $url = $blog->popup_url; 
                            } else {
                                $url = "#";
                            }
                            ?>
                            <a href="<?php echo $url; ?>" data-url="{{ url("/blog/".@$blog->slug) }}">
                                <img src="{{ Storage::disk('s3')->url(@$blog->sidebar_adv_image) }}" alt="{{ @$blog->name }}" />
                            </a>
                            <?php }?>
                            
                
                            <?php
                            if($objSideAdverts) {?>
                            <div class="owl-carousel">
                                <?php
                                foreach ($objSideAdverts as $objAdvert) {
                                $url_top_img = Storage::disk('s3')->url($objAdvert->banner_image_url);
                                $url = \Crypt::encrypt($objAdvert->website);
                                ?>
                                <div class="item">
                                    <?php /*<a href="{{ url('click-ads?position=side&id='.$blog->id.'&url='.$url) }}" style="display:block;text-align:center;" target='blank'>
                                        <img src="<?php echo $url_top_img;?>" alt="{{ @$objAdvert->banner_image_title }}" class='advertisement img-contain mt-4 mt-lg-0' />
                                    </a>*/?>
                                    <a href="javascript:void(0);" data-target="#requstcontactPopup" data-toggle="modal" data-url="{{ url("/blog/".@$blog->slug) }}">
                                        <img src="<?php echo $url_top_img;?>" alt="{{ @$objAdvert->banner_image_title }}" class='advertisement img-contain mt-4 mt-lg-0' />
                                    </a>
                                </div>
                                <?php }?>
                            </div>
                            <?php }?>
                        </div>
                        <!-- End Sidebar Area  -->
                    </div>
                </div>
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
                        $imgsrc = asset('blog_assets/images/post-images/post-sm-01.jpg');
                        if (!empty(@$objBlog->banner_image_url)) {
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objBlog->banner_image_url));
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
                        $imgsrc = asset('basicfront/images/team/1.jpg');
                        if (!empty($teacher->profile_image_url)) {
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$teacher->profile_image_url));
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
                        $imgsrc = asset('basicfront/images/all/1.jpg');
                        if (!empty($objCourse->image_url)) {
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objCourse->image_url));
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
                        $imgsrc = asset('basicfront/images/all/1.jpg');
                        if (!empty($objVideo->image_url)) {
                            $imgsrc = Storage::disk('s3')->url(rawurlencode(@$objVideo->image_url));
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
<div id="requstcontactPopup" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header pb-0">
                    @if(@$blog->popup_heading)
                        <h4>{{ @$blog->popup_heading }}</h4>
                    @else
                        <h4>Book a Retreat Get upto 40% Off</h4>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" style="text-align:right;font-size:30px;width:auto;"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="frmRequestCall" name="frmRequestCall" method="post">
                                <input type="hidden" name="inquiry_for" id="inquiry_for" value="{{ url("/blog/".@$blog->slug) }}" />
                                <input type="hidden" name="inquiry_subject" id="inquiry_subject" value="{{ @$blog->name }}" />
                                @if(@$blog->popup_description)
                                <p class="mb-3">{{ @$blog->popup_description }}</p>
                                @else
                                <p class="mb-3">Let us help you get the best deal, inclusions, upgrads, transport and much more!!</p>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control required" id="name" name="name" required=""  placeholder="Your Name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control required email" id="email" name="email" required=""  placeholder="Email*" />  
                                        </div>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
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
                                    </div>  
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="number" class="form-control required" id="phone" name="phone" required="" maxlength="10" placeholder="Mobile Number*" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="message" name="message" placeholder="Message for us (Optional)..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btnRequestCallForm p-3" style="font-size:14px;">Send Me The Best Deals</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if(in_array(@$category->id, array(23, 30, 78))) {?>
<div id="myModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content" style="background-image:url('<?php echo url('basicfront/images/balancegurus-pop-up-ayurveda-1.jpg');?>');background-repeat: no-repeat;background-size: cover;background-position:center">
                    <div class="modal-header pb-0" style="background:transparent;border:0;">
                        <h5 class="modal-title">Get the best deal on Ayurveda Retreat Packages upto 40% OFF, VIP upgrades & inclusions. Leave in your details and we will get back to you.</h5>
                        <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <form id="frmWelcome" name="frmWelcome" method="post">
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
                                    $("#frmWelcome").after('<div class="alert alert-success" align="left">Thank you for Subscription!</div>');
                                }
                                $(".btnWelcomeForm").attr("disabled", false);
                            }
                        });
                    }
                    return false;
                });
            });
        </script>
        <?php }?>
        <div class="modal fade" id="mdlModiYoga" tabindex="-1" role="dialog" aria-labelledby="mdlModiYoga" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="sib-form p-0" style="text-align: center;">
              <div id="sib-form-container" class="sib-form-container">
                <div id="error-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;max-width:540px;">
                  <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                      <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z" />
                    </svg>
                    <span class="sib-form-message-panel__inner-text">
                                      Your subscription could not be saved. Please try again.
                                  </span>
                  </div>
                </div>
                <div></div>
                <div id="success-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#085229; background-color:#e7faf0; border-radius:3px; border-color:#13ce66;max-width:540px;">
                  <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                    <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                      <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z" />
                    </svg>
                    <span class="sib-form-message-panel__inner-text">
                                      Your subscription to the offer has been successful.
                                  </span>
                  </div>
                </div>
                <div></div>
                <div id="sib-container" class="sib-container--large sib-container--vertical" style="text-align:center; background-color:rgba(255,255,255,1); max-width:540px; border-radius:3px; border-width:1px; border-color:#a92e7c; border-style:solid; direction:ltr">
                  <form id="sib-form" method="POST" action="https://95b75200.sibforms.com/serve/MUIEAJEX0VuK7DLOwjFfMlM0EzF8eFQVlovypbAa7WXdIGqfzyVFy-gXmhS_9j3p4uYeKCVMXQUvkbR3s9PpJPn8h78ALP4q1AOKbwmvmtSTHSQ3DNGTxma5vPjvVEYyWrN1Hb5hR0n92DWJmeUZvjR-52lIpwowL7uyQk-VJxasVHsIPa4OVe-AVMiYkkwb84Xe1NHiyyZ6YJpl" data-type="subscription">
                    <div style="padding: 8px 0;">
                      <div class="sib-form-block" style="font-size:28px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3C4858; background-color:transparent; text-align:left">
                        <p>Offers at Modi Yoga Retreat</p>
                      </div>
                    </div>
                    <div style="padding: 8px 0;">
                      <div class="sib-input sib-form-block">
                        <div class="form__entry entry_block">
                          <div class="form__label-row ">
                            <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="FIRSTNAME" data-required="*">Enter your Name</label>
            
                            <div class="entry__field">
                              <input class="input " maxlength="200" type="text" id="FIRSTNAME" name="FIRSTNAME" autocomplete="off" placeholder="FIRSTNAME" data-required="true" required />
                            </div>
                          </div>
            
                          <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                          </label>
                          <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                            Customize this optional help text before publishing your form.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div style="padding: 8px 0;">
                      <div class="sib-input sib-form-block">
                        <div class="form__entry entry_block">
                          <div class="form__label-row ">
                            <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="EMAIL" data-required="*">Enter your Email</label>
            
                            <div class="entry__field">
                              <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="EMAIL" data-required="true" required />
                            </div>
                          </div>
            
                          <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                          </label>
                          <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                            Customize this optional help text before publishing your form.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div style="padding: 8px 0;">
                      <div class="sib-sms-field sib-form-block">
                        <div class="form__entry entry_block">
                          <div class="form__label-row ">
                            <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="SMS" data-required="*">Enter your Phone number for quick communication</label>
            
                            <div class="sib-sms-input-wrapper" style="direction:ltr">
                              <div class="sib-sms-input" data-placeholder="SMS" data-required="1" data-country-code="IN" data-whatsapp-country-code="IN" data-value="" data-whatsappvalue="" data-attributename="SMS">
                                <div class="entry__field">
                                  <select class="input" name="SMS__COUNTRY_CODE" data-required="true">
                                    <option value="+93">
                                      +93 AF
                                    </option>
                                    <option value="+358">
                                      +358 AX
                                    </option>
                                    <option value="+355">
                                      +355 AL
                                    </option>
                                    <option value="+213">
                                      +213 DZ
                                    </option>
                                    <option value="+1684">
                                      +1684 AS
                                    </option>
                                    <option value="+376">
                                      +376 AD
                                    </option>
                                    <option value="+244">
                                      +244 AO
                                    </option>
                                    <option value="+1264">
                                      +1264 AI
                                    </option>
                                    <option value="+672">
                                      +672 AQ
                                    </option>
                                    <option value="+1268">
                                      +1268 AG
                                    </option>
                                    <option value="+54">
                                      +54 AR
                                    </option>
                                    <option value="+374">
                                      +374 AM
                                    </option>
                                    <option value="+297">
                                      +297 AW
                                    </option>
                                    <option value="+61">
                                      +61 AU
                                    </option>
                                    <option value="+43">
                                      +43 AT
                                    </option>
                                    <option value="+994">
                                      +994 AZ
                                    </option>
                                    <option value="+1242">
                                      +1242 BS
                                    </option>
                                    <option value="+973">
                                      +973 BH
                                    </option>
                                    <option value="+880">
                                      +880 BD
                                    </option>
                                    <option value="+1246">
                                      +1246 BB
                                    </option>
                                    <option value="+375">
                                      +375 BY
                                    </option>
                                    <option value="+32">
                                      +32 BE
                                    </option>
                                    <option value="+501">
                                      +501 BZ
                                    </option>
                                    <option value="+229">
                                      +229 BJ
                                    </option>
                                    <option value="+1441">
                                      +1441 BM
                                    </option>
                                    <option value="+975">
                                      +975 BT
                                    </option>
                                    <option value="+591">
                                      +591 BO
                                    </option>
                                    <option value="+599">
                                      +599 BQ
                                    </option>
                                    <option value="+387">
                                      +387 BA
                                    </option>
                                    <option value="+267">
                                      +267 BW
                                    </option>
                                    <option value="+47">
                                      +47 BV
                                    </option>
                                    <option value="+55">
                                      +55 BR
                                    </option>
                                    <option value="+246">
                                      +246 IO
                                    </option>
                                    <option value="+673">
                                      +673 BN
                                    </option>
                                    <option value="+359">
                                      +359 BG
                                    </option>
                                    <option value="+226">
                                      +226 BF
                                    </option>
                                    <option value="+257">
                                      +257 BI
                                    </option>
                                    <option value="+855">
                                      +855 KH
                                    </option>
                                    <option value="+237">
                                      +237 CM
                                    </option>
                                    <option value="+1">
                                      +1 CA
                                    </option>
                                    <option value="+238">
                                      +238 CV
                                    </option>
                                    <option value="+1345">
                                      +1345 KY
                                    </option>
                                    <option value="+236">
                                      +236 CF
                                    </option>
                                    <option value="+235">
                                      +235 TD
                                    </option>
                                    <option value="+56">
                                      +56 CL
                                    </option>
                                    <option value="+86">
                                      +86 CN
                                    </option>
                                    <option value="+61">
                                      +61 CX
                                    </option>
                                    <option value="+61">
                                      +61 CC
                                    </option>
                                    <option value="+57">
                                      +57 CO
                                    </option>
                                    <option value="+269">
                                      +269 KM
                                    </option>
                                    <option value="+242">
                                      +242 CG
                                    </option>
                                    <option value="+243">
                                      +243 CD
                                    </option>
                                    <option value="+682">
                                      +682 CK
                                    </option>
                                    <option value="+506">
                                      +506 CR
                                    </option>
                                    <option value="+225">
                                      +225 CI
                                    </option>
                                    <option value="+385">
                                      +385 HR
                                    </option>
                                    <option value="+53">
                                      +53 CU
                                    </option>
                                    <option value="+599">
                                      +599 CW
                                    </option>
                                    <option value="+357">
                                      +357 CY
                                    </option>
                                    <option value="+420">
                                      +420 CZ
                                    </option>
                                    <option value="+45">
                                      +45 DK
                                    </option>
                                    <option value="+253">
                                      +253 DJ
                                    </option>
                                    <option value="+1767">
                                      +1767 DM
                                    </option>
                                    <option value="+1809">
                                      +1809 DO
                                    </option>
                                    <option value="+1829">
                                      +1829 DO
                                    </option>
                                    <option value="+1849">
                                      +1849 DO
                                    </option>
                                    <option value="+593">
                                      +593 EC
                                    </option>
                                    <option value="+20">
                                      +20 EG
                                    </option>
                                    <option value="+503">
                                      +503 SV
                                    </option>
                                    <option value="+240">
                                      +240 GQ
                                    </option>
                                    <option value="+291">
                                      +291 ER
                                    </option>
                                    <option value="+372">
                                      +372 EE
                                    </option>
                                    <option value="+251">
                                      +251 ET
                                    </option>
                                    <option value="+500">
                                      +500 FK
                                    </option>
                                    <option value="+298">
                                      +298 FO
                                    </option>
                                    <option value="+679">
                                      +679 FJ
                                    </option>
                                    <option value="+358">
                                      +358 FI
                                    </option>
                                    <option value="+33">
                                      +33 FR
                                    </option>
                                    <option value="+594">
                                      +594 GF
                                    </option>
                                    <option value="+689">
                                      +689 PF
                                    </option>
                                    <option value="+262">
                                      +262 TF
                                    </option>
                                    <option value="+241">
                                      +241 GA
                                    </option>
                                    <option value="+220">
                                      +220 GM
                                    </option>
                                    <option value="+995">
                                      +995 GE
                                    </option>
                                    <option value="+49">
                                      +49 DE
                                    </option>
                                    <option value="+233">
                                      +233 GH
                                    </option>
                                    <option value="+350">
                                      +350 GI
                                    </option>
                                    <option value="+30">
                                      +30 GR
                                    </option>
                                    <option value="+299">
                                      +299 GL
                                    </option>
                                    <option value="+1473">
                                      +1473 GD
                                    </option>
                                    <option value="+590">
                                      +590 GP
                                    </option>
                                    <option value="+1671">
                                      +1671 GU
                                    </option>
                                    <option value="+502">
                                      +502 GT
                                    </option>
                                    <option value="+44">
                                      +44 GG
                                    </option>
                                    <option value="+224">
                                      +224 GN
                                    </option>
                                    <option value="+245">
                                      +245 GW
                                    </option>
                                    <option value="+592">
                                      +592 GY
                                    </option>
                                    <option value="+509">
                                      +509 HT
                                    </option>
                                    <option value="+672">
                                      +672 HM
                                    </option>
                                    <option value="+379">
                                      +379 VA
                                    </option>
                                    <option value="+504">
                                      +504 HN
                                    </option>
                                    <option value="+852">
                                      +852 HK
                                    </option>
                                    <option value="+36">
                                      +36 HU
                                    </option>
                                    <option value="+354">
                                      +354 IS
                                    </option>
                                    <option value="+91">
                                      +91 IN
                                    </option>
                                    <option value="+62">
                                      +62 ID
                                    </option>
                                    <option value="+98">
                                      +98 IR
                                    </option>
                                    <option value="+964">
                                      +964 IQ
                                    </option>
                                    <option value="+353">
                                      +353 IE
                                    </option>
                                    <option value="+44">
                                      +44 IM
                                    </option>
                                    <option value="+972">
                                      +972 IL
                                    </option>
                                    <option value="+39">
                                      +39 IT
                                    </option>
                                    <option value="+1876">
                                      +1876 JM
                                    </option>
                                    <option value="+81">
                                      +81 JP
                                    </option>
                                    <option value="+44">
                                      +44 JE
                                    </option>
                                    <option value="+962">
                                      +962 JO
                                    </option>
                                    <option value="+7">
                                      +7 KZ
                                    </option>
                                    <option value="+254">
                                      +254 KE
                                    </option>
                                    <option value="+686">
                                      +686 KI
                                    </option>
                                    <option value="+850">
                                      +850 KP
                                    </option>
                                    <option value="+82">
                                      +82 KR
                                    </option>
                                    <option value="+965">
                                      +965 KW
                                    </option>
                                    <option value="+996">
                                      +996 KG
                                    </option>
                                    <option value="+856">
                                      +856 LA
                                    </option>
                                    <option value="+371">
                                      +371 LV
                                    </option>
                                    <option value="+961">
                                      +961 LB
                                    </option>
                                    <option value="+266">
                                      +266 LS
                                    </option>
                                    <option value="+231">
                                      +231 LR
                                    </option>
                                    <option value="+218">
                                      +218 LY
                                    </option>
                                    <option value="+423">
                                      +423 LI
                                    </option>
                                    <option value="+370">
                                      +370 LT
                                    </option>
                                    <option value="+352">
                                      +352 LU
                                    </option>
                                    <option value="+853">
                                      +853 MO
                                    </option>
                                    <option value="+389">
                                      +389 MK
                                    </option>
                                    <option value="+261">
                                      +261 MG
                                    </option>
                                    <option value="+265">
                                      +265 MW
                                    </option>
                                    <option value="+60">
                                      +60 MY
                                    </option>
                                    <option value="+960">
                                      +960 MV
                                    </option>
                                    <option value="+223">
                                      +223 ML
                                    </option>
                                    <option value="+356">
                                      +356 MT
                                    </option>
                                    <option value="+692">
                                      +692 MH
                                    </option>
                                    <option value="+596">
                                      +596 MQ
                                    </option>
                                    <option value="+222">
                                      +222 MR
                                    </option>
                                    <option value="+230">
                                      +230 MU
                                    </option>
                                    <option value="+262">
                                      +262 YT
                                    </option>
                                    <option value="+52">
                                      +52 MX
                                    </option>
                                    <option value="+691">
                                      +691 FM
                                    </option>
                                    <option value="+373">
                                      +373 MD
                                    </option>
                                    <option value="+377">
                                      +377 MC
                                    </option>
                                    <option value="+976">
                                      +976 MN
                                    </option>
                                    <option value="+382">
                                      +382 ME
                                    </option>
                                    <option value="+1664">
                                      +1664 MS
                                    </option>
                                    <option value="+212">
                                      +212 MA
                                    </option>
                                    <option value="+258">
                                      +258 MZ
                                    </option>
                                    <option value="+95">
                                      +95 MM
                                    </option>
                                    <option value="+264">
                                      +264 NA
                                    </option>
                                    <option value="+674">
                                      +674 NR
                                    </option>
                                    <option value="+977">
                                      +977 NP
                                    </option>
                                    <option value="+31">
                                      +31 NL
                                    </option>
                                    <option value="+687">
                                      +687 NC
                                    </option>
                                    <option value="+64">
                                      +64 NZ
                                    </option>
                                    <option value="+505">
                                      +505 NI
                                    </option>
                                    <option value="+227">
                                      +227 NE
                                    </option>
                                    <option value="+234">
                                      +234 NG
                                    </option>
                                    <option value="+683">
                                      +683 NU
                                    </option>
                                    <option value="+672">
                                      +672 NF
                                    </option>
                                    <option value="+1670">
                                      +1670 MP
                                    </option>
                                    <option value="+47">
                                      +47 NO
                                    </option>
                                    <option value="+968">
                                      +968 OM
                                    </option>
                                    <option value="+92">
                                      +92 PK
                                    </option>
                                    <option value="+680">
                                      +680 PW
                                    </option>
                                    <option value="+970">
                                      +970 PS
                                    </option>
                                    <option value="+507">
                                      +507 PA
                                    </option>
                                    <option value="+675">
                                      +675 PG
                                    </option>
                                    <option value="+595">
                                      +595 PY
                                    </option>
                                    <option value="+51">
                                      +51 PE
                                    </option>
                                    <option value="+63">
                                      +63 PH
                                    </option>
                                    <option value="+64">
                                      +64 PN
                                    </option>
                                    <option value="+48">
                                      +48 PL
                                    </option>
                                    <option value="+351">
                                      +351 PT
                                    </option>
                                    <option value="+1787">
                                      +1787 PR
                                    </option>
                                    <option value="+974">
                                      +974 QA
                                    </option>
                                    <option value="+383">
                                      +383 XK
                                    </option>
                                    <option value="+262">
                                      +262 RE
                                    </option>
                                    <option value="+40">
                                      +40 RO
                                    </option>
                                    <option value="+7">
                                      +7 RU
                                    </option>
                                    <option value="+250">
                                      +250 RW
                                    </option>
                                    <option value="+590">
                                      +590 BL
                                    </option>
                                    <option value="+290">
                                      +290 SH
                                    </option>
                                    <option value="+1869">
                                      +1869 KN
                                    </option>
                                    <option value="+1758">
                                      +1758 LC
                                    </option>
                                    <option value="+590">
                                      +590 MF
                                    </option>
                                    <option value="+508">
                                      +508 PM
                                    </option>
                                    <option value="+1784">
                                      +1784 VC
                                    </option>
                                    <option value="+685">
                                      +685 WS
                                    </option>
                                    <option value="+378">
                                      +378 SM
                                    </option>
                                    <option value="+239">
                                      +239 ST
                                    </option>
                                    <option value="+966">
                                      +966 SA
                                    </option>
                                    <option value="+221">
                                      +221 SN
                                    </option>
                                    <option value="+381">
                                      +381 RS
                                    </option>
                                    <option value="+248">
                                      +248 SC
                                    </option>
                                    <option value="+232">
                                      +232 SL
                                    </option>
                                    <option value="+65">
                                      +65 SG
                                    </option>
                                    <option value="+1721">
                                      +1721 SX
                                    </option>
                                    <option value="+421">
                                      +421 SK
                                    </option>
                                    <option value="+386">
                                      +386 SI
                                    </option>
                                    <option value="+677">
                                      +677 SB
                                    </option>
                                    <option value="+252">
                                      +252 SO
                                    </option>
                                    <option value="+27">
                                      +27 ZA
                                    </option>
                                    <option value="+500">
                                      +500 GS
                                    </option>
                                    <option value="+211">
                                      +211 SS
                                    </option>
                                    <option value="+34">
                                      +34 ES
                                    </option>
                                    <option value="+94">
                                      +94 LK
                                    </option>
                                    <option value="+249">
                                      +249 SD
                                    </option>
                                    <option value="+597">
                                      +597 SR
                                    </option>
                                    <option value="+47">
                                      +47 SJ
                                    </option>
                                    <option value="+268">
                                      +268 SZ
                                    </option>
                                    <option value="+46">
                                      +46 SE
                                    </option>
                                    <option value="+41">
                                      +41 CH
                                    </option>
                                    <option value="+963">
                                      +963 SY
                                    </option>
                                    <option value="+886">
                                      +886 TW
                                    </option>
                                    <option value="+992">
                                      +992 TJ
                                    </option>
                                    <option value="+255">
                                      +255 TZ
                                    </option>
                                    <option value="+66">
                                      +66 TH
                                    </option>
                                    <option value="+670">
                                      +670 TL
                                    </option>
                                    <option value="+228">
                                      +228 TG
                                    </option>
                                    <option value="+690">
                                      +690 TK
                                    </option>
                                    <option value="+676">
                                      +676 TO
                                    </option>
                                    <option value="+1868">
                                      +1868 TT
                                    </option>
                                    <option value="+216">
                                      +216 TN
                                    </option>
                                    <option value="+90">
                                      +90 TR
                                    </option>
                                    <option value="+993">
                                      +993 TM
                                    </option>
                                    <option value="+1649">
                                      +1649 TC
                                    </option>
                                    <option value="+688">
                                      +688 TV
                                    </option>
                                    <option value="+256">
                                      +256 UG
                                    </option>
                                    <option value="+380">
                                      +380 UA
                                    </option>
                                    <option value="+971">
                                      +971 AE
                                    </option>
                                    <option value="+44">
                                      +44 GB
                                    </option>
                                    <option value="+1">
                                      +1 US
                                    </option>
                                    <option value="+246">
                                      +246 UM
                                    </option>
                                    <option value="+598">
                                      +598 UY
                                    </option>
                                    <option value="+998">
                                      +998 UZ
                                    </option>
                                    <option value="+678">
                                      +678 VU
                                    </option>
                                    <option value="+58">
                                      +58 VE
                                    </option>
                                    <option value="+84">
                                      +84 VN
                                    </option>
                                    <option value="+1284">
                                      +1284 VG
                                    </option>
                                    <option value="+1340">
                                      +1340 VI
                                    </option>
                                    <option value="+681">
                                      +681 WF
                                    </option>
                                    <option value="+212">
                                      +212 EH
                                    </option>
                                    <option value="+967">
                                      +967 YE
                                    </option>
                                    <option value="+260">
                                      +260 ZM
                                    </option>
                                    <option value="+263">
                                      +263 ZW
                                    </option>
                                  </select>
                                </div>
                                <div class="entry__field" style="width: 100%">
                                  <input type="tel" type="text" class="input" id="SMS" name="SMS" autocomplete="off" placeholder="SMS" data-required="true" required />
                                </div>
                              </div>
                              <div class="sib-sms-tooltip">
                                <div class="sib-sms-tooltip__box">
                                  The SMS field must contain between 6 and 19 digits and include the country code without using +/0 (e.g. 1xxxxxxxxxx for the United States)
                                </div>
                                <span class="sib-sms-tooltip__icon">?</span>
                              </div>
                            </div>
                          </div>
            
                          <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                          </label>
                          <label class="entry__error entry__error--secondary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                          </label>
                          <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                            Customize this optional help text before publishing your form.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div style="padding: 8px 0;">
                      <div class="sib-optin sib-form-block">
                        <div class="form__entry entry_mcq">
                          <div class="form__label-row ">
                            <div class="entry__choice" style="">
                              <label>
                                <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN" />
                                <span class="checkbox checkbox_tick_positive"
                        style="margin-left:"
                        ></span><span style="font-size:14px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#3C4858; background-color:transparent;"><p>I agree to receive your offers and accept the data privacy statement.</p></span> </label>
                            </div>
                          </div>
                          <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                          </label>
                          <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                            You may unsubscribe at any time using the link in our newsletter.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div style="padding: 8px 0;">
                      <div class="sib-form-block" style="text-align: left">
                        <button class="sib-form-block__button sib-form-block__button-with-loader" style="font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#FFFFFF; background-color:#f72658; border-radius:3px; border-width:0px;" form="sib-form" type="submit">
                          <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                            <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z" />
                          </svg>
                          Submit
                        </button>
                      </div>
                    </div>
            
                    <input type="text" name="email_address_check" value="" class="input--hidden">
                    <input type="hidden" name="locale" value="en">
                  </form>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mdlSideAdv" tabindex="-1" role="dialog" aria-labelledby="mdlSideAdv" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="sib-form p-0" style="text-align: center;background-color: #e7e7e7;">
                  <div id="sib-form-container" class="sib-form-container">
                    <div id="error-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;max-width:540px;">
                      <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                        <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                          <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z" />
                        </svg>
                        <span class="sib-form-message-panel__inner-text">
                                          Your subscription could not be saved. Please try again.
                                      </span>
                      </div>
                    </div>
                    <div></div>
                    <div id="success-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#085229; background-color:#e7faf0; border-radius:3px; border-color:#13ce66;max-width:540px;">
                      <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                        <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                          <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z" />
                        </svg>
                        <span class="sib-form-message-panel__inner-text">
                                          Your subscription to the offer has been successful.
                                      </span>
                      </div>
                    </div>
                    <div></div>
                    <div id="sib-container" class="sib-container--large sib-container--vertical" style="text-align:center; background-color:rgba(255,255,255,1); max-width:540px; border-radius:3px; border-width:1px; border-color:#a92e7c; border-style:solid; direction:ltr">
                      <form id="sib-form" method="POST" action="https://95b75200.sibforms.com/serve/MUIEAEK2zrcMSLpVTVWD356XjRKO0mytC3Sm6lOdeEzrgM73qCIyuC_UJ8UiuWpoXbu7iKIgxcoa1lIUBLYfrXe6uQOfDSfNrm5Fee130IKcsuOgFCJNOUmxvoH3DRsyXIBnVCKzqopvom5S5r88RwNu5TDVlacCQNwOKupxD8mFMlTb8lnpS9YUV3aw0cFh2IiGbIJkBgie8TH7" data-type="subscription">
                        <div style="padding: 8px 0;">
                          <div class="sib-form-block" style="font-size:28px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3C4858; background-color:transparent; text-align:left">
                            <p>Upto 40% off on Ayurveda &amp; Yoga Retreats&nbsp;</p>
                          </div>
                        </div>
                        <div style="padding: 8px 0;">
                          <div class="sib-input sib-form-block">
                            <div class="form__entry entry_block">
                              <div class="form__label-row ">
                                <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="FIRSTNAME" data-required="*">Enter your Name</label>
                
                                <div class="entry__field">
                                  <input class="input " maxlength="200" type="text" id="FIRSTNAME" name="FIRSTNAME" autocomplete="off" placeholder="FIRSTNAME" data-required="true" required />
                                </div>
                              </div>
                
                              <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                              </label>
                              <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                                Customize this optional help text before publishing your form.
                              </label>
                            </div>
                          </div>
                        </div>
                        <div style="padding: 8px 0;">
                          <div class="sib-input sib-form-block">
                            <div class="form__entry entry_block">
                              <div class="form__label-row ">
                                <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="EMAIL" data-required="*">Enter your Email</label>
                
                                <div class="entry__field">
                                  <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="EMAIL" data-required="true" required />
                                </div>
                              </div>
                
                              <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                              </label>
                              <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                                Customize this optional help text before publishing your form.
                              </label>
                            </div>
                          </div>
                        </div>
                        <div style="padding: 8px 0;">
                          <div class="sib-sms-field sib-form-block">
                            <div class="form__entry entry_block">
                              <div class="form__label-row ">
                                <label class="entry__label" style="font-weight: 700; text-align:left; font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#3c4858;" for="SMS" data-required="*">Enter your Phone number for quick communication</label>
                
                                <div class="sib-sms-input-wrapper" style="direction:ltr">
                                  <div class="sib-sms-input" data-placeholder="SMS" data-required="1" data-country-code="IN" data-whatsapp-country-code="IN" data-value="" data-whatsappvalue="" data-attributename="SMS">
                                    <div class="entry__field">
                                      <select class="input" name="SMS__COUNTRY_CODE" data-required="true">
                                        <option value="+93">
                                          +93 AF
                                        </option>
                                        <option value="+358">
                                          +358 AX
                                        </option>
                                        <option value="+355">
                                          +355 AL
                                        </option>
                                        <option value="+213">
                                          +213 DZ
                                        </option>
                                        <option value="+1684">
                                          +1684 AS
                                        </option>
                                        <option value="+376">
                                          +376 AD
                                        </option>
                                        <option value="+244">
                                          +244 AO
                                        </option>
                                        <option value="+1264">
                                          +1264 AI
                                        </option>
                                        <option value="+672">
                                          +672 AQ
                                        </option>
                                        <option value="+1268">
                                          +1268 AG
                                        </option>
                                        <option value="+54">
                                          +54 AR
                                        </option>
                                        <option value="+374">
                                          +374 AM
                                        </option>
                                        <option value="+297">
                                          +297 AW
                                        </option>
                                        <option value="+61">
                                          +61 AU
                                        </option>
                                        <option value="+43">
                                          +43 AT
                                        </option>
                                        <option value="+994">
                                          +994 AZ
                                        </option>
                                        <option value="+1242">
                                          +1242 BS
                                        </option>
                                        <option value="+973">
                                          +973 BH
                                        </option>
                                        <option value="+880">
                                          +880 BD
                                        </option>
                                        <option value="+1246">
                                          +1246 BB
                                        </option>
                                        <option value="+375">
                                          +375 BY
                                        </option>
                                        <option value="+32">
                                          +32 BE
                                        </option>
                                        <option value="+501">
                                          +501 BZ
                                        </option>
                                        <option value="+229">
                                          +229 BJ
                                        </option>
                                        <option value="+1441">
                                          +1441 BM
                                        </option>
                                        <option value="+975">
                                          +975 BT
                                        </option>
                                        <option value="+591">
                                          +591 BO
                                        </option>
                                        <option value="+599">
                                          +599 BQ
                                        </option>
                                        <option value="+387">
                                          +387 BA
                                        </option>
                                        <option value="+267">
                                          +267 BW
                                        </option>
                                        <option value="+47">
                                          +47 BV
                                        </option>
                                        <option value="+55">
                                          +55 BR
                                        </option>
                                        <option value="+246">
                                          +246 IO
                                        </option>
                                        <option value="+673">
                                          +673 BN
                                        </option>
                                        <option value="+359">
                                          +359 BG
                                        </option>
                                        <option value="+226">
                                          +226 BF
                                        </option>
                                        <option value="+257">
                                          +257 BI
                                        </option>
                                        <option value="+855">
                                          +855 KH
                                        </option>
                                        <option value="+237">
                                          +237 CM
                                        </option>
                                        <option value="+1">
                                          +1 CA
                                        </option>
                                        <option value="+238">
                                          +238 CV
                                        </option>
                                        <option value="+1345">
                                          +1345 KY
                                        </option>
                                        <option value="+236">
                                          +236 CF
                                        </option>
                                        <option value="+235">
                                          +235 TD
                                        </option>
                                        <option value="+56">
                                          +56 CL
                                        </option>
                                        <option value="+86">
                                          +86 CN
                                        </option>
                                        <option value="+61">
                                          +61 CX
                                        </option>
                                        <option value="+61">
                                          +61 CC
                                        </option>
                                        <option value="+57">
                                          +57 CO
                                        </option>
                                        <option value="+269">
                                          +269 KM
                                        </option>
                                        <option value="+242">
                                          +242 CG
                                        </option>
                                        <option value="+243">
                                          +243 CD
                                        </option>
                                        <option value="+682">
                                          +682 CK
                                        </option>
                                        <option value="+506">
                                          +506 CR
                                        </option>
                                        <option value="+225">
                                          +225 CI
                                        </option>
                                        <option value="+385">
                                          +385 HR
                                        </option>
                                        <option value="+53">
                                          +53 CU
                                        </option>
                                        <option value="+599">
                                          +599 CW
                                        </option>
                                        <option value="+357">
                                          +357 CY
                                        </option>
                                        <option value="+420">
                                          +420 CZ
                                        </option>
                                        <option value="+45">
                                          +45 DK
                                        </option>
                                        <option value="+253">
                                          +253 DJ
                                        </option>
                                        <option value="+1767">
                                          +1767 DM
                                        </option>
                                        <option value="+1809">
                                          +1809 DO
                                        </option>
                                        <option value="+1829">
                                          +1829 DO
                                        </option>
                                        <option value="+1849">
                                          +1849 DO
                                        </option>
                                        <option value="+593">
                                          +593 EC
                                        </option>
                                        <option value="+20">
                                          +20 EG
                                        </option>
                                        <option value="+503">
                                          +503 SV
                                        </option>
                                        <option value="+240">
                                          +240 GQ
                                        </option>
                                        <option value="+291">
                                          +291 ER
                                        </option>
                                        <option value="+372">
                                          +372 EE
                                        </option>
                                        <option value="+251">
                                          +251 ET
                                        </option>
                                        <option value="+500">
                                          +500 FK
                                        </option>
                                        <option value="+298">
                                          +298 FO
                                        </option>
                                        <option value="+679">
                                          +679 FJ
                                        </option>
                                        <option value="+358">
                                          +358 FI
                                        </option>
                                        <option value="+33">
                                          +33 FR
                                        </option>
                                        <option value="+594">
                                          +594 GF
                                        </option>
                                        <option value="+689">
                                          +689 PF
                                        </option>
                                        <option value="+262">
                                          +262 TF
                                        </option>
                                        <option value="+241">
                                          +241 GA
                                        </option>
                                        <option value="+220">
                                          +220 GM
                                        </option>
                                        <option value="+995">
                                          +995 GE
                                        </option>
                                        <option value="+49">
                                          +49 DE
                                        </option>
                                        <option value="+233">
                                          +233 GH
                                        </option>
                                        <option value="+350">
                                          +350 GI
                                        </option>
                                        <option value="+30">
                                          +30 GR
                                        </option>
                                        <option value="+299">
                                          +299 GL
                                        </option>
                                        <option value="+1473">
                                          +1473 GD
                                        </option>
                                        <option value="+590">
                                          +590 GP
                                        </option>
                                        <option value="+1671">
                                          +1671 GU
                                        </option>
                                        <option value="+502">
                                          +502 GT
                                        </option>
                                        <option value="+44">
                                          +44 GG
                                        </option>
                                        <option value="+224">
                                          +224 GN
                                        </option>
                                        <option value="+245">
                                          +245 GW
                                        </option>
                                        <option value="+592">
                                          +592 GY
                                        </option>
                                        <option value="+509">
                                          +509 HT
                                        </option>
                                        <option value="+672">
                                          +672 HM
                                        </option>
                                        <option value="+379">
                                          +379 VA
                                        </option>
                                        <option value="+504">
                                          +504 HN
                                        </option>
                                        <option value="+852">
                                          +852 HK
                                        </option>
                                        <option value="+36">
                                          +36 HU
                                        </option>
                                        <option value="+354">
                                          +354 IS
                                        </option>
                                        <option value="+91">
                                          +91 IN
                                        </option>
                                        <option value="+62">
                                          +62 ID
                                        </option>
                                        <option value="+98">
                                          +98 IR
                                        </option>
                                        <option value="+964">
                                          +964 IQ
                                        </option>
                                        <option value="+353">
                                          +353 IE
                                        </option>
                                        <option value="+44">
                                          +44 IM
                                        </option>
                                        <option value="+972">
                                          +972 IL
                                        </option>
                                        <option value="+39">
                                          +39 IT
                                        </option>
                                        <option value="+1876">
                                          +1876 JM
                                        </option>
                                        <option value="+81">
                                          +81 JP
                                        </option>
                                        <option value="+44">
                                          +44 JE
                                        </option>
                                        <option value="+962">
                                          +962 JO
                                        </option>
                                        <option value="+7">
                                          +7 KZ
                                        </option>
                                        <option value="+254">
                                          +254 KE
                                        </option>
                                        <option value="+686">
                                          +686 KI
                                        </option>
                                        <option value="+850">
                                          +850 KP
                                        </option>
                                        <option value="+82">
                                          +82 KR
                                        </option>
                                        <option value="+965">
                                          +965 KW
                                        </option>
                                        <option value="+996">
                                          +996 KG
                                        </option>
                                        <option value="+856">
                                          +856 LA
                                        </option>
                                        <option value="+371">
                                          +371 LV
                                        </option>
                                        <option value="+961">
                                          +961 LB
                                        </option>
                                        <option value="+266">
                                          +266 LS
                                        </option>
                                        <option value="+231">
                                          +231 LR
                                        </option>
                                        <option value="+218">
                                          +218 LY
                                        </option>
                                        <option value="+423">
                                          +423 LI
                                        </option>
                                        <option value="+370">
                                          +370 LT
                                        </option>
                                        <option value="+352">
                                          +352 LU
                                        </option>
                                        <option value="+853">
                                          +853 MO
                                        </option>
                                        <option value="+389">
                                          +389 MK
                                        </option>
                                        <option value="+261">
                                          +261 MG
                                        </option>
                                        <option value="+265">
                                          +265 MW
                                        </option>
                                        <option value="+60">
                                          +60 MY
                                        </option>
                                        <option value="+960">
                                          +960 MV
                                        </option>
                                        <option value="+223">
                                          +223 ML
                                        </option>
                                        <option value="+356">
                                          +356 MT
                                        </option>
                                        <option value="+692">
                                          +692 MH
                                        </option>
                                        <option value="+596">
                                          +596 MQ
                                        </option>
                                        <option value="+222">
                                          +222 MR
                                        </option>
                                        <option value="+230">
                                          +230 MU
                                        </option>
                                        <option value="+262">
                                          +262 YT
                                        </option>
                                        <option value="+52">
                                          +52 MX
                                        </option>
                                        <option value="+691">
                                          +691 FM
                                        </option>
                                        <option value="+373">
                                          +373 MD
                                        </option>
                                        <option value="+377">
                                          +377 MC
                                        </option>
                                        <option value="+976">
                                          +976 MN
                                        </option>
                                        <option value="+382">
                                          +382 ME
                                        </option>
                                        <option value="+1664">
                                          +1664 MS
                                        </option>
                                        <option value="+212">
                                          +212 MA
                                        </option>
                                        <option value="+258">
                                          +258 MZ
                                        </option>
                                        <option value="+95">
                                          +95 MM
                                        </option>
                                        <option value="+264">
                                          +264 NA
                                        </option>
                                        <option value="+674">
                                          +674 NR
                                        </option>
                                        <option value="+977">
                                          +977 NP
                                        </option>
                                        <option value="+31">
                                          +31 NL
                                        </option>
                                        <option value="+687">
                                          +687 NC
                                        </option>
                                        <option value="+64">
                                          +64 NZ
                                        </option>
                                        <option value="+505">
                                          +505 NI
                                        </option>
                                        <option value="+227">
                                          +227 NE
                                        </option>
                                        <option value="+234">
                                          +234 NG
                                        </option>
                                        <option value="+683">
                                          +683 NU
                                        </option>
                                        <option value="+672">
                                          +672 NF
                                        </option>
                                        <option value="+1670">
                                          +1670 MP
                                        </option>
                                        <option value="+47">
                                          +47 NO
                                        </option>
                                        <option value="+968">
                                          +968 OM
                                        </option>
                                        <option value="+92">
                                          +92 PK
                                        </option>
                                        <option value="+680">
                                          +680 PW
                                        </option>
                                        <option value="+970">
                                          +970 PS
                                        </option>
                                        <option value="+507">
                                          +507 PA
                                        </option>
                                        <option value="+675">
                                          +675 PG
                                        </option>
                                        <option value="+595">
                                          +595 PY
                                        </option>
                                        <option value="+51">
                                          +51 PE
                                        </option>
                                        <option value="+63">
                                          +63 PH
                                        </option>
                                        <option value="+64">
                                          +64 PN
                                        </option>
                                        <option value="+48">
                                          +48 PL
                                        </option>
                                        <option value="+351">
                                          +351 PT
                                        </option>
                                        <option value="+1787">
                                          +1787 PR
                                        </option>
                                        <option value="+974">
                                          +974 QA
                                        </option>
                                        <option value="+383">
                                          +383 XK
                                        </option>
                                        <option value="+262">
                                          +262 RE
                                        </option>
                                        <option value="+40">
                                          +40 RO
                                        </option>
                                        <option value="+7">
                                          +7 RU
                                        </option>
                                        <option value="+250">
                                          +250 RW
                                        </option>
                                        <option value="+590">
                                          +590 BL
                                        </option>
                                        <option value="+290">
                                          +290 SH
                                        </option>
                                        <option value="+1869">
                                          +1869 KN
                                        </option>
                                        <option value="+1758">
                                          +1758 LC
                                        </option>
                                        <option value="+590">
                                          +590 MF
                                        </option>
                                        <option value="+508">
                                          +508 PM
                                        </option>
                                        <option value="+1784">
                                          +1784 VC
                                        </option>
                                        <option value="+685">
                                          +685 WS
                                        </option>
                                        <option value="+378">
                                          +378 SM
                                        </option>
                                        <option value="+239">
                                          +239 ST
                                        </option>
                                        <option value="+966">
                                          +966 SA
                                        </option>
                                        <option value="+221">
                                          +221 SN
                                        </option>
                                        <option value="+381">
                                          +381 RS
                                        </option>
                                        <option value="+248">
                                          +248 SC
                                        </option>
                                        <option value="+232">
                                          +232 SL
                                        </option>
                                        <option value="+65">
                                          +65 SG
                                        </option>
                                        <option value="+1721">
                                          +1721 SX
                                        </option>
                                        <option value="+421">
                                          +421 SK
                                        </option>
                                        <option value="+386">
                                          +386 SI
                                        </option>
                                        <option value="+677">
                                          +677 SB
                                        </option>
                                        <option value="+252">
                                          +252 SO
                                        </option>
                                        <option value="+27">
                                          +27 ZA
                                        </option>
                                        <option value="+500">
                                          +500 GS
                                        </option>
                                        <option value="+211">
                                          +211 SS
                                        </option>
                                        <option value="+34">
                                          +34 ES
                                        </option>
                                        <option value="+94">
                                          +94 LK
                                        </option>
                                        <option value="+249">
                                          +249 SD
                                        </option>
                                        <option value="+597">
                                          +597 SR
                                        </option>
                                        <option value="+47">
                                          +47 SJ
                                        </option>
                                        <option value="+268">
                                          +268 SZ
                                        </option>
                                        <option value="+46">
                                          +46 SE
                                        </option>
                                        <option value="+41">
                                          +41 CH
                                        </option>
                                        <option value="+963">
                                          +963 SY
                                        </option>
                                        <option value="+886">
                                          +886 TW
                                        </option>
                                        <option value="+992">
                                          +992 TJ
                                        </option>
                                        <option value="+255">
                                          +255 TZ
                                        </option>
                                        <option value="+66">
                                          +66 TH
                                        </option>
                                        <option value="+670">
                                          +670 TL
                                        </option>
                                        <option value="+228">
                                          +228 TG
                                        </option>
                                        <option value="+690">
                                          +690 TK
                                        </option>
                                        <option value="+676">
                                          +676 TO
                                        </option>
                                        <option value="+1868">
                                          +1868 TT
                                        </option>
                                        <option value="+216">
                                          +216 TN
                                        </option>
                                        <option value="+90">
                                          +90 TR
                                        </option>
                                        <option value="+993">
                                          +993 TM
                                        </option>
                                        <option value="+1649">
                                          +1649 TC
                                        </option>
                                        <option value="+688">
                                          +688 TV
                                        </option>
                                        <option value="+256">
                                          +256 UG
                                        </option>
                                        <option value="+380">
                                          +380 UA
                                        </option>
                                        <option value="+971">
                                          +971 AE
                                        </option>
                                        <option value="+44">
                                          +44 GB
                                        </option>
                                        <option value="+1">
                                          +1 US
                                        </option>
                                        <option value="+246">
                                          +246 UM
                                        </option>
                                        <option value="+598">
                                          +598 UY
                                        </option>
                                        <option value="+998">
                                          +998 UZ
                                        </option>
                                        <option value="+678">
                                          +678 VU
                                        </option>
                                        <option value="+58">
                                          +58 VE
                                        </option>
                                        <option value="+84">
                                          +84 VN
                                        </option>
                                        <option value="+1284">
                                          +1284 VG
                                        </option>
                                        <option value="+1340">
                                          +1340 VI
                                        </option>
                                        <option value="+681">
                                          +681 WF
                                        </option>
                                        <option value="+212">
                                          +212 EH
                                        </option>
                                        <option value="+967">
                                          +967 YE
                                        </option>
                                        <option value="+260">
                                          +260 ZM
                                        </option>
                                        <option value="+263">
                                          +263 ZW
                                        </option>
                                      </select>
                                    </div>
                                    <div class="entry__field" style="width: 100%">
                                      <input type="tel" type="text" class="input" id="SMS" name="SMS" autocomplete="off" placeholder="SMS" data-required="true" required />
                                    </div>
                                  </div>
                                  <div class="sib-sms-tooltip">
                                    <div class="sib-sms-tooltip__box">
                                      The SMS field must contain between 6 and 19 digits and include the country code without using +/0 (e.g. 1xxxxxxxxxx for the United States)
                                    </div>
                                    <span class="sib-sms-tooltip__icon">?</span>
                                  </div>
                                </div>
                              </div>
                
                              <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                              </label>
                              <label class="entry__error entry__error--secondary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                              </label>
                              <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                                Customize this optional help text before publishing your form.
                              </label>
                            </div>
                          </div>
                        </div>
                        <div style="padding: 8px 0;">
                          <div class="sib-optin sib-form-block">
                            <div class="form__entry entry_mcq">
                              <div class="form__label-row ">
                                <div class="entry__choice" style="">
                                  <label>
                                    <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN" />
                                    <span class="checkbox checkbox_tick_positive"
                            style="margin-left:"
                            ></span><span style="font-size:14px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#3C4858; background-color:transparent;"><p>I agree to receive your offers and accept the data privacy statement.</p></span> </label>
                                </div>
                              </div>
                              <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                              </label>
                              <label class="entry__specification" style="font-size:12px; text-align:left; font-family:&quot;Helvetica&quot;, sans-serif; color:#8390A4; text-align:left">
                                You may unsubscribe at any time using the link in our newsletter.
                              </label>
                            </div>
                          </div>
                        </div>
                        <div style="padding: 8px 0;">
                          <div class="sib-form-block" style="text-align: left">
                            <button class="sib-form-block__button sib-form-block__button-with-loader" style="font-size:16px; text-align:left; font-weight:700; font-family:&quot;Helvetica&quot;, sans-serif; color:#FFFFFF; background-color:#f72658; border-radius:3px; border-width:0px;" form="sib-form" type="submit">
                              <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                                <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z" />
                              </svg>
                              Submit
                            </button>
                          </div>
                        </div>
                
                        <input type="text" name="email_address_check" value="" class="input--hidden">
                        <input type="hidden" name="locale" value="en">
                      </form>
                    </div>
                  </div>
                </div>
        </div>
    </div>
</div>   

@endsection
@section('footer')
<script defer src="{{ url('basicfront/js/jquery.validate.min.js') }}"></script>
<script defer src="{{ url('blog_assets/js/vendor/owl.carousel.min.js') }}"></script>
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
        //    $("#requstcontactPopup #inquiry_for").val(inquiry_for);
        });
        $("#requstcontactPopup .close").on("click", function(){
        //    $("#requstcontactPopup #inquiry_for").val("");
            $("#frmRequestCall")[0].reset();
           $("#requstcontactPopup").hide();
        });
        $(".owl-carousel").owlCarousel({
            items:1,
            loop:true,
            autoplay:true,
            nav:false,
            autoplayTimeout:10000,
            autoplayHoverPause:true
        });
    });
</script>
<script>
  window.REQUIRED_CODE_ERROR_MESSAGE = 'Please choose a country code';
  window.LOCALE = 'en';
  window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";

  window.REQUIRED_ERROR_MESSAGE = "This field cannot be left blank. ";

  window.GENERIC_INVALID_MESSAGE = "The information provided is invalid. Please review the field format and try again.";




  window.translation = {
    common: {
      selectedList: '{quantity} list selected',
      selectedLists: '{quantity} lists selected'
    }
  };

  var AUTOHIDE = Boolean(0);
</script>
<script defer src="https://sibforms.com/forms/end-form/build/main.js"></script>
<!--script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery.instagramFeed/3.0.3/jquery.instagramFeed.min.js"></script>
<script type="text/javascript">
    $(window).on('load', function(){
        $.instagramFeed({
            'username': 'thebalanceboat',
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