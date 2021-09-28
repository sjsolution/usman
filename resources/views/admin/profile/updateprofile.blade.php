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
          <h3 class="page-title">Update Profile</h3>
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
          <form class="forms-sample" id="profileform" action="{{route('admin.myprofile')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name</label>
                            <input type="text" class="form-control" maxlength="20" name="name" value="{{$user->name}}" placeholder="Name" required>
                      </div>
                      @if ($errors->has('name'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}"placeholder="Email address">
                    </div>
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Profile Pic</label>
                      <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/x-png,image/gif,image/jpeg"/>
                    </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    @if($user->profile_pic)
                      <div id="lightgallery-without-thumb" class="row lightGallery">
                          <a href="{{config('app.AWS_URL').$user->profile_pic}}" class="">
                          <img src="{{config('app.AWS_URL').$user->profile_pic}}" alt="" width="150" height="150">
                        </a>
                      </div>
                  @else
                    <div id="lightgallery-without-thumb" class="row lightGallery">
                      <a href="{{config('app.DEFAULT_IMAGE')}}" class="image-tile">
                        <img src="{{config('app.DEFAULT_IMAGE')}}" class="" width="150" height="150">
                      </a>
                    </div>

                  @endif
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
