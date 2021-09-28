@extends('app')
@section('content')
<style>
    .form-control
    {
      background-color: transparent!important;
    }
</style>

<div class="page-header">
    <nav aria-label="breadcrumb"></nav>
</div>
<br>

<link rel="stylesheet" href="{{ asset('date_range_picker/dist/daterangepicker.min.css') }}"/>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h3 class="page-title">User signup rewards settings</h3>
            <!-- <h4 class="card-title">Knet Charges</h4>  -->
                <form class="forms-sample" id="formdata" action="{{route('reward.setting.update')}}" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Date Range:</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="from_date" id="date-range0" placeholder="From Date"
                                           value="{{ !empty($rewards->from_date) && (!empty($rewards->to_date)) ?  $rewards->from_date. ' to ' . $rewards->to_date : '' }}"   required="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6"></div>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reward Amount (In KWD) </label>
                                <input type="text" min="0" max="100" class="form-control" name="reward_amount" id="reward_amount" placeholder="Reward Amount"
                                value="{{ !empty($rewards->reward_amount) ?  $rewards->reward_amount: '' }}"   required=" " data-parsley-required data-parsley-type="number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status </label>
                                <select class="form-control" id="status" name="status">
                                    <option value="0" {{ isset($rewards->status) && $rewards->status == 0 ? 'selected' : '' }}>In-Active</option>
                                    <option value="1" {{ isset($rewards->status) && $rewards->status == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                            </div>
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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
   <script src="{{ asset('date_range_picker/src/jquery.daterangepicker.js') }}"></script>
   <script src="{{ asset('date_range_picker/demo.js') }}"></script>
   <script src="{{ asset('js/parsley.js')}}"></script>

@endsection
