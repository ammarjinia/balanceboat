{{-- Extracted from experience_detail.blade.php's original inline "Choose Your Room" + "Price Options" blocks (Task 7).
     Stacked cards only (locked design decision — no tabs). Mechanical move of DISPLAY markup only.
     $roomPricing is intentionally still computed in the parent view (not here): it is also consumed by
     partials/experience-booking-fields.blade.php (shared desktop/mobile booking form, Task 14/15) further down
     the parent, and Blade @include does not leak variables set inside a partial back to the parent scope.
     This partial only reads: $experience_accomodations, $accomodationimagegalleries, $roomPricing,
     $experience_availability_next, $experience_accommodation_duration_prices, $experience_durations, $experience —
     all already defined by the parent before this include runs. --}}

{{-- Choose Your Room (room/accommodation cards) --}}
@if(sizeof(@$experience_accomodations) > 0)
<div class="xd-card xd-pkg-group" id="accomodation-rooms">
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

                <?php $rp = $roomPricing[$experience_accomodation->id]; ?>
                <div class="xd-room-bottom">
                    @if($rp['hasFlat'])
                    <div class="xd-room-price-block">
                        <small>Total for {{ @$experience_accomodation->duration }}</small>
                        <div class="xd-room-price">
                            @if(!empty($rp['discount']))
                            <del>{{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['pay'], $rp['currency']) }}</del>
                            @endif
                            <span>{{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['pay'] - $rp['discount'], $rp['currency']) }}</span>
                        </div>
                    </div>
                    @elseif($rp['fallback'])
                    <div class="xd-room-price-block">
                        <small>Starting from</small>
                        <div class="xd-room-price">
                            <span>{{ \App\Http\Helpers\CommonHelper::get_currency_rate($rp['fallback'], $rp['currency']) }}</span>
                        </div>
                    </div>
                    @else
                    <div class="xd-room-price-block"></div>
                    @endif
                    <div class="xd-room-cta">
                        <button type="button" class="xd-btn-gradient xd-btn-sm" onclick="xdSelectRoom('{{ $experience_accomodation->id }}')">Select Room</button>
                        <button type="button" data-popup="requstcallPopup" class="show-bg-modal xd-btn-outline xd-btn-sm">Send Inquiry</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Price Options (duration price table) --}}
@if(@$experience_durations && sizeof(@$experience_durations) > 0)
<div class="xd-card xd-pkg-group" id="price-options">
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
