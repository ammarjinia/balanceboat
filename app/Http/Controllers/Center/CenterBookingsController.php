<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Centers;
use App\Experiences;
use App\Bookings;
use App\BookingUserInfo;
use App\BookingExperienceInfo;
use App\ExperienceAccomodations;
use App\Accomodation;

class CenterBookingsController extends Controller
{
    public const STAGES = [
        'inquiry'       => 'Inquiry',
        'qualified'     => 'Qualified',
        'proposal_sent' => 'Proposal Sent',
        'confirmed'     => 'Confirmed',
        'checked_in'    => 'Checked In',
        'completed'     => 'Completed',
        'cancelled'     => 'Cancelled',
        'refunded'      => 'Refunded',
    ];

    public function __construct()
    {
        $this->middleware('center.auth');
    }

    /**
     * Bookings ledger — real bookings scoped to this center via experiences.center_id
     * (bookings.center_id is never populated by the checkout flow, so it can't be trusted).
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experiences   = Experiences::where('center_id', $centerId)->orderBy('name')->get();
        $experienceIds = $experiences->pluck('id');

        $bookings = Bookings::select('bookings.*', 'experiences.name as experience_name')
            ->join('experiences', 'experiences.id', '=', 'bookings.experience_id')
            ->where('experiences.center_id', $centerId)
            ->orderBy('bookings.created_at', 'desc')
            ->get();

        $bookingIds = $bookings->pluck('id');

        $userInfos = BookingUserInfo::whereIn('booking_id', $bookingIds)->get()->keyBy('booking_id');
        $expInfos  = BookingExperienceInfo::whereIn('booking_id', $bookingIds)->get()->keyBy('booking_id');

        // Room name lookup: bookings.experience_accomodation_id -> experience_accomodations.id
        // -> experience_accomodations.title (stores accomodation.id) -> accomodation.name
        $eaIds  = $bookings->pluck('experience_accomodation_id')->filter()->unique()->values();
        $eas    = ExperienceAccomodations::whereIn('id', $eaIds)->get()->keyBy('id');
        $accoms = Accomodation::whereIn('id', $eas->pluck('title')->filter()->unique())->get()->keyBy('id');

        $ledger = $bookings->map(function ($b) use ($userInfos, $expInfos, $eas, $accoms) {
            $userInfo = $userInfos->get($b->id);
            $expInfo  = $expInfos->get($b->id);
            $ea       = $b->experience_accomodation_id ? $eas->get($b->experience_accomodation_id) : null;
            $accom    = $ea ? $accoms->get($ea->title) : null;

            $gross = $b->booking_amount ?: (((float) $b->pay_amount) + ((float) $b->discount_amount));

            return [
                'id'            => $b->id,
                'customerName'  => $userInfo ? trim($userInfo->firstname . ' ' . $userInfo->lastname) : '—',
                'retreatName'   => $b->experience_name,
                'roomCategory'  => $accom->name ?? '—',
                'pax'           => $b->guest_count ?? null,
                'bookingDate'   => optional($b->created_at)->format('Y-m-d'),
                'checkInDate'   => $b->arrival_date ? Carbon::parse($b->arrival_date)->format('Y-m-d') : optional($b->start_date_time)->format('Y-m-d'),
                'grossAmount'   => round((float) $gross, 2),
                'discount'      => round((float) $b->discount_amount, 2),
                'commissionPct' => $expInfo && $expInfo->commission !== null ? (float) $expInfo->commission : null,
                'extraDetails'  => $userInfo->message ?? '',
                'notes'         => $b->host_notes ?? '',
                'status'        => $b->stage ?: 'inquiry',
                'currency'      => $b->booking_currency ?: ($b->currency ?: ''),
            ];
        })->values();

        // Accommodations grouped per experience, for the manual-entry modal's room dropdown
        $eaAll        = ExperienceAccomodations::whereIn('experience_id', $experienceIds)->get();
        $accomAllById = Accomodation::whereIn('id', $eaAll->pluck('title')->filter()->unique())->get()->keyBy('id');
        $accommodationsByExperience = $eaAll->groupBy('experience_id')->map(function ($rows) use ($accomAllById) {
            return $rows->map(fn($ea) => [
                'id'   => $ea->title,
                'name' => $accomAllById->get($ea->title)->name ?? 'Room',
            ])->values();
        });

        return view('center_panel.bookings', [
            'center'                          => $center,
            'experiences'                     => $experiences,
            'ledgerJson'                       => $ledger->values()->toJson(),
            'accommodationsByExperienceJson'   => $accommodationsByExperience->toJson(),
            'stages'                          => self::STAGES,
        ]);
    }

    /**
     * Manual/offline booking entry — writes a real bookings row (plus user-info & experience-info snapshots)
     * so it behaves identically to a checkout-created booking everywhere else in the app.
     */
    public function store(Request $request)
    {
        $centerId = Session::get('center_id');

        $request->validate([
            'name'             => 'required|string|max:200',
            'experience_id'    => 'required|integer',
            'accommodation_id' => 'nullable|integer',
            'pax'              => 'required|integer|min:1|max:50',
            'booking_date'     => 'required|date',
            'check_in_date'    => 'required|date',
            'gross_amount'     => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0',
            'commission_pct'   => 'required|numeric|min:0|max:100',
            'status'           => 'required|string|in:' . implode(',', array_keys(self::STAGES)),
            'extra_details'    => 'nullable|string',
            'host_notes'       => 'nullable|string',
        ]);

        $experience = Experiences::where('id', $request->experience_id)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $ea = null;
        if ($request->accommodation_id) {
            $ea = ExperienceAccomodations::where('experience_id', $experience->id)
                ->where('title', $request->accommodation_id)
                ->first();
        }

        $gross            = (float) $request->gross_amount;
        $discount          = (float) ($request->discount ?? 0);
        $commissionPct     = (float) $request->commission_pct;
        $commissionAmount = round($gross * $commissionPct / 100, 2);

        $booking = new Bookings();
        $booking->experience_id              = $experience->id;
        $booking->experience_accomodation_id = $ea->id ?? null;
        $booking->guest_count                = (int) $request->pax;
        $booking->arrival_date               = $request->check_in_date;
        $booking->booking_amount             = $gross;
        $booking->discount_amount            = $discount;
        $booking->commission_amount          = $commissionAmount;
        $booking->pay_amount                 = max(0, $gross - $discount);
        $booking->currency                   = $experience->currency ?: 'INR';
        $booking->booking_currency           = $experience->currency ?: 'INR';
        $booking->stage                      = $request->status;
        $booking->host_notes                 = $request->host_notes;
        $booking->created_at                 = Carbon::parse($request->booking_date);
        $booking->save();

        $nameParts = preg_split('/\s+/', trim($request->name), 2);
        $userInfo = new BookingUserInfo();
        $userInfo->booking_id = $booking->id;
        $userInfo->firstname  = $nameParts[0] ?? $request->name;
        $userInfo->lastname   = $nameParts[1] ?? '';
        $userInfo->email      = '';
        $userInfo->phone      = '';
        $userInfo->message    = $request->extra_details;
        $userInfo->save();

        $expInfo = new BookingExperienceInfo();
        $expInfo->booking_id    = $booking->id;
        $expInfo->experience_id = $experience->id;
        $expInfo->center_id     = $centerId;
        $expInfo->name          = $experience->name;
        $expInfo->commission    = $commissionPct;
        $expInfo->save();

        return redirect()->route('center-panel.bookings')
            ->with('success', 'Manual booking entry for "' . $request->name . '" logged successfully.');
    }

    /**
     * AJAX: advance/change a booking's stage.
     */
    public function updateStage(Request $request, $id)
    {
        $centerId = Session::get('center_id');

        $booking = Bookings::select('bookings.*')
            ->join('experiences', 'experiences.id', '=', 'bookings.experience_id')
            ->where('bookings.id', $id)
            ->where('experiences.center_id', $centerId)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'stage' => 'required|string|in:' . implode(',', array_keys(self::STAGES)),
        ]);

        $booking->stage = $request->stage;
        $booking->save();

        return response()->json([
            'success' => true,
            'stage'   => $booking->stage,
            'label'   => self::STAGES[$booking->stage],
        ]);
    }
}
