<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Maak </title>
   <link rel="shortcut icon" href="{{asset('favicon.png')}}" />
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.addons.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{asset('vendors/iconfonts/font-awesome/css/font-awesome.min.css')}}" />
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <link rel="stylesheet" href="{{asset('css/parsley.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/lightgallery/css/lightgallery.css')}}">
  <!-- endinject -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{asset('images/DashLogo.png')}}" />
  <style>
  .tableimage{height:40px !important;border-radius: 50% !important;width:40px !important; margin-right: 5px !important;}
  </style>
  @yield('stylesheets')
  <style>
    #cover-spin {
  position:fixed;
  width:100%;
  left:0;right:0;top:0;bottom:0;
  background-color: rgba(255,255,255,0.7);
  z-index:9999;
  display:none;
}

@-webkit-keyframes spin {
from {-webkit-transform:rotate(0deg);}
to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
from {transform:rotate(0deg);}
to {transform:rotate(360deg);}
}

#cover-spin::after {
  content:'';
  display:block;
  position:absolute;
  left:48%;top:40%;
  width:40px;height:40px;
  border-style:solid;
  border-color:black;
  border-top-color:transparent;
  border-width: 4px;
  border-radius:50%;
  -webkit-animation: spin .8s linear infinite;
  animation: spin .8s linear infinite;
}
  </style>
</head>
