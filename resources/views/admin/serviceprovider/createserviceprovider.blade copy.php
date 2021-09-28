@extends('app')
@section('content')
<style>
.form-control countrycode, .form-control email, .form-control mobile_no ,.form-control
{
    background-color: transparent!important;
}
.emailerror{
  color: #B94A48 !important;
  font-size: 13px !important;
  margin: -14px 0px 10px 3px !important;
}
.mobileerror{
  color: #B94A48 !important;
  font-size: 13px !important;
  margin: -14px 0px 10px 3px !important;
}
.phoneerror{
  color: #B94A48 !important;
  font-size: 13px !important;
  margin: -14px 0px 10px 3px !important;
}
.hideerror{
  display: none;
}
.showerror{
  display: block;
}

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

<div class="page-header">
    <nav aria-label="breadcrumb"></nav>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                    <h3 class="page-title">Service Provider  </h3>
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                {{-- <h4 class="card-title">Create</h4> --}}
                <form class="forms-sample" id="formdata" action="{{route('sp.createServiceprovider')}}" data-validate-parsley =" " method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Name </label>
                                <input type="text" maxlength="50" class="form-control" name="full_name_en" placeholder="Name"
                                    required=" ">
                            </div>
                            @if ($errors->has('full_name_en'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('full_name_en') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:right;">اسم الشركة</label>
                                <input type="text" style="text-align:right;" maxlength="50" class="form-control full_name_ar"
                                    name="full_name_ar" placeholder="الاسم" required=" " >
                            </div>
                            @if ($errors->has('full_name_ar'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('full_name_ar') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Email</label>
                                <input type="email" style="text-align:left;" value="" data-id1="1" maxlength="50" class="form-control email emailaddress"
                                    name="email" placeholder="Email" required=" " data-parsley-type="email">
                            </div>
                            <span class="emailerror" role="alert"></span>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone No.</label>
                                <input type="tel" id="desc_en" maxlength="15" class="form-control phoneaddress" name="phone_number" value="" data-id1="3"  placeholder=" Phone No. "  data-parsley-type="number" required>
                            </div>
                            <span class="phoneerror" role="alert">

                            </span>
                            @if ($errors->has('phone_number'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Person incharge name</label>
                                <input type="text" id="desc_en" maxlength="150" class="form-control" name="incharge_name" placeholder="Incharge name " required="">
                            </div>
                            @if ($errors->has('incharge_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('incharge_name') }}</strong>
                            </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Mobile.no</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 39px;">+965</span>
                                    </div>
                                    <input type="text" style="text-align:left;" maxlength="12" class="form-control mobile_no mobileaddress" value="" data-id1="2" name="mobile_no" placeholder="Mobile.no" data-parsley-type="number" >
                                </div>
                            </div>
                            <span class="mobileerror" role="alert">

                            </span>
                            @if ($errors->has('mobile_no'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('mobile_no') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                          <div class="form-group">
                              <label>Country</label>
                              <select id="country_0" class="browser-default custom-select form-control country" name="country_0[]" required="">
                                      <option disabled selected>-----Select Country-----</option>
                                      @foreach($kuwait as $k)
                                          <option value="{{$k->id}}" > {{$k->name}} </option>
                                      @endforeach
                              </select>
                          </div>
                          @if ($errors->has('country'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('country') }}</strong>
                          </span>
                          @endif
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <label  class="arabic_label" style="float:left;">City</label>
                              <select id="city_0" class="browser-default custom-select form-control city" name="city_0[]" required="">
                                  <option disabled selected>-----Select City-----</option>
                                  {{-- @foreach($cities as $city)
                                              <option value="{{$city->id}}" > {{$city->name_en}} </option>
                                              @endforeach --}}
                              </select>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <label>Area</label>
                              <select id="area_0" class="browser-default custom-select form-control area  js-example-basic-multiple" name="area_0[]" multiple="multiple" required="">
                                      {{-- <option disabled selected>-----Select Area-----</option> --}}
                                      {{-- @foreach($kuwait as $k)
                                              <option value="{{$k->id}}" > {{$k->name}} </option>
                                              @endforeach --}}
                              </select>
                          </div>
                          @if ($errors->has('area'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('area') }}</strong>
                          </span>
                          @endif
                      </div>
                      <div class="col-md-1">
                        <button type="button" class="btn btn-lg btn-block btn-gradient-danger add_ons " id="addrow"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>

                      </div>
                      <div class="row">
                        <div class="order-list form-group">
                        </div>
                      </div>


                    </div>
{{-- This code is used to add multiple city and area --}}

<div class="field_wrapper"></div>

<div class="card field_wrap"></div>



{{-- End city and area --}}







                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="arabic_label">Address</label>
                                <textarea class="form-control" name="address" required></textarea>
                            </div>
                            @if ($errors->has('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Multiple category</label>
                            <select class="js-example-basic-multiple" multiple="multiple" style="width:100%" name="categories[]" id="my_multiselect" required="">
                                @foreach ($category as $key => $value)
                                <option value="{{$value['id']}}">{{ $value['name_en'] }}</option>
                                @endforeach

                            </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Multiple sub category</label>
                                <select class="js-example-basic-multiple" multiple="multiple" style="width:100%" name="subcategories[]" id="subcategory">
                                {{-- @foreach ($category as $key => $value)
                                    <option value="{{$value['id']}}">{{ $value['name_en'] }}</option>
                                @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bank name</label>
                                <input type="text" id="desc_en" maxlength="150" class="form-control" name="bank_name" placeholder="Bank's name " >
                            </div>
                            @if ($errors->has('bank_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bank_name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>IBAN</label>
                                <input type="text" id="desc_en" maxlength="150" class="form-control" name="iban" placeholder=" IBAN " data-parsley-type="alphanum" >
                            </div>
                            @if ($errors->has('iban'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('iban') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <h5>Commission Fees</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Fixed Price</label>
                                <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="fixed_price" id="fixed_price" placeholder="Fixed Price" data-parsley-type="number" required >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Percentage (In %)</label>
                                <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="maak_percentage" id="maak_percentage" placeholder="Percentage" data-parsley-type="number" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Monthly Fees</label>
                                <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="monthly_fee" id="monthly_fee" placeholder="Monthly Fees" data-parsley-type="number" >
                            </div>
                            @if ($errors->has('monthly_fee'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('monthly_fee') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label class="arabic_label" style="float:left;">Setup Fees</label>
                                    <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="setup_fee" id="setup_fee" placeholder="Setup Fees" data-parsley-type="number" >
                            </div>
                            @if ($errors->has('setup_fee'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('setup_fee') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Opening Hours</label>
                                <input type="number" style="text-align:left;" maxlength="40" class="form-control hours" name="opening_hours" placeholder="Opening Hours" required=" " data-parsley-type="digits">
                            </div>
                            @if ($errors->has('opening_hours'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('opening_hours') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Closing Hours</label>
                                <input type="number" style="text-align:left;" maxlength="40" class="form-control hours" name="closing_hours" placeholder="Closing Hours" required=" " data-parsley-type="digits">
                            </div>
                            @if ($errors->has('closing_hours'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('closing_hours') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div> --}}
                   
                    

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
                            <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/select2.js')}}"></script>
<script type="text/javascript">
    $("input[value=Submit]").click(function(event) {
        if ($("#formdata").parsley().validate()) {
            $(this).attr("disabled", true);
            $('#formdata').submit();
        }
    });
      $('body').on('change', '.country',$(this), function() {
        var countryid = $(this).val();
        var contdata = $(this).attr("id");
        $.get('{{route("get-getcities")}}/'+countryid,function(data){
          if(data.status=="success"){
            var strArray = contdata.split("_");
            $("#city_"+strArray[1]).html(data.option);
          }
      });
    });

    $('body').on('change', '.city',$(this), function() {
      var status = $(this).val();
      var citydata = $(this).attr("id");
      $.get('{{route("get-area-bystatus")}}/'+status,function(data){
        if(data.status=="success"){
          var strArray1 = citydata.split("_");
          $("#area_"+strArray1[1]).html(data.option);
        }
      }).fail(function(){
        $("#area_"+strArray1[1]).html('');
      });
});
 
    $(document).ready(function(){
        $('#my_multiselect').on('change',function() {
            let categoryID = $(this).val();
            $.ajax({
                url: "{{route('getSubcategorydata')}}",
                method: 'POST',
                data:{categoryID: categoryID},
                type:"json",
                success: function(response) {
                if(response.status==1){
                    $('#subcategory').html(response.option);
                }
                },
            });
        });
    });

</script>
<script>
  $(document).ready(function(){
    var y = 1; //Initial field counter is 1
      var maximumField = 10; //Input fields increment limitation
      var addButto = $('.add_ons'); //Add button selector
      var wrap = $('.field_wrap'); //Input field wrapper
      $(document).on('click','.add_ons,.add_onssubs',function(){
          //Check maximum number of input fields
          if(y < maximumField){
            var fieldHTM = '<div class="row plus_button msg_box_button" id='+y+'><div class="col-md-4"><div class="form-group"><label>Country</label><select class="browser-default custom-select form-control country" name="country_'+y+'[]" required="" id="country_'+y+'"><option disabled selected>-----Select Country-----</option>@foreach($kuwait as $k)<option value="{{$k->id}}" > {{$k->name}} </option>@endforeach</select></div></div><div class="col-md-4"><div class="form-group"><label  class="arabic_label" style="float:left;">City</label><select class="browser-default custom-select form-control city" id="city_'+y+'" name="city_'+y+'[]" required=""><option disabled selected>-----Select City-----</option></select></div></div><div class="col-md-3"><div class="form-group"><label>Area</label><select class="browser-default js-example-basic-multiple custom-select form-control area" multiple="multiple" name="area_'+y+'[]" required="" id="area_'+y+'"></select></div></div><a href="javascript:void(0);" class="remove_button"><span class="btn btn-gradient-danger sm" style="padding: 8px;margin-top: 24px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a><span class="btn btn-gradient-primary sm add_onssubs " style="padding: 8px; margin-top: 24px; margin-bottom:65px; margin-left: 8px;"><i class="fa fa-plus-circle" aria-hidden="true"></i></span></div>';
            $(wrap).append(fieldHTM); //Add field html
            y++; //Increment field counter
            $('.js-example-basic-multiple').select2();
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
$(document).ready(function(){
    var repeater = $('.repeater').repeater({
        isFirstItemUndeletable: true,
        errorMessage: true,
        errorMessageClass: 'error_message',
    });

  $(".emailaddress").keyup(function(){
      let email = $(this).val();
      let userid = '';
      let type = $(this).data('id1');
      $.ajax({
        url: "{{route('checkexists')}}",
        method: 'POST',
        data:{userid:userid,email:email,type:type},
        type:"json",
        success: function(response) {
           if(response.status == '1'){
             if(type ==1){
                $(".emailerror").addClass('showerror');
                $(".emailerror").text(response.message);
             }
           }else if(response.status == '0'){
             $(".emailerror").addClass('hideerror');
             $(".emailerror").text('');
           }
        }
    });
});

$(".mobileaddress").keyup(function(){
    let mobile_number = $(this).val();
    let userid = '';
    let type = $(this).data('id1');
    $.ajax({
      url: "{{route('checkexists')}}",
      method: 'POST',
      data:{userid:userid,mobile_number:mobile_number,type:type},
      type:"json",
      success: function(response) {
         if(response.status == '1'){
           if(type ==2){
              $(".mobileerror").addClass('showerror');
              $(".mobileerror").text(response.message);
           }
         }else if(response.status == '0'){
           $(".mobileerror").addClass('hideerror');
           $(".mobileerror").text('');
         }
      }
  });
});

$(".phoneaddress").keyup(function(){
    let phone_number = $(this).val();
    let userid = '';
    let type = $(this).data('id1');
    $.ajax({
      url: "{{route('checkexists')}}",
      method: 'POST',
      data:{userid:userid,phone_number:phone_number,type:type},
      type:"json",
      success: function(response) {
         if(response.status == '1'){
           if(type ==3){
              $(".phoneerror").addClass('showerror');
              $(".phoneerror").text(response.message);
           }
         }else if(response.status == '0'){
           $(".phoneerror").addClass('hideerror');
           $(".phoneerror").text('');
         }
      }
  });
});
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

@endsection
