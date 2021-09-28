<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M5XXHLH');</script>
<!-- End Google Tag Manager -->
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Font Awesome Css -->
        <link rel="stylesheet" href="{{ asset('frontend_assets/css/font-awesome/css/all.css') }}" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('frontend_assets/css/bootstrap.min.css') }}" />
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('frontend_assets/css/main.css') }}" />
        <title>MAAK - Everything you need for your car in an App</title>
         <style>
            html {
                scroll-behavior: smooth;
            }
            .error { 
               color : red;
            }
        </style>
        
        
        <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '349069163079716'); 
fbq('track', 'PageView');
</script>
<noscript>
<img height="1" width="1" 
src="https://www.facebook.com/tr?id=349069163079716&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3S4GGR6GKT"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-3S4GGR6GKT');
</script>



 


<!-- Hotjar Tracking Code for maak.live -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2303980,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

    </head>
    <body>
        
   <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5XXHLH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

        <header class="header">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <a class="navbar-brand" href="#">
                        <img src="{{ asset('frontend_assets/img/logo.png') }}" alt="logo" />
                    </a>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarNav"
                        aria-controls="navbarNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                        >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#aboutus">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#userSection">User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#services">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#vendorSection">Service Providers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact Us</a>
                            </li>
                             
                            <li class="nav-item">
                            <a class="button button-orange" href="{{ url('/home-ar') }}"> عربى </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="header-hero">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <figure class="header-mobile"><img src="{{ asset('frontend_assets/img/hero-main.png') }}" alt="phone" /></figure>
                        </div>
                        <div class="col-lg-6">
                            <div class="header-content">
                                <h1 class="display-1">Welcome to MAAK</h1>
                                <h2 class="font-weight-light mb-5">Everything you need for your car in an app</h2>
                                 
                                <div class="mb-5" style=""><a class="button button-white" href="#userSection" style="text-decoration:none; ">User</a></div>
                                <div class=""><a class="button button-white" href="#vendorSection" style="text-decoration:none; margin:50px">Service provider</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
              <section class="services" style="background:#fb6016;" >
                <div class="container" style="padding:0 100px;">
                    <h1 class="heading-primary mb-5" style="width:100%; text-align: center; text-transform: uppercase; color:#fff;"><strong>Download MAAK App</strong></h1>

                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <div class="services-box">
                                 
                                <a href="https://apps.apple.com/ae/app/maak-car-service-platform/id1494889873" target="_blank"> 
                                    <img src="{{ asset('frontend_assets/img/apple-icon.png') }}" alt="icon" />
                                 </a>
                                <a href="https://play.google.com/store/apps/details?id=com.maak" > 
                                     <img src="{{ asset('frontend_assets/img/playstore-icon.png') }}" alt="icon"  />
                                 </a>
                            </div>
                        </div>
                         
                         
                    </div>
                </div>
            </section>

            <section class="about" id="aboutus">
                <div class="container">
                    <h1 class="heading-primary mb-5">About Us</h1>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="about-grid">
                                <h2 class="heading-secondary">Mission</h2>
                                <p>To provide a Full end to end Mobile car services platform for:</p>
                                <ul>
                                    <li>End User</li>
                                    <li>Serices Provider</li>
                                </ul>
                                <p>
                                    In a very convenient effective and hight quality standards that matches the local
                                    customer's expectations with consideration to the variety and area coverage
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="about-grid">
                                <h2 class="heading-secondary">Vision</h2>
                                <p>
                                    To become the leader in enabling any service provider and end users through mobile
                                    applications in the region
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="about-grid">
                                <h2 class="heading-secondary">Product Description</h2>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub">Customer App:</h3>
                                    <p>Booking and showcasing car services, vendors and products to customers.</p>
                                </div>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub">Vendor Backend:</h3>
                                    <p>Control and monitor all the provided services, SLA and staff overall performance.</p>
                                </div>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub">Staff App:</h3>
                                    <p>Shows booking requests, task lists and customer location. </p>
                                </div>
                                <div class="">
                                    <h3 class="heading-secondary-sub">MAAK management admin:</h3>
                                    <p>
                                        Shows overall request status, SLA, vendors performance, rating and both transactional and financial reports.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="user"  id="userSection">
                <div class="container-fluid">
                    <div class="row align-items-xl-center">
                        <div class="col-lg-4" style="padding-left: 100px !important;">
                            <div class="user-box">
                                <h1 class="heading-primary mb-5">User</h1>

                                <h2 class="heading-secondary">Welcome</h2>
                                <p>
                                    <span class="orange-text">MAAK</span> gives you the opportunity to book, and find
                                    all your car services needs and benefit from discount and offers where ever you
                                    are.
                                </p>
                            </div>
                            <div class="user-box">
                                <h2 class="heading-secondary">Services</h2>
                                <p><span class="orange-text">MAAK</span> services include the following:</p>
                                <ul>
                                    <li>Car washing services</li>
                                    <li>Car Maintainance services</li>
                                    <li>24/7 Road assistance services</li>
                                    <li>Car Insurance services</li>
                                    <li>Car protection and window tinting</li>
                                </ul>
                            </div>
                            <div class="user-box">
                                <h2 class="heading-secondary">Services</h2>
                                <p>Subscribers will benefit from all year round offers and discounts</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="user-figure-wrapper">
                                <figure class="user-figure">
                                    <img src="{{ asset('frontend_assets/img/side-cover.png') }}" alt="cover" />
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="services" id="services">
                <div class="container-fluid" style="padding:0 100px;">
                    <h1 class="heading-primary mb-5">Services</h1>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Welcome</h1>
                                <p>
                                    with <span class="orange-text">MAAK</span> user will benefit from all services
                                    they need for their car maintainance, insurance washing protection and tinitling
                                </p>
                                <p>Maak subscribers will recieve discounts and promotions</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Washing services</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/washing.png') }}" alt="icon" />
                                </figure>
                                <p>Best car wash offers at the tips of your fingers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Insurance</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/insurance.png') }}" alt="icon" />
                                </figure>
                                <p>Insurance your car in quick steps from anywhere</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">24/7 Road assistance</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/road-insurance.png') }}" alt="icon" />
                                </figure>
                                <p>24/7 road assistance whereever you are</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="services-circle services-circle-sm">
                    <img src="{{ asset('frontend_assets/img/circle-sm.png') }}" alt="circle-graphic" />
                </div>
                <div class="services-circle services-circle-lg">
                    <img src="{{ asset('frontend_assets/img/circle-lg.png') }}" alt="circle-graphic" />
                </div>
             
                <div class="container-fluid" style="padding:0 100px;">
                    <!--<h1 class="heading-primary mb-5">Services</h1>-->

                    <div class="row">
                        <div class="col-lg-3 offset-lg-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Car services</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/service.png') }}" alt="icon" />
                                </figure>
                                <p>Best car wash offers at the tips of your fingers</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Protection & Tinting</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/protection.png') }}" alt="icon" />
                                </figure>
                                <p>Insurance your car in quick steps from anywhere</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Wallet recharge</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/wallet.png') }}" alt="icon" />
                                </figure>
                                <p>24/7 road assistance whereever you are</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
          

            <section class="services"  id="vendorSection">
                <div class="container-fluid" style="padding:0 100px;">
                    <h1 class="heading-primary mb-3">Services Providers</h1>
                    <h1 class="heading-secondary text-center mb-5">Features & Benefits</h1>

                    <div class="row">
                        <div class="col-lg-3 border-right">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">Welcome</h1>
                                <p>
                                    <span class="orange-text">MAAk</span> gives you the right exposure to generate
                                    more revenue to your business, nhance and optimize your workforce effectiveness,
                                    and use the app backend for your payments
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 border-right">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5">Workforce optimization</h1>
                                <ul>
                                    <li>Monitoring & Controlling</li>
                                    <li>Geolocating</li>
                                    <li>Scheduling</li>
                                    <li>Easy onboarding</li>
                                </ul>
                                <figure class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/workforce.png') }}" alt="icon" />
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-3 border-right">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5">Multiple payment methods</h1>
                                <ul>
                                    <li>Better authorization payment methods</li>
                                    <li>Fast payment settlements</li>
                                </ul>
                                <figure class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/payment.png') }}" alt="icon" />
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5">Wider market exposure</h1>
                                <ul>
                                    <li>Get more customers to know about your services</li>
                                </ul>
                                <figure class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/market.png') }}" alt="icon" />
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="services-circle services-circle--sm">
                    <img src="{{ asset('frontend_assets/img/circle-sm.png') }}" alt="circle-graphic" />
                </div>
                <div class="services-circle services-circle-md">
                    <img src="{{ asset('frontend_assets/img/circle-md.png') }}" alt="circle-graphic" />
                </div>
            </section>

            <section class="contact" id="contact">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="contact-box my-3">
                                <div class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/contact.png') }}" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="contact-box my-3">
                                <h1 class="heading-primary mb-3">Contact Us</h1>
                            </div> 
                            <div class="contact-box my-3">
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

                                    {{-- <div class="col">
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
                                    <div class="col">
                                        <button class="btn btn-primary" id="btn-submit" type="submit">Send Message</button>
                                    </div> --}}


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-3">
                            <div class="footer-box">
                                <ul class="footer-desc">
                                    <li>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p>
                                            Kuwait city, Qibla, Fahd al salem street, Saadoum Al-Jassem building, Floor 2,
                                            Office 8
                                        </p>
                                    </li>
                                    <li>
                                        <i class="fas fa-phone-alt"></i>
                                        <p><a href="tel:+96522233313" style="color:#fff; text-decoration:none; margin-top:10px;">+965 22233313</a></p>
                                    </li>
                                    <li>
                                        <i class="far fa-envelope"></i>
                                        <p><a href="mailto:info@maak.com" style="color:#fff; text-decoration:none; margin-top:10px;">info@maak.live</a></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-3">
                            <div class="footer-box">
                                <h3>Connect with us</h3>
                                <h6>Find us on social media</h6>
                                <ul class="footer-social">
                                    <li>
                                        <a href="https://www.facebook.com/Maakcars-326887002006480" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/MaakCars" class="twitter"><i class="fab fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/maak.car/" class="instagram"><i class="fab fa-instagram"></i></a>
                                    </li>
                                    <!--<li>
                                        <a href="#" class="snapchat"><i class="fab fa-snapchat-ghost"></i></a>
                                    </li>-->
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            </section>
        </main>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{ asset('frontend_assets/js/jquery.js') }}"></script>
        <script src="{{ asset('frontend_assets/js/popper.js') }}"></script>
        <script src="{{ asset('frontend_assets/js/bootstrap.min.js') }}"></script>

        <!-- Custom JS -->
        <script src="{{ asset('frontend_assets/js/main.js') }}"></script>
       

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
                             response.json().then((errors) => {
                                 $('#msg').html(errors.msg);
                             });
                             tata.success('Success', 'You request successfully submitted')
                             $('#vendor-form').trigger("reset");
                             $("#btn-submit").removeAttr('disabled', 'disabled');
                             setTimeout(function () {
                                 $('#msg').html(''); //will redirect to your blog page (an ex: blog.html)
                             }, 2000);
                         } else if (response.status === 422) {
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
       <script src="{{ asset('js/tmpl.min.js') }}"></script>
       <script src="{{ asset('js/fetch.js') }}"></script>
       <script src="{{ asset('js/toaster.js') }}"></script>
    </body>
</html>
<!-- 
<i class="fab fa-facebook-f"></i>
<i class="fab fa-google"></i>
<i class="fab fa-twitter"></i>
<i class="fab fa-linkedin-in"></i>
<i class="fab fa-youtube"></i>
<i class="fab fa-instagram"></i>
<i class="fab fa-whatsapp"></i> 
<i class="fas fa-check"></i>
<i class="far fa-check-circle"></i>
<i class="fas fa-chevron-down"></i>
<i class="far fa-envelope"></i>
<i class="fas fa-phone-alt"></i>
<i class="fas fa-map-marker-alt"></i>
-->
