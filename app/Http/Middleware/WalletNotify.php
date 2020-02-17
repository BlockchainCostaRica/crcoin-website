<?php

namespace App\Http\Middleware;

use App\Exceptions\UserApiException;
use Closure;

class WalletNotify
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
        if ($request->header('secret-key') == 'R3*M4@9%@B_A5?n9YZ6L_k@9-_72YvU@h!T^uUEUE=aEPJ4rj?@6H4SF5@u')
        {
            return $next($request);
        }
        throw new UserApiException('You are not authorize',400);
    }

}
