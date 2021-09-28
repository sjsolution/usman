<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
          if ($guard == "admin" && Auth::guard($guard)->check()) {
               return redirect()->route('admin.home');
           }
           if (Auth::guard($guard)->check()) {
               return redirect()->route('user.home');
           }
           return $next($request);
      // if (Auth::guard($guard)->check()) {
      //      if ($guard === 'admin') {
      //          return redirect()->route('admin.dashboard');
      //      }
      //      return redirect()->route('user.home');
      //  }
      //  return $next($request);
        //
        // if ($guard == "admins" && Auth::guard($guard)->check()) {
        //         return redirect('/admin');
        // }
        // if (Auth::guard($guard)->check()) {
        //     return redirect('/home');
        // }
        // return $next($request);
    }
}
