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
        <h3 class="page-title">Insurance Coverage Range</h3>

                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
            {{-- <h4 class="card-title">Create</h4> --}}
            <form class="forms-sample" id="formdata" action="{{route('vehicle.coverage')}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Range </label>
                            <input type="text" maxlength="20" class="form-control name_en" name="start_range" id="start_range" placeholder="Start Range"
                               value="{{ !empty($range->start_range) ?  $range->start_range: '' }}"   required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('name_en'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_en') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Range </label>
                            <input type="text" maxlength="20" class="form-control name_en" name="end_range" id="end_range" placeholder="End Range"
                            value="{{ !empty($range->end_range) ?  $range->end_range: '' }}"  required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('name_en'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name_en') }}</strong>
                        </span>
                        @endif
                    </div>
                   
                </div>
               
                <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
                <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
              
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