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
            <h3 class="page-title">Edit Sub Category</h3>
          <form class="forms-sample" id="formdata" action="{{route('category.updateSubCategory',$category->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name</label>
                          <input type="text" maxlength="50" class="form-control" name="name_en" value="{{$category->name_en}}" placeholder="Name" required>
                      </div>
                      @if ($errors->has('name_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">اسم</label>
                          <input type="text" maxlength="50" class="form-control arabic_name rtl" name="name_ar" value="{{$category->name_ar}}" placeholder="اسم" required>
                      </div>
                      @if ($errors->has('name_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_ar') }}</strong>
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
                </div>
                <input type="hidden" name="existingImage" value="{{$category->image}}">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select name="is_active" id="status" class="form-control" required>
                        <option value="0" {{$category->is_active ? '' :'selected'}}>Inactive</option>
                        <option value="1" {{$category->is_active ? 'selected' :''}}>Active</option>
                      </select>
                    </div>
                    @if ($errors->has('is_active'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('is_active') }}</strong>
                    </span>
                    @endif
              </div> --}}

                {{-- <div class="col-md-6">
                  @if(!empty($category->image))
                  <img class='tableimage' src="{{env('AWS_URL').$category->image}}">
                @endif
                </div> --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="parent_id">Category</label>
                    <select name="parent_id" id="parent_id" class="form-control" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $categore)
                        <option value="{{$categore->id}}" {{ $category->parent_id == $categore->id ? 'selected' : ''}}>{{$categore->name_en}}</option>
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
            <br>



          <style>
          .button-right button{
            float: right;
          }
          </style>


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
    <script>
    $(document).ready(function()
    {
      var counter = 0;

      $("#addrow").on("click", function() {
          // if(counter < 5){
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td class="abc"><input type="text" maxlength="50" placeholder="English Name" class="form-control" name="arrtibutes_en[]'+ counter +  '"/ required></td> <br>';
            cols += '<td class="abc second"><input type="text" maxlength="50" placeholder="اسم السمات" class="form-control arabic_name" name="arrtibutes_ar[]'+ counter +  '"/ required></td> <br>';
            cols += '<td ><button type="button" class="ibtnDel btn btn-sm btn-danger">x</button></td>';
            newRow.append(cols);
            $("div.order-list").append(newRow);
            counter++;

      });
          $("div.order-list").on("click", ".ibtnDel", function (event) {
              $(this).closest("tr").remove();
              counter -= 1
          });
    });
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
    </script>



    <script>
      $(function () {
       $(document).on("click",".deleteattributes",function(){
          let id = $(this).data('id');
          let name = $(this).data('id1');
          let language = $(this).data('id2');
         // alert(id+"->"+language+"->"+name);
          swal({
          title: 'Are you sure?',
          text: "You want to delete External Attributes "+name+"!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3f51b5',
          cancelButtonColor: '#ff4081',
          confirmButtonText: 'Great ',
          buttons: {
            cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              className: "btn btn-warning",
              closeModal: true,
            },
            confirm: {
              text: "OK",
              value: true,
              visible: true,
              className: "btn btn-info",
              closeModal: true,
            }
          }

        }).then(function(isConfirm){
          if(isConfirm){
            $.ajax({
              url: "{{route('category.subcategoryUpdate')}}",
              method: 'POST',
              data:{id:id,name:name,language:language},
              //type:"json",
              success: function(response) {
                swal({
                  text: 'Attribute has been deleted sucessfully.......',
                  button: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-primary"
                  }
                })
                //console.log('response.data');
                 if(response.status == 'success'){
                   	$('.attributes').html(response.html);
                 }
              },
              error:function(){
                swal({
                  text: 'Something went wrong',
                  button: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-primary"
                  }
                })
              }
          });
          }
        })

        });
  });

   </script>
@endsection
