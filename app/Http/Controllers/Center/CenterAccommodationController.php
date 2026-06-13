<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Centers;
use App\Accomodation;
use App\CenterAccomodations;
use App\AccomodationImageGallery;
use Storage;

class CenterAccommodationController extends Controller
{
    public function __construct()
    {
        $this->middleware('center.auth');
    }

    public function index()
    {
        $centerId = Session::get('center_id');
        $center = Centers::findOrFail($centerId);

        $accommodations = Accomodation::select('accomodation.*')
            ->join('center_accomodations', 'center_accomodations.accomodation_id', '=', 'accomodation.id')
            ->where('center_accomodations.center_id', $centerId)
            ->orderBy('accomodation.name', 'ASC')
            ->get();

        return view('center_panel.accommodations', [
            'center'         => $center,
            'accommodations' => $accommodations,
        ]);
    }

    public function create()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        return view('center_panel.accommodation_form', [
            'center'         => $center,
            'accommodation'  => null,
            'imagegalleries' => [],
            'pageTitle'      => 'Add New Accommodation',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $centerId = Session::get('center_id');

        $accommodation = new Accomodation();
        $accommodation->name              = $request->name;
        $accommodation->slug              = $request->slug;
        $accommodation->description       = $request->description;
        $accommodation->max_guest_in_room = $request->max_guest_in_room;

        if ($request->file('banner_image')) {
            $accommodation->banner_image_url   = $this->uploadBannerImage($request);
            $accommodation->banner_image_title = $request->file('banner_image')->getClientOriginalName();
        }

        $accommodation->save();

        // Link to current center
        $link = new CenterAccomodations();
        $link->accomodation_id = $accommodation->id;
        $link->center_id       = $centerId;
        $link->save();

        $this->moveGalleryImages($request->image_gallery_ids ?? '', $accommodation->id);

        return redirect()->route('center-panel.accommodations')
            ->with('success', 'Accommodation "' . $accommodation->name . '" created successfully.');
    }

    public function edit($id)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        if (!$this->belongsToCenter($id, $centerId)) {
            return redirect()->route('center-panel.accommodations')
                ->with('error', 'Accommodation not found.');
        }

        $accommodation  = Accomodation::findOrFail($id);
        $imagegalleries = AccomodationImageGallery::get_data([
            'select' => ['id', 'accomodation_id', 'image_url', 'image_title'],
            'where'  => ['accomodation_id' => $id],
        ]);

        return view('center_panel.accommodation_form', [
            'center'         => $center,
            'accommodation'  => $accommodation,
            'imagegalleries' => $imagegalleries,
            'pageTitle'      => 'Edit Accommodation',
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $centerId = Session::get('center_id');

        if (!$this->belongsToCenter($id, $centerId)) {
            return redirect()->route('center-panel.accommodations')
                ->with('error', 'Accommodation not found.');
        }

        $accommodation = Accomodation::findOrFail($id);
        $accommodation->name              = $request->name;
        $accommodation->slug              = $request->slug;
        $accommodation->description       = $request->description;
        $accommodation->max_guest_in_room = $request->max_guest_in_room;

        if ($request->file('banner_image')) {
            $accommodation->banner_image_url   = $this->uploadBannerImage($request);
            $accommodation->banner_image_title = $request->file('banner_image')->getClientOriginalName();
        }

        $accommodation->save();

        $this->moveGalleryImages($request->image_gallery_ids ?? '', $id);

        return redirect()->route('center-panel.accommodations')
            ->with('success', 'Accommodation "' . $accommodation->name . '" updated successfully.');
    }

    public function destroy(Request $request)
    {
        $centerId = Session::get('center_id');
        $id       = $request->id;

        if (!$this->belongsToCenter($id, $centerId)) {
            return redirect()->route('center-panel.accommodations')
                ->with('error', 'Accommodation not found.');
        }

        // Remove only this center's link
        CenterAccomodations::where('accomodation_id', $id)
            ->where('center_id', $centerId)
            ->delete();

        // Delete the accommodation record if no other centers are linked to it
        if (!CenterAccomodations::where('accomodation_id', $id)->exists()) {
            Accomodation::find($id)?->delete();
        }

        return redirect()->route('center-panel.accommodations')
            ->with('success', 'Accommodation removed successfully.');
    }

    public function uploadGalleryImage(Request $request)
    {
        $file    = $request->file('file');
        $allowed = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg', 'image/webp'];

        if ($file && in_array($file->getClientMimeType(), $allowed)) {
            $ext      = strtolower($file->getClientOriginalExtension());
            $base     = preg_replace("~\." . $ext . "$~i", '', strtolower($file->getClientOriginalName()));
            $filename = $base . time() . '.' . $ext;
            $folder   = 'tmp/accomodations/' . date('Y/m/d');
            $file->storeAs($folder, $filename, ['disk' => 'azure']);
            echo json_encode(['success' => true, 'filename' => $folder . '/' . $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        }
    }

    public function deleteGalleryImage(Request $request)
    {
        $centerId = Session::get('center_id');
        $gallery  = AccomodationImageGallery::find($request->id);

        if ($gallery && $this->belongsToCenter($gallery->accomodation_id, $centerId)) {
            Storage::disk('azure')->delete($gallery->image_url);
            $gallery->delete();
            echo true;
        } else {
            echo 'Something went wrong.';
        }
    }

    public function deleteBannerImage(Request $request)
    {
        $centerId      = Session::get('center_id');
        $id            = $request->id;
        $accommodation = Accomodation::find($id);

        if ($accommodation && $this->belongsToCenter($id, $centerId) && $accommodation->banner_image_url) {
            Storage::disk('azure')->delete($accommodation->banner_image_url);
            $accommodation->banner_image_url   = null;
            $accommodation->banner_image_title = null;
            $accommodation->save();
            echo true;
        } else {
            echo 'Something went wrong.';
        }
    }

    private function belongsToCenter(int $accommodationId, int $centerId): bool
    {
        return CenterAccomodations::where('accomodation_id', $accommodationId)
            ->where('center_id', $centerId)
            ->exists();
    }

    private function uploadBannerImage(Request $request): string
    {
        $file     = $request->file('banner_image');
        $ext      = strtolower($file->getClientOriginalExtension());
        $base     = preg_replace("~\." . $ext . "$~i", '', strtolower($file->getClientOriginalName()));
        $filename = $base . time() . '.' . $ext;
        $folder   = 'accomodations/' . date('Y/m/d');
        $file->storeAs($folder, $filename, ['disk' => 'azure']);
        return $folder . '/' . $filename;
    }

    private function moveGalleryImages(string $imageGalleryIds, int $accommodationId): void
    {
        if (empty($imageGalleryIds)) return;

        foreach (explode('|@|@|', $imageGalleryIds) as $tmpPath) {
            if (empty($tmpPath)) continue;
            $dest = str_replace('tmp/', '', $tmpPath);
            Storage::disk('azure')->move($tmpPath, $dest);
            $gallery                = new AccomodationImageGallery();
            $gallery->accomodation_id = $accommodationId;
            $gallery->image_title   = basename($dest);
            $gallery->image_url     = $dest;
            $gallery->save();
        }
    }
}
