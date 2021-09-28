<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\Models\PasswordReset;
use Validator;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $passwordReset = PasswordReset::where('token',$token)->first();
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => !empty($passwordReset) ? $passwordReset->email : '']
        );
    }

         /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'    => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token'    => 'required|string'
    
        ]);

        
        if($validator->fails()){

            return redirect()->back()->withErrors( $validator )->withInput();

        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset){
            return redirect()->back()->with('error', 'something went wrong: token expire'); 
        }
            

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user){
            return redirect()->back()->withErrors(['errors' => 'Email not found our records'])->withInput();
        }
        

        $user->password      = bcrypt($request->password);
        $user->password_txt	 = $request->password;
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        return redirect()->back()->with('success', 'your password successfully changed,please login your app');   


    }


}
