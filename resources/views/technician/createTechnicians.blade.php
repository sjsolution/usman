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

                <h3 class="page-title">Technicians </h3>

                
                <form class="forms-sample" id="formdata" action="{{route('sp.technicianCreated')}}" data-validate-parsley =" " method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name </label>
                                <input type="text" maxlength="20" class="form-control" name="full_name_en" placeholder="Name"
                            required=" " value="{{ old('full_name_en') }}">
                                @if ($errors->has('full_name_en'))
                                    <span style="color:red">{{ $errors->first('full_name_en') }}<span>
                                @endif    
                            </div>
                          
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:right;">الاسم</label>
                                <input type="text" style="text-align:right;" maxlength="20" class="form-control full_name_ar"
                                    name="full_name_ar" placeholder="الاسم" required=" " value="{{ old('full_name_ar') }}">
                                @if ($errors->has('full_name_ar'))
                                    <span style="color:red">{{ $errors->first('full_name_ar') }}<span>
                                @endif   
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="arabic_label" style="float:left;">Email</label>
                                <input type="email" style="text-align:left;" maxlength="40" class="form-control email"
                                    name="email" placeholder="Email" required=" " value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span style="color:red">{{ $errors->first('email') }}<span>
                                @endif   
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
                                    name="mobile_number" placeholder="Phone.no" data-parsley-type="number" required=" " value="{{ old('mobile_number') }}">
                                    @if ($errors->has('mobile_number'))
                                        <span style="color:red; position: absolute;
                                        top: 41px;">{{ $errors->first('mobile_number') }}<span>
                                    @endif   
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="row">

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
