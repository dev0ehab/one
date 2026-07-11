<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow neo-sidebar" data-scroll-to-active="true" data-neo-sidebar>
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand neo-brand" href="{{ url('admin/dashboard') }}">
                    <img class="brand-logo img-logo w-auto" src="{{ Cache::get('settings')['logo'] }}" alt="{{ config('app.name') }}">
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {!! \App\Traits\SideBarTrait::sidebar() !!}
        </ul>
    </div>
</div>
