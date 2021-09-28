
@extends('appsp')

@section('content')
<style>
.removeaddons{display: none;}
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
      <h3 class="page-title"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Edit Service  </h3>

        {{-- <h4 class="card-title">Create</h4> --}}
        <form class="forms-sample"  id="formdata" action="{{route('sp.serviceupdate',$details->id)}}" method="POST" enctype="multipart/form-data" autocomplete="off">
          @csrf

           <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="status">Category</label>
                <select name="category" id="category" class="form-control" required>
                  @if($categories)
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}" @if($category['id'] ==$details->category_id){{ 'selected' }} @endif>{{ $category['name_en'] }}</option>
                    @endforeach
                  @endif
                  {{-- @if($details->category_id)
                    <option  selected value>  </option>
                  @endif --}}
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
                    <label >Title</label>
                    <input type="text" maxlength="50" class="form-control" name="title_en" placeholder="Title" value="{{$details->name_en}}" required>
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
                    <input type="text" style="text-align:right;" maxlength="50" class="form-control arabic_name" name="title_ar" placeholder="العنوان" value="{{$details->name_ar}}" required>
                </div>
                @if ($errors->has('title_ar'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name_ar') }}</strong>
                </span>
                @endif
            </div>
          </div>

         @if($details->type ==2)
          <span class="serviceType  {{ ($details->type !=2) ? 'hideServiceType' : '' }}">
            <div class="row ">
              <div class="col-md-6">
                <div class="form-group ">
                  <label>Service Type</label>
                  <select class="form-control" name="serviceType">
                      <option value='1' @if($details->service_type ==1){{ 'selected' }}@endif>Full Party</option>
                      <option value='2' @if($details->service_type ==2){{ 'selected' }}@endif>Third Party</option>
                      {{-- <option value='3' @if($details->service_type ==3){{ 'selected' }}@endif>Both</option> --}}
                  </select>
                </div>
              </div>
              <div class="col-md-6   ">
                <div class="form-group">
                  <label>Premium (In %)</label>
                <input type="text" name="premium_percentage" id="premium_percentage"  class="form-control" value="{{ $details->insurance_percentage }}" min="0" max="100">
                </div>
              </div>

            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Fixed Price (In KWD)</label>
                  <input type="text" min="0" name="fixed_price" id="fixed_price"  class="form-control" value="{{ $details->fixed_price }}"  data-parsley-notequalto="#premium_percentage" data-parsley-notequalto-message="Premium %age & fixed price must not be zero at same time!">
                </div>
              </div>
            </div>
          </span>
          @endif

          <div class="row">
            <div class="col-md-6 hideSubCategoryType" id="subCategoryBlock">
              <div class="form-group">
                <label for="status">Sub-category</label>
                <select name="sub_category" id="subcategory" class="form-control" data-parsley-required="false">
                <option disabled selected value> -- Select an option -- </option>
                </select>
              </div>
              @if ($errors->has('sub_category'))
              <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('type') }}</strong>
              </span>
              @endif
            </div>
            <div class="col-md-6 {{ ($details->type ==2) ? 'showAmountBlock' : '' }}" id="amountBlock">
              <div class="form-group">
                  <label >Amount</label>
                  <input type="text" maxlength="50" class="form-control" name="amount" placeholder=" Amount" id="amount" value="{{$details->amount}}" min="0" step="100" 
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
                <textarea  name="special_note_en" placeholder="Special notes" class="form-control">{{ $details->special_note_en }}</textarea>
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
                <textarea  name="special_note_ar" placeholder="ملاحظات خاصة" class="form-control rtl">{{ $details->special_note_ar }}</textarea>
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
                <select class="js-example-basic-multiple form-control" multiple="multiple" name="vehicle_type[]" id="status" required  >
                  @if($vehicle_type)
                    @foreach($vehicle_type as $category)
                        <option value="{{ $category->id  }}" {{
                              (in_array($category->id,$ucb)) ?  'selected' : '' }}
                        >{{ $category->name_en }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>   
            <div class="col-md-6 serviceTimeDuration {{ ($details->type ==2) ? 'showServiceTimeDurationBlock' : '' }}">
              <div class="form-group">
                  <label >Service time duration</label>
                  <input type="text" maxlength="5" class="form-control" name="time_duration"  id="time_duration" placeholder=" Time" value="{{$details->time_duration}}">
              </div>
              @if ($errors->has('time_duration'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('time_duration') }}</strong>
              </span>
              @endif
            </div>
            {{-- <div class="col-md-2 serviceTimeDuration  {{ ($details->type ==2) ? 'showServiceTimeDurationBlock' : '' }}">
              <div class="form-group " style=" margin-top: 22px; margin-left: -30px;">
                <select id="status" class="form-control" name="TimeFormat">
                  <option value=""> -- Select Time Format -- </option>
                  <option value="mins" @if($details->time_type =='mins'){{ 'selected' }}@endif>Minutes</option>
                  <option value="hours" @if($details->time_type =='hours'){{ 'selected' }}@endif>Hours</option>
                </select>
              </div>
              @if ($errors->has('time'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('time') }}</strong>
              </span>
              @endif
            </div> --}}
          </div>

          @if(!empty($details->servicedescription))
            @foreach($details->servicedescription as $keydes=>$desc)
              <div class="row" id={{ $keydes }}>
                <div class="col-md-6">
                  <div class="form-group ">
                      <label>Description</label>
                  <input type="text"  class="form-control" name="description_en[]" placeholder="Description " value="{{$desc->description_en}}" required>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group ">
                      <label class="pull-right">الوصف</label>
                  <input type="text" class="form-control rtl" name="description_ar[]" placeholder="Description " value="{{$desc->description_ar}}" required>
                  </div>
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="remove_button deleteservicedescription" data-id="{{ $desc->id }}" data-id1="{{ $desc->service_id }}" data-id2={{$keydes}}><i style="margin-top: 30px;font-size: 25px !important;" class="fa fa-minus-circle" aria-hidden="true"></i></a>
                  </div>
              </div>
            @endforeach
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="form-group ">
                  <label>Description</label>
                  <input type="text"  class="form-control" name="description_en[]" placeholder="Description " >
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
                  <input type="text"  class="form-control rtl" name="description_ar[]" placeholder="الوصف " >
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
            @if($details->serviceaddonsdata)
              @foreach ($details->serviceaddonsdata as $keyaddon=>$addonsvalue)
                <div class="row" id='{{ $addonsvalue->id }}'>
                  <div class="col-md-6">
                    <div class="form-group"><label>Addons</label>
                      <input class="form-control" type="text" name="addons_name_en[]" value="{{$addonsvalue->name_en}}" required/>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="pull-right">إضافات</label>
                          <input class="form-control rtl " type="text" name="addons_name_ar[]" value="{{$addonsvalue->name_ar}}" required/>
                        </div>
                      </div>
                      {{-- <div class="col-md-6"><div class="form-group"><label>Description</label>
                          <input class="form-control" type="text" name="addons_description_en[]" value="{{$addonsvalue->description_en}}" required />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="pull-right">وصف</label>
                        <input class="form-control rtl" type="text" name="addons_description_ar[]" value="{{$addonsvalue->description_ar}}" required/>
                      </div>
                    </div> --}}
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>price</label>
                        <input class="form-control" min="0" type="text" name="addons_amount[]" value="{{$addonsvalue->amount}}" required/>
                      </div>
                    </div>
                    {{-- <div class="col-md-3">
                      <div class="form-group">
                        <label>Time duration</label>
                        <input type="text" maxlength="5" class="form-control" name="addons_time_duration[]" placeholder="Time" value="{{$addonsvalue->time_duration}}" required >
                      </div>
                    </div> --}}
                      {{-- <div class="col-md-2">
                        <div class="form-group " style=" margin-top: 22px; margin-left: -30px;">
                        <select name="addons_time_type[]"  class="form-control" required>
                          <option value=""> -- Select Time Format -- </option>
                          <option value="mins" @if($addonsvalue->time_type =='mins'){{ 'selected' }}@endif>Minutes</option>
                          <option value="hours" @if($addonsvalue->time_type =='hours'){{ 'selected' }}@endif>Hours</option>
                        </select>
                      </div>
                    </div> --}}
                    <a href="javascript:void(0);" class="deleteAddonsdata" data-id="{{ $addonsvalue->id }}"><span class="btn btn-gradient-danger sm" style="padding: 8px;margin-top: 24px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                    <span class="btn btn-gradient-primary sm add_onssubs " style="padding: 8px; margin-top: 24px; margin-bottom: 29px; margin-left: 8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                  </div>
              @endforeach
            @endif

            <div class="card field_wrap"></div>
            <div class="col-md-12">
                <div id="myTable" class="order-list form-group"></div>
            </div>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                <a href="javascriptvoid:0" class="btn btn-gradient-danger add_ons">Add Ons +</a> <br><br>


                </div>
         </div>

            <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >
            <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
            <!-- <span  class="add_ons" style="margin-left: 54px;" class="btn btn-gradient-danger mr-2">Add Ons +</span> -->

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
     console.log($("#formdata").parsley().validate());
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
          $('#time_duration').removeAttr('required');
      
        }else{
          $('.serviceType').removeClass('showServiceType');
          $('.serviceType').addClass('hideServiceType');
          $('#amountBlock').removeClass('showAmountBlock');
          $('#amount').prop('required',true);
          $('.serviceTimeDuration').removeClass('showServiceTimeDurationBlock');
          $('#time_duration').prop('required',true);
          $('#status').prop('required',true);
          $('#time_duration').prop('required',true);
        }
        
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

        }
        // }).fail(function(){
        //  $('#subcategory').html('');
       });
  });

  var catId = "{{ $details->category_id }}";
  var subCat = "{{ $details->sub_category_id }}";
  if(catId){
    var category = catId;
     $.get('{{route("get_subcategory_bycategory")}}/'+category,function(data){
        if(data.status=="success"){
          $('#subcategory').html(data.option);
          if(data.count > 0){
            $("#subcategory").prop('required',true);
            $('#subCategoryBlock').removeClass('hideSubCategoryType');
            var e = document.getElementById("subcategory");
            var strUser = e.options[e.selectedIndex].value;
            for(x in e.options){ 
              if(e.options[x].value == subCat){
                document.getElementById('subcategory').getElementsByTagName('option')[x].selected = 'selected'

              }
            }
            

          }else{
            $('#subcategory').removeAttr('required');
            $('#subCategoryBlock').addClass('hideSubCategoryType');
          }

        }
        // }).fail(function(){
        //  $('#subcategory').html('');
       });
  }
});

 </script>

