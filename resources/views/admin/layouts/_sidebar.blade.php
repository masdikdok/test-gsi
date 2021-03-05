
<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Web Test</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <div class="profile_pic">
            <img src="{{ asset('images/img.jpg') }}" alt="..." class="img-circle profile_img thumb img-thumbnail">
        </div>
        <div class="profile_info">
            <span>Halo,</span>
            <h2>{{ Session::get('credentials')->nama }}</h2>
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <h3>General</h3>
            <ul class="nav side-menu flex-column">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-home"></i> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.produksi') }}">
                        <i class="fa fa-desktop"></i> Produksi</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="menu_section">
            <h3>Setting</h3>
            <ul class="nav side-menu flex-column">
                <li>
                    <a class="pl-4" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

    </div>
    <!-- /sidebar menu -->
</div>
