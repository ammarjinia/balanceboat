<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\CentreOnboard;
use Storage;
use DB;

class CentreOnboardController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Centers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $data['centers'] = CentreOnboard::select("id", "name", "email", "location")
                ->orderBy("id", "DESC")
                ->get();
        return view("admin.centre_onboard.index", $data);
    }

    /**
     * Show the form for creating a new Centers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        return view('admin.centre_onboard.create', $data);
    }

    /**
     * Store a newly created Center in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'location' => 'required',
        ]);

        $name = $request['name'];
        $email = $request['email'];
        $url = $request['url'];
        $location = $request['location'];
        $status = $request['status'];
        if (!empty($request['id'])) {
            $objCenter = CentreOnboard::find($request['id']);
            if ($objCenter) {
                $objCenter->name = $name;
                $objCenter->email = $email;
                $objCenter->url = $url;
                $objCenter->location = $location;
                $objCenter->status = $status;
                try {
                    $objCenter->save();
                    $center_id = $request['id'];

                } catch (Exception $e) {
                    return redirect('bbadmin/centre-onboard')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/centre-onboard')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/centre-onboard')
                            ->with('flash_message', 'Center Onboard' . $objCenter->name . ' updated');
        } else {
            try {
                $objCenter = new \App\CentreOnboard();
                $objCenter->name = $name;
                $objCenter->email = $email;
                $objCenter->url = $url;
                $objCenter->location = $location;
                $objCenter->status = $status;
                $resCenter = $objCenter->save();
                $center_id = $objCenter->id;
            } catch (Exception $e) {
                return redirect('bbadmin/centre-onboard')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/centre-onboard')
                            ->with('flash_message', 'Center Onboard ' . $objCenter->name . ' created');
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
            redirect("bbadmin/centre-onboard");
        }
        $data['ecenter'] = CentreOnboard::find($id);
        return view('admin.centre_onboard.edit', $data);
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
            $objCenter = CentreOnboard::find($id);
            if (!empty($objCenter)) {
                $objCenter->delete();
            } else {
                return redirect('bbadmin/centre-onboard')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/centre-onboard')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/centre-onboard')
                        ->with('flash_message', 'Centre Onboard deleted successfully');
    }
    

    /**
     * Remove the specified Centers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request) {
        $data = array();
        $name = $email = $url = $location = "";

        //  file validation
        $this->validate($request, [
            'file' => 'required'
        ]);

        $file = $request->file("file");
        $csvData = file_get_contents($file);

        $rows = array_map("str_getcsv", explode("\n", $csvData));
        
        $header = array_shift($rows);

        foreach ($rows as $row) {
            if (isset($row[0])) {
                if ($row[0] != "") {
                    $row = array_combine($header, $row);
                    
                    // master data
                    $centreData = array(
                        "name" => @$row["name"],
                        "email" => @$row["email"],
                        "url" => @$row["url"],
                        "location" => @$row["location"]
                    );

                    // ----------- check if record already exists ----------------
                    $checkcentre =  CentreOnboard::where("email", "=", $row["email"])->where("name", $row["name"])->first();
                    if (!is_null($checkcentre)) {
                        $updateCentre = CentreOnboard::where("email", "=", $row["email"])->update($centreData);
                        if($updateCentre == true) {
                            $data["status"]     =       "flash_message";
                            $data["message"]    =       "Imported successfully";
                        }
                    }

                    else {
                        $centre = CentreOnboard::create($centreData);
                        if(!is_null($centre)) {
                            $data["status"]     =       "flash_message";
                            $data["message"]    =       "Imported successfully";
                        }                        
                    }
                }
            }
        }

        return redirect('bbadmin/centre-onboard')->with($data["status"], $data["message"]);
    }

    /**
     * Show the form for Config Email Template
     *
     * @return \Illuminate\Http\Response
     */
    public function config_email_template(Request $request) {
        $data = array();
        if ($request->input("subject")) {
            DB::table("centre_onboard_email_template")->where("id",1)->update(array(
                "subject" => $request->input("subject"),
                "body" => $request->input("body")
            ));
        }
        $data["email"] = DB::table("centre_onboard_email_template")->where("id",1)->first();
        return view('admin.centre_onboard.config_email_template', $data);
    }
}
