<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    @php
 
      $roleMenu = [];

      if(\Auth::user()->type != '1')
        $roleMenu = \App\Models\Menu::whereHas('rolePermission',function($q){
          $q->where('role_id',\Auth::user()->role[0]->roleAssign[0]->id)->where('is_read',1);
        })->get();

    @endphp

    @if(\Auth::user()->type == '1')
 
      @foreach ($menus as $menu)
        <li class="nav-item {{ Request::is($menu->url.'/*') || Request::is($menu->url) ? 'active' : '' }}">
          <a class="nav-link"  @if($menu->subMenu->count()) data-toggle="collapse" @endif href="{{ !empty($menu->named_url) ? route( $menu->named_url ) : '#'.$menu->name }}" aria-expanded="{{ Request::is($menu.'*') ? 'true' : 'false' }}" aria-controls="{{ '#'.$menu->name }}">
            <span class="menu-title  w-100">{{ $menu->name }}</span>
            @if($menu->subMenu->count())
              <i class="menu-arrow"></i>
            @endif
            <i class="{{ $menu->icon }}" aria-hidden="true"></i>
          </a>
            @if($menu->subMenu->count())
            <div class="collapse {{ Request::is($menu->url.'/*') ? 'show' : '' }}" id="{{ $menu->name }}">
              <ul class="nav flex-column sub-menu">
                @foreach($menu->subMenu as $submenu)
                <li class="nav-item">
                  <a class="nav-link {{ Request::is($submenu->url.'/*') || Request::is($submenu->url)  ? 'active' : '' }}" href="{{route($submenu->named_url)}}">{{ $submenu->name }}</a>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
        </li>
      @endforeach

    @else

      @foreach ($roleMenu as $menu)
        <li class="nav-item {{ Request::is($menu->url) ? 'active' : '' }}">
          <a class="nav-link"  @if($menu->subMenu->count()) data-toggle="collapse" @endif href="{{ !empty($menu->named_url) ? route( $menu->named_url ) : '#'.$menu->name }}" aria-expanded="{{ Request::is($menu.'*') ? 'true' : 'false' }}" aria-controls="{{ '#'.$menu->name }}">
            <span class="menu-title  w-100">{{ $menu->name }}</span>
            @if($menu->subMenu->count())
              <i class="menu-arrow"></i>
            @endif
            <i class="{{ $menu->icon }}" aria-hidden="true"></i>
          </a>
            @if($menu->subMenu->count())
            <div class="collapse {{ Request::is($menu->url) ? 'show' : '' }}" id="{{ $menu->name }}">
              <ul class="nav flex-column sub-menu">
                @foreach($menu->subMenu as $submenu)
                <li class="nav-item">
                  <a class="nav-link {{ Request::is($submenu->url) ? 'active' : '' }}" href="{{route($submenu->named_url)}}">{{ $submenu->name }}</a>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
        </li>
      @endforeach

    @endif

  </ul>
</nav>
