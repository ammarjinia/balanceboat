{{-- Header restructured (visual-parity pass, corrected against the reference at full resolution): the
     reference's title/meta/badges are plain text on the page background, ABOVE a separate photo gallery —
     not overlaid on a dark-gradient hero photo. Removed the .xd-hero-banner photo-overlay treatment;
     $experience->banner_image_url is already rendered as the first gallery tile by
     partials/experience-gallery.blade.php, so no image data is lost, only the presentation changed.
     Order matches the reference: title -> meta (location/host/price) -> badges row.
     Same variables as before: $experience, $center, $category, $experienceList, $site_currency, $discount, $pay
     (still computed in the parent view, not here — $razorPayAmount is read again later by the footer
     Razorpay-widget script, per the original scope-leak-avoidance note this partial inherited from Task 3). --}}
<section class="pt-4">
    <div class="xd-container">
        <div class="xd-header-top-row">
            <h1 class="xd-header-title">{{ @$experience->name }}</h1>
            <div class="xd-header-actions">
                <div class="bg-menu-list">
                    <span class="xd-header-action-btn"><span class="icon-share"></span> Share</span>
                    <ul class="bg-box horiz">
                        <li><a target="_blank" href="https://www.facebook.com/balanceboat"><span class="icon-facebook"></span></a></li>
                        <li><a target="_blank" href="https://www.pinterest.com/balanceboat"><span class="icon-pinterest"></span></a></li>
                    </ul>
                </div>
                <a href="#booking-card" class="xd-header-action-btn"><span class="icon-compass"></span> Reserve</a>
            </div>
        </div>

        <div class="xd-header-meta">
            @if(@$center->address_of_center || @$experience->location)
            <span><span class="icon-location"></span> {{ @$center->address_of_center }} {{ @$experience->location }}</span>
            @endif
            @if(@$center->name)
            <span>Hosted by {{ @$center->name }}</span>
            @endif
            @if(@$experienceList->min_duration_price)
            <span class="xd-header-price">
                From
                @if(!empty($discount))
                <del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate((@$pay), $site_currency) }}</del>
                @endif
                <strong>{{ \App\Http\Helpers\CommonHelper::get_currency_rate(@$pay - $discount, $site_currency) }}</strong>
            </span>
            @endif
        </div>

        <div class="xd-header-badges">
            @if($category)
            <span class="xd-plain-tag">{{ $category }}</span>
            @endif
            @include('partials.commission-tier-badge')
        </div>
    </div>
</section>
