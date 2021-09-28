<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maak</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport"> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('landing_page/style.css') }}">
</head>
<style>
    .error {
        color : red !important;
    }
</style>

<body>

<div class="header-background-color">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <nav class="navbar navbar-expand-md">
                    <a class="navbar-brand" href="#">

                        <img alt="Logo" class="img-responsive logo" src="{{ asset('landing_page/images/logo.svg') }}"/></a>

                    <button class="navbar-toggler ml-auto custom-toggler" data-target="#collapsibleNavbar" data-toggle="collapse"
                            type="button">
                        <span class="navbar-toggler-icon">
                                 <span class="line"></span>
        <span class="line"></span>
        <span class="line" style="margin-bottom: 0;"></span>
                        </span>
                    </button>

                    <div class="collapse navbar-collapse  justify-content-end" id="collapsibleNavbar">
                        <ul class="navbar-nav ">
                            <li class="nav-item">
                                <a class="nav-link" href="#">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Testimonial</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Plans</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </nav>


            </div>
        </div>

</div>
<div class="section_one_carcare">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <p class="secone-title">
                We provide best car services in the world
            </p>
            <p class="secone-subtitle">
                Viverra aliquet eget sit amet tellus cras adipiscing enim.
            </p>
        </div>
        <div class="col-md-3"></div>
    </div>



<div class="section_one_image_carcare">

<!--    <div class="container ">-->
        <div class="row mutiple-images">
            <div class="col-md 1"></div>
            <div class="col-md-3">
                <div class="carecare_image">
                <img src="{{ asset('landing_page/images/app2.png') }}" img class="img-fluid">
                </div>
            </div>
            <div class="col-md-4">
                <div class="carecare_image">
                <img src="{{ asset('landing_page/images/app1@2x.png') }}" img class="img-fluid">
                </div>
            </div>
            <div class="col-md-3">
                <div class="carecare_image">
                <img src="{{ asset('landing_page/images/app3.png') }}" img class="img-fluid">
                </div>
            </div>
            <div class="col-md 1"></div>
<!--        </div>-->
    </div>
</div>


</div>

<div class="section_two_carcare">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
    <p class="sectwo-title">
        Interdum posuere lorem ipsum dolor sit amet. Nec u
    </p>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row sectwo-spacing ">
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">
                car Insurance
            </p>
            <p class="sectwo-one-title">
                Interdum posuere lorem ipsum dolor sit amet.
            </p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ltrices dui sapien eget mi proin sed libero enim. Magna eget
                est
            </p>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/Untitled-1.png') }}" img class="img-fluid" >
        </div>
    </div>
    <div class="row sectwo-spacing web-view">
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/Untitled-2.png') }}" img class="img-fluid">
        </div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">car Wash</p>
            <p class="sectwo-one-title">
                Interdum posuere lorem ipsum dolor sit amet.
            </p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ultrices dui sapien eget mi proin sed libero enim.
                Magna eget est lorem ipsum. Vulputate ut pharetra sit amet aliquam. Congue mauris rhoncus aenean vel
                elit
            </p>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="row sectwo-spacing mobile-view">
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">car Wash</p>
            <p class="sectwo-one-title">
                Interdum posuere lorem ipsum dolor sit amet.
            </p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ultrices dui sapien eget mi proin sed libero enim.
                Magna eget est lorem ipsum. Vulputate ut pharetra sit amet aliquam. Congue mauris rhoncus aenean vel
                elit
            </p>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/Untitled-2.png') }}" img class="img-fluid">
        </div>
    </div>
    <div class="row sectwo-spacing">
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">car service</p>
            <p class="sectwo-one-title">
                Interdum posuere lorem ipsum dolor sit amet.
            </p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ultrices dui sapien eget mi proin sed libero enim.
                Magna eget est lorem ipsum. Vulputate ut pharetra sit amet aliquam. Congue mauris rhoncus aenean vel
                elit
            </p>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/Untitled-3.png') }}" img class="img-fluid">
        </div>
    </div>
    <div class="row sectwo-spacing web-view">
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/ill7.png') }}" img class="img-fluid">
        </div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">Emergency Services</p>
            <p class="sectwo-one-title">Interdum posuere lorem ipsum dolor sit amet.</p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ultrices dui sapien eget mi proin sed libero enim.
                Magna eget est lorem ipsum. Vulputate ut pharetra sit amet aliquam. Congue mauris rhoncus aenean vel
                elit
            </p>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="row sectwo-spacing mobile-view">
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <p class="sectwo-one-maintitle">Emergency Services</p>
            <p class="sectwo-one-title">Interdum posuere lorem ipsum dolor sit amet.</p>
            <p class="sectwo-one-subtitle">
                Interdum posuere lorem ipsum dolor sit amet. Nec ultrices dui sapien eget mi proin sed libero enim.
                Magna eget est lorem ipsum. Vulputate ut pharetra sit amet aliquam. Congue mauris rhoncus aenean vel
                elit
            </p>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('landing_page/images/ill7.png') }}" img class="img-fluid">
        </div>
    </div>
