<div class="strip_all_tour_list wow fadeIn item" <?php if($teacher->featured_experiences > 0) {?> style='border:1px solid red;padding:5px;' <?php }?> >
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="img_list">
                <a href="{{ url("/teacher/".$teacher->slug) }}">
                    <img data-src="{{ ($teacher->profile_image_url) ? Storage::disk('s3')->url(rawurlencode($teacher->profile_image_url)) : '' }}" alt="{{ $teacher->profile_image_url }}" class="img-responsive lazy" /> 
                </a>
            </div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="col-lg-8 col-md-8 col-sm-8 text-left">
            <div class="tour_list_desc" style="height:auto;">
                <a href="{{ url("/teacher/".$teacher->slug) }}"><h3 style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">{{ $teacher->name }}</h3></a>
                <?php if (@$teacher->short_description) { ?>
                <div style="height:120px;overflow:hidden;">
                    {!! @$teacher->short_description !!}
                </div>    
                <?php } ?>
                <?php
                    $expAr = explode("||", @$teacher->expertise_id);
                    $ExpNames = \App\Expertise::select("name")->whereIn("id", $expAr)->get()->pluck("name");
                    if (!empty($ExpNames)) {
                        echo $ExpNames->implode(', ');
                    } else {
                        echo "N/A";
                    }
                ?>
                @if(!empty(@$teacher->certificates))
                    <div class="head-price mb-1">
                        <div>
                            <span class="fs-14 c-medium">{{ @$teacher->certificates }}</span>
                        </div>
                      </div>
                @endif
                <a href="{{ url("teacher/".$teacher->slug) }}" class="article-text">{!! \App\Http\Helpers\CommonHelper::excerpt(strip_tags(@$teacher->complete_bio), 100) !!}</a>
            </div>
        </div>
    </div>
</div>
