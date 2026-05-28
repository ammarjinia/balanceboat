<?php

namespace App\Http\Controllers\Retreat;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Category;
use App\Models\Amenity;
use App\Http\Requests\StoreRetreatRequest;
use App\Http\Requests\UpdateRetreatRequest;
use App\Services\RetreatService;
use App\Services\PricingEngine;
use Illuminate\Http\Request;

class RetreatController extends Controller
{
    public function __construct(
        private RetreatService $retreatService,
        private PricingEngine $pricingEngine
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        $center = auth()->user()->primary_center ?? auth()->user()->centers()->first();

        if (!$center) {
            return redirect()->route('dashboard')->with('error', 'No center assigned');
        }

        $retreats = $center->experiences()
            ->with(['accommodations', 'bookings', 'reviews'])
            ->orderByDesc('created_at')
            ->paginate(12);

        $totalRevenue = $center->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($e) => $e->bookings->where('payment_status', 'completed')->sum('pay_amount'));

        $totalBookings = $center->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($e) => $e->bookings->where('order_status', 'confirmed')->count());

        return view('retreat.index', [
            'retreats' => $retreats,
            'center' => $center,
            'total_revenue' => $totalRevenue,
            'total_bookings' => $totalBookings,
        ]);
    }

    public function create()
    {
        $center = auth()->user()->primary_center ?? auth()->user()->centers()->first();

        return view('retreat.create', [
            'center' => $center,
            'accommodations' => $center->accommodations()->get(),
            'categories' => Category::all(),
            'teachers' => $center->teachers()->get(),
            'amenities' => Amenity::all(),
        ]);
    }

    public function store(StoreRetreatRequest $request)
    {
        $center = auth()->user()->primary_center ?? auth()->user()->centers()->first();

        $retreat = $this->retreatService->createRetreat($center, $request->validated());

        if ($request->has('categories')) {
            $retreat->categories()->sync($request->categories);
        }

        if ($request->has('teachers')) {
            $retreat->teachers()->sync($request->teachers);
        }

        if ($request->has('amenities')) {
            $retreat->amenities()->sync($request->amenities);
        }

        // Create initial pricing
        $retreat->durationPrices()->create([
            'duration' => intval(str_replace('_days', '', $request->duration ?? '7')),
            'price' => $request->price_per_person,
            'currency' => $request->currency ?? 'INR'
        ]);

        // Handle banner image
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('retreats', 'public');
            $retreat->update(['banner_image_url' => $path]);
        }

        return redirect()
            ->route('retreat.edit', $retreat)
            ->with('success', 'Retreat created successfully');
    }

    public function edit(Experience $retreat)
    {
        $this->authorize('view', $retreat);

        $center = $retreat->center;

        return view('retreat.edit', [
            'retreat' => $retreat->load([
                'accommodations',
                'schedules',
                'teachers',
                'categories',
                'amenities',
                'durationPrices'
            ]),
            'accommodations' => $center->accommodations,
            'categories' => Category::all(),
            'teachers' => $center->teachers,
            'amenities' => Amenity::all(),
        ]);
    }

    public function update(UpdateRetreatRequest $request, Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $this->retreatService->updateRetreat($retreat, $request->validated());

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('retreats', 'public');
            $retreat->update(['banner_image_url' => $path]);
        }

        if ($request->has('accommodations')) {
            $retreat->accommodations()->sync($request->accommodations);
        }

        if ($request->has('teachers')) {
            $retreat->teachers()->sync($request->teachers);
        }

        if ($request->has('amenities')) {
            $retreat->amenities()->sync($request->amenities);
        }

        return back()->with('success', 'Retreat updated successfully');
    }

    public function show(Experience $retreat)
    {
        $this->authorize('view', $retreat);

        return view('retreat.show', [
            'retreat' => $retreat->load([
                'center',
                'accommodations',
                'schedules',
                'teachers',
                'bookings',
                'galleries',
                'reviews'
            ]),
            'bookings' => $retreat->bookings()->paginate(10),
            'reviews' => $retreat->reviews()->with('user')->paginate(5),
            'metrics' => $this->retreatService->getRetreatSummary($retreat),
        ]);
    }

    public function publish(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        try {
            $this->retreatService->publishRetreat($retreat);
            return back()->with('success', 'Retreat is now live and bookable');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function draft(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $this->retreatService->draftRetreat($retreat);
        return back()->with('success', 'Retreat moved to draft');
    }

    public function duplicate(Experience $retreat)
    {
        $this->authorize('view', $retreat);

        $cloned = $this->retreatService->duplicateRetreat($retreat);

        return redirect()
            ->route('retreat.edit', $cloned)
            ->with('success', 'Retreat duplicated. Adjust dates and publish.');
    }

    public function destroy(Experience $retreat)
    {
        $this->authorize('delete', $retreat);

        $name = $retreat->name;
        $this->retreatService->deleteRetreat($retreat);

        return redirect()
            ->route('retreat.index')
            ->with('success', "Retreat '{$name}' deleted successfully");
    }
}
