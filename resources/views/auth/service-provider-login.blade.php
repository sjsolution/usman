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
         <img src="{{asset('images/form-logo.png')}}">
         <form method="POST" action="{{ route('userlogin') }}">
             @csrf
             @if(Session::has('error'))
                  <div class="alert alert-danger">
                      {{ Session::get('error') }}
                  </div>
           @endif
             <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Email.">

             @error('email')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
             @enderror
             <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password"  autocomplete="current-password" placeholder="Password.">
             @error('password')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                 
             @enderror

               <a href="{{ route('sp.forgot') }}" style="cursor: pointer;float: right;color: #EC7326;margin-top: 10px;">Forgot Password</a>
              
             <input type="submit" name="" value="Login">
           
            <!-- <input type="Password" name="" placeholder="User id">
            <input type="Password" name="" placeholder="Password">
            <input type="submit" name="" value="Login"> -->
         </form>
      </div>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
   </body>
</html>
