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

<div class="row">
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
        <h3 class="page-title">Add Role</h3>

        <br>
                <form class="forms-sample" id="formdata" action="#" data-validate-parsley  method="post"
                    enctype="multipart/form-data" autocomplete="off">
           
                @csrf

                <div class="row">
                    <div class="col-md-3">
                        <label>Role Name</label>   
                    </div> 
                    <div class="col-md-3">:</div>
                    <div class="col-md-6">    
                        <input class="form-control" type="text" name="role_name" id="role_name" required>              
                    </div>
                </div>
                <br>
                <br>
                <div class="row" style="color:green">
                    <div class="col-md-2">
                        <strong>Access Control</strong>
                    </div>
                    <div class="col-md-2">  </div>
                    <div class="col-md-4">
                        <strong>Read Permission</strong>
                    </div>
                    <div class="col-md-4">
                        <strong>Write Permission</strong>
                    </div>
                </div>
                <hr><br>
                @foreach ($menus as $menu)
                    <div class="row">
                        <div class="col-md-2">
                            <strong>{{ $menu->name }}</strong>
                            <input type="hidden" name="menu[menu-{{$menu->id}}][id]"  value="{{ $menu->id }}">
                        </div>
                        <div class="col-md-2"> : </div>
                        <div class="col-md-4">
                            <span><input  type="checkbox"  name="menu[menu-{{$menu->id}}][read]"></span>
                        </div>
                        <div class="col-md-4">
                            <span><input  type="checkbox"  name="menu[menu-{{$menu->id}}][write]"></span>  
                        </div>
                    </div> 
                    <hr>  
                @endforeach
              
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
