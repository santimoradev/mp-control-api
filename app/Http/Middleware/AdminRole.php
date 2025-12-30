<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use JWTAuth;
use Sentinel;

class AdminRole
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
		$role = Sentinel::findById( $user->id )->roles()->get()->first();
        if ( $role->id !== 1) :
            abort(403, 'Access only users admin');
        endif;
        return $next($request);
    }
}
