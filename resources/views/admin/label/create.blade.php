@extends('app')
@section('content')
<style>
    input.parsley-success, select.parsley-success, textarea.parsley-success
    {
      background-color: transparent!important;
    }

    .error { 
      color: red;
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

          <h3 class="page-title">Add label </h3>
          
            {{-- <h4 class="card-title">Create</h4> --}}
          <form class="forms-sample" method="post" id="label-form" autocomplete="off">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label >Name</label>
                          <input type="text" maxlength="254" class="form-control" name="label_name_en" value="" placeholder="Name" required="">
                      </div>
                  
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="arabic_label pull-right">الاسم</label>
                          <input type="text" maxlength="254" class="form-control rtl" name="label_name_ar" value=" " placeholder="اسم" required>
                      </div>
                     
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Language Key</label>
                      <input type="text" name="label_key" class="form-control label_key"  value= "" required="">       
                  </div>
                     
                </div>
                <div> 
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Label Type</label>
                      <select class="form-control" name="type" id="type" required>
                         <option value="">--Select label type</option>
                         <option value="0">User</option>
                         <option value="1">Technician</option>
                      </select>
                  </div>
                   
                </div>
            </div>
            <br>
            <br>
            <input class="btn btn-primary" type="submit" value="Submit" id="btn-submit">
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
     $(document).ready(function(){    
        var ajax_req='';
        custom_rules();
        function custom_rules() {          
            /* function to unique bussiness name*/
            $.validator.addMethod("key_unique", function(value, element) {
                if (ajax_req) {
                    ajax_req.abort();
                }
                var flag = false;
                ajax_req = $.ajax({
                    url: '/admin/settings/label/unique/key/name',
                    data: {
                        "value": value,
                        "_token": $('input[name=_token]').val()
                    },
                    type: 'post',
                    async: false,
                    success: function(response) {
                        if (!response.trim()) {
                            flag = true;
                        }
                    },
                    complete: function(response) {}
                });
                return flag;
            }, "this key is already used.");

            /** Rule to check valdate email of people. */
            $.validator.addMethod("validate_key_pattern", function (value, element) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                var return_valu =  re.test(String(value).toLowerCase());
                if (return_valu) {
                    flag = true;
                } else {
                    flag = false;
                }
                return flag;
            }, "Please Enter Valid key string");
            
        } 
    
    
        $("#label-form").validate({
            rules: {
              label_key: {
                    required: true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    },
                    key_unique : true
                },
                label_name_en: {
                    required: true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    }
                },
                label_name_ar: {
                    required: true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    }
                }
            },
            messages: {
              label_name_en: "Please enter label name in english",
              label_name_ar: "Please enter lable name in arabic",
                key     : {
                    required   : "please enter key",
                    key_unique : "this key is already used."

                }
            },
            submitHandler: function(event) {
                $("#btn-submit").attr('disabled', 'disabled');
              
                var formSubmit = fetchRequest('/admin/settings/label/store');
                var formData = new FormData();
                formData.append("_token", document.getElementById("csrf-token").value);
                formData.append("label_en", document.getElementById("label_en").value);
                formData.append("key", document.getElementById("key").value);
                formData.append("label_ar", document.getElementById("label_ar").value);
                formSubmit.setBody(formData);
    
                formSubmit.post().then(function (response) {
                    if (response.status === 200) {
                        showInfoToast('Success','Label successfully added');
                        $('#label-form').trigger("reset");
                        $("#btn-submit").removeAttr('disabled', 'disabled');

   
                    }else if(response.status === 422){
                        response.json().then((errors) => {
                            console.log(errors.errors.label_en);
                            showDangerToast('Error',errors.errors.label_en);
                            $("#btn-submit").removeAttr('disabled', 'disabled');
                        });
                    }
                });
            }
        });

    });
  </script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
  
@endsection
