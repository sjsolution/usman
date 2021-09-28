@extends('app')
@section('content')
<style>
    .form-control
    {
      background-color: transparent!important;
    }
    textarea#message_en {
        width: 100%;
    }
    textarea#message_ar {
        width: 100%;
    }
    label.col-form-label.dir-float {
        direction: unset !important;
        text-align: right;
        width: 100%;
    }
</style>

<div class="page-header">
    <nav aria-label="breadcrumb"></nav>
</div>


<div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Push Notification</h4>
                    <form class="cmxform" id="formdata" method="POST" action="" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-6 form-group">
                                <label class="col-form-label ">Title</label>
                                <input class="form-control" name="title_en" id="title_en" type="text" placeholder="Enter title in english" required>
                            </div>
                            <div class="col-md-6 form-group" style="direction: rtl;">
                                <label class="col-form-label dir-float">عنوان</label>
                                <input class="form-control dir-rtl" name="title_ar" id="title_ar" type="text" placeholder="Enter title in arabic" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="col-form-label ">Message</label><br>
                                <textarea rows="5" cols="51" id="message_en" name="message_en" required></textarea>
                            </div>
                            <div class="col-md-6 form-group" style="direction: rtl;">
                                <label class="col-form-label dir-float">رسالة</label> <br>
                                <textarea class="dir-rtl" rows="5" cols="51" id="message_ar" name="message_ar" required></textarea>
                            </div>


                            <div class="col-md-6 form-group">
                                <label class="col-form-label ">Send to</label>
                                <select class="form-control" id="send_to" name="send_to" required onchange="myFunction()">
                                    <option value="">--Select user --</option>
                                    <option value="0">All user</option>
                                    <option value="1">Specific user</option>
                                </select>
                            </div>

                        </div>


                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                        <div id="email_address" class="col-md-6" style="display:none">
                           <label class="col-form-label ">Select Email</label>
                            <select class="js-example-basic-multiple" multiple="multiple" style="width:100%" name="email[]" id="my_multiselect" >
                                @foreach ($email as $key => $value)

                                    <option value="{{$key}}">{{ $value }}</option>
                                @endforeach

                            </select>
                        </div>

                        <br>
                        <br>

                        <fieldset>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <input class="btn btn-primary" type="submit" value="Submit" id="btn-submit">
                                    <input type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">

                                </div>
                            </div>
                        </fieldset>

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

  function myFunction() {

      var x = document.getElementById("send_to").value;

      if(x==1){
        document.getElementById( 'email_address' ).style.display = 'block';
        $('#my_multiselect').attr('required',true);

    }else{
        document.getElementById( 'email_address' ).style.display = 'none';
       $('#my_multiselect').removeAttr('required');

    }
  }

</script>
<script src="{{ asset('js/parsley.js')}}"></script>
<script src="{{asset('js/select2.js')}}"></script>

@endsection
