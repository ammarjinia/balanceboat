<?php
$__firstDuration = (@$experience_durations && sizeof(@$experience_durations) > 0) ? $experience_durations[0]->duration : @$experience->duration;
$__firstAccomId = (@$experience_accomodations && sizeof(@$experience_accomodations) > 0) ? $experience_accomodations[0]->id : '';
?>
<div class="xd-form-group">
    <label class="xd-form-label">Duration</label>
    <input type="hidden" name="durations" class="qb-durations-value" value="{{ $__firstDuration }}" />
    @if(@$experience_durations && sizeof(@$experience_durations) > 0)
    <div class="xd-pill-group qb-duration-pills">
        @foreach($experience_durations as $i => $ed)
        <button type="button" class="xd-pill-btn {{ $i == 0 ? 'active' : '' }}" data-value="{{ $ed->duration }}">{{ $ed->duration }} Days</button>
        @endforeach
    </div>
    @endif
</div>

<div class="xd-form-group">
    <label class="xd-form-label">Select Room</label>
    <input type="hidden" name="exp_accomodation_id" class="qb-accomodation-value" value="{{ $__firstAccomId }}" />
    @if(@$experience_accomodations && sizeof(@$experience_accomodations) > 0)
    <div class="xd-room-picker-list qb-room-picker-list">
        @foreach($experience_accomodations as $i => $racm)
        <?php $rp = $roomPricing[$racm->id] ?? array('hasFlat' => false, 'pay' => 0, 'discount' => 0, 'fallback' => null, 'currency' => @$racm->currency, 'thumb' => null); ?>
        <div class="xd-room-picker-item {{ $i == 0 ? 'active' : '' }}" data-value="{{ $racm->id }}">
            @if($rp['thumb'])
            <img class="lazy xd-room-picker-thumb" data-src="{{ strtok(Storage::disk('s3')->url(rawurlencode($rp['thumb'])),'?') }}" alt="{{ $racm->name }}" />
            @endif
            <div class="xd-room-picker-details">
                <span class="xd-room-picker-name">{{ $racm->name }}</span>
                @if($rp['hasFlat'] || $rp['fallback'])
                <span class="xd-room-picker-price">
                    @if($rp['hasFlat'])
                        @if(!empty($rp['discount']))<del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['pay'], $rp['currency']) }}</del>@endif
                        {{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['pay'] - $rp['discount'], $rp['currency']) }}
                    @else
                        From {{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['fallback'], $rp['currency']) }}
                    @endif
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
