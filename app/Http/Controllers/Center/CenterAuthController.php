<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Session;
use Redirect;
use App\User;

class CenterAuthController extends Controller
{
    /**
     * Show the center login form
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (session()->has('center_user_id')) {
            return redirect('/center/dashboard');
        }
        
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }
        
        return view('center_panel.auth.login');
    }

    /**
     * Handle center login
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ], [
            'email.exists' => 'Email not found in our records.',
            'email.email' => 'Please provide a valid email address.'
        ]);

        // Check if user exists and has "Owner" role
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.'])->withInput();
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid credentials.'])->withInput();
        }

        // Check if user has "Owner" role
        if (!$user->hasRole('Owner')) {
            return back()->withErrors(['email' => 'This account does not have Center Owner access.'])->withInput();
        }

        // Check if user has an associated center
        $center = $user->center()->first();
        if (!$center) {
            return back()->withErrors(['email' => 'No center associated with this account.'])->withInput();
        }

        // Create center session
        Session::put('center_user_id', $user->id);
        Session::put('center_user_email', $user->email);
        Session::put('center_user_name', $user->first_name . ' ' . $user->last_name);
        Session::put('center_id', $center->id);

        return redirect('/center-panel/dashboard')->with('success', 'Welcome to Center Dashboard!');
    }

    /**
     * Handle center logout
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Session::forget('center_user_id');
        Session::forget('center_user_email');
        Session::forget('center_user_name');
        Session::forget('center_id');
        
        return redirect('/center/login')->with('success', 'You have been logged out successfully.');
    }
}
