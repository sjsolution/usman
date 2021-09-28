@extends('frontend.layouts.master')

@section('title', 'Maak Company for designing and developing websites and smart applications')

@section('content')

	<!-- carousel background-color:#ffba2d;-->
	<section
		class="d-flex align-items-center"
		style="background-color: #ffb12d; min-height: calc(100vh - 78px)"
	>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6" style="padding: 30px 50px">
					<h1 class="mb-5" style="color: #fff; font-size: 70px">  Website design & 
						development
					</h1>
					<p class="text-white mb-4">
							We offer you regular and premium website designs & development at prices that suits your budget and business requirements
					</p>
					<p class="text-white mb-4">
						Websites are designed with the highest quality in terms of design, which saves our customers from future modicfication, as we take into account all the options that the client may need so that he does not have to design the site again, which may cost money and time.
					</p>
				</div>
				<div class="col-md-6"><img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid" /></div>
			</div>
		</div>
	</section>

	<section style="background-color: #f16522">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-3">
					<p class="text-white text-center text-md-auto">What does the site include?  </p>
				</div>
				<div class="col-md-9">
					<div class="row align-items-center">
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-4.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">responsive & compatible
									with all devices
								</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-3.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">incorporation 
									of e-mail accounts
								</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-2.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">website source 
									code provision
								</p>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="text-center my-5">
								<img src="{{ asset('assets/img/icon-1.png') }}" class="img-fluid" alt="icon" />
								<p class="text-white mt-4">Secured site</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section style="background-color: #e5e5e5">
		<div class="container">
			<h1 class="text-center display-2 mb-60" style="color: #f16533">Website design stages</h1>
			<div class="row justify-content-between align-items-center mb-50">
				<div class="col-md-5 order-2 order-md-0">
					<h1 class="mb-4" style="color: #f16533">The first step</h1>
					<p class="" style="color: #f16533">
						We meet with you to learn about your needs to build your project, business strategy and desired goals to develop your business
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
					<h1 class="mb-4" style="color: #f16533">The second step</h1>
					<p class="" style="color: #f16533">
						We'll outline the project, establish milestones, and agree on priorities. Run the project system immediately after defining the primary project objectives, target audience, and meeting customer needs. 
					</p>
				</div>
			</div>
			<div class="row justify-content-between align-items-center mb-50">
				<div class="col-md-5 order-2 order-md-0">
					<h1 class="mb-4" style="color: #f16533">The Third step</h1>
					<p class="" style="color: #f16533">
							When we have finished the outlines, prototypes of the project designs will be created. design team Our specialist will collaborate to come up with the best ideas and designs that fully correspond to your goals.


					</p>
					<p style="color: #f16533">
						Then the developers will go to work to create your own website using powerful programming languages ​​and interface Fashionable to suit your needs 
					</p>
				</div>
				<div class="col-md-5 order-1 order-md-1 my-5">
					<img src="{{ asset('assets/img/grid-img-3.png') }}" class="img-fluid" alt="cover" />
				</div>
			</div>
			<div class="row justify-content-between align-items-center">
				<div class="col-md-5 my-5">
					<img src="{{ asset('assets/img/grid-img-4.png') }}" class="img-fluid" alt="cover" />
				</div>
				<div class="col-md-5">
					<h1 class="mb-4" style="color: #f16533">The fourth step</h1>
					<p class="" style="color: #f16533">
							Now it's time to review and test. In this process, we aim to ensure the quality of the project All computer platforms and cell phones. 
					</p>
					<p style="color: #f16533">
						When all of this is done, we will present the project to you for your perusal. Upon approval, it will be Launching and promoting the site immediately. 
					</p>
				</div>
			</div>
		</div>
	</section>
	
@endsection