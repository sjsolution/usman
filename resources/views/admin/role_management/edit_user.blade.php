@extends('app')
@section('content')
 
<style>
    .form-control countrycode, .form-control email, .form-control mobile_no ,.form-control
    {
        background-color: transparent!important;
    }
</style>

<div class="page-header">
    <nav aria-label="breadcrumb"></nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3 class="page-title">Edit User </h3>
                <form class="forms-sample" id="formdata" action="{{route('role.user.update',$admin->id)}}" data-validate-parsley =" " method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name </label>
                            <input type="text" maxlength="20" class="form-control" name="name" value="{{ $admin->name }}"
                                    required=" ">
                            </div>
                        </div>
                        <div class="col-md-6">   
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Phone.no</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 39px;">+965</span>
                                    </div>
                                    <input type="text" style="text-align:left;" maxlength="12" class="form-control mobile_no"
                                name="mobile_no" value="{{ $admin->mobile_number }}" data-parsley-type="number" required=" ">
                                </div>
                                
                            </div>
                            @if ($errors->has('mobile_no'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('mobile_no') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="arabic_label" style="float:left;">Email</label>
                                <input type="email" style="text-align:left;" maxlength="40" class="form-control email"
                             name="email" value="{{ $admin->email }}" required=" ">
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif

                        </div> 
                
                        <div class="col-md-6">   
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" placeholder="Profile Image">
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">   
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">--Select Role--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id}}" {{ ($admin->role[0]->role_id == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
            
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @if($admin->profile_pic)
                                    <div id="lightgallery-without-thumb" class="row lightGallery">
                                        <a href="{{config('app.AWS_URL').$admin->profile_pic}}" class="">
                                        <img src="{{config('app.AWS_URL').$admin->profile_pic}}" alt="" width="150" height="150">
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
