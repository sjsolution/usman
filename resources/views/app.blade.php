<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"> 
    @include('partials.header.head')
    @yield('style')
   
  <body>
    <div id="cover-spin"></div>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      @include('partials.header.header')
      
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        
        @include('partials.sidebar.sidebar')
       
        <div class="main-panel">
            
          <div class="content-wrapper">
            @yield('content')
          </div>
          @include('partials.header.admin-scripts')
          @include('partials.footer.footer')
          @yield('scripts')
        </div>
 
      </div>

    </div>
    @include('sweetalert::alert')

  </body>
</html>
