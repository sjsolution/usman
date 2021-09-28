<!DOCTYPE html>
<html  dir="rtl" lang="ar">
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
        <title>معاك - كل إللي تبيه لسيارتك في تطبيق</title>
        <style>
            html {
                scroll-behavior: smooth;
            }

            .error {
               color :red;
            }
            .hidden-mobile{display: inline-block;}
             @media (max-width: 768px) {
                 .hidden-mobile{display: none !important;}
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
    <body dir="rtl">
        <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5XXHLH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        <header class="header arabic">
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
                        <ul class="navbar-nav mr-auto"> 
                            <li class="nav-item">
                                <a class="nav-link" href="#aboutus">معلومات عنا</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#userSection">المستخدم</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#services"> الخدمات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#vendorSection"> مقدم الخدمة</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contactus">اتصل بنا</a>
                            </li> 
                            <li class="nav-item">
                            <a class="button button-orange" href="{{ url('/') }}">English</a>
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
                                <h1 class="display-1">  أهلا بك في "معاك"</h1>
                                <h2 class="font-weight-light mb-5">كل إللي تبيه لسيارتك في تطبيق</h2>
                                <h2 class="font-weight-light mb-4">هل أنت</h2>
                                <div class="mb-5" style=""><a class="button button-white" href="#userSection" style="text-decoration:none; ">مستخدم</a></div>
                                <div class=""><a class="button button-white" href="#vendorSection" style="text-decoration:none; margin:50px">مقدم خدمة</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
                        <section class="services" style="background:#fb6016;" >
                <div class="container" style="padding:0 100px;">
                    <h1 class="heading-primary mb-5" style="width:100%; text-align: center; text-transform: uppercase; color:#fff;"><strong>حمل التطبيق</strong></h1>

                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <div class="services-box">
                                 
                                <a href="https://apps.apple.com/ae/app/maak-car-service-platform/id1494889873" target="_blank"> 
                                    <img src="{{ asset('frontend_assets/img/apple-icon.png') }}" alt="icon" />
                                 </a>
                                <a href="https://play.google.com/store/apps/details?id=com.maak"> 
                                     <img src="{{ asset('frontend_assets/img/playstore-icon.png') }}" alt="icon"  />
                                 </a>
                            </div>
                        </div>
                         
                         
                    </div>
                </div>
            </section>

            <section class="about arabic" id="aboutus">
                <div class="container">
                    <h1 class="heading-primary mb-5">معلومات عنا</h1>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="about-grid">
                                <h2 class="heading-secondary">مهمتنا</h2>
                                <p>توفير منصة كاملة لخدمات السيارات
                                    عبر الموبايل
                                </p>
                                <ul>
                                    <li>للمستخدم</li>
                                    <li>لمقدم الخدمات</li>
                                </ul>
                                <p> بمعايير مريحة، فعالة وعالية الجودة
                                    تتوافق مع توقعات العملاء المحليين مع المراعاة
                                    للتنوع الخدمات وتغطية المنطقة </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="about-grid">
                                <h2 class="heading-secondary">رؤيتنا</h2>
                                <p>
                                    أن نصبح رائدين في تمكين مقدم الخدمة او المستخدم من خلال تطبيقات الهاتف المحمول في المنطقة
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4" dir="rtl" style="direction:rtl;">
                            <div class="about-grid">
                                <h2 class="heading-secondary">وصف المنتج</h2>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub" dir="rtl" style="direction:rtl;">تطبيق المستخدم:</h3>
                                    <p>حجز وعرض خدمات السيارات والموردين والمنتجات العملاء.</p>
                                </div>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub">لوحة التحكم لمقدم الخدمة:</h3>
                                    <p>مراقبة جميع الخدمات المقدمة واتفاقية مستوى الخدمة والأداء العام للموظفين.</p>
                                </div>
                                <div class="mb-2">
                                    <h3 class="heading-secondary-sub">تطبيق الموظفين:</h3>
                                    <p>يعرض طلبات الحجز وقوائم المهام وموقع العميل.</p>
                                </div>
                                <div class="">
                                    <h3 class="heading-secondary-sub">إدارة تقارير معاك:</h3>
                                    <p>يُظهر حالة الطلبات الإجمالية، مستوى الخدمة، أداء البائعين، التصنيف، تقارير المعاملات، والتقارير المالية.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="user arabic" id="userSection">
                <div class="container-fluid">
                    <div class="row align-items-xl-center">
                        <div class="col-lg-4"  style="padding-right: 100px !important;">
                            <div class="user-box">
                                <h1 class="heading-primary mb-5" >المستخدم</h1>

                                <h2 class="heading-secondary">أهلا بك</h2>
                                <p>
                                    يمنحك تطبيق
                                    <span class="orange-text">MAAK</span>  
                                    الفرصة للحجز والعثور على جميع احتياجات خدمات سيارتك والاستفادة من الخصومات والعروض أينما كنت.
                                </p>
                            </div>
                            <div class="user-box">
                                <h2 class="heading-secondary">الخدمات</h2>
                                <p>تشمل خدمات
                                    <span class="orange-text">MAAK</span> 
                                    ما يلي:

                                </p>
                                <ul>
                                    <li>خدمات غسيل السيارات</li>
                                    <li>خدمات صيانة السيارات</li>
                                    <li>خدمات المساعدة على الطريق 24/7</li>
                                    <li>خدمات التأمين على السيارات</li>
                                    <li>حماية السيارة و تظليل النوافذ</li>
                                </ul>
                            </div>
                            <div class="user-box">
                                <h2 class="heading-secondary">فوائد</h2>
                                <p>سيستفيد المشتركون من العروض والخصومات على مدار السنة</p>
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

            <section class="services arabic" id="services">
                <div class="container-fluid" style="padding:0 100px;">
                    <h1 class="heading-primary mb-5" style="margin-right: 100px !important;">الخدمات </h1>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">أهلا بك</h1>
                                <p>مع تطبيق
                                    <span class="orange-text">MAAK</span>  
                                    سيستفيد المستخدم من جميع  الخدمات التي يحتاجها لصيانة، حماية، غسيل، التأمين، وتظليل السيارة.
                                </p>
                                <p>
                                    سيحصل مشتركو
                                    <span class="orange-text">MAAK</span> 
                                    على خصومات وعروض ترويجية
                                </p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">خدمات الغسيل</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/washing.png') }}" alt="icon" />
                                </figure>
                                <p>أفضل عروض غسيل السيارات في متناول يدك</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">التأمين</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/insurance.png') }}" alt="icon" />
                                </figure>
                                <p>قم بتأمين سيارتك بخطوات سريعة من أي مكان</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">المساعدة على الطريق 24/7</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/road-insurance.png') }}" alt="icon" />
                                </figure>
                                <p>المساعدة على الطريق 24/7 أينما كنت</p>
                            </div>
                        </div>
                            <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">خدمات السيارات</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/service.png') }}" alt="icon" />
                                </figure>
                                <p>أفضل خدمات صيانة السيارات
                                    عند بيتك</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">الحماية والتظليل</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/protection.png') }}" alt="icon" />
                                </figure>
                                <p>أفضل خدمات الحماية والتظليل عند بيتك</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">إعادة شحن المحفظة</h1>
                                <figure class="services-icon">
                                    <img src="{{ asset('frontend_assets/img/wallet.png') }}" alt="icon"  />
                                </figure>
                                <p>اشحن محفظتك واستفد من العروض المتنوعة</p>
                            </div>
                        </div>
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
          

         
                 
            </section>
            


            <section class="services arabic" id="vendorSection">
                <div class="container-fluid" style="padding:0 100px;">
                    <h1 class="heading-primary mb-3">مقدمو الخدمات </h1>
                    <h1 class="heading-secondary text-center mb-5">الميزات والفوائد</h1>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="services-box">
                                <h1 class="heading-secondary mb-5">أهلا بك</h1>

                                <p>
                                    يمنحك تطبيق
                                    <span class="orange-text">MAAK</span> 
                                    عرض لخدماتك للمزيد من الإيرادات، وتعزيز وتحسين فاعلية القوى العاملة، واستخدام الواجهة الخلفية للتطبيق للمدفوعات. 
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 border-right">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5">تحسين القوى العاملة</h1>
                                <ul>
                                    <li>المراقبة والتحكم</li>
                                    <li>جيو لوكيشن</li>
                                    <li>الجدولة</li>
                                    <li>سهولة الإعداد</li>
                                </ul>
                                <figure class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/workforce.png') }}" alt="icon" />
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-3 border-right">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5"> تعدد طرق الدفع</h1>
                                <ul>
                                    <li>تفويض أفضل لطرق الدفع</li>
                                    <li>تسويات دفع سريعة</li>
                                </ul>
                                <figure class="services-icon-box">
                                    <img src="{{ asset('frontend_assets/img/payment.png') }}" alt="icon" />
                                </figure>
                            </div>
                        </div>
                        <div class="col-lg-3 border-right">
                            <div class="services-box services-box-providers">
                                <h1 class="heading-secondary mb-5">عرض خدمات لسوق
                                    اوسع</h1>
                                <ul>
                                    <li>احصل على مزيد من العملاء للتعرف على خدماتك</li>
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

            <section class="contact arabic" id="contactus">
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
                                <h1 class="heading-primary mb-3">اتصل بنا</h1>
                            </div>
                            <div class="contact-box my-3">
                              <form id="vendor-form" action="#">
                                 @csrf
                                 <div class="col">
                                    <input class="form-control" placeholder="الاسم الاول" type="text" name="name" id="name">
                                 </div>
                                 <div class="col">
                                    <input class="form-control" placeholder="رقم الهاتف" type="number" name="phone_number"
                                       id="phone_number">
                                 </div>
                                 <div class="col">
                                    <input class="form-control" placeholder="البريد الإلكتروني" type="email" name="email" id="email">
                                 </div>
                                 <div class="col">
                                    <textarea class="form-control" placeholder="رسالة" rows="5" name="description"
                                       id="description"></textarea>
                                 </div>
                                 <button class="btn btn-primary" id="btn-submit" type="submit">أرسل رسالة</button>
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
                        <div class="col-lg-4">
                            <div class="footer-box">
                                <ul class="footer-desc">
                                    <li>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p style=" text-align: right; padding-top:10px; padding-right:20px;">
                                            الكويت، القبلة، شارع فهد السالم، مبنى سعدون الجاسم، الدور 2، مكتب 8
                                             
                                        </p>
                                    </li>
                                    <li >
                                        <i class="fas fa-phone-alt"></i>
                                        <p style=" direction:ltr; padding-top:10px; padding-right:20px; "><a href="tel:+96522233313" style="color:#fff; text-decoration:none; ">+965 22233313</a></p>
                                    </li>
                                    <li>
                                        <i class="far fa-envelope"></i>
                                        <p style=" padding-top:10px; padding-right:20px;"><a href="mailto:info@maak.com" style="color:#fff; text-decoration:none; margin-top:10px;">info@maak.live</a></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            <div class="footer-box" style="text-align:right;">
                                <h3>تواصل معنا</h3>
                                <h6>متواجدون عبر المنصات التالية</h6>
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