<script>
  $(document).on("click",".deleteservicedescription",function(){
    let descid = $(this).data('id');
    let service_id = $(this).data('id1');
    let removkeyname = $(this).data('id2');
    swal({
      title: 'Are you sure?',
      text: "You want to delete this!",
      //text: "You want to delete the Category",
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
          url: "{{route('deletedescription')}}",
          method: 'POST',
          data:{descid:descid,service_id:service_id},
          type:"json",
          success: function(response) {
             if(response.status == '1'){
               // $("#"+countryid).remove();
               // $("#city_"+removkeyname).removeAttr('required');
               // $("#country_"+removkeyname).removeAttr('required');
               // $("#area_"+removkeyname).removeAttr('required');
               //$("#"+countryid).addClass('removecountry');
               swal("Success!", response.message, "success");
               location.reload();
             }else if(response.status == '0'){
               swal("Error!", response.message, "error");
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

          <script type="text/javascript">
            $(document).ready(function(){
              var y = 1; //Initial field counter is 1
                var maximumField = 10; //Input fields increment limitation
                var addButto = $('.add_ons'); //Add button selector
                var wrap = $('.field_wrap'); //Input field wrapper
                var fieldHTM = '<div class="row" id='+y+'><div class="col-md-6"><div class="form-group"><label>Addons</label><input class="form-control" type="text" name="addons_name_en[]" value="" required/></div></div><div class="col-md-6"><div class="form-group"><label class="pull-right">إضافات</label><input class="form-control rtl " type="text" name="addons_name_ar[]" value="" required/></div></div><div class="col-md-6"><div class="form-group"><label>price</label><input class="form-control" min="0" type="text" name="addons_amount[]" value="" required/></div></div><a href="javascript:void(0);" class="remove_button"><span class="btn btn-gradient-danger sm" style="padding: 8px;margin-top: 24px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a><span class="btn btn-gradient-primary sm add_onssubs " style="padding: 8px; margin-top: 24px; margin-bottom: 29px; margin-left: 8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i></span></div>'; //New input field html
                $(document).on('click','.add_ons,.add_onssubs plus_button',function(){
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
            <script>

               $(document).on("click",".deleteAddonsdata",function(){
                  let addonsid = $(this).data('id');
                  swal({
                  title: 'Are you sure?',
                  text: "You want to delete this addons!",
                  //text: "You want to delete the Category",
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
                      url: "{{route('deleteaddons')}}",
                      method: 'POST',
                      data:{addonsid:addonsid},
                      type:"json",
                      success: function(response) {
                         if(response.status == '1'){
                           swal("Success!", response.message, "success");
                           $("#"+addonsid).addClass('removeaddons');
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
           </script>
@endsection
