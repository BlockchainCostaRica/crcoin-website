<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ((!empty(Auth::user()) && (Auth::user()->user_type == USER_ROLE_USER)) || ((Auth::user()) && Auth::user()->user_type == USER_ROLE_OPERATOR)) {

            if (Auth::user()->is_verified == 1) {
                //   app()->setLocale(Auth::user()->language);
                return $next($request);
            } else
                return response()->json(['error' => true, 'message' => 'Please verify your email']);
        }
        return response()->json(['error' => true, 'message' => 'Did not logged in']);

    }
}
