<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @include('partials.header-sp.head')
    @yield('style') 
  <body> 
    <div id="cover-spin"></div>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      @include('partials.header-sp.header')
     
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">


        @include('partials.sidebar-sp.sidebar')

        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
          </div> 

          @include('partials.footer-sp.footer')
          @yield('scripts')
        </div>

      </div>

    </div>
    @include('sweetalert::alert')
  </body>
</html>
