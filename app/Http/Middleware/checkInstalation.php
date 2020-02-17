<?php

namespace App\Http\Middleware;

use Closure;

class checkInstalation
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
        if (file_exists(storage_path('installed'))) {
            return $next($request);
        }

        return redirect(url('install'));
    }
}
