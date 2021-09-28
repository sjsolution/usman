@extends('app')
@section('content')
<style>
    .form-control 
    {
      background-color: transparent!important;
    }
</style>
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
        <h3 class="page-title">{{ $user->full_name_en }} : Change Password</h3>

            
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
         <!-- <h4 class="card-title">Knet Charges</h4>  -->
            <form class="forms-sample" id="formdata" action="{{route('admin.change.password')}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                <br>              
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password </label>
                            <input type="password" maxlength="20" class="form-control name_en" name="password" id="password" placeholder="Enter Password"
                                 required=" " data-parsley-required>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" maxlength="20" class="form-control name_en" name="password_confirmation" id="password_confirmation" placeholder="Enter Confirm Password"
                            required=" " data-parsley-required>
                        </div>
                        @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                   
                </div>

                <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
                <a href="{{ url('admin/serviceproviderlist') }}" class="btn btn-gradient-danger ">Back</a>
              
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">

// form submit
$("input[value=Submit]").click(function(event) {
if ($("#formdata").parsley().validate()) {
    $(this).attr("disabled", true);
    $('#formdata').submit();
}
});
</script>
@endsection