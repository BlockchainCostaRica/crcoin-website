<?php

namespace App\Http\Middleware;

use App\Exceptions\UserApiException;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiUserCheck
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
        if (isset(Auth::user()->id) && Auth::user()->type == USER_ROLE_USER && Auth::user()->status == STATUS_SUCCESS) {
            if (Auth::user()->is_verified == 1) {
                return $next($request);
            } else {
                throw new UserApiException(__('Your mail is not verified. please verify your email.'), 401);
            }
        } else {
            throw new UserApiException(__('You are not authorised.'), 401);
        }
    }
}
