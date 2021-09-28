<?php 

namespace App\Helpers;

class Api 
{ 
    public static function apiSuccessResponse($message,$data=[])
    {
        $success = [
            'status'        => 1,
            'message'       => $message,   
            'data'          => $data
        ];

        return $success;
    }

    public static function apiValidationResponse($validator)
    {

        $validationError = [
            'status'       => 0,
            'message'       => $validator,
        ];

        return $validationError;
             
    }

    public static function apiErrorResponse($message)
    {
        $error = [
            'status'       => 0,
            'message'       => $message,
        ];

        return $error;
    }

    public static function validationResponse($validator)
    {
        foreach($validator->errors()->toArray() as $v => $a){

            $validationError = [
                'success'       => false,
                'error'         => true,
                'response_code' => 422,
                'message'       => $a[0],
            ];
    
            return $validationError;
            
        }

    }
}