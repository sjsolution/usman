@extends('app')

@section('content')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
          <h3 class="page-title">Add Category</h3>
            {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample"  id="formdata" action="{{route('category.createCategory')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name</label>
                          <input type="text" maxlength="50" class="form-control" name="name_en" placeholder="Name" required>
                      </div>
                      @if ($errors->has('name_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">الاسم</label>
                          <input type="text"  maxlength="50" class="form-control arabic_name rtl" name="name_ar" placeholder="اسم" required>
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
                          <label>Category image</label>
                          <input type="file" name="filename" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg">
                          <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                            </span>
                          </div>
                        </div>
                      @if ($errors->has('filename'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('filename') }}</strong>
                      </span>
                      @endif
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Type</label>
                      <?php $typesArray = ['1'=>'Normal','2'=>'Insurance','3'=>'Emergency'];?>
                        <select name="type" id="status" class="form-control" required>
                          <option value="">Select Type</option>
                          @foreach ($typesArray as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                    </div>
                    @if ($errors->has('type'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                    @endif
              </div>

              
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status">Is apply user applicable fee ?</label>
                
                </div>
              </div>
              <div class="col-md-6">
                  <label class="switch">
                    <input type="checkbox" class="switch-input" name="is_apply" id="is_apply" checked="">
                    <span class="slider round"></span>
                  </label>
              </div>
            </div>
           
            <strong style=" border-style: groove;
            border-bottom: thick line #ff0000;
            border-top: none;
            border-left: none;
            border-right: none;">User Applicable Fees</strong><br><br>
          
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label >Fixed Price</label>
                      <input type="number" min="0" class="form-control" name="fixed_price" id="fixed_price" placeholder="fixed_price">
                  </div>
                  @if ($errors->has('fixed_price'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('fixed_price') }}</strong>
                  </span>
                  @endif
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="arabic_label">Commission %</label>
                      <input type="number"  min="0" max="100" class="form-control arabic_name" name="commission_percent" placeholder="Commission Percent">
                  </div>
                  @if ($errors->has('commission_percent'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('commission_percent') }}</strong>
                  </span>
                  @endif
              </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label >Field Name</label>
                    <input type="text" maxlength="50" class="form-control" name="field_name" id="field_name" placeholder="Field Name">
                </div>
                @if ($errors->has('field_name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('field_name') }}</strong>
                </span>
                @endif
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label >اسم الحقل</label>
                  <input type="text" maxlength="50" class="form-control" name="field_name_ar" id="field_name_ar" placeholder="Field Name">
              </div>
              @if ($errors->has('field_name_ar'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('field_name_ar') }}</strong>
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
