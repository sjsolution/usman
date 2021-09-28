@extends('app')

@section('content')
<style>
 #ui-datepicker-div { 
     width: 25% !important;
 }

 .select2-container {
  min-width: 400px;
}

.error {
    color : red;
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #f77750;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
	background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: #eaeaeb;
	color: #272727;
}
.select2-container--default .select2-selection--multiple {
	margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
	border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
	border-color: #f77750;
	border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
	border-width: 2px;
}
.select2-container--open .select2-dropdown--below {
	
	border-radius: 6px;
	box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
	content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
	display: none;
}
.select-icon .placeholder {
	display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
	display: none !important;
	/* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
	display: none;
} 
</style>
    <br><br>
    <!-- Content Header (Page header) -->
    <div class="page-header">

      <nav aria-label="breadcrumb">
      </nav>
    </div>
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Coupon</h4>
            <br>
          <form class="forms-sample"  id="formdata" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Coupon Name</label>
                        <input type="text" class="form-control"  id="name_en"  name="name_en" placeholder="Coupon Name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="arabic_label pull-right">اسم القسيمة</label>
                        <input type="text" class="form-control arabic_name rtl" id="name_ar" name="name_ar" placeholder="اسم" required>
                    </div>
                    
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Coupon Code</label>
                        <input type="text" maxlength="50" class="form-control" id="coupon_code"  name="coupon_code" placeholder="Coupon Code" required>
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Coupon Image</label>
                        <input type="file" class="form-control" name="image"  id="image" required>
                        <i style="color:lightgrey">*recommended dimension 412 x 732</i>
                    </div>  
                </div>
            </div>

        
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Type</label>
                        <?php $typesArray = ['1'=>'Percentage','2'=>'Amount'];?>
                        <select name="coupon_type" id="coupon_type" class="form-control" required>
                            <option value="">Select Type</option>
                            @foreach ($typesArray as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" class="form-control" id="coupon_value" name="coupon_value" placeholder="Coupon Value" required>
                    </div>  
                </div>
            </div>
           

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Valid Till</label>
                        <input type = "text" class="form-control" id = "datepicker-12" name="coupon_valid_till">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="arabic_label">Customer Name</label>
                        <select id="customer_name_select2" name="customer_name[]" class="select2-hidden-accessible" multiple="" style="width:100%">
                            @foreach($users as $user)
                          
                              <option value="{{ $user->id}}">{{ $user->full_name_en}}</option>
                            @endforeach
                        </select>
                    </div>
            
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Min Cost Limit</label>
                        <input type="text" maxlength="50" class="form-control" name="min_cost_limit" id="min_cost_limit" placeholder="Max Cost Limit" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Max Cost Limit</label>
                        <input type="text" class="form-control" name="max_cost_limit" id="max_cost_limit" placeholder="Max Cost Limit" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >User Limit</label>
                        <input type="text" class="form-control" name="user_limit" id="user_limit" placeholder="User Limit" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Coupon Use Limit</label>
                        <input type="text" class="form-control" name="coupon_use_limit" id="coupon_use_limit" placeholder="Coupon Use Limit" required>
                    </div>
                  
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label >Service Category</label>
                    <select id="ser_cat_ids" name="ser_cat_ids[]" multiple="" style="width:100%"  required >
                        @foreach($categories as $category)
                            <option value="{{ $category->id}}" >{{ $category->name_en}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Service Providers</label>   
                        <select  multiple="" style="width:100%" name="service_provider_ids[]" id="service_provider_ids" required ></select>
                    </div>
                </div>
            </div>
       
       
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Services</label>   
                        <select  multiple="" style="width:100%" name="services[]" id="services"></select>
                    </div>
                </div>
              
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label >Description</label>
                        <textarea class="form-control" name="description_en" id="description_en"></textarea>
                    </div>
                  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label style="float:right" >وصف</label>
                        <textarea style="direction:rtl" class="form-control" name="description_ar" id="description_ar"></textarea>
                    </div>
                  
                </div>
            </div>
           
              <input type="submit" value="Submit" id="btn-submit" class="btn btn-gradient-danger mr-2" >
              <a href="javascript:history.go(-1)" class="btn btn-gradient-danger ">Back</a>
            </form>
          </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>

<script>  

$(function() {
    $(document).ready(function() { 
        
        $("#service_provider_ids").select2({
            placeholder: "Select service provider",
            allowClear: true,
            closeOnSelect : false
        }); 

        $("#services").select2({
            placeholder: "Select services",
            allowClear: true,
            closeOnSelect : false
        }); 

        $("#ser_cat_ids").select2({
            placeholder: "Select category",
            allowClear: true,
            closeOnSelect : false
        }); 
        $('#customer_name_select2').select2({
            placeholder: "Select customer",
            allowClear: true,
            closeOnSelect : false
        });

        // Fetch the preselected item, and add to the control
        var categorySelect = $('#ser_cat_ids');

        categorySelect.on('change', function(e) {

            let categoryId = $(this).val();
           
            $.ajax({
                type: 'GET',
                url: '/admin/category/service/providers/' + categoryId

            }).then(function (data) {
                
                $('#service_provider_ids').html(data.option);
                $("#service_provider_ids").select2(); 
               
            });

        });

        // Fetch the preselected item, and add to the control
        var serviceProviderSelect = $('#service_provider_ids');

        serviceProviderSelect.on('change', function(e) {

            let serviceProviderId = $(this).val();
           
            $.ajax({
                type: 'GET',
                url: '/admin/category/services/' + serviceProviderId

            }).then(function (data) {
                
                $('#services').html(data.option);
                $("#services").select2(); 
               
            });

        });

    
    });

   $( "#datepicker-12" ).datepicker();
//    $( "#datepicker-12" ).datepicker("setDate", "10w+1");
});


var ajax_req;

custom_rules();

function custom_rules() {
                
    /* function to unique bussiness name*/
    $.validator.addMethod("coupon_code_unique", function(value, element) {
        if (ajax_req) {
            ajax_req.abort();
        }
        var flag = false;
        ajax_req = $.ajax({
            url: '/admin/coupon/check',
            data: {
                "code": value,
                "_token": $('input[name=_token]').val()
            },
            type: 'post',
            async: false,
            success: function(response) {
                if (!response.trim()) {
                    flag = true;
                    $("#btn-submit").removeAttr('disabled', 'disabled');
                }
            },
            complete: function(response) {}
        });
        return flag;
    }, "Coupon code already used.");


    $.validator.addMethod('greaterThan', function (value, element, param) {
        
        var x = document.getElementById("coupon_type").value;

        if(x == 1){
            return true;
        }

        return this.optional(element) || parseInt(value) >= parseInt($(param).val());

    }, 'Invalid value');

    $.validator.addMethod('greaterThanCouponValue', function (value, element, param) {
       
        var x = document.getElementById("coupon_type").value;
        
        if(x == 1){
            return true;
        }


        return this.optional(element) || parseInt(value) >= parseInt($(param).val());

    }, 'Invalid value');

  


}

