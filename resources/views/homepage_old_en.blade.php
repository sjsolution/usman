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
      <style>
         .error {
         color: red !important;
         }
      </style>
   </head>
   <body>
      <div class="header-background-color">
         <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
               <nav class="navbar navbar-expand-md">
                  <a class="navbar-brand" href="#">
                  <img alt="Logo" class="img-responsive logo" src="{{ asset('landing_page/images/logo.svg') }}" /></a>
                  <button class="navbar-toggler ml-auto custom-toggler" data-target="#collapsibleNavbar"
                     data-toggle="collapse" type="button">
                  <span class="navbar-toggler-icon">
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line" style="margin-bottom: 0;"></span>
                  </span>
                  </button>
                  <div class="collapse navbar-collapse " id="collapsibleNavbar">
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

                     <li><a href="{{ url('home-ar') }}"> <span> عربى </span></a></li>

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
               <h1 class="secone-title">
                  MAAK
                  Full end to end at home services
               </h1>

               <!--<p class="secone-subtitle">
                  Viverra aliquet eget sit amet tellus cras adipiscing enim.
                  </p>-->
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
                  <div class="carecare_image middle_img">
                     <img src="{{ asset('landing_page/images/app1%402x.png') }}" img class="img-fluid">
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
      <!------------------------------------------ Section about us  ---------------------------------->
      <div class="Section_About bg p ">
         <div class="container ">
            <h1 style="width: 100%; color: rgb(0, 0, 0); text-align: center; margin-bottom: 50px;  "> About us </h1>
            <div class="row">
               <div class="col-lg-4 col-md-8 col-sm-12">
                  <h2 class="color w "> Mission </h2>
                  <p class="font_size ">
                     We will provide a Full end to
                     end mobile (at home) service
                     platform for both service
                     provider and end user in a
                     very convenient, effectiveness
                     & highest quality standard
                     that match local customer’s
                     expectations considering the
                     variety and areas coverage. 
                  </p>
                  <img src="{{ asset('landing_page/images/1.jpg')}}" alt="1">
               </div>
               <div class="col-lg-4 col-md-8 col-sm-12">
                  <h2 class="color w "> Vision </h2>
                  <p class="font_size ">
                     MAAK platform to be the
                     basic need for any service
                     provider and end users as a
                     marketplace for all mobile (at
                     home )services in the region. 
                  </p>
                  <img src="{{ asset('landing_page/images/2.jpg') }}" alt="2">
               </div>
               <div class="col-lg-4 col-md-8 col-sm-12">
                  <h2 class="color w "> Product Description </h2>
                  <p class="font_size ">
                     We will provide a Full end to
                     end mobile (at home) service
                     platform for both service
                     provider and end user in a
                     very convenient, effectiveness
                     & highest quality standard
                     that match local customer’s
                     expectations considering the
                     variety and areas coverage. 
                  </p>
                  <img src="{{ asset('landing_page/images/3.jpg') }}" alt="3">
               </div>
            </div>
         </div>
      </div>
      <!------------------------------------------ Section Featuers  ---------------------------------->
      <div class="Section_About bg p2 ">
         <div class="container ">
            <h1 style="width: 100%; color: rgb(0, 0, 0); text-align: center; margin-bottom: 50px;  "> Features </h1>
            <div class="row">

               <div class="col-lg-6 col-md-6 col-sm-12">
                  <h2 class="color w "> E-Commerce Business Overview </h2>
                  <p class="font_size ">
                     <img src="{{ asset('landing_page/images/maak.png') }}" alt="1">
               </div>


               <div class="col-lg-6 col-md-6 col-sm-12">
                  <h2 class="color w "> At Home Business Concept </h2>
                  <img src="{{ asset('landing_page/images/5.jpg') }}" alt="1">
               </div>


            </div>
         </div>
      </div>
      <!------------------------------------------------------------------------------------------------>
      <div class="section_two_carcare">
         <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
               <p class="sectwo-title">
                  The Services avaibale in our app
               </p>
            </div>
            <div class="col-md-2"></div>
         </div>
         <div class="row sectwo-spacing ">
            <div class="col-md-1"></div>
            <div class="col-md-5">
               <p class="sectwo-one-title color2">
                  car Insurance
               </p>
               <p class=" sectwo-one-maintitle color3">
                  Service. Savings. Satisfaction.
               </p>
               <p class="sectwo-one-subtitle">
                  Take advantage of our superior service , discounts designed for those who serve and do it from your home in 10 min 
               </p>
            <!--    <br><br>
               <p class="sectwo-one-title color2">
                  تامين السيارة
               </p>
               <p class=" sectwo-one-maintitle color3">
                  خدمات , توفير , رضاك
               </p>
               <p class="sectwo-one-subtitle">
                  استفد من خدماتنا الفائقة المصممة لك خصيصا , في منزلك في 10 دقائق 
               </p> -->

               
            </div>
            <div class="col-md-6 pdr">
               <img src="{{ asset('landing_page/images/Untitled-1.png') }}" img class="img-fluid">
            </div>
         </div>
      </div>
      <div class="row sectwo-spacing web-view">
         <div class="col-md-6 pdl">
            <img src="{{ asset('landing_page/images/Untitled-2.png') }}" img class="img-fluid">
         </div>
         <div class="col-md-5">
            <p class="sectwo-one-title color2">car Wash</p>
            <p class="sectwo-one-maintitle color3">
               Premium Car Wash Home Service
            </p>
            <p class="sectwo-one-subtitle">
               tack advantage of our app in washing your car in 20 min 
            </p>
            <!-- <br><br>
            <p class="sectwo-one-title color2">غسيل السيارة </p>
            <p class="sectwo-one-maintitle color3">
               خدمة غسيل سيارات في المنزل 
            </p>
            <p class="sectwo-one-subtitle">
               استفد من خدماتنا في غسيل سيارتك في 20 دقيقة فقط 
            </p> -->
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
            <img src="{{ asset('landing_page/images/Untitled-2.png')}}" img class="img-fluid">
         </div>
      </div>
      <div class="row sectwo-spacing">
         <div class="col-md-1"></div>
         <div class="col-md-5">
            <p class="sectwo-one-title color2">car service</p>
            <p class="sectwo-one-maintitle color3">
               Best Car services in kuwait 
            </p>
            <p class="sectwo-one-subtitle">
               We are an innovative mobile auto service that is the ideal way
               to save your time on all of your maintenance needs  in your home
            </p>
           <!--  <br><br>
            <p class="sectwo-one-title color2">صيانة السيارة</p>
            <p class="sectwo-one-maintitle color3">
               افضل خدمة صيانة سيارات في الكويت  
            </p>
            <p class="sectwo-one-subtitle">
               افضل خدمة صيانة سيارات متنقلة لتوفير وقتك في جميع مايتعلق بصيانة السيارة الخاصة بك في منزلك 
            </p> -->
         </div>
         <div class="col-md-6 pdr">
            <img src="{{ asset('landing_page/images/Untitled-3.png') }}" img class="img-fluid">
         </div>
      </div>
      <div class="row sectwo-spacing web-view">
         <div class="col-md-6 pdl">
            <img src="{{ asset('landing_page/images/ill7.png') }}" img class="img-fluid">
         </div>
         <div class="col-md-5">
            <p class="sectwo-one-title color2">Emergency Services</p>
            <p class="sectwo-one-maintitle color3"> 24/7 hour for any Road Emergency </p>
            <p class="sectwo-one-subtitle">
               Worried if your car broke down in the middle of the road? Our help is on the way to help you
            </p>
           <!--  <br><br>
            <p class="sectwo-one-title color2">طوارى على الطريق</p>
            <p class="sectwo-one-maintitle color3"> نعمل 24 ساعة على مدار في الاسبوع </p>
            <p class="sectwo-one-subtitle">
               Worried if your car broke down in the middle of the road? Our help is on the way to help you
               هل تشعر بقلق بانه تعطلت سيارتك في منتصف الطريق ؟ لا تقلق خدمتنا قادمة اليك لمساعدتك 
            </p> -->
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
                  <img src="{{ asset('landing_page/images/logo-white.png') }}">
               </div>
               <div class="col-md-7">
                  <p class="secthree-title">Get the app now!</p>
               </div>
               <div class="col-md-2 app_img"><img src="{{ asset('landing_page/images/Icons%20App%20Store%20Google%20play.png')}}">
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
                  <form id="vendor-form" action="#">
                     @csrf
                     <div class="col">
                        <input class="form-control" placeholder="First name" type="text" name="name" id="name">
                     </div>
                     <div class="col">
                        <input class="form-control" placeholder="Phone Number" type="number" name="phone_number"
                           id="phone_number">
                     </div>
                     <div class="col">
                        <input class="form-control" placeholder="Email" type="email" name="email" id="email">
                     </div>
                     <div class="col">
                        <textarea class="form-control" placeholder="Message" rows="5" name="description"
                           id="description"></textarea>
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
                        <a class="loc_class" href="http://goo.gl/maps/K5mto41jrxn" target="_blank" rel="noopener">
                           <img src="landing_page/images/location.png" class="footer-icon location">
                           <div class="footer-text">Kuwait city, Qibla, Fahd al salem street, Saadoun Al-Jassem building, Floor 2, Office 8</div>
                        </a>
                     </div>
                     <div class="content">
                        <a class="loc_class"
                           href="http://api.whatsapp.com/send?phone=+96555330897&amp;text=&amp;source=&amp;data="
                           target="_blank" rel="noopener">
                           <img src="landing_page/images/call.png" class="footer-icon">
                           <div class="footer-text">+965 22233313</div>
                        </a>
                     </div>
                     <div class="content">
                        <a class="loc_class" href="mailto:info@maak.live">
                           <img src="landing_page/images/message.png" class="footer-icon">
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
                        <h2>Connect with us</h2>
                        <p>Find us on social media</p>
                     </div>
                     <div class="social-icons">
                        <ul>
                           <li><a href="https://m.facebook.com/maakcarservice/" target="_blank" rel="noopener">
                              <img src="landing_page/images/facebook.png"> </a>
                           </li>
                           <li><a href="https://mobile.twitter.com/LiveMaak" target="_blank" rel="noopener">
                              <img src="landing_page/images/twitter.png"> </a>
                           </li>
                           <li><a href="https://instagram.com/maaklive?igshid=vrgdfy4v58lc">
                              <img src="landing_page/images/instagram.png">
                              </a>
                           </li>
                           <li><a href="#" target="_blank" rel="noopener">
                              <img src="landing_page/images/snapchat.png"></a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
      <script src="{{ asset('js/tmpl.min.js') }}"></script>
      <script src="{{ asset('js/fetch.js') }}"></script>
      <script src="{{ asset('js/toaster.js') }}"></script>
      <script type="text/javascript">
         $(document).ready(function () {
             $("#vendor-form").validate({
                 rules: {
                     email: {
                         required: true,
                         email: true,
                         normalizer: function (value) {
                             return $.trim(value);
                         },
                     },
                     name: {
                         required: true,
                         normalizer: function (value) {
                             return $.trim(value);
                         },
                     },
                     description: {
                         required: true,
                         normalizer: function (value) {
                             return $.trim(value);
                         },
                     },
                     is_agree: {
                         required: true,
                     },
                     phone_number: {
                         required: true,
                         number: true,
                         normalizer: function (value) {
                             return $.trim(value);
                         },
                     },
                 },
                 messages: {
                     email: {
                         required: "Please enter your email",
                         email: "Please enter valid email"
                     },
                     name: {
                         required: "Please enter your name"
                     },
                     description: {
                         required: "Please enter description"
                     },
                     phone_number: {
                         required: "Please enter your phone number"
                     },
                     is_agree: {
                         required: "Please accept terms & conditions"
                     }
                 },
                 submitHandler: function (form, event) {
         
                     $("#btn-submit").attr('disabled', 'disabled');
                     $('#error-render').html('');
                     $('#error-response').addClass('hide');
                     var formSubmit = fetchRequest('/be-vendor/store');
                     var formData = new FormData(form);
                     formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
                     formSubmit.setBody(formData);
                     formSubmit.post().then(function (response) {
         
                         if (response.status === 200) {
                             response.json().then((errors) = > {
                                 $('#msg').html(errors.msg);
                             });
                             tata.success('Success', 'You request successfully submitted')
                             $('#vendor-form').trigger("reset");
                             $("#btn-submit").removeAttr('disabled', 'disabled');
                             setTimeout(function () {
                                 $('#msg').html(''); //will redirect to your blog page (an ex: blog.html)
                             }, 2000);
                         } else if (response.status === 422) {
                             response.json().then((errors) = > {
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