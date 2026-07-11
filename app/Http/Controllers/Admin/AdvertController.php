<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Advert;
use Carbon\Carbon;
use Storage;
use Spatie\OpeningHours\OpeningHours;
use Mail;
use DB;

class AdvertController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Advert.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $ajax = Request()->input('ajax') ? Request()->input('ajax') : false;
        if ($ajax == true) {
            $page = $_REQUEST['length'];
            $offset = $_REQUEST['start'];
            $search = $_REQUEST['search']['value'];
            $advert = Advert::query();
            if (!empty($search)) {
                $advert = $advert->where("name", "like", "%" . $search . "%");
            }
            $listingTotal = clone $advert;
            $order = "DESC";
            $orderBy = "updated_at";
            if (Request()->has('order.0.column')) {
                switch (Request()->input('order.0.column')) {
                    case '0' :
                        $orderBy = "banner_image_title";
                        if (Request()->input('order.0.dir') == 'desc')
                            $order = "DESC";
                        else
                            $order = "ASC";
                        break;
                    default :
                        break;
                }
            }
            $advert = $advert->orderBy($orderBy, $order)->skip($offset)->limit($page)->get();
            $listingTotal = $listingTotal->count();
            $listingData = array();
            if ($advert) {
                foreach ($advert as $objAdvert) {
                    $action = '<div class="btn-group">
                        <button type = "button" class = "btn btn-info dropdown-toggle" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">Action</button>
                        <div class = "dropdown-menu" x-placement = "bottom-start">
                            <a class="dropdown-item" href="' . url('bbadmin/advert/edit/' . $objAdvert->id) . '">Edit</a>'
                            . '<div class="dropdown-divider"></div>'
                            . '<a class="dropdown-item text-danger advert-delete" href="#" data-rel="'.$objAdvert->id.'" >Delete</a>'
                            . '</div>'
                            . '</div>';
                            
                    $listingData[] = array(
                        $objAdvert->banner_image_title,
                        ($objAdvert->banner_image_url) ? '<img src="'.Storage::disk('s3')->url($objAdvert->banner_image_url).'" height="50px" />' : '',
                        $objAdvert->position,
                        ($objAdvert->is_draft == 0) ? 'Publish' : 'Draft',
                        $action
                    );
                }
            }

            $json_data = array(
                "draw" => intval($_REQUEST['draw']),
                "recordsTotal" => intval($listingTotal),
                "recordsFiltered" => intval($listingTotal),
                "data" => $listingData
            );
            echo json_encode($json_data);
        } else {
            return view("admin.advert.index", $data);
        }
    }

    /**
     * Show the form for creating a new Advert.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        return view('admin.advert.create', $data);
    }

    /**
     * Store a newly created Advert in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'banner_image_title' => 'required',
             'banner_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $banner_image_title = $request['banner_image_title'];
        $is_draft = $request['is_draft'];
        if ($request->file('banner_image')) {
            $imageUrl = $this->upload_image($request->file("banner_image"));
        }
        
        if (!empty($request['id'])) {
            $objAdvert = Advert::find($request['id']);
        } else {
            $objAdvert = new Advert();
        }
        
        $objAdvert->banner_image_title = $banner_image_title;
        $objAdvert->website = $request['website'];
        $objAdvert->position = $request['position'];
        $objAdvert->sub_title = $request['sub_title'];
        $objAdvert->is_draft = $is_draft;
        if ($request['banner_image']) {
            $objAdvert->banner_image_url = $imageUrl;
        }
        
        try {
            $objAdvert->save();
            return redirect('bbadmin/adverts')->with('flash_message', 'Advert ' . $objAdvert->name . ' submitted');
        } catch (Exception $e) {
            return redirect('bbadmin/adverts')->with('flash_error_message', 'Something went wrong');
        }
    }

    /**
     * Show the form for ediing a Advert.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/adverts");
        }

        $data['eadvert'] = Advert::where("id", $id)->first();
        return view('admin.advert.edit', $data);
    }

    /**
     * Remove the specified Advert from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objAdvert = Advert::find($id);
            if (!empty($objAdvert)) {
                if ($objAdvert->banner_image_title) {
                    Storage::disk('s3')->delete($objAdvert->banner_image_title);
                }
                $objAdvert->delete();
            } else {
                return redirect('bbadmin/adverts')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/adverts')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/adverts')
                        ->with('flash_message', 'Advert deleted successfully');
    }

    public function upload_image($file) {
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
            $folderName = 'adverts' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $field = $request['field'];
            $objAdvert = Advert::find($id);
            if (!empty($objAdvert)) {
                Storage::disk('s3')->delete($objAdvert->$field);
                if ($field == "banner_image_url") {
                    $objAdvert->banner_image_url = null;
                }
                $objAdvert->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
