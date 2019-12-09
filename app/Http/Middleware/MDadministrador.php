<?php

namespace App\Http\Middleware;

use Closure;

class MDadministrador
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
        $userlog=\Auth::user();
        if($userlog->id_categoria!=2){

        }
        return $next($request);
    }
}
