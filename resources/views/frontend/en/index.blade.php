@extends('frontend.layouts.master')

@section('title', 'Maak Company for designing and developing websites and smart applications')

@section('content')

	<!-- carousel background-color:#F16522;-->
	<section
		class=""
		style="background-image: linear-gradient(#f16522, #e96221); min-height: calc(100vh - 84px)"
	>
		<div class="container">
			<div class="row" style="padding-top: 8em">
				<div class="col-md-6" style="padding: 30px 50px">
					<h1 class="" style="color: #fff; font-size: 72px">web design</h1>
					<p style="color: #fff">
						We offer you the best new and premium website designs at the best prices that suit you
						with experts Web design professionals
					</p>
				</div>
				<div class="col-md-6"><img src="{{ asset('assets/img/main-icon.png') }}" style="max-width: 100%" /></div>
			</div>
		</div>
	</section>

	<section class="" style="background-color: #ffb12d; padding-top: 5em">
		<div class="container">
			<h1 style="color: #fff">website design</h1>
			<p style="color: #fff">
				We are a leading company in the field of software systems solutions that is distinguished
				by its interest in customer service after delivery its projects by providing a project
				follow-up and periodic maintenance service, as it has allocated a professional support
				team consisting of The net sites team consists of a group of specialists with
				international competencies striving to provide the utmost attention and speed to provide
				full support to our customers.
			</p>
		</div>
	</section>

	<section class="" style="background-color: #ffb12d; padding-bottom: 5em">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-8" style="color: #fff">
							<h1>complete design</h1>
							<p>
								Start now with an attractive design for your website according to your business
								needs.
							</p>
						</div>
						<div class="col-4"><img src="{{ asset('assets/img/about-1.png') }}" /></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-8" style="color: #fff">
							<h1>Your site stats</h1>
							<p>
								Achieve your goals, a complete panel to see the number of visitors to your site.
								and places to visit
							</p>
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
					<h1 style="color: #f16522">mobile app design</h1>
					<p style="color: #f16522">Modern applications that reflect your company.</p>
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
							<h1>Unique Features</h1>
							<p>
								We make your website unique and differentiate your business to expand your
								products and services at the global market level.
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
							<h3 style="color: #f16522; padding-top:30px">Website design</h3>
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
							<h3 style="color: #f16522; padding-top:30px">online store design</h3>
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
							<h3 style="color: #f16522; padding-top:30px">mobile app design</h3>
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
							padding-top: 110px;
						"
					>
						<div class="col-6">
							<h3 style="color: #f16522; padding-top:30px">E-Marketing</h3>
						</div>
						<div class="col-6"><img src="{{ asset('assets/img/ser4.png') }}" /></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Services -->

	<!-- project -->
	<section class="project">
		<div class="container">
			<div class="project__block">
				<h1 class="project__title mt-auto">Everything you need for your car in an App</h1>
				<h1 class="project__title mb-5">كل إللي تبيه لسيارتك في تطبيق</h1>
				<a href="#" class="project__button mb-2">
					<img src="{{ asset('assets/img/icons/app-store.png') }}" alt="app store icon" />
				</a>
				<a href="#" class="project__button mb-5">
					<img src="{{ asset('assets/img/icons/google-play.png') }}" alt="google play icon" />
				</a>
			</div>
		</div>
	</section>

	<!-- contact -->
	<section class="contact">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1 class="primary-text mb-3">Contact us form:</h1>
					<form class="contact__form">
						<input type="text" placeholder="Name" />
						<input type="email" placeholder="Email" />
						<input type="text" placeholder="Phone" />
						<select>
							<option value="">Service</option>
							<option value="option2">option2</option>
							<option value="option3">option3</option>
						</select>
						<textarea rows="5" placeholder="Description"></textarea>
						<button>Submit</button>
					</form>
						
				</div>
				<div class="col-md-6">
					<h1 class="primary-text" style="padding-top:50px;">
											<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3477.010831690542!2d47.969514615461485!3d29.369972057071486!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf858cf8e4768b%3A0xaa38e26bf5f28367!2z2LTYsdmD2Kkg2YXYudin2YMg2YTYqti12YXZitmFINmI2KfYr9in2LHYqSDZhdmI2KfZgti5INin2YTYp9mG2KrYsdmG2KogTUFBSw!5e0!3m2!1sen!2skw!4v1625733479458!5m2!1sen!2skw" width="100%" height="515" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
											</h1>
				</div>
			</div>
		</div>
	</section>

@endsection