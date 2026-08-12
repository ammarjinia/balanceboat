{{-- Extracted from experience_detail.blade.php's original inline "Upcoming Availability" block (Task 9).
     Restyled as a list (not a month-grid calendar — user decision: our data is a flat list of ~20 upcoming
     start_date rows, not full month-by-month occupancy, so a list matches the actual data shape without
     inventing a bigger calendar UI). Same $experience_upcoming_availability/$experience_accomodations
     variables, same @if guard, same status logic — mechanical move + class restyle only. --}}
@if(@$experience_upcoming_availability && sizeof(@$experience_upcoming_availability) > 0)
<div class="xd-card" id="availability">
    <span class="xd-tag">Plan Ahead</span>
    <h2 class="xd-title"><span class="xd-title-icon">&#128197;</span> Upcoming Availability</h2>
    <div class="xd-avail-list">
        @foreach(@$experience_upcoming_availability as $avail)
        <?php
            $avAcm = null;
            foreach (@$experience_accomodations as $ea_lookup) {
                if ($ea_lookup->id == $avail->accommodation_id) { $avAcm = $ea_lookup; break; }
            }
        ?>
        <div class="xd-routine-item">
            <span class="xd-routine-time">{{ \Carbon\Carbon::parse($avail->start_date)->format('d M Y') }}</span>
            <div class="xd-routine-desc" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <span>{{ $avAcm ? $avAcm->name : '-' }}</span>
                <span class="xd-badge {{ $avail->status == 'open' ? 'xd-badge-open' : ($avail->status == 'few_left' ? 'xd-badge-warn' : 'xd-badge-danger') }}">
                    {{ \App\ExperienceAccommodationAvailability::statusLabel($avail->status) }}
                    @if(in_array($avail->status, ['open', 'few_left']))
                    &middot; {{ $avail->remaining }} left
                    @endif
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
