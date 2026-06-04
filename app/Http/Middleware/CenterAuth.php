<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class CenterAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('center_user_id')) {
            return redirect('/center-panel/login')->with('error', 'Please log in to access the Center Dashboard.');
        }

        return $next($request);
    }
}
