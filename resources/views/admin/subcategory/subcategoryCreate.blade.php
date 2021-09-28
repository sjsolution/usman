@extends('app')

@section('content')

  <style>
  #addrow
  {

    padding: 10px 30px;
    color: #fff;
    width: 50px;
    margin-top: 24px;
    /* float: right; */
    /* margin-right: 100px; */
    /* margin-top: -60px; */

  }
  .abc input{

  margin-top: 5px;
  }


.abc
{
  width: 53%;
}

  .second input{
    width: 92%;
    margin-left: 40px!important;
  }
  .ibtnDel
  {
    padding: 8px 27px;
    margin-left: 35px;
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
            {{-- <h4 class="card-title">Create</h4> --}}
            <h3 class="page-title">Add Sub Category</h3>

          <form class="forms-sample" id="formdata" action="{{route('category.createSubCategory')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
              {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label for="status">Type</label>
                    <select name="type" id="type" class="form-control" required>
                      <option disabled selected value> -- select an option -- </option>
                      <option value="1">Insurance</option>
                      <option value="2">Normal</option>
                    </select>
                  </div>
                  @if ($errors->has('type'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('type') }}</strong>
                  </span>
                  @endif
            </div>

            <script>
             $(document).ready(function(){
             $("#type").on('change', function(){
               var value = $("#type").val()
          //  alert(value);
                $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                  type:'post',
                  url:"{{'categorylist'}}",
                  data:{value,value},
                  dataType:'json',
                  success:function(response){
                      if(response.status ==1){
                        $("#parent_id").html(response.html);
                     //  console.log(response);
                     }
                   }
                });
             });
             });
            </script> --}}

              <div class="col-md-6">

                <div class="form-group">
                  <label for="parent_id">Category</label>
                  <select name="parent_id" id="parent_id" class="form-control" required>
                    <option disabled selected value> -- select an option -- </option>
                  @foreach ($categories as $category)
                  <option value="{{$category->id}}/{{$category->type}}">{{$category->name_en}}</option>
                  @endforeach
                  </select>
                </div>
                @if ($errors->has('parent_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('parent_id') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="row">
              {{-- <div class="col-md-6">
                <div class="form-group">
                        <label>Category image</label>
                        <input type="file" name="filename" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg">
                        <div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-gradient-info" type="button">Upload</button>
                          </span>
                        </div>
                      </div>
                    @if ($errors->has('filename'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('filename') }}</strong>
                    </span>
                    @endif
              </div> --}}
              {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select name="is_active" id="status" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                  </div>
                  @if ($errors->has('is_active'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('is_active') }}</strong>
                  </span>
                  @endif
            </div> --}}
          </div>
              {{-- <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name (English)</label>
                          <input type="text" maxlength="50" class="form-control" name="name_en[]" placeholder="Name" required>
                      </div>
                      @if ($errors->has('name_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-5">
                      <div class="form-group">
                          <label class="arabic_label" style="float:right;">Name (Arabic)</label>
                          <input type="text" style="text-align:right;" maxlength="50" class="form-control arabic_name" name="name_ar[]" placeholder="اسم" required>
                      </div>
                      @if ($errors->has('name_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_ar') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-lg btn-block btn-gradient-danger addrow" id="addrow">+</button>

                  </div>
              </div> --}}





             <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                        <label>Name</label>
                        <input type="text" maxlength="50" name="name_en[]" required class="form-control attren" placeholder="English name" >
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group">
                        <label class="arabic_label pull-right">اسم السمات</label>
                        <input type="text" maxlength="50" name="name_ar[]" required  class="attrar form-control arabic_name rtl" placeholder="اسم السمات" >
                </div>
              </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-lg btn-block btn-gradient-danger addrow" id="addrow">+</button>

                    </div>

                    <div class="col-md-12">
                      <div id="myTable" class="order-list form-group">
                      </div>
                                </div>
          </div>



          <div class="input_fields_wrap">
          </div>

              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >
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
     if($("#formdata").parsley().validate()){
        $(this).attr("disabled",true);
       $('#formdata').submit();
    }
   });
  </script>


  <script>
$(".attren").keyup(function() {
  var id = $(this).val();
 // alert(id.length);
  if (id.length > 0) {
    $(".attrar").attr("required", "true");
  }
  else
    {
      $(".attrar").removeAttr("required");
    }
})
$(".attrar").keyup(function() {
  var id1 = $(this).val();
 // alert(id.length);
  if (id1.length > 0) {
    $(".attren").attr("required", "true");
  }
  else
    {
      $(".attren").removeAttr("required");
    }
})

  $(document).ready(function () {

    var counter = 0;
    $("#addrow").on("click", function () {
        // if(counter < 5){
          var newRow = $("<tr>");
          var cols = "";
          cols += '<td class="abc"><input type="text" maxlength="50" placeholder="English Name" class="form-control" name="name_en[]'+ counter +  '"required/></td> <br>';
          cols += '<td class="abc second"><input type="text" maxlength="50" placeholder="اسم السمات" class="form-control arabic_name" name="name_ar[]'+ counter +  '" required/></td> <br>';
          cols += '<td ><button  type="button" class="ibtnDel btn btn-sm btn-gradient-danger">x</button></td>';
          newRow.append(cols);
          $("div.order-list").append(newRow);
          counter++;
        // }else{
        //   alert('Maximum 6 attributes allowed');
        //   //console.log('only for 10 time slot');
        //   //$("#monday").html('only for 10 time slot');
        // }
    });
    $("div.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
    });
  });

  </script>

@endsection
