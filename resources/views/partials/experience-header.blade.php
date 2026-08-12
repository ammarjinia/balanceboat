{{-- Extracted from experience_detail.blade.php's original inline Hero block (Task 3). Mechanical move of DISPLAY markup only.
     $discount, $pay, and $razorPayAmount are intentionally still computed in the parent view (not here) because
     $razorPayAmount is consumed later by the footer Razorpay-widget script, and Blade @include does not leak
     variables set inside a partial back out to the parent scope. This partial only reads variables already
     defined by the parent before this include runs: $experience, $center, $category, $experienceList,
     $site_currency, $discount, $pay. --}}
<section class="pt-2">
    <div class="xd-container">
        <div class="xd-hero-banner">
            @if(@$experience->banner_image_url)
            <div class="xd-hero-bg" style="background-image:url('{{ strtok(Storage::disk('s3')->url(rawurlencode(@$experience->banner_image_url)),'?') }}');"></div>
            @endif
            <div class="xd-hero-overlay"></div>
            <div class="xd-hero-top">
                <div class="d-flex align-items-center xd-header-badges" style="gap:8px; flex-wrap:wrap;">
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
                    @if(@$experienceList->min_duration_price)
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
