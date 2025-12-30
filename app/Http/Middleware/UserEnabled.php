<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use JWTAuth;

class UserEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = JWTAuth::parseToken()->authenticate();
        if ( $user->status === 0) :
            abort(403, 'Access denied');
        endif;
        return $next($request);
    }
}
