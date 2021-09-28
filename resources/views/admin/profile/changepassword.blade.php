@extends('app')
 
@section('content')

    <!-- Content Header (Page header) -->
    <div class="page-header">
      <h3 class="page-title">Change password</h3>
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
            {{-- <h4 class="card-title">Create</h4> --}}
            @if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>{{ $message }}</strong>
              </div>
              @endif


              @if ($message = Session::get('error'))
              <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>{{ $message }}</strong>
              </div>
              @endif
          <form class="forms-sample" id="profileform" action="{{route('admin.changepassword')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Current Password</label>
                            <input type="password" class="form-control" name="current_password" value="" placeholder="Current Password" required>
                      </div>
                      @if ($errors->has('current_password'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('current_password') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" value="" placeholder="New Password" required>
                    </div>
                    @if ($errors->has('new_password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('new_password') }}</strong>
                    </span>
                    @endif
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Confirm Password</label>
                      <input type="password" class="form-control" name="confirm_password" value="" placeholder="Confirm Password" required>
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

@endsection
