<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <title>{{ config('app.name') }}</title> -->
  <title> Maak </title>

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
  <link rel="stylesheet" href="{{ asset('css/timepicker.css')}}"/>`
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{asset('images/DashLogo.png')}}" />

<script src="{{asset('js/admin/jtable/jquery-1.6.4.min.js')}}"></script>
  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('vendors/js/vendor.bundle.addons.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/timepicker.js')}}"></script>

  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/misc.js')}}"></script>
  <script src="{{asset('js/settings.js')}}"></script>
  <script src="{{asset('js/todolist.js')}}"></script>
  <script src="{{ asset('js/toastDemo.js') }}"></script>

  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{asset('js/dashboard.js')}}"></script>
  <script src="{{asset('vendors/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('vendors/tinymce/themes/modern/theme.js')}}"></script>

<script src="{{asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/admin/themes/redmond/jquery-ui-1.8.16.custom.css')}}">
<link rel="stylesheet" href="{{asset('js/admin/jtable/themes/lightcolor/orange/jtable.css')}}">

<script src="{{asset('js/admin/jquery-ui-1.8.16.custom.min.js')}}"></script>
<script src="{{asset('js/admin/jtable/jquery.jtable.js')}}"></script>
<script src="{{ asset('js/parsley.js')}}"></script>
<script src="{{ asset('js/file-upload.js')}}"></script>
<style>
.tableimage{height:80px !important;border-radius: 50% !important;width:80px !important; margin-right: 5px !important;}
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
