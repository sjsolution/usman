<?php

namespace App\Http\Middleware;

use Closure;

class CheckHeader
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
      // Check header request and determine localizaton
      $xauth = ($request->hasHeader('X-Maak-Auth')) ? $request->header('X-Maak-Auth'):'';
      if($xauth === "MAAK"){
        return $next($request);
      }else{
        return response()->json(['status'=>0,'message'=>'Please enter valid X-Header-Auth value']);
      }
    }
}