</div>
<div class="section_three_carcare">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ asset('landing_page/images/logo-white.png') }} "></div>
            <div class="col-md-7">
                <p class="secthree-title">Get the app now!</p></div>
            <div class="col-md-2"><img src="{{ asset('landing_page/images/Icons%20App%20Store%20Google%20play.png') }}">
            </div>
        </div>
    </div>
</div>
<div class="section_four_carcare">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <img src="{{ asset('landing_page/images/ill8.png') }}" img class="img-fluid">
            </div>
            <div class="col-md-5">
                <p class="secfour-title">Be a vendor</p>
                <form id="vendor-form" action="">
                    @csrf
                    <div class="col">
                        <input class="form-control" placeholder="First name" type="text" name="name" id="name">
                    </div>
                    <div class="col">
                        <input class="form-control" placeholder="Phone Number" type="number" name="phone_number" id="phone_number">
                    </div>
                    <div class="col">
                        <input class="form-control" placeholder="Email" type="email" name="email" id="email">
                    </div>
                    <div class="col">
                        <textarea class="form-control" placeholder="Message" rows="5" name="description" id="description"></textarea>
                    </div>
                    <button class="btn btn-primary" id="btn-submit" type="submit">Send Message</button>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="footer_carcare">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <div class="footer-right-content">
                    <div class="content ">
                        <a class="loc_class" href="//goo.gl/maps/K5mto41jrxn" target="_blank" rel="noopener">
                            <img src="{{ asset('landing_page/images/location.png') }}" class="footer-icon">

                            <div class="footer-text">Salhyia - Fahad El Salem st. - Ossama Tower</div>
                        </a>
                    </div>
                    <div class="content">
                        <a class="loc_class" href="//api.whatsapp.com/send?phone=+96555330897&amp;text=&amp;source=&amp;data=" target="_blank" rel="noopener">
                            <img src="{{ asset('landing_page/images/call.png') }}" class="footer-icon">

                            <div class="footer-text">+965 553 30897</div>
                        </a>
                    </div>
                    <div class="content">
                        <a class="loc_class" href="mailto:info@maak.live">
                            <img src="{{ asset('landing_page/images/message.png') }}" class="footer-icon">

                            <div class="footer-text">info@maak.live

                            </div>
                        </a>
                    </div>


                </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="footer-left-content">
                    <div class="copyright">
                        <h2>About the company</h2>
                        <p>loreum ipsum dollar</p>
                    </div>
                    <div class="social-icons">
                        <ul>
                            <li><a href="https://m.facebook.com/maakcarservice/ " target="_blank" rel="noopener">
                                <img src="{{ asset('landing_page/images/facebook.png') }}"> </a></li>
                            <li><a href="https://mobile.twitter.com/LiveMaak " target="_blank" rel="noopener"> 
                                <img src="{{ asset('landing_page/images/twitter.png' ) }}"> </a></li>
                            <li><a href="https://instagram.com/maaklive?igshid=vrgdfy4v58lc ">
                                 <img src="{{ asset('landing_page/images/instagram.png' ) }}">
                            </a></li>
                            <li><a href="#" target="_blank" rel="noopener">
                                <img src="{{ asset('landing_page/images/snapchat.png' ) }}"></a></li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/tmpl.min.js')}}"></script>
<script src="{{ asset('js/fetch.js')}}"></script>
<script src="{{ asset('js/toaster.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function(){
      $("#vendor-form").validate({
            rules: {
                email: {
                    required             : true,
                    email                : true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    },
                },
                name: {
                    required              : true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    },
                },
                description: {
                    required      : true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    },
                }, 
                is_agree: {
                    required : true,
                }, 
                phone_number: {
                    required              : true,
                    number:true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    },
                },               
            },
            messages: {
                email          : {
                    required             : "Please enter your email",
                    email                : "Please enter valid email"
                },
                name   :  {
                    required              : "Please enter your name"
                },
                description   :  {
                    required              : "Please enter description"
                },
                phone_number   :  {
                    required              : "Please enter your phone number"
                },
                is_agree : {
                   required :  "Please accept terms & conditions"
                }
            },
            submitHandler: function(form,event) {
              
                $("#btn-submit").attr('disabled', 'disabled');
                $('#error-render').html('');
                $('#error-response').addClass('hide');
                var formSubmit = fetchRequest('/be-vendor/store');
                var formData = new FormData(form);
                formData.append('_token',document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
                formSubmit.setBody(formData);
                formSubmit.post().then(function (response) {
             
                    if (response.status === 200) {
                        response.json().then((errors) => {
                           $('#msg').html(errors.msg);
                        });
                        tata.success('Success', 'You request successfully submitted')
                        $('#vendor-form').trigger("reset");
                        $("#btn-submit").removeAttr('disabled', 'disabled');
                        setTimeout(function () {
                           $('#msg').html(''); //will redirect to your blog page (an ex: blog.html)
                        }, 2000); 
                        
   
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


</body>
</html>
