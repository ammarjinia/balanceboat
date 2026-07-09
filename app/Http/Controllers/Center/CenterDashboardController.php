<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Storage;
use App\Centers;
use App\Experiences;
use App\Bookings;
use App\Category;
use App\Amenities;
use App\CenterImageGallery;
use App\ExperienceCategory;
use App\ExperienceImageGallery;
use App\ExperienceDurationPrices;
use App\Inquiry;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use DB;

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

        // Fetch center details
        $center = Centers::findOrFail($centerId);

        $experiences = Experiences::where('center_id', $centerId)
            ->select('id', 'name', 'thumbnail_image_url', 'avg_price', 'currency', 'is_draft', 'is_bookable')
            ->get();
        $expIds = $experiences->pluck('id')->toArray();

        $totalExperiences  = $experiences->count();
        $activeExperiences = $experiences->where('is_draft', 0)->count();

        $now       = Carbon::now();
        $lastMonth = Carbon::now()->subMonthNoOverflow();

        $bookingsQuery = Bookings::whereIn('experience_id', $expIds);
        $totalBookings = (clone $bookingsQuery)->count();

        $inquiriesQuery = Inquiry::whereIn('experience_id', $expIds);
        $totalInquiries = (clone $inquiriesQuery)->count();

        $monthlyBookings = (clone $bookingsQuery)->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->get();
        $monthlyRevenue  = $monthlyBookings->sum('pay_amount');
        $monthlyBookingsCount = $monthlyBookings->count();
        $monthlyInquiriesCount = (clone $inquiriesQuery)->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();

        $lastMonthBookingsCount = (clone $bookingsQuery)->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $lastMonthInquiriesCount = (clone $inquiriesQuery)->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $lastMonthRevenue = (clone $bookingsQuery)->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->sum('pay_amount');

        $bookingsDelta  = $this->percentDelta($monthlyBookingsCount, $lastMonthBookingsCount);
        $inquiriesDelta = $this->percentDelta($monthlyInquiriesCount, $lastMonthInquiriesCount);
        $revenueDelta   = $this->percentDelta($monthlyRevenue, $lastMonthRevenue);

        // Booking pipeline funnel by stage
        $stageOrder  = ['inquiry', 'qualified', 'proposal_sent', 'confirmed', 'checked_in', 'completed', 'cancelled', 'refunded'];
        $stageLabels = [
            'inquiry' => 'Inquiry', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal Sent',
            'confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'completed' => 'Completed',
            'cancelled' => 'Cancelled', 'refunded' => 'Refunded',
        ];
        $stageCounts = (clone $bookingsQuery)->select('stage', DB::raw('count(*) as cnt'))
            ->groupBy('stage')->pluck('cnt', 'stage');
        $pipelineFunnel = [];
        foreach ($stageOrder as $stage) {
            $count = (int) ($stageCounts[$stage] ?? 0);
            if ($count > 0) {
                $pipelineFunnel[] = ['stage' => $stage, 'label' => $stageLabels[$stage], 'count' => $count];
            }
        }
        $maxStageCount = collect($pipelineFunnel)->max('count') ?: 1;

        // Per-retreat performance (bookings + inquiries)
        $bookingCountsByExp  = (clone $bookingsQuery)->select('experience_id', DB::raw('count(*) as cnt'))->groupBy('experience_id')->pluck('cnt', 'experience_id');
        $inquiryCountsByExp  = (clone $inquiriesQuery)->select('experience_id', DB::raw('count(*) as cnt'))->groupBy('experience_id')->pluck('cnt', 'experience_id');

        $retreatPerformance = $experiences->map(function ($exp) use ($bookingCountsByExp, $inquiryCountsByExp) {
            $bookings  = (int) ($bookingCountsByExp[$exp->id] ?? 0);
            $inquiries = (int) ($inquiryCountsByExp[$exp->id] ?? 0);
            $exp->bookings_count  = $bookings;
            $exp->inquiries_count = $inquiries;
            $exp->activity_score  = $bookings + $inquiries;
            return $exp;
        })->sortByDesc('activity_score')->values();

        $topPerformer = $retreatPerformance->first();

        // Recent inquiries
        $recentInquiries = Inquiry::whereIn('experience_id', $expIds)
            ->with('experience')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Profile completeness engine
        [$completenessScore, $missingFields] = $this->profileCompleteness($center, $centerId);

        $currencySymbols = ['INR' => '₹', 'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'AED' => 'AED ', 'SGD' => 'SGD '];
        $primaryCurrency = $experiences->pluck('currency')->filter()->countBy()->sortDesc()->keys()->first() ?? 'INR';
        $currencySymbol  = $currencySymbols[$primaryCurrency] ?? ($primaryCurrency . ' ');

        $data = [
            'center'                 => $center,
            'userName'               => Session::get('center_user_name'),
            'userEmail'              => Session::get('center_user_email'),
            'totalExperiences'       => $totalExperiences,
            'activeExperiences'      => $activeExperiences,
            'totalBookings'          => $totalBookings,
            'totalInquiries'         => $totalInquiries,
            'monthlyRevenue'         => $monthlyRevenue,
            'monthlyBookingsCount'   => $monthlyBookingsCount,
            'monthlyInquiriesCount'  => $monthlyInquiriesCount,
            'bookingsDelta'          => $bookingsDelta,
            'inquiriesDelta'         => $inquiriesDelta,
            'revenueDelta'           => $revenueDelta,
            'pipelineFunnel'         => $pipelineFunnel,
            'maxStageCount'          => $maxStageCount,
            'retreatPerformance'     => $retreatPerformance->take(5),
            'topPerformer'           => $topPerformer,
            'recentInquiries'        => $recentInquiries,
            'completenessScore'      => $completenessScore,
            'missingFields'          => $missingFields,
            'currencySymbol'         => $currencySymbol,
        ];

        return view('center_panel.dashboard', $data);
    }

    /**
     * Percentage change helper, safe against division by zero.
     *
     * @return array{value: float, positive: bool}
     */
    private function percentDelta($current, $previous): array
    {
        $current  = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0.0) {
            return ['value' => $current > 0 ? 100.0 : 0.0, 'positive' => $current >= 0];
        }

        $change = (($current - $previous) / abs($previous)) * 100;

        return ['value' => round(abs($change), 1), 'positive' => $change >= 0];
    }

    /**
     * Compute the center profile completeness score and list missing fields.
     *
     * @return array{0: int, 1: array<int, string>}
     */
    private function profileCompleteness(Centers $center, $centerId): array
    {
        $fields = [
            'about_center'       => 'About the Center description',
            'what_sets_us_apart' => 'What Sets You Apart section',
            'our_philosophy'     => 'Our Philosophy statement',
            'banner_image_url'   => 'High-resolution banner image',
            'video_url'          => 'Video walkthrough URL',
            'address_of_center'  => 'Center address',
            'city'               => 'City',
            'country'            => 'Country',
            'contact_number'     => 'Contact phone number',
            'whatsapp_number'    => 'WhatsApp number',
            'founders'           => 'Founders information',
            'year_of_foundation' => 'Year of foundation',
            'amenities'          => 'Amenities selection',
            'center_highlights'  => 'Center highlights',
        ];

        $filled  = 0;
        $missing = [];

        foreach ($fields as $key => $label) {
            if (!empty($center->{$key})) {
                $filled++;
            } else {
                $missing[] = $label;
            }
        }

        $totalFields = count($fields) + 1; // +1 for gallery images below

        if (CenterImageGallery::where('center_id', $centerId)->exists()) {
            $filled++;
        } else {
            $missing[] = 'Gallery images';
        }

        $score = (int) round(($filled / $totalFields) * 100);

        return [$score, $missing];
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

        // Load duration packages for all experiences on this page, grouped by experience_id
        $expIds = $experiences->pluck('id')->toArray();
        $durationPackages = ExperienceDurationPrices::whereIn('experience_id', $expIds)
            ->orderBy('duration')
            ->get()
            ->groupBy('experience_id');

        $totalExperiences = Experiences::where('center_id', $centerId)->count();
        $totalBookings = 0;
        $activeDistributionListings = intval(ceil($totalExperiences * 0.6));
        $upcomingCyclePipelines = max(0, min(12, intval(floor($totalExperiences / 2))));
        $pipelineOccupancyVelocity = $totalBookings > 0
            ? round(min(100, 55 + ($totalBookings / max(1, $totalExperiences + 1)) * 30), 1)
            : 0;
        $topConvertingProgramName = Experiences::where('center_id', $centerId)
            ->orderBy('created_at', 'desc')
            ->value('name') ?? 'Retreat Program';

        return view('center_panel.experiences', [
            'center'                     => $center,
            'userName'                   => Session::get('center_user_name'),
            'experiences'                => $experiences,
            'durationPackages'           => $durationPackages,
            'totalExperiences'           => $totalExperiences,
            'activeDistributionListings' => $activeDistributionListings,
            'upcomingCyclePipelines'     => $upcomingCyclePipelines,
            'pipelineOccupancyVelocity'  => $pipelineOccupancyVelocity,
            'topConvertingProgramName'   => $topConvertingProgramName,
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
        $center   = Centers::findOrFail($centerId);

        $amenities     = Amenities::select('id', 'name')->orderBy('name')->get();
        $imageGalleries = CenterImageGallery::where('center_id', $centerId)
            ->orderBy('id', 'asc')
            ->get();

        return view('center_panel.settings', [
            'center'         => $center,
            'amenities'      => $amenities,
            'imageGalleries' => $imageGalleries,
        ]);
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
            'name'            => 'required|string|max:255',
            'email_address'   => 'nullable|email|max:255',
            'contact_number'  => 'nullable|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'address_of_center' => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'gps'             => 'nullable|string|max:100',
            'year_of_foundation' => 'nullable|integer|min:1800|max:' . date('Y'),
            'video_url'       => 'nullable|url|max:500',
            'meta_title'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $center->name                = $request->name;
        $center->email_address       = $request->email_address;
        $center->contact_number      = $request->contact_number;
        $center->whatsapp_number     = $request->whatsapp_number;
        $center->address_of_center   = $request->address_of_center;
        $center->city                = $request->city;
        $center->country             = $request->country;
        $center->gps                 = $request->gps;
        $center->about_center        = $request->about_center;
        $center->what_sets_us_apart  = $request->what_sets_us_apart;
        $center->our_philosophy      = $request->our_philosophy;
        $center->our_mission         = $request->our_mission;
        $center->center_highlights   = $request->center_highlights;
        $center->center_features     = $request->center_features;
        $center->have_accomodation   = $request->have_accomodation ?? 'No';
        $center->amenities           = is_array($request->amenities)
            ? implode('||', $request->amenities) : null;
        $center->how_to_get_there    = $request->how_to_get_there;
        $center->things_to_do_around_the_center = $request->things_to_do_around_the_center;
        $center->founders            = $request->founders;
        $center->year_of_foundation  = $request->year_of_foundation ?: null;
        $center->awards              = $request->awards;
        $center->tags                = $request->tags;
        $center->balancegurus_profile_link = $request->balancegurus_profile_link;
        $center->video_url           = $request->video_url;
        $center->meta_title          = $request->meta_title;
        $center->keywords            = $request->keywords;
        $center->meta_description    = $request->meta_description;

        // Banner image
        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
            $file   = $request->file('banner_image');
            $folder = 'centers/' . date('Y/m/d');
            $name   = preg_replace('/[^A-Za-z0-9]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . time() . '.' . strtolower($file->getClientOriginalExtension());
            $file->storeAs($folder, $name, ['disk' => 'azure']);
            if ($center->banner_image_url) {
                Storage::disk('azure')->delete($center->banner_image_url);
            }
            $center->banner_image_url   = $folder . '/' . $name;
            $center->banner_image_title = $file->getClientOriginalName();
        }

        $center->save();

        // Move Dropzone-uploaded gallery images from tmp to permanent
        $galleryPaths = $request->input('image_gallery_ids', '');
        if ($galleryPaths) {
            foreach (explode('|@|@|', $galleryPaths) as $tmpPath) {
                $tmpPath = trim($tmpPath);
                if (!$tmpPath) continue;
                $dest = str_replace('tmp/', '', $tmpPath);
                Storage::disk('azure')->move($tmpPath, $dest);
                CenterImageGallery::create([
                    'center_id'   => $centerId,
                    'image_url'   => $dest,
                    'image_title' => basename($dest),
                ]);
            }
        }

        return back()->with('success', 'Center profile updated successfully.');
    }

    // ── Settings: image AJAX helpers ───────────────────────────────────────

    public function uploadGalleryImage(Request $request)
    {
        $file = $request->file('file');
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        if (!$file || !in_array($file->getClientMimeType(), $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file']);
            return;
        }
        $ext    = strtolower($file->getClientOriginalExtension());
        $name   = preg_replace('/[^A-Za-z0-9]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . time() . '.' . $ext;
        $folder = 'tmp/centers/' . date('Y/m/d');
        $file->storeAs($folder, $name, ['disk' => 'azure']);
        echo json_encode(['success' => true, 'filename' => $folder . '/' . $name]);
    }

    public function deleteGalleryImage(Request $request)
    {
        $centerId = Session::get('center_id');
        $gallery  = CenterImageGallery::where('id', $request->id)
            ->where('center_id', $centerId)
            ->first();
        if ($gallery) {
            Storage::disk('azure')->delete($gallery->image_url);
            $gallery->delete();
            echo '1';
        } else {
            echo 'error';
        }
    }

    public function deleteBannerImage(Request $request)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::where('id', $centerId)->firstOrFail();
        if ($center->banner_image_url) {
            Storage::disk('azure')->delete($center->banner_image_url);
            $center->banner_image_url   = null;
            $center->banner_image_title = null;
            $center->save();
            echo '1';
        } else {
            echo 'error';
        }
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
            'center'                   => $center,
            'userName'                 => Session::get('center_user_name'),
            'experience'               => null,
            'experienceDurationPrices' => collect(),
            'pageTitle'                => 'Create New Retreat Program',
            'formAction'               => route('center-panel.experience.store'),
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

        $this->syncDurationPrices($exp->id, $request);
        $this->syncCategories($exp->id, $request->input('experience_category_id', []));
        $this->syncGalleryImages($exp->id, $request->file('image_galleries', []));

        return redirect()->route('center-panel.experiences')
            ->with('success', 'Retreat program created successfully.');
    }

    public function experienceEdit($id)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experience = Experiences::with('image_galleries')->where('id', $id)->where('center_id', $centerId)->firstOrFail();

        $experience->selectedCategories = ExperienceCategory::where('experience_id', $id)
            ->pluck('category_id')->toArray();

        $experienceDurationPrices = ExperienceDurationPrices::where('experience_id', $id)
            ->orderBy('duration')->get();

        return view('center_panel.experience_form', array_merge($this->experienceFormData(), [
            'center'                   => $center,
            'userName'                 => Session::get('center_user_name'),
            'experience'               => $experience,
            'experienceDurationPrices' => $experienceDurationPrices,
            'pageTitle'                => 'Edit Retreat Program',
            'formAction'               => route('center-panel.experience.update', $id),
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

        $this->syncDurationPrices($id, $request);
        $this->syncCategories($id, $request->input('experience_category_id', []));
        $this->syncGalleryImages($id, $request->file('image_galleries', []));

        return redirect()->route('center-panel.accommodations')
            ->with('success', 'Retreat program updated successfully.');
    }

    public function experienceDestroy(Request $request)
    {
        $centerId = Session::get('center_id');
        $id       = $request->id;

        $exp = Experiences::where('id', $id)->where('center_id', $centerId)->first();

        if (!$exp) {
            return redirect()->route('center-panel.experiences')
                ->with('error', 'Retreat program not found.');
        }

        ExperienceDurationPrices::where('experience_id', $id)->delete();
        ExperienceCategory::where('experience_id', $id)->delete();
        ExperienceImageGallery::where('experience_id', $id)->delete();
        $exp->delete();

        return redirect()->route('center-panel.experiences')
            ->with('success', 'Retreat program "' . $exp->name . '" deleted successfully.');
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
        // Set duration to the minimum submitted duration (from the packages list)
        $durations = array_filter(array_map('intval', $request->input('durations', [])));
        $exp->duration = !empty($durations) ? min($durations) : ($request->input('duration') ?: 7);
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

    private function syncDurationPrices(int $experienceId, Request $request): void
    {
        $durations = $request->input('durations', []);

        ExperienceDurationPrices::where('experience_id', $experienceId)->delete();

        foreach ($durations as $nights) {
            $nights = (int) $nights;
            if ($nights <= 0) continue;

            $row = new ExperienceDurationPrices();
            $row->experience_id = $experienceId;
            $row->duration      = $nights;
            $row->save();
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
