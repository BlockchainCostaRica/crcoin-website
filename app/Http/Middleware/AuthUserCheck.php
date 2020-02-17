<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthUserCheck
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
        if (!empty(Auth::user())){
            if( !empty(Auth::user()->is_verified)) {
                if(Auth::user()->status == STATUS_ACTIVE) {
                    if(Auth::user()->user_type != USER_ROLE_USER) {
                        app()->setLocale(Auth::user()->language);
                        if ((Auth::user()->g2f_enabled) && (session()->has('g2f_checked'))) {
                            return $next($request);
                        }elseif((Auth::user()->g2f_enabled) && !(session()->has('g2f_checked')))
                            return redirect()->route('g2fChecked');
                        else
                            return $next($request);

                    }else{
                        Auth::logout();
                        return redirect('login')->with('error_msg',__('You are not elgiable for login in this panel'));
                    }
                } else {
                    Auth::logout();
                    return redirect('login')->with('error_msg',__('Your account is currently deactivate, Please contact to admin'));
                }
            } else {
                Auth::logout();
                return redirect('login')->with('error_msg',__('Please verify your EMAIL'));
            }
        }
        else {
            Auth::logout();
            return redirect('login');
        }
        return redirect('login');

    }
}
