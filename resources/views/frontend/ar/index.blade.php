@extends('frontend.layouts.master')

@section('title', 'شركة معاك لتصميم وتطوير مواقع الانترنت والتطبيقات الذكية')

@section('content')

	<!-- carousel background-color:#F16522;-->
	<section
		class=""
		style="background-image: linear-gradient(#f16522, #e96221); min-height: calc(100vh - 84px)"
	>
		<div class="container">
			<div class="row" style="padding-top: 8em">
				<div class="col-md-6" style="padding: 30px 50px">
					<h1 class="" style="color: #fff; font-size: 72px">تصميم مواقع الانترنت</h1>
					<p style="color: #fff">
						نقدم لك أفضل تصميمات المواقع المتميزة والجديدة بأفضل الأسعار التي تناسبك مع خبراء
						محترفين في مجال تصميم مواقع الإنترنت
					</p>
				</div>
				<div class="col-md-6"><img src="{{ asset('assets/img/main-icon.png') }}" style="max-width: 100%" /></div>
			</div>
		</div>
	</section>

	<section class="" style="background-color: #ffb12d; padding-top: 5em">
		<div class="container">
			<h1 style="color: #fff">تصميم المواقع الالكترونية</h1>
			<p style="color: #fff">
				نحن شركة رائدة في مجال حلول الأنظمية البرمجية يميزها إهتمامها بخدمة العملاء بعد تسليم
				مشاريعها بتقديم خدمة متابعة المشاريع وصيانتها الدورية حيث قامت بتخصيص فريق دعم محترف يتكون
				فريق مواقع نت من مجموعة من المختصين ذوي الكفاءات العالمية يسعون لتوفير أقصى درجات الإهتمام
				والسرعة لتقديم كامل الدعم لعملائنا.
			</p>
		</div>
	</section>

	<section class="" style="background-color: #ffb12d; padding-bottom: 5em">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-8" style="color: #fff">
							<h1>تصميم كامل</h1>
							<p>إنطلق الأن بتصميم جذاب لموقعك الإلكتروني حسب إحتياجات عملك.</p>
						</div>
						<div class="col-4"><img src="{{ asset('assets/img/about-1.png') }}" /></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-8" style="color: #fff">
							<h1>احصائيات موقعك</h1>
							<p>حقق أهدافك, لوحة كاملة لمعرفة عدد زوار موقعك . وأمكان الزيارات</p>
						</div>
						<div class="col-4"><img src="{{ asset('assets/img/about-2.png') }}" /></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="parallax">
		<div class="container">
			<div class="row">
				<div style="padding-top: 80px">
					<h1 style="color: #f16522">تصميم تطبيقات الجوال</h1>
					<p style="color: #f16522">تطبيقات بطابع عصري تعكس شركتكم.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="" style="background-color: #ffb12d; padding-bottom: 10em">
		<div class="container">
			<div class="row">
				<div class="col-md-12" style="padding-bottom: 50px">
					<div class="row">
						<div style="color: #fff">
							<h1>خصائص فريدة</h1>
							<p>
								نجعل موقع الويب الخاص بك فريدًا ونقوم بتمييز أعمالك لتوسيع نطاق منتجاتك وخدماتك
								على مستوى الأسواق العالمية.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-3" style="padding: 30px">
					<div
						class="row"
						style="
							background-color: #fff;
							border-radius: 25px;
							min-height: 300px;
							padding-top: 90px;
						"
					>
						<div class="col-6">
							<h1 style="color: #f16522">تصميم الموقع الالكتروني</h1>
						</div>
						<div class="col-6"><img src="{{ asset('assets/img/ser1.png') }}" /></div>
					</div>
				</div>
				<div class="col-md-3" style="padding: 30px">
					<div
						class="row"
						style="
							background-color: #fff;
							border-radius: 25px;
							min-height: 300px;
							padding-top: 90px;
						"
					>
						<div class="col-6">
							<h1 style="color: #f16522">تصميم المتجر الإكتروني</h1>
						</div>
						<div class="col-6"><img src="{{ asset('assets/img/ser2.png') }}" /></div>
					</div>
				</div>
				<div class="col-md-3" style="padding: 30px">
					<div
						class="row"
						style="
							background-color: #fff;
							border-radius: 25px;
							min-height: 300px;
							padding-top: 90px;
						"
					>
						<div class="col-6">
							<h1 style="color: #f16522">تصميم تطبيقات الجوال</h1>
						</div>
						<div class="col-6"><img src="{{ asset('assets/img/ser3.png') }}" /></div>
					</div>
				</div>
				<div class="col-md-3" style="padding: 30px">
					<div
						class="row"
						style="
							background-color: #fff;
							border-radius: 25px;
							min-height: 300px;
							padding-top: 90px;
						"
					>
						<div class="col-6">
							<h1 style="color: #f16522">التسويق الالكتروني</h1>
						</div>
						<div class="col-6"><img src="{{ asset('assets/img/ser4.png') }}" /></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Services -->
	<!-- <section class="services">
		<div class="container">
			<div class="row justify-content-center align-items-center gx-5 mb-5">
				<div class="col-md-4">
					<figure class="services__figure">
						<!-- <img src="" alt="some image here" /> - ->
					</figure>
				</div>
				<div class="col-md-4">
					<h5 class="services__title">Development</h5>
					<ul class="services__list">
						<li class="services__item">E-commerce websites</li>
						<li class="services__item">Static website creation</li>
						<li class="services__item">
							Web application development Mobile application development Third-party integration
							(SMS, payment gateways ..etc)
						</li>
						<li class="services__item">SEO creation</li>
					</ul>
				</div>
			</div>
			<div class="row justify-content-center align-items-center gx-5 mb-5">
				<div class="col-md-4">
					<h5 class="services__title">Design</h5>
					<ul class="services__list">
						<li class="services__item">Corporate identity</li>
						<li class="services__item">
							<ul class="services__sublist">
								<li class="services__item">Logo creation</li>
								<li class="services__item">Statuary creation</li>
								<li class="services__item">Company profile</li>
							</ul>
						</li>
						<li class="services__item">Website and mobile application design</li>
						<li class="services__item">UI/UX constultancy</li>
					</ul>
				</div>
				<div class="col-md-4">
					<figure class="services__figure">
						<!-- <img src="" alt="some image here" /> - ->
					</figure>
				</div>
			</div>
			<div class="row justify-content-center align-items-center gx-5">
				<div class="col-md-4">
					<figure class="services__figure">
						<!-- <img src="" alt="some image here" /> - ->
					</figure>
				</div>
				<div class="col-md-4">
					<h5 class="services__title">Consultancy</h5>
					<ul class="services__list">
						<li class="services__item">Design edits and makeover</li>
						<li class="services__item">Feasibility study</li>
						<li class="services__item">Business plan</li>
						<li class="services__item">Business Training / Entrepreneur training</li>
						<li class="services__item">Market understanding and project launching.</li>
						<li class="services__item">SEO makeover and configuring Content writeup</li>
					</ul>
				</div>
			</div>
		</div>
	</section> -->

	<!-- project -->
	<section class="project">
		<div class="container">
			<div class="project__block">
				<h1 class="project__title mt-auto">Everything you need for your car in an App</h1>
				<h1 class="project__title mb-5">كل إللي تبيه لسيارتك في تطبيق</h1>
				<a href="https://apps.apple.com/ae/app/maak-car-service-platform/id1494889873" class="project__button mb-2">
					<img src="{{ asset('assets/img/icons/app-store.png') }}" alt="app store icon" />
				</a>
				<a href="https://play.google.com/store/apps/details?id=com.maak" class="project__button mb-5">
					<img src="{{ asset('assets/img/icons/google-play.png') }}" alt="google play icon" />
				</a>
			</div>
		</div>
	</section>

	<!-- clients -->
	<!--<section class="clients">
		<div class="container">
			<div class="row">
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-1.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-2.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-3.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-4.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-5.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-6.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure">
						<img src="{{ asset('assets/img/logos/client-7.png') }}" alt="client logo image here" />
					</figure>
				</div>
				<div class="col-md-3 mb-4">
					<figure class="clients__figure"></figure>
				</div>
				<div class="col-md-3">
					<figure class="clients__figure"></figure>
				</div>
				<div class="col-md-3">
					<figure class="clients__figure"></figure>
				</div>
				<div class="col-md-3">
					<figure class="clients__figure"></figure>
				</div>
				<div class="col-md-3">
					<figure class="clients__figure"></figure>
				</div>
			</div>
		</div>
	</section> -->

	<!-- contact -->

@endsection