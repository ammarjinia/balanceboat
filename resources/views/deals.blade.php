@extends('layouts.deals_layout')
@section('title', "Best Deals")
@section('meta_title', "Best Deals")
@section('description', "Explore handpicked savings on retreats and trainings, grouped by focus area, duration, and regional specialty.")
@section('head')
<style type="text/css">
    .bd-page {
        --bd-brand: var(--brand-color, #ff3366);
        --bd-brand-dark: var(--brand-color-dark, #e62050);
        --bd-brand-light: rgba(255, 51, 102, 0.06);
        --bd-brand-border: rgba(255, 51, 102, 0.2);
        --bd-text-main: var(--primary-color, #111827);
        --bd-text-body: #4b5563;
        --bd-text-muted: var(--c-medium, #6b7280);
        --bd-border: var(--border-1, #e5e7eb);
        --bd-surface: var(--c-white, #fff);
        --bd-subtle: var(--c-light, #f8f9fa);
        --bd-radius-xl: 20px;
        --bd-radius-lg: 14px;
        --bd-radius-md: var(--radius-8, 10px);
        --bd-radius-pill: var(--button-radius, 100px);
        --bd-shadow-card: 0 4px 20px rgba(0, 0, 0, 0.05);
        --bd-shadow-float: 0 15px 35px rgba(0, 0, 0, 0.08);
        background: #fff;
        padding-bottom: 40px;
    }

    .bd-wrap { max-width: 1240px; margin: 0 auto; padding: 0 clamp(18px, 3.4vw, 40px); }

    /* Hero */
    .bd-hero { padding: 34px 0 22px; background: #fff; }
    .bd-hero-badge {
        display: inline-flex; align-items: center; gap: 6px; background: var(--bd-brand-light); color: var(--bd-brand);
        border: 1px solid var(--bd-brand-border); font-size: 11px; font-weight: bold; text-transform: uppercase;
        letter-spacing: 1px; padding: 5px 14px; border-radius: var(--bd-radius-pill); margin-bottom: 14px;
    }
    .bd-hero h1 { font-size: 34px; font-weight: 800; color: var(--bd-text-main); line-height: 1.2; margin-bottom: 10px; }
    .bd-hero h1 span { color: var(--bd-brand); }
    .bd-hero p { font-size: 15px; color: var(--bd-text-body); max-width: 640px; margin: 0; }

    /* Filter bar (desktop) */
    .bd-filter-bar {
        background: var(--bd-surface); border: 1px solid var(--bd-border); border-radius: var(--bd-radius-xl);
        box-shadow: var(--bd-shadow-float); padding: 20px; margin: 24px 0; position: sticky; top: 16px; z-index: 20;
    }
    .bd-filter-grid { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 12px; align-items: end; }
    .bd-filter-group label {
        display: block; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;
        color: var(--bd-text-muted); margin-bottom: 6px;
    }
    .bd-filter-group input, .bd-filter-group select {
        width: 100%; background: var(--bd-subtle); border: 1px solid var(--bd-border); border-radius: var(--bd-radius-md);
        padding: 9px 12px; font-size: 13px; color: var(--bd-text-main);
    }
    .bd-filter-group input:focus, .bd-filter-group select:focus { outline: none; border-color: var(--bd-brand); }
    .bd-filter-submit {
        background: linear-gradient(135deg, var(--bd-brand) 0%, var(--bd-brand-dark) 100%); color: #fff; border: none;
        padding: 9px 20px; border-radius: var(--bd-radius-pill); font-size: 13px; font-weight: bold; cursor: pointer;
        white-space: nowrap;
    }
    .bd-filter-foot {
        margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--bd-subtle); display: flex;
        align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    }
    .bd-chip {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: var(--bd-radius-md);
        font-size: 12px; font-weight: 600; background: var(--bd-brand-light); color: var(--bd-brand); border: 1px solid var(--bd-brand-border);
        text-decoration: none;
    }
    .bd-count { font-size: 12px; font-weight: bold; color: var(--bd-text-muted); }

    /* Mobile filter summary */
    .bd-mobile-summary {
        display: none; margin: 0 0 18px; align-items: center; justify-content: space-between; background: var(--bd-surface);
        border: 1px solid var(--bd-border); border-radius: var(--bd-radius-md); padding: 10px 14px;
    }
    .bd-mobile-summary span { font-size: 12px; color: var(--bd-text-muted); }
    .bd-mobile-summary a { font-size: 12px; font-weight: bold; color: var(--bd-brand); text-decoration: underline; }

    /* Card grid */
    .bd-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; margin-top: 8px; }
    .bd-card {
        background: var(--bd-surface); border: 1px solid var(--bd-border); border-radius: var(--bd-radius-xl);
        overflow: hidden; box-shadow: var(--bd-shadow-card); display: flex; flex-direction: column; transition: box-shadow .25s ease, transform .25s ease;
    }
    .bd-card:hover { box-shadow: var(--bd-shadow-float); transform: translateY(-3px); }
    .bd-card-media { position: relative; height: 220px; overflow: hidden; background: var(--bd-subtle); }
    .bd-card-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
    .bd-card:hover .bd-card-media img { transform: scale(1.06); }
    .bd-card-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(17,24,39,0) 45%, rgba(17,24,39,0.82) 100%); }
    .bd-card-badge {
        position: absolute; top: 14px; left: 14px; background: var(--bd-brand); color: #fff; font-size: 11px;
        font-weight: bold; padding: 5px 12px; border-radius: var(--bd-radius-pill); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .bd-card-badge-alt {
        position: absolute; top: 14px; right: 14px; background: rgba(255,255,255,0.92); color: var(--bd-text-main); font-size: 11px;
        font-weight: bold; padding: 5px 12px; border-radius: var(--bd-radius-pill);
    }
    .bd-card-title { position: absolute; left: 16px; right: 16px; bottom: 14px; color: #fff; font-size: 19px; font-weight: 700; line-height: 1.25; }
    .bd-card-body { padding: 20px; display: flex; flex-direction: column; gap: 14px; flex: 1; }
    .bd-card-desc { font-size: 13px; color: var(--bd-text-body); line-height: 1.55; flex: 1; }
    .bd-card-foot { padding-top: 14px; border-top: 1px solid var(--bd-subtle); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .bd-card-foot small { display: block; font-size: 10px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; color: var(--bd-text-muted); }
    .bd-card-foot strong { font-size: 13px; color: var(--bd-text-main); }
    .bd-card-cta {
        background: var(--bd-text-main); color: #fff; font-size: 12px; font-weight: bold; padding: 10px 18px;
        border-radius: var(--bd-radius-md); text-decoration: none; white-space: nowrap; transition: background .2s ease;
    }
    .bd-card:hover .bd-card-cta { background: var(--bd-brand); }

    .bd-empty { text-align: center; padding: 60px 20px; color: var(--bd-text-muted); }
    .bd-empty a { color: var(--bd-brand); font-weight: bold; text-decoration: underline; }

    /* Mobile sticky bar + drawer */
    .bd-mobile-bar { display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 1500; justify-content: center; padding: 14px; }
    .bd-mobile-bar-inner {
        background: rgba(17,24,39,0.95); border-radius: var(--bd-radius-pill); padding: 8px; display: flex; gap: 8px;
        width: 100%; max-width: 420px; box-shadow: var(--bd-shadow-float);
    }
    .bd-mobile-bar-btn {
        flex: 1; background: linear-gradient(135deg, var(--bd-brand) 0%, var(--bd-brand-dark) 100%); color: #fff;
        border: none; padding: 12px; border-radius: var(--bd-radius-pill); font-size: 13px; font-weight: bold; cursor: pointer;
    }
    .bd-mobile-drawer-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 2100; display: none; align-items: flex-end;
    }
    .bd-mobile-drawer-overlay.active { display: flex; }
    .bd-mobile-drawer { background: var(--bd-surface); border-top-left-radius: 22px; border-top-right-radius: 22px; padding: 22px 18px 18px; width: 100%; max-height: 85vh; overflow-y: auto; }
    .bd-drawer-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .bd-drawer-close { background: none; border: none; font-size: 16px; color: var(--bd-text-muted); cursor: pointer; }

    @media (max-width: 991px) {
        .bd-filter-bar { display: none; }
        .bd-mobile-summary { display: flex; }
        .bd-mobile-bar { display: flex; }
        .bd-grid { grid-template-columns: 1fr 1fr; }
        body { padding-bottom: 90px; }
    }
    @media (max-width: 640px) {
        .bd-hero h1 { font-size: 26px; }
        .bd-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
@section('content')
<div class="bd-page">

    <section class="bd-hero">
        <div class="bd-wrap">
            <span class="bd-hero-badge"><span class="icon-fire"></span> Exclusive Savings &amp; Curated Packages</span>
            <h1>Curated <span>Retreat Deal</span> Collections</h1>
            <p>Explore handpicked savings grouped by focus area, duration, and regional specialty.</p>
        </div>
    </section>

    <div class="bd-wrap">

        {{-- Desktop filter bar --}}
        <form class="bd-filter-bar" method="GET" action="{{ url('/best-deals') }}">
            <div class="bd-filter-grid">
                <div class="bd-filter-group">
                    <label for="bd-q">Keywords</label>
                    <input type="text" id="bd-q" name="q" value="{{ $q }}" placeholder="Search deal collections..." />
                </div>
                <div class="bd-filter-group">
                    <label for="bd-destination">Destination</label>
                    <select id="bd-destination" disabled>
                        <option>All Destinations</option>
                        @foreach($locations as $loc)
                        <option>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bd-filter-group">
                    <label for="bd-category">Retreat Focus</label>
                    <select id="bd-category" disabled>
                        <option>All Focus Areas</option>
                        @foreach($categories as $cat)
                        <option>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bd-filter-submit"><span class="icon-search"></span> Search</button>
            </div>
            <div class="bd-filter-foot">
                <div>
                    @if($q !== '')
                    <a href="{{ url('/best-deals') }}" class="bd-chip">Keywords: {{ $q }} <span class="icon-close"></span></a>
                    @else
                    <span class="bd-count">Destination &amp; Focus filters coming soon</span>
                    @endif
                </div>
                <span class="bd-count">Showing {{ count($objDeals) }} Collection{{ count($objDeals) == 1 ? '' : 's' }}</span>
            </div>
        </form>

        {{-- Mobile filter summary --}}
        <div class="bd-mobile-summary">
            <span>
                @if($q !== '')
                Filtered by &ldquo;{{ $q }}&rdquo; &middot; {{ count($objDeals) }} Collection{{ count($objDeals) == 1 ? '' : 's' }}
                @else
                {{ count($objDeals) }} Collection{{ count($objDeals) == 1 ? '' : 's' }} Available
                @endif
            </span>
            <a href="javascript:void(0);" onclick="bdOpenDrawer()">Search</a>
        </div>

        {{-- Deal collections grid --}}
        @if(count($objDeals) > 0)
        <div class="bd-grid">
            @foreach($objDeals as $objDeal)
            <?php
            $dealCount = @$dealExperienceCounts[$objDeal->id] ?? 0;
            $dealValidUntil = null;
            if (@$objDeal->end_date) {
                $todayStr = \Carbon\Carbon::parse(date("Y-m-d"))->format("Y-m-d");
                if (\Carbon\Carbon::parse($objDeal->end_date)->format("Y-m-d") >= $todayStr) {
                    $dealValidUntil = \Carbon\Carbon::parse($objDeal->end_date)->format('d M Y');
                }
            }
            ?>
            <a href="{{ url('/deal/'.$objDeal->slug) }}" class="bd-card">
                <div class="bd-card-media">
                    @if($objDeal->image_url)
                    <img class="lazy" src="{{ strtok(Storage::disk('s3')->url(rawurlencode($objDeal->image_url)),'?') }}" alt="{{ $objDeal->image_title ?: $objDeal->name }}" />
                    @endif
                    <div class="bd-card-overlay"></div>
                    @if($dealCount > 0)
                    <span class="bd-card-badge">{{ $dealCount }} Retreat{{ $dealCount == 1 ? '' : 's' }}</span>
                    @endif
                    @if($dealValidUntil)
                    <span class="bd-card-badge-alt">Till {{ $dealValidUntil }}</span>
                    @endif
                    <h3 class="bd-card-title">{{ $objDeal->name }}</h3>
                </div>
                <div class="bd-card-body">
                    @if($objDeal->description)
                    <p class="bd-card-desc">{{ \App\Http\Helpers\CommonHelper::excerpt(strip_tags(html_entity_decode($objDeal->description)), 150) }}</p>
                    @endif
                    <div class="bd-card-foot">
                        <div>
                            <small>Collection</small>
                            <strong>{{ $dealCount > 0 ? $dealCount.' Verified Retreat'.($dealCount == 1 ? '' : 's') : 'View Details' }}</strong>
                        </div>
                        <span class="bd-card-cta">Explore &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bd-empty">
            <p>No deal collections match &ldquo;{{ $q }}&rdquo; right now.</p>
            <a href="{{ url('/best-deals') }}">Clear search and view all collections</a>
        </div>
        @endif

    </div>

    {{-- Mobile sticky bar --}}
    <div class="bd-mobile-bar">
        <div class="bd-mobile-bar-inner">
            <button type="button" class="bd-mobile-bar-btn" onclick="bdOpenDrawer()">
                <span class="icon-search"></span> Search Collections
            </button>
        </div>
    </div>

    {{-- Mobile filter drawer --}}
    <div class="bd-mobile-drawer-overlay" id="bd-mobile-drawer-overlay">
        <div class="bd-mobile-drawer">
            <div class="bd-drawer-head">
                <strong>Search Collections</strong>
                <button type="button" class="bd-drawer-close" onclick="bdCloseDrawer()">&#10005;</button>
            </div>
            <form method="GET" action="{{ url('/best-deals') }}">
                <div class="bd-filter-group" style="margin-bottom:12px;">
                    <label for="bd-q-mobile">Keywords</label>
                    <input type="text" id="bd-q-mobile" name="q" value="{{ $q }}" placeholder="Search deal collections..." />
                </div>
                <div class="bd-filter-group" style="margin-bottom:12px;">
                    <label for="bd-destination-mobile">Destination</label>
                    <select id="bd-destination-mobile" disabled>
                        <option>All Destinations</option>
                        @foreach($locations as $loc)
                        <option>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bd-filter-group" style="margin-bottom:16px;">
                    <label for="bd-category-mobile">Retreat Focus</label>
                    <select id="bd-category-mobile" disabled>
                        <option>All Focus Areas</option>
                        @foreach($categories as $cat)
                        <option>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bd-filter-submit" style="width:100%;">Show Collections</button>
            </form>
        </div>
    </div>

</div>
@endsection
@section('js')
<script type="text/javascript">
    function bdOpenDrawer() {
        document.getElementById('bd-mobile-drawer-overlay').classList.add('active');
    }
    function bdCloseDrawer() {
        document.getElementById('bd-mobile-drawer-overlay').classList.remove('active');
    }
    document.addEventListener('DOMContentLoaded', function () {
        var overlay = document.getElementById('bd-mobile-drawer-overlay');
        overlay.addEventListener('click', function (e) {
            if (e.target === this) bdCloseDrawer();
        });
    });
</script>
@endsection
