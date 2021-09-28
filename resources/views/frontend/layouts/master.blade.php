<!DOCTYPE html>
<html @if (session()->has('ar')) lang="ar" dir="rtl" @else lang="en" @endif>

	@include('frontend.layouts.head')

	<body>

        @include('frontend.layouts.header')

        @yield('content')

        @include('frontend.layouts.footer')

        @include('frontend.layouts.scripts')

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
