<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <li class="nav-item nav-profile" style="display:none">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <span class="login-status online"></span>
        </div> 
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2"></span>
        </div>
      </a>
    </li>


    
    {{-- ###### Dashboard ################### --}}
    <li class="nav-item {{ Request::is('admin/home') ? 'active' : '' }}">
    <a class="nav-link" href="{{route('admin.home')}}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

            {{-- ********************User****************** --}}
           
      <li class="nav-item  {{ Request::is('admin/user*') ? 'active' : '' }}">
        <a class="nav-link "  href="{{route('user.index')}}" aria-expanded="{{ Request::is('user*') ? 'true' : 'false' }}" aria-controls="sidebar-user">
          <span class="menu-title w-100">Users</span>
          <i class="fa fa-user" aria-hidden="true"></i>
        </a>

      </li>



        {{-- ****************Service Provider**********************--}}
      <li class="nav-item {{ Request::is('admin/serviceprovider*') ? 'active' : '' }}">
         <a class="nav-link"  href="{{route('sp.listServiceprovider')}}" aria-expanded="{{ Request::is('admin/serviceprovider*') ? 'true' : 'false' }}" aria-controls="sidebar-serviceprovider">
           <span class="menu-title w-100">Service Provider</span>
           <i class="fa fa-users" aria-hidden="true"></i>
         </a>
       </li>

      {{-- **************** Booking **********************--}}
      <li class="nav-item {{ Request::is('admin/booking*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('booking.list.view')}}" aria-expanded="{{ Request::is('admin/serviceprovider*') ? 'true' : 'false' }}" aria-controls="sidebar-serviceprovider">
          <span class="menu-title w-100">Booking</span>
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </a>
      </li>

      {{-- **************** Transaction **********************--}}
       <li class="nav-item {{ Request::is('admin/transaction*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('transaction.list.view')}}" aria-expanded="{{ Request::is('admin/serviceprovider*') ? 'true' : 'false' }}" aria-controls="sidebar-serviceprovider">
          <span class="menu-title w-100">Transaction</span>
          <i class="fa fa-money" aria-hidden="true"></i>
        </a>
      </li>

      {{-- Review Tab --}}
      <li class="nav-item {{ Request::is('admin/review*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('admin.review.list.view')}}" aria-expanded="{{ Request::is('review*') ? 'true' : 'false' }}" aria-controls="sidebar-review">
          <span class="menu-title w-100">Review</span>
          <i class="fa fa-comments" aria-hidden="true"></i>
        </a>
      </li>

       {{-- Coupon Tab --}}
       <li class="nav-item {{ Request::is('admin/coupon*') ? 'active' : '' }}">
        <a class="nav-link"  href="{{route('coupon.list.view')}}" aria-expanded="{{ Request::is('coupon*') ? 'true' : 'false' }}" aria-controls="sidebar-review">
          <span class="menu-title w-100">Coupons</span>
          <i class="fa fa-bookmark" aria-hidden="true"></i>
        </a>
      </li>

     
      {{-- ****************Settings**********************--}}
      <li class="nav-item {{ Request::is('admin/settings/label*') ? 'active' : '' }}">
         <a class="nav-link" data-toggle="collapse" href="#sidebar-label" aria-expanded="{{ Request::is('admin/settings/label*') ? 'true' : 'false' }}" aria-controls="sidebar-label">
           <span class="menu-title">Settings</span>
           <i class="menu-arrow"></i>
           <i class="fa fa-cogs" aria-hidden="true"></i>
         </a>
         <div class="collapse {{ Request::is('admin/settings/label*') ? 'show' : '' }}" id="sidebar-label">
           <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/label') ? 'active' : '' }}" href="{{route('label.index')}}">Labels</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/banner/index') ? 'active' : '' }}" href="{{route('banner.index')}}">Banners</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/ourpartnerslist') ? 'active' : '' }}" href="{{route('op.partnerslist')}}">Our Partners</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings//categoryView') ? 'active' : '' }}" href="{{route('category.categoryView')}}">Categories</a>
              </li>
              <li class="nav-item">
                 <a class="nav-link {{ Request::is('admin/settings/category/subcategory') ? 'active' : '' }}" href="{{route('category.subcategory')}}">Sub Categories</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/vehicle/list') ? 'active' : '' }}" href="{{route('getvehiclelist')}}">Vehicle Type</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/brand/brandlist') ? 'active' : '' }}" href="{{route('vehicle.brandlist')}}">Vehicle Brand</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/model/modellist') ? 'active' : '' }}" href="{{route('vehicle.modellist')}}">Vehicle Model</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/manufacture/manufacturelist') ? 'active' : '' }}" href="{{route('vehicle.manufacturelist')}}">Vehicle manufacturing year</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/coverage') ? 'active' : '' }}" href="{{route('vehicle.coverage')}}">Insurance Coverage Range</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/fee_charge') ? 'active' : '' }}" href="{{route('fee.charge')}}">Fees Setting</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ Request::is('admin/settings/model/modellist/create_listing') ? 'active' : '' }}" href="{{route('create.list')}}">Listing Mangement</a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings/timeslot') ? 'active' : '' }}" href="{{route('admin.timeslot')}}">Time Slot</a>
              </li>
            </ul>
         </div>
       </li>

  </ul>
</nav>
