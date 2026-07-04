<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Centers;
use App\Experiences;
use App\Accomodation;
use App\ExperienceAccomodations;
use App\ExperienceAccomodationPrices;
use App\ExperienceAccommodationAvailability;
use App\ExperienceAccommodationDurationPrice;
use App\ExperienceDurationPrices;

class CenterAvailabilityController extends Controller
{
    private const CURRENCIES = [
        'INR' => '₹ INR', 'USD' => '$ USD', 'EUR' => '€ EUR',
        'GBP' => '£ GBP', 'AED' => 'AED',   'SGD' => 'SGD',
    ];

    public function __construct()
    {
        $this->middleware('center.auth');
    }

    // ── Pricing & Accommodation Configuration ─────────────────────────────────

    /**
     * List center's experiences.
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experiences = Experiences::where('center_id', $centerId)->orderBy('name')->get();
        $ids = $experiences->pluck('id')->toArray();

        $accomCounts = ExperienceAccomodations::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, count(*) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        $priceCounts = ExperienceAccomodationPrices::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, count(*) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        $scheduleCounts = ExperienceAccommodationAvailability::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, COUNT(DISTINCT start_date) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        return view('center_panel.availability', compact(
            'center', 'experiences', 'accomCounts', 'priceCounts', 'scheduleCounts'
        ));
    }

    /**
     * Show the pricing / accommodation form for one experience.
     */
    public function manage($experienceId)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $centerAccommodations = Accomodation::select('accomodation.*')
            ->join('center_accomodations', 'center_accomodations.accomodation_id', '=', 'accomodation.id')
            ->where('center_accomodations.center_id', $centerId)
            ->orderBy('accomodation.name')
            ->get();

        // ExperienceAccomodations keyed by accomodation.id (stored in `title`)
        $existingEA = ExperienceAccomodations::where('experience_id', $experienceId)
            ->get()->keyBy('title');

        // Seasonal price ranges grouped by accommodation_id
        $existingPrices = ExperienceAccomodationPrices::where('experience_id', $experienceId)
            ->orderBy('start_date')
            ->get()->groupBy('accomodation_id');

        // Experience duration tiers (e.g. 7, 14, 21 nights)
        $experienceDurations = ExperienceDurationPrices::where('experience_id', $experienceId)
            ->orderBy('duration')
            ->get();

        // Duration-based prices: accommodation_id → duration_days → price row
        $existingDurationPrices = ExperienceAccommodationDurationPrice::where('experience_id', $experienceId)
            ->get()
            ->groupBy('accommodation_id')
            ->map(fn($rows) => $rows->keyBy('duration_days'));

