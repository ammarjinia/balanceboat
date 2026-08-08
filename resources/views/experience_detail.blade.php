@extends('layouts.experience_details')
@section('title', @$experience->name)

<?php
$category = $subcategory = "";
foreach ($experience_categories as $ecat) {
    if ($ecat->parent == 0) {
        $category = $ecat->name;
    }
    if ($ecat->parent != 0) {
        $subcategory .= $ecat->name . ", ";
    }
}
?>

<?php
$country = $city = "";
foreach ($experience_destination as $edest) {
    if ($edest->parent == 0) {
        $country = $edest->name;
    }
    if ($edest->parent != 0) {
        $city .= $edest->name . ", ";
    }
}
?>


<!-- Meta Info Start-->
<?php if (!empty(@$experience->meta_title)) { ?>
    @section('meta_title', @$experience->meta_title)
<?php } else { ?>
    @section('meta_title', @$experience->name." in ".$city.", ".$country)
<?php } ?>
<?php if (!empty(@$experience->meta_description)) { ?>
    @section('description', strip_tags(@$experience->meta_description))
<?php } else { ?>
    @section('description', \App\Http\Helpers\CommonHelper::excerpt(strip_tags(html_entity_decode(@$experience->experience_overview)),160))
<?php } ?>
<?php if (!empty(@$experience->keywords)) { ?>
    @section('keywords', strip_tags(@$experience->keywords))
<?php } else { ?>
    @section('keywords', "learn ".$subcategory." in ".$country.", learn and find ".$subcategory.", learn and find ".$category.", ".$subcategory." in ".$city.", ".$category." in ".$country." ".$city.", course in ".$country." ".$city.", ".$category." retreat in ".$country." ".$city)
<?php } ?>
<?php if (!empty(@$experience->banner_image_url)) { ?>
    @section('image', Storage::disk('s3')->url(rawurlencode(@$experience->banner_image_url)))
<?php } ?>
<!-- Meta Info End -->
@section('head')
<style type="text/css">
    .xd-page {
        --xd-brand: var(--brand-color, #ff3366);
        --xd-brand-dark: var(--brand-color-dark, #e62050);
        --xd-brand-glow: rgba(255, 51, 102, 0.2);
        --xd-brand-light: rgba(255, 51, 102, 0.06);
        --xd-text-main: var(--primary-color, #111827);
        --xd-text-body: #4b5563;
        --xd-text-muted: var(--c-medium, #6b7280);
        --xd-border: var(--border-1, #e5e7eb);
        --xd-surface: var(--c-white, #fff);
        --xd-subtle: var(--c-light, #f8f9fa);
        --xd-radius-xl: 20px;
        --xd-radius-lg: 14px;
        --xd-radius-md: var(--radius-8, 10px);
        --xd-radius-pill: var(--button-radius, 100px);
        --xd-shadow-card: 0 4px 20px rgba(0, 0, 0, 0.05);
        --xd-shadow-float: 0 15px 35px rgba(0, 0, 0, 0.08);
        --xd-shadow-colored: 0 8px 20px rgba(255, 51, 102, 0.18);
    }

    /* Gallery grid cosmetic upgrade (structure/JS unchanged) */
    .xd-gallery.bg-listing-gallery { border-radius: var(--xd-radius-xl); overflow: hidden; box-shadow: var(--xd-shadow-card); }

    /* Hero */
    .xd-hero-banner {
        position: relative;
        border-radius: var(--xd-radius-xl);
        overflow: hidden;
        min-height: 340px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 28px 36px;
        margin-top: 24px;
        box-shadow: var(--xd-shadow-float);
        background: var(--xd-text-main);
    }
    .xd-hero-bg { position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 0; }
    .xd-hero-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(17,24,39,0.15) 0%, rgba(17,24,39,0.85) 100%); z-index: 1; }
    .xd-hero-top, .xd-hero-bottom { position: relative; z-index: 2; }
    .xd-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .xd-hero-tag {
        display: inline-block; background: rgba(255,255,255,0.18); color: #fff; border: 1px solid rgba(255,255,255,0.4);
        padding: 5px 14px; border-radius: var(--xd-radius-pill); font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
    }
    .xd-hero-actions { display: flex; align-items: center; gap: 8px; margin-left: auto; }
    .xd-hero-icon-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%;
        background: rgba(255,255,255,0.18); color: #fff; cursor: pointer; text-decoration: none;
    }
    .xd-hero-title { font-size: 30px; color: #fff; line-height: 1.15; font-weight: 600; margin-bottom: 10px; }
    .xd-hero-meta { display: flex; flex-wrap: wrap; gap: 18px; align-items: center; color: rgba(255,255,255,0.92); font-size: 14px; }
    .xd-hero-meta .icon-location { margin-right: 4px; }
    .xd-hero-price { display: inline-flex; align-items: center; gap: 8px; }
    .xd-hero-price del { color: rgba(255,255,255,0.65); font-weight: normal; }
    .xd-hero-price strong { color: #fff; font-size: 16px; }
    .xd-hero-banner .tier-badge { position: relative; }

    /* Layout grid */
    .xd-layout-grid { display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start; margin-top: 8px; }
    .xd-main-col { min-width: 0; }

    /* Cards */
    .xd-card {
        background: var(--xd-surface);
        border: 1px solid var(--xd-border);
        border-radius: var(--xd-radius-xl);
        padding: 32px;
        margin-bottom: 28px;
        box-shadow: var(--xd-shadow-card);
    }
    .xd-tag {
        display: inline-block; background: var(--xd-brand-light); color: var(--xd-brand);
        border: 1px solid rgba(255,51,102,0.2); padding: 4px 12px; border-radius: var(--xd-radius-pill);
        font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;
    }
    .xd-title { font-size: 20px; color: var(--xd-text-main); font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .xd-title-icon {
        display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 12px;
        background: var(--xd-brand-light); font-size: 18px; line-height: 1; flex-shrink: 0;
    }
    .xd-card table { width: 100%; border-collapse: collapse; }
    .xd-card table th, .xd-card table td { padding: 8px 10px; border-bottom: 1px solid var(--xd-border); text-align: left; font-size: 14px; }
    .xd-card table th { color: var(--xd-text-muted); font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }

    /* Highlights grid */
    .xd-audience-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .xd-audience-item {
        display: flex; align-items: flex-start; gap: 14px; background: var(--xd-subtle); padding: 18px;
        border-radius: var(--xd-radius-md); border: 1px solid var(--xd-border);
    }
    .xd-audience-icon { color: var(--xd-brand); font-size: 15px; line-height: 1.4; }

    /* Room cards */
    .xd-room-list { display: flex; flex-direction: column; gap: 26px; }
    .xd-room-card {
        display: grid; grid-template-columns: 260px 1fr; background: var(--xd-subtle); border-radius: var(--xd-radius-lg);
        overflow: hidden; border: 1px solid var(--xd-border);
    }
    .xd-room-gallery { display: flex; flex-direction: column; padding: 12px; gap: 8px; background: var(--xd-surface); }
    .xd-room-main-img { width: 100%; height: 170px; object-fit: cover; border-radius: var(--xd-radius-md); background: var(--xd-subtle); }
    .xd-room-thumbs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
    .xd-room-thumb { width: 100%; height: 44px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; opacity: 0.8; }
    .xd-room-thumb:hover { opacity: 1; border-color: var(--xd-brand); }
    .xd-room-info { padding: 22px; }
    .xd-room-badge { display: inline-block; background: var(--xd-brand); color: #fff; padding: 5px 12px; border-radius: 10px; font-size: 12px; font-weight: bold; }
    .xd-room-loc { color: var(--xd-text-muted); font-size: 13px; margin-top: 8px; }
    .xd-room-loc .icon-location { color: var(--xd-brand); margin-right: 4px; }
    .xd-room-tags { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0; }
    .xd-room-tag {
        background: var(--xd-surface); color: var(--xd-text-body); border: 1px solid var(--xd-border);
        padding: 3px 10px; border-radius: var(--xd-radius-pill); font-size: 12px;
    }
    .xd-badge { padding: 3px 10px; border-radius: var(--xd-radius-pill); font-size: 11px; font-weight: 600; }
    .xd-badge-open { background: #dcfce7; color: #16a34a; }
    .xd-badge-warn { background: #fef9c3; color: #a16207; }
    .xd-badge-danger { background: #fee2e2; color: #dc2626; }
    .xd-room-title { font-size: 17px; color: var(--xd-text-main); font-weight: 600; margin: 6px 0; }
    .xd-room-title a { color: inherit; text-decoration: none; }
    .xd-room-avail-note { font-size: 13px; color: var(--xd-text-muted); font-weight: normal; margin-bottom: 8px; }
    .xd-room-desc-list { margin: 8px 0; }
    .xd-room-about { font-size: 13px; color: var(--xd-text-body); }
    .xd-occ-table { margin: 12px 0; overflow-x: auto; }
    .xd-room-bottom { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 16px; flex-wrap: wrap; }
    .xd-room-price-block small { color: var(--xd-text-muted); font-size: 12px; }
    .xd-room-price { font-size: 17px; font-weight: bold; color: var(--xd-brand); display: flex; align-items: center; gap: 8px; }
    .xd-room-price del { color: var(--xd-text-muted); font-weight: normal; font-size: 13px; }
    .xd-room-cta { display: flex; gap: 8px; }

    /* Daily routine */
    .xd-routine-list { display: flex; flex-direction: column; gap: 12px; }
    .xd-routine-item {
        display: grid; grid-template-columns: 140px 1fr; gap: 16px; padding: 14px 18px; background: var(--xd-subtle);
        border-left: 3px solid var(--xd-brand); border-radius: 0 var(--xd-radius-md) var(--xd-radius-md) 0; align-items: center;
    }
    .xd-routine-time { font-weight: bold; color: var(--xd-brand); font-size: 12px; }
    .xd-routine-desc h4 { font-size: 15px; font-weight: 600; color: var(--xd-text-main); margin-bottom: 2px; }
    .xd-routine-desc p { margin: 0; color: var(--xd-text-body); font-size: 14px; }

    /* Amenities */
    .xd-amenities-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .xd-amenity-card {
        display: flex; align-items: center; gap: 10px; padding: 14px; background: var(--xd-subtle);
        border: 1px solid var(--xd-border); border-radius: var(--xd-radius-md); color: var(--xd-text-main); font-weight: 600; font-size: 14px;
    }

    /* Culinary */
    .xd-culinary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .xd-culinary-img { width: 100%; height: 190px; object-fit: cover; border-radius: var(--xd-radius-lg); border: 1px solid var(--xd-border); }

    /* Inclusions/Exclusions */
    .xd-inc-exc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .xd-inc-card { padding: 20px; border-radius: var(--xd-radius-lg); border: 1px solid var(--xd-border); background: var(--xd-subtle); }
    .xd-inc-head { font-size: 15px; font-weight: bold; margin-bottom: 10px; }
    .xd-inc-head.included { color: #16a34a; }
    .xd-inc-head.excluded { color: #dc2626; }
    .xd-inc-list ul { margin: 0; padding-left: 18px; }
    .xd-inc-list p { margin: 0 0 6px 0; }

    /* Sidebar / booking card */
    .xd-sidebar { position: sticky; top: 90px; align-self: start; }
    .xd-booking-card {
        background: var(--xd-surface); border: 1px solid var(--xd-border); border-radius: var(--xd-radius-xl);
        padding: 22px; box-shadow: var(--xd-shadow-float);
    }
    .xd-booking-header { text-align: center; margin-bottom: 14px; }
    .xd-discount-tag {
        display: inline-flex; align-items: center; gap: 4px; background: rgba(220,38,38,0.08); color: #dc2626;
        border: 1px solid rgba(220,38,38,0.2); font-size: 10px; font-weight: bold; text-transform: uppercase;
        letter-spacing: 0.5px; padding: 3px 10px; border-radius: var(--xd-radius-pill); margin-bottom: 8px;
    }
    .xd-booking-title { font-size: 16px; color: var(--xd-text-main); margin-bottom: 4px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .xd-form-group { margin-bottom: 12px; }
    .xd-form-label { display: block; font-size: 11px; font-weight: bold; color: var(--xd-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .xd-select, .xd-input {
        width: 100%; padding: 9px 12px; background: var(--xd-subtle); border: 1px solid var(--xd-border);
        border-radius: var(--xd-radius-md); font-size: 14px; color: var(--xd-text-main);
    }
    .xd-select:focus, .xd-input:focus { outline: none; border-color: var(--xd-brand); }
    .xd-calc-box { background: var(--xd-subtle); border-radius: var(--xd-radius-md); padding: 10px 14px; margin: 12px 0; border: 1px solid var(--xd-border); }
    .xd-calc-row { display: flex; justify-content: space-between; margin-bottom: 4px; color: var(--xd-text-body); font-size: 13px; }
    .xd-calc-row.total { border-top: 1px solid var(--xd-border); padding-top: 6px; margin-bottom: 0; font-size: 15px; color: var(--xd-text-main); font-weight: bold; }
    .xd-btn-gradient {
        width: 100%; background: linear-gradient(135deg, var(--xd-brand) 0%, var(--xd-brand-dark) 100%); color: #fff; border: none;
        padding: 12px; border-radius: var(--xd-radius-pill); font-size: 14px; font-weight: bold; cursor: pointer;
        box-shadow: var(--xd-shadow-colored); text-align: center; text-decoration: none; display: inline-block;
    }
    .xd-btn-gradient:disabled { opacity: 0.55; cursor: not-allowed; }
    .xd-btn-outline {
        width: 100%; background: transparent; color: var(--xd-text-main); border: 1px solid var(--xd-border);
        padding: 9px; border-radius: var(--xd-radius-pill); font-size: 13px; font-weight: bold; cursor: pointer; margin-bottom: 8px;
    }
    .xd-btn-outline:hover { border-color: var(--xd-brand); color: var(--xd-brand); }
    .xd-btn-sm { width: auto; padding: 7px 16px; font-size: 12px; }

    /* Mobile bottom bar + drawer */
    .xd-mobile-bar {
        display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--xd-surface);
        border-top: 1px solid var(--xd-border); padding: 10px 16px; box-shadow: 0 -4px 20px rgba(0,0,0,0.12); z-index: 1500;
        flex-direction: column; gap: 8px;
    }
    .xd-mobile-bar-top { display: flex; justify-content: center; }
    .xd-mobile-discount-tag {
        background: rgba(220,38,38,0.1); color: #dc2626; border: 1px solid rgba(220,38,38,0.25);
        font-size: 11px; font-weight: bold; padding: 3px 10px; border-radius: var(--xd-radius-pill);
    }
    .xd-mobile-discount-tag--plain { background: var(--xd-subtle); color: var(--xd-text-muted); border-color: var(--xd-border); }
    .xd-mobile-bar-bottom { display: flex; gap: 10px; }
    .xd-btn-mobile-outline, .xd-btn-mobile-gradient {
        flex: 1; padding: 10px; border-radius: var(--xd-radius-pill); font-size: 13px; font-weight: bold; cursor: pointer; border: none;
    }
    .xd-btn-mobile-outline { background: var(--xd-surface); color: var(--xd-text-main); border: 1px solid var(--xd-border); }
    .xd-btn-mobile-gradient { background: linear-gradient(135deg, var(--xd-brand) 0%, var(--xd-brand-dark) 100%); color: #fff; box-shadow: var(--xd-shadow-colored); }

    .xd-mobile-drawer-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 2100; display: none; opacity: 0; transition: opacity 0.25s ease;
    }
    .xd-mobile-drawer-overlay.active { display: flex; opacity: 1; }
    .xd-mobile-drawer {
        position: absolute; bottom: 0; left: 0; right: 0; background: var(--xd-surface);
        border-top-left-radius: 22px; border-top-right-radius: 22px; padding: 22px 18px 18px; max-height: 85vh; overflow-y: auto;
    }
    .xd-drawer-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .xd-modal-close { background: none; border: none; font-size: 16px; cursor: pointer; color: var(--xd-text-muted); }

    @media (max-width: 1024px) {
        .xd-layout-grid { grid-template-columns: 1fr; }
        .xd-sidebar { display: none; }
        .xd-mobile-bar { display: flex; }
        body { padding-bottom: 90px; }
    }
    @media (max-width: 768px) {
        .xd-hero-banner { padding: 22px 18px; min-height: 280px; }
        .xd-room-card, .xd-audience-grid, .xd-amenities-grid, .xd-culinary-grid, .xd-inc-exc-grid { grid-template-columns: 1fr; }
        .xd-routine-item { grid-template-columns: 1fr; border-left: none; border-top: 3px solid var(--xd-brand); border-radius: 0 0 var(--xd-radius-md) var(--xd-radius-md); }
        .xd-card { padding: 20px; }
        .xd-hero-title { font-size: 24px; }
    }
</style>
<script defer src="https://cdn.razorpay.com/widgets/affordability/affordability.js"></script>
@endsection
@section('content')
<div class="xd-page">

    <!-- Hero -->
    <section class="pt-2">
        <div class="container">
            <div class="xd-hero-banner">
                @if(@$experience->banner_image_url)
                <div class="xd-hero-bg" style="background-image:url('{{ strtok(Storage::disk('s3')->url(rawurlencode(@$experience->banner_image_url)),'?') }}');"></div>
                @endif
                <div class="xd-hero-overlay"></div>
                <div class="xd-hero-top">
                    <div class="d-flex align-items-center" style="gap:8px; flex-wrap:wrap;">
                        @if($category)
                        <span class="xd-hero-tag">{{ $category }}</span>
                        @endif
                        @include('partials.commission-tier-badge')
                    </div>
                    <div class="xd-hero-actions">
                        <div class="bg-menu-list">
                            <span class="xd-hero-icon-btn"><span class="icon-share"></span></span>
                            <ul class="bg-box horiz">
                                <li><a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a></li>
                                <li><a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a></li>
                            </ul>
                        </div>
                        <a href="#booking-card" class="xd-hero-icon-btn"><span class="icon-compass"></span></a>
                    </div>
                </div>
                <div class="xd-hero-bottom">
                    <h1 class="xd-hero-title">{{ @$experience->name }}</h1>
                    <div class="xd-hero-meta">
                        @if(@$center->address_of_center || @$experience->location)
                        <span><span class="icon-location"></span>{{ @$center->address_of_center }} {{ @$experience->location }}</span>
                        @endif
                        <?php $discount = $razorPayAmount = 0; ?>
                        @if(@$experienceList->min_duration_price)
                        <?php
                        $pay = @$experienceList->min_promo_price ? @$experienceList->min_promo_price : @$experienceList->min_duration_price;
                        if ((!empty(@$experienceList->offer_start_date)) && (!empty(@$experienceList->offer_discount)) && (@$experienceList->offer_discount > 0)) {
                            $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                            if ((\Carbon\Carbon::parse(@$experienceList->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experienceList->offer_end_date)->format("Y-m-d") >= $now)) {
                                if (@$experienceList->offer_discount_type == "amt") {
                                    $discount += @$experienceList->offer_discount;
                                } else {
                                    $discount += (@$pay * @$experienceList->offer_discount) / 100;
                                }
                            }
                        }
                        $razorPayAmount = \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency, false);
                        ?>
                        <span class="xd-hero-price">
                            From
                            @if(!empty($discount))
                            <del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), $site_currency) }}</del>
                            @endif
                            <strong>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency) }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="pt-4">
        <div class="container">
            <div class="bg-listing-gallery main xd-gallery">
                <?php $i = 0;?>
                @if(@$experience->banner_image_url)
                <div class="bg-listing-gallery-items one">
                    <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode(@$experience->banner_image_url)),'?') }}" alt="{!! $experience->banner_image_url !!}" />
                </div>
                @else
                    <?php $i = -1;?>
                @endif
                <?php
                if ($i == -1) {
                    $gi = array("one","two", "three", "four", "five");
                } else {
                    $gi = array("two", "three", "four", "five");
                }?>
                @foreach(@$imagegalleries as $gallery)
                <?php if ($i < 4) {
                    $i++;
                    ?>
                    <div class="bg-listing-gallery-items <?php echo ($gi[$i - 1]) ?? ''; ?>">
                        @if(@$gallery->image_url)
                        @if($gallery->bg_exp_id)
                        <img class="lazy" data-src="{{ strtok(Storage::disk('azure_bg')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" />
                        @else
                        <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" />
                        @endif
                        @endif
                    </div>
                <?php } ?>
                @endforeach
                <span id="bg-gallery-all" class="show-all-btn">
                    <span class="icon-arrows1 me-2"></span> Show All
                </span>
            </div>
        </div>
    </section>

    <section class="pt-5 mb-5">
        <div class="container">
            <div class="xd-layout-grid">
                <main id="package" class="xd-main-col">

                    <div class="intereactive-lists-loader">
                        <div class="row"><div class="col-12"><div class="il-wrapper bg-box"><div class="listing-loader"></div></div></div></div>
                        <div class="row"><div class="col-12"><div class="il-wrapper bg-box"><div class="listing-loader"></div></div></div></div>
                        <div class="row"><div class="col-12"><div class="il-wrapper bg-box"><div class="listing-loader"></div></div></div></div>
                        <div class="row"><div class="col-12"><div class="il-wrapper bg-box"><div class="listing-loader"></div></div></div></div>
                        <div class="row"><div class="col-12"><div class="il-wrapper bg-box"><div class="listing-loader"></div></div></div></div>
                    </div>

                    <div class="intereactive-lists no-loader d-none">

                        {{-- Overview --}}
                        @if(@$experience->experience_overview)
                        <div class="xd-card">
                            <span class="xd-tag">The Philosophy</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#x1F9D8;</span> Overview</h2>
                            <div>{!! @$experience->experience_overview !!}</div>
                        </div>
                        @endif

                        {{-- Highlights (real experience_highlights field) --}}
                        @php
                            $highlightsText = trim(strip_tags(@$experience->experience_highlights));
                            $highlightPoints = array();
                            if ($highlightsText !== '') {
                                $highlightPoints = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $highlightsText))));
                                if (count($highlightPoints) <= 1) {
                                    $highlightPoints = array_values(array_filter(array_map('trim', preg_split('/(?<=[.!?])\s+/', $highlightsText, -1, PREG_SPLIT_NO_EMPTY))));
                                }
                            }
                        @endphp
                        @if(count($highlightPoints) > 0)
                        <div class="xd-card">
                            <span class="xd-tag">Why This Retreat</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#10022;</span> Highlights</h2>
                            <div class="xd-audience-grid">
                                @foreach($highlightPoints as $point)
                                <div class="xd-audience-item">
                                    <span class="xd-audience-icon">&#10022;</span>
                                    <div>{{ $point }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Experience Summary --}}
                        @if(@$experience->experience_summary)
                        <div class="xd-card">
                            <span class="xd-tag">At a Glance</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128203;</span> Experience Summary</h2>
                            @php
                                $summaryText = trim(strip_tags($experience->experience_summary));
                                $summaryPoints = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $summaryText))));
                                if (count($summaryPoints) <= 1) {
                                    $summaryPoints = array_values(array_filter(array_map('trim', preg_split('/(?<=[.!?])\s+/', $summaryText, -1, PREG_SPLIT_NO_EMPTY))));
                                }
                            @endphp
                            <ul class="bg-list-icon">
                                @foreach($summaryPoints as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Sanctuaries / Accommodations --}}
                        @if(sizeof(@$experience_accomodations) > 0)
                        <div class="xd-card" id="accomodation-rooms">
                            <span class="xd-tag">The Residences</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127968;</span> Choose Your Room</h2>
                            <div class="xd-room-list">
                                @foreach(@$experience_accomodations as $experience_accomodation)
                                <?php
                                $roomImgs = array();
                                if (@$accomodationimagegalleries) {
                                    foreach (@$accomodationimagegalleries as $ex_img) {
                                        if ($ex_img->accomodation_id == $experience_accomodation->id && $ex_img->image_url) {
                                            $roomImgs[] = $ex_img;
                                        }
                                    }
                                }
                                $roomMainId = 'xd-room-main-'.$experience_accomodation->id;
                                ?>
                                <div class="xd-room-card">
                                    <div class="xd-room-gallery">
                                        @if(sizeof($roomImgs) > 0)
                                        <img class="lazy xd-room-main-img" id="{{ $roomMainId }}" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($roomImgs[0]->image_url)),'?') }}" alt="{{ $experience_accomodation->name }}" />
                                        @if(sizeof($roomImgs) > 1)
                                        <div class="xd-room-thumbs">
                                            @foreach($roomImgs as $ri)
                                            <img class="lazy xd-room-thumb" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($ri->image_url)),'?') }}" onclick="xdSwapRoomImage('{{ $roomMainId }}', this)" alt="{{ $ri->image_title }}" />
                                            @endforeach
                                        </div>
                                        @endif
                                        @else
                                        <div class="xd-room-main-img" id="{{ $roomMainId }}"></div>
                                        @endif
                                    </div>
                                    <div class="xd-room-info">
                                        <span class="xd-room-badge">{{ $experience_accomodation->name }}</span>
                                        <div class="xd-room-loc">
                                            <span class="icon-location"></span><?php echo \App\Experiences::get_state_country($experience->id); ?>
                                        </div>
                                        @php
                                            $roomCapacity = @$experience_accomodation->ea_max_guest_in_room ?: @$experience_accomodation->max_guest_in_room;
                                            $nextAvail = @$experience_availability_next[$experience_accomodation->id] ?? null;
                                        @endphp
                                        <div class="xd-room-tags">
                                            @if($roomCapacity)
                                            <span class="xd-room-tag"><span class="icon-user"></span> Sleeps up to {{ $roomCapacity }}</span>
                                            @endif
                                            @if(@$experience_accomodation->duration)
                                            <span class="xd-room-tag">{{ @$experience_accomodation->duration }} Days</span>
                                            @endif
                                            @if($nextAvail)
                                            <span class="xd-badge {{ $nextAvail->status == 'open' ? 'xd-badge-open' : ($nextAvail->status == 'few_left' ? 'xd-badge-warn' : 'xd-badge-danger') }}">
                                                {{ \App\ExperienceAccommodationAvailability::statusLabel($nextAvail->status) }}
                                                @if(in_array($nextAvail->status, ['open', 'few_left']))
                                                &middot; {{ $nextAvail->remaining }} left from {{ \Carbon\Carbon::parse($nextAvail->start_date)->format('d M Y') }}
                                                @endif
                                            </span>
                                            @endif
                                        </div>

                                        <h3 class="xd-room-title">
                                            <a href="javascript:void(0);" class="c-pointer popup-large more-info-deal">{{ $experience_accomodation->name }}</a>
                                        </h3>

                                        <h4 class="xd-room-avail-note">
                                            @if(@$experience_accomodation->recurring_type == "Daily")
                                            Available all year round
                                            @else
                                            {{ @$experience_accomodation->available_month }}
                                            @endif
                                        </h4>

                                        <ul class="bg-list-icon xd-room-desc-list">
                                            {!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$experience_accomodation->description))) !!}
                                        </ul>

                                        @if(@$experience_accomodation->ea_about)
                                        <p class="xd-room-about">{!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$experience_accomodation->ea_about), 200)) !!}</p>
                                        @endif

                                        @php
                                            $durPrices = @$experience_accommodation_duration_prices[$experience_accomodation->id] ?? collect();
                                        @endphp
                                        @if(@$experience_accomodation->single_occupancy_price || @$experience_accomodation->double_occupancy_price || $durPrices->count())
                                        <div class="xd-occ-table">
                                            <table class="table table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Duration</th>
                                                        <th>Single Occupancy</th>
                                                        <th>Double Occupancy (pp)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($durPrices->count())
                                                        @foreach($durPrices as $dp)
                                                        <tr>
                                                            <td>{{ $dp->duration_days }} Days</td>
                                                            <td>{{ $dp->single_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($dp->single_price, $dp->currency ?: @$experience_accomodation->currency) : '-' }}</td>
                                                            <td>{{ $dp->double_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($dp->double_price, $dp->currency ?: @$experience_accomodation->currency) : '-' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                    <tr>
                                                        <td>{{ @$experience_accomodation->duration ? @$experience_accomodation->duration.' Days' : 'Standard' }}</td>
                                                        <td>{{ @$experience_accomodation->single_occupancy_price ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience_accomodation->single_occupancy_price, @$experience_accomodation->currency) : '-' }}</td>
                                                        <td>{{ @$experience_accomodation->double_occupancy_price ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience_accomodation->double_occupancy_price, @$experience_accomodation->currency) : '-' }}</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif

                                        <div class="xd-room-bottom">
                                            <div class="xd-room-price-block">
                                                <small>Total for {{ @$experience_accomodation->duration }}</small>
                                                <div class="xd-room-price">
                                                    <?php
                                                    $discount = 0;
                                                    $pay = @$experience_accomodation->room_price;
                                                    if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                                                        if (@$experience->eirly_bird_discount_type == "amt") {
                                                            $discount += @$experience->eirly_bird_discount;
                                                        } else {
                                                            $discount = (@$pay * @$experience->eirly_bird_discount) / 100;
                                                        }
                                                    }
                                                    if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                                                        $now = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                                                        if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $now) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $now)) {
                                                            if (@$experience->offer_discount_type == "amt") {
                                                                $discount += @$experience->offer_discount;
                                                            } else {
                                                                $discount += (@$pay * @$experience->offer_discount) / 100;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    @if(!empty($discount))
                                                    <del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), @$experience_accomodation->currency) }}</del>
                                                    @endif
                                                    <span>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, @$experience_accomodation->currency) }}</span>
                                                </div>
                                            </div>
                                            <div class="xd-room-cta">
                                                <a href="#booking-card" class="xd-btn-gradient xd-btn-sm">Select Room</a>
                                                <button type="button" data-popup="requstcallPopup" class="show-bg-modal xd-btn-outline xd-btn-sm">Send Inquiry</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Price Options --}}
                        @if(@$experience_durations && sizeof(@$experience_durations) > 0)
                        <div class="xd-card" id="price-options">
                            <span class="xd-tag">Flexible Stays</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128176;</span> Price Options</h2>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Promo Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(@$experience_durations as $ed)
                                        <tr>
                                            <td>{{ $ed->duration }} Days</td>
                                            <td>{{ $ed->price ? \App\Http\Helpers\CommonHelper::get_currency_rate($ed->price, $ed->currency) : '-' }}</td>
                                            <td>{{ $ed->promo_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($ed->promo_price, $ed->currency) : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- Upcoming Availability --}}
                        @if(@$experience_upcoming_availability && sizeof(@$experience_upcoming_availability) > 0)
                        <div class="xd-card" id="availability">
                            <span class="xd-tag">Plan Ahead</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128197;</span> Upcoming Availability</h2>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>Accommodation</th>
                                            <th>Status</th>
                                            <th>Rooms Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(@$experience_upcoming_availability as $avail)
                                        <?php
                                            $avAcm = null;
                                            foreach (@$experience_accomodations as $ea_lookup) {
                                                if ($ea_lookup->id == $avail->accommodation_id) { $avAcm = $ea_lookup; break; }
                                            }
                                        ?>
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($avail->start_date)->format('d M Y') }}</td>
                                            <td>{{ $avAcm ? $avAcm->name : '-' }}</td>
                                            <td>
                                                <span class="xd-badge {{ $avail->status == 'open' ? 'xd-badge-open' : ($avail->status == 'few_left' ? 'xd-badge-warn' : 'xd-badge-danger') }}">
                                                    {{ \App\ExperienceAccommodationAvailability::statusLabel($avail->status) }}
                                                </span>
                                            </td>
                                            <td>{{ in_array($avail->status, ['open', 'few_left']) ? $avail->remaining : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- Daily Routine --}}
                        <?php $routineItems = (@$experience_schedules && sizeof(@$experience_schedules) > 0) ? $experience_schedules->sortBy('schedule_start_time') : collect(); ?>
                        @if($routineItems->count() > 0)
                        <div class="xd-card" id="routine">
                            <span class="xd-tag">Daily Rhythm</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127749;</span> The Daily Routine</h2>
                            <div class="xd-routine-list">
                                @foreach($routineItems as $schedule)
                                <div class="xd-routine-item">
                                    <span class="xd-routine-time">
                                        @if($schedule->schedule_start_time){{ \Carbon\Carbon::parse($schedule->schedule_start_time)->format('h:i A') }}@endif
                                        @if($schedule->schedule_end_time) - {{ \Carbon\Carbon::parse($schedule->schedule_end_time)->format('h:i A') }}@endif
                                    </span>
                                    <div class="xd-routine-desc">
                                        @if($schedule->schedule_day)<h4>{{ $schedule->schedule_day }}</h4>@endif
                                        <p>{{ $schedule->activity_description }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @elseif(@$experience->schedule)
                        <div class="xd-card" id="routine">
                            <span class="xd-tag">Daily Rhythm</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127749;</span> Experience Schedule</h2>
                            <div>{!! @$experience->schedule !!}</div>
                        </div>
                        @endif

                        {{-- Experience Details --}}
                        @if(@$experience->experience_details)
                        <div class="xd-card">
                            <span class="xd-tag">More Detail</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128221;</span> Experience Details</h2>
                            <div>{!! @$experience->experience_details !!}</div>
                        </div>
                        @endif

                        {{-- About Center --}}
                        @if(@$center->about_center)
                        <div id="about" class="xd-card">
                            <span id="centre-overview" class="xd-tag">The Host</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127978;</span> About {{ @$center->name }}</h2>
                            <span class="view-website c-medium fs-14 fw-500 d-block mb-3">
                                <span class="c-brand icon-globe me-2"></span>
                                <a target="_blank" href="{{ url("center/".@$center->slug) }}">View Profile</a>
                            </span>
                            {!! @$center->about_center !!}

                            <div class="row mt-3">
                                @if(@$center->year_of_foundation)
                                <div class="col-md-6 mb-2"><strong>Founded:</strong> {{ @$center->year_of_foundation }}</div>
                                @endif
                                @if(@$center->founders)
                                <div class="col-md-6 mb-2"><strong>Founders:</strong> {{ @$center->founders }}</div>
                                @endif
                            </div>

                            @if(@$center->our_mission)
                            <div class="mt-3">
                                <h4 class="fs-16">Our Mission</h4>
                                {!! @$center->our_mission !!}
                            </div>
                            @endif

                            @if(@$center->our_philosophy)
                            <div class="mt-3">
                                <h4 class="fs-16">Our Philosophy</h4>
                                {!! @$center->our_philosophy !!}
                            </div>
                            @endif

                            @if(@$center->what_sets_us_apart)
                            <div class="mt-3">
                                <h4 class="fs-16">What Sets Us Apart</h4>
                                {!! @$center->what_sets_us_apart !!}
                            </div>
                            @endif

                            @if(@$center->center_highlights)
                            <div class="mt-3">
                                <h4 class="fs-16">Highlights</h4>
                                {!! @$center->center_highlights !!}
                            </div>
                            @endif

                            @if(@$center->center_features)
                            <div class="mt-3">
                                <h4 class="fs-16">Features</h4>
                                {!! @$center->center_features !!}
                            </div>
                            @endif

                            @if(@$center->awards)
                            <div class="mt-3">
                                <h4 class="fs-16">Awards</h4>
                                {!! @$center->awards !!}
                            </div>
                            @endif

                            @if(sizeof((array)@$center_locations) > 0)
                            <div class="mt-3">
                                <h4 class="fs-16">Nearby Locations</h4>
                                <ul class="bg-list-icon list-unstyled d-flex flex-wrap">
                                    @foreach(@$center_locations as $center_location)
                                    <li class="me-3 mb-2">{{ $center_location->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="row mt-3">
                                @if(@$center->email_address)
                                <div class="col-md-4 mb-2"><span class="c-brand icon-mail me-1"></span> {{ @$center->email_address }}</div>
                                @endif
                                @if(@$center->contact_number)
                                <div class="col-md-4 mb-2"><span class="c-brand icon-phone me-1"></span> {{ @$center->contact_number }}</div>
                                @endif
                                @if(@$center->whatsapp_number)
                                <div class="col-md-4 mb-2"><span class="c-brand icon-whatsapp me-1"></span> {{ @$center->whatsapp_number }}</div>
                                @endif
                                @if(@$center->website)
                                <div class="col-md-4 mb-2"><span class="c-brand icon-globe me-1"></span> <a target="_blank" href="{{ @$center->website }}">{{ @$center->website }}</a></div>
                                @endif
                                @if(@$center->facebook_url)
                                <div class="col-md-4 mb-2"><a target="_blank" href="{{ @$center->facebook_url }}"><span class="icon-facebook me-1"></span> Facebook</a></div>
                                @endif
                                @if(@$center->instagram_url)
                                <div class="col-md-4 mb-2"><a target="_blank" href="{{ @$center->instagram_url }}"><span class="icon-instagram me-1"></span> Instagram</a></div>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Amenities (standalone, decoupled from about_center) --}}
                        @if(sizeof((array)@$amenities) > 0)
                        <div class="xd-card">
                            <span class="xd-tag">Resort Comforts</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128142;</span> Amenities</h2>
                            <div class="xd-amenities-grid">
                                @foreach(@$amenities as $amenity)
                                <div class="xd-amenity-card">
                                    @if(@$amenity->image_url)
                                    <img class="lazy" width="22" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($amenity->image_url)),'?') }}" alt="{{ $amenity->name }}" />
                                    @endif
                                    {{ $amenity->name }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Certification --}}
                        @if($experience->experience_certification_id)
                        <div class="xd-card">
                            <span class="xd-tag">Recognised</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127942;</span> Certification</h2>
                            <?php $imagegallerie = \App\Certificates::where("id", $experience->experience_certification_id)->get(); ?>
                            @if(@$imagegallerie)
                            <div class="d-flex flex-wrap" style="gap:12px;">
                                @foreach(@$imagegallerie as $gallery)
                                @if(@$gallery && @$gallery->image_url)
                                <img class="lazy" width="150px" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{!! @$gallery->image_url !!}">
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Accomodation Overview (center-level gallery) --}}
                        @if((sizeof((array)@$accomodationimagegalleries)>0) OR (@$center->accomodation_banner_image_url) OR (@$center->accomodation_overview))
                        <div id="accomodation" class="xd-card">
                            <span class="xd-tag">The Space</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127960;</span> Accomodation</h2>
                            <div class="article-items">
                                <div class="left w-100">
                                    <div class="container-fluid p-0">
                                        <div class="slideshow-container">
                                            @if(@$center->accomodation_banner_image_url)
                                            <div class="mySlides fade">
                                                <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode(@$center->accomodation_banner_image_url)),'?') }}" />
                                            </div>
                                            @endif
                                            @if(sizeof((array)@$accomodationimagegalleries)>0)
                                                @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                @if(@$accomodationimagegallery->image_url)
                                                <div class="mySlides fade">
                                                    <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                </div>
                                                @endif
                                                @endforeach
                                                <a class="prev">&#10094;</a>
                                                <a class="next">&#10095;</a>
                                                <div class="thumnnails">
                                                    @if(@$center->accomodation_banner_image_url)
                                                    <span class="dot">
                                                        <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode(@$center->accomodation_banner_image_url)),'?') }}" />
                                                    </span>
                                                    @endif
                                                    @foreach(@$accomodationimagegalleries as $accomodationimagegallery)
                                                    @if(@$accomodationimagegallery->image_url)
                                                    <span class="dot">
                                                        <img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode(@$accomodationimagegallery->image_url)),'?') }}" />
                                                    </span>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                {!! @$center->accomodation_overview !!}
                            </div>
                        </div>
                        @endif

                        {{-- Food Overview --}}
                        @if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))
                        <div id="food-overview" class="xd-card">
                            <span class="xd-tag">Conscious Dining</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#129367;</span> Farm-to-Table Culinary Art</h2>
                            <?php
                            $foodImgs = array();
                            if (@$experience->food_banner_image_url) { $foodImgs[] = (object) array('image_url' => $experience->food_banner_image_url, 'image_title' => ''); }
                            if (sizeof(@$foodimagegalleries->toArray()) > 0) {
                                foreach (@$foodimagegalleries as $fg) { $foodImgs[] = $fg; }
                            }
                            $foodCount = sizeof($foodImgs);
                            ?>
                            @if($foodCount > 0 && $foodCount <= 2)
                            <div class="xd-culinary-grid">
                                @foreach($foodImgs as $fi)
                                @if(@$fi->image_url)
                                <img class="lazy xd-culinary-img" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($fi->image_url)),'?') }}" alt="{{ @$fi->image_title }}" />
                                @endif
                                @endforeach
                            </div>
                            @elseif($foodCount > 2)
                            <div class="article-items">
                                <div class="left w-100">
                                    <div class="container-fluid p-0">
                                        <div class="slideshow-container">
                                            @foreach($foodImgs as $fi)
                                            @if(@$fi->image_url)
                                            <div class="mySlides fade"><img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($fi->image_url)),'?') }}" /></div>
                                            @endif
                                            @endforeach
                                            <a class="prev">&#10094;</a>
                                            <a class="next">&#10095;</a>
                                            <div class="thumnnails">
                                                @foreach($foodImgs as $fi)
                                                @if(@$fi->image_url)
                                                <span class="dot"><img class="lazy" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($fi->image_url)),'?') }}" /></span>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(@$experience->food_overview)
                            <div class="mt-3">{!! @$experience->food_overview !!}</div>
                            @endif
                        </div>
                        @endif

                        {{-- Inclusions & Exclusions --}}
                        @if(@$experience->what_is_included || @$experience->what_is_not_included)
                        <div class="xd-card" id="what-is-included">
                            <span class="xd-tag">Full Clarity</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#9878;&#65039;</span> Inclusions &amp; Exclusions</h2>
                            <div class="xd-inc-exc-grid">
                                @if(@$experience->what_is_included)
                                <div class="xd-inc-card">
                                    <div class="xd-inc-head included">&#10003; What's Included</div>
                                    <div class="xd-inc-list bg-list-icon">{!! @$experience->what_is_included !!}</div>
                                </div>
                                @endif
                                @if(@$experience->what_is_not_included)
                                <div class="xd-inc-card" id="what-is-not-included">
                                    <div class="xd-inc-head excluded">&#10005; What's Excluded</div>
                                    <div class="xd-inc-list bg-list-icon">{!! @$experience->what_is_not_included !!}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Style --}}
                        @if(@$experience->styles_taught)
                        <div class="xd-card" id="style">
                            <span class="xd-tag">Practice</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#129496;</span> Style</h2>
                            <ul class="bg-list-icon">
                                <?php
                                foreach (explode(",", @$experience->styles_taught) as $styles_taught) {
                                ?>
                                    <li>{{ $styles_taught }}</li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        @endif

                        {{-- Languages --}}
                        @if(@$experience->language_spoken)
                        <div class="xd-card" id="languages">
                            <span class="xd-tag">Communication</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128172;</span> Languages</h2>
                            <ul class="bg-list-icon">
                                <?php
                                foreach (explode("||", @$experience->language_spoken) as $language) {
                                ?>
                                    <li>{{ @$language }}</li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        @endif

                        {{-- How to Reach --}}
                        @if(@$center->how_to_get_there || @$center->airport_name || @$center->pickup_drop_cost)
                        <div id="howtoreach" class="xd-card">
                            <span class="xd-tag">Getting There</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#9992;&#65039;</span> How to reach {{ @$center->name }}</h2>
                            <div>{!! @$center->how_to_get_there !!}</div>
                            <div class="row mt-3">
                                @if(@$center->airport_name)
                                <div class="col-md-6 mb-2"><strong>Nearest Airport:</strong> {{ @$center->airport_name }}</div>
                                @endif
                                @if(@$center->pickup_drop_cost)
                                <div class="col-md-6 mb-2"><strong>Pickup / Drop Cost:</strong> {{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$center->pickup_drop_cost, $site_currency) }}</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Things to do --}}
                        @if(@$center->things_to_do_around_the_center)
                        <div id="things-to-do" class="xd-card">
                            <span class="xd-tag">Explore</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128506;&#65039;</span> Things to Do Around {{ @$center->name }}</h2>
                            <div>{!! @$center->things_to_do_around_the_center !!}</div>
                        </div>
                        @endif

                        {{-- Booking Info --}}
                        @if(@$experience->booking_info)
                        <div class="xd-card" id="booking_info">
                            <span class="xd-tag">Good to Know</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#8505;&#65039;</span> Booking Info</h2>
                            <div>{!! @$experience->booking_info !!}</div>
                        </div>
                        @endif

                        {{-- Payment & Cancellation Terms --}}
                        @php
                            $depositPolicy = @$experience->deposit_policy ?: @$center_commission->deposit_policy;
                            $depositAmount = @$experience->deposit_amount ?: @$center_commission->deposit_amount;
                            $cancelCondition = @$experience->cancellation_policy_condition ?: @$center_commission->cancellation_policy_condition;
                            $cancelDays = @$experience->cancellation_policy_days ?: @$center_commission->cancellation_policy_days;
                            $restOfPayment = @$experience->rest_of_payment ?: @$center_commission->rest_of_payment;
                            $restOfPaymentDays = @$experience->rest_of_payment_days ?: @$center_commission->rest_of_payment_days;
                            $taxInfo = @$experience->tax ?: @$center_commission->tax;
                        @endphp
                        @if($depositPolicy || $cancelCondition || $restOfPayment || $taxInfo)
                        <div class="xd-card" id="payment-terms">
                            <span class="xd-tag">Fine Print</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128179;</span> Payment &amp; Cancellation Terms</h2>
                            <ul class="bg-list-icon">
                                @if($depositPolicy && $depositAmount)
                                <li>A deposit of {{ \App\Http\Helpers\CommonHelper::get_currency_rate($depositAmount, $site_currency) }} is required to confirm booking.</li>
                                @endif
                                @if($restOfPayment && $restOfPaymentDays)
                                <li>Balance payment is due {{ $restOfPaymentDays }} days before the retreat start date.</li>
                                @endif
                                @if($cancelCondition && $cancelDays)
                                <li>Cancellations must be made at least {{ $cancelDays }} days in advance as per the cancellation policy.</li>
                                @endif
                                @if($taxInfo)
                                <li>Applicable Tax: {{ $taxInfo }}</li>
                                @endif
                            </ul>
                        </div>
                        @endif

                        {{-- Cancellation Policy --}}
                        @if(@$experience->cancellation_policy)
                        <div class="xd-card" id="cancellation">
                            <span class="xd-tag">Please Note</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128220;</span> Cancellation Policy</h2>
                            <div>{!! @$experience->cancellation_policy !!}</div>
                        </div>
                        @endif

                        {{-- Reviews --}}
                        @if(@$center->bg_id)
                        <div class="xd-card">
                            <span class="xd-tag">Guest Experiences</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128172;</span> Reviews</h2>
                            <iframe src="https://balancegurus.com/embed/reviews?listing_id=<?php echo @$center->bg_id;?>" target="_blank" width="100%" height="600" style="border:none;"></iframe>
                        </div>
                        @endif

                    </div>
                </main>

                <!-- Desktop Sticky Booking Sidebar -->
                <aside class="xd-sidebar">
                    <div class="xd-booking-card" id="booking-card">
                        <div class="xd-booking-header">
                            <?php
                            $offerActive = false;
                            if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                                $nowD = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                                if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $nowD) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $nowD)) {
                                    $offerActive = true;
                                }
                            }
                            ?>
                            @if($offerActive)
                            <span class="xd-discount-tag">
                                &#128293; {{ @$experience->offer_discount_type == 'amt' ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$experience->offer_discount, $site_currency) : @$experience->offer_discount.'%' }} OFF &middot; Ends {{ \Carbon\Carbon::parse($experience->offer_end_date)->format('d M') }}
                            </span>
                            @endif
                            <h3 class="xd-booking-title">Reserve This Retreat</h3>
                        </div>

                        <form id="frmBookingDesktop" name="frmBookingDesktop" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking">
                            {{ csrf_field() }}
                            <input type="hidden" name="hdn_experience_id" class="qb-exp-id" value="{{ @$experience->id }}" />

                            <div class="xd-form-group">
                                <label class="xd-form-label">Duration</label>
                                <select name="durations" class="xd-select qb-durations">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="xd-form-group">
                                <label class="xd-form-label">Accommodation</label>
                                <select name="exp_accomodation_id" class="xd-select qb-accomodation">
                                    <option value="">Please Select Accommodation</option>
                                </select>
                            </div>

                            <div class="xd-form-group">
                                <label class="xd-form-label">Start Date</label>
                                <input type="text" class="xd-input qb-date bkdate" name="booking_date" value="" min="<?php echo date("Y-m-d");?>" onfocus="(this.type = 'date')" placeholder="Select a date" />
                            </div>

                            <div class="xd-calc-box">
                                <div class="xd-calc-row">
                                    <span>Price</span>
                                    <span class="qb-price">-</span>
                                </div>
                                <div class="xd-calc-row total">
                                    <span>Booking Amount</span>
                                    <span class="qb-booking-amount">-</span>
                                </div>
                            </div>

                            <button type="button" class="xd-btn-outline show-bg-modal" data-popup="checkAvailability">
                                Check Dates &amp; Availability
                            </button>

                            @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
                            <button type="submit" class="xd-btn-gradient qb-reserve-btn" disabled="disabled">
                                Book Now
                            </button>
                            @endif
                        </form>

                        <div id="razorpay-affordability-widget" class="mt-3" style="margin:auto; text-align:center;"></div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- Mobile persistent booking bar -->
    <div class="xd-mobile-bar">
        <div class="xd-mobile-bar-top">
            @if($offerActive)
            <span class="xd-mobile-discount-tag">&#128293; Limited-Time Offer Available</span>
            @else
            <span class="xd-mobile-discount-tag xd-mobile-discount-tag--plain">Flexible Dates Available</span>
            @endif
        </div>
        <div class="xd-mobile-bar-bottom">
            <button type="button" class="xd-btn-mobile-outline show-bg-modal" data-popup="checkAvailability">Check Dates</button>
            <button type="button" class="xd-btn-mobile-gradient" onclick="xdOpenDrawer()">Reserve Now</button>
        </div>
    </div>

    <!-- Mobile full booking drawer -->
    <div class="xd-mobile-drawer-overlay" id="xd-mobile-drawer-overlay">
        <div class="xd-mobile-drawer">
            <div class="xd-drawer-header">
                <h3 class="xd-booking-title" style="justify-content:flex-start;">Reserve This Retreat</h3>
                <button type="button" class="xd-modal-close" onclick="xdCloseDrawer()">&#10005;</button>
            </div>

            <form id="frmBookingMobile" name="frmBookingMobile" action="{{ url('/reservation') }}" method="POST" novalidate="novalidate" class="quick-booking">
                {{ csrf_field() }}
                <input type="hidden" name="hdn_experience_id" class="qb-exp-id" value="{{ @$experience->id }}" />

                <div class="xd-form-group">
                    <label class="xd-form-label">Duration</label>
                    <select name="durations" class="xd-select qb-durations">
                        <option value="">Select</option>
                    </select>
                </div>

                <div class="xd-form-group">
                    <label class="xd-form-label">Accommodation</label>
                    <select name="exp_accomodation_id" class="xd-select qb-accomodation">
                        <option value="">Please Select Accommodation</option>
                    </select>
                </div>

                <div class="xd-form-group">
                    <label class="xd-form-label">Start Date</label>
                    <input type="text" class="xd-input qb-date bkdate" name="booking_date" value="" min="<?php echo date("Y-m-d");?>" onfocus="(this.type = 'date')" placeholder="Select a date" />
                </div>

                <div class="xd-calc-box">
                    <div class="xd-calc-row">
                        <span>Price</span>
                        <span class="qb-price">-</span>
                    </div>
                    <div class="xd-calc-row total">
                        <span>Booking Amount</span>
                        <span class="qb-booking-amount">-</span>
                    </div>
                </div>

                @if(@$experience->is_draft == 0 && @$experience->is_bookable == 1)
                <button type="submit" class="xd-btn-gradient qb-reserve-btn" disabled="disabled">
                    Book Now
                </button>
                @endif
            </form>
        </div>
    </div>

