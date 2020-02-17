<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role_task)
    {
        $permission = checkRolePermission($role_task,Auth::user()->admin_role_id);
        if ($permission) {
            return $next($request);
        }
        return redirect()->route('serverError');
    }



}

