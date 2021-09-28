@extends('app')

@section('content')


<style>
  input.parsley-error, select.parsley-error, textarea.parsley-error
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
            <h3 class="page-title">Banner</h3>


          <form class="forms-sample" id="formdata" action="{{route('banner.createBanner')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Title</label>
                          <input type="text" maxlength="250" class="form-control" name="title_en" placeholder="Title" required>
                      </div>
                      @if ($errors->has('title_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('title_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">العنوان</label>
                          <input type="text"  maxlength="250" class="form-control arabic_name rtl" name="title_ar" placeholder="العنوان" required>
                      </div>
                      @if ($errors->has('title_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('title_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Description</label>
                          <textarea  class="form-control" name="description_en" placeholder="Description" required></textarea>
                      </div>
                      @if ($errors->has('description_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('description_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right" >الوصف</label>
                          <textarea   class="form-control rtl" name="description_ar" placeholder="الوصف" required></textarea>

                      </div>
                      @if ($errors->has('title_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('title_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Banner image (English User)</label>
                          
                          <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>
                            
                          </div>
                          <input type="file" name="banner_image" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg" required>
                        </div>
                      @if ($errors->has('banner_image'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('banner_image') }}</strong>
                      </span>
                      @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Banner image (Arabic User)</label>
                          
                          <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>
                            
                          </div>
                          <input type="file" name="banner_image_ar" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg" required>
                        </div>
                      @if ($errors->has('banner_image_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('banner_image_ar') }}</strong>
                      </span>
                      @endif
                </div>
              </div>
              <div class="row">  
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Type</label>
                      <select name="type" id="status" class="form-control" required>
                          <option disabled selected value> -- select an option -- </option>
                        <option value="1">Splash</option>
                        <option value="2">Carousel</option>
                      </select>
                    </div>
                    @if ($errors->has('type'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                    @endif
              </div>
            </div>
              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >
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
     if($("#formdata").parsley().validate()){
        $(this).attr("disabled",true);
       $('#formdata').submit();
    }
   });
  </script>


@endsection
