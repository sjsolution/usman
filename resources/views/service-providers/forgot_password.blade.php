<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title> MAAK </title>
      <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="{{asset('css/stylelogin.css')}}">
      <link rel="icon" href="{{asset('images/DashLogo.png')}}" type="image/x-icon" />
      <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
      <style>
          .error {
              color :red;
          }
      </style>
   </head>
   <body >

      <div class="hedaer">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-4 logo-color text-center"  >
                  <img src="{{asset('images/header-logo.png')}}">
               </div>
               <div class="col-md-8 col-sm-8 col-xs-8">
               </div>
            </div>
         </div>
      </div>

      <div class="login-page">
         <img src="{{asset('images/form-logo.png')}}">
         <h3>Forgot your password?</h3>
        <form id="forgot-password" method="POST"  autocomplete="off">
            @csrf
            <div class="form-group">
                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Email.">
                <strong class="error" id="email-err"></strong>
            </div>
            <br>
            <input type="submit" name="" value="Submit">

        </form>
    
      </div>
      <script src="{{ asset('js/toaster.js')}}"></script>

      <script>

        var ajax_req;
        
        $(document).ready(function(){     
     
            custom_rules();
     
            function custom_rules() {
                /** Rule to check duplicate email of people. */
                $.validator.addMethod("email_exists", function(value, element) {
                     if (ajax_req) {
                         ajax_req.abort();
                     }
                     var flag = false;
                     ajax_req = $.ajax({
                         
                         url: '/serviceprovider/email/exists',
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
                             console.log('aaa',response.trim());
                         },
                         complete: function(response) {
                             console.log(response);
                         }
                     });
                     console.log(flag);
                     return flag;
                 }, "Email is already taken by someone.");
                /** Rule to check duplicate email of people. */
              //   $.validator.addMethod("email_exists", function(value, element) {
                   
              //       var flag = true;
              //       var ajaxRequest = fetchRequest('/user/email/exists');
              //       var formData = new FormData();
              //       formData.append("_token",  $('meta[name="csrf-token"]').attr('content'));
              //       formData.append("email", document.getElementById("email").value);
              //       ajaxRequest.setBody(formData);
              //       ajaxRequest.post().then(function (response) {
              //          if (response.status === 200) {
              //             response.json().then((result) => {
              //                if(result.status)
              //                   flag = result.status;
              //                   document.getElementById('email-err').innerText = result.message;
                                                           
              //             });
                          
              //          }
              //       });
                   
              //       return flag;
     
              //   }, "This email is not exists in our records.");
     
                /** Rule to check valdate email of people. */
                $.validator.addMethod("validate_email", function (value, element) {
                    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    var return_valu =  re.test(String(value).toLowerCase());
                    if (return_valu) {
                        flag = true;
                    } else {
                        flag = false;
                    }
                    return flag;
                }, "Please Enter Valid Email");
                
            }
        
            $("#forgot-password").validate({
                rules: {
                    email: {
                        required             : true,
                        validate_email       : true,
                        email                : true,
                        email_exists         : true,
                        normalizer: function( value ) {
                            return $.trim( value );
                        },
                    }
                    
                },
                messages: {
                    email          : {
                        required             : "Please enter email",
                        email                : "Please enter valid email",
                        validate_email       : "The given email format is not valid",
                        email_exists         : "This email is not exists in our records.."
                    }
                },
                submitHandler: function(form,event) {
                    $('#processing').css("display", "block");
                    $("#btn-submit").attr('disabled', 'disabled');
                    var formSubmit = fetchRequest('/serviceprovider/forgot-password');
                    var formData = new FormData(form);
                    formSubmit.setBody(formData);
                    formSubmit.post().then(function (response) {
     
                        if (response.status === 200) {
                            $('#processing').css("display", "none")
                            $('#user-form').trigger("reset");
                            $("#btn-submit").removeAttr('disabled', 'disabled');
                            response.json().then((result) => {
                                tata.success('Success', result.message)
                            });
                             
       
                        }else if(response.status === 422){
                            response.json().then((errors) => {
                                console.log(errors.errors);
                            });
                        }
                    });
                }
            });
     
        });
     
     </script>
     <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
     <script src="{{ asset('js/parsley.js') }}"></script>
     <script src="{{ asset('js/fetch.js')}}"></script>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
   </body>
</html>
