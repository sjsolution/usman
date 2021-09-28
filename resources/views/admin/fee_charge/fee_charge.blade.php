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
        <h3 class="page-title">K-net charges on a transaction</h3>

            
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
         <!-- <h4 class="card-title">Knet Charges</h4>  -->
            <form class="forms-sample" id="formdata" action="{{route('fee.charge')}}" data-validate-parsley  method="post"
                enctype="multipart/form-data" autocomplete="off">
                @csrf

                <br>              
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fixed Price (In KWD) </label>
                            <input type="text" min="0" class="form-control name_en" name="fixed_price_knet" id="fixed_price_knet" placeholder="Fixed Price"
                               value="{{ !empty($charges->knet_fixed_charges) ?  $charges->knet_fixed_charges: '' }}"   required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('fixed_price_knet'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('fixed_price_knet') }}</strong>
                        </span>
                        @endif
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission (In %) </label>
                            <input type="text" min="0" max="100" class="form-control name_en" name="commission_per_knet" id="commission_per_knet" placeholder="Commission Percent"
                            value="{{ !empty($charges->knet_commission_charges) ?  $charges->knet_commission_charges: '' }}"  required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('commission_per_knet'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('commission_per_knet') }}</strong>
                        </span>
                        @endif
                    </div>
                   
                </div>

                <strong>Debit/credit charges on a transaction</strong>
                <hr>
                <br>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fixed Price (In KWD) </label>
                            <input type="text" min="0" class="form-control name_en" name="fixed_price_other" id="fixed_price_other" placeholder="Fixed Price"
                               value="{{ !empty($charges->other_fixed_charges) ?  $charges->other_fixed_charges: '' }}"   required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('fixed_price_other'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('fixed_price_other') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission (In %) </label>
                            <input type="text" min="0" max="100" class="form-control name_en" name="commission_per_other" id="commission_per_other" placeholder="Commission Percent"
                            value="{{ !empty($charges->other_commission_charges) ?  $charges->other_commission_charges: '' }}"  required=" " data-parsley-required data-parsley-type="number">
                        </div>
                        @if ($errors->has('commission_per_other'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('commission_per_other') }}</strong>
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