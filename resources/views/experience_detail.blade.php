{{--
    Final top-to-bottom section order (Task 10 — authoritative, supersedes design.md's earlier proposed order).
    Scope note: only the placement of About Center / Amenities / How to Reach / Things to Do / Booking Info
    (plus Certification and the center-level Accomodation Overview gallery, folded in for the same reason)
    was reconciled against the reference image in this task. All other sections' relative order below reflects
    the page's existing structure as of this task and was not reshuffled — reordering those was outside Task 10's
    scope (see the code comments at the old and new locations of the moved cluster for the specific reasoning).

     1. Header/title                        — partials/experience-header.blade.php
     2. Gallery                              — partials/experience-gallery.blade.php
     3. Overview                             — inline
     4. Highlights                           — inline
     5. Experience Summary                   — inline
     6. Choose Your Room + Price Options      — partials/experience-pricing-packages.blade.php
     7. Upcoming Availability                — partials/experience-availability.blade.php
     8. Daily Routine                        — inline
     9. Experience Details (experience_details field) — inline
    10. Food & Drink                         — partials/experience-food.blade.php
    11. Inclusions & Exclusions              — inline
    12. Style                                — inline
    13. Languages                            — inline
    14. Payment & Cancellation Terms         — inline
    15. Cancellation Policy                  — inline
    16. About the Center (consolidated, Task 10 placement):
        About Center -> Amenities -> Certification -> Accomodation Overview (gallery)
        -> How to Reach -> Things to Do -> Booking Info               — inline
    17. Need Help? (new section, Task 12)    — partials/experience-need-help.blade.php
    18. Reviews (iframe restyle only, per Requirement 16)             — inline
    19. Sidebar: booking card                — desktop, sticky, parallel to main column throughout
    20. Mobile bottom bar + drawer            — mobile, fixed, throughout
--}}
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        /* Visual-parity pass, corrected against the actual reference image at full resolution:
           reference typography is compact (Inter, ~14-15px body), not loosely spaced. */
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        font-size: 15px;
        line-height: 1.55;
        color: var(--xd-text-body);
    }
    .xd-page, .xd-page * { font-family: inherit; }
    .xd-page p { line-height: 1.6; margin-bottom: 1em; }

    /* Content width — matches the reference layout's own container instead of the site's narrower Bootstrap .container */
    .xd-container { max-width: 1200px; width: 100%; margin: 0 auto; padding: 0 24px; }
    @media (max-width: 768px) {
        .xd-container { padding: 0 16px; }
    }

    /* Gallery grid cosmetic upgrade (structure/JS unchanged), enlarged to match reference proportions */
    .xd-gallery.bg-listing-gallery { border-radius: var(--xd-radius-xl); overflow: hidden; box-shadow: var(--xd-shadow-card); min-height: 420px; }
    .xd-gallery.bg-listing-gallery .bg-listing-gallery-items img { height: 100%; }

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
    .xd-hero-title { font-size: 28px; color: #fff; line-height: 1.2; font-weight: 700; margin-bottom: 8px; }
    .xd-hero-meta { display: flex; flex-wrap: wrap; gap: 18px; align-items: center; color: rgba(255,255,255,0.92); font-size: 15px; }
    .xd-hero-meta .icon-location { margin-right: 4px; }
    .xd-hero-price { display: inline-flex; align-items: center; gap: 8px; }
    .xd-hero-price del { color: rgba(255,255,255,0.65); font-weight: normal; }
    .xd-hero-price strong { color: #fff; font-size: 18px; }
    .xd-hero-banner .tier-badge { position: relative; }

    /* Layout grid */
    .xd-layout-grid { display: grid; grid-template-columns: 1fr 380px; gap: 40px; align-items: start; margin-top: 8px; }
    .xd-main-col { min-width: 0; }

    /* Cards — corrected against the reference: sections are NOT heavy bordered/shadowed boxes there.
       They flow directly on the page, separated by a thin bottom divider and generous spacing. */
    .xd-card {
        background: transparent;
        border: none;
        border-bottom: 1px solid var(--xd-border);
        border-radius: 0;
        padding: 32px 0;
        margin-bottom: 0;
        box-shadow: none;
        scroll-margin-top: 150px; /* clears the sticky 80px site header + the sticky xd-nav-tabs bar */
    }
    .xd-card:last-child { border-bottom: none; }
    /* The reference's section headings are plain bold text — no icon badge, no eyebrow pill above them.
       Hidden via CSS (not deleted from markup) so the underlying data-driven structure is untouched and
       this is trivially reversible if a future design wants them back. */
    .xd-tag { display: none; }
    .xd-title-icon { display: none; }
    .xd-title { font-size: 20px; color: var(--xd-text-main) !important; font-weight: 700; margin-bottom: 16px; }
    .xd-card table { width: 100%; border-collapse: collapse; }
    .xd-card table th, .xd-card table td { padding: 10px 12px; border-bottom: 1px solid var(--xd-border); text-align: left; font-size: 14px; }
    .xd-card table th { color: var(--xd-text-muted); font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }

    /* Highlights grid */
    .xd-audience-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .xd-audience-item {
        display: flex; align-items: flex-start; gap: 16px; background: var(--xd-subtle); padding: 20px;
        border-radius: var(--xd-radius-md); border: 1px solid var(--xd-border);
    }
    .xd-audience-icon { color: var(--xd-brand); font-size: 16px; line-height: 1.5; }

    /* Room cards */
    .xd-room-list { display: flex; flex-direction: column; gap: 28px; }
    .xd-room-card {
        display: grid; grid-template-columns: 280px 1fr; background: var(--xd-subtle); border-radius: var(--xd-radius-lg);
        overflow: hidden; border: 1px solid var(--xd-border);
    }
    .xd-room-gallery { display: flex; flex-direction: column; padding: 12px; gap: 8px; background: var(--xd-surface); }
    .xd-room-main-img { width: 100%; height: 190px; object-fit: cover; border-radius: var(--xd-radius-md); background: var(--xd-subtle); }
    .xd-room-thumbs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .xd-room-thumb { width: 100%; height: 58px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent; opacity: 0.8; }
    .xd-room-thumb:hover { opacity: 1; border-color: var(--xd-brand); }
    .xd-room-info { padding: 28px; }
    .xd-room-badge { display: inline-block; background: var(--xd-brand); color: #fff; padding: 6px 14px; border-radius: 10px; font-size: 13px; font-weight: bold; }
    .xd-room-loc { color: var(--xd-text-muted); font-size: 14px; margin-top: 10px; }
    .xd-room-loc .icon-location { color: var(--xd-brand); margin-right: 4px; }
    .xd-room-tags { display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0; }
    .xd-room-tag {
        background: var(--xd-surface); color: var(--xd-text-body); border: 1px solid var(--xd-border);
        padding: 4px 12px; border-radius: var(--xd-radius-pill); font-size: 13px;
    }
    .xd-badge { padding: 4px 12px; border-radius: var(--xd-radius-pill); font-size: 12px; font-weight: 600; }
    .xd-badge-open { background: #dcfce7; color: #16a34a; }
    .xd-badge-warn { background: #fef9c3; color: #a16207; }
    .xd-badge-danger { background: #fee2e2; color: #dc2626; }
    .xd-room-title { font-size: 19px; color: var(--xd-text-main); font-weight: 700; margin: 8px 0; }
    .xd-room-title a { color: inherit; text-decoration: none; }
    .xd-room-avail-note { font-size: 14px; color: var(--xd-text-muted); font-weight: normal; margin-bottom: 10px; }
    .xd-room-desc-list { margin: 10px 0; }
    .xd-room-about { font-size: 14px; color: var(--xd-text-body); }
    .xd-occ-table { margin: 14px 0; overflow-x: auto; }
    .xd-room-bottom { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 18px; flex-wrap: wrap; }
    .xd-room-price-block small { color: var(--xd-text-muted); font-size: 13px; }
    .xd-room-price { font-size: 19px; font-weight: bold; color: var(--xd-brand); display: flex; align-items: center; gap: 8px; }
    .xd-room-price del { color: var(--xd-text-muted); font-weight: normal; font-size: 14px; }
    .xd-room-cta { display: flex; gap: 10px; }

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
    .xd-amenities-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .xd-amenity-card {
        display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--xd-subtle);
        border: 1px solid var(--xd-border); border-radius: var(--xd-radius-md); color: var(--xd-text-main); font-weight: 600; font-size: 15px;
    }

    /* Culinary */
    .xd-culinary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
    .xd-culinary-img { width: 100%; height: 240px; object-fit: cover; border-radius: var(--xd-radius-lg); border: 1px solid var(--xd-border); }

    /* Inclusions/Exclusions */
    .xd-inc-exc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .xd-inc-card { padding: 22px; border-radius: var(--xd-radius-lg); border: 1px solid var(--xd-border); background: var(--xd-subtle); }
    .xd-inc-head { font-size: 16px; font-weight: bold; margin-bottom: 12px; }
    .xd-inc-head.included { color: #16a34a; }
    .xd-inc-head.excluded { color: #dc2626; }
    .xd-inc-list ul { margin: 0; padding-left: 18px; }
    .xd-inc-list p { margin: 0 0 6px 0; }

    /* Sidebar / booking card */
    .xd-sidebar { position: sticky; top: 90px; align-self: start; }
    .xd-booking-card {
        background: var(--xd-surface); border: 1px solid var(--xd-border); border-radius: var(--xd-radius-xl);
        padding: 20px; box-shadow: var(--xd-shadow-float);
    }
    .xd-booking-header { text-align: center; margin-bottom: 14px; }
    .xd-discount-tag {
        display: inline-flex; align-items: center; gap: 4px; background: rgba(220,38,38,0.08); color: #dc2626;
        border: 1px solid rgba(220,38,38,0.2); font-size: 11px; font-weight: bold; text-transform: uppercase;
        letter-spacing: 0.5px; padding: 4px 12px; border-radius: var(--xd-radius-pill); margin-bottom: 10px;
    }
    .xd-booking-title { font-size: 18px; color: var(--xd-text-main); margin-bottom: 4px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .xd-form-group { margin-bottom: 16px; }
    .xd-form-label { display: block; font-size: 12px; font-weight: bold; color: var(--xd-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .xd-select, .xd-input {
        width: 100%; padding: 11px 14px; background: var(--xd-subtle); border: 1px solid var(--xd-border);
        border-radius: var(--xd-radius-md); font-size: 15px; color: var(--xd-text-main);
    }
    .xd-select:focus, .xd-input:focus { outline: none; border-color: var(--xd-brand); }

    /* Duration pills */
    .xd-pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .xd-pill-btn {
        flex: 1; min-width: 64px; padding: 9px 8px; background: var(--xd-subtle); border: 1px solid var(--xd-border);
        border-radius: var(--xd-radius-md); font-size: 13px; color: var(--xd-text-body); cursor: pointer;
    }
    .xd-pill-btn:hover { border-color: var(--xd-brand); color: var(--xd-brand); }
    .xd-pill-btn.active {
        background: linear-gradient(135deg, var(--xd-brand) 0%, var(--xd-brand-dark) 100%); color: #fff; border-color: transparent;
        font-weight: bold; box-shadow: 0 2px 8px var(--xd-brand-glow);
    }

    /* Room picker list */
    .xd-room-picker-list { display: flex; flex-direction: column; gap: 8px; max-height: 300px; overflow-y: auto; }
    .xd-room-picker-item {
        display: flex; align-items: center; padding: 9px 12px; background: var(--xd-subtle); border: 1px solid var(--xd-border);
        border-radius: var(--xd-radius-md); cursor: pointer;
    }
    .xd-room-picker-item:hover { border-color: var(--xd-brand); }
    .xd-room-picker-item.active { border-color: var(--xd-brand); background: var(--xd-brand-light); }
    .xd-room-picker-thumb { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; margin-right: 12px; flex-shrink: 0; }
    .xd-room-picker-details { display: flex; flex-direction: column; min-width: 0; }
    .xd-room-picker-name { font-size: 13.5px; color: var(--xd-text-main); font-weight: bold; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .xd-room-picker-price { font-size: 13px; color: var(--xd-brand); font-weight: bold; }
    .xd-room-picker-price del { color: var(--xd-text-muted); font-weight: normal; margin-right: 4px; font-size: 12px; }

    .xd-calc-box { background: var(--xd-subtle); border-radius: var(--xd-radius-md); padding: 14px 16px; margin: 16px 0; border: 1px solid var(--xd-border); }
    .xd-calc-row { display: flex; justify-content: space-between; margin-bottom: 6px; color: var(--xd-text-body); font-size: 14px; }
    .xd-calc-row.total { border-top: 1px solid var(--xd-border); padding-top: 8px; margin-bottom: 0; font-size: 16px; color: var(--xd-text-main); font-weight: bold; }
    .xd-btn-gradient {
        width: 100%; background: linear-gradient(135deg, var(--xd-brand) 0%, var(--xd-brand-dark) 100%); color: #fff; border: none;
        padding: 14px; border-radius: var(--xd-radius-pill); font-size: 15px; font-weight: bold; cursor: pointer;
        box-shadow: var(--xd-shadow-colored); text-align: center; text-decoration: none; display: inline-block;
    }
    .xd-btn-gradient:disabled { opacity: 0.55; cursor: not-allowed; }
    .xd-btn-outline {
        width: 100%; background: transparent; color: var(--xd-text-main); border: 1px solid var(--xd-border);
        padding: 11px; border-radius: var(--xd-radius-pill); font-size: 14px; font-weight: bold; cursor: pointer; margin-bottom: 10px;
    }
    .xd-btn-outline:hover { border-color: var(--xd-brand); color: var(--xd-brand); }
    .xd-btn-sm { width: auto; padding: 8px 18px; font-size: 13px; }

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

    /* ===== New class families (Task 2 scaffolding — additive only, no existing rule above is modified) ===== */

    /* In-page tab navigation (reference: Overview/Accommodation/Food/Pricing/Availability/Terms/Center/Reviews).
       Sticky just below the site's own sticky 80px header so it stays visible while scrolling. */
    .xd-nav-tabs-wrap { position: sticky; top: 80px; z-index: 40; background: var(--xd-surface); margin-top: 20px; }
    .xd-nav-tabs { display: flex; gap: 4px; overflow-x: auto; border-bottom: 1px solid var(--xd-border); }
    .xd-nav-tab {
        flex-shrink: 0; padding: 14px 18px; font-size: 14px; font-weight: 600; color: var(--xd-text-muted);
        text-decoration: none; border-bottom: 2px solid transparent; white-space: nowrap;
    }
    .xd-nav-tab:hover { color: var(--xd-text-main); }
    .xd-nav-tab.active { color: var(--xd-brand); border-bottom-color: var(--xd-brand); }

    /* Header (rebuilt, visual-parity pass): plain text on the page background, not a photo-overlay hero.
       Order matches the reference: title -> meta -> badges. */
    .xd-header-top-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding-top: 20px; }
    .xd-header-title { font-size: 28px; color: var(--xd-text-main); font-weight: 700; line-height: 1.25; margin: 0; }
    .xd-header-actions { display: flex; align-items: center; gap: 16px; flex-shrink: 0; padding-top: 4px; }
    .xd-header-action-btn {
        display: inline-flex; align-items: center; gap: 6px; color: var(--xd-text-body); font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; white-space: nowrap;
    }
    .xd-header-action-btn:hover { color: var(--xd-brand); }
    .xd-header-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 16px; color: var(--xd-text-body); font-size: 14px; margin: 10px 0 14px; }
    .xd-header-meta .icon-location { color: var(--xd-brand); margin-right: 2px; }
    .xd-header-price del { color: var(--xd-text-muted); font-weight: normal; margin-right: 4px; }
    .xd-header-price strong { color: var(--xd-text-main); }
    .xd-header-rating { display: inline-flex; align-items: center; gap: 4px; font-weight: 600; color: var(--xd-text-main); font-size: 13px; }
    .xd-plain-tag {
        display: inline-block; background: var(--xd-brand-light); color: var(--xd-brand);
        padding: 4px 12px; border-radius: var(--xd-radius-pill); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .xd-header-badges { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 20px; }
    .xd-header-badge {
        display: inline-flex; align-items: center; gap: 4px; background: var(--xd-subtle); color: var(--xd-text-body);
        border: 1px solid var(--xd-border); padding: 4px 10px; border-radius: var(--xd-radius-pill); font-size: 11px; font-weight: 600;
    }
    /* .tier-badge (from partials/commission-tier-badge.blade.php) only has legacy CSS for photo-overlay
       (.img_list/.thumb, position:absolute) or h1+sibling (position:static) contexts — neither matches this
       header's markup structure, so it's scoped and styled explicitly here rather than relying on those. */
    .xd-header-badges .tier-badge {
        position: static; display: inline-block; background: #2F6F57; color: #fff;
        font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: var(--xd-radius-pill);
    }

    /* Pricing/package section grouping wrapper (stacked cards, no tabs — see design.md) */
    .xd-pkg-group { display: flex; flex-direction: column; gap: 24px; }
    .xd-pkg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Price-breakdown note line, extends existing .xd-calc-box/.xd-calc-row rather than duplicating them */
    .xd-breakdown-note { font-size: 12px; color: var(--xd-text-muted); margin-top: 6px; line-height: 1.4; }

    /* Availability/calendar section */
    .xd-avail-weekdays {
        display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 8px;
        font-size: 12px; font-weight: 700; color: var(--xd-text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;
    }
    .xd-avail-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
    .xd-avail-day {
        display: flex; align-items: center; justify-content: center; aspect-ratio: 1; border-radius: var(--xd-radius-md);
        font-size: 14px; color: var(--xd-text-body); background: var(--xd-subtle); border: 1px solid var(--xd-border);
    }
    .xd-avail-day--blank { background: transparent; border-color: transparent; }
    .xd-avail-day.is-open { background: #dcfce7; color: #16a34a; border-color: transparent; font-weight: 700; }
    .xd-avail-day.is-few { background: #fef9c3; color: #a16207; border-color: transparent; font-weight: 700; }
    .xd-avail-day.is-full { background: #fee2e2; color: #dc2626; border-color: transparent; font-weight: 700; }
    .xd-avail-legend { display: flex; flex-wrap: wrap; gap: 18px; margin-top: 18px; font-size: 13px; color: var(--xd-text-muted); }
    .xd-avail-legend-item { display: inline-flex; align-items: center; gap: 6px; }
    .xd-avail-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .xd-avail-other-heading { font-size: 15px; font-weight: 700; color: var(--xd-text-main); margin: 24px 0 12px; }
    .xd-avail-list { display: flex; flex-direction: column; gap: 8px; }
    /* Sidebar quick-date pills (reuses .xd-pill-btn interaction pattern for the sidebar's upcoming-start-date picker) */
    .xd-avail-pill-group { display: flex; flex-wrap: wrap; gap: 6px; }
    @media (max-width: 480px) {
        .xd-avail-weekdays, .xd-avail-grid { gap: 4px; }
        .xd-avail-day { font-size: 12px; }
    }

    /* Need Help? / support section */
    .xd-help-card { display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: var(--xd-subtle); border: 1px solid var(--xd-border); border-radius: var(--xd-radius-lg); }
    .xd-help-contact-row { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
    .xd-help-contact-item {
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--xd-surface);
        border: 1px solid var(--xd-border); border-radius: var(--xd-radius-pill); font-size: 13px; color: var(--xd-text-main); text-decoration: none;
    }

    /* Reviews container restyle (iframe src/embed mechanism untouched — wrapper styling only) */
    .xd-reviews-wrap { border-radius: var(--xd-radius-xl); overflow: hidden; border: 1px solid var(--xd-border); }

    /* About Center subsection headings (visual-parity pass) */
    .xd-about-subhead { font-size: 16px; font-weight: 700; color: var(--xd-text-main); margin-bottom: 10px; }

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
        .xd-card { padding: 24px 0; }
        .xd-hero-title { font-size: 24px; }
    }
</style>
<script defer src="https://cdn.razorpay.com/widgets/affordability/affordability.js"></script>
@endsection
@section('content')
<div class="xd-page">

    <!-- Hero / Header (display markup extracted to partials/experience-header.blade.php — Task 3).
         $discount/$pay/$razorPayAmount stay computed here, not in the partial, because $razorPayAmount is
         read again later in the footer Razorpay-widget script and Blade's include mechanism does not leak
         child-set vars back out. -->
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
    @endif
    @include('partials.experience-header')

    <!-- Gallery (extracted to partials/experience-gallery.blade.php — Task 4) -->
    @include('partials.experience-gallery')

    <!-- In-page tab navigation (new, visual-parity pass — reference shows Overview/Accommodation/Food/Pricing/
         Availability/Terms/Center/Reviews tabs under the gallery). Pure anchor links + scroll-spy; reuses
         existing section ids, no new backend/data. Each tab is omitted if its target section has no data,
         so the nav never links to an empty/missing section. -->
    <div class="xd-container">
        <div class="xd-nav-tabs-wrap">
            <nav class="xd-nav-tabs" id="xd-page-nav">
                <a href="#package" class="xd-nav-tab active">Overview</a>
                @if(sizeof(@$experience_accomodations) > 0)<a href="#accomodation-rooms" class="xd-nav-tab">Accommodation</a>@endif
                @if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))<a href="#food-overview" class="xd-nav-tab">Food</a>@endif
                @if(@$experience_durations && sizeof(@$experience_durations) > 0)<a href="#price-options" class="xd-nav-tab">Pricing</a>@endif
                @if(@$experience_upcoming_availability && sizeof(@$experience_upcoming_availability) > 0)<a href="#availability" class="xd-nav-tab">Availability</a>@endif
                <a href="#payment-terms" class="xd-nav-tab">Terms</a>
                @if(@$center->about_center)<a href="#about" class="xd-nav-tab">Center</a>@endif
                @if(@$center->bg_id)<a href="#reviews" class="xd-nav-tab">Reviews</a>@endif
            </nav>
        </div>
    </div>

    <section class="pt-5 mb-5">
        <div class="xd-container">
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
                        <?php
                        // Precomputed once so the room cards below and the sidebar/mobile room-picker
                        // (which needs the same price + thumbnail per room) never fall out of sync.
                        $roomPricing = array();
                        foreach (@$experience_accomodations as $racm) {
                            $rDiscount = 0;
                            $rPay = @$racm->room_price;
                            if ((!empty(@$experience->eirly_bird_before_days)) && (!empty(@$experience->eirly_bird_discount)) && (@$experience->eirly_bird_discount > 0)) {
                                if (@$experience->eirly_bird_discount_type == "amt") {
                                    $rDiscount += @$experience->eirly_bird_discount;
                                } else {
                                    $rDiscount = (@$rPay * @$experience->eirly_bird_discount) / 100;
                                }
                            }
                            if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                                $rNow = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                                if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $rNow) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $rNow)) {
                                    if (@$experience->offer_discount_type == "amt") {
                                        $rDiscount += @$experience->offer_discount;
                                    } else {
                                        $rDiscount += (@$rPay * @$experience->offer_discount) / 100;
                                    }
                                }
                            }
                            $rHasFlat = !empty($rPay) && $rPay > 0;
                            $rFallback = null;
                            if (!$rHasFlat) {
                                $occCandidates = array();
                                if (!empty(@$racm->single_occupancy_price)) { $occCandidates[] = @$racm->single_occupancy_price; }
                                if (!empty(@$racm->double_occupancy_price)) { $occCandidates[] = @$racm->double_occupancy_price; }
                                $durP = @$experience_accommodation_duration_prices[$racm->id] ?? collect();
                                foreach ($durP as $dp) {
                                    if (!empty($dp->single_price)) { $occCandidates[] = $dp->single_price; }
                                    if (!empty($dp->double_price)) { $occCandidates[] = $dp->double_price; }
                                }
                                if (sizeof($occCandidates) > 0) {
                                    $rFallback = min($occCandidates);
                                }
                            }
                            $rThumb = null;
                            if (@$accomodationimagegalleries) {
                                foreach (@$accomodationimagegalleries as $rimg) {
                                    if ($rimg->accomodation_id == $racm->id && $rimg->image_url) {
                                        $rThumb = $rimg->image_url;
                                        break;
                                    }
                                }
                            }
                            $roomPricing[$racm->id] = array(
                                'hasFlat' => $rHasFlat,
                                'pay' => $rPay,
                                'discount' => $rDiscount,
                                'fallback' => $rFallback,
                                'currency' => @$racm->currency,
                                'thumb' => $rThumb,
                            );
                        }
                        ?>
                        {{-- Pricing/Package sections (Choose Your Room + Price Options) extracted to
                             partials/experience-pricing-packages.blade.php — Task 7. $roomPricing computed
                             above stays in this parent scope because partials/experience-booking-fields.blade.php
                             (shared booking form, further down this file) also reads it. --}}
                        @include('partials.experience-pricing-packages')

                        {{-- Upcoming Availability (extracted to partials/experience-availability.blade.php — Task 9) --}}
                        @include('partials.experience-availability')

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

                        {{-- Placement: About Center, Amenities, Certification, and Accomodation Overview moved
                             (Task 10) to a consolidated "About the Center" cluster after Payment & Cancellation
                             Terms / Cancellation Policy, before Reviews — matches the reference image's single
                             "About [Center]" section (founder story + highlights + amenities + nearby + contact)
                             in that position. See the consolidated block below Cancellation Policy. --}}

                        {{-- Food & Drink (extracted to partials/experience-food.blade.php — Task 8) --}}
                        @include('partials.experience-food')

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

                        {{-- Placement: How to Reach, Things to Do, and Booking Info moved (Task 10) into the
                             same consolidated "About the Center" cluster after Payment & Cancellation Terms /
                             Cancellation Policy — How to Reach maps to the reference's "Nearby"/logistics content
                             inside its "About [Center]" section; Things to Do and Booking Info have no explicit
                             slot in the reference, so per the no-removal rule they are preserved in the same
                             cluster rather than dropped. --}}

                        {{-- Payment & Cancellation Terms + Cancellation Policy (extracted, merged — partials/experience-payment-terms.blade.php — Task 11) --}}
                        @include('partials.experience-payment-terms')

                        {{-- ===== Consolidated "About the Center" cluster (Task 10) =====
                             Placement decision: the reference image shows ONE "About [Center]" section
                             (founder story + highlights + amenities + nearby/logistics + contact) positioned
                             after Payment & Cancellation Terms, before Reviews. That single reference section
                             absorbs what this app models as five separate fields: About Center, Amenities,
                             How to Reach, Things to Do, and Booking Info — plus Certification and the
                             center-level Accomodation Overview gallery, which sat alongside them before this
                             move and are kept adjacent rather than orphaned. None are removed; all are
                             preserved here per the no-removal rule. --}}

                        {{-- Placement: matches reference's "About [Center]" founder-story block --}}
                        @if(@$center->about_center)
                        <div id="about" class="xd-card">
                            <span id="centre-overview" class="xd-tag">The Host</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#127978;</span> About {{ @$center->name }}</h2>
                            <span class="view-website c-medium fs-14 fw-500 d-block mb-3">
                                <span class="c-brand icon-globe me-2"></span>
                                <a target="_blank" href="{{ url("center/".@$center->slug) }}">View Profile</a>
                            </span>
                            {!! @$center->about_center !!}

                            @if(@$center->year_of_foundation || @$center->founders)
                            <div class="xd-inc-exc-grid" style="margin-top:20px;">
                                @if(@$center->year_of_foundation)
                                <div class="xd-inc-card"><div class="xd-inc-head" style="color:var(--xd-text-main);">Founded</div>{{ @$center->year_of_foundation }}</div>
                                @endif
                                @if(@$center->founders)
                                <div class="xd-inc-card"><div class="xd-inc-head" style="color:var(--xd-text-main);">Founders</div>{{ @$center->founders }}</div>
                                @endif
                            </div>
                            @endif

                            @if(@$center->our_mission)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Our Mission</h4>
                                {!! @$center->our_mission !!}
                            </div>
                            @endif

                            @if(@$center->our_philosophy)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Our Philosophy</h4>
                                {!! @$center->our_philosophy !!}
                            </div>
                            @endif

                            @if(@$center->what_sets_us_apart)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">What Sets Us Apart</h4>
                                {!! @$center->what_sets_us_apart !!}
                            </div>
                            @endif

                            @if(@$center->center_highlights)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Highlights</h4>
                                {!! @$center->center_highlights !!}
                            </div>
                            @endif

                            @if(@$center->center_features)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Features</h4>
                                {!! @$center->center_features !!}
                            </div>
                            @endif

                            @if(@$center->awards)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Awards</h4>
                                {!! @$center->awards !!}
                            </div>
                            @endif

                            @if(sizeof((array)@$center_locations) > 0)
                            <div style="margin-top:24px;">
                                <h4 class="xd-about-subhead">Nearby Locations</h4>
                                <div class="xd-room-tags">
                                    @foreach(@$center_locations as $center_location)
                                    <span class="xd-room-tag">{{ $center_location->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="xd-help-contact-row" style="margin-top:24px;">
                                @if(@$center->email_address)
                                <span class="xd-help-contact-item"><span class="c-brand icon-mail me-1"></span> {{ @$center->email_address }}</span>
                                @endif
                                @if(@$center->contact_number)
                                <span class="xd-help-contact-item"><span class="c-brand icon-phone me-1"></span> {{ @$center->contact_number }}</span>
                                @endif
                                @if(@$center->whatsapp_number)
                                <span class="xd-help-contact-item"><span class="c-brand icon-whatsapp me-1"></span> {{ @$center->whatsapp_number }}</span>
                                @endif
                                @if(@$center->website)
                                <a class="xd-help-contact-item" target="_blank" href="{{ @$center->website }}"><span class="c-brand icon-globe me-1"></span> Website</a>
                                @endif
                                @if(@$center->facebook_url)
                                <a class="xd-help-contact-item" target="_blank" href="{{ @$center->facebook_url }}"><span class="icon-facebook me-1"></span> Facebook</a>
                                @endif
                                @if(@$center->instagram_url)
                                <a class="xd-help-contact-item" target="_blank" href="{{ @$center->instagram_url }}"><span class="icon-instagram me-1"></span> Instagram</a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Placement: matches reference's amenities sub-block inside "About [Center]" --}}
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

                        {{-- Placement: kept adjacent to About Center (was already neighboring it before this move; not one of the five, preserved rather than orphaned) --}}
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

                        {{-- Placement: kept adjacent to About Center (center-level property gallery; was already neighboring it before this move; not one of the five, preserved rather than orphaned) --}}
                        @if((sizeof((array)@$accomodationimagegalleries)>0) OR (@$center->accomodation_banner_image_url) OR (@$center->accomodation_overview))
                        <div id="accomodation" class="xd-card deal-gallery">
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

                        {{-- Placement: matches reference's "Nearby"/logistics content inside "About [Center]" --}}
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

                        {{-- Placement: no explicit slot in the reference; fallback — preserved in this cluster rather than dropped --}}
                        @if(@$center->things_to_do_around_the_center)
                        <div id="things-to-do" class="xd-card">
                            <span class="xd-tag">Explore</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128506;&#65039;</span> Things to Do Around {{ @$center->name }}</h2>
                            <div>{!! @$center->things_to_do_around_the_center !!}</div>
                        </div>
                        @endif

                        {{-- Placement: no explicit slot in the reference; fallback — preserved in this cluster rather than dropped --}}
                        @if(@$experience->booking_info)
                        <div class="xd-card" id="booking_info">
                            <span class="xd-tag">Good to Know</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#8505;&#65039;</span> Booking Info</h2>
                            <div>{!! @$experience->booking_info !!}</div>
                        </div>
                        @endif
                        {{-- ===== End consolidated cluster ===== --}}

                        {{-- Need Help? (new section — partials/experience-need-help.blade.php — Task 12) --}}
                        @include('partials.experience-need-help')

                        {{-- Reviews (container restyle only, Task 13 — iframe src/embed mechanism and @if guard untouched, per Requirement 16) --}}
                        @if(@$center->bg_id)
                        <div class="xd-card" id="reviews">
                            <span class="xd-tag">Guest Experiences</span>
                            <h2 class="xd-title"><span class="xd-title-icon">&#128172;</span> Reviews</h2>
                            <div class="xd-reviews-wrap">
                                <iframe src="https://balancegurus.com/embed/reviews?listing_id=<?php echo @$center->bg_id;?>" target="_blank" width="100%" height="600" style="border:none;"></iframe>
                            </div>
                        </div>
                        @endif

                    </div>
                </main>

                <!-- Desktop Sticky Booking Sidebar (display markup extracted to
                     partials/experience-booking-sidebar.blade.php — Task 14). $offerActive stays computed
                     here, not in the partial, because the mobile bottom bar further down also reads it and
                     Blade's include mechanism does not leak child-set vars back out (same pattern as Tasks 3/7). -->
                <?php
                $offerActive = false;
                if ((!empty(@$experience->offer_start_date)) && (!empty(@$experience->offer_discount)) && (@$experience->offer_discount > 0)) {
                    $nowD = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                    if ((\Carbon\Carbon::parse(@$experience->offer_start_date)->format("Y-m-d") <= $nowD) && (\Carbon\Carbon::parse(@$experience->offer_end_date)->format("Y-m-d") >= $nowD)) {
                        $offerActive = true;
                    }
                }
                ?>
                @include('partials.experience-booking-sidebar')
            </div>
        </div>
    </section>

    <!-- Mobile persistent booking bar + drawer (extracted to
         partials/experience-mobile-booking.blade.php — Task 15) -->
    @include('partials.experience-mobile-booking')

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
            var $durations = $form.find('.qb-durations-value');
            var $accom = $form.find('.qb-accomodation-value');
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

            $form.find('.qb-duration-pills').on('click', '.xd-pill-btn', function() {
                $form.find('.qb-duration-pills .xd-pill-btn').removeClass('active');
                $(this).addClass('active');
                $durations.val($(this).data('value'));
                calculatePrice();
            });

            $form.find('.qb-room-picker-list').on('click', '.xd-room-picker-item', function() {
                $form.find('.qb-room-picker-list .xd-room-picker-item').removeClass('active');
                $(this).addClass('active');
                $accom.val($(this).data('value'));
                calculatePrice();
            });

            $date.on('change', calculatePrice);
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

    function xdSelectRoom(accomId) {
        $('form.quick-booking').each(function() {
            var $form = $(this);
            var $item = $form.find('.qb-room-picker-list .xd-room-picker-item[data-value="' + accomId + '"]');
            if ($item.length) {
                $item.trigger('click');
            }
        });

        var sidebar = document.querySelector('.xd-sidebar');
        if (sidebar && getComputedStyle(sidebar).display !== 'none') {
            document.getElementById('booking-card').scrollIntoView({behavior: 'smooth', block: 'start'});
        } else {
            xdOpenDrawer();
        }
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

    // Additive-only (new section, visual-parity pass): scroll-spy for the in-page tab nav.
    // Pure UI highlight, does not touch /get_booking_price, /reservation, or /store-inquiry.
    function xdInitPageNavScrollSpy() {
        var nav = document.getElementById('xd-page-nav');
        if (!nav) return;
        var tabs = Array.prototype.slice.call(nav.querySelectorAll('.xd-nav-tab'));
        var targets = tabs.map(function(tab) {
            var id = tab.getAttribute('href').slice(1);
            return document.getElementById(id);
        });
        function setActiveOnScroll() {
            var pos = window.scrollY + 160;
            var activeIndex = 0;
            for (var i = 0; i < targets.length; i++) {
                if (targets[i] && targets[i].offsetTop <= pos) activeIndex = i;
            }
            tabs.forEach(function(tab, i) {
                tab.classList.toggle('active', i === activeIndex);
            });
        }
        window.addEventListener('scroll', setActiveOnScroll, { passive: true });
        // Re-run after 'load' and again just past the 3000ms mark: script.js keeps the whole
        // content column hidden behind .d-none until a hardcoded setTimeout(...,3000) reveals
        // it (see public/basicfront/js/script.js), so every section's offsetTop still reads ~0
        // at DOMContentLoaded/'load' time — the loop below then marches through every tab and
        // leaves the LAST one active instead of the first.
        window.addEventListener('load', setActiveOnScroll);
        setTimeout(setActiveOnScroll, 3200);
        setActiveOnScroll();
    }
    document.addEventListener('DOMContentLoaded', xdInitPageNavScrollSpy);

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
