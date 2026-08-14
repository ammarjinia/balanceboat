{{-- Availability section (Task 9, rebuilt in the visual-parity pass). Same $experience_upcoming_availability
     variable and same @if guard as before — no new backend query. The reference image shows a month-grid
     calendar, so this rebuilds that from the existing flat list of upcoming (start_date, accommodation_id,
     status, remaining) rows: rows are grouped by date, with the best status across accommodations shown per
     day (open > few_left > full/closed), for the month containing the earliest upcoming date. A secondary
     "Other upcoming start dates" list (the previous list-based rendering) is kept below the calendar for
     dates outside the displayed month, so no data is dropped by only showing one month. --}}
@if(@$experience_upcoming_availability && sizeof(@$experience_upcoming_availability) > 0)
<?php
    $avByDate = array();
    $statusRank = array('open' => 3, 'few_left' => 2, 'full' => 1, 'closed' => 1);
    foreach ($experience_upcoming_availability as $avail) {
        $d = \Carbon\Carbon::parse($avail->start_date)->format('Y-m-d');
        $rank = $statusRank[$avail->status] ?? 0;
        if (!isset($avByDate[$d]) || $rank > $statusRank[$avByDate[$d]]) {
            $avByDate[$d] = $avail->status;
        }
    }
    ksort($avByDate);
    $firstDateKey = array_key_first($avByDate);
    $calMonth = $firstDateKey ? \Carbon\Carbon::parse($firstDateKey)->startOfMonth() : \Carbon\Carbon::now()->startOfMonth();
    $daysInMonth = $calMonth->daysInMonth;
    $leadingBlanks = ($calMonth->copy()->startOfMonth()->dayOfWeekIso - 1); // Monday-first grid
    $statusClass = function ($status) {
        return $status == 'open' ? 'is-open' : ($status == 'few_left' ? 'is-few' : 'is-full');
    };
?>
<div class="xd-card" id="availability">
    <span class="xd-tag">Plan Ahead</span>
    <h2 class="xd-title"><span class="xd-title-icon">&#128197;</span> Availability &mdash; {{ $calMonth->format('F Y') }}</h2>

    <div class="xd-avail-weekdays">
        <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
    </div>
    <div class="xd-avail-grid">
        @for($i = 0; $i < $leadingBlanks; $i++)
        <div class="xd-avail-day xd-avail-day--blank"></div>
        @endfor
        @for($day = 1; $day <= $daysInMonth; $day++)
        <?php
            $dateKey = $calMonth->copy()->day($day)->format('Y-m-d');
            $dayStatus = $avByDate[$dateKey] ?? null;
        ?>
        <div class="xd-avail-day @if($dayStatus) {{ $statusClass($dayStatus) }} @endif" title="@if($dayStatus){{ \App\ExperienceAccommodationAvailability::statusLabel($dayStatus) }}@endif">
            {{ $day }}
        </div>
        @endfor
    </div>

    <div class="xd-avail-legend">
        <span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#16a34a;"></span> Open</span>
        <span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#a16207;"></span> Few Left</span>
        <span class="xd-avail-legend-item"><span class="xd-avail-legend-dot" style="background:#dc2626;"></span> Full / Closed</span>
    </div>

    <?php
        $otherDates = array_filter($avByDate, function ($d) use ($calMonth) {
            return \Carbon\Carbon::parse($d)->format('Y-m') !== $calMonth->format('Y-m');
        }, ARRAY_FILTER_USE_KEY);
    ?>
    @if(sizeof($otherDates) > 0)
    <h3 class="xd-avail-other-heading">Other upcoming start dates</h3>
    <div class="xd-avail-list">
        @foreach($otherDates as $d => $status)
        <div class="xd-routine-item">
            <span class="xd-routine-time">{{ \Carbon\Carbon::parse($d)->format('d M Y') }}</span>
            <div class="xd-routine-desc" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <span class="xd-badge {{ $statusClass($status) == 'is-open' ? 'xd-badge-open' : ($statusClass($status) == 'is-few' ? 'xd-badge-warn' : 'xd-badge-danger') }}">
                    {{ \App\ExperienceAccommodationAvailability::statusLabel($status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif
