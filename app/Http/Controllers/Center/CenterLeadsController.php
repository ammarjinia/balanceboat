<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Mail;
use Carbon\Carbon;
use App\Centers;
use App\Experiences;
use App\Inquiry;

class CenterLeadsController extends Controller
{
    public const STAGES = [
        'new'           => 'New Assignment',
        'proposal_sent' => 'Proposal Sent',
        'negotiation'   => 'Negotiation Loop',
        'won'           => 'Won',
        'lost'          => 'Lost',
    ];

    public function __construct()
    {
        $this->middleware('center.auth');
    }

    /**
     * Lead pipeline — real inquiries scoped to this center via experiences.center_id.
     * Inquiries without an experience_id (e.g. the site-wide quick-lead form) aren't
     * yet assigned to any retreat/center, so they naturally don't appear here.
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experiences = Experiences::where('center_id', $centerId)->orderBy('name')->get();

        $leads = Inquiry::select('inquiries.*', 'experiences.name as experience_name')
            ->join('experiences', 'experiences.id', '=', 'inquiries.experience_id')
            ->where('experiences.center_id', $centerId)
            ->orderBy('inquiries.created_at', 'desc')
            ->get();

        $currencySymbols = ['INR' => '₹', 'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'AED' => 'AED ', 'SGD' => 'SGD '];
        $primaryCurrency = $experiences->pluck('currency')->filter()->countBy()->sortDesc()->keys()->first() ?? 'INR';
        $currencySymbol  = $currencySymbols[$primaryCurrency] ?? ($primaryCurrency . ' ');

        $pipeline = $leads->map(function ($l) {
            $stage = $l->stage ?: 'new';

            return [
                'id'              => $l->id,
                'name'            => trim($l->name . ' ' . $l->lastname),
                'email'           => $l->email,
                'phone'           => $l->phone ? (($l->country_code ?: '') . $l->phone) : '',
                'retreatName'     => $l->experience_name,
                'sourceTag'       => in_array($l->source, [null, '', 'website']) ? 'BalanceBoat' : 'External',
                'stage'           => $stage,
                'dealValue'       => (float) ($l->deal_value ?? 0),
                'message'         => trim(strip_tags(str_replace(['<br />', '<br>', '<br/>'], "\n", (string) $l->message))),
                'note'            => $l->note ?? '',
                'lossReason'      => $l->loss_reason ?? '',
                'createdAt'       => optional($l->created_at)->format('M j, Y \a\t g:i A'),
                'ageHours'        => $l->created_at ? $l->created_at->diffInHours(Carbon::now()) : null,
                'responseMinutes' => ($stage !== 'new' && $l->updated_at && $l->created_at) ? $l->created_at->diffInMinutes($l->updated_at) : null,
            ];
        })->values();

        return view('center_panel.leads', [
            'center'         => $center,
            'experiences'    => $experiences,
            'stages'         => self::STAGES,
            'leadsJson'      => $pipeline->toJson(),
            'currencySymbol' => $currencySymbol,
        ]);
    }

    /**
     * Manual lead entry ("Ingest External CRM Record") — writes a real inquiries row
     * tied to one of this center's own experiences, tagged as an External-source lead.
     */
    public function store(Request $request)
    {
        $centerId = Session::get('center_id');

        $request->validate([
            'name'          => 'required|string|max:200',
            'email'         => 'required|email|max:191',
            'phone'         => 'nullable|string|max:20',
            'experience_id' => 'required|integer',
            'deal_value'    => 'nullable|numeric|min:0',
            'message'       => 'nullable|string|max:2000',
        ]);

        $experience = Experiences::where('id', $request->experience_id)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $lead = new Inquiry();
        $lead->name            = $request->name;
        $lead->email           = $request->email;
        $lead->phone           = preg_replace('/\D/', '', (string) $request->phone) ?: null;
        $lead->experience_id   = $experience->id;
        $lead->conversation_id = 'crm' . uniqid();
        $lead->source          = 'center_manual';
        $lead->message         = $request->message;
        $lead->deal_value      = $request->deal_value ?: 0;
        $lead->stage           = 'new';
        $lead->save();

        return redirect()->route('center-panel.leads')
            ->with('success', 'Lead "' . $request->name . '" added to the pipeline.');
    }

    /**
     * AJAX: advance/change a lead's pipeline stage.
     */
    public function updateStage(Request $request, $id)
    {
        $centerId = Session::get('center_id');

        $lead = Inquiry::select('inquiries.*')
            ->join('experiences', 'experiences.id', '=', 'inquiries.experience_id')
            ->where('inquiries.id', $id)
            ->where('experiences.center_id', $centerId)
            ->first();

        if (!$lead) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'stage'       => 'required|string|in:' . implode(',', array_keys(self::STAGES)),
            'loss_reason' => 'required_if:stage,lost|nullable|string|max:500',
        ]);

        $lead->stage = $request->stage;
        $lead->loss_reason = $request->stage === 'lost' ? $request->loss_reason : null;
        $lead->save();

        return response()->json([
            'success' => true,
            'stage'   => $lead->stage,
            'label'   => self::STAGES[$lead->stage],
        ]);
    }

    /**
     * AJAX: dispatch a direct email reply to the lead and log it against the record.
     */
    public function respond(Request $request, $id)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $lead = Inquiry::select('inquiries.*')
            ->join('experiences', 'experiences.id', '=', 'inquiries.experience_id')
            ->where('inquiries.id', $id)
            ->where('experiences.center_id', $centerId)
            ->first();

        if (!$lead) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = $request->message;

        try {
            if ($lead->email) {
                Mail::raw($message, function ($m) use ($lead, $center) {
                    $m->to($lead->email, trim($lead->name . ' ' . $lead->lastname))
                      ->subject('Response to your retreat inquiry — ' . $center->name);
                });
            }
        } catch (\Exception $e) {
            // Email dispatch failure shouldn't block logging the response internally.
        }

        $logEntry  = '[' . Carbon::now()->format('M j, Y g:i A') . '] Center response dispatched: ' . $message;
        $lead->note = trim(($lead->note ? $lead->note . "\n\n" : '') . $logEntry);

        if ($lead->stage === 'new') {
            $lead->stage = 'proposal_sent';
        }
        $lead->save();

        return response()->json([
            'success' => true,
            'note'    => $lead->note,
            'stage'   => $lead->stage,
            'label'   => self::STAGES[$lead->stage],
        ]);
    }
}
