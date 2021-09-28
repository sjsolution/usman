@extends('app')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="page-header">
      <h3 class="page-title">Service</h3>
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
          <form class="forms-sample" id="formdata" action="{{route('category.createCategory')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name (English)</label>
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
                          <label class="arabic_label">Name (Arabic)</label>
                          <input type="text" maxlength="50" class="form-control arabic_name" name="name_ar" placeholder="اسم" required>
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
                          <label >Category</label>
                          <select class="form-control" name="category_id" required>
                            @foreach($categories as $category)

                            <otption value="{{$category->id}}">{{$category->name_en}}</option>
                              @endforeach
                      </div>
                      @if ($errors->has('name_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_en') }}</strong>
                      </span>
                      @endif
                  </div>
                </div>
              <input type="button" value="Submit" class="btn btn-gradient-info mr-2">
              {{-- <input type="button " class="btn btn-gradient-danger " value="Cancel" > --}}
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
