<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminUsers
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
        if (!empty(Auth::user()) && Auth::user()->is_verified == 1) {
            if (Auth::user()->user_type == USER_ROLE_ADMIN) {

                return $next($request);
            } else {
                Auth::logout();
                return redirect('login')->with('error_msg', __('You are not elgiable for login in this panel'));
            }

        } else {
            Auth::logout();
            return redirect('login')->with('error_msg', __('Please verify your EMAIL'));
        }
        return redirect('login');
    }
}
