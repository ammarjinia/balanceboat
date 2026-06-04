<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use App\Centers;
use App\Experiences;
use App\Bookings;

class CenterDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     * Check if center user is authenticated
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('center.auth');
    }

    /**
     * Show the center dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $centerUserId = Session::get('center_user_id');

        // Fetch center details
        $center = Centers::findOrFail($centerId);

        // Fetch statistics
        $totalExperiences = 0;//Experiences::where('center_id', $centerId)->count();
        $totalBookings = 0;//Bookings::where('center_id', $centerId)->count();

        $recentBookings = [];/*Bookings::where('center_id', $centerId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();*/

        $activeDistributionListings = intval(ceil($totalExperiences * 0.6));
        $upcomingCyclePipelines = max(0, min(12, intval(floor($totalExperiences / 2))));
        $pipelineOccupancyVelocity = $totalBookings > 0
            ? round(min(100, 55 + ($totalBookings / max(1, $totalExperiences + 1)) * 30), 1)
            : 0;
        $topConvertingProgramName = Experiences::where('center_id', $centerId)
            ->orderBy('created_at', 'desc')
            ->value('name') ?? 'Retreat Program';

        $data = [
            'center' => $center,
            'totalExperiences' => $totalExperiences,
            'totalBookings' => $totalBookings,
            'recentBookings' => $recentBookings,
            'userName' => Session::get('center_user_name'),
            'userEmail' => Session::get('center_user_email'),
            'activeDistributionListings' => $activeDistributionListings,
            'upcomingCyclePipelines' => $upcomingCyclePipelines,
            'pipelineOccupancyVelocity' => $pipelineOccupancyVelocity,
            'topConvertingProgramName' => $topConvertingProgramName,
        ];

        return view('center_panel.dashboard', $data);
    }

    /**
     * Get center overview data (for API/AJAX)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOverviewData()
    {
        $centerId = Session::get('center_id');

        $data = [
            'totalExperiences' => Experiences::where('center_id', $centerId)->count(),
            'totalBookings' => Bookings::where('center_id', $centerId)->count(),
            'pendingBookings' => Bookings::where('center_id', $centerId)->where('order_status', 'pending')->count(),
            'confirmedBookings' => Bookings::where('center_id', $centerId)->where('order_status', 'confirmed')->count(),
        ];

        return response()->json($data);
    }

    /**
     * Get center experiences
     * 
     * @return \Illuminate\View\View
     */
    public function experiences()
    {
        $centerId = Session::get('center_id');
        $center = Centers::findOrFail($centerId);
        $experiences = [];//Experiences::where('center_id', $centerId)->paginate(15);

        return view('center_panel.experiences', [
            'center' => $center,
            'userName' => Session::get('center_user_name'),
            'experiences' => $experiences,
        ]);
    }

    /**
     * Get center bookings
     * 
     * @return \Illuminate\View\View
     */
    public function bookings()
    {
        $centerId = Session::get('center_id');
        $center = Centers::findOrFail($centerId);
        $bookings = Bookings::where('center_id', $centerId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('center_panel.bookings', [
            'center' => $center,
            'userName' => Session::get('center_user_name'),
            'bookings' => $bookings,
        ]);
    }

    /**
     * Get center profile/settings
     * 
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $centerId = Session::get('center_id');
        $center = Centers::findOrFail($centerId);

        return view('center_panel.settings', ['center' => $center]);
    }

    /**
     * Update center profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request)
    {
        $centerId = Session::get('center_id');
        $center = Centers::findOrFail($centerId);

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email_address' => 'nullable|email',
            'contact_number' => 'nullable|string|max:20',
            'address_of_center' => 'nullable|string|max:255'
        ]);

        $center->name = $request->name;
        $center->email_address = $request->email_address;
        $center->contact_number = $request->contact_number;
        $center->address_of_center = $request->address_of_center;
        $center->save();

        return back()->with('success', 'Center settings updated successfully.');
    }
}
