<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session;
class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {

        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function adminlogin(Request $request){
        // dd(bcrypt('parth'));
      if($request->isMethod('post')){
        //dd(bcrypt('parth'));
        // Validate the form data
        $this->validate($request, [
          'email'   => 'required|email',
          'password' => 'required|min:5'
        ]);
        // Attempt to log the user in
        if (Auth::guard()->attempt(['email' => $request->email,'user_type'=>1,'password' => $request->password], $request->remember)) {
          $user = Auth::user();
          if($user->is_active == 1){
              return redirect()->intended(route('admin.home'));
          }else{
            return back()->with('error','Account not verified!');
          }
        }
        return back()->with('error','Incorrect email or password!');
        // if unsuccessful, then redirect back to the login with the form data
      }
      return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //   $this->validate($request, [
    //     'email'   => 'required|email',
    //     'password' => 'required|min:5'
    //   ]);
    //   // Attempt to log the user in
    //   if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
    //     return redirect()->intended(route('admin.home'));
    //   }
    //   return redirect()->back()->withInput($request->only('email', 'remember'));
    // }

    public function logout(Request $request)
    {

        if(Auth::guard('admin')->check()){
          Auth::guard('admin')->logout();
          Session::flush();
          $request->session()->regenerate();
          return redirect('/admin/adminlogin');
        }
    }
}
