<?php

namespace App\Http\Controllers\Retreat;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRetreatRequest;
use App\Http\Requests\UpdateRetreatRequest;
use App\Models\Experience;
use App\Models\Center;
use App\Services\RetreatService;
use Illuminate\Support\Facades\Auth;

class RetreatController extends Controller
{
    public function __construct(private RetreatService $retreatService) {}

    public function index()
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return view('dashboard.no-center');
        }

        $retreats = $center->experiences()
            ->with('accommodations', 'bookings')
            ->orderByDesc('created_at')
            ->paginate(10);

        $retreats = $retreats->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'start_date' => $r->start_date_time?->format('M d, Y') ?? 'N/A',
            'end_date' => $r->end_date_time?->format('M d, Y') ?? 'N/A',
            'price' => $r->price_per_person,
            'status' => $r->is_draft ? 'Draft' : 'Published',
            'booked' => $r->occupied_spaces,
            'total' => $r->total_spaces,
            'occupancy_percent' => $r->occupancy_percentage,
            'bookings' => $r->bookings->where('order_status', 'confirmed')->count(),
            'revenue' => $r->bookings->where('payment_status', 'completed')->sum('pay_amount'),
            'rating' => $r->average_rating,
        ]);

        return view('retreat.index', [
            'center' => $center,
            'retreats' => $retreats,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return redirect()->route('dashboard');
        }

        return view('retreat.create', [
            'center' => $center,
        ]);
    }

    public function store(StoreRetreatRequest $request)
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return redirect()->route('dashboard')->with('error', 'Center not found');
        }

        $retreat = $this->retreatService->createRetreat($center, $request->validated());

        // Handle accommodations
        if ($request->has('accommodations')) {
            foreach ($request->input('accommodations') as $accId) {
                $retreat->accommodations()->attach($accId, [
                    'price_per_night_per_guest' => $request->input('price_per_night'),
                ]);
            }
        }

        return redirect()->route('retreat.edit', $retreat)
            ->with('success', 'Retreat created successfully!');
    }

    public function edit(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        return view('retreat.edit', [
            'retreat' => $retreat,
            'center' => $retreat->center,
        ]);
    }

    public function update(UpdateRetreatRequest $request, Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $this->retreatService->updateRetreat($retreat, $request->validated());

        return redirect()->back()->with('success', 'Retreat updated successfully!');
    }

    public function show(Experience $retreat)
    {
        $this->authorize('view', $retreat);

        $summary = $this->retreatService->getRetreatSummary($retreat);

        return view('retreat.show', [
            'retreat' => $retreat,
            'summary' => $summary,
        ]);
    }

    public function publish(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        try {
            $this->retreatService->publishRetreat($retreat);
            return redirect()->back()->with('success', 'Retreat published successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function draft(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $this->retreatService->draftRetreat($retreat);

        return redirect()->back()->with('success', 'Retreat moved to draft.');
    }

    public function duplicate(Experience $retreat)
    {
        $this->authorize('create', Experience::class);

        $duplicate = $this->retreatService->duplicateRetreat($retreat);

        return redirect()->route('retreat.edit', $duplicate)
            ->with('success', 'Retreat duplicated successfully!');
    }

    public function destroy(Experience $retreat)
    {
        $this->authorize('delete', $retreat);

        $this->retreatService->deleteRetreat($retreat);

        return redirect()->route('retreat.index')
            ->with('success', 'Retreat deleted successfully!');
    }
}
