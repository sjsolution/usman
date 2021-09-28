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
          <h3 class="page-title">Add Service</h3>
            {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample"  id="formdata" action="{{route('sp.createservice')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Title</label>
                          <input type="text" maxlength="50" class="form-control" name="title_en" placeholder="Title" required>
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
                                    <option disabled selected value> -- Select an option -- </option>
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
                          <option disabled selected value> -- Select an option -- </option>
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
                  <option disabled selected value> -- Select an option -- </option>
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
             

                <div class="col-md-6">
                    <div class="form-group">
                        <label >Vehicle type</label>
                        <select name="vehicle_type" id="status" class="form-control "  required  >
                            <option value=""> -- Select Vehilce Type -- </option>
                          @if($vehilce_type)
                          @foreach($vehilce_type as $vehilce_type_ids)
                        <option value="{{$vehilce_type_ids['id']}}">{{$vehilce_type_ids['name_en']}}</option>
                          @endforeach
                          @endif  
                          
                          </select>
            </div>
             </div>
             <div class="col-md-3">
                <div class="form-group">
                    <label >Service time duration</label>
                    <input type="text" maxlength="5" class="form-control" name="time" placeholder=" Time" required >
                </div>
                @if ($errors->has('time'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif
        </div>
        <div class="col-md-2">
            <div class="form-group " style=" margin-top: 22px; margin-left: -30px;">
                <select name="format" id="status" class="form-control" required>
                <option value=""> -- Select Time Format -- </option>
                <option value="mins">Minutes</option>
                <option value="hours">Hours</option>
              </select>
           </div>
            @if ($errors->has('time'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('time') }}</strong>
            </span>
            @endif
    </div>
        
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
           
                </div>
                 
                
                
                       {{-- <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <select name="service_time_duration[]" id="service_slot_length" class="form-control input-sm medium " required>
                                @foreach ($slotDuration as $key => $slots)
                                  <option value="{{ $key }}">{{$slots}}</option>
                                @endforeach
                              </select>
                          </div>
                        </div>
                              <label class="col-md-2">Add-ons:</label>
                              <div class="col-md-6">
                               <input type="radio" name="add-on" value="1"> Yes<br>
                               <input type="radio" name="add-on" value="2"> No<br>              
                              </div>
                            
                       </div> --}}
                {{-- this is  addons  area  --}}

                {{-- <div class="card-repeated" id ="repeated-content" > --}}
                {{-- <div class="row" >
                    <div class="col-md-6">
                      <div class="form-group">
                              <label>Add-Ons</label>
                              <input type="text" maxlength="50" name="add_on_name_en[]" class="form-control attren" placeholder="English name"  >
                      </div>
                    </div>
      
                    <div class="col-md-6">
                      <div class="form-group">
                              <label class="arabic_label pull-right">اسم السمات</label>
                              <input type="text" maxlength="50" name="add_on_name_ar[]" class="attrar form-control arabic_name rtl" placeholder="اسم السمات" >
                      </div>
                    </div>
                  </div> --}}
                   {{-- <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label >Description </label>
                              <input type="text" maxlength="50" name="description_addons[]" class="attrar form-control " placeholder="Description " >
                      </div>
                      </div>
                      <div class="col-md-5">
                          <div class="form-group">
                              <label >Amount </label>
                              <input type="text" maxlength="8" name="Amount_add_on[]" class="attrar form-control " placeholder="Description " >
                      </div>      
                  </div>
                  <div class="col-md-1">
                      <button type="button" class="btn btn-sm btn-block btn-gradient-danger addrow" id="addrow" style="margin-top:28px;">+</button>

                    </div>     
                
              </div>  --}}
            {{-- </div> --}}

              <div class="col-md-12">
                  <div id="myTable" class="order-list form-group">
                  </div>
                            </div>
                        
                <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >
                <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>   

                </div>
              
            </form>
          </div>
        </div>
    </div>
    
@endsection
@section('scripts')

  <script type="text/javascript">
  //form submit
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
  <script>
      
        // $(document).ready(function () {
      
        //   var counter = 0;
        //   $("#addrow").on("click", function () {
        //       // if(counter < 5){
        //         var newRow = $("<div>");
        //         var cols = "";
        //         cols += "<div>";
        //         cols +='<div class="row" ><div class="col-md-6"><div class="form-group"><label>Add-Ons</label><input type="text" maxlength="50" name="add_on_name_en[]" class="form-control attren" placeholder="English name"  ></div></div><div class="col-md-6"><div class="form-group"><label class="arabic_label pull-right">اسم السمات</label><input type="text" maxlength="50" name="add_on_name_ar[]" class="attrar form-control arabic_name rtl" placeholder="اسم السمات" ></div></div> </div>'
        //         cols += '<div class="row">  <div class="col-md-6"> <div class="form-group"> <label >Description </label><input type="text" maxlength="50" name="description_addons[]'+ counter +  '" class="attrar form-control " placeholder="Description " > </div> </div>  <div class="col-md-5"><div class="form-group"><label >Amount </label> <input type="text" maxlength="8" name="Amount_add_on[]" class="attrar form-control " placeholder="Description " ></div> </div> </div>';
               
        //         cols += '<div class="col-md-1"><button  type="button" class="ibtnDel btn btn-sm btn-gradient-danger">x</button></div>';
        //         cols += "</div>"
        //         newRow.append(cols);
        //         $("div.order-list").append(newRow);
        //         counter++;
        //       // }else{
        //       //   alert('Maximum 6 attributes allowed');
        //       //   //console.log('only for 10 time slot');
        //       //   //$("#monday").html('only for 10 time slot');
        //       // }
        //   });
        //   $("div.order-list").on("click", ".ibtnDel", function (event) {
        //       $(this).closest("tr").remove();
        //       counter -= 1
        //   });
        // });
      
        </script>
        {{-- <script>
          $(document).ready(function(){
            
            $("#desc_en").keyup(function() {
           var id = $(this).val();
           //alert(id.length);
           if (id.length > 0) {
           $("#desc_ar").attr("required", "true");
           }
          else
          {
            $("#desc_ar").removeAttr("required");
          }
  
      });
       
               var counters = 0;
            $(".addesc").on("click", function () {
                  // if(counter < 5){
                   var newRow = $("<div class='ibtnDel'>");
                   var cols = "";
                   cols += "<div>";
                   cols +='<div class="row "><div class="col-md-6"><div class="form-group "><label>Description</label><textarea type="text" id="desc_en" maxlength="150" class="form-control" name="description_en[]'+ counters +  '" placeholder="Description " ></textarea></div> @if ($errors->has('description_en'))<span class="invalid-feedback" role="alert"><strong>{{ $errors->first('description_en') }}</strong></span>@endif</div><div class="col-md-5"><div class="form-group"> <label class="pull-right" >الوصف  </label><textarea type="text" id="desc_ar" maxlength="50" class="form-control rtl" name="description_ar[]'+ counters +  '" placeholder="الوصف " ></textarea> </div>@if ($errors->has('description_ar'))<span class="invalid-feedback" role="alert"><strong>{{ $errors->first('description_ar') }}</strong></span>@endif</div><div class="col-md-1"><button  type="button" style=" margin-top: 35px; "class="ibtnDel btn btn-sm btn-gradient-danger">x</button></div></div>'
                   cols += '';
                   cols += "</div>"
                   newRow.append(cols);
                $("div.order-list").append(newRow);
                   counters++;
              // }else{
              //   alert('Maximum 6 attributes allowed');
              //   //console.log('only for 10 time slot');
              //   //$("#monday").html('only for 10 time slot');
              // }
                 });
                $("div.order-list").on("click", ".ibtnDel", function (event) {
                $(this).closest("div").remove();
                counters -= 1
          });
        });

         
        </script> --}}
        <script type="text/javascript">
          $(document).ready(function(){
              var maxField = 10; //Input fields increment limitation
              var addButton = $('.add_button'); //Add button selector
              var wrapper = $('.field_wrapper'); //Input field wrapper
              var fieldHTML = '<div class="row"><div class="col-md-6"><div class=""><label>Description</label><input class="form-control" type="text" name="description_en[]" value=""/></div></div><div class="col-md-5"><div class="form-group"><label>الوصف</label><input class="form-control rtl" type="text" name="description_ar[]" value=""/></div></div><a href="javascript:void(0);" class="remove_button"><i style=" margin-top: 30px; font-size: 24px; " class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'; //New input field html 
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
