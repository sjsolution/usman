@extends('appsp')
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
                    <h4 class="card-title">Car Groups</h4>
                    <form class="cmxform" action="{{route('cargroup.save')}}" id="formdata" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                           @if(!empty($groups))
                               @foreach($groups as $group)
                            <div class="col-md-6 form-group">
                                <label class="col-form-label ">{{$group->group_name}}</label>
                                <input class="form-control" name="group[{{$group->id}}]"  type="number" placeholder="Enter percentage value" value="{{$providerGroup[$group->id]->percentage ?? 0}}" required min="0" max="100" step="0.01">
                            </div>
                                @endforeach
                           @endif

                            

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
