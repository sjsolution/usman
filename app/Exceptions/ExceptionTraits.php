<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;

use InvalidArgumentException;

trait ExceptionTraits {

    public function apiException($request , $e)
    {

        if($e instanceof AuthenticationException ){
            return response()->json([
                'response_code' => '401',
                'status' => 0,
                'message'       => 'Unauthorized',
            ], 401);

        }

        if ($e instanceof ModelNotFoundException) {

            return response()->json([
                'response_code' => '404',
                'status' => 0,
                'message'       => 'Model Not Found Exception',
            ], 404);

        }

        if ($e instanceof NotFoundHttpException) {

            return response()->json([
                'response_code' => '404',
                'status' => 0,
                'message'       => 'Not Found Http Exception',
            ], 404);
        }

        if ($e instanceof InvalidArgumentException) {

            return response()->json([
                'response_code' => '500',
                'status' => 0,
                'message'       => 'Invalid Argument Exception',
            ], 500);
        }

        if ($e instanceof MethodNotAllowedHttpException) {

            return response()->json([
                'response_code' => '500',
                'status' => 0,
                'message'       => 'Method Not Allowed Http Exception',
            ], 500);
        }
        // if ($e instanceof QueryException) {

        //     return response()->json([
        //         'response_code' => '500',
        //         'status' => 0,
        //         'message'       => 'Query Exception',
        //     ], 500);
        // }
        return parent::render($request, $e);

    }

}
