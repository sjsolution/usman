@extends('app')
@section('content')
<style>
.form-control countrycode, .form-control email, .form-control mobile_no ,.form-control
{
    background-color: transparent!important;
}
</style>
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
    <h3 class="page-title">Service Provider</h3>
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
                                <input type="email" style="text-align:left;" maxlength="50" class="form-control email"
                                    name="email" placeholder="Email" required=" " data-parsley-type="email">
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone No.</label>
                                <input type="tel" id="desc_en" maxlength="15" class="form-control" name="phone_number" placeholder=" Phone No. "  data-parsley-type="number" required>
                            </div>
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
                                <label>Incharge name</label>
                                <input type="text" id="desc_en" maxlength="150" class="form-control" name="incharge_name" placeholder="Incharge name " required="">
                            </div>
                            @if ($errors->has('incharge_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('incharge_name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <label class="arabic_label">Country Code</label>
                                    <select class="browser-default custom-select form-control"   name="countrycode" >
                                        <option selected>Select </option>
                                        @foreach($kuwait as $k)
                                        <option value="{{$k->phonecode}}" > +{{$k->phonecode}} </option>
                                        @endforeach
                                            </select>
                            </div>
                            @if ($errors->has('countrycode'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('countrycode') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Mobile.no</label>
                                <input type="text" style="text-align:left;" maxlength="12" class="form-control mobile_no"  name="mobile_no" placeholder="Mobile.no" data-parsley-type="number" >
                            </div>
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
                              <select class="browser-default custom-select form-control"   name="country" required="">
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
                              <select id="city" class="browser-default custom-select form-control"   name="city" required="">
                                  <option disabled selected>-----Select City-----</option>
                                  @foreach($cities as $city)
                                              <option value="{{$city->id}}" > {{$city->name_en}} </option>
                                              @endforeach
                              </select>
                          </div>
                          @if ($errors->has('city'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('city') }}</strong>
                          </span>
                          @endif
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <label>Area</label>
                              <select id="area" class="browser-default custom-select form-control"   name="area" required="">
                                      <option disabled selected>-----Select Area-----</option>
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
                        <button type="button" class="btn btn-lg btn-block btn-gradient-danger addrow" id="addrow">+</button>

                      </div>
                      <div class="col-md-12">
                        <div id="myTable" class="order-list form-group">
                        </div>
                      </div>


                    </div>






                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <select class="browser-default custom-select form-control"   name="country" required="">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="arabic_label" style="float:left;">City</label>
                                <select id="city" class="browser-default custom-select form-control"   name="city" required="">
                                    <option disabled selected>-----Select City-----</option>
                                    @foreach($cities as $city)
                                                <option value="{{$city->id}}" > {{$city->name_en}} </option>
                                                @endforeach
                                </select>
                            </div>
                            @if ($errors->has('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Area</label>
                                <select id="area" class="browser-default custom-select form-control"   name="area" required="">
                                        <option disabled selected>-----Select Area-----</option>
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
                        <div class="col-md-6">
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
                                <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="fixed_price" id="fixed_price" placeholder="Fixed Price" data-parsley-type="number" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="arabic_label" style="float:left;">Percentage (In %)</label>
                                <input type="text" style="text-align:left;" maxlength="40" class="form-control monthly_fee" name="maak_percentage" id="maak_percentage" placeholder="Percentage" data-parsley-type="number" >
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

                    <div class="row">
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
                    </div>

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

    $(document).ready(function(){
      $('#city').change(function(){
          var status = $(this).val();
          $.get('{{route("get-area-bystatus")}}/'+status,function(data){
            if(data.status=="success"){
              $('#area').html(data.option);
            }
          }).fail(function(){
            $('#area').html('');
          });
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
