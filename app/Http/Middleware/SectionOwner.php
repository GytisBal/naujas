<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SectionOwner
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

        // dd( $request->user()->hasRole('super-admin'));

        if($request->route()->uri == "users" || $request->user()->hasRole('super-admin')){

            return $next($request);

        }else if($request->route()->user != Auth::id() )
        {
            abort(403, 'Access denied');
        } else
        {
            return $next($request);
        }
        
    }
}
