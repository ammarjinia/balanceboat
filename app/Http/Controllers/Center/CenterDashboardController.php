<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use App\Centers;
use App\Experiences;
use App\Bookings;
use App\Category;
use App\ExperienceCategory;
use App\ExperienceImageGallery;
use Illuminate\Support\Str;

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
        $experiences = Experiences::where('center_id', $centerId)->orderBy("id", "DESC")->paginate(15);

        $totalExperiences = Experiences::where('center_id', $centerId)->count();
        $totalBookings = 0;//Bookings::where('center_id', $centerId)->count();
        $activeDistributionListings = intval(ceil($totalExperiences * 0.6));
        $upcomingCyclePipelines = max(0, min(12, intval(floor($totalExperiences / 2))));
        $pipelineOccupancyVelocity = $totalBookings > 0
            ? round(min(100, 55 + ($totalBookings / max(1, $totalExperiences + 1)) * 30), 1)
            : 0;
        $topConvertingProgramName = Experiences::where('center_id', $centerId)
            ->orderBy('created_at', 'desc')
            ->value('name') ?? 'Retreat Program';

        return view('center_panel.experiences', [
            'center' => $center,
            'userName' => Session::get('center_user_name'),
            'experiences' => $experiences,
            'totalExperiences' => $totalExperiences,
            'activeDistributionListings' => $activeDistributionListings,
            'upcomingCyclePipelines' => $upcomingCyclePipelines,
            'pipelineOccupancyVelocity' => $pipelineOccupancyVelocity,
            'topConvertingProgramName' => $topConvertingProgramName,
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

    // ── Experience Create / Edit Wizard ────────────────────────────────────

    private function experienceFormData(): array
    {
        return [
            'retreatCategories' => Category::where('type', 0)->where('parent', 0)->orderBy('name')->get(),
            'destinations'      => Category::where('type', 1)->where('parent', 0)->orderBy('name')->get(),
            'currencies'        => ['INR' => '₹ INR', 'USD' => '$ USD', 'EUR' => '€ EUR', 'GBP' => '£ GBP', 'AED' => 'AED', 'SGD' => 'SGD'],
        ];
    }

    public function experienceCreate()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        return view('center_panel.experience_form', array_merge($this->experienceFormData(), [
            'center'     => $center,
            'userName'   => Session::get('center_user_name'),
            'experience' => null,
            'pageTitle'  => 'Create New Retreat Program',
            'formAction' => route('center-panel.experience.store'),
        ]));
    }

    public function experienceStore(Request $request)
    {
        $centerId = Session::get('center_id');

        $request->validate(['name' => 'required|string|max:255']);

        $slug = $request->input('slug') ?: Str::slug($request->input('name'));

        $exp = new Experiences();
        $this->fillExperienceFromRequest($exp, $request, $centerId, $slug);
        $exp->save();

        $this->syncCategories($exp->id, $request->input('experience_category_id', []));
        $this->syncGalleryImages($exp->id, $request->file('image_galleries', []));

        return redirect()->route('center-panel.experiences')
            ->with('success', 'Retreat program created successfully.');
    }

    public function experienceEdit($id)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experience = Experiences::where('id', $id)->where('center_id', $centerId)->firstOrFail();

        $experience->selectedCategories = ExperienceCategory::where('experience_id', $id)
            ->pluck('category_id')->toArray();

        return view('center_panel.experience_form', array_merge($this->experienceFormData(), [
            'center'     => $center,
            'userName'   => Session::get('center_user_name'),
            'experience' => $experience,
            'pageTitle'  => 'Edit Retreat Program',
            'formAction' => route('center-panel.experience.update', $id),
        ]));
    }

    public function experienceUpdate(Request $request, $id)
    {
        $centerId = Session::get('center_id');
        $request->validate(['name' => 'required|string|max:255']);

        $exp  = Experiences::where('id', $id)->where('center_id', $centerId)->firstOrFail();
        $slug = $request->input('slug') ?: Str::slug($request->input('name'));

        $this->fillExperienceFromRequest($exp, $request, $centerId, $slug);
        $exp->save();

        $this->syncCategories($id, $request->input('experience_category_id', []));
        $this->syncGalleryImages($id, $request->file('image_galleries', []));

        return redirect()->route('center-panel.experiences')
            ->with('success', 'Retreat program updated successfully.');
    }

    private function fillExperienceFromRequest(Experiences $exp, Request $request, $centerId, string $slug): void
    {
        $exp->center_id           = $centerId;
        $exp->name                = $request->input('name');
        $exp->slug                = $slug;
        $exp->experience_summary  = $request->input('experience_summary');
        $exp->experience_overview = $request->input('experience_overview');
        $exp->experience_details  = $request->input('experience_details');
        $exp->experience_highlights = $request->input('experience_highlights');
        $exp->schedule = $request->input('experience_schedule');
        $exp->what_is_included    = $request->input('what_is_included');
        $exp->what_is_not_included = $request->input('what_is_not_included');
        $exp->how_to_get_here     = $request->input('how_to_get_here');
        $exp->booking_info        = $request->input('booking_info');
        $exp->batch_size          = $request->input('batch_size');
        $exp->duration            = $request->input('duration');
        $exp->avg_price           = $request->input('avg_price');
        $exp->currency            = $request->input('currency', 'INR');
        $exp->is_bookable         = $request->input('is_bookable', 1);
        $exp->is_draft            = $request->input('is_draft', 1);
        $exp->language_spoken     = is_array($request->input('language_spoken'))
            ? implode('||', $request->input('language_spoken'))
            : $request->input('language_spoken');
        $exp->skill_level         = $request->input('skill_level');
        $exp->video_url           = $request->input('video_url');
        $exp->cancellation_policy = $request->input('cancellation_policy');
        $exp->cancellation_policy_condition = $request->input('cancellation_policy_condition', 1);
        $exp->cancellation_policy_days = $request->input('cancellation_policy_days');
        $exp->deposit_policy      = $request->input('deposit_policy', 1);
        $exp->deposit_amount      = $request->input('deposit_amount');
        $exp->rest_of_payment     = $request->input('rest_of_payment', 1);
        $exp->food                = $request->input('food');
        $exp->area                = $request->input('area');
        $exp->atmosphere          = $request->input('atmosphere');
        $exp->gps                 = $request->input('gps');
        $exp->meta_title          = $request->input('meta_title') ?: $request->input('name');
        $exp->meta_description    = $request->input('meta_description') ?: $request->input('experience_summary');
        $exp->tags                = $request->input('tags');

        // Thumbnail image upload
        if ($request->hasFile('thumbnail_image') && $request->file('thumbnail_image')->isValid()) {
            $file = $request->file('thumbnail_image');
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $filename = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;
            $folderName = 'experiences/' . date("Y") . "/" . date("m") . "/" . date("d");
            $file->storeAs($folderName, $filename, ['disk' => 'azure']);
            $exp->thumbnail_image_url = $folderName . "/" . $filename;
        }

        // Banner image upload
        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
            $file = $request->file('banner_image');
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $filename = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;
            $folderName = 'experiences/' . date("Y") . "/" . date("m") . "/" . date("d");
            $file->storeAs($folderName, $filename, ['disk' => 'azure']);
            $exp->banner_image_url   = $folderName . "/" . $filename;
            $exp->banner_image_title = $file->getClientOriginalName();
        }
    }

    private function syncCategories(int $experienceId, array $categoryIds): void
    {
        ExperienceCategory::where('experience_id', $experienceId)->delete();
        foreach (array_filter($categoryIds) as $catId) {
            ExperienceCategory::create([
                'experience_id' => $experienceId,
                'category_id'   => (int) $catId,
            ]);
        }
    }

    private function syncGalleryImages(int $experienceId, array $galleryFiles): void
    {
        if (empty($galleryFiles)) {
            return;
        }

        foreach ($galleryFiles as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            // Generate filename similar to admin pattern
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = preg_replace('/[^A-Za-z0-9 ]/', '', $filenameWithoutExt) . time() . "." . $ext;

            // Store to Azure blob storage
            $folderName = 'experiences/' . date("Y") . "/" . date("m") . "/" . date("d");
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            $imageUrl = $folderName . "/" . $renamefile;

            ExperienceImageGallery::create([
                'experience_id' => $experienceId,
                'image_url'     => $imageUrl,
                'image_title'   => $file->getClientOriginalName(),
            ]);
        }
    }
}
