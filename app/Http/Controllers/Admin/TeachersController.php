<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Teachers;
use storage;
use DB;

class TeachersController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['teachers'] = Teachers::select("teachers.id","teachers.name","teachers.slug","teachers.short_description", DB::raw('group_concat(`centers`.`name`) as CenterName'))
                ->leftJoin("center_teachers", "center_teachers.teacher_id", "=", "teachers.id")
                ->leftJoin("centers", "centers.id", "=", "center_teachers.center_id")
                ->groupBy("teachers.id","teachers.name","teachers.slug","teachers.short_description")
                ->orderBy("teachers.name", "ASC")
                ->get();
        return view("admin.teachers.index", $data);
    }

    /**
     * Show the form for creating a new Teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['expertises'] = \App\Expertise::get_data($param);
        $data['centers'] = \App\Centers::get_data($param);
        return view('admin.teachers.create', $data);
    }

    /**
     * Store a newly created category in storage.
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
        $meta_title = $request['meta_title'];
        $keywords = $request['keywords'];
        $meta_description = $request['meta_description'];
        $short_description = $request['short_description'];
        $expertise_id = (is_array($request['expertise_id'])) ? implode("||", $request['expertise_id']) : "";
        $certificate_id = (is_array($request['certificate_id'])) ? implode("||", $request['certificate_id']) : "";
        $center_ids = (is_array($request['center_id'])) ? $request['center_id'] : array();
        $teaching_since = $request['teaching_since'];
        $complete_bio = $request['complete_bio'];
        $currently_working_with = $request['currently_working_with'];
        if ($request->file('profile_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('profile_image')->getClientOriginalName();
        }
        $image_galleries = (!empty($request['image_gallery_ids'])) ? $request['image_gallery_ids'] : "";

        if (!empty($request['id'])) {
            $teacher = Teachers::find($request['id']);
            if ($teacher) {
                $teacher->name = $name;
                $teacher->slug = $slug;
                $teacher->meta_title = $meta_title;
                $teacher->keywords = $keywords;
                $teacher->meta_description = $meta_description;
                $teacher->short_description = $short_description;
                $teacher->expertise_id = $expertise_id;
                $teacher->certificate_id = $certificate_id;
                $teacher->teaching_since = $teaching_since;
                $teacher->complete_bio = $complete_bio;
                $teacher->currently_working_with = $currently_working_with;
                if ($request['profile_image']) {
                    $teacher->profile_image_title = $imageTitle;
                    $teacher->profile_image_url = $imageUrl;
                }
                try {
                    $teacher->save();
                    $teacher_id = $request['id'];

                    // Teacher Center Mapping
                    $existCenterIds = (@$request['hdn_center_id']) ? explode("||", $request['hdn_center_id']) : array();
                    if (!empty(@$existCenterIds)) {
                        foreach (@$existCenterIds as $hdn_center_id) {
                            if (!in_array($hdn_center_id, @$center_ids) or empty(@$center_ids)) {
                                $objCenterTeahcer = \App\CenterTeachers::select("id")->where(array("center_id" => $hdn_center_id, "teacher_id" => $teacher_id))->first();
                                if (!empty($objCenterTeahcer)) {
                                    $objCenterTeahcer->delete();
                                }
                            }
                        }
                    }

                    if (!empty($center_ids)) {
                        foreach ($center_ids as $center_id) {
                            if (!in_array($center_id, @$existCenterIds)) {
                                $objCenterTeachers = new \App\CenterTeachers();
                                $objCenterTeachers->teacher_id = $teacher_id;
                                $objCenterTeachers->center_id = $center_id;
                                $objCenterTeachers->save();
                            }
                        }
                    }

                    // Move Images from tmp to src
                    if (!empty(@$image_galleries)) {
                        $image_galleries_array = explode("|@|@|", @$image_galleries);
                        foreach ($image_galleries_array as $galimage) {
                            $dest = str_replace("tmp/", "", $galimage);
                            \Storage::disk('azure')->move($galimage, $dest);
                            $objTeacherImageGallery = new \App\TeacherImageGallery();
                            $objTeacherImageGallery->teacher_id = $teacher_id;
                            $objTeacherImageGallery->image_title = basename($dest);
                            $objTeacherImageGallery->image_url = $dest;
                            $objTeacherImageGallery->save();
                        }
                    }
                } catch (Exception $e) {
                    return redirect('bbadmin/teachers')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/teachers')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/teachers')
                            ->with('flash_message', 'Teacher ' . $teacher->name . ' updated');
        } else {
            try {
                $teacher = new \App\Teachers();
                $teacher->name = $name;
                $teacher->slug = $slug;
                $teacher->meta_title = $meta_title;
                $teacher->keywords = $keywords;
                $teacher->meta_description = $meta_description;
                $teacher->short_description = $short_description;
                $teacher->expertise_id = $expertise_id;
                $teacher->certificate_id = $certificate_id;
                $teacher->teaching_since = $teaching_since;
                $teacher->complete_bio = $complete_bio;
                $teacher->currently_working_with = $currently_working_with;
                if ($request['profile_image']) {
                    $teacher->profile_image_title = $imageTitle;
                    $teacher->profile_image_url = $imageUrl;
                }
                $resteacher = $teacher->save();
                $teacher_id = $teacher->id;

                // Teacher Center Mapping
                if (!empty(@$center_ids)) {
                    foreach (@$center_ids as $center_id) {
                        $objCenterTeachers = new \App\CenterTeachers();
                        $objCenterTeachers->teacher_id = $teacher_id;
                        $objCenterTeachers->center_id = $center_id;
                        $objCenterTeachers->save();
                    }
                }

                // Move Images from tmp to src
                if (!empty(@$image_galleries)) {
                    $image_galleries_array = explode("|@|@|", @$image_galleries);
                    foreach ($image_galleries_array as $galimage) {
                        $dest = str_replace("tmp/", "", $galimage);
                        \Storage::disk('azure')->move($galimage, $dest);
                        $objTeacherImageGallery = new \App\TeacherImageGallery();
                        $objTeacherImageGallery->teacher_id = $teacher_id;
                        $objTeacherImageGallery->image_title = basename($dest);
                        $objTeacherImageGallery->image_url = $dest;
                        $objTeacherImageGallery->save();
                    }
                }
            } catch (Exception $e) {
                return redirect('bbadmin/teachers')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/teachers')
                            ->with('flash_message', 'Teacher ' . $teacher->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/teachers");
        }
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['certificates'] = \App\Certificates::get_data($param);
        $data['expertises'] = \App\Expertise::get_data($param);
        $data['centers'] = \App\Centers::get_data($param);

        // Get Teachers Gallery Images
        $paramTGI['select'] = array('id', 'teacher_id', 'image_url', 'image_title');
        $paramTGI['where'] = array("teacher_id" => $id);
        $data['imagegalleries'] = \App\TeacherImageGallery::get_data($paramTGI);

        // Get Center Teachers
        $paramCT['select'] = array('id', 'teacher_id', 'center_id');
        $paramCT['where'] = array("teacher_id" => $id);
        $center_teachers = \App\CenterTeachers::get_data($paramCT);
        $data['center_teachers'] = array();
        if (!empty($center_teachers)) {
            foreach ($center_teachers as $center_teacher) {
                $data['center_teachers'][$center_teacher->id] = $center_teacher->center_id;
            }
        }

        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['eteacher'] = Teachers::get_data($param);
        $data['eteacher'] = (@$data['eteacher'][0]) ? : array();
        return view('admin.teachers.edit', $data);
    }

    /**
     * Remove the specified Teacher from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $teacher = Teachers::find($id);
            if (!empty($teacher)) {
                if (!empty($teacher->profile_image_url)) {
                    \Storage::disk('azure')->delete($teacher->profile_image_url);
                }
                $teacher->delete();                
                \App\CenterTeachers::where("teacher_id", $id)->delete();

                $paramTGI['where'] = array("teacher_id" => $id);
                $imagegalleries = \App\TeacherImageGallery::get_data($paramTGI);
                if (!empty(@$imagegalleries)) {
                    foreach (@$imagegalleries as $imagegallery) {
                        \Storage::disk('azure')->delete(@$imagegallery->image_url);
                        $imagegallery->delete();
                    }
                }
            } else {
                return redirect('bbadmin/teachers')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/teachers')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/teachers')
                        ->with('flash_message', 'Teacher deleted successfully');
    }

    public function upload_image($request) {
        $file = $request->file("profile_image");
        // check mime type
        if ($file->getClientMimeType() == ( "image/png" ) ||
                $file->getClientMimeType() == ( "image/jpeg" ) ||
                $file->getClientMimeType() == ( "image/gif" ) ||
                $file->getClientMimeType() == ( "image/jpg" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'teachers' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objTeacher = Teachers::find($id);
            if (!empty($objTeacher)) {
                \Illuminate\Support\Facades\Storage::disk('azure')->delete($objTeacher->profile_image_url);
                $objTeacher->profile_image_title = null;
                $objTeacher->profile_image_url = null;
                $objTeacher->save();
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
                $file->getClientMimeType() == ( "image/jpg" )) {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'tmp/teachers' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
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
            $objTeacherImageGallery = \App\TeacherImageGallery::find($id);
            if (!empty($objTeacherImageGallery)) {
                Storage::disk('azure')->delete($objTeacherImageGallery->image_url);
                $objTeacherImageGallery->delete();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
