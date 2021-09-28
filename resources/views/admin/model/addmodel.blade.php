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
        <h3 class="page-title">{{ $title }} </h3>
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
            {{-- <h4 class="card-title">Create</h4> --}}
            <?php if(!empty ($model->id)){ ?>
                <form class="forms-sample" id="formdata" action="{{route('vehicle.updatemodel',$model->id)}}" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
              <?php }else{?>
                <form class="forms-sample" id="formdata" action="{{route('vehicle.createmodel')}}" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
              <?php } ?>
            {{-- <form class="forms-sample" id="formdata" action="{{route('vehicle.createbrand')}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off"> --}}
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name </label>
                            <input type="text" maxlength="20" class="form-control name_en" name="name_en" value="@if($model !=''){{ $model->name_en }}@endif" placeholder="Name"
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
                            <input type="text"  maxlength="20" class="form-control rtl"
                                name="name_ar" placeholder="الاسم" required=" " value="@if($model !=''){{ $model->name_ar }}@endif">
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
                                <label>Vehicle Brand's </label>
                                <select name="brand" class="form-control" required=" ">
                                  <option value="">Select brand</option>
                                    @foreach ($brand as $key => $value)
                                      <option value="{{ $value['id'] }}" @if(!empty($model->vehicle_brand_id) && $model->vehicle_brand_id == $value['id']){{ "selected" }}@endif>{{ $value['name_en'] }}</option>
                                    @endforeach

                                </select>
                              </div>
                            @if ($errors->has('brand'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('brand') }}</strong>
                            </span>
                            @endif
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                                  <label>Status </label>
                                  <?php $statusdata = [0=>'Active',1=>'Inactive']; ?>
                                  <select name="is_active" class="form-control" required=" ">
                                      @foreach ($statusdata as $key=>$status)
                                        <option value="{{ $key }}" @if(!empty($model->is_active) && $model->is_active == $key){{ "selected" }}@endif>{{ $status }}</option>
                                      @endforeach

                                  </select>
                                </div>
                              @if ($errors->has('is_active'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('is_active') }}</strong>
                              </span>
                              @endif
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