$('[name="max_cost_limit"]').on('change blur keyup', function() {
    $('[name="min_cost_limit"]').valid(); // <- trigger a validation test
    $('[name="coupon_value"]').valid();
});

$('[name="min_cost_limit"]').on('change blur keyup', function() {
   
    $('[name="coupon_value"]').valid(); // <- trigger a validation test
});


$("#formdata").validate({
    rules: {
        coupon_code: {
            required             : true,
            coupon_code_unique   : true,
            normalizer: function( value ) {
                return $.trim( value );
            },
        },
        name_en : {
            required             : true,
            normalizer: function( value ) {
                return $.trim( value );
            },
        },
        name_ar : {
            required             : true,
            normalizer: function( value ) {
                return $.trim( value );
            },
        },
        coupon_type : {
            required             : true
        },
        image : {
            required             : true
        },
        user_limit : {
            required             : true,
            // digits               : true,
            min                  : 1,
        },
        coupon_use_limit : {
            required             : true,
            // digits               : true,
            min                  : 1,
        },
        coupon_value : {
            required             : true,
            // digits               : true,
            min                  : 1,
        },
        'ser_cat_ids[]' : {
            required             : true,
        },
        'service_provider_ids[]' : {
            required             : true,
        },
        min_cost_limit: {
           required: true,
           number: true,
           greaterThanCouponValue: '#coupon_value'


        },
        max_cost_limit: {
            required: true,
            number: true,
            greaterThan: '#min_cost_limit'
        }
        
    },
    messages: {
        name_en : {
            required             : "Please enter coupon name in english",
        },
        name_ar : {
            required             : "Please enter coupon name in arabic",
        },
        image : {
            required             : "Please upload coupon image"
        },
        coupon_type : {
            required             : "Please select coupon type"
        },
        'ser_cat_ids[]' : {
            required               : "Please choose service category",
        },
        'service_provider_ids[]' : {
            required               : "Please choose service provider",
        },
        coupon_code          : {
            required               : "Please enter coupon code",
            coupon_code_unique     : "Coupon code already used."
        },
        coupon_value : {
            required               : "Please enter coupon value"
        },
        user_limit : {
            required               : "Please enter user limit",
            min                    : "Please enter value greater than 0"
        },
        coupon_use_limit : {
            required               : "Please enter coupon use limit",
            min                    : "Please enter value greater than 0"
        },
        max_cost_limit: {
            required               : "Please enter maximum cost limit",
            greaterThan            : 'Max. cost limit should must be geater then or equal to min. cost limit.'
        },
        min_cost_limit: {
            required               : "Please enter minimum cost limit",
            greaterThanCouponValue : 'Min. cost limit should must be geater then or equal to coupon value.'
        }
    },
    submitHandler: function(form,event) {
       
        $("#btn-submit").attr('disabled', 'disabled');
        var formSubmit = fetchRequest('/admin/coupon/create');
        var formData = new FormData(form);
        formSubmit.setBody(formData);
        formSubmit.post().then(function (response) {

            if (response.status === 200) {

                showInfoToast('Success','Coupon successfully created');
                $('#formdata').trigger("reset");
                $("#btn-submit").removeAttr('disabled', 'disabled');
                // setTimeout(function () {
                //     window.location.href = "{{ url('/admin/coupon/list')}}"; //will redirect to your blog page (an ex: blog.html)
                // }, 2000); 
               

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log(errors.errors);
                });
            }
        });
    }
});

   
</script>
<script src="{{ asset('select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/parsley.js')}}"></script>



@endsection
