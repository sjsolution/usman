<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use auth;
use App\User;
use App\Models\PasswordReset;
use Validator;
use App\Notifications\PasswordResetRequest;
use App\Notifications\UserVerifiedMail;
use Carbon\Carbon;

class ProviderLoginController extends Controller
{
  use AuthenticatesUsers;

  public function __construct()
  {
    //$this->middleware('guest')->except('logout');
    $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
  }


  public function userlogin(Request $request)
  {
    if($request->isMethod('post')){
     
      $this->validate($request, [
        'email'    => 'required|email',
        'password' => 'required'
      ]);

      // Attempt to log the user in
      if (Auth::guard()->attempt(['email' => $request->email,'user_type'=>1,'password' => $request->password], $request->remember)) {
        $user = Auth::user();
        if($user->is_active == 1){
          return redirect()->intended(route('user.home'));
        }else{
          return back()->with('error','Account not verified!');
        }
      }

      return back()->with('error','Incorrect email or password!');
      // if unsuccessful, then redirect back to the login with the form data
    }

    return view('auth.service-provider-login');
  }

  public function forgotPasswort()
  {
    return view('service-providers.forgot_password');
  }

  public function logout(Request $request)
  { 
    if(Auth::guard()->check()){
      Auth::guard()->logout();
      return redirect('/userlogin');
    }
  }


  public function activation(Request $request,$id)
  {
    $tokenExist = User::where('remember_token',$id)->first();

    if($tokenExist){
      if($tokenExist->is_active ==1){
        $success='';
        $error= 'Your account already verified!';
        return view('message.verification',compact('error','success'));
      }else{
        $user = User::find($tokenExist->id);
        $user->remember_token='';
        $user->is_verified_mobile='1';
        $user->is_verified_email='1';
        $user->is_active='1';
        $user->save();
        $success='Your account has been verified successfully.';
        $error= '';
        return view('message.verification',compact('error','success'));
      }
    }else{
      $success='';
      $error= 'Account not verified, Email link has been expired!';
      return view('message.verification',compact('error','success'));
    }
  }

  public function uniqueEmailExists(Request $request)
  {
    $user     = User::where('user_type',1)->where('email', $request->value)->exists();
    echo (string)(!$user);
  }

  public function forgotPasswordNotify(Request $request)
  {
      $validator = Validator::make($request->all(),[
          'email'      => 'email|required',
      ]);
      
      if($validator->fails()){

          return response()->json(Api::validationResponse($validator),422); 
      }

      $user = User::where('email', $request->email)->first();

      if (!$user){

          return response()->json(Api::apiErrorResponse(__('message.emailNotFound')),404);
  
      }
          
      $passwordReset = PasswordReset::updateOrCreate(
          ['email' => $user->email],[
              'email' => $user->email,
              'token' => str_random(60)
          ]
      ); 

      if ($user && $passwordReset){

          $mailDetails = [
              'greeting'     => 'Hello',
              'body'         => 'We have received a reset password request from you, to reset your password please click on the reset button below.',
              'body1'        => 'If you did not ask for a password reset, no further action is required .',
              'thanks'       => 'All the best,',
              'token'        => $passwordReset->token,
              'subject'      => 'Password Reset',
              'company_name' => 'Pound It Team',
          ];

          $user->notify(new PasswordResetRequest($mailDetails));
      }
         

      return response()->json(['status' => 'Success','message' => 'Reset link sent to your mail'],200);
  }


}
