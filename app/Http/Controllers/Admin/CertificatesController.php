<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Certificates;

class CertificatesController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource - Certificates.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $param['order'] = "ASC";
        $param['orderby'] = "name";
        $data['certificates'] = Certificates::get_data($param);
        ;
        return view("admin.certificates.index", $data);
    }

    /**
     * Show the form for creating a new Certificates.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = array();
        return view('admin.certificates.create', $data);
    }

    /**
     * Store a newly created Certificate in storage.
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
        $description = $request['description'];
        if ($request->file('certificate_image')) {
            $imageUrl = $this->upload_image($request);
            $imageTitle = $request->file('certificate_image')->getClientOriginalName();
        }

        if (!empty($request['id'])) {
            $certificate = Certificates::find($request['id']);
            if ($certificate) {
                $certificate->name = $name;
                $certificate->slug = $slug;
                $certificate->description = $description;
                if ($request['certificate_image']) {
                    $certificate->image_title = $imageTitle;
                    $certificate->image_url = $imageUrl;
                }
                try {
                    $certificate->save();
                } catch (Exception $e) {
                    return redirect('bbadmin/certificates')
                                    ->with('flash_error_message', 'Something went wrong');
                }
            } else {
                return redirect('bbadmin/certificates')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/certificates')
                            ->with('flash_message', 'Certificate ' . $certificate->name . ' updated');
        } else {
            $objCertificates = new \App\Certificates();
            $objCertificates->name = $name;
            $objCertificates->slug = $slug;
            $objCertificates->description = $description;
            if ($request['certificate_image']) {
                $objCertificates->image_title = $imageTitle;
                $objCertificates->image_url = $imageUrl;
            }
            try {
                $objCertificates->save();
            } catch (Exception $e) {
                return redirect('bbadmin/certificates')
                                ->with('flash_error_message', 'Something went wrong');
            }
            return redirect('bbadmin/certificates')
                            ->with('flash_message', 'Certificate ' . $objCertificates->name . ' created');
        }
    }

    /**
     * Show the form for ediing a Certificates.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = '') {
        $data = array();
        $param = array();
        if (!$id) {
            redirect("bbadmin/certificates");
        }
        $param = array("where" => array("id" => $id), "limit" => 1);
        $data['ecertificate'] = Certificates::get_data($param);
        $data['ecertificate'] = (@$data['ecertificate'][0]) ? : array();
        return view('admin.certificates.edit', $data);
    }

    /**
     * Remove the specified certificate from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request['id'];
        try {
            $certificate = Certificates::find($id);
            if (!empty($certificate)) {
                $certificate->delete();
            } else {
                return redirect('bbadmin/certificates')
                                ->with('flash_error_message', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect('bbadmin/certificates')
                            ->with('flash_error_message', 'Something went wrong.');
        }
        return redirect('bbadmin/certificates')
                        ->with('flash_message', 'Certificate deleted successfully');
    }

    public function upload_image($request) {
        $file = $request->file("certificate_image");
        // check mime type
        if ($file->getClientMimeType() == 'image/jpeg') {
            // feel free to change this logic, that is an example
            $baseFileName = strtolower($file->getClientOriginalName());
            $ext = strtolower($file->getClientOriginalExtension());
            $filenameWithoutExt = preg_replace("~\." . $ext . "$~i", '', $baseFileName);
            $renamefile = $filenameWithoutExt . time() . "." . $ext;
            // folder name in container, could be empty
            $folderName = 'certificates' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objCertificate = Certificates::find($id);
            if (!empty($objCertificate)) {
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($objCertificate->image_url);
                $objCertificate->image_title = null;
                $objCertificate->image_url = null;
                $objCertificate->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (Exception $e) {
            echo 'Something went wrong.';
        }
    }

}
