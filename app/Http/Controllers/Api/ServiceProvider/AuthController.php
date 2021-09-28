<?php

namespace App\Http\Controllers\Api\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Api;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'      => 'email|required',
            'password'   => 'required',
            'user_type'  => 'required|in:1'

        ]);

        if($validator->fails()){

            return response()->json(Api::validationResponse($validator),422);
        }


        if(Auth::attempt(['email' => request('email'), 'password' => request('password'),'user_type' => $request->user_type]))
        {
            $user = Auth::user();

            if($request->user_type == '1' && $user->is_active == 0)
            {
                return response()->json(Api::apiErrorResponse(__('message.accountBlocked')),422);
            }

            $success = [
                'success'       => true,
                'error'         => false,
                'response_code' => 200,
                'message'       => __('message.userLoggedIn'),
                'token'         =>  $user->createToken(time())->accessToken,
                'data'          => [
                    'name'         =>  $user->full_name_en,
                    'userId'       =>  $user->id,
                    'phone_number' =>  $user->phone_number
                ]
            ];

            return response()->json($success,200);

        }else{

            return response()->json(Api::apiErrorResponse(__('message.credentialsNotMatch')),422);

        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'      => 'email|required',
            'password'   => 'required',
        ]);

        if($validator->fails()){

            return response()->json(Api::validationResponse($validator),422);
        }


        if(Auth::guard('admin')->attempt(['email' => request('email'), 'password' => request('password'),'type' => '1', 'is_active' => '1']))
        {
            $user = Auth::guard('admin')->user();

            $success = [
                'success'       => true,
                'error'         => false,
                'response_code' => 200,
                'message'       => __('message.adminLoggedIn'),
                // 'token'         =>  $user->createToken(time())->accessToken,
                'data'          => [
                    'name'         =>  $user->name,
                    'userId'       =>  $user->id,
                    'mobile_number' =>  $user->mobile_number,
                    'profile_pic' =>  $user->profile_pic
                ]
            ];

            return response()->json($success,200);

        }else{
            return response()->json(Api::apiErrorResponse(__('message.credentialsNotMatch')),422);

        }
    }
}
