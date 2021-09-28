<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title> MAAK </title>
      <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="{{asset('css/stylelogin.css')}}">
      <link rel="icon" href="{{asset('images/DashLogo.png')}}" type="image/x-icon" />
   </head>
   <body >

      <div class="hedaer">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-4 logo-color text-center"  >
                  <img src="{{asset('images/header-logo.png')}}">
               </div>
               <div class="col-md-8 col-sm-8 col-xs-8">
               </div>
            </div>
         </div>
      </div>

      <div class="login-page">
         {{-- <img src="{{asset('images/form-logo.png')}}"> --}}
         <h3 style="text-align:center; color:#EC7326">Reset Password</h3>
         <form method="POST" action="{{ route('resetPassword',$tokens) }}">
             @csrf
             @if(Session::has('error'))
                  <div class="alert alert-danger">
                      {{ Session::get('error') }}
                  </div>
           @endif
           @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
          @endif
             <input id="password" type="password" placeholder="New Password." class="@error('email') is-invalid @enderror" name="password" value="" required autofocus>

             @error('password')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
             @enderror
             <input id="confirmpassword" type="password" placeholder="Confirm Password. " class="@error('password_confirmation') is-invalid @enderror" name="password_confirmation" required >
             @error('password_confirmation')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
             @enderror
             <input type="submit" name="" value="Submit">

            <!-- <input type="Password" name="" placeholder="User id">
            <input type="Password" name="" placeholder="Password">
            <input type="submit" name="" value="Login"> -->
         </form>
      </div>
      <script src="js/bootstrap.min.js"></script>
   </body>
</html>
