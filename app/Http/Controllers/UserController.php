<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Storage;

class UserController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return redirect("/myaccount");
    }

    /**
     * Updated User Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'phone_number' => 'required|Numeric'
        ]);

        $id = Auth::user()->id;

        try {
            $user = \App\User::findOrFail($id);
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->phone_number = $request['phone_number'];
            $user->date_of_birth = ($request['date_of_birth']) ? Carbon::parse($request['date_of_birth'])->format("Y-m-d") : NULL;
            $user->street_address = $request['street_address'];
            $user->city = $request['city'];
            $user->zipcode = $request['zipcode'];
            $user->country = $request['country'];
            if ($request->file('profile_image_url')) {
                $imageUrl = $this->upload_image($request->file('profile_image_url'));
                $user->profile_image_url = $imageUrl;
            }
            $user->save();
            return redirect("/myaccount")->with('flash_message', 'Profile updated successfully!');
        } catch (Exception $e) {
            return redirect('/myaccount')
                            ->with('flash_error_message', 'Something went wrong');
        }
        exit;
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
            $folderName = 'users' . '/' . date("Y") . "/" . date("m") . "/" . date("d");
            // store file on s3
            $file->storeAs($folderName, $renamefile, ['disk' => 's3']);
            // save file name somewhere
            return $saveFileName = $folderName . "/" . $renamefile;
        }
    }

    public function delete_image(Request $request) {
        try {
            $id = $request['id'];
            $objUser = \App\User::find($id);
            if (!empty($objUser)) {
                Storage::disk('s3')->delete($objUser->profile_image_url);
                $objUser->profile_image_url = null;
                $objUser->save();
                echo true;
            } else {
                echo 'Something went wrong.';
            }
        } catch (\Exception $e) {
            echo 'Something went wrong.';
        }
    }

    /**
     * Check User Email Already Exist?
     *
     * @return \Illuminate\Http\Response
     */
    public function check_email_exist(Request $request) {
        $this->validate($request, [
            'email' => 'required',
        ]);
        try {
            $email = $request['email'];
            $objUser = \App\User::select("id")->where("email", $email)->count();
            if ($objUser) {
                echo 'false';
            } else {
                echo 'true';
            }
        } catch (Exception $e) {
            echo 'true';
        }
        exit;
    }
    
    
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            if($finduser){
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
