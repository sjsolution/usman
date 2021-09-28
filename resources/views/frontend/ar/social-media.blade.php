@extends('frontend.layouts.master')

@section('title', 'معاك - التسويق الالكتروني')

@section('content')

	<!-- carousel background-color:#ffba2d;-->
	<section
		class="d-flex align-items-center"
		style="background-color: #ffba2d; min-height: calc(100vh - 78px)"
	>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6" style="padding: 30px 50px">
					<h1 class="mb-5" style="color: #fff; font-size: 70px">تصميم متجر الكتروني</h1>
					<p class="text-white mb-4">
						نضع تحت يدكم خبرة أكثر من 15 عام في مجال المواقع الالكترونية
					</p>
					<p class="text-white mb-4">
						نقدم لك أفضل تصميمات المواقع المتميزة والجديدة بأفضل الأسعار التي تناسبك مع خبراء
						محترفين في مجال تصميم مواقع الإنترنت
					</p>
					<p class="text-white">
						يتم تصميم المواقع بأعلى جودة من حيث التصميم والتي توفر على عملائنا التعديل في المستقبل
						حيث نأخذ بالاعتبار جميع الخيارات التي قد يحتاجها العميل حتى لا يضطر إلى تصميم الموقع
						من جديد مما قد يكلف المال والوقت.
					</p>
				</div>
				<div class="col-md-6"><img src="{{ asset('assets/img/hero-img-2.png') }}" class="img-fluid" /></div>
			</div>
		</div>
	</section>

	<section style="background-color: #f16522">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-3">
					<p class="text-white text-center text-md-auto">ماذا يتضمن الموقع</p>
				</div>
				<div class="col-md-9">
					<div class="row align-items-center">
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-9.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">نقوم بتوفير موقع أمن</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-10.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">نقوم بتوفير موقع أمن</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-11.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">نوفر لكم عدد من البريد الالكتروني</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-12.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">الموقع الالكتروني متوافق مع جميع الأجهزه</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section style="background-color: #e5e5e5">
		<div class="container">
			<h1 class="text-center display-2 mb-60" style="color: #f16533">مراحل تصميم الموقع</h1>
			<div class="row justify-content-between align-items-center mb-50">
				<div class="col-md-5 order-2 order-md-0">
					<h1 class="mb-4" style="color: #f16533">الخطوة الأولى</h1>
					<p class="" style="color: #f16533">
						نجتمع بكم للتعرف على احتياجاتكم لبناء مشروعكم وإستراتيجية عملك و الأهداف المرجوة
						لتطوير عملك
					</p>
				</div>
				<div class="col-md-5 order-1 order-md-1 my-5">
					<img src="{{ asset('assets/img/grid-img-1.png') }}" class="img-fluid" alt="cover" />
				</div>
			</div>
			<div class="row justify-content-between align-items-center mb-50">
				<div class="col-md-5 my-5">
					<img src="{{ asset('assets/img/grid-img-2.png') }}" class="img-fluid" alt="cover" />
				</div>
				<div class="col-md-5">
					<h1 class="mb-4" style="color: #f16533">الخطوة الثانية</h1>
					<p class="" style="color: #f16533">
						سنقوم بتحديد الخطوط العريضة للمشروع، إنشاء المعالم الأساسية، والاتفاق على الأولويات.
						تشغيل المشروع سيستم مباشرة بعد تحديد أهداف المشروع الأساسية، الجمهور المستهدف، وتلبية
						احتياجات العملاء.
					</p>
				</div>
			</div>
			<div class="row justify-content-between align-items-center mb-50">
				<div class="col-md-5 order-2 order-md-0">
					<h1 class="mb-4" style="color: #f16533">الخطوة الأولى</h1>
					<p class="" style="color: #f16533">
						عندما ننتهي من الخطوط العريضة، سيتم إنشاء نماذج مبدأية لتصاميم المشروع. فريق التصميم
						المتخصص لدينا سيتعاون ليصل إلى أفضل الأفكار والتصاميم التي تتوافق كليا مع أهدافك.
					</p>
					<p style="color: #f16533">
						ثم سيقوم المطورون بمباشرة العمل لانشاء موقعك الخاص باستعمال لغات برمجة قوية و واجهة
						عصرية تناسب احتياجاتك
					</p>
				</div>
				<div class="col-md-5 order-1 order-md-1 my-5">
					<img src="{{ asset('assets/img/grid-img-5.png') }}" class="img-fluid" alt="cover" />
				</div>
			</div>
			<div class="row justify-content-between align-items-center">
				<div class="col-md-5 my-5">
					<img src="{{ asset('assets/img/grid-img-6.png') }}" class="img-fluid" alt="cover" />
				</div>
				<div class="col-md-5">
					<h1 class="mb-4" style="color: #f16533">الخطوة الرابعة</h1>
					<p class="" style="color: #f16533">
						الآن حان وقت المراجعة والاختبار. نستهدف في هذه العملية أن نتأكد من جودة المشروع على
						جميع منصات الكمبيوتر والهواتف الخلوية.
					</p>
					<p style="color: #f16533">
						حين يتم الإنتهاء من كل ذلك، سنقدم لك المشروع لتقوم بالإطلاع عليه. عند الموافقة، سيتم
						إطلاق وترويج الموقع في الحال.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!--<section style="background-color: #f16522">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="rounded-card">
						<div class="rounded-card__header rounded-card__header--3">
							<h3>الباقة البرونزية</h3>
							<p>باقات التسويق</p>
						</div>
						<div class="rounded-card__body">
							<ul>
								<li>أنشاء صفحة</li>
								<li>تصميم فريم للصفحة</li>
								<li>تصميم شعار خاص بصفحتك</li>
								<li>تصميم غلاف خاص بصفحتك</li>
								<li>ربط صفحات التواصل مع الموقع</li>
								<li>تصميم 15 أعلان أحترافي</li>
								<li>أشراف ومتابعة بشكل يومي مع العميل</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
							</ul>
						</div>
						<div class="rounded-card__footer">
							<a href="#" class="rounded-card__btn">قم بالطلب الآن</a>
							<p class="rounded-card__tagline">عرض خاص لفترة محدودة</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="rounded-card">
						<div class="rounded-card__header rounded-card__header--2">
							<h3>الباقة البرونزية</h3>
							<p>باقات التسويق</p>
						</div>
						<div class="rounded-card__body">
							<ul>
								<li>أنشاء صفحة</li>
								<li>تصميم فريم للصفحة</li>
								<li>تصميم شعار خاص بصفحتك</li>
								<li>تصميم غلاف خاص بصفحتك</li>
								<li>ربط صفحات التواصل مع الموقع</li>
								<li>تصميم 15 أعلان أحترافي</li>
								<li>أشراف ومتابعة بشكل يومي مع العميل</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
							</ul>
						</div>
						<div class="rounded-card__footer">
							<a href="#" class="rounded-card__btn">قم بالطلب الآن</a>
							<p class="rounded-card__tagline">عرض خاص لفترة محدودة</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="rounded-card">
						<div class="rounded-card__header rounded-card__header--1">
							<h3>الباقة البرونزية</h3>
							<p>باقات التسويق</p>
						</div>
						<div class="rounded-card__body">
							<ul>
								<li>أنشاء صفحة</li>
								<li>تصميم فريم للصفحة</li>
								<li>تصميم شعار خاص بصفحتك</li>
								<li>تصميم غلاف خاص بصفحتك</li>
								<li>ربط صفحات التواصل مع الموقع</li>
								<li>تصميم 15 أعلان أحترافي</li>
								<li>أشراف ومتابعة بشكل يومي مع العميل</li>
								<li>الترويج و الأستهداف بشكل دقيق</li>
							</ul>
						</div>
						<div class="rounded-card__footer">
							<a href="#" class="rounded-card__btn">قم بالطلب الآن</a>
							<p class="rounded-card__tagline">عرض خاص لفترة محدودة</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>-->

@endsection