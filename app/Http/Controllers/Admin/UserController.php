<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Mail;

class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::orderBy("id","DESC")->get();
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::get();
        return view('admin.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'first_name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'center_id' => 'required_if:role,!=,admin',
            'password' => 'required|min:6|confirmed'
        ],[
            'center_id.required' => 'The center field is required.'
        ]);
        $user = User::create(array("email" => $request['email'], 'first_name' => $request['first_name'], 'last_name' => $request['last_name'], "password" => $request['password']));
        $roles = $request['roles'];
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r);
            }
        }

        $center_id = $request['center_id'];
        if (!empty($center_id)) {
            $objCenter = \App\Centers::find($center_id);
            if ($objCenter) {
                $objCenter->user_id = $user->id;
                $objCenter->save();
            }
        }

        return redirect("bbadmin/users")
                        ->with('flash_message', 'User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect("bbadmin/users");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id);
        $center = \App\Centers::select("id", "name")->where("user_id", $id)->first();
        $roles = Role::get();
        return view('admin.users.edit', compact('user', 'roles', 'center'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'first_name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,' . $id,
            'center_id' => 'required_if:role,!=,admin',
            'password' => 'sometimes|nullable|min:6|confirmed'
        ],[
            'center_id.required' => 'The center field is required.'
        ]);

        $input = $request->only(['first_name', 'last_name', 'email']);
        if ($request->filled('password')) {
            $input['password'] = $request->password;
        }
        $roles = $request['roles'];
        $user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles);
        } else {
            $user->roles()->detach();
        }
        $center_id = $request['center_id'];
        if (!empty($center_id)) {
            $objCenter = \App\Centers::find($center_id);
            if ($objCenter) {
                $objCenter->user_id = $user->id;
                $objCenter->save();
            }
        }
        return redirect("bbadmin/users")
                        ->with('flash_message', 'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect("bbadmin/users")
                        ->with('flash_message', 'User successfully deleted.');
    }

    /**
     * Send Invitation Email
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invitation($userId) {
        try {
            $user = User::findOrFail($userId);
            $password = preg_replace("/[^a-zA-Z]+/", "", $user->first_name) . "@" . rand(1000, 9999);
            $user->password = $password;
            $user->save();
            $center = \App\Centers::select("id", "name")->where("user_id", $userId)->first();
            $roles = Role::get();

            $userinfo = $user;
            if (!empty(@$user->email)) {
                try {
                    Mail::send('admin.emails.user_invitation', ['userinfo' => @$user, 'centerinfo' => @$center, "password" => $password], function ($message) use($user) {
                        $message->subject("Greetings from Balanceboat. ");
                        $message->to(@$user->email, @$user->first_name . " " . @$user->last_name);
                    });
                    return redirect()->route('users.index')
                                    ->with('flash_message', 'Your message has been successfully sent.');
                } catch (Exception $ex) {
                    return redirect()->route('users.index')
                                    ->with('flash_business_error_message', 'Something went wrong');
                }
            }
        } catch (Exception $ex) {
            return redirect()->route('users.index')
                            ->with('flash_error_message', 'Something went wrong');
        }
    }

}
