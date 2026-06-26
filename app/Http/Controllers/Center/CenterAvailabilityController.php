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
     * List center's experiences to choose from.
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experiences = Experiences::where('center_id', $centerId)
            ->orderBy('name')
            ->get();

        $ids = $experiences->pluck('id')->toArray();

        $accomCounts = ExperienceAccomodations::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, count(*) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        $priceCounts = ExperienceAccomodationPrices::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, count(*) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        // Count configured start dates per experience
        $scheduleCounts = ExperienceAccommodationAvailability::whereIn('experience_id', $ids)
            ->selectRaw('experience_id, COUNT(DISTINCT start_date) as total')
            ->groupBy('experience_id')
            ->pluck('total', 'experience_id');

        return view('center_panel.availability', [
            'center'         => $center,
            'experiences'    => $experiences,
            'accomCounts'    => $accomCounts,
            'priceCounts'    => $priceCounts,
            'scheduleCounts' => $scheduleCounts,
        ]);
    }

    /**
     * Show the pricing/accommodation form for one experience.
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

        $existingEA = ExperienceAccomodations::where('experience_id', $experienceId)
            ->get()
            ->keyBy('title');

        $existingPrices = ExperienceAccomodationPrices::where('experience_id', $experienceId)
            ->orderBy('start_date')
            ->get()
            ->groupBy('accomodation_id');

        return view('center_panel.availability_form', [
            'center'               => $center,
            'experience'           => $experience,
            'centerAccommodations' => $centerAccommodations,
            'existingEA'           => $existingEA,
            'existingPrices'       => $existingPrices,
            'currencies'           => self::CURRENCIES,
        ]);
    }

    /**
     * Save pricing settings for an experience.
     */
    public function save(Request $request, $experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $defaultAccomId = $request->input('default_accommodation');
        $submitted      = $request->input('accommodations', []);

        foreach ($submitted as $accomId => $data) {
            $included = !empty($data['included']);
            $eaId     = $data['ea_id'] ?? null;

            if ($included) {
                $ea = null;
                if ($eaId) {
                    $ea = ExperienceAccomodations::find($eaId);
                }
                if (!$ea) {
                    $ea = ExperienceAccomodations::where('experience_id', $experienceId)
                        ->where('title', $accomId)
                        ->first() ?? new ExperienceAccomodations();
                }

                $ea->experience_id             = $experienceId;
                $ea->title                     = $accomId;
                $ea->price_per_night_per_guest = $this->nullableDecimal($data['base_price'] ?? null);
                $ea->single_occupancy_price    = $this->nullableDecimal($data['single_base_price'] ?? null);
                $ea->double_occupancy_price    = $this->nullableDecimal($data['double_base_price'] ?? null);
                $ea->currency                  = $data['currency'] ?? 'INR';
                $ea->accomodation_default      = ($defaultAccomId == $accomId) ? 1 : 0;
                $ea->save();

                $submittedPriceIds = [];

                foreach ($data['ranges'] ?? [] as $range) {
                    if (empty($range['start_date']) || empty($range['end_date'])) continue;

                    $priceId = !empty($range['price_id']) ? (int)$range['price_id'] : null;
                    $price   = null;

                    if ($priceId) {
                        $price = ExperienceAccomodationPrices::where('id', $priceId)
                            ->where('experience_id', $experienceId)
                            ->where('accomodation_id', $accomId)
                            ->first();
                    }
                    if (!$price) {
                        $price                  = new ExperienceAccomodationPrices();
                        $price->experience_id   = $experienceId;
                        $price->accomodation_id = $accomId;
                    }

                    $price->start_date                = $range['start_date'];
                    $price->end_date                  = $range['end_date'];
                    $price->duration                  = !empty($range['duration']) ? (int)$range['duration'] : null;
                    $price->price_per_night_per_guest = $this->nullableDecimal($range['price'] ?? null);
                    $price->single_occupancy_price    = $this->nullableDecimal($range['single_occupancy_price'] ?? null);
                    $price->double_occupancy_price    = $this->nullableDecimal($range['double_occupancy_price'] ?? null);
                    $price->promotional_price         = $this->nullableDecimal($range['promo_price'] ?? null);
                    $price->promotional_discount      = !empty($range['promo_discount']) ? $range['promo_discount'] : null;
                    $price->currency                  = $data['currency'] ?? 'INR';
                    $price->save();

                    $submittedPriceIds[] = $price->id;
                }

                ExperienceAccomodationPrices::where('experience_id', $experienceId)
                    ->where('accomodation_id', $accomId)
                    ->when(!empty($submittedPriceIds), fn($q) => $q->whereNotIn('id', $submittedPriceIds))
                    ->when(empty($submittedPriceIds), fn($q) => $q)
                    ->delete();

            } elseif ($eaId) {
                ExperienceAccomodationPrices::where('experience_id', $experienceId)
                    ->where('accomodation_id', $accomId)
                    ->delete();
                ExperienceAccomodations::find($eaId)?->delete();
            }
        }

        return redirect()->route('center-panel.availability.manage', $experienceId)
            ->with('success', 'Availability & pricing saved successfully.');
    }

    /**
     * AJAX: delete a single price row.
     */
    public function deletePrice(Request $request)
    {
        $centerId = Session::get('center_id');
        $price    = ExperienceAccomodationPrices::find($request->id);

        if ($price) {
            $belongs = Experiences::where('id', $price->experience_id)
                ->where('center_id', $centerId)
                ->exists();
            if ($belongs) {
                $price->delete();
                echo '1';
                return;
            }
        }
        echo 'error';
    }

    // ── Schedule / Start-Date Availability ───────────────────────────────────

    /**
     * Show the start-date availability manager for an experience.
     */
    public function manageSchedule($experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        // Accommodations already linked & priced for this experience
        $linkedAccoms = ExperienceAccomodations::where('experience_id', $experienceId)
            ->get();

        if ($linkedAccoms->isEmpty()) {
            return redirect()
                ->route('center-panel.availability.manage', $experienceId)
                ->with('error', 'Please configure accommodation pricing first before managing schedule availability.');
        }

        $accomIds = $linkedAccoms->pluck('title')->map(fn($v) => (int) $v)->toArray();

        $accommodations = Accomodation::whereIn('id', $accomIds)
            ->orderBy('name')
            ->get();

        // Which accommodation tab is selected?
        $selectedAccomId = (int) request('accom', $accommodations->first()?->id ?? 0);

        // All availability rows for selected accommodation in this experience
        $availabilityRows = ExperienceAccommodationAvailability::where('experience_id', $experienceId)
            ->where('accommodation_id', $selectedAccomId)
            ->orderBy('start_date')
            ->get();

        // Overview matrix: all accommodations × all unique start dates (upcoming)
        $allRows = ExperienceAccommodationAvailability::where('experience_id', $experienceId)
            ->whereIn('accommodation_id', $accomIds)
            ->orderBy('start_date')
            ->get();

        $uniqueDates = $allRows->pluck('start_date')->unique()->sortBy(fn($d) => $d)->values();

        // Build matrix: accommodation_id → date_string → row
        $matrix = [];
        foreach ($allRows as $row) {
            $matrix[$row->accommodation_id][$row->start_date->toDateString()] = $row;
        }

        return view('center_panel.schedule_availability', [
            'experience'       => $experience,
            'accommodations'   => $accommodations,
            'selectedAccomId'  => $selectedAccomId,
            'availabilityRows' => $availabilityRows,
            'uniqueDates'      => $uniqueDates,
            'matrix'           => $matrix,
            'statusList'       => ExperienceAccommodationAvailability::statusList(),
        ]);
    }

    /**
     * Bulk-save all rows for one accommodation via form POST.
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
                [
                    'experience_id'    => $experienceId,
                    'accommodation_id' => $accomId,
                    'start_date'       => $row['start_date'],
                ],
                [
                    'status'       => $status,
                    'total_rooms'  => $total,
                    'booked_rooms' => $booked,
                ]
            );
        }

        return redirect()
            ->route('center-panel.availability.schedule', [$experienceId, 'accom' => $accomId])
            ->with('success', 'Schedule availability saved successfully.');
    }

    /**
     * AJAX: add or update a single start-date row.
     */
    public function updateStartDate(Request $request)
    {
        $centerId = Session::get('center_id');

        $experienceId = (int) $request->input('experience_id');
        $accomId      = (int) $request->input('accommodation_id');
        $startDate    = $request->input('start_date');
        $total        = max(0, (int) $request->input('total_rooms', 0));
        $booked       = max(0, min($total, (int) $request->input('booked_rooms', 0)));
        $status       = $request->input('status', '');

        if (!$experienceId || !$accomId || !$startDate) {
            return response()->json(['error' => 'Missing required fields'], 422);
        }

        $belongs = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->exists();

        if (!$belongs) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($status, ['open', 'few_left', 'full', 'closed'])) {
            $status = ExperienceAccommodationAvailability::deriveStatus($total, $booked);
        }

        $row = ExperienceAccommodationAvailability::updateOrCreate(
            [
                'experience_id'    => $experienceId,
                'accommodation_id' => $accomId,
                'start_date'       => $startDate,
            ],
            [
                'status'       => $status,
                'total_rooms'  => $total,
                'booked_rooms' => $booked,
            ]
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

        $id = (int) $request->input('id');
        $row = ExperienceAccommodationAvailability::find($id);

        if (!$row) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $belongs = Experiences::where('id', $row->experience_id)
            ->where('center_id', $centerId)
            ->exists();

        if (!$belongs) {
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
