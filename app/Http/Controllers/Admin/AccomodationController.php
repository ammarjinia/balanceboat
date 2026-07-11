<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Accomodation;
use Storage;
use DB;

class AccomodationController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Accomodation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['accomodations'] = Accomodation::select("accomodation.id", "accomodation.name", "accomodation.slug", "accomodation.description", DB::raw('group_concat(`centers`.`name`) as CenterName'))
                ->leftJoin("center_accomodations", "center_accomodations.accomodation_id", "=", "accomodation.id")
                ->leftJoin("centers", "centers.id", "=", "center_accomodations.center_id")
                ->groupBy("accomodation.id", "accomodation.name", "accomodation.slug", "accomodation.description")
                ->orderBy("accomodation.name", "ASC")
                ->get();
        return view("admin.accomodation.index", $data);
    }

    /**
     * Show the form for creating a new Accomodation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($param);
        return view('admin.accomodation.create', $data);
    }

    /**
     * Store a newly created Accomodation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $name = $request['name'];
        $slug = $request['slug'];
        $max_guest_in_room = $request['max_guest_in_room'];
        $center_ids = (is_array($request['center_id'])) ? $request['center_id'] : array();
        $description = $request['description'];
        if ($request->file('banner_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('banner_image')->getClientOriginalName();
        }
        $image_galleries = (!empty($request['image_gallery_ids'])) ? $request['image_gallery_ids'] : "";
        if (!empty($request['id'])) {
            $objAccomodation = Accomodation::find($request['id']);
            if ($objAccomodation) {
                $objAccomodation->name = $name;
                $objAccomodation->slug = $slug;
                $objAccomodation->description = $description;
                $objAccomodation->max_guest_in_room = $max_guest_in_room;
                if ($request['banner_image']) {
                    $objAccomodation->banner_image_title = $imageTitle;
                    $objAccomodation->banner_image_url = $imageUrl;
                }
                try {
                    $objAccomodation->save();
                    $accomodation_id = $request['id'];

                    // Accomodation Center Mapping
                    $existCenterIds = (@$request['hdn_center_id']) ? explode("||", $request['hdn_center_id']) : array();
                    if (!empty(@$existCenterIds)) {
                        foreach (@$existCenterIds as $hdn_center_id) {
                            if (!in_array($hdn_center_id, @$center_ids) or empty(@$center_ids)) {
                                $objCenterAccomodation = \App\CenterAccomodations::select("id")->where(array("center_id" => $hdn_center_id, "accomodation_id" => $accomodation_id))->first();
                                if (!empty($objCenterAccomodation)) {
                                    $objCenterAccomodation->delete();
                                }
                            }
                        }
                    }

                    if (!empty($center_ids)) {
                        foreach ($center_ids as $center_id) {
                            if (!in_array($center_id, @$existCenterIds)) {
                                $objCenterAccomodations = new \App\CenterAccomodations();
                                $objCenterAccomodations->accomodation_id = $accomodation_id;
                                $objCenterAccomodations->center_id = $center_id;
                                $objCenterAccomodations->save();
                            }
                        }
                    }

                    // Move Images from tmp to src
                    if (!empty(@$image_galleries)) {
                        $image_galleries_array = explode("|@|@|", @$image_galleries);
                        foreach ($image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            Storage::disk('s3')->move($galimage, $dest);
                            $objAccomodationImageGallery = new \App\AccomodationImageGallery();
                            $objAccomodationImageGallery->accomodation_id = $accomodation_id;
                            $objAccomodationImageGallery->image_title = basename($dest);
                            $objAccomodationImageGallery->image_url = $dest;
                            $objAccomodationImageGallery->save();
                        }
                    }
                } catch (Exception $e) {
                    return redirect('bbadmin/accomodations')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/accomodations')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/accomodations')
                            ->with('flash_message', 'Accomodation ' . $objAccomodation->name . ' updated');
        } else {
            $objAccomodation = new Accomodation();
            $objAccomodation->name = $name;
            $objAccomodation->slug = $slug;
            $objAccomodation->description = $description;
            $objAccomodation->max_guest_in_room = $max_guest_in_room;
            if ($request['banner_image']) {
                $objAccomodation->banner_image_title = $imageTitle;
                $objAccomodation->banner_image_url = $imageUrl;
            }
            try {
                $objAccomodation->save();
                $accomodation_id = $objAccomodation->id;

                // Accomodation Center Mapping
                if (!empty(@$center_ids)) {
                    foreach (@$center_ids as $center_id) {
                        $objCenterAccomodations = new \App\CenterAccomodations();
                        $objCenterAccomodations->accomodation_id = $accomodation_id;
                        $objCenterAccomodations->center_id = $center_id;
                        $objCenterAccomodations->save();
                    }
                }

                // Move Images from tmp to src
                if (!empty(@$image_galleries)) {
                    $image_galleries_array = explode("|@|@|", @$image_galleries);
                    foreach ($image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        Storage::disk('s3')->move($galimage, $dest);
                        $objCenterImageGallery = new \App\CenterImageGallery();
                        $objCenterImageGallery->accomodation_id = $accomodation_id;
                        $objCenterImageGallery->image_title = basename($dest);
                        $objCenterImageGallery->image_url = $dest;
                        $objCenterImageGallery->save();
                    }
                }
            } catch (Exception $e) {
                return redirect('bbadmin/accomodations')
                                ->with('flash_error_message', 'Something went wrong');
            }

            return redirect('bbadmin/accomodations')
                            ->with('flash_message', 'Accomodation ' . $objAccomodation->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Accomodation.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/accomodation");
        }

        $cparam['order'] = "ASC";
        $cparam['orderby'] = "name";
        $data['centers'] = \App\Centers::get_data($cparam);

        // Get Center Accomodations
        $paramCA['select'] = array('id', 'accomodation_id', 'center_id');
        $paramCA['where'] = array("accomodation_id" => $id);
        $center_accomodations = \App\CenterAccomodations::get_data($paramCA);
        $data['center_accomodations'] = array();
        if (!empty($center_accomodations)) {
            foreach ($center_accomodations as $center_accomodation) {
                $data['center_accomodations'][$center_accomodation->id] = $center_accomodation->center_id;
            }
        }

        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['eaccomodation'] = Accomodation::get_data($param);
        $data['eaccomodation'] = $data['eaccomodation'][0];

        // Get Accomodation Gallery Images
        $paramAGI['select'] = array('id', 'accomodation_id', 'image_url', 'image_title');
        $paramAGI['where'] = array("accomodation_id" => $id);
        $data['imagegalleries'] = \App\AccomodationImageGallery::get_data($paramAGI);
        return view('admin.accomodation.edit', $data);
    }

    /**
     * Remove the specified Accomodation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objAccomodation = Accomodation::find($id);
            if (!empty($objAccomodation)) {
                $objAccomodation->delete();
            } else {
                return redirect('bbadmin/accomodations')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/accomodations')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/accomodations')
                        ->with('flash_message', 'Accomodation deleted successfully');
    }

    public function upload_image($request) {
        $file = $request->file("banner_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ("image/webp")
            ) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'accomodations' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objAccomodation = Accomodation::find($id);
            if (!empty($objAccomodation)) {
                Storage::disk('s3')->delete($objAccomodation->banner_image_url);
                $objAccomodation->banner_image_title = null;
                $objAccomodation->banner_image_url = null;
                $objAccomodation->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Upload Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_gallery_image(Request $request) {
        $file = $request->file('file');
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ("image/webp")
            ) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/accomodations' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            $saveFileName = $folderName . "/" . $renamefile;
            echo (json_encode(array('success' => true, 'filename' => $saveFileName)));
        } else {
            echo (json_encode(array('success' => false, 'message' => 'Either file is not valid or file not found')));
        }
    }

    /**
     * Delete Image Gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_gallery_image(Request $request) {
        try {
            $id = $request['id'];
            $objAccomodationImageGallery = \App\AccomodationImageGallery::find($id);
            if (!empty($objAccomodationImageGallery)) {
                Storage::disk('s3')->delete($objAccomodationImageGallery->image_url);
                $objAccomodationImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
