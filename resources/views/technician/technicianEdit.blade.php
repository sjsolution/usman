@extends('appsp')

@section('content')
 
<style>
        .form-control countrycode, .form-control email, .form-control mobile_no ,.form-control
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

            
    <h3 class="page-title">Edit Technicians</h3>

                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    @if (Session::get('error'))
                    @php $errors = Session::get('error');@endphp                 
                @endif
                {{-- <h4 class="card-title">Create</h4> --}}

                <form class="forms-sample" id="formdata" action="{{route('sp.technicianUpdated',$userdata->id)}}" data-validate-parsley =" " method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name </label>
                            <input type="text" maxlength="20" class="form-control" name="full_name_en" placeholder="Name" value="{{$userdata->full_name_en}}"
                                    required=" ">
                            </div>
                            @if ($errors->has('full_name_en'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('full_name_en') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:right;">الاسم</label>
                            <input type="text" style="text-align:right;" maxlength="20" class="form-control full_name_ar" value="{{$userdata->full_name_ar}}"
                                    name="full_name_ar" placeholder="الاسم" required=" " >
                            </div>
                            @if ($errors->has('full_name_ar'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('full_name_ar') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="arabic_label" style="float:left;">Email</label>
                                <input type="email" style="text-align:left;" maxlength="40" class="form-control email" value="{{$userdata->email}}"
                                    name="email" placeholder="Email" required=" ">
                            </div>
                            @if ($errors->has('email'))
                                <span  style="color:red; position: absolute;
                                top: 61px;">{{ $errors->first('email') }}<span>
                            @endif   

                        </div>
                        
                        <div class="col-md-6">
                                
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Phone.no</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 39px;">+965</span>
                                    </div>
                                    <input type="text" style="text-align:left;" maxlength="12" class="form-control mobile_no"
                                name="mobile_no" value="{{ $userdata->mobile_number }}" data-parsley-type="number" required=" " >
                                @if ($errors->has('mobile_no'))
                                    <span style="color:red; position: absolute;
                                    top: 41px;">{{ $errors->first('mobile_no') }}<span>
                                @endif   
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <br>
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
