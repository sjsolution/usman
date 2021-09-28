@extends('appsp')

@section('content')
<style>
.showServiceType{
  display: block;
}
.hideServiceType{
  display: none;
}

.hideSubCategoryType{
  display: none;
} 

.showAmountBlock {
  display: none;
}

.showServiceTimeDurationBlock {
  display: none;
}

</style>

<div class="page-header">
  {{-- <h3 class="page-title">Add Service  </h3> --}}

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
      <h3 class="page-title"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Add Service  </h3>
      {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample"  id="formdata" action="{{route('sp.createservice')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
             

              <div class="row">
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status" >Category</label>
                    {{-- <select name="category" id="category" class="form-control" required>
                        <option disabled selected value> -- Select an option -- </option>
                    </select> --}}
                    <select name="category" id="category" class="form-control" required>
                        <option value=""> -- Select an option -- </option>
                        @foreach($categories as $category)
                          <option value="{{$category->id}}" data-id="{{ $category->type }}">  {{ $category->name_en}} </option>
                        @endforeach
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
                          <label >Service Name</label>
                          <input type="text" maxlength="100" class="form-control" name="title_en" placeholder="Title" required>
                      </div>
                      @if ($errors->has('title_en'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('title_en') }}</strong>
                      </span>
                      @endif
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label" style="float:right;">اسم الخدمة</label>
                          <input type="text" style="text-align:right;" maxlength="100" class="form-control arabic_name" name="title_ar" placeholder="العنوان" required>
                      </div>
                      @if ($errors->has('title_ar'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('name_ar') }}</strong>
                      </span>
                      @endif
                  </div>
              </div>

              <span class="serviceType hideServiceType">
              <div class="row ">
                <div class="col-md-6">
                  <div class="form-group ">
                    <label>Service Type</label>
                    <select class="form-control" name="serviceType">
                      <option value='1'>Full Party</option>
                      <option value='2'>Third Party</option>
                      {{-- <option value='3'>Both</option> --}}
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Premium (In %)</label>
                    <input type="text" name="premium_percentage" id="premium_percentage"  max="100"  min="0" class="form-control" placeholder="Enter percentage">
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Fixed Price (In KWD)</label>
                    <input type="text" min="0" name="fixed_price" id="fixed_price"  class="form-control" placeholder="Enter Fixed Price"  data-parsley-notequalto="#premium_percentage" data-parsley-notequalto-message="Premium %age & fixed price must not be zero at same time!">
                  </div>
                </div>
              </div>
              </span>

              <div class="row">
                <div class="col-md-6 hideSubCategoryType" id="subCategoryBlock">
                  <div class="form-group">
                    <label for="status">Sub-category</label>
                    <select name="sub_category" id="subcategory" class="form-control">
                      <option disabled selected value> -- Select an option -- </option>
                    </select>
                  </div>
                  @if ($errors->has('sub_category'))
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('type') }}</strong>
                  </span>
                  @endif
                </div>
                <div class="col-md-6" id="amountBlock">
                  <div class="form-group">
                      <label class="pull-left">Amount</label>
                      <input type="text" maxlength="50" class="form-control" name="amount" id="amount" placeholder=" Amount" min="0" step="100" 
                      data-parsley-validation-threshold="1"
                      data-parsley-trigger="keyup" 
                      data-parsley-type="number">
                  </div>
                  @if ($errors->has('amount'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('amount') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="status">Special Notes</label>
                    <textarea  name="special_note_en" placeholder="Special notes" class="form-control"></textarea>
                  </div>
                  @if ($errors->has('special_note_en'))
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('special_note_en') }}</strong>
                  </span>
                  @endif
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="arabic_label" style="float:right;">ملاحظات خاصة</label>
                    <textarea  name="special_note_ar" placeholder="ملاحظات خاصة" class="form-control rtl"></textarea>
                  </div>
                  @if ($errors->has('special_note_ar'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('special_note_ar') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label >Vehicle type</label>
                    <select  class="js-example-basic-multiple form-control" multiple="multiple" name="vehicle_type[]" id="status" required  >
                        @if($vehilce_type)
                          @foreach($vehilce_type as $vehilce_type_ids)
                            <option value="{{$vehilce_type_ids['id']}}">{{$vehilce_type_ids['name_en']}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div> 
                <div class="col-md-6 serviceTimeDuration showServiceTimeDurationBlock">
                  <div class="form-group">
                      <label >Service time duration (In Minutes)</label>
                      <input type="text" maxlength="5" class="form-control" name="time_duration" id="time_duration" placeholder=" Time"  >
                  </div>
                  @if ($errors->has('time_duration'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('time_duration') }}</strong>
                  </span>
                  @endif
                </div>
                {{-- <div class="col-md-2 serviceTimeDuration showServiceTimeDurationBlock">
                  <div class="form-group " style=" margin-top: 22px; margin-left: -30px;">
                    <select name="time_type" id="status" class="form-control">
                      <option value=""> -- Select Time Format -- </option>
                      <option value="mins">Minutes</option>
                      <option value="hours">Hours</option>
                    </select>
                  </div>
                  @if ($errors->has('time_type'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('time_type') }}</strong>
                  </span>
                  @endif
                </div> --}}
              </div>
             

              <div class="row" >
                <div class="col-md-6" >
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
                  <a  href="javascript:void(0);" class="add_button" title="Add New"><i  style=" margin-top:30px; font-size: 25px;" class="fa fa-plus-circle" aria-hidden="true"></i></a>
                </div>
              </div>

              <div class="field_wrapper"></div>

              <div class="card field_wrap"></div>


              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                <a href="javascriptvoid:0" class="btn btn-gradient-danger add_ons">Add Ons +</a> <br><br>


                </div>
         </div>
              <!-- <a href="javascriptvoid:0" style="margin-right: 20px;" class="btn btn-gradient-danger add_ons">Add Ons +</a> -->


              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >

              <a  style="margin-left: 20px;" href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>



          </form>
      </div>
    </div>
  </div>
</div>


@endsection
@section('scripts')
<script src="{{asset('js/select2.js')}}"></script>
  <script type="text/javascript">
  window.Parsley.addValidator("notequalto", {
    requirementType: "string",
    validateString: function(value, element) {
      return value !== '0' || $(element).val() !== '0';
    }
  });
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
  
  $('#category').change(function(){

     var category = $(this).val();
     var types = $(this).find(':selected').attr('data-id');

     if(types ==2){

        $('.serviceType').addClass('showServiceType');
        $('.serviceType').removeClass('hideServiceType');
        $('#amountBlock').addClass('showAmountBlock');
        $('#amount').removeAttr('required');
        $('.serviceTimeDuration').addClass('showServiceTimeDurationBlock');
        $('#time_duration').removeAttr('required');
        $('#status').removeAttr('required');


      }else{

        $('.serviceType').removeClass('showServiceType');
        $('.serviceType').addClass('hideServiceType');
        $('#amountBlock').removeClass('showAmountBlock');
        $('#amount').prop('required',true);
        $('.serviceTimeDuration').removeClass('showServiceTimeDurationBlock');
        $('#time_duration').prop('required',true);
        $('#status').prop('required',true);

      }

      $('#subCategoryBlock').addClass('hideSubCategoryType');

     $.get('{{route("get_subcategory_bycategory")}}/'+category,function(data){
       if(data.status=="success"){
          $('#subcategory').html(data.option);
          if(data.count > 0){
            $("#subcategory").prop('required',true);
            $('#subCategoryBlock').removeClass('hideSubCategoryType');
          }else{
            $('#subcategory').removeAttr('required');
            $('#subCategoryBlock').addClass('hideSubCategoryType');
          }
       } }).fail(function(){
         $('#subcategory').html('');
         $('#subCategoryBlock').addClass('hideSubCategoryType');
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
            var x = 1; //Initial field counter is 1
              var maxField = 10; //Input fields increment limitation
              var addButton = $('.add_button'); //Add button selector
              var wrapper = $('.field_wrapper'); //Input field wrapper
              var fieldHTML = '<div class="row" id='+x+'><div class="col-md-6"><div class="form-group"><label>Description</label><input class="form-control" type="text" name="description_en[]" value="" /></div></div><div class="col-md-5"><div class="form-group"><label class="pull-right">الوصف</label><input class="form-control rtl " type="text" name="description_ar[]" value=""/></div></div><a href="javascript:void(0);" class="remove_button"><i  style="margin-left:20px; margin-top: 30px;font-size: 25px !important;" class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'; //New input field html
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
                  //e.preventDefault();
                  e.preventDefault(); $(this).parent('div').remove(); x--;

                 // $(x).remove(); //Remove field html
                 // x--; //Decrement field counter
              });
          });
          </script>

<script type="text/javascript">
  $(document).ready(function(){
    var y = 1; //Initial field counter is 1
      var maximumField = 10; //Input fields increment limitation
      var addButto = $('.add_ons'); //Add button selector
      var wrap = $('.field_wrap'); //Input field wrapper
      var fieldHTM = '<div class="row plus_button" id='+y+'><div class="col-md-6"><div class="form-group"><label>Addons</label><input class="form-control" type="text" name="addons_name_en[]" value="" required/></div></div><div class="col-md-6"><div class="form-group"><label class="pull-right">إضافات</label><input class="form-control rtl " type="text" name="addons_name_ar[]" value="" required/></div></div><div class="col-md-6"><div class="form-group"><label>price</label><input class="form-control" min="0" type="text" name="addons_amount[]" value="" required/></div></div><a href="javascript:void(0);" class="remove_button"><span class="btn btn-gradient-danger sm" style="padding: 8px;margin-top: 24px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a><span class="btn btn-gradient-primary sm add_onssubs " style="padding: 8px; margin-top: 24px; margin-bottom: 29px; margin-left: 8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i></span></div>'; //New input field html
      $(document).on('click','.add_ons,.add_onssubs',function(){
          //Check maximum number of input fields
          if(y < maximumField){
              y++; //Increment field counter
              $(wrap).append(fieldHTM); //Add field html
          }
      });
      //Once remove button is clicked
      $(wrap).on('click', '.remove_button', function(e){
          e.preventDefault();
          e.preventDefault(); $(this).parent('div').remove(); y--;

      });
  });

  </script>

@endsection