        return view('center_panel.availability_form', compact(
            'center', 'experience', 'centerAccommodations',
            'existingEA', 'existingPrices',
            'experienceDurations', 'existingDurationPrices'
        ) + ['currencies' => self::CURRENCIES]);
    }

    /**
     * Save pricing (duration tiers + seasonal overrides only — no base rates).
     */
    public function save(Request $request, $experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $submitted = $request->input('accommodations', []);

        foreach ($submitted as $accomId => $data) {
            $included = !empty($data['included']);
            $eaId     = $data['ea_id'] ?? null;

            if ($included) {
                // ── Upsert ExperienceAccomodations link record (no base pricing — seasonal only) ──
                $ea = ($eaId ? ExperienceAccomodations::find($eaId) : null)
                    ?? ExperienceAccomodations::where('experience_id', $experienceId)
                         ->where('title', $accomId)->first()
                    ?? new ExperienceAccomodations();

                $ea->experience_id = $experienceId;
                $ea->title         = $accomId;
                $ea->currency      = $data['currency'] ?? 'INR';
                $ea->save();

                // ── Duration-based prices ──
                $submittedDurations = [];
                foreach ($data['durations'] ?? [] as $durationDays => $dData) {
                    $durationDays = (int) $durationDays;
                    $singlePrice  = $this->nullableDecimal($dData['single_price'] ?? null);
                    $doublePrice  = $this->nullableDecimal($dData['double_price'] ?? null);

                    if ($singlePrice || $doublePrice) {
                        ExperienceAccommodationDurationPrice::updateOrCreate(
                            [
                                'experience_id'    => $experienceId,
                                'accommodation_id' => $accomId,
                                'duration_days'    => $durationDays,
                            ],
                            [
                                'single_price' => $singlePrice,
                                'double_price' => $doublePrice,
                                'currency'     => $data['currency'] ?? 'INR',
                            ]
                        );
                        $submittedDurations[] = $durationDays;
                    } else {
                        // Remove if both prices cleared
                        ExperienceAccommodationDurationPrice::where('experience_id', $experienceId)
                            ->where('accommodation_id', $accomId)
                            ->where('duration_days', $durationDays)
                            ->delete();
                    }
                }

                // ── Seasonal / date-range price overrides ──
                $submittedPriceIds = [];
                foreach ($data['ranges'] ?? [] as $range) {
                    if (empty($range['start_date']) || empty($range['end_date'])) continue;

                    $priceId = !empty($range['price_id']) ? (int)$range['price_id'] : null;
                    $price   = ($priceId
                        ? ExperienceAccomodationPrices::where('id', $priceId)
                            ->where('experience_id', $experienceId)
                            ->where('accomodation_id', $accomId)
                            ->first()
                        : null) ?? new ExperienceAccomodationPrices();

                    if (!$price->exists) {
                        $price->experience_id   = $experienceId;
                        $price->accomodation_id = $accomId;
                    }

                    $price->start_date                = $range['start_date'];
                    $price->end_date                  = $range['end_date'];
                    $price->duration                  = !empty($range['duration']) ? (int)$range['duration'] : null;
                    $price->single_occupancy_price    = $this->nullableDecimal($range['single_occupancy_price'] ?? null);
                    $price->double_occupancy_price    = $this->nullableDecimal($range['double_occupancy_price'] ?? null);
                    $price->single_promo_price        = $this->nullableDecimal($range['single_promo_price'] ?? null);
                    $price->double_promo_price        = $this->nullableDecimal($range['double_promo_price'] ?? null);
                    $price->promotional_discount      = !empty($range['promo_discount']) ? $range['promo_discount'] : null;
                    $price->currency                  = $data['currency'] ?? 'INR';
                    $price->save();

                    $submittedPriceIds[] = $price->id;
                }

                // Delete removed seasonal rows
                ExperienceAccomodationPrices::where('experience_id', $experienceId)
                    ->where('accomodation_id', $accomId)
                    ->when(!empty($submittedPriceIds), fn($q) => $q->whereNotIn('id', $submittedPriceIds))
                    ->when(empty($submittedPriceIds), fn($q) => $q)
                    ->delete();

            } elseif ($eaId) {
                // Toggled off — remove everything for this accommodation in this experience
                ExperienceAccomodationPrices::where('experience_id', $experienceId)
                    ->where('accomodation_id', $accomId)->delete();
                ExperienceAccommodationDurationPrice::where('experience_id', $experienceId)
                    ->where('accommodation_id', $accomId)->delete();
                ExperienceAccomodations::find($eaId)?->delete();
            }
        }

        return redirect()->route('center-panel.availability.manage', $experienceId)
            ->with('success', 'Pricing saved successfully.');
    }

    /**
     * AJAX: delete a single seasonal price row.
     */
    public function deletePrice(Request $request)
    {
        $centerId = Session::get('center_id');
        $price    = ExperienceAccomodationPrices::find($request->id);

        if ($price) {
            $belongs = Experiences::where('id', $price->experience_id)
                ->where('center_id', $centerId)->exists();
            if ($belongs) { $price->delete(); echo '1'; return; }
        }
        echo 'error';
    }

    // ── Schedule / Start-Date Availability ───────────────────────────────────

    /**
     * Show the calendar-based start-date availability manager.
     */
    public function manageSchedule($experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        // Accommodations already linked & priced for this experience
        $linkedAccoms = ExperienceAccomodations::where('experience_id', $experienceId)->get();

        if ($linkedAccoms->isEmpty()) {
            return redirect()->route('center-panel.availability.manage', $experienceId)
                ->with('error', 'Please configure accommodation pricing first before managing schedule availability.');
        }

        $accomIds = $linkedAccoms->pluck('title')->map(fn($v) => (int) $v)->toArray();

        $accommodations = Accomodation::whereIn('id', $accomIds)->orderBy('name')->get();

        $selectedAccomId = (int) request('accom', $accommodations->first()?->id ?? 0);

        // All availability rows for the selected accommodation — as date-keyed array for JS
        $availabilityRows = ExperienceAccommodationAvailability::where('experience_id', $experienceId)
            ->where('accommodation_id', $selectedAccomId)
            ->orderBy('start_date')
            ->get();

        $availabilityData = $availabilityRows
            ->keyBy(fn($r) => $r->start_date->toDateString())
            ->map(fn($r) => [
                'id'     => $r->id,
                'status' => $r->status,
                'total'  => $r->total_rooms,
                'booked' => $r->booked_rooms,
            ]);

        // Overview matrix: all accommodations × all unique start dates
        $allRows = ExperienceAccommodationAvailability::where('experience_id', $experienceId)
            ->whereIn('accommodation_id', $accomIds)
            ->orderBy('start_date')
            ->get();

        $uniqueDates = $allRows->pluck('start_date')
            ->unique()->sort()->values()
            ->map(fn($d) => $d->toDateString());

        $matrix = [];
        foreach ($allRows as $row) {
            $matrix[$row->accommodation_id][$row->start_date->toDateString()] = $row;
        }

        return view('center_panel.schedule_availability', compact(
            'experience', 'accommodations', 'selectedAccomId',
            'availabilityRows', 'availabilityData',
            'uniqueDates', 'matrix'
        ));
    }

    /**
     * Bulk-save schedule rows (POST fallback from form).
     */
    public function saveSchedule(Request $request, $experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $accomId = (int) $request->input('accommodation_id');
        $rows    = $request->input('rows', []);

        foreach ($rows as $row) {
            if (empty($row['start_date'])) continue;
            $total  = max(0, (int) ($row['total_rooms']  ?? 0));
            $booked = max(0, min($total, (int) ($row['booked_rooms'] ?? 0)));
            $status = in_array($row['status'] ?? '', ['open','few_left','full','closed'])
                ? $row['status']
                : ExperienceAccommodationAvailability::deriveStatus($total, $booked);

            ExperienceAccommodationAvailability::updateOrCreate(
                ['experience_id' => $experienceId, 'accommodation_id' => $accomId, 'start_date' => $row['start_date']],
                ['status' => $status, 'total_rooms' => $total, 'booked_rooms' => $booked]
            );
        }

        return redirect()
            ->route('center-panel.availability.schedule', [$experienceId, 'accom' => $accomId])
            ->with('success', 'Schedule saved successfully.');
    }

    /**
     * AJAX: upsert a single start-date availability row.
     */
    public function updateStartDate(Request $request)
    {
        $centerId     = Session::get('center_id');
        $experienceId = (int) $request->input('experience_id');
        $accomId      = (int) $request->input('accommodation_id');
        $startDate    = $request->input('start_date');
        $total        = max(0, (int) $request->input('total_rooms', 0));
        $booked       = max(0, min($total, (int) $request->input('booked_rooms', 0)));
        $status       = $request->input('status', '');

        if (!$experienceId || !$accomId || !$startDate) {
            return response()->json(['error' => 'Missing required fields'], 422);
        }
        if (!Experiences::where('id', $experienceId)->where('center_id', $centerId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if (!in_array($status, ['open', 'few_left', 'full', 'closed'])) {
            $status = ExperienceAccommodationAvailability::deriveStatus($total, $booked);
        }

        $row = ExperienceAccommodationAvailability::updateOrCreate(
            ['experience_id' => $experienceId, 'accommodation_id' => $accomId, 'start_date' => $startDate],
            ['status' => $status, 'total_rooms' => $total, 'booked_rooms' => $booked]
        );

        return response()->json([
            'id'        => $row->id,
            'status'    => $row->status,
            'label'     => ExperienceAccommodationAvailability::statusLabel($row->status),
            'total'     => $row->total_rooms,
            'booked'    => $row->booked_rooms,
            'remaining' => $row->remaining,
        ]);
    }

    /**
     * AJAX: delete a start-date availability row.
     */
    public function deleteStartDate(Request $request)
    {
        $centerId = Session::get('center_id');
        $row      = ExperienceAccommodationAvailability::find((int) $request->input('id'));

        if (!$row) return response()->json(['error' => 'Not found'], 404);

        if (!Experiences::where('id', $row->experience_id)->where('center_id', $centerId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $row->delete();
        return response()->json(['success' => true]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function nullableDecimal($value): ?float
    {
        if ($value === null || $value === '') return null;
        $v = (float) $value;
        return $v > 0 ? $v : null;
    }
}
