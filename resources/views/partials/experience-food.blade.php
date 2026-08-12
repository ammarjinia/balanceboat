{{-- Extracted from experience_detail.blade.php's original inline "Food Overview" block (Task 8). Mechanical
     move only: same $foodimagegalleries/$experience->food_banner_image_url/$experience->food_overview variables,
     same @if guard (renders only when overview text OR banner OR gallery images exist).
     Per user decision: no "Sample Day's Menu" table is rendered here — no structured meal-schedule data
     exists in this app (only free-text food_overview + an image gallery), so none is fabricated. --}}
@if((sizeof(@$foodimagegalleries->toArray())>0) OR (@$experience->food_banner_image_url) OR (@$experience->food_overview))
<div id="food-overview" class="xd-card deal-gallery">
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
