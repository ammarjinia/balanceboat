<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
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

        // Record the sign-in. The column already existed on `users` but nothing wrote to it, which
        // left the inactivity / reactivation email triggers with no signal to read.
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        // Create center session
        Session::put('center_user_id', $user->id);
        Session::put('center_user_email', $user->email);
        Session::put('center_user_name', $user->first_name . ' ' . $user->last_name);
        Session::put('center_id', $center->id);

        return redirect('/center-panel/dashboard')->with('success', 'Welcome to Center Dashboard!');
    }

    /**
     * Show the "forgot password" request form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        if (session()->has('center_user_id')) {
            return redirect('/center-panel/dashboard');
        }

        return view('center_panel.auth.forgot_password');
    }

    /**
     * Email a center owner a password reset link.
     *
     * Uses the shared "users" password broker (config('auth.passwords.users'), same token table the
     * bbadmin reset flow uses) to generate the token, but sends
     * its own center-panel-branded email pointing at the center-panel reset routes instead of the
     * framework's default ResetPassword notification — that notification resolves the plain
     * `password.reset` route name, which belongs to the bbadmin flow and would land an Owner there.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Always show the same generic confirmation regardless of whether the address matches a
        // Center Owner account, so this endpoint can't be used to enumerate who has access — but
        // only actually send the email when it genuinely resolves to one.
        if ($user && $user->hasRole('Owner') && $user->center()->exists()) {
            $token = Password::broker('users')->createToken($user);
            $resetUrl = route('center-panel.password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);

            Mail::send('center_panel.emails.password_reset', [
                'user'          => $user,
                'resetUrl'      => $resetUrl,
                'expireMinutes' => config('auth.passwords.users.expire', 60),
            ], function ($message) use ($user) {
                $message->subject('Reset your BalanceBoat Center password');
                $message->to($user->email, trim($user->first_name . ' ' . $user->last_name));
            });
        }

        return back()->with('success', 'If that email belongs to a Center Owner account, a password reset link has been sent to it.');
    }

    /**
     * Show the "choose a new password" form for a reset link's token/email pair.
     *
     * @param string $token
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm($token, Request $request)
    {
        return view('center_panel.auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Complete a password reset for a center owner.
     *
     * Deliberately does not Auth::login() the user — the center panel authenticates via its own
     * session variables set in login() above, not the default "web" guard — so a successful reset
     * simply sends the owner back to the center-panel login form with their new password.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $credentials = $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('users')->reset($credentials, function ($user, $password) {
            $user->password = $password; // App\User::setPasswordAttribute() bcrypts it
            $user->save();
        });

        // Translate the broker's status key ourselves rather than via __() — this app ships without
        // a lang/passwords.php file, so __() would just echo the raw key (e.g. "passwords.token")
        // back at the user instead of a real message.
        if ($status !== Password::PASSWORD_RESET) {
            $message = $status === Password::INVALID_TOKEN || $status === Password::INVALID_USER
                ? 'This password reset link is invalid or has expired. Please request a new one.'
                : 'We could not reset your password. Please try again.';

            return back()->withErrors(['email' => $message])->withInput($request->only('email'));
        }

        return redirect()->route('center-panel.login')
            ->with('success', 'Your password has been reset. You can now sign in with your new password.');
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
