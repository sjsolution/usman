@extends('app')
@section('content')
<style>
    input.parsley-success, select.parsley-success, textarea.parsley-success
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

          <h3 class="page-title">Edit label </h3>
          
              @if(Session::has('message'))
              <p class="alert {{ Session::get('alert-class-success', 'class="alert alert-success alert-block"') }}">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                your label details have been changed successfully .</p>
              @endif
            {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample" id="formdata" action="{{route('label.updated',$label->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name</label>
                          <input type="text" maxlength="254" class="form-control" name="label_name_en" value="{{$label->name_en}}" placeholder="Name" required="">
                      </div>
                      @if ($errors->has('label_name_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('label_name_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">الاسم</label>
                          <input type="text" maxlength="254" class="form-control rtl" name="label_name_ar" value="{{$label->name_ar}} " placeholder="اسم" required>
                      </div>
                      @if ($errors->has('label_name_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('label_name_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Language Key</label>
                  <input type="text" name="label_key" class="form-control label_key" value= "{{$label->label_key}} " readonly required="">
                         
                        </div>
                      @if ($errors->has('label_key'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('label_key') }} </strong>
                      </span>
                      @endif
                </div>
                <div>   @if (Session::has('msg'))
                    <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{Session::get('msg')}}</strong>
                    </div>
                    @endif </div>
            </div>
            <br>
            <br>
              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
              <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
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
     if($("#formdata").parsley().validate()){
        $(this).attr("disabled",true);
       $('#formdata').submit();
    }
   });
  </script>
@endsection
