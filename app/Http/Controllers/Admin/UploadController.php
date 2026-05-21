<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Mail;

class UploadController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a sitemap form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.upload.index');
    }

    /**
     * Upload a sitemap file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_sitemap(Request $request) {
        $this->validate($request, [
            'sitemap' => 'required|mimetypes:application/xml,text/xml',
        ]);

        $sitemapfile = $request->file("sitemap");
        if ($request->file('sitemap')) {
            file_put_contents(base_path()."/". $sitemapfile->getClientOriginalName(),  file_get_contents($sitemapfile->getRealPath()));
        }
        return redirect()->to('bbadmin/upload')->with('flash_message', $sitemapfile->getClientOriginalName().' Successfully Uploaded.');
    }

}
