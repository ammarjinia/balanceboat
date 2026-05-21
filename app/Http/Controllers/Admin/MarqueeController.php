<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Marquee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Mail;

class MarqueeController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a Marquee form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        
        $data['marquee'] = Marquee::where("id", 1)->first();
        return view('admin.marquee.index', $data);
    }

    /**
     * update Marquee
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    public function update_marquee(Request $request) {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $content = $request['content'];
        $url = $request['url'];
        $is_draft = $request['is_draft'];
        
        
        $objMarquee = Marquee::find($request['id']);
        
        $objMarquee->content = $content;
        $objMarquee->url = $url;
        $objMarquee->is_draft = $is_draft;
        
        try {
            $objMarquee->save();
            return redirect('bbadmin/marquee')->with('flash_message', 'Marquee update Sucessfully');
        } catch (Exception $e) {
            return redirect('bbadmin/marquee')->with('flash_error_message', 'Something went wrong');
        }
    }

}
