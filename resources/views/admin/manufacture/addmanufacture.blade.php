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
        <h3 class="page-title">{{ $title }}</h3>

                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
            {{-- <h4 class="card-title">Create</h4> --}}
            <?php if(!empty ($manufacture->id)){ ?>
                <form class="forms-sample" id="formdata" action="{{route('vehicle.updatemanufacture',$manufacture->id)}}" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
              <?php }else{?>
                <form class="forms-sample" id="formdata" action="{{route('vehicle.createmanufacture')}}" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
              <?php } ?>
            {{-- <form class="forms-sample" id="formdata" action="{{route('vehicle.createbrand')}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off"> --}}
                @csrf

                <?php $year = range(1980,date("Y")); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>From Year</label>
                            <select name="from_year" class="form-control fromyears" required=" ">
                              <option value="">Select year</option>
                                @foreach ($year as $key => $value)
                                  <option value="{{ $value }}" @if(!empty($manufacture->from_year) && $manufacture->from_year == $value){{ "selected" }}@endif>{{ $value }}</option>
                                @endforeach
                            </select>

                        </div>
                        @if ($errors->has('from_year'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('from_year') }}</strong>
                        </span>
                        @endif
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">To year</label>
                            <select name="to_year" class="form-control toyear" required=" ">
                              <option value="">Select year</option>
                              @foreach ($year as $key => $value)
                                <option value="{{ $value }}" @if(!empty($manufacture->to_year) && $manufacture->to_year== $value){{ "selected" }}@endif>{{ $value }}</option>
                              @endforeach
                            </select>
                        </div>
                        @if ($errors->has('to_year'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('to_year') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="row">
 
                    <div class="col-md-6">
                        <div class="form-group">
                                <label>Vehicle's  Model</label>
                                <select name="vehicle" class="form-control" required=" ">
                                  <option value="">Select vehicles</option>
                                    @foreach ($vehicles as $key => $value)
                                      <option value="{{ $value['id'] }}" @if(!empty($manufacture->vehicle_model_id) && $manufacture->vehicle_model_id == $value['id']){{ "selected" }}@endif>{{ $value['name_en'] }}</option>
                                    @endforeach

                                </select>
                              </div>
                            @if ($errors->has('vehicle'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('vehicle') }}</strong>
                            </span>
                            @endif
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                                  <label>Status </label>
                                  <?php $statusdata = [0=>'Active',1=>'Inactive']; ?>
                                  <select name="is_active" class="form-control" required=" ">
                                      @foreach ($statusdata as $key=>$status)
                                        <option value="{{ $key }}" @if(!empty($manufacture->is_active) && $manufacture->is_active == $key){{ "selected" }}@endif>{{ $status }}</option>
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
<script>
$(document).ready(function(){
    $(".fromyears").change(function(){
      let fromyear = $(this).val();
      $.ajax({
               type:'POST',
               url:'{{route('vehicle.yearselection')}}',
               data:{fromyear:fromyear},
               success:function(data) {
                 console.log(data);
                  $(".toyear").html(data);
               }
            });
    });
});
</script>

@endsection
