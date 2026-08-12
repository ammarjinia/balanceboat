{{-- Extracted from experience_detail.blade.php's original inline Gallery block (Task 4). Mechanical move only:
     same $experience/$imagegalleries variables, same $i/$gi fallback logic (banner substituted into the grid
     when fewer than 4 gallery images exist), same #bg-gallery-all / .bg-listing-gallery-items DOM hooks that
     public/basicfront/js/script.js binds globally via querySelector — no ID/class renamed. --}}
<section class="pt-4">
    <div class="xd-container">
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
