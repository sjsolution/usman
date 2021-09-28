@extends('app')
@section('content')
<style>
    input.parsley-success, select.parsley-success, textarea.parsley-success
    {
      background-color: transparent!important;
    }
    </style>
    <!-- Content Header (Page header) -->
<div class="row">
    
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

          <h3 class="page-title">Edit admin wallet </h3>
          
              @if(Session::has('message'))
              <p class="alert {{ Session::get('alert-class-success', 'class="alert alert-success alert-block"') }}">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                your amount details have been changed successfully.</p>
              @endif
          <form class="forms-sample" id="formdata" action="{{route('addmoney.updated',$admin_wallet->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-6">
                  	<div class="form-group">
                          <label>Amount</label>
                  		<input type="text" name="amount" class="form-control amount" value= "{{$admin_wallet->amount}} " required="">
                    </div>
                  	@if ($errors->has('amount'))
                  	<span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('amount') }} </strong>
                  	</span>
                  	@endif
                </div>
                <div>   
                	@if (Session::has('msg'))
                    <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{Session::get('msg')}}</strong>
                    </div>
                    @endif 
                </div>
                <div class="col-md-6">
                  	<div class="form-group">
                          <label>Credit Amount</label>
                  		<input type="text" name="credit_amount" class="form-control credit_amount" value= "{{$admin_wallet->credit_amount}} " required="">
                    </div>
                  	@if ($errors->has('credit_amount'))
                  	<span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('credit_amount') }} </strong>
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