</div>

<section id="bg-gallery-popup" class="bg-listing-gallery-cont gallery-popup hidden">
    <div class="container-fluid pos-sticky-top bg-white">
        <div class="row">
            <div class="col-12">
                <div class="head ps-0 pe-3 ps-md-4 pe-md-4">
                    <div id="bg-gallery-back" class="gallery-back-btn">
                        <div class="bg-btn bg-btn-secondary btn-medium btn-round">
                            <span class="fs-18 icon-arrow-left"></span>
                        </div>
                    </div>
                    <div class="right ms-auto">
                        <span class="icon-share c-brand me-2"></span>
                        <div class="bg-menu-list">
                            <span> Share </span>
                            <ul class="bg-box horiz">
                                <li>
                                    <a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a>
                                </li>
                            </ul>
                        </div>
                        <span class="icon-love c-brand ms-2"></span>
                        <a href="">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-0 pt-md-3 pb-3">
        <div class="row gallery">
            <div class="col-12 mb-3">
                <div class="bg-gallery-popup">
                    @foreach(@$imagegalleries as $gallery)
                        @if($gallery->image_url)
                        <div class="bg-gallery-items">
                            <img class="gallery__Image" src="{{ strtok(Storage::disk('s3')->url(rawurlencode($gallery->image_url)),'?') }}" alt="{{ $gallery->image_title }}" data-description="" data-large="{{ strtok(Storage::disk('s3')->url(rawurlencode($gallery->image_url)),'?') }}" />
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer')
<script src="{{asset('public/basicfront/js/jquery.validate.min.js')}}" defer></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function xdInitQuickBooking($form) {
            var $durations = $form.find('.qb-durations');
            var $accom = $form.find('.qb-accomodation');
            var $date = $form.find('.qb-date');
            var $reserveBtn = $form.find('.qb-reserve-btn');
            var $price = $form.find('.qb-price');
            var $bookingAmount = $form.find('.qb-booking-amount');
            var expId = $form.find('.qb-exp-id').val();

            function calculatePrice() {
                if ($accom.val() == '' || $date.val() == '' || expId == '') {
                    $reserveBtn.prop('disabled', true);
                    return;
                }
                $.ajax({
                    url: APP_URL + '/get_booking_price',
                    type: "post",
                    data: {'exp_id': expId, 'booking_date': $date.val(), 'acc_id': $accom.val(), 'days': $durations.val()}
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data && data.accomodation_price > 0) {
                        $reserveBtn.prop('disabled', false);
                        $bookingAmount.html(data.booking_amount_html_price);
                        $price.html(data.accomodation_html_price);
                    } else {
                        $price.add($bookingAmount).html('-');
                        $reserveBtn.prop('disabled', true);
                    }
                });
            }

            function loadFilters() {
                $.ajax({
                    url: APP_URL + '/get_ajax_filter_values',
                    type: "post",
                    data: {'exp_id': expId}
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data) {
                        if (data.experience_durations) {
                            $durations.append(data.experience_durations);
                        }
                        if (data.experience_accomodations_options) {
                            $accom.append(data.experience_accomodations_options);
                        }
                        if ($durations.find('option').length === 2) {
                            $durations.val($durations.find('option:eq(1)').val());
                        }
                    }
                });
            }

            $date.on('change', calculatePrice);
            $accom.on('change', calculatePrice);
            $durations.on('change', calculatePrice);

            loadFilters();
        }

        $('form.quick-booking').each(function() {
            xdInitQuickBooking($(this));
        });
    });

    function xdSwapRoomImage(mainId, thumbEl) {
        var full = thumbEl.getAttribute('data-src') || thumbEl.src;
        var mainImg = document.getElementById(mainId);
        if (mainImg) {
            mainImg.src = full;
            mainImg.setAttribute('data-src', full);
        }
    }

    function xdOpenDrawer() {
        document.getElementById('xd-mobile-drawer-overlay').classList.add('active');
    }

    function xdCloseDrawer() {
        document.getElementById('xd-mobile-drawer-overlay').classList.remove('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('xd-mobile-drawer-overlay');
        overlay.addEventListener('click', function(e) {
            if (e.target === this) xdCloseDrawer();
        });
    });

    <?php
    if (!empty($razorPayAmount)) {
        if (\App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - @$discount, $site_currency, false) > 0) {?>

            const key = "rzp_live_wp4Xjvh9X9GOYe"; //Replace it with your Test Key ID generated from the Dashboard
            const amount = <?php echo $razorPayAmount*100; ?> //in paise

            window.onload = function() {
            const widgetConfig = {
                "key": key,
                "amount": amount,
            };
            const rzpAffordabilitySuite = new RazorpayAffordabilitySuite(widgetConfig);
            rzpAffordabilitySuite.render();
            }
        <?php }
    }?>
</script>
@endsection
