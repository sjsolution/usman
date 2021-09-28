
	<!-- footer ar -->
	@if(session()->has('ar'))
		<section style="background-color: #4d4d4d">
			<div class="container">
				<div class="row flex-row-reverse">
					<div class="col-md-5">
						<ul class="text-end">
							<li class="mb-3">
								<a href="tel:22233313" dir="ltr"class="text-white">
									<span class="footer-icon"><i class="fas fa-phone-alt fa-fw"></i></span>+965 22223313
								</a>
							</li>
							<li class="mb-3">
								<a href="https://maak.live" class="text-white">
									www.maak.live
									<span class="footer-icon"><i class="fas fa-globe-europe fa-fw"></i></span>
								</a>
							</li>
							<li class="mb-3">
								<a href="mailto:info@maak.live" class="text-white">
									info@maak.live
									<span class="footer-icon"><i class="far fa-envelope fa-fw"></i></span>
								</a>
							</li>
							<li class="mb-3">
								<a href="https://www.instagram.com/maak.dev/" class="text-white">
									maak.dev
									<span class="footer-icon"><i class="fab fa-instagram fa-fw"></i></span>
								</a>
							</li>
							<li class="mb-3">
								<a href="https://www.instagram.com/maak.car/" class="text-white">
									maak.car
									<span class="footer-icon"><i class="fab fa-instagram fa-fw"></i></span>
								</a>
							</li>
							<!--<li class="">
								<a href="https://www.facebook.com/Cars.Maak/" class="text-white">
									maak.cars
									<span class="footer-icon"><i class="fab fa-facebook-f fa-fw"></i></span>
								</a>
							</li>-->
														<li class="">
								<a href="https://wa.me/message/5MS3KOJCLG5VD1" class="text-white">
									Chat with us		<span class="footer-icon"><i class="fab fa-whatsapp fa-fw"></i></span>							
								</a>
							</li>
						</ul>
						<p class="text-end text-white mt-5">
							Kuwait city, Qibla, Fahd al salem street, Saadoun Al-Jassem building, Floor 2, Office
							8
						</p>
					</div>
				</div>
			</div>
		</section>

		<footer class="bg-dark py-5">
			<p class="text-center text-white">
				جميع الحقوق محفوظة © <?php echo date('Y');?>  معاك لتصميم المواقع الالكترونية.
			</p>
		</footer>
	@endif

	<!-- footer en -->
	@if(session()->has('en'))
		<section style="background-color: #4d4d4d">
			<div class="container">
				<div class="row flex-row">
					<div class="col-md-5">
											<ul dir="ltr"class=" ">
							<li class="mb-3">
								<a href="tel:22233313" class="text-white">
									<span class="footer-icon"><i class="fas fa-phone-alt fa-fw"></i></span>+965 222233134									
								</a>
							</li>
							<li class="mb-3">
								<a href="http://www.maak.live" class="text-white">
									<span class="footer-icon"><i class="fas fa-globe-europe fa-fw"></i></span>www.maak.live									
								</a>
							</li>
							<li class="mb-3">
								<a href="mailto:info@maak.live" class="text-white">
									<span class="footer-icon"><i class="far fa-envelope fa-fw"></i></span>info@maak.live									
								</a>
							</li>
							<li class="mb-3">
								<a href="https://www.instagram.com/maak.dev/" class="text-white">
									<span class="footer-icon"><i class="fab fa-instagram fa-fw"></i></span>maak.dev									
								</a>
							</li>
							<li class="mb-3">
								<a href="https://www.instagram.com/maak.car/" class="text-white">
									<span class="footer-icon"><i class="fab fa-instagram fa-fw"></i></span>maak.car									
								</a>
							</li>
							<li class="">
								<a href="https://wa.me/message/5MS3KOJCLG5VD1" class="text-white">
									<span class="footer-icon"><i class="fab fa-whatsapp fa-fw"></i></span>Chat with us									
								</a>
							</li>
						</ul>
						<p class="  text-white mt-5">
							Kuwait city, Qibla, Fahd al salem street, Saadoun Al-Jassem building, Floor 2, Office
							8
						</p>
					</div>
				</div>
			</div>
		</section>
				<footer class="bg-dark py-5">
			<p class="text-center text-white">
				All rights reserved ©  <?php echo date('Y');?>   Maak for web design.
			</p>
		</footer>
	@endif