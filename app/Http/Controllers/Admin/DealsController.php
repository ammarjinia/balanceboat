<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Deals;
use Storage;
use DB;

class DealsController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a deal of the resource.
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
            $deal = Deals::query();
            if (!empty($search)) {
                $deal = $deal->where("name", "like", "%" . $search . "%");
            }
            $dealTotal = clone $deal;
            $order = "DESC";
            $orderBy = "updated_at";
            if (Request()->has('order.0.column')) {
                switch (Request()->input('order.0.column')) {
                    case '0' :
                        $orderBy = "name";
                        if (Request()->input('order.0.dir') == 'desc')
                            $order = "DESC";
                        else
                            $order = "ASC";
                        break;
                    default :
                        break;
                }
            }
            $deal = $deal->orderBy($orderBy, $order)->skip($offset)->limit($page)->get();
            $dealTotal = $dealTotal->count();
            $dealData = array();
            if ($deal) {
                foreach ($deal as $objDeal) {
                    $action = '<div class="btn-group">
                        <button type = "button" class = "btn btn-info dropdown-toggle" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false">Action</button>
                        <div class = "dropdown-menu" x-placement = "bottom-start">
                            <a class = "dropdown-item" href = "' . url('bbadmin/deal/edit/' . @$objDeal->id) . '">Edit</a>
                            <a class="dropdown-item text-info" href="'.url('deal/' . $objDeal->slug).'" target="_blank">Preview</a>'
                            . '<div class = "dropdown-divider"></div>'
                            . '<a class = "dropdown-item text-danger deal-delete" href = "#" data-rel = "' . @$objDeal->id . '" >Delete</a>'
                            . '</div>'
                            . '</div>';
                    $dealData[] = array(
                        $objDeal->name,
                        $objDeal->updated_at?->format('d M, Y h:i A'),
                        $action
                    );
                }
            }
            $json_data = array(
                "draw" => intval($_REQUEST['draw']),
                "recordsTotal" => intval($dealTotal),
                "recordsFiltered" => intval($dealTotal),
                "data" => $dealData
            );
            echo json_encode($json_data);
        } else {
            return view("admin.deal.index", $data);
        }
    }

    /**
     * Show the form for creating a new deal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['experiences'] = \App\Experiences::get_data($param);
        return view('admin.deal.create', $data);
    }

    /**
     * Store a newly created Deal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:deals,slug,'.$request["id"],
        ]);
        $name = $request['name'];
        $slug = $request['slug'];
        $meta_title = $request['meta_title'];
        $meta_keywords = $request['meta_keywords'];
        $meta_description = $request['meta_description'];
        $description = $request['description'];
        $experience_ids = (is_array($request['experience_id'])) ? implode("||", $request['experience_id']) : "";
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $status = $request['status'];
        if ($request->file('deal_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('deal_image')->getClientOriginalName();
        }
        if (!empty($request['id'])) {
            $objDeal = Deals::find($request['id']);
        } else {
            $objDeal = new Deals();
        }
        
        if ($objDeal) {
            $objDeal->name = $name;
            $objDeal->slug = $slug;
            $objDeal->meta_title = $meta_title;
            $objDeal->meta_keywords = $meta_keywords;
            $objDeal->meta_description = $meta_description;
            $objDeal->description = $description;
            // $objDeal->experience_id = $experience_ids;
            $objDeal->start_date = $start_date;
            $objDeal->end_date = $end_date;
            $objDeal->status = $status;
            if ($request['deal_image']) {
                $objDeal->image_title = $imageTitle;
                $objDeal->image_url = $imageUrl;
            }
            
            try {
                $resDeal = $objDeal->save();
                $deal_id = $objDeal->id;
                
                $objDealExperience = \App\DealExperience::where("deal_id", $deal_id)->delete();
                                
                $experience_ids1 = (@$experience_ids) ? explode("||", $experience_ids) : array();
                if (!empty(@$experience_ids1)) {
                    foreach (@$experience_ids1 as $experience_id) {
                        if ($experience_id) {
                            $objDealExperience = new \App\DealExperience();
                            $objDealExperience->deal_id = $deal_id;
                            $objDealExperience->experience_id = $experience_id;
                            $objDealExperience->save();
                        }
                    }
                }
                return redirect('bbadmin/deals')->with('flash_message', 'Deal ' . $objDeal->name . ' Submitted');
            } catch (Exception $e) {
                return redirect('bbadmin/deals')->with('flash_error_message', 'Something went wrong');
            }
        } else {
            return redirect('bbadmin/deals')->with('flash_error_message', 'Something went wrong');
        }    
    }

    /**
     * Show the form for ediing a Deal.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        if (!$id) {
            redirect("bbadmin/deals");
        }
        $data['edeal'] = Deals::where("id", $id)->first();
        
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        
        // Get Collction Experiences
        $deal_experiences = \App\DealExperience::select("deal_experience.experience_id", "experiences.name")
                        ->Join("experiences", "experiences.id", "=", "deal_experience.experience_id")
                        ->where("deal_id", $id)
                        ->where("experiences.is_draft",0)->orderBy("deal_id");
        $data['deal_experiences'] = $deal_experiences->get();
        
        return view('admin.deal.edit', $data);
    }

    /**
     * Remove the specified Deal from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $objDeals = Deals::find($id);
            if (!empty($objDeals)) {
                \App\DealExperience::where("deal_id", $id)->delete();
                $objDeals->delete();
            } else {
                return redirect('bbadmin/deals')->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/deals')->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/deals')->with('flash_message', 'Deal deleted successfully');
    }

    public function upload_image($request) {
        $file = $request->file("deal_image");
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
            $folderName = 'deal' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on azure blob
            $file->storeAs($folderName, $renamefile, ['disk' => 'azure']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objDeal = Deals::find($id);
            if (!empty($objDeal)) {
                \Illuminate\Support\Facades\Storage::disk('azure')->delete($objDeal->image_url);
                $objDeal->image_title = null;
                $objDeal->image_url = null;
                $objDeal->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }
    
    public function getexperiences() {
        $json_data = array();
        $search = Request()->input('search');
        if (!empty($search)) {
            $experiences = \App\Experiences::select("id", "name")->where("name", 'like', '%' . $search . '%')->get();
            if (sizeof($experiences) > 0) {
                foreach ($experiences as $experience) {
                    array_push($json_data, array("id" => $experience->id, "text" => $experience->name));
                }
            }
        }
        echo json_encode($json_data);
    }

}
