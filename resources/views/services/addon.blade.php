@extends('appsp')

@section('content')

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
          <h3 class="page-title">Add-Ons</h3>
            {{-- <h4 class="card-title">Add-Ons</h4> --}}
          <form class="forms-sample"  id="formdata" action="{{route('sp.createdServices')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Title</label>
                          <input type="text" maxlength="50" class="form-control" name="title_en" placeholder="title" required>
                      </div>
                      @if ($errors->has('title_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('title_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label" style="float:right;">العنوان</label>
                          <input type="text" style="text-align:right;" maxlength="50" class="form-control arabic_name" name="title_ar" placeholder="العنوان" required>
                      </div>
                      @if ($errors->has('title_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                        <div class="form-group">
                                <label for="status">Type</label>
                                <select name="type" id="status" class="form-control" required>
                                    <option disabled selected value> -- select an option -- </option>
                                  <option value="1">Insurance</option>
                                  <option value="2">Normal</option>
                                  <option value="3">Emergency</option>
                                </select>
                             </div>
                              @if ($errors->has('type'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('type') }}</strong>
                              </span>
                              @endif
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Category</label>
                      <select name="category" id="category" class="form-control" required>
                          <option disabled selected value> -- select an option -- </option>
                      </select>
                    </div>
                    @if ($errors->has('category'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('category') }}</strong>
                    </span>
                    @endif
              </div>
            </div>
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                  <label for="status">Sub-category</label>
                  <select name="sub_category" id="subcategory" class="form-control" >
                  <option disabled selected value> -- select an option -- </option>
                  </select>
                  </div>
                  @if ($errors->has('sub_category'))
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('type') }}</strong>
                  </span>
                  @endif
                </div> 
                <div class="col-md-6">
                        <div class="form-group">
                            <label >Amount</label>
                            <input type="text" maxlength="50" class="form-control" name="amount" placeholder=" Amount" required >
                        </div>
                        @if ($errors->has('amount'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('amount') }}</strong>
                        </span>
                        @endif
                </div>
             </div>
                  <div class="row field_wrapper">
                        <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Description</label>
                                    <input type="text" id="desc_en" maxlength="150" class="form-control" name="description_en[]" placeholder="Description " >
                                </div>
                                @if ($errors->has('description_en'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description_en') }}</strong>
                                </span>
                                @endif
                        </div>
                        <div class="col-md-5">
                                <div class="form-group">
                                    <label class="pull-right" >الوصف
                                        </label>
                                    <input type="text" id="desc_ar" maxlength="50" class="form-control rtl" name="description_ar[]" placeholder="الوصف " >
                                </div>
                                @if ($errors->has('description_ar'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description_ar') }}</strong>
                                </span>
                                @endif
                        </div>
                        <div class="col-md-1">
                            {{-- <button type="button" class="btn btn-sm btn-block btn-gradient-danger addesc" id="add_description" style="margin-top:35px;">+</button> --}}
                            <a  href="javascript:void(0);" class="add_button" title="Add field"><i  style=" margin-top: 39px; font-size: 24px; " class="fa fa-plus-circle" aria-hidden="true"></i></a>
                          </div> 
           
              <div class="col-md-12">
                  <div id="myTable" class="order-list form-group">
                  </div>
                            </div> 
                </div>
            </form>
          </div>
        </div>
    </div>
    
@endsection
@section('scripts')

  <script type="text/javascript">
  form submit
   $("input[value=Submit]").click(function(event) {
     if($("#formdata").parsley().validate()){
        $(this).attr("disabled",true);
       $('#formdata').submit();
    }
   });
  </script>
 
   
<script>
$(document).ready(function(){
  $('#status').change(function(){
      var status = $(this).val();
      $.get('{{route("get-category-bystatus")}}/'+status,function(data){
        if(data.status=="success"){
          $('#category').html(data.option);
        }
      }).fail(function(){
        $('#category').html('');
      });
  });
  $('#category').change(function(){
     var category = $(this).val();
     $.get('{{route("get_subcategory_bycategory")}}/'+category,function(data){
       if(data.status=="success"){
         $('#subcategory').html(data.option);
       } }).fail(function(){
         $('#subcategory').html('');
       });
  });
  // $(".attren").keyup(function() {
  //       var id = $(this).val();
  //       //alert(id.length);
  //       if (id.length > 0) {
  //         $(".attrar").attr("required", "true");
  //       }
  //       else
  //         {
  //           $(".attrar").removeAttr("required");
  //         }
  
  //     });
});
 
 </script>

        <script type="text/javascript">
          $(document).ready(function(){
              var maxField = 10; //Input fields increment limitation
              var addButton = $('.add_button'); //Add button selector
              var wrapper = $('.field_wrapper'); //Input field wrapper
              var fieldHTML = '<div class="row"><div class="col-md-6"><div class=""><label>Description</label><input class="form-control" type="text" name="desc_en[]" value=""/></div></div><div class="col-md-5"><div class="form-group"><label>الوصف</label><input class="form-control rtl" type="text" name="desc_en[]" value=""/></div></div><a href="javascript:void(0);" class="remove_button"><i style=" margin-top: 30px; font-size: 24px; " class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'; //New input field html 
              var x = 1; //Initial field counter is 1
              
              //Once add button is clicked
              $(addButton).click(function(){
                  //Check maximum number of input fields
                  if(x < maxField){ 
                      x++; //Increment field counter
                      $(wrapper).append(fieldHTML); //Add field html
                  }
              });
              
              //Once remove button is clicked
              $(wrapper).on('click', '.remove_button', function(e){
                  e.preventDefault();
                  $(this).parent('div').remove(); //Remove field html
                  x--; //Decrement field counter
              });
          });
          </script>

@endsection