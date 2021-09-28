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
          <h3 class="page-title">Update Banner</h3>

          
            {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample" id="formdata" action="{{route('banner.updateBanner',$banner->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Title </label>
                          <input type="text" maxlength="250" class="form-control" name="title_en" value="{{$banner->title_en}}" placeholder="Title" required>
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
                          <input type="text" maxlength="250" class="form-control arabic_name rtl" name="title_ar"  value="{{$banner->title_ar}}"  placeholder="العنوان" required >
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
                          <label>Description </label>
                          <textarea  class="form-control" name="description_en" placeholder="Description" required>{{ $banner->description_en }}</textarea>
                      </div>
                      @if ($errors->has('description_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('description_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">الوصف</label>
                          <textarea  class="form-control rtl" name="description_ar" placeholder="الوصف" required>{{ $banner->description_ar }}</textarea>

                      </div>
                      @if ($errors->has('description_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('description_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Banner image (English View)</label>
                          <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>

                          </div>

                          <input type="file" name="banner_image" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg">
                          {{-- <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>
                          </div> --}}
                        </div>
                      @if ($errors->has('banner_image'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('banner_image') }}</strong>
                      </span>
                      @endif
                </div>
                <input type="hidden" name="existingImage" value="{{$banner->banner_image}}">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Banner image (Arabic View)</label>
                          <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>

                          </div>

                          <input type="file" name="banner_image_ar" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg">
                       
                        </div>
                      @if ($errors->has('banner_image_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('banner_image_ar') }}</strong>
                      </span>
                      @endif
                </div>
                <input type="hidden" name="existingImageAr" value="{{$banner->banner_image_ar}}">
             
                
            </div>
            
            <div class="row">
                <div class="col-md-6">
                  @if(!empty($banner->banner_image))
                  <img class='tableimage' src="{{config('app.AWS_URL').$banner->banner_image}}">
                @endif
                </div>
                <div class="col-md-6">
                  @if(!empty($banner->banner_image_ar))
                  <img class='tableimage' src="{{config('app.AWS_URL').$banner->banner_image_ar}}">
                @endif
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status">Type</label>
                  <select name="type" id="status" class="form-control" required>
                    <option value="1" @if($banner->type =="1"){{'selected'}} @endif>Splash</option>
                    <option value="2" @if($banner->type =="2"){{'selected'}} @endif>Carousel</option>
                  </select>
                </div>
                @if ($errors->has('type'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
                @endif
              </div>
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
