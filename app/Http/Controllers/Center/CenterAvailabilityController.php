<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Centers;
use App\Experiences;
use App\Accomodation;
use App\CenterAccomodations;
use App\ExperienceAccomodations;
use App\ExperienceAccomodationPrices;

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

        return view('center_panel.availability', [
            'center'       => $center,
            'experiences'  => $experiences,
            'accomCounts'  => $accomCounts,
            'priceCounts'  => $priceCounts,
        ]);
    }

    /**
     * Show the availability management form for one experience.
     */
    public function manage($experienceId)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        // All accommodations linked to this center
        $centerAccommodations = Accomodation::select('accomodation.*')
            ->join('center_accomodations', 'center_accomodations.accomodation_id', '=', 'accomodation.id')
            ->where('center_accomodations.center_id', $centerId)
            ->orderBy('accomodation.name')
            ->get();

        // Existing ExperienceAccomodations keyed by accomodation.id (stored in the `title` column)
        $existingEA = ExperienceAccomodations::where('experience_id', $experienceId)
            ->get()
            ->keyBy('title');

        // Existing prices grouped by accomodation_id (= accomodation.id)
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
     * Save availability settings for an experience.
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
                // Find existing or create new ExperienceAccomodations record
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
                $ea->title                     = $accomId;   // accomodation.id stored in title column
                $ea->price_per_night_per_guest = $data['base_price'] ?? 0;
                $ea->currency                  = $data['currency'] ?? 'USD';
                $ea->accomodation_default      = ($defaultAccomId == $accomId) ? 1 : 0;
                $ea->save();

                // Process date-range pricing rows
                foreach ($data['ranges'] ?? [] as $range) {
                    if (empty($range['start_date']) || empty($range['end_date'])) continue;

                    $priceId = $range['price_id'] ?? null;
                    $price   = null;

                    if ($priceId) {
                        $price = ExperienceAccomodationPrices::find($priceId);
                    }
                    if (!$price) {
                        $price = new ExperienceAccomodationPrices();
                        $price->experience_id   = $experienceId;
                        $price->accomodation_id = $accomId;
                    }

                    $price->start_date                = $range['start_date'];
                    $price->end_date                  = $range['end_date'];
                    $price->duration                  = !empty($range['duration']) ? (int)$range['duration'] : null;
                    $price->price_per_night_per_guest = $range['price'] ?? 0;
                    $price->promotional_price         = !empty($range['promo_price'])    ? $range['promo_price']    : null;
                    $price->promotional_discount      = !empty($range['promo_discount']) ? $range['promo_discount'] : null;
                    $price->currency                  = $data['currency'] ?? 'USD';
                    $price->save();
                }

            } elseif ($eaId) {
                // Toggled off — remove this accommodation from the experience
                ExperienceAccomodationPrices::where('experience_id', $experienceId)
                    ->where('accomodation_id', $accomId)
                    ->delete();
                ExperienceAccomodations::find($eaId)?->delete();
            }
        }

        return redirect()->route('center-panel.availability.manage', $experienceId)
            ->with('success', 'Availability saved successfully.');
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
                echo true;
                return;
            }
        }
        echo 'error';
    }
}
