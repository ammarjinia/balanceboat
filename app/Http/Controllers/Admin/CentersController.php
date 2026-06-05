<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Centers;
use Storage;
use DB;

class CentersController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Centers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['centers'] = Centers::select("centers.id", "centers.name", "centers.slug", "centers.updated_at", DB::raw('group_concat(`category`.`name`) as LocationName'))
                ->leftJoin("center_locations", "center_locations.center_id", "=", "centers.id")
                ->leftJoin("category", "category.id", "=", "center_locations.location_id")
                ->groupBy("centers.id")
                ->orderBy("centers.name", "ASC")
                ->get();
        return view("admin.centers.index", $data);
    }

    /**
     * Show the form for creating a new Centers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['center_types'] = \App\CenterTypes::get_data($param);
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['expertises'] = \App\Expertise::get_data($param);
        $data['teachers'] = \App\Teachers::get_data($param);
        $data['accomodations'] = \App\Accomodation::get_data($param);
        $data['categories'] = \App\Category::get_data($param);
        $data['amenities'] = \App\Amenities::select("id","name")->orderBy("name","ASC")->get();
        $data['owners'] = User::role('Owner')->orderBy('first_name', 'ASC')->get();
        return view('admin.centers.create', $data);
    }

    /**
     * Store a newly created Center in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'slug' => 'required',
        ];

        // If owner_email is provided and no existing owner selected, validate email and password
        if ($request->filled('owner_email')) {
            $existingUser = User::where('email', $request['owner_email'])->first();
            if (!$existingUser) {
                // New user: email must be unique, password required
                $rules['owner_email'] = 'email|unique:users,email';
                $rules['owner_password'] = 'required|min:6';
            } else {
                // Existing user: ensure it's the same owner already linked to this center
                if (!empty($request['id'])) {
                    $currentCenter = \App\Centers::find($request['id']);
                    if ($currentCenter && $currentCenter->user_id != $existingUser->id) {
                        $rules['owner_email'] = 'email|unique:users,email';
                    }
                } else {
                    // Creating a new center but email already taken
                    $rules['owner_email'] = 'email|unique:users,email';
                }
                if ($request->filled('owner_password')) {
                    $rules['owner_password'] = 'min:6';
                }
            }
        }

        $this->validate($request, $rules);

        // Handle Owner user creation/update
        $user_id = null;
        $owner_email = $request['owner_email'];
        $owner_password = $request['owner_password'];

        if (!empty($owner_email)) {
            $ownerUser = User::where('email', $owner_email)->first();
            if ($ownerUser) {
                // Update existing user's password if provided
                if (!empty($owner_password)) {
                    $ownerUser->password = $owner_password;
                    $ownerUser->save();
                }
                // Ensure user has Owner role
                if (!$ownerUser->hasRole('Owner')) {
                    $ownerUser->assignRole('Owner');
                }
                $user_id = $ownerUser->id;
            } else {
                // Create new Owner user
                $ownerUser = User::create([
                    'first_name' => $request['name'],
                    'last_name' => '',
                    'email' => $owner_email,
                    'password' => $owner_password,
                ]);
                $ownerUser->assignRole('Owner');
                $user_id = $ownerUser->id;
            }
        }

        $name = $request['name'];
        $slug = $request['slug'];
        $meta_title = $request['meta_title'];
        $keywords = $request['keywords'];
        $meta_description = $request['meta_description'];
        $locations = (is_array($request['location'])) ? $request['location'] : array();
        $gps = $request['gps'];
        $founders = $request['founders'];
        $meta_description = $request['meta_description'];
        $center_type = $request['center_type'];
        $speciality_id = (is_array($request['speciality_id'])) ? implode("||", $request['speciality_id']) : "";
        $year_of_foundation = $request['year_of_foundation'];
        $about_center = $request['about_center'];
        $what_sets_us_apart = $request['what_sets_us_apart'];
        $our_philosophy = $request['our_philosophy'];
        $our_mission = $request['our_mission'];
        $center_highlights = $request['center_highlights'];
        $airport_name = $request['airport_name'];
        $pickup_drop_cost = $request['pickup_drop_cost'];
        $teacher_ids = (is_array($request['teacher_id'])) ? $request['teacher_id'] : array();
        $accomodation_ids = (is_array($request['accomodation_id'])) ? $request['accomodation_id'] : array();
        $certificate_id = (is_array($request['certificate_id'])) ? implode("||", $request['certificate_id']) : "";
        $awards = $request['awards'];
        $address_of_center = $request['address_of_center'];
        $city = $request['city'];
        $country = $request['country'];
        $email_address = $request['email_address'];
        $contact_number = $request['contact_number'];
        $whatsapp_number = $request['whatsapp_number'];
        $balancegurus_profile_link = $request['balancegurus_profile_link'];
        $tags = $request['tags'];
        $category_id = $request['category_id'];
        $have_accomodation = $request['have_accomodation'];
        $center_features = $request['center_features'];
        $accomodation_overview = $request['accomodation_overview'];
        $how_to_get_there = $request['how_to_get_there'];
        $things_to_do_around_the_center = $request['things_to_do_around_the_center'];
        $videoUrl = $request['video'];
        $amenities = (is_array($request['amenities'])) ? implode("||", $request['amenities']) : NULL;
        
        if ($request->file('banner_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('banner_image')->getClientOriginalName();
        }
        if ($request->file('accomodation_banner_image')) {
            $acmimageUrl = $this->upload_accomodation_image($request);
            $acmimageTitle = $request->file('accomodation_banner_image')->getClientOriginalName();
        }
        $image_galleries = (!empty($request['image_gallery_ids'])) ? $request['image_gallery_ids'] : "";
        $is_draft = $request['is_draft'];
        if (!empty($request['id'])) {
            $objCenter = Centers::find($request['id']);
            if ($objCenter) {
                $objCenter->name = $name;
                $objCenter->slug = $slug;
                $objCenter->user_id = $user_id;
                $objCenter->meta_title = $meta_title;
                $objCenter->keywords = $keywords;
                $objCenter->meta_description = $meta_description;
                //$objCenter->location = $location;
                $objCenter->center_type = $center_type;
                $objCenter->founders = $founders;
                $objCenter->gps = $gps;
                $objCenter->speciality_id = $speciality_id;
                $objCenter->year_of_foundation = $year_of_foundation;
                $objCenter->about_center = $about_center;
                $objCenter->what_sets_us_apart = $what_sets_us_apart;
                $objCenter->our_philosophy = $our_philosophy;
                $objCenter->our_mission = $our_mission;
                $objCenter->pickup_drop_cost = $pickup_drop_cost;
                $objCenter->airport_name = $airport_name;
                $objCenter->center_highlights = $center_highlights;
                $objCenter->certificate_id = $certificate_id;
                $objCenter->awards = $awards;
                $objCenter->address_of_center = $address_of_center;
                $objCenter->city = $city;
                $objCenter->country = $country;
                $objCenter->email_address = $email_address;
                $objCenter->contact_number = $contact_number;
                $objCenter->whatsapp_number = $whatsapp_number;
                $objCenter->balancegurus_profile_link = $balancegurus_profile_link;
                $objCenter->tags = $tags;
                $objCenter->category_id = $category_id;
                $objCenter->have_accomodation = $have_accomodation;
                $objCenter->center_features = $center_features;
                $objCenter->accomodation_overview = $accomodation_overview;
                $objCenter->how_to_get_there = $how_to_get_there;
                $objCenter->things_to_do_around_the_center = $things_to_do_around_the_center;
                $objCenter->video_url = $videoUrl;
                $objCenter->amenities = $amenities;
                $objCenter->is_draft = $is_draft;
                if ($request['banner_image']) {
                    $objCenter->banner_image_title = $imageTitle;
                    $objCenter->banner_image_url = $imageUrl;
                }
                if ($request['accomodation_banner_image']) {
                    $objCenter->accomodation_banner_image_title = $acmimageTitle;
                    $objCenter->accomodation_banner_image_url = $acmimageUrl;
                }
                try {
                    $objCenter->save();
                    $center_id = $request['id'];

                    // Center Teacher Mapping
                    $existTeacherIds = (@$request['hdn_teacher_id']) ? explode("||", $request['hdn_teacher_id']) : array();
                    if (!empty($existTeacherIds)) {
                        foreach (@$existTeacherIds as $hdn_teacher_id) {
                            if (!in_array($hdn_teacher_id, @$teacher_ids) or empty(@$teacher_ids)) {
                                $objCenterTeahcer = \App\CenterTeachers::select("id")->where(array("teacher_id" => $hdn_teacher_id, "center_id" => $center_id))->first();
                                if (!empty($objCenterTeahcer)) {
                                    $objCenterTeahcer->delete();
                                }
                            }
                        }
                    }

                    if (!empty($teacher_ids)) {
                        foreach ($teacher_ids as $teacher_id) {
                            if (!in_array($teacher_id, @$existTeacherIds)) {
                                $objCenterTeachers = new \App\CenterTeachers();
                                $objCenterTeachers->teacher_id = $teacher_id;
                                $objCenterTeachers->center_id = $center_id;
                                $objCenterTeachers->save();
                            }
                        }
                    }

                    // Center Accomodation Mapping
                    $existAccomodationIds = (@$request['hdn_accomodation_id']) ? explode("||", $request['hdn_accomodation_id']) : array();
                    if (!empty(@$existAccomodationIds)) {
                        foreach (@$existAccomodationIds as $hdn_accomodation_id) {
                            if (!in_array($hdn_accomodation_id, @$accomodation_ids) or empty(@$accomodation_ids)) {
                                $objCenterAccomodation = \App\CenterAccomodations::select("id")->where(array("accomodation_id" => $hdn_accomodation_id, "center_id" => $center_id))->first();
                                if (!empty(@$objCenterAccomodation)) {
                                    $objCenterAccomodation->delete();
                                }
                            }
                        }
                    }

                    if (!empty(@$accomodation_ids)) {
                        foreach (@$accomodation_ids as $accomodation_id) {
                            if (!in_array($accomodation_id, @$existAccomodationIds)) {
                                $objCenterAccomodation = new \App\CenterAccomodations();
                                $objCenterAccomodation->accomodation_id = $accomodation_id;
                                $objCenterAccomodation->center_id = $center_id;
                                $objCenterAccomodation->save();
                            }
                        }
                    }

                    // Center Location Mapping
                    $existLocations = (@$request['hdn_location_id']) ? explode("||", $request['hdn_location_id']) : array();
                    if (!empty(@$existLocations)) {
                        foreach (@$existLocations as $hdn_location_id) {
                            if (!in_array($hdn_location_id, @$locations) or empty(@$locations)) {
                                $objCenterLocation = \App\CenterLocations::select("id")->where(array("location_id" => $hdn_location_id, "center_id" => $center_id))->first();
                                if (!empty($objCenterLocation)) {
                                    $objCenterLocation->delete();
                                }
                            }
                        }
                    }

                    if (!empty($locations)) {
                        foreach (@$locations as $location) {
                            if (!in_array($location, @$existLocations)) {
                                $objCenterLocations = new \App\CenterLocations();
                                $objCenterLocations->location_id = $location;
                                $objCenterLocations->center_id = $center_id;
                                $objCenterLocations->save();
                            }
                        }
                    }

                    // Move Images from tmp to src
                    if (!empty(@$image_galleries)) {
                        $image_galleries_array = explode("|@|@|", @$image_galleries);
                        foreach ($image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            Storage::disk('azure')->move($galimage, $dest);
                            $objCenterImageGallery = new \App\CenterImageGallery();
                            $objCenterImageGallery->center_id = $center_id;
                            $objCenterImageGallery->image_title = basename($dest);
                            $objCenterImageGallery->image_url = $dest;
                            $objCenterImageGallery->save();
                        }
                    }
                } catch (Exception $e) {
                    return redirect('bbadmin/centers')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/centers')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/centers')
                            ->with('flash_message', 'Center ' . $objCenter->name . ' updated');
        } else {
            try {
                $objCenter = new \App\Centers();
                $objCenter->name = $name;
                $objCenter->slug = $slug;
                $objCenter->user_id = $user_id;
                $objCenter->meta_title = $meta_title;
                $objCenter->keywords = $keywords;
                $objCenter->meta_description = $meta_description;
                $objCenter->founders = $founders;
                $objCenter->gps = $gps;
                $objCenter->center_type = $center_type;
                $objCenter->speciality_id = $speciality_id;
                $objCenter->year_of_foundation = $year_of_foundation;
                $objCenter->about_center = $about_center;
                $objCenter->what_sets_us_apart = $what_sets_us_apart;
                $objCenter->our_philosophy = $our_philosophy;
                $objCenter->our_mission = $our_mission;
                $objCenter->pickup_drop_cost = $pickup_drop_cost;
                $objCenter->airport_name = $airport_name;
                $objCenter->center_highlights = $center_highlights;
                $objCenter->certificate_id = $certificate_id;
                $objCenter->awards = $awards;
                $objCenter->address_of_center = $address_of_center;
                $objCenter->city = $city;
                $objCenter->country = $country;
                $objCenter->email_address = $email_address;
                $objCenter->contact_number = $contact_number;
                
                $objCenter->whatsapp_number = $whatsapp_number;
                $objCenter->balancegurus_profile_link = $balancegurus_profile_link;
                $objCenter->tags = $tags;
                $objCenter->category_id = $category_id;
                
                $objCenter->have_accomodation = $have_accomodation;
                $objCenter->center_features = $center_features;
                $objCenter->accomodation_overview = $accomodation_overview;
                $objCenter->how_to_get_there = $how_to_get_there;
                $objCenter->things_to_do_around_the_center = $things_to_do_around_the_center;
                $objCenter->video_url = $videoUrl;
                $objCenter->amenities = $amenities;
                $objCenter->is_draft = $is_draft;
                if ($request['banner_image']) {
                    $objCenter->banner_image_title = $imageTitle;
                    $objCenter->banner_image_url = $imageUrl;
                }
                if ($request['accomodation_banner_image']) {
                    $objCenter->accomodation_banner_image_title = $acmimageTitle;
                    $objCenter->accomodation_banner_image_url = $acmimageUrl;
                }
                $resCenter = $objCenter->save();
                $center_id = $objCenter->id;

                // Center Location Mapping
                if (!empty($locations)) {
                    foreach (@$locations as $location) {
                        $objCenterLocations = new \App\CenterLocations();
                        $objCenterLocations->location_id = $location;
                        $objCenterLocations->center_id = $center_id;
                        $objCenterLocations->save();
                    }
                }

                // Center Teacher Mapping
                if (!empty($teacher_ids)) {
                    foreach ($teacher_ids as $teacher_id) {
                        $objCenterTeachers = new \App\CenterTeachers();
                        $objCenterTeachers->teacher_id = $teacher_id;
                        $objCenterTeachers->center_id = $center_id;
                        $objCenterTeachers->save();
                    }
                }

                // Center Accomodation Mapping
                if (!empty(@$accomodation_ids)) {
                    foreach (@$accomodation_ids as $accomodation_id) {
                        $objCenterAccomodation = new \App\CenterAccomodations();
                        $objCenterAccomodation->accomodation_id = $accomodation_id;
                        $objCenterAccomodation->center_id = $center_id;
                        $objCenterAccomodation->save();
                    }
                }

                // Move Images from tmp to src
                if (!empty(@$image_galleries)) {
                    $image_galleries_array = explode("|@|@|", @$image_galleries);
                    foreach ($image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        Storage::disk('azure')->move($galimage, $dest);
                        $objCenterImageGallery = new \App\CenterImageGallery();
                        $objCenterImageGallery->center_id = $center_id;
                        $objCenterImageGallery->image_title = basename($dest);
                        $objCenterImageGallery->image_url = $dest;
                        $objCenterImageGallery->save();
                    }
                }
            } catch (Exception $e) {
                return redirect('bbadmin/centers')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/centers')
                            ->with('flash_message', 'Center ' . $objCenter->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Center.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/centers");
        }
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['center_types'] = \App\CenterTypes::get_data($param);
        $data['expertises'] = \App\Expertise::get_data($param);
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['accomodations'] = \App\Accomodation::get_data($param);
        $data['teachers'] = \App\Teachers::get_data($param);
        $data['categories'] = \App\Category::get_data($param);
        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['ecenter'] = Centers::get_data($param);
        $data['ecenter'] = (@$data['ecenter'][0]) ? : array();

        // Get Center Gallery Images
        $paramCGI['select'] = array('id', 'center_id', 'image_url', 'image_title', 'bg_exp_id');
        $paramCGI['where'] = array("center_id" => $id);
        $data['imagegalleries'] = \App\CenterImageGallery::get_data($paramCGI);

        // Get Center Locations
        $paramCL['select'] = array('id', 'location_id');
        $paramCL['where'] = array("center_id" => $id);
        $center_locations = \App\CenterLocations::get_data($paramCL);
        $data['center_locations'] = array();
        if (!empty($center_locations)) {
            foreach ($center_locations as $center_location) {
                $data['center_locations'][$center_location->id] = $center_location->location_id;
            }
        }

        // Get Center Teachers
        $paramCT['select'] = array('id', 'teacher_id');
        $paramCT['where'] = array("center_id" => $id);
        $center_teachers = \App\CenterTeachers::get_data($paramCT);
        $data['center_teachers'] = array();
        if (!empty($center_teachers)) {
            foreach ($center_teachers as $center_teacher) {
                $data['center_teachers'][$center_teacher->id] = $center_teacher->teacher_id;
            }
        }

        // Get Center Accomodations
        $paramCA['select'] = array('id', 'accomodation_id');
        $paramCA['where'] = array("center_id" => $id);
        $center_accomodations = \App\CenterAccomodations::get_data($paramCA);
        $data['center_accomodations'] = array();
        if (!empty(@$center_accomodations)) {
            foreach (@$center_accomodations as $center_accomodation) {
                $data['center_accomodations'][$center_accomodation->id] = $center_accomodation->accomodation_id;
            }
        }
        $data['amenities'] = \App\Amenities::select("id","name")->orderBy("name","ASC")->get();
        $data['owners'] = User::role('Owner')->orderBy('first_name', 'ASC')->get();
        return view('admin.centers.edit', $data);
    }

    /**
     * Remove the specified Centers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objCenter = Centers::find($id);
            if (!empty($objCenter)) {
                if (!empty($objCenter->banner_image_url)) {
                    Storage::disk('azure')->delete($objCenter->banner_image_url);
                }
                if (!empty($objCenter->accomodation_banner_image_url)) {
                    Storage::disk('azure')->delete($objCenter->accomodation_banner_image_url);
                }
                $objCenter->delete();
                \App\CenterAccomodations::where("center_id", $id)->delete();
                \App\CenterTeachers::where("center_id", $id)->delete();
                \App\CenterLocations::where("center_id", $id)->delete();

                $paramCGI['where'] = array("center_id" => $id);
                $imagegalleries = \App\CenterImageGallery::get_data($paramCGI);
                if (!empty(@$imagegalleries)) {
                    foreach (@$imagegalleries as $imagegallery) {
                        Storage::disk('azure')->delete(@$imagegallery->image_url);
                        $imagegallery->delete();
                    }
                }
            } else {
                return redirect('bbadmin/centers')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/centers')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/centers')
                        ->with('flash_message', 'Centers deleted successfully');
    }
    
    public function upload_image($request) {
        $file = $request->file("banner_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ( "image/webp" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'centers' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    /**
     * Delete Image
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objCenter = Centers::find($id);
            if (!empty($objCenter)) {
                Storage::disk('azure')->delete($objCenter->banner_image_url);
                $objCenter->banner_image_title = null;
                $objCenter->banner_image_url = null;
                $objCenter->save();
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
                $file->getClientMimeType() == ( "image/webp" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/centers' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
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
            $objCenterImageGallery = \App\CenterImageGallery::find($id);
            if (!empty($objCenterImageGallery)) {
                Storage::disk('azure')->delete($objCenterImageGallery->image_url);
                $objCenterImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Get Center Accomodation
     *
     * @return \Illuminate\Http\Response
     */
    public function get_center_accomodation(Request $request) {
        $data = array();
        $data["accomodations"] = array();
        $data["commissions"] = array();
        try {
            // Get Center Accomodations
            $center_id = (!empty($request['center_id'])) ? $request['center_id'] : "";
            if ($center_id) {
                $center_accomodations = \App\Accomodation::select("accomodation.id", "accomodation.name")->Join("center_accomodations", "accomodation.id", "=", "center_accomodations.accomodation_id")
                                ->where("center_accomodations.center_id", $center_id)->get();
                if (!empty(@$center_accomodations)) {
                    foreach (@$center_accomodations as $center_accomodation) {
                        $data["accomodations"][$center_accomodation->id] = $center_accomodation->name;
                    }
                }

                $center_commission = \App\CenterCommissions::where("center_id", $center_id)->first();
                if (!empty(@$center_commission)) {
                    $data["commissions"] = @$center_commission;
                }
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode($data);
        }
    }

    public function upload_accomodation_image($request) {
        $file = $request->file("accomodation_banner_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" ) ||
                $file->getClientMimeType() == ( "image/webp" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'accomodation' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_accomodation_image(Request $request) {
        try {
            $id = $request['id'];
            $objCenter = Centers::find($id);
            if (!empty($objCenter)) {
                Storage::disk('azure')->delete($objCenter->accomodation_banner_image_url);
                $objCenter->accomodation_banner_image_title = null;
                $objCenter->accomodation_banner_image_url = null;
                $objCenter->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Get Center Teachers
     *
     * @return \Illuminate\Http\Response
     */
    public function get_center_teachers(Request $request) {
        $data = array();
        try {
            // Get Center Accomodations
            $center_id = (!empty($request['center_id'])) ? $request['center_id'] : "";
            if ($center_id) {
                $objCenterTeahcers = \App\CenterTeachers::select("teacher_id")->where(array("center_id" => $center_id))->get();
                if (!empty(@$objCenterTeahcers)) {
                    foreach (@$objCenterTeahcers as $objCenterTeahcer) {
                        $data[] = $objCenterTeahcer->teacher_id;
                    }
                }
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode($data);
        }
    }

    public function getcenters() {
        $json_data = array();
        $search = Request()->input('search');
        if (!empty($search)) {
            $objCenters = \App\Centers::select("id", "name")->where("name", 'like', '%' . $search . '%')->get();
            if (sizeof($objCenters) > 0) {
                foreach ($objCenters as $objCenter) {
                    array_push($json_data, array("id" => $objCenter->id, "text" => $objCenter->name));
                }
            }
        }
        echo json_encode($json_data);
    }
    
    /**
     * Show the form for Upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload() {
        return view('admin.centers.upload');
    }

    /**
     * Store a newly centres in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpload(Request $request) {
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt',
        ]);
        try {
            if($_FILES['file']['name']) {
                $existRecord = fopen("exist_records.txt", "w") or die("Unable to open file!");
                $filename = explode(".", $_FILES['file']['name']);
                $handle = fopen($_FILES['file']['tmp_name'], "r");
                $i = 0;
                $insert = 0;
                $exists = 0;
                while($data = fgetcsv($handle)) {
                    if ($i>0) {
                        
                        $objCentre = \App\Centers::where('id', $data[0])->first();
                        if(!$objCentre) {
                            
                            $objCentre = new \App\Centers();
                            $objCentre->id = $data[0];
                            $objCentre->bg_id = $data[1];
                            $objCentre->name = $data[2];
                            $objCentre->slug = $data[3];
                            $objCentre->meta_title = $data[4];
                            $objCentre->meta_description = $data[5];
                            $objCentre->city = @$data[6];
                            $objCentre->country = @$data[7];
                            $objCentre->save();
                            $centre_id = $objCentre->id;
                            
                            if ($data[6]) {
                                foreach (explode(",", $data[6]) as $location) {
                                    if (trim($location)) {
                                        $objCategory = \App\Category::firstOrNew(["name" => trim($location), "type" => 1]);
                                        if (!$objCategory->exists) {
                                            $objCategory->name = trim($location);
                                            $objCategory->type = 1;
                                            $objCategory->save();
                                        }
                                        $category_id = $objCategory->id;
                                        
                                        $objCenterLocations = new \App\CenterLocations();
                                        $objCenterLocations->location_id = $category_id;
                                        $objCenterLocations->center_id = $centre_id;
                                        $objCenterLocations->save();
                                        \App\Centers::where("id", $centre_id)->update(["city" => $category_id]);
                                    }
                                }
                            }
                            if ($data[7]) {
                                foreach (explode(",", $data[7]) as $country) {
                                    if (trim($country)) {
                                        $objCategory = \App\Category::firstOrNew(["name" => trim($country), "type" => 1]);
                                        if (!$objCategory->exists) {
                                            $objCategory->name = trim($country);
                                            $objCategory->type = 1;
                                            $objCategory->save();
                                        }
                                        $category_id = $objCategory->id;
                                        
                                        $objCenterLocations = new \App\CenterLocations();
                                        $objCenterLocations->location_id = $category_id;
                                        $objCenterLocations->center_id = $centre_id;
                                        $objCenterLocations->save();
                                        \App\Centers::where("id", $centre_id)->update(["country" => $category_id]);
                                    }
                                }
                            }
                            $insert++;
                        } else {
                            $exists++;
                            $txt = $data[0]."\n";
                            fwrite($existRecord, $txt);
                        }
                    }
                    $i++;
                }
            }
        } catch (Exception $e) {
            return redirect('bbadmin/centers')->with('flash_error_message', 'Something went wrong');
        }
        $txtExist = "";
        if ($exists > 0) {
            $txtExist = " And ".$exists." Already Exist";
        }
        return redirect('bbadmin/centers')->with('flash_message', ($insert).' Centres Inserted!'.$txtExist);
    }

}
