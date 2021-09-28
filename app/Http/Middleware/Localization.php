<?php

namespace App\Http\Middleware;

use Closure;
use Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class Localization
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

        // // Check header request and determine localizaton
        // $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';
        // // set laravel localization
        // app()->setLocale($local);
        // // continue request
        // return $next($request);

        $header = $request->header('X-localization');

        if($header == 'en' || $header == 'ar'){

            \App::setlocale($header);

            
            if(\Auth::check() && \Auth::user()->user_type == 2){

                if(\Auth::user()->is_active == 0){

                    Auth('api')->user()->token()->delete();
                    
                    Auth::user()->deviceToken()->delete();

                    return response()->json(Api::apiErrorResponse(__('message.userLogout')),401);
                }
                   
            }

            return $next($request);

        }else{

            $error = [
                'success'       => false,
                'error'         => true,
                'response_code' => 422,
                'message'       => 'Invalid localization or empty',
            ];

            return response()->json($error,422);
        }
    }
}
