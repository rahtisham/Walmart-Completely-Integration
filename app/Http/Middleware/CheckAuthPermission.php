<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckAuthPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        if($CheckAuthPermission == "admin" && auth()->user()->roles == 1) {
//            abort(404);
//        }
//        if($CheckAuthPermission == "user" && auth()->user()->roles == 2) {
//            abort(404);
//        }
//        return $next($request);
    }
}
