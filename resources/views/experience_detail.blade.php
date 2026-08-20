{{--
    Retreat Detail Page — artifact-matched layout (design ref: Claude Artifact "Retreat Detail Page —
    Full Booking Layout Concept").

    Reuses real data exactly as prepared by ExperienceController@index and reuses four existing
    partials unchanged for their PHP logic/JS hooks — only their CSS is re-skinned here, under the
    .rd-page scope, to match the artifact:
      - partials.experience-booking-fields   (duration pills + room picker, qb-* JS hooks)
      - partials.experience-booking-sidebar  (desktop sticky booking form, quick-booking JS)
      - partials.experience-availability     (real calendar generation from $experience_upcoming_availability)
      - partials.experience-mobile-booking   (mobile bottom bar + drawer, same form)
    Everything else (header/gallery/overview/accommodation/food/pricing/terms/center/reviews) is fresh
    markup matching the artifact's own class names (room-card, cal-card, terms-grid, etc. — prefixed rd-
    here to avoid colliding with the site's Bootstrap/global CSS), wired to the same real fields the
    page uses. No field is fabricated: sections/chips with no backing data are simply omitted, matching
    the "no fabricated data" precedent already set in partials.experience-food's comments.
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
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style type="text/css">
  /* ---------- Tokens (scoped to .rd-page only) ---------- */
  .rd-page {
    --rd-brand: #ff3366;
    --rd-brand-deep: #d32b55;
    --rd-gold: #ffc107;
    --rd-gold-text: #916300;
    --rd-ink: #221f20;
    --rd-ink-soft: #55494b;
    --rd-grey-mid: #756a6c;
    --rd-grey-line: #e4dcda;
    --rd-wash: #f4efed;
    --rd-paper: #ffffff;
    --rd-card: #ffffff;
    --rd-success: #2f8f45;
    --rd-success-bg: #e8f5e9;
    --rd-danger: #c13515;
    --rd-danger-bg: #fbece8;
    --rd-gold-bg: #fff6dd;
    --rd-shadow: 0 1px 2px rgba(34,31,32,0.04), 0 12px 32px -16px rgba(34,31,32,0.18);
    --rd-shadow-lg: 0 24px 60px -24px rgba(34,31,32,0.35);
  }

  /* ---------- Base ---------- */
  .rd-page {
    background: var(--rd-paper); color: var(--rd-ink);
    font-family: 'Work Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 15.5px; line-height: 1.6; -webkit-font-smoothing: antialiased;
  }
  .rd-page img, .rd-page svg { max-width: 100%; display: block; }
  .rd-page a { color: inherit; text-decoration: none; }
  .rd-page h1, .rd-page h2, .rd-page h3, .rd-page h4 {
    font-family: 'Fraunces', Georgia, serif; font-weight: 600; color: var(--rd-ink);
    margin: 0; text-wrap: balance; letter-spacing: -0.01em;
  }
  /* The site's global .bg-page-listing h2:not([class*="c-"]) rule (0,2,1 specificity) otherwise
     beats the rule above (0,1,1) for every <h2> on the page, painting every section heading brand-pink. */
  .rd-page h2 { color: var(--rd-ink) !important; }
  .rd-eyebrow {
    font-family: 'Work Sans', sans-serif; font-size: 0.76rem; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase; color: var(--rd-brand); display: block; margin-bottom: 6px;
  }
  .rd-page p { color: var(--rd-ink-soft); margin: 0 0 1em; overflow-wrap: break-word; }
  .rd-measure { max-width: 78ch; overflow-wrap: break-word; word-break: break-word; }
  .rd-measure img { max-width: 100%; height: auto; }
  .rd-measure table { max-width: 100%; display: block; overflow-x: auto; }
  .rd-measure iframe { max-width: 100%; }
  .rd-tnum { font-variant-numeric: tabular-nums; }
  .rd-wrap { max-width: 1240px; margin: 0 auto; padding: 0 clamp(18px, 3.4vw, 40px); }
  .rd-ico { width: 15px; height: 15px; flex: none; display: inline-block; }
  /* CMS rich-text fields (About Center / How to Reach / etc.) can carry long unbroken URLs, phone
     numbers, or fixed-width WYSIWYG markup; without this they overflow the content column. .rd-left-col
     needs min-width:0 too since it's a grid item — grid items default to min-width:auto, which ignores
     child overflow-wrap and lets long content stretch the column instead of wrapping. */
  .rd-left-col { min-width: 0; }
  .rd-content-col, .rd-check-list, .rd-cross-list, .rd-terms-grid dd { overflow-wrap: break-word; word-break: break-word; }

  .rd-page section[id] { scroll-margin-top: 150px; }

  /* ---------- Utility bar ---------- */
  .rd-util-bar { border-bottom: 1px solid var(--rd-grey-line); background: var(--rd-paper); }
  .rd-util-bar .rd-wrap { display: flex; align-items: center; justify-content: space-between; padding: 12px clamp(18px,3.4vw,40px); flex-wrap: wrap; gap: 8px; }
  .rd-crumbs { font-size: 0.82rem; color: var(--rd-grey-mid); display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
  .rd-crumbs a:hover { color: var(--rd-brand); }
  .rd-sep { color: var(--rd-grey-line); }
  .rd-util-actions { display: flex; gap: 18px; font-size: 0.85rem; color: var(--rd-ink-soft); }
  .rd-util-actions .bg-menu-list > span, .rd-util-actions > span { display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
  .rd-util-actions span:hover { color: var(--rd-brand); }

  /* ---------- Title block ---------- */
  .rd-title-block { padding: 22px 0 18px; }
  .rd-title-block h1 { font-size: clamp(1.6rem, 3vw, 2.15rem); margin-bottom: 8px; }
  .rd-title-meta { display: flex; flex-wrap: wrap; gap: 8px 10px; align-items: center; font-size: 0.9rem; color: var(--rd-ink-soft); }
  .rd-title-meta .icon-location { color: var(--rd-brand); margin-right: 2px; }
  .rd-title-price del { color: var(--rd-grey-mid); font-weight: normal; margin-right: 4px; }
  .rd-title-price strong { font-family: 'Fraunces', serif; color: var(--rd-ink); }
  .rd-badge-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
  .rd-badge {
    display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600;
    padding: 6px 12px; border-radius: 999px;
  }
  .rd-badge-cert { background: var(--rd-gold-bg); color: var(--rd-gold-text); }
  .rd-badge-row .tier-badge { position: static; display: inline-block; background: #2F6F57; color: #fff; font-size: 0.78rem; font-weight: 600; padding: 6px 12px; border-radius: 999px; }

  /* ---------- Gallery grid ---------- */
  .rd-gallery-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(2, 150px);
    gap: 6px; border-radius: 16px; overflow: hidden; position: relative; margin: 18px 0 6px;
  }
  .rd-g-tile { grid-column: span 1; grid-row: span 1; min-height: 0; overflow: hidden; cursor: pointer; }
  .rd-g-tile img { width: 100%; height: 100%; object-fit: cover; }
  .rd-g-tile:first-child { grid-column: span 2; grid-row: span 2; }
  .rd-g-tile--empty { background: var(--rd-wash); }
  .rd-show-all-btn {
    position: absolute; right: 16px; bottom: 16px; background: var(--rd-card); color: var(--rd-ink);
    font-size: 0.82rem; font-weight: 600; padding: 9px 16px; border-radius: 8px; box-shadow: var(--rd-shadow);
    display: inline-flex; align-items: center; gap: 7px; border: 1px solid var(--rd-grey-line); cursor: pointer;
  }

  /* ---------- Section nav ---------- */
  .rd-section-nav {
    position: sticky; top: 80px; z-index: 30; background: var(--rd-paper);
    border-bottom: 1px solid var(--rd-grey-line); box-shadow: var(--rd-shadow); margin-top: 8px;
  }
  .rd-section-nav ul { list-style: none; display: flex; gap: 30px; margin: 0; padding: 0; overflow-x: auto; }
  .rd-section-nav a {
    display: inline-block; padding: 16px 2px; font-size: 0.88rem; font-weight: 600; color: var(--rd-grey-mid);
    border-bottom: 2px solid transparent; white-space: nowrap;
  }
  .rd-section-nav a:hover { color: var(--rd-ink); }
  .rd-section-nav a.active { color: var(--rd-ink); border-color: var(--rd-brand); }

  /* ---------- Page grid ---------- */
  /* .rd-page-grid now wraps .rd-left-col (title/gallery/nav/content) + the sidebar as siblings,
     so the sidebar starts level with the banner instead of only alongside the content sections.
     Top padding lives on .rd-title-block instead, so it isn't applied twice. */
  .rd-page-grid { display: grid; grid-template-columns: 1fr 380px; gap: 56px; align-items: start; padding: 0 0 80px; }
  .rd-content-col > section { padding: 34px 0; border-top: 1px solid var(--rd-grey-line); }
  .rd-content-col > section:first-child { border-top: none; }
  .rd-content-col h2 { font-size: clamp(1.35rem, 2vw, 1.65rem); margin-bottom: 18px; }

  .rd-spec-row { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
  .rd-spec-chip {
    display: inline-flex; align-items: center; gap: 7px; font-size: 0.84rem; color: var(--rd-ink-soft);
    padding: 8px 13px; border-radius: 999px; background: var(--rd-wash);
  }

  .rd-bullet-list { list-style: none; margin: 16px 0 0; padding: 0; display: grid; gap: 11px; }
  .rd-bullet-list li { display: flex; gap: 11px; color: var(--rd-ink-soft); font-size: 0.94rem; }
  .rd-bullet-list li::before { content: ""; flex: none; width: 6px; height: 6px; border-radius: 50%; background: var(--rd-brand); margin-top: 8px; }

  .rd-schedule-list { list-style: none; margin: 0; padding: 0; display: grid; border-top: 1px solid var(--rd-grey-line); }
  .rd-schedule-list li { display: flex; gap: 16px; padding: 10px 0; border-bottom: 1px solid var(--rd-grey-line); font-size: 0.9rem; color: var(--rd-ink-soft); }
  .rd-schedule-list time { font-variant-numeric: tabular-nums; color: var(--rd-brand); font-weight: 600; width: 4.4em; flex: none; }

  .rd-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
  .rd-check-list ul, .rd-cross-list ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 9px; font-size: 0.9rem; }
  .rd-check-list li, .rd-cross-list li { display: flex; gap: 10px; color: var(--rd-ink-soft); }
  .rd-check-list li::before { content: "\2713"; color: var(--rd-success); font-weight: 700; flex: none; }
  .rd-cross-list li::before { content: "\2013"; color: var(--rd-grey-mid); font-weight: 700; flex: none; }

  /* ---------- Room cards ---------- */
  .rd-room-card { border: 1px solid var(--rd-grey-line); border-radius: 14px; overflow: hidden; margin-bottom: 18px; background: var(--rd-card); }
  .rd-room-photos { display: grid; grid-template-columns: repeat(3, 1fr); grid-auto-rows: 130px; gap: 2px; height: 130px; background: var(--rd-wash); position: relative; }
  .rd-room-photos img { display: block; width: 100%; height: 100%; object-fit: cover; cursor: pointer; }
  .rd-room-photos--empty { height: 0; }
  .rd-room-gallery-btn {
    position: absolute; right: 10px; bottom: 10px; background: rgba(34,31,32,0.72); color: #fff;
    font-size: 0.76rem; font-weight: 600; padding: 6px 12px; border-radius: 8px;
    display: inline-flex; align-items: center; gap: 6px; cursor: pointer; z-index: 2;
  }
  .rd-room-gallery-btn:hover { background: rgba(34,31,32,0.88); }

  /* ---------- Room photo lightbox (rd-room-gallery) ---------- */
  .rd-lightbox {
    position: fixed; inset: 0; background: rgba(17,15,15,0.92); z-index: 3000; display: none;
    align-items: center; justify-content: center; flex-direction: column; padding: 40px 20px;
  }
  .rd-lightbox.active { display: flex; }
  .rd-lightbox-title { color: #fff; font-family: 'Fraunces', serif; font-size: 1.05rem; margin-bottom: 14px; text-align: center; }
  .rd-lightbox-stage { position: relative; max-width: min(920px, 92vw); width: 100%; display: flex; align-items: center; justify-content: center; }
  .rd-lightbox-stage img { max-width: 100%; max-height: 74vh; border-radius: 10px; object-fit: contain; }
  .rd-lightbox-close, .rd-lightbox-prev, .rd-lightbox-next {
    position: absolute; background: rgba(255,255,255,0.12); color: #fff; border: none; border-radius: 50%;
    width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem;
  }
  .rd-lightbox-close:hover, .rd-lightbox-prev:hover, .rd-lightbox-next:hover { background: rgba(255,255,255,0.24); }
  .rd-lightbox-close { top: -52px; right: 0; }
  .rd-lightbox-prev { left: -8px; top: 50%; transform: translateY(-50%); }
  .rd-lightbox-next { right: -8px; top: 50%; transform: translateY(-50%); }
  .rd-lightbox-count { color: var(--rd-grey-line); font-size: 0.82rem; margin-top: 12px; }
  @media (max-width: 640px) {
    .rd-lightbox-prev { left: 4px; } .rd-lightbox-next { right: 4px; }
    .rd-lightbox-close { top: -44px; }
  }
  .rd-room-body { padding: 18px 20px 20px; }
  .rd-room-top { display: flex; justify-content: space-between; gap: 14px; align-items: flex-start; flex-wrap: wrap; }
  .rd-room-name { font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.12rem; }
  .rd-room-name a { color: inherit; }
  .rd-room-desc { font-size: 0.87rem; color: var(--rd-grey-mid); margin: 4px 0 0; }
  .rd-room-avail-note { font-size: 0.78rem; color: var(--rd-brand); font-weight: 600; margin: 6px 0 0; }
  .rd-amenity-row { display: flex; flex-wrap: wrap; gap: 14px; margin: 14px 0; font-size: 0.82rem; color: var(--rd-ink-soft); }
  .rd-amenity-row span { display: inline-flex; align-items: center; gap: 6px; }
  .rd-room-price-table { font-size: 0.85rem; margin-top: 12px; overflow-x: auto; }
  .rd-room-price-table table { border-collapse: collapse; width: 100%; }
  .rd-room-price-table th { text-align: left; font-weight: 500; color: var(--rd-grey-mid); padding: 2px 14px 6px 0; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.04em; }
  .rd-room-price-table td { padding: 3px 14px 3px 0; font-weight: 600; }
  .rd-room-cta { display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap; }

  .rd-status-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; padding: 5px 12px; border-radius: 999px; white-space: nowrap; }
  .rd-status-pill .rd-dot { width: 7px; height: 7px; border-radius: 50%; }
  .rd-status-open { background: var(--rd-success-bg); color: var(--rd-success); } .rd-status-open .rd-dot { background: var(--rd-success); }
  .rd-status-few { background: var(--rd-gold-bg); color: var(--rd-gold-text); } .rd-status-few .rd-dot { background: var(--rd-gold); }
  .rd-status-full { background: var(--rd-danger-bg); color: var(--rd-danger); } .rd-status-full .rd-dot { background: var(--rd-danger); }

  /* ---------- Food ---------- */
  .rd-food-photos { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin: 18px 0 6px; }
  .rd-food-photos img { aspect-ratio: 1; border-radius: 10px; object-fit: cover; width: 100%; height: 100%; }

  /* ---------- Pricing ---------- */
  .rd-page table.rd-price-table { width: 100%; border-collapse: collapse; font-size: 0.94rem; }
  .rd-page table.rd-price-table th { text-align: left; font-weight: 500; color: var(--rd-grey-mid); font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0 14px 12px 0; border-bottom: 1px solid var(--rd-grey-line); }
  .rd-page table.rd-price-table td { padding: 15px 14px 15px 0; border-bottom: 1px solid var(--rd-grey-line); vertical-align: baseline; }
  .rd-page table.rd-price-table tr:last-child td { border-bottom: none; }
  .rd-price-main { font-family: 'Fraunces', serif; font-weight: 600; font-size: 1.1rem; color: var(--rd-ink); }
  .rd-price-strike { color: var(--rd-grey-mid); text-decoration: line-through; font-size: 0.84rem; }
  .rd-table-scroll { overflow-x: auto; }

  /* ---------- Terms ---------- */
  .rd-terms-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 36px; margin: 0; }
  .rd-terms-grid dt { font-weight: 600; font-size: 0.87rem; margin-bottom: 4px; color: var(--rd-ink); }
  .rd-terms-grid dd { margin: 0; color: var(--rd-ink-soft); font-size: 0.88rem; }
  .rd-terms-grid > div { padding-bottom: 16px; border-bottom: 1px solid var(--rd-grey-line); }

  /* ---------- Center ---------- */
  .rd-center-head { display: flex; gap: 18px; align-items: center; margin-bottom: 18px; }
  .rd-center-mark {
    width: 54px; height: 54px; border-radius: 50%; flex: none;
    background: linear-gradient(135deg, var(--rd-brand), var(--rd-gold));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; color: #fff; font-weight: 600; font-size: 1.2rem;
  }
  .rd-center-head h2 { font-size: 1.35rem; margin-bottom: 2px; }
  .rd-center-head p { margin: 0; font-size: 0.88rem; color: var(--rd-grey-mid); }
  .rd-center-photos { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; margin: 16px 0 20px; }
  .rd-center-photos img { aspect-ratio: 1; border-radius: 8px; object-fit: cover; width: 100%; height: 100%; }
  .rd-tag-row { display: flex; flex-wrap: wrap; gap: 8px; }
  .rd-chip { display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; padding: 7px 13px; border-radius: 999px; background: var(--rd-wash); color: var(--rd-ink-soft); }
  .rd-chip-icon { width: 15px; height: 15px; object-fit: contain; flex: none; filter: grayscale(1) opacity(0.75); }
  .rd-map-box {
    margin-top: 22px; border-radius: 14px; height: 160px; position: relative; overflow: hidden;
    background-image:
      linear-gradient(var(--rd-grey-line) 1px, transparent 1px), linear-gradient(90deg, var(--rd-grey-line) 1px, transparent 1px);
    background-size: 26px 26px; background-color: var(--rd-wash);
    display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 10px;
  }
  .rd-map-pin { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; background: var(--rd-brand); transform: rotate(-45deg); box-shadow: var(--rd-shadow-lg); }
  .rd-map-box .rd-map-label { font-size: 0.85rem; font-weight: 600; color: var(--rd-ink); background: var(--rd-card); padding: 6px 14px; border-radius: 999px; box-shadow: var(--rd-shadow); text-align: center; max-width: 80%; }
  .rd-contact-row { display: flex; flex-wrap: wrap; gap: 8px 22px; font-size: 0.87rem; margin-top: 20px; color: var(--rd-ink-soft); }
  .rd-contact-row a { border-bottom: 1px solid var(--rd-grey-line); }
  .rd-contact-row a:hover { border-color: var(--rd-brand); color: var(--rd-brand); }
  /* .rd-page h1,h2,h3,h4 {margin:0} above has higher specificity (element+class beats class alone),
     so a bare .rd-subhead rule never actually wins — every rd-subhead heading site-wide rendered with
     zero margin regardless of this declaration. Scoping it to .rd-page .rd-subhead fixes that. */
  .rd-page .rd-subhead { font-size: 1rem; font-weight: 700; margin: 22px 0 10px; }

  /* ---------- Reviews ---------- */
  .rd-reviews-wrap { border-radius: 14px; overflow: hidden; border: 1px solid var(--rd-grey-line); }

  /* ---------- Booking sidebar (re-skins partials.experience-booking-sidebar / -fields) ---------- */
  .rd-page .xd-sidebar { position: sticky; top: 96px; max-height: calc(100vh - 116px); overflow-y: auto; }
  .rd-page .xd-booking-card {
    border: 1px solid var(--rd-grey-line); border-radius: 16px; padding: 18px; background: var(--rd-card);
    box-shadow: var(--rd-shadow-lg);
  }
  .rd-page .xd-booking-header { text-align: left; margin-bottom: 4px; }
  .rd-page .xd-discount-tag {
    display: inline-flex; align-items: center; gap: 4px; background: var(--rd-danger-bg); color: var(--rd-danger);
    border: none; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
    padding: 3px 10px; border-radius: 999px; margin-bottom: 8px;
  }
  .rd-page .xd-booking-title { font-family: 'Fraunces', serif; font-size: 1.08rem; color: var(--rd-ink); margin-bottom: 12px; display: block; justify-content: flex-start; text-align: left; }
  .rd-page .xd-form-group { margin-bottom: 12px; }
  .rd-page .xd-form-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--rd-grey-mid); margin-bottom: 6px; display: block; }
  .rd-page .xd-pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
  .rd-page .xd-pill-btn {
    flex: 1; min-width: 64px; text-align: center; font-size: 0.82rem; font-weight: 600; padding: 8px 6px;
    border: 1px solid var(--rd-grey-line); border-radius: 9px; cursor: pointer; color: var(--rd-ink-soft); background: var(--rd-card);
  }
  .rd-page .xd-pill-btn:hover { border-color: var(--rd-brand); color: var(--rd-brand); }
  .rd-page .xd-pill-btn.active { border-color: var(--rd-brand); background: var(--rd-brand); color: #fff; box-shadow: none; font-weight: 600; }
  .rd-page .xd-room-picker-list { display: flex; flex-direction: column; gap: 6px; max-height: 190px; overflow-y: auto; padding-right: 2px; }
  .rd-page .xd-room-picker-item {
    display: flex; align-items: center; padding: 6px 8px; border: 1px solid var(--rd-grey-line);
    border-radius: 9px; cursor: pointer; background: var(--rd-card);
  }
  .rd-page .xd-room-picker-item:hover { border-color: var(--rd-brand); }
  .rd-page .xd-room-picker-item.active { border-color: var(--rd-brand); box-shadow: 0 0 0 1px var(--rd-brand) inset; background: var(--rd-card); }
  .rd-page .xd-room-picker-thumb { width: 30px; height: 30px; border-radius: 7px; object-fit: cover; margin-right: 9px; flex-shrink: 0; }
  .rd-page .xd-room-picker-details { display: flex; flex-direction: column; min-width: 0; flex: 1; }
  .rd-page .xd-room-picker-name { font-size: 0.81rem; color: var(--rd-ink); font-weight: 600; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .rd-page .xd-room-picker-price { font-size: 0.78rem; color: var(--rd-ink-soft); font-weight: 600; }
  .rd-page .xd-room-picker-item.active .xd-room-picker-price { color: var(--rd-brand); }
  .rd-page .xd-room-picker-price del { color: var(--rd-grey-mid); font-weight: normal; margin-right: 4px; font-size: 0.78rem; }
  .rd-page .xd-input, .rd-page .xd-select {
    width: 100%; padding: 11px 14px; background: var(--rd-card); border: 1px solid var(--rd-grey-line);
    border-radius: 10px; font-size: 0.94rem; color: var(--rd-ink);
  }
  .rd-page .xd-input:focus, .rd-page .xd-select:focus { outline: 2px solid var(--rd-brand); outline-offset: 1px; border-color: var(--rd-brand); }
  .rd-page .xd-calc-box { background: var(--rd-wash); border-radius: 10px; padding: 11px 14px; margin: 12px 0; border: none; }
  .rd-page .xd-calc-row { display: flex; justify-content: space-between; margin-bottom: 5px; color: var(--rd-ink-soft); font-size: 0.84rem; }
  .rd-page .xd-calc-row.total { border-top: 1px solid var(--rd-grey-line); padding-top: 6px; margin-bottom: 0; font-size: 0.96rem; color: var(--rd-ink); font-weight: 700; }
  .rd-page .xd-calc-row.total .qb-booking-amount { font-family: 'Fraunces', serif; }
  .rd-page .xd-btn-gradient {
    width: 100%; background: var(--rd-brand); color: #fff; border: 1px solid transparent;
    padding: 11px 24px; border-radius: 10px; font-size: 0.92rem; font-weight: 600; cursor: pointer;
    text-align: center; text-decoration: none; display: inline-block; box-shadow: none; transition: background-color .15s;
  }
  .rd-page .xd-btn-gradient:hover { background: var(--rd-brand-deep); }
  .rd-page .xd-btn-gradient:disabled { opacity: 0.55; cursor: not-allowed; }
  .rd-page .xd-btn-outline {
    width: 100%; background: transparent; color: var(--rd-ink); border: 1px solid var(--rd-grey-line);
    padding: 9px 24px; border-radius: 10px; font-size: 0.87rem; font-weight: 600; cursor: pointer; margin-bottom: 8px;
  }
  .rd-page .xd-btn-outline:hover { border-color: var(--rd-ink-soft); }
  .rd-page .xd-btn-sm { width: auto; padding: 9px 16px; font-size: 0.85rem; margin-bottom: 0; }
  .rd-page #razorpay-affordability-widget { margin-top: 10px !important; }

  /* ---------- Availability (re-skins partials.experience-availability's .xd-card) ---------- */
  .rd-page .xd-card { border: 1px solid var(--rd-grey-line); border-radius: 14px; padding: 20px 22px 22px; }
  .rd-page .xd-tag, .rd-page .xd-title-icon { display: none; }
  .rd-page .xd-title { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; color: var(--rd-ink); margin-bottom: 14px; }
  .rd-page .xd-avail-weekdays {
    display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 4px;
    font-size: 0.72rem; font-weight: 600; color: var(--rd-grey-mid); text-transform: uppercase; letter-spacing: 0.04em; text-align: center;
  }
  .rd-page .xd-avail-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
  .rd-page .xd-avail-day {
    aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px;
    font-size: 0.85rem; color: var(--rd-ink-soft); background: transparent; border: none;
  }
  .rd-page .xd-avail-day--blank { background: transparent; }
  .rd-page .xd-avail-day.is-open { background: transparent; color: var(--rd-ink-soft); position: relative; }
  .rd-page .xd-avail-day.is-open::after { content: ""; position: absolute; bottom: 5px; width: 5px; height: 5px; border-radius: 50%; background: var(--rd-success); }
  .rd-page .xd-avail-day.is-few { background: transparent; color: var(--rd-ink-soft); position: relative; }
  .rd-page .xd-avail-day.is-few::after { content: ""; position: absolute; bottom: 5px; width: 5px; height: 5px; border-radius: 50%; background: var(--rd-gold); }
  .rd-page .xd-avail-day.is-full { background: transparent; color: var(--rd-ink-soft); position: relative; }
  .rd-page .xd-avail-day.is-full::after { content: ""; position: absolute; bottom: 5px; width: 5px; height: 5px; border-radius: 50%; background: var(--rd-danger); }
  .rd-page .xd-avail-legend { display: flex; gap: 18px; flex-wrap: wrap; font-size: 0.8rem; color: var(--rd-grey-mid); margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--rd-grey-line); }
  .rd-page .xd-avail-legend-item { display: inline-flex; align-items: center; gap: 6px; }
  .rd-page .xd-avail-legend-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
  .rd-page .xd-avail-other-heading { font-family: 'Fraunces', serif; font-size: 0.95rem; font-weight: 600; color: var(--rd-ink); margin: 22px 0 10px; }
  /* ---------- Calendar popup + "Check Availability" modal calendar (items 6 & 7) ----------
     Both are built client-side by rdBuildCalendar() using the same .xd-avail-* classes above, so the
     popup, the modal, and the static Availability section all look identical. These rules add the
     month-nav header and interactive/disabled day states the static section didn't need. */
  .rd-page .xd-avail-cal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
  .rd-page .xd-avail-cal-month { font-family: 'Fraunces', serif; font-size: 0.95rem; font-weight: 600; color: var(--rd-ink); }
  .rd-page .rd-cal-nav-btn {
    width: 28px; height: 28px; border-radius: 50%; border: 1px solid var(--rd-grey-line); background: var(--rd-card);
    color: var(--rd-ink-soft); cursor: pointer; font-size: 1rem; line-height: 1; display: inline-flex; align-items: center; justify-content: center;
  }
  .rd-page .rd-cal-nav-btn:hover { border-color: var(--rd-brand); color: var(--rd-brand); }
  .rd-page .xd-avail-day--past { opacity: 0.35; }
  .rd-page .rd-cal-day-pick { cursor: pointer; }
  .rd-page .rd-cal-day-pick:hover { background: var(--rd-wash); font-weight: 700; }
  .rd-page .rd-cal-day-pick.rd-cal-day-selected { background: var(--rd-brand); color: #fff; font-weight: 700; }

  .rd-datefield-group { position: relative; }
  .rd-page .rd-date-input { cursor: pointer; background: var(--rd-card); }
  .rd-page .rd-cal-popup {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 60; display: none;
    background: var(--rd-card); border: 1px solid var(--rd-grey-line); border-radius: 14px;
    box-shadow: var(--rd-shadow-lg); padding: 14px;
  }
  .rd-page .rd-cal-popup.active { display: block; }

  /* Calendar embedded in the shared #checkAvailability modal (layouts/experience_details.blade.php)
     — that markup sits outside .rd-page, so it's wrapped in a .rd-page carrier div to pick up these
     same tokens/rules. It only needs to render inline (no popup positioning/background). */
  .rd-modal-cal-wrap.rd-page {
    background: transparent; margin-bottom: 22px; font-size: 15px;
    border: 1px solid var(--rd-grey-line); border-radius: 14px; padding: 18px 20px;
  }
  .rd-page .xd-avail-list { display: flex; gap: 10px; overflow-x: auto; padding: 2px 2px 6px; }
  .rd-page .xd-routine-item {
    flex: none; width: 140px; border: 1px solid var(--rd-grey-line); border-radius: 10px; padding: 12px;
    background: var(--rd-card); display: block; border-left: 1px solid var(--rd-grey-line);
  }
  .rd-page .xd-routine-time { font-family: 'Fraunces', serif; font-weight: 600; font-size: 0.85rem; color: var(--rd-ink); display: block; margin-bottom: 8px; }
  .rd-page .xd-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 0.76rem; font-weight: 600; padding: 4px 11px; border-radius: 999px; }
  .rd-page .xd-badge-open { background: var(--rd-success-bg); color: var(--rd-success); }
  .rd-page .xd-badge-warn { background: var(--rd-gold-bg); color: var(--rd-gold-text); }
  .rd-page .xd-badge-danger { background: var(--rd-danger-bg); color: var(--rd-danger); }

  /* ---------- Mobile bottom bar + drawer ---------- */
  .rd-page .xd-mobile-bar { display: none; }
  .rd-page .xd-mobile-drawer-overlay {
    position: fixed; inset: 0; background: rgba(34,31,32,0.6); z-index: 2100; display: none; opacity: 0; transition: opacity .25s ease;
  }
  .rd-page .xd-mobile-drawer-overlay.active { display: flex; opacity: 1; }
  .rd-page .xd-mobile-drawer {
    position: absolute; bottom: 0; left: 0; right: 0; background: var(--rd-card);
    border-top-left-radius: 22px; border-top-right-radius: 22px; padding: 22px 18px 18px; max-height: 85vh; overflow-y: auto;
  }
  .rd-page .xd-drawer-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
  .rd-page .xd-drawer-header .xd-booking-title { margin-bottom: 0; }
  .rd-page .xd-modal-close { background: none; border: none; font-size: 16px; cursor: pointer; color: var(--rd-grey-mid); line-height: 1; }
  .rd-page footer.rd-credit { text-align: center; padding: 20px 0 40px; font-size: 0.78rem; color: var(--rd-grey-mid); border-top: 1px solid var(--rd-grey-line); }

  @media (max-width: 1024px) {
    .rd-page-grid { grid-template-columns: 1fr; }
    .rd-page .xd-sidebar { display: none; }
    .rd-page .xd-mobile-bar {
      display: flex; position: fixed; bottom: 0; left: 0; right: 0; z-index: 1500;
      background: var(--rd-card); border-top: 1px solid var(--rd-grey-line); padding: 12px 18px;
      align-items: center; justify-content: space-between; box-shadow: 0 -8px 24px rgba(0,0,0,0.12);
      flex-direction: row; gap: 10px;
    }
    .rd-page .xd-mobile-bar-top { display: none; }
    .rd-page .xd-mobile-bar-bottom { display: flex; gap: 10px; flex: 1; }
    .rd-page .xd-btn-mobile-outline, .rd-page .xd-btn-mobile-gradient {
      flex: 1; padding: 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; border: 1px solid var(--rd-grey-line);
    }
    .rd-page .xd-btn-mobile-outline { background: var(--rd-card); color: var(--rd-ink); }
    .rd-page .xd-btn-mobile-gradient { background: var(--rd-brand); color: #fff; border-color: transparent; }
    .rd-gallery-grid { grid-template-rows: repeat(2, 110px); }
    .rd-terms-grid, .rd-two-col { grid-template-columns: 1fr; }
    .rd-food-photos, .rd-center-photos { grid-template-columns: repeat(3, 1fr); }
    body { padding-bottom: 78px; }
  }
  @media (prefers-reduced-motion: reduce) { .rd-page * { transition: none !important; } }
</style>
@endsection

@section('content')
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

<?php
// Per-room resolved price (flat duration price, or fallback min occupancy price), shared by the
// accommodation cards and the booking sidebar's room picker (partials.experience-booking-fields).
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

<?php
// Same date => best-status aggregation as partials.experience-availability, computed again here
// (Blade @include doesn't leak a partial's local variables back to the parent) so the date-picker
// popup and the "Check Availability" modal calendar (items 6 & 7) can be colored from the same
// real data as the static Availability section, via rdBuildCalendar() in the footer script.
$avByDate = array();
$statusRank = array('open' => 3, 'few_left' => 2, 'full' => 1, 'closed' => 1);
foreach (@$experience_upcoming_availability ?? [] as $avail) {
    $d = \Carbon\Carbon::parse($avail->start_date)->format('Y-m-d');
    $rank = $statusRank[$avail->status] ?? 0;
    if (!isset($avByDate[$d]) || $rank > $statusRank[$avByDate[$d]]) {
        $avByDate[$d] = $avail->status;
    }
}
ksort($avByDate);
?>

<div class="rd-page">

  <div class="rd-util-bar">
    <div class="rd-wrap">
      <nav class="rd-crumbs">
        <a href="{{ url('/') }}">Home</a>
        @if($country)<span class="rd-sep">/</span><a href="{{ url('/experiences?sdestination='.($experience_destination->firstWhere('parent', 0)->id ?? '')) }}">{{ $country }}</a>@endif
        @if($city)<span class="rd-sep">/</span><span>{{ rtrim($city, ', ') }}</span>@endif
        <span class="rd-sep">/</span><span>{{ @$experience->name }}</span>
      </nav>
      <div class="rd-util-actions">
        <div class="bg-menu-list">
          <span><span class="icon-share"></span> Share</span>
          <ul class="bg-box horiz">
            <li><a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a></li>
            <li><a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a></li>
          </ul>
        </div>
        <a href="#booking-card"><span><span class="icon-compass"></span> Reserve</span></a>
      </div>
    </div>
  </div>

  {{-- Single shared grid: the booking sidebar (aside, below) is a sibling of .rd-left-col so it
       starts level with the title/banner instead of only alongside the content sections. --}}
  <div class="rd-wrap rd-page-grid">
    <div class="rd-left-col">

  <div class="rd-title-block">
    <div>
      @if($category)
      <span class="rd-eyebrow">{{ $category }}{{ $subcategory ? ' · '.rtrim($subcategory, ', ') : '' }}</span>
      @endif
      <h1>{{ @$experience->name }}</h1>
      <div class="rd-title-meta">
        @if(@$center->address_of_center || @$experience->location)
        <span><span class="icon-location"></span> {{ @$center->address_of_center }} {{ @$experience->location }}</span>
        @endif
        @if(@$center->name)
        <span>&middot; Hosted by {{ @$center->name }}</span>
        @endif
        @if(@$experienceList->min_duration_price)
        <span class="rd-title-price">&middot; From
          @if(!empty($discount))<del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), $site_currency) }}</del>@endif
          <strong>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency) }}</strong>
        </span>
        @endif
      </div>
      <div class="rd-badge-row">
        @if($category)<span class="rd-badge rd-badge-cert">{{ $category }}</span>@endif
        @include('partials.commission-tier-badge')
      </div>
    </div>

    <?php
    $galleryImgs = array();
    if (@$experience->banner_image_url) {
        $galleryImgs[] = Storage::disk('s3')->url(rawurlencode(@$experience->banner_image_url));
    }
    foreach (@$imagegalleries as $gallery) {
        if (@$gallery->image_url && sizeof($galleryImgs) < 5) {
            $galleryImgs[] = strtok(Storage::disk('s3')->url(rawurlencode($gallery->image_url)), '?');
        }
    }
    ?>
    <div class="rd-gallery-grid">
      @if(sizeof($galleryImgs) > 0)
        @foreach($galleryImgs as $gimg)
        <div class="rd-g-tile"><img class="lazy" data-src="{{ $gimg }}" alt="{{ @$experience->name }}" /></div>
        @endforeach
      @else
        <div class="rd-g-tile rd-g-tile--empty"></div>
      @endif
      <span id="bg-gallery-all" class="rd-show-all-btn">
        <span class="icon-arrows1"></span> Show all photos
      </span>
    </div>
  </div>

  <nav class="rd-section-nav" id="rd-page-nav">
    <div class="rd-wrap">
      <ul>
        <li><a href="#overview" class="active">Overview</a></li>
        @if(sizeof(@$experience_accomodations) > 0)<li><a href="#accommodation">Accommodation</a></li>@endif
        @if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))<li><a href="#food">Food</a></li>@endif
        @if(@$experience_durations && sizeof(@$experience_durations) > 0)<li><a href="#pricing">Pricing</a></li>@endif
        @if(@$experience_upcoming_availability && sizeof(@$experience_upcoming_availability) > 0)<li><a href="#availability">Availability</a></li>@endif
        <li><a href="#payment-terms">Terms</a></li>
        @if(@$center->about_center)<li><a href="#center">Center</a></li>@endif
        @if(@$center->bg_id)<li><a href="#reviews">Reviews</a></li>@endif
      </ul>
    </div>
  </nav>

    <div class="rd-content-col">

      {{-- OVERVIEW --}}
      <section id="overview">
        <?php
        $specChips = array();
        if (@$experience->duration) { $specChips[] = $experience->duration; }
        if (@$experience->skill_level) { $specChips[] = $experience->skill_level; }
        if (@$experience->styles_taught) {
            foreach (explode(",", $experience->styles_taught) as $st) { if (trim($st)) $specChips[] = trim($st); }
        }
        if (@$experience->language_spoken) {
            foreach (explode("||", $experience->language_spoken) as $lg) { if (trim($lg)) $specChips[] = trim($lg); }
        }
        ?>
        @if(sizeof($specChips) > 0)
        <div class="rd-spec-row">
            @foreach($specChips as $chip)
            <span class="rd-spec-chip">{{ $chip }}</span>
            @endforeach
        </div>
        @endif

        @if(@$experience->experience_overview)
        <div class="rd-measure">{!! @$experience->experience_overview !!}</div>
        @endif

        <?php
        $highlightsText = trim(strip_tags(@$experience->experience_highlights));
        $highlightPoints = array();
        if ($highlightsText !== '') {
            $highlightPoints = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $highlightsText))));
            if (count($highlightPoints) <= 1) {
                $highlightPoints = array_values(array_filter(array_map('trim', preg_split('/(?<=[.!?])\s+/', $highlightsText, -1, PREG_SPLIT_NO_EMPTY))));
            }
        }
        ?>
        @if(count($highlightPoints) > 0)
        <h3 class="rd-subhead" style="margin-top:24px;">Highlights</h3>
        <ul class="rd-bullet-list">
            @foreach($highlightPoints as $point)
            <li>{{ $point }}</li>
            @endforeach
        </ul>
        @endif

        <?php
        $routineItems = (@$experience_schedules && sizeof(@$experience_schedules) > 0) ? $experience_schedules->sortBy('schedule_start_time') : collect();
        ?>
        @if($routineItems->count() > 0)
        <h3 class="rd-subhead">Daily Schedule</h3>
        <ul class="rd-schedule-list">
            @foreach($routineItems as $schedule)
            <li>
                <time>@if($schedule->schedule_start_time){{ \Carbon\Carbon::parse($schedule->schedule_start_time)->format('H:i') }}@endif</time>
                {{ $schedule->schedule_day ? $schedule->schedule_day.' — ' : '' }}{{ $schedule->activity_description }}
            </li>
            @endforeach
        </ul>
        @elseif(@$experience->schedule)
        <h3 class="rd-subhead">Daily Schedule</h3>
        <div class="rd-measure">{!! @$experience->schedule !!}</div>
        @endif

        @if(@$experience->experience_summary)
        <h3 class="rd-subhead">At a Glance</h3>
        <?php
        $summaryText = trim(strip_tags($experience->experience_summary));
        $summaryPoints = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $summaryText))));
        if (count($summaryPoints) <= 1) {
            $summaryPoints = array_values(array_filter(array_map('trim', preg_split('/(?<=[.!?])\s+/', $summaryText, -1, PREG_SPLIT_NO_EMPTY))));
        }
        ?>
        <ul class="rd-bullet-list">
            @foreach($summaryPoints as $point)
            <li>{{ $point }}</li>
            @endforeach
        </ul>
        @endif

        @if(@$experience->what_is_included || @$experience->what_is_not_included)
        <div class="rd-two-col" style="margin-top:28px;">
            @if(@$experience->what_is_included)
            <div>
                <h3 class="rd-subhead" style="margin-top:0;">Included</h3>
                <div class="rd-check-list">{!! @$experience->what_is_included !!}</div>
            </div>
            @endif
            @if(@$experience->what_is_not_included)
            <div>
                <h3 class="rd-subhead" style="margin-top:0;">Not Included</h3>
                <div class="rd-cross-list">{!! @$experience->what_is_not_included !!}</div>
            </div>
            @endif
        </div>
        @endif

        @if(@$experience->experience_details)
        <h3 class="rd-subhead">More Details</h3>
        <div class="rd-measure">{!! @$experience->experience_details !!}</div>
        @endif
      </section>

      {{-- ACCOMMODATION --}}
      @if(sizeof(@$experience_accomodations) > 0)
      <section id="accommodation">
        <h2>Accommodation</h2>
        <p class="rd-measure" style="color:var(--rd-grey-mid); font-size:0.88rem; margin-top:-8px; margin-bottom:20px;">Choose your room below — pricing and availability update instantly in the booking panel.</p>

        @foreach(@$experience_accomodations as $racm)
        <?php
        $roomImgs = array();
        if (@$accomodationimagegalleries) {
            foreach (@$accomodationimagegalleries as $ex_img) {
                if ($ex_img->accomodation_id == $racm->id && $ex_img->image_url && sizeof($roomImgs) < 5) {
                    $roomImgs[] = strtok(Storage::disk('s3')->url(rawurlencode($ex_img->image_url)), '?');
                }
            }
        }
        $roomCapacity = @$racm->ea_max_guest_in_room ?: @$racm->max_guest_in_room;
        $nextAvail = @$experience_availability_next[$racm->id] ?? null;
        $rp = $roomPricing[$racm->id];
        $durPrices = @$experience_accommodation_duration_prices[$racm->id] ?? collect();
        ?>
        <div class="rd-room-card">
            @if(sizeof($roomImgs) > 0)
            <div class="rd-room-photos" style="grid-template-columns: repeat({{ sizeof($roomImgs) }}, 1fr);" data-room-gallery data-images="{{ json_encode($roomImgs) }}" data-title="{{ $racm->name }}">
                @foreach($roomImgs as $rimg)
                <img class="lazy" data-src="{{ $rimg }}" alt="{{ $racm->name }}" />
                @endforeach
                <span class="rd-room-gallery-btn"><span class="icon-arrows1"></span> {{ sizeof($roomImgs) }} photo{{ sizeof($roomImgs) > 1 ? 's' : '' }}</span>
            </div>
            @endif
            <div class="rd-room-body">
                <div class="rd-room-top">
                    <div>
                        <div class="rd-room-name"><a href="javascript:void(0);" class="popup-large more-info-deal">{{ $racm->name }}</a></div>
                        @if(@$racm->ea_about)
                        <p class="rd-room-desc">{!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$racm->ea_about), 140)) !!}</p>
                        @elseif(@$racm->description)
                        <p class="rd-room-desc">{!! html_entity_decode(\App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$racm->description), 140)) !!}</p>
                        @endif
                        @if(@$racm->recurring_type == 'Daily')
                        <p class="rd-room-avail-note">Available all year round</p>
                        @elseif(@$racm->available_month)
                        <p class="rd-room-avail-note">{{ $racm->available_month }}</p>
                        @endif
                    </div>
                    @if($nextAvail)
                    <span class="rd-status-pill {{ $nextAvail->status == 'open' ? 'rd-status-open' : ($nextAvail->status == 'few_left' ? 'rd-status-few' : 'rd-status-full') }}">
                        <span class="rd-dot"></span>{{ \App\ExperienceAccommodationAvailability::statusLabel($nextAvail->status) }}
                        @if(in_array($nextAvail->status, ['open', 'few_left']))
                        &middot; {{ $nextAvail->remaining }} left from {{ \Carbon\Carbon::parse($nextAvail->start_date)->format('d M') }}
                        @endif
                    </span>
                    @endif
                </div>
                <div class="rd-amenity-row">
                    @if($roomCapacity)<span><span class="icon-user"></span> Sleeps {{ $roomCapacity }}</span>@endif
                    @if(@$racm->duration)<span><span class="icon-clock"></span> {{ $racm->duration }} Days</span>@endif
                    <span><span class="icon-location"></span> <?php echo \App\Experiences::get_state_country($experience->id); ?></span>
                </div>

                @if($durPrices->count() || @$racm->single_occupancy_price || @$racm->double_occupancy_price)
                <div class="rd-room-price-table">
                    <table>
                        <thead><tr><th>Duration</th><th>Single</th><th>Double (pp)</th></tr></thead>
                        <tbody>
                            @if($durPrices->count())
                                @foreach($durPrices as $dp)
                                <tr>
                                    <td class="rd-tnum">{{ $dp->duration_days }} Days</td>
                                    <td class="rd-tnum">{{ $dp->single_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($dp->single_price, $dp->currency ?: @$racm->currency) : '-' }}</td>
                                    <td class="rd-tnum">{{ $dp->double_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($dp->double_price, $dp->currency ?: @$racm->currency) : '-' }}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td class="rd-tnum">{{ @$racm->duration ? @$racm->duration.' Days' : 'Standard' }}</td>
                                <td class="rd-tnum">{{ @$racm->single_occupancy_price ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$racm->single_occupancy_price, @$racm->currency) : '-' }}</td>
                                <td class="rd-tnum">{{ @$racm->double_occupancy_price ? \App\Http\Helpers\CommonHelper::get_currency_rate(@$racm->double_occupancy_price, @$racm->currency) : '-' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="rd-room-cta">
                    <button type="button" class="xd-btn-gradient xd-btn-sm" onclick="xdSelectRoom('{{ $racm->id }}')">Select Room</button>
                    <button type="button" data-popup="requstcallPopup" class="show-bg-modal xd-btn-outline xd-btn-sm">Send Inquiry</button>
                </div>
            </div>
        </div>
        @endforeach
      </section>
      @endif

      {{-- FOOD --}}
      @if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))
      <section id="food">
        <h2>Food &amp; Dining</h2>
        @if(@$experience->food_overview)
        <div class="rd-measure">{!! @$experience->food_overview !!}</div>
        @endif
        <?php
        $foodImgs = array();
        if (@$experience->food_banner_image_url) { $foodImgs[] = strtok(Storage::disk('s3')->url(rawurlencode(@$experience->food_banner_image_url)), '?'); }
        foreach (@$foodimagegalleries as $fg) {
            if (@$fg->image_url && sizeof($foodImgs) < 4) { $foodImgs[] = strtok(Storage::disk('s3')->url(rawurlencode($fg->image_url)), '?'); }
        }
        ?>
        @if(sizeof($foodImgs) > 0)
        <div class="rd-food-photos" style="grid-template-columns: repeat({{ min(sizeof($foodImgs), 4) }}, 1fr);">
            @foreach($foodImgs as $fimg)
            <img class="lazy" data-src="{{ $fimg }}" alt="Food at {{ @$center->name }}" />
            @endforeach
        </div>
        @endif
      </section>
      @endif

      {{-- PRICING --}}
      @if(@$experience_durations && sizeof(@$experience_durations) > 0)
      <section id="pricing">
        <h2>Pricing</h2>
        <div class="rd-table-scroll">
            <table class="rd-price-table">
                <thead><tr><th>Duration</th><th>Standard Price</th><th>Promo Price</th></tr></thead>
                <tbody>
                    @foreach(@$experience_durations as $ed)
                    <tr>
                        <td class="rd-tnum">{{ $ed->duration }} Days</td>
                        <td class="rd-price-strike rd-tnum">{{ $ed->price ? \App\Http\Helpers\CommonHelper::get_currency_rate($ed->price, $ed->currency) : '-' }}</td>
                        <td class="rd-price-main rd-tnum">{{ $ed->promo_price ? \App\Http\Helpers\CommonHelper::get_currency_rate($ed->promo_price, $ed->currency) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </section>
      @endif

      {{-- AVAILABILITY (real calendar-generation logic, restyled via CSS above) --}}
      @include('partials.experience-availability')

      {{-- PAYMENT & CANCELLATION TERMS --}}
      <?php
      $depositPolicy = @$experience->deposit_policy ?: @$center_commission->deposit_policy;
      $depositAmount = @$experience->deposit_amount ?: @$center_commission->deposit_amount;
      $cancelCondition = @$experience->cancellation_policy_condition ?: @$center_commission->cancellation_policy_condition;
      $cancelDays = @$experience->cancellation_policy_days ?: @$center_commission->cancellation_policy_days;
      $restOfPayment = @$experience->rest_of_payment ?: @$center_commission->rest_of_payment;
      $restOfPaymentDays = @$experience->rest_of_payment_days ?: @$center_commission->rest_of_payment_days;
      $taxInfo = @$experience->tax ?: @$center_commission->tax;
      ?>
      @if($depositPolicy || $cancelCondition || $restOfPayment || $taxInfo || @$experience->cancellation_policy)
      <section id="payment-terms">
        <h2>Payment &amp; Cancellation Terms</h2>
        @if($depositPolicy || $cancelCondition || $restOfPayment || $taxInfo)
        <dl class="rd-terms-grid">
            @if($depositPolicy && $depositAmount)
            <div><dt>Deposit</dt><dd>{{ \App\Http\Helpers\CommonHelper::get_currency_rate($depositAmount, $site_currency) }} required to confirm your place</dd></div>
            @endif
            @if($restOfPayment && $restOfPaymentDays)
            <div><dt>Balance due</dt><dd>{{ $restOfPaymentDays }} days before the retreat start date</dd></div>
            @endif
            @if($cancelCondition && $cancelDays)
            <div><dt>Cancellation</dt><dd>Must be made at least {{ $cancelDays }} days in advance</dd></div>
            @endif
            @if($taxInfo)
            <div><dt>Applicable tax</dt><dd>{{ $taxInfo }}</dd></div>
            @endif
        </dl>
        @endif
        @if(@$experience->cancellation_policy)
        <div style="margin-top:{{ ($depositPolicy || $cancelCondition || $restOfPayment || $taxInfo) ? '24px' : '0' }};">
            <h3 class="rd-subhead" style="margin-top:0;">Cancellation Policy</h3>
            <div class="rd-measure">{!! @$experience->cancellation_policy !!}</div>
        </div>
        @endif
      </section>
      @endif

      {{-- CENTER --}}
      @if(@$center->about_center)
      <section id="center">
        <?php
        $centerInitials = '';
        foreach (explode(' ', trim(@$center->name)) as $w) { if ($w) $centerInitials .= mb_substr($w, 0, 1); }
        $centerInitials = mb_strtoupper(mb_substr($centerInitials, 0, 2));
        ?>
        <div class="rd-center-head">
            <div class="rd-center-mark">{{ $centerInitials ?: 'C' }}</div>
            <div>
                <h2>About {{ @$center->name }}</h2>
                @if(@$center->year_of_foundation || @$center->founders)
                <p>@if(@$center->year_of_foundation)Founded {{ $center->year_of_foundation }}@endif @if(@$center->founders) by {{ $center->founders }}@endif</p>
                @endif
            </div>
        </div>
        <div class="rd-measure">{!! @$center->about_center !!}</div>

        @if(@$center->our_mission)<h3 class="rd-subhead">Our Mission</h3><div class="rd-measure">{!! $center->our_mission !!}</div>@endif
        @if(@$center->our_philosophy)<h3 class="rd-subhead">Our Philosophy</h3><div class="rd-measure">{!! $center->our_philosophy !!}</div>@endif
        @if(@$center->what_sets_us_apart)<h3 class="rd-subhead">What Sets Us Apart</h3><div class="rd-measure">{!! $center->what_sets_us_apart !!}</div>@endif

        <?php
        $centerPhotos = array();
        if (@$center->accomodation_banner_image_url) { $centerPhotos[] = strtok(Storage::disk('s3')->url(rawurlencode($center->accomodation_banner_image_url)), '?'); }
        foreach (@$accomodationimagegalleries as $cimg) {
            if (@$cimg->image_url && sizeof($centerPhotos) < 5) { $centerPhotos[] = strtok(Storage::disk('s3')->url(rawurlencode($cimg->image_url)), '?'); }
        }
        ?>
        @if(sizeof($centerPhotos) > 0)
        <div class="rd-center-photos" style="grid-template-columns: repeat({{ min(sizeof($centerPhotos), 5) }}, 1fr);">
            @foreach($centerPhotos as $cphoto)
            <img class="lazy" data-src="{{ $cphoto }}" alt="{{ @$center->name }}" />
            @endforeach
        </div>
        @endif

        @if(@$center->center_highlights)
        <h3 class="rd-subhead">Highlights</h3>
        <div class="rd-measure">{!! $center->center_highlights !!}</div>
        @endif

        @if(sizeof((array)@$amenities) > 0)
        <h3 class="rd-subhead">Amenities</h3>
        <div class="rd-tag-row">
            @foreach(@$amenities as $amenity)
            <span class="rd-chip">
                @if(@$amenity->image_url)
                {{-- Same host every other amenity icon on the site uses (content-deal-experience,
                     payment, reservation, etc.). It's currently returning 403 "account is disabled" —
                     a pre-existing, site-wide Azure storage outage, not specific to this page. The
                     onerror hides a broken icon gracefully rather than leaving the glyph once it fires. --}}
                <img class="rd-chip-icon" src="{{ 'https://pub-2f883e7452554ee2bbe1b3d44d2a8715.r2.dev/balancegurus/'.rawurlencode($amenity->image_url) }}" alt="" onerror="this.style.display='none'" />
                @endif
                {{ $amenity->name }}
            </span>
            @endforeach
        </div>
        @endif

        @if(sizeof((array)@$center_locations) > 0)
        <h3 class="rd-subhead">Nearby</h3>
        <div class="rd-tag-row">
            @foreach(@$center_locations as $center_location)
            <span class="rd-chip">{{ $center_location->name }}</span>
            @endforeach
        </div>
        @endif

        @if(@$center->how_to_get_there || @$center->airport_name || @$center->pickup_drop_cost)
        <h3 class="rd-subhead">Getting There</h3>
        @if(@$center->airport_name || @$center->pickup_drop_cost)
        <dl class="rd-terms-grid" style="grid-template-columns:1fr 1fr;">
            @if(@$center->airport_name)<div><dt>Nearest airport</dt><dd>{{ $center->airport_name }}</dd></div>@endif
            @if(@$center->pickup_drop_cost)<div style="border-bottom:none;"><dt>Pickup / drop</dt><dd class="rd-tnum">{{ \App\Http\Helpers\CommonHelper::get_currency_rate($center->pickup_drop_cost, $site_currency) }}</dd></div>@endif
        </dl>
        @endif
        @if(@$center->how_to_get_there)
        <div class="rd-measure" style="margin-top:14px;">{!! $center->how_to_get_there !!}</div>
        @endif
        @endif

        @if(@$center->things_to_do_around_the_center)
        <h3 class="rd-subhead">Things to Do Nearby</h3>
        <div class="rd-measure">{!! $center->things_to_do_around_the_center !!}</div>
        @endif

        @if(@$experience->booking_info)
        <h3 class="rd-subhead">Good to Know</h3>
        <div class="rd-measure">{!! $experience->booking_info !!}</div>
        @endif

        @if(@$center->address_of_center || @$experience->location)
        <div class="rd-map-box">
            <div class="rd-map-pin"></div>
            <span class="rd-map-label">{{ @$center->address_of_center }} {{ @$experience->location }}</span>
        </div>
        @endif

        <div class="rd-contact-row">
            @if(@$center->email_address)<a href="mailto:{{ $center->email_address }}">{{ $center->email_address }}</a>@endif
            @if(@$center->contact_number)<a href="tel:{{ $center->contact_number }}">{{ $center->contact_number }}</a>@endif
            @if(@$center->whatsapp_number)<a href="https://wa.me/{{ preg_replace('/\D+/', '', $center->whatsapp_number) }}" target="_blank">WhatsApp</a>@endif
            @if(@$center->website)<a href="{{ $center->website }}" target="_blank">Website</a>@endif
            @if(@$center->facebook_url)<a href="{{ $center->facebook_url }}" target="_blank">Facebook</a>@endif
            @if(@$center->instagram_url)<a href="{{ $center->instagram_url }}" target="_blank">Instagram</a>@endif
        </div>
      </section>
      @endif

      {{-- REVIEWS --}}
      @if(@$center->bg_id)
      <section id="reviews">
        <h2>Reviews</h2>
        <div class="rd-reviews-wrap">
            <iframe src="https://balancegurus.com/embed/reviews?listing_id=<?php echo @$center->bg_id;?>" width="100%" height="600" style="border:none;"></iframe>
        </div>
      </section>
      @endif

    </div>

    </div>{{-- /.rd-left-col --}}

    {{-- Desktop sticky booking sidebar — real partial, re-skinned via CSS above --}}
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

  <footer class="rd-credit">{{ @$experience->name }} &middot; hosted by {{ @$center->name }} &middot; via Balanceboat</footer>

  {{-- Mobile persistent booking bar + drawer — real partial, re-skinned via CSS above.
       Deliberately kept INSIDE .rd-page (matches .rd-page structure) — every .rd-page .xd-mobile-*
       rule above depends on this ancestor to match at all. --}}
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

{{-- Room photo lightbox (item 5 — accommodation images popup). Single reusable overlay: each
     .rd-room-photos block below carries its own images as a data-images JSON attribute; the click
     handler in the footer script reads that attribute and repopulates this same overlay. --}}
<div class="rd-lightbox" id="rd-lightbox">
    <div class="rd-lightbox-title" id="rd-lightbox-title"></div>
    <div class="rd-lightbox-stage">
        <button type="button" class="rd-lightbox-close" id="rd-lightbox-close" aria-label="Close">&#10005;</button>
        <button type="button" class="rd-lightbox-prev" id="rd-lightbox-prev" aria-label="Previous photo">&#10094;</button>
        <img id="rd-lightbox-img" src="" alt="" />
        <button type="button" class="rd-lightbox-next" id="rd-lightbox-next" aria-label="Next photo">&#10095;</button>
    </div>
    <div class="rd-lightbox-count" id="rd-lightbox-count"></div>
</div>
@endsection

@section('footer')
<script src="{{asset('public/basicfront/js/jquery.validate.min.js')}}" defer></script>
<script type="text/javascript">
    // Real per-date status map (open/few_left/full/closed), same data as the static Availability
    // section. Feeds the date-picker popup and the "Check Availability" modal calendar.
    window.RD_AVAILABILITY = <?php echo json_encode($avByDate); ?>;

    // Builds one month of the availability calendar into `container` (any element). Shared by the
    // date-picker popup (clickable) and the read-only calendar in the "Check Availability" modal.
    // Uses the same .xd-avail-* classes/markup shape as partials.experience-availability so all three
    // calendars (static section, popup, modal) look identical.
    function rdBuildCalendar(container, opts) {
        opts = opts || {};
        var avail = opts.data || {};
        var keys = Object.keys(avail).sort();
        var start = keys.length ? new Date(keys[0] + 'T00:00:00') : new Date();
        var current = new Date(start.getFullYear(), start.getMonth(), 1);

        function statusClass(s) { return s === 'open' ? 'is-open' : (s === 'few_left' ? 'is-few' : 'is-full'); }
        function pad(n) { return n < 10 ? '0' + n : '' + n; }
        function fmtKey(y, m, d) { return y + '-' + pad(m + 1) + '-' + pad(d); }

        function render() {
            var y = current.getFullYear(), m = current.getMonth();
            var daysInMonth = new Date(y, m + 1, 0).getDate();
            var firstDow = new Date(y, m, 1).getDay();
            var leading = (firstDow + 6) % 7; // Monday-first, matching the static section's grid
            var monthLabel = current.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            var today = new Date(); today.setHours(0, 0, 0, 0);

            var html = '<div class="xd-avail-cal-head">'
                + '<button type="button" class="rd-cal-nav-btn" data-nav="-1" aria-label="Previous month">&#8249;</button>'
                + '<span class="xd-avail-cal-month">' + monthLabel + '</span>'
                + '<button type="button" class="rd-cal-nav-btn" data-nav="1" aria-label="Next month">&#8250;</button>'
                + '</div>';
            html += '<div class="xd-avail-weekdays"><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span></div>';
            html += '<div class="xd-avail-grid">';
            for (var i = 0; i < leading; i++) html += '<div class="xd-avail-day xd-avail-day--blank"></div>';
            for (var d = 1; d <= daysInMonth; d++) {
                var key = fmtKey(y, m, d);
                var status = avail[key];
                var isPast = new Date(y, m, d) < today;
                var cls = 'xd-avail-day';
                if (status) cls += ' ' + statusClass(status);
                if (isPast) cls += ' xd-avail-day--past';
                if (opts.clickable && !isPast) cls += ' rd-cal-day-pick';
                if (opts.selected === key) cls += ' rd-cal-day-selected';
                html += '<div class="' + cls + '"' + (opts.clickable && !isPast ? ' data-date="' + key + '"' : '') + (status ? ' title="' + status.replace('_', ' ') + '"' : '') + '>' + d + '</div>';
            }
            html += '</div>';
            html += '<div class="xd-avail-legend">'
                + '<span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#16a34a;"></span> Open</span>'
                + '<span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#a16207;"></span> Few Left</span>'
                + '<span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#dc2626;"></span> Full / Closed</span>'
                + '</div>';
            container.innerHTML = html;

            Array.prototype.forEach.call(container.querySelectorAll('[data-nav]'), function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    current.setMonth(current.getMonth() + parseInt(btn.getAttribute('data-nav'), 10));
                    render();
                });
            });
            if (opts.clickable) {
                Array.prototype.forEach.call(container.querySelectorAll('.rd-cal-day-pick'), function(cell) {
                    cell.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (opts.onPick) opts.onPick(cell.getAttribute('data-date'));
                    });
                });
            }
        }
        render();
    }

    // Wires every .rd-date-input (desktop sidebar + mobile drawer) to its sibling .rd-cal-popup.
    (function() {
        var inputs = document.querySelectorAll('.rd-date-input');
        Array.prototype.forEach.call(inputs, function(input) {
            var popup = input.parentElement.querySelector('.rd-cal-popup');
            if (!popup) return;
            var built = false;
            input.addEventListener('click', function(e) {
                e.stopPropagation();
                if (!built) {
                    rdBuildCalendar(popup, {
                        data: window.RD_AVAILABILITY,
                        clickable: true,
                        selected: input.value,
                        onPick: function(dateStr) {
                            input.value = dateStr;
                            popup.classList.remove('active');
                            input.dispatchEvent(new Event('change'));
                        }
                    });
                    built = true;
                }
                document.querySelectorAll('.rd-cal-popup.active').forEach(function(p) { if (p !== popup) p.classList.remove('active'); });
                popup.classList.toggle('active');
            });
        });
        document.addEventListener('click', function(e) {
            if (e.target.closest('.rd-datefield-group')) return;
            document.querySelectorAll('.rd-cal-popup.active').forEach(function(p) { p.classList.remove('active'); });
        });
    })();

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

            // Solo/Couple is captured as guest_type on the reservation form; it does not change the
            // calculated price (room pricing here is a flat per-room rate, not per-occupancy), so no
            // calculatePrice() call is needed here.
            $form.find('.qb-guest-type-pills').on('click', '.xd-pill-btn', function() {
                $form.find('.qb-guest-type-pills .xd-pill-btn').removeClass('active');
                $(this).addClass('active');
                $form.find('.qb-guest-type-value').val($(this).data('value'));
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
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) xdCloseDrawer();
            });
        }
    });

    // Scroll-spy for the in-page section nav. Re-runs on 'load' and once more just past the 3000ms
    // mark: lazy-loaded images can still be resizing the page shortly after 'load', so a single early
    // calculation can under/over-shoot.
    function rdInitPageNavScrollSpy() {
        var nav = document.getElementById('rd-page-nav');
        if (!nav) return;
        var tabs = Array.prototype.slice.call(nav.querySelectorAll('a'));
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
        window.addEventListener('load', setActiveOnScroll);
        setTimeout(setActiveOnScroll, 3200);
        setActiveOnScroll();
    }
    document.addEventListener('DOMContentLoaded', rdInitPageNavScrollSpy);

    // Keep the section nav's sticky offset glued to the SITE header's actual bottom edge, instead of
    // assuming it's always a fixed 80px. The global <header> (position:sticky) stops sticking and
    // scrolls out of view once its own containing block is scrolled past — a pre-existing site
    // behavior. A hardcoded `top: 80px` on .rd-page-nav would then leave a stale 80px gap once the
    // header is gone, with page content bleeding up through it. Re-measuring the header on every
    // scroll/resize keeps the nav pinned directly under wherever the header currently ends (or top:0
    // once it's gone).
    function rdSyncNavTop() {
        var header = document.querySelector('header');
        var nav = document.getElementById('rd-page-nav');
        if (!header || !nav) return;
        function sync() {
            var headerBottom = Math.max(0, Math.round(header.getBoundingClientRect().bottom));
            nav.style.top = headerBottom + 'px';
        }
        window.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', sync);
        window.addEventListener('load', sync);
        sync();
    }
    document.addEventListener('DOMContentLoaded', rdSyncNavTop);

    // Room photo lightbox (item 5). Delegated so it also covers rooms rendered after DOMContentLoaded
    // (none currently, but keeps this resilient) and both the strip images and the count button.
    (function() {
        var lightbox = document.getElementById('rd-lightbox');
        if (!lightbox) return;
        var imgEl = document.getElementById('rd-lightbox-img');
        var titleEl = document.getElementById('rd-lightbox-title');
        var countEl = document.getElementById('rd-lightbox-count');
        var images = [];
        var title = '';
        var index = 0;

        function render() {
            if (!images.length) return;
            imgEl.src = images[index];
            imgEl.alt = title;
            countEl.textContent = (index + 1) + ' / ' + images.length;
        }
        function open(imgs, startIndex, roomTitle) {
            images = imgs;
            index = startIndex || 0;
            title = roomTitle || '';
            titleEl.textContent = title;
            render();
            lightbox.classList.add('active');
        }
        function close() {
            lightbox.classList.remove('active');
        }
        function step(delta) {
            if (!images.length) return;
            index = (index + delta + images.length) % images.length;
            render();
        }

        document.querySelectorAll('[data-room-gallery]').forEach(function(block) {
            var imgs;
            try { imgs = JSON.parse(block.getAttribute('data-images') || '[]'); } catch (e) { imgs = []; }
            if (!imgs.length) return;
            var roomTitle = block.getAttribute('data-title') || '';
            var opener = function(e) {
                var clickedImg = e.target.closest('img');
                var startIndex = 0;
                if (clickedImg) {
                    var all = Array.prototype.slice.call(block.querySelectorAll('img'));
                    startIndex = Math.max(0, all.indexOf(clickedImg));
                }
                open(imgs, startIndex, roomTitle);
            };
            block.querySelectorAll('img').forEach(function(img) {
                img.addEventListener('click', opener);
            });
            var btn = block.querySelector('.rd-room-gallery-btn');
            if (btn) btn.addEventListener('click', opener);
        });

        document.getElementById('rd-lightbox-close').addEventListener('click', close);
        document.getElementById('rd-lightbox-prev').addEventListener('click', function() { step(-1); });
        document.getElementById('rd-lightbox-next').addEventListener('click', function() { step(1); });
        lightbox.addEventListener('click', function(e) { if (e.target === lightbox) close(); });
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') step(-1);
            if (e.key === 'ArrowRight') step(1);
        });
    })();

    <?php
    if (!empty($razorPayAmount)) {
        if (\App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - @$discount, $site_currency, false) > 0) {?>

            const key = "rzp_live_wp4Xjvh9X9GOYe";
            const amount = <?php echo $razorPayAmount*100; ?>

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
<script defer src="https://cdn.razorpay.com/widgets/affordability/affordability.js"></script>
@endsection
