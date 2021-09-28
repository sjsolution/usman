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
            

        <h3 class="page-title">Edit Vehicle Details</h3>



                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
            {{-- <h4 class="card-title">Create</h4> --}}
            <form class="forms-sample" id="formdata" action="{{route('vehicle.updated',$vehicle->id)}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name </label>
                        <input type="text" maxlength="20" class="form-control name_en" name="name_en" placeholder="Name" value="{{$vehicle->name_en}}"
                                required=" ">
                        </div>
                        @if ($errors->has('name_en'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_en') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="pull-right">الاسم</label>
                            <input type="text"  value="{{$vehicle->name_ar}}" maxlength="20" class="form-control rtl"
                                name="name_ar" placeholder="الاسم"  required=" " >
                        </div>
                        @if ($errors->has('name_ar'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_ar') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                   
                    <div class="col-md-6">
                        <div class="form-group">
                                <label>Vehicle's image</label>
                                
                                <div class="input-group col-xs-12">
                                  <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                                  <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                                  </span>
                                  
                                </div>
                                <input type="file" name="image" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg" >
                              </div>
                            @if ($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                            @endif
                      </div>
                      <div class="col-md-6">
                            <div class="form-group">
                              <label for="image">Current Image :</label>
                              <img src="{{config('app.AWS_URL').$vehicle->image}}"/>
                            </div>
                            
                      </div>
                </div>
                <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
                <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
                {{-- <input type="button " class="btn btn-gradient-danger " value="Cancel" > --}}
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