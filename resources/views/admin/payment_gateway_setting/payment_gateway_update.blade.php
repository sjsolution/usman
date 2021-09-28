@extends('app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="page-header">
    
      <nav aria-label="breadcrumb">
        {{-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Forms</a></li>
          <li class="breadcrumb-item active" aria-current="page">Form elements</li>
        </ol> --}}
      </nav>
    </div>
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
          <h3 class="page-title">Update Payment Gateway</h3>
            {{-- <h4 class="card-title">Create</h4> --}}
            @if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>{{ $message }}</strong>
              </div>
              @endif
              @if ($errors->any())
     @foreach ($errors->all() as $error)
       <div class="alert alert-danger alert-block">
         <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $error }}</strong>
       </div>
     @endforeach
 @endif 
          <form class="forms-sample" id="profileform" action="{{route('paymentgatewaysetting.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                    <div class="custom-control custom-radio mt-3">
                        <input type="radio" id="customRadio1" name="payment_type" class="custom-control-input" value="0" checked="">
                        <label class="custom-control-label" for="customRadio1">Hisabe</label>
                    </div>
                 <div  style="padding-top:12px">
                    <input type="radio" id="customRadio2" value="1" name="payment_type">
                    <label for="customRadio2">My Fatoorah</label>
                </div>
                  </div>
              </div>
              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">

            </form>
          </div>
        </div>
    </div>
    </div>



@endsection
@section('scripts')
  <script src="{{ asset('js/parsley.js')}}"></script>
  <script src="{{ asset('js/file-upload.js')}}"></script>
  <script type="text/javascript">
  // form submit
   $("input[value=Submit]").click(function(event) {
     if($("#profileform").parsley().validate()){
        $(this).attr("disabled",true);
       $('#profileform').submit();
    }
   });
  </script>
  <script>
  (function($) {
  'use strict';
  if ($("#lightgallery").length) {
    $("#lightgallery").lightGallery();
  }

  if ($("#lightgallery-without-thumb").length) {
    $("#lightgallery-without-thumb").lightGallery({
      thumbnail: true,
      animateThumb: false,
      showThumbByDefault: false
    });
  }

  if ($("#video-gallery").length) {
    $("#video-gallery").lightGallery();
  }
})(jQuery);
  </script>
@endsection
