<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param \string $CheckAuthPermission
     * @return mixed
     */
    public function handle(Request $request, Closure $next  , string $CheckAuthPermission)
    {
        if(Auth()->check()) {

            if ($CheckAuthPermission == "user" && auth()->user()->roles != 1) {
                abort('404');
            }

            if ($CheckAuthPermission == "admin" && auth()->user()->roles != 2) {
                abort('404');
            }
            if ($CheckAuthPermission == "password" && auth()->user()->roles != 3) {
                abort('404');
            }
            return $next($request);

        }


    }
}
