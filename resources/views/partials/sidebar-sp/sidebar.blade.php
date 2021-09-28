<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
   @if(Auth::user()->setup_complete == '1' || Auth::user()->setup_complete == '5')
    {{-- ###### Deshboard ################### --}}
    <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
    <a class="nav-link" href="{{route('user.home')}}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    {{-- ********************User****************** --}}
      
    <li class="nav-item  {{ Request::is('serviceprovider/users-view*') ? 'active' : '' }}">
      <a class="nav-link "  href="{{route('sp.users')}}" aria-expanded="{{ Request::is('user*') ? 'true' : 'false' }}" aria-controls="sidebar-user">
        <span class="menu-title w-100">Users</span>
        <i class="fa fa-user" aria-hidden="true"></i>
      </a>

    </li>
    
     {{-- ********************Services****************** --}}
     <li class="nav-item {{ Request::is('services*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('sp.getservicelist')}}" aria-expanded="{{ Request::is('services/*') ? 'true' : 'false' }}" aria-controls="sidebar-services">
          <span class="menu-title w-100">Services</span>
          <i class="fa fa-truck" aria-hidden="true"></i>
        </a>
      </li>
      
      {{-- Booking Tab --}}

      <li class="nav-item {{ Request::is('serviceprovider/booking/list*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('sp.booking.view')}}" aria-expanded="{{ Request::is('booking*') ? 'true' : 'false' }}" aria-controls="sidebar-booking">
          <span class="menu-title w-100">Bookings</span>
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </a>
       </li>
       
       {{-- Transaction Tab --}}
       <li class="nav-item {{ Request::is('serviceprovider/transaction/list*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('sp.transaction.view')}}" aria-expanded="{{ Request::is('transaction*') ? 'true' : 'false' }}" aria-controls="sidebar-transaction">
          <span class="menu-title w-100">Transactions</span>
          <i class="fa fa-money" aria-hidden="true"></i>
        </a>
       </li>

        {{-- Review Tab --}}
        <li class="nav-item ">
          <a class="nav-link"  href="{{route('review.list.view')}}" aria-expanded="{{ Request::is('review*') ? 'true' : 'false' }}" aria-controls="sidebar-review">
            <span class="menu-title w-100">Reviews</span>
            <i class="fa fa-comments" aria-hidden="true"></i>
          </a>
        </li>
     
      {{-- ********************Technicians****************** --}}
      <li class="nav-item {{ Request::is('technician*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('sp.getTechnicianlist')}}" aria-expanded="{{ Request::is('technician*') ? 'true' : 'false' }}" aria-controls="sidebar-transaction">
          <span class="menu-title w-100">Technicians</span>
          <i class="fa fa-users" aria-hidden="true"></i>
        </a>
      </li>
      {{-- ****************Settings**********************--}}
      <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#sidebar-settings" aria-expanded="{{ Request::is('settings*') ? 'true' : 'false' }}" aria-controls="sidebar-settings">
          <span class="menu-title">Settings</span>
          <i class="menu-arrow"></i>
          <i class="fa fa-cogs" aria-hidden="true"></i>
        </a>
        <div class="collapse {{ Request::is('settings*') ? 'show' : '' }}" id="sidebar-settings">
          <ul class="nav flex-column sub-menu">                
            <li class="nav-item">
            <a class="nav-link {{ Request::is('sptimeslot/') ? 'active' : '' }}" href="{{ route('sp.sptimeslot')}}">Timeslot</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::is('settings/push-notification/list') ? 'active' : '' }}" href="{{ route('sp.push.send.list')}}">Push Notification</a>
            </li>
            <li class="nav-item">
            <a class="nav-link {{ Request::is('settings/car-groups') ? 'active' : '' }}" href="{{ route('cargroup.view')}}">Car Group</a>
            </li>
          </ul>
        </div>
      </li>
    @else
    <li class="nav-item {{ Request::is('home') ? 'active' : '' }}" >
        <a class="nav-link" href="{{route('user.home')}}" >
            <span class="menu-title">Dashboard</span>
            <i class="mdi mdi-home menu-icon"></i>
          </a>
        </li>
         {{-- ********************User****************** --}}
      
      <li class="nav-item  {{ Request::is('serviceprovider/users-view*') ? 'active' : '' }}">
        <a class="nav-link "  href="#" aria-expanded="{{ Request::is('user*') ? 'true' : 'false' }}" aria-controls="sidebar-user" style="cursor:not-allowed">
          <span class="menu-title w-100">Users</span>
          <i class="fa fa-user" aria-hidden="true"></i>
        </a>

      </li>
         {{-- ********************Services****************** --}}
         <li class="nav-item {{ Request::is('services*') ? 'active' : '' }}">
            <a class="nav-link"  href="#" aria-expanded="{{ Request::is('services/*') ? 'true' : 'false' }}" aria-controls="sidebar-services" style="cursor:not-allowed">
              <span class="menu-title w-100">Services</span>
              <i class="fa fa-truck" aria-hidden="true"></i>
            </a>
          </li>

          
          {{-- Booking Tab --}}
    
          <li class="nav-item {{ Request::is('serviceprovider/booking/list*') ? 'active' : '' }}">
            <a class="nav-link"  href="#" aria-expanded="{{ Request::is('booking*') ? 'true' : 'false' }}" aria-controls="sidebar-booking" style="cursor:not-allowed">
              <span class="menu-title w-100">Bookings</span>
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </a>
           </li>
           
           {{-- Transaction Tab --}}
           <li class="nav-item {{ Request::is('serviceprovider/transaction/list*') ? 'active' : '' }}">
            <a class="nav-link"  href="#" aria-expanded="{{ Request::is('transaction*') ? 'true' : 'false' }}" aria-controls="sidebar-transaction" style="cursor:not-allowed">
              <span class="menu-title w-100">Transactions</span>
              <i class="fa fa-money" aria-hidden="true"></i>
            </a>
           </li>
    
            {{-- Review Tab --}}
            <li class="nav-item ">
              <a class="nav-link"  href="#" aria-expanded="{{ Request::is('review*') ? 'true' : 'false' }}" aria-controls="sidebar-review" style="cursor:not-allowed">
                <span class="menu-title w-100">Reviews</span>
                <i class="fa fa-comments" aria-hidden="true"></i>
              </a>
            </li>
         
          {{-- ********************Technicians****************** --}}
          <li class="nav-item {{ Request::is('technician*') ? 'active' : '' }}">
            <a class="nav-link"  href="#" aria-expanded="{{ Request::is('technician*') ? 'true' : 'false' }}" aria-controls="sidebar-transaction" style="cursor:not-allowed">
              <span class="menu-title w-100">Technicians</span>
              <i class="fa fa-users" aria-hidden="true"></i>
            </a>
          </li>
          
          {{-- ****************Settings**********************--}}
          <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#" aria-expanded="{{ Request::is('settings*') ? 'true' : 'false' }}" aria-controls="sidebar-settings" style="cursor:not-allowed">
              <span class="menu-title">Settings</span>
              <i class="menu-arrow"></i>
              <i class="fa fa-cogs" aria-hidden="true"></i>
            </a>
          </li>
    @endif 
  </ul>
</nav>
