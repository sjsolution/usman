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
<h3 class="page-title">Top Rated</h3>
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
            {{-- <h4 class="card-title">Create</h4> --}}
            <form class="forms-sample" id="formdata" action="{{route('tr.createdToprated')}}" data-validate-parsley =" " method="post"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name </label>
                            <input type="text" maxlength="20" class="form-control full_name_en" name="full_name_en" placeholder="Name"
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
                            <label class="arabic_label pull-right">الاسم</label>
                            <input type="text"  maxlength="20" class="form-control full_name_ar rtl"
                                name="full_name_ar" placeholder="الاسم" required=" " >
                        </div>
                        @if ($errors->has('full_name_ar'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('full_name_ar') }}</strong>
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
