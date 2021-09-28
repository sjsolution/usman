<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{ 

  public function resetPassword(Request $request,$encrypted_data)
  {
    $tokens         = $encrypted_data;
    $tokens1        = base64_decode($encrypted_data);
    $requesttoken   = explode('-',$tokens1);
    $user_id        = $requesttoken[0];

    if($request->isMethod('post')){

      $user = User::find($user_id);
      if($encrypted_data == $user->password_reset_token){

        $validator= Validator::make($request->all(),[
            'password' => 'required|confirmed|min:6',
        ]);

        if($validator->fails()){
          return back()->with('error',$validator->errors()->first());
        }

        $user->password = bcrypt($request->password);
        $user->password_txt = $request->password;
        $user->password_reset_token= '';
        $user->save();
        return back()->with('success','Password has been reset successfully!.');

      }else{
        return back()->with('error','Reset password link has been expired!.');
      }

    }

    return view('auth.reset_password',compact('tokens'));

  }

}
