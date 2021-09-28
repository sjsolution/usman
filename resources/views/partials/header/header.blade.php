<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class=" navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{route('admin.home')}}"><img src="{{asset('images/DashLogo.png')}}" alt="logo" /></a>
  <a class="navbar-brand brand-logo-mini" href="{{route('admin.home')}}"><img src="{{asset('images/DashbaordLogo.png')}}" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    {{-- <div class="search-field d-none d-md-block">
      <form class="d-flex align-items-center h-100" action="#">
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <i class="input-group-text border-0 mdi mdi-magnify"></i>
          </div>
          <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
        </div>
      </form>
    </div> --}}
    <ul class="navbar-nav navbar-nav-right">
      <span id="notify_user">
          <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">No new Notification</h6>
                <div class="dropdown-divider"></div>
                
              
              </div>
          </li>
      </span>
  
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
          <div class="nav-profile-img">
            <!-- <img src="{{asset('images/faces/face1.jpg')}}" alt="image"> -->
             @if(!empty(Auth::user()) && Auth::user()->profile_pic)
                  <img src="{{config('app.AWS_URL').Auth::user()->profile_pic}}" alt="">
            @else
              <img src="{{ asset('images/defaultProfile.png')}}" alt="">
            @endif
            <span class="availability-status online"></span>
          </div>
          <div class="nav-profile-text">
            <p class="mb-1 text-black">{{ !empty(Auth::user()) ? Auth::user()->name : 'Admin' }}</p>
          </div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="{{route('admin.myprofile')}}">
          <!-- <a class="dropdown-item" href="#"> -->
            <i class="mdi mdi-cached mr-2 text-success"></i> Profile </a>
            <!-- <a class="dropdown-item" href="#">
              <i class="mdi mdi-settings text-success"></i> Change password </a> -->
            <a class="dropdown-item" href="{{route('admin.changepassword')}}">
              <i class="mdi mdi-settings text-success"></i> Change password </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>

                <form id="logout-form" action="{{ 'App\Admin' == Auth::getProvider()->getModel() ? route('admin.logout') : route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
        </div>
      </li>

    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
