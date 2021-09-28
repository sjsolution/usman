<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use WebPushNotification;
use App\Events\WalletGift;



class TestController extends Controller
{
  public function forgotpassword(Request $request)
  {
    $verify = $request->verify;
    $user   = User::where('email',$verify)->orwhere('mobile_number',$verify)->first();
    if($user){
      $email= $user->email;
      $user->password_reset_token=encrypt($user->id).'-'.time();
      $user->save();
      Mail::to($email)->send(new ForgotPasswordSp($user,$user->password_reset_token));
    }
  }

  public function ss(){
    WebPushNotification::webPush();
  }

  public function gift()
  {
    $user = \App\User::find(140);
    event(new WalletGift($user));
  }
}
