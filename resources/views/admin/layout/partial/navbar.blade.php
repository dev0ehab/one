<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">

                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs neo-icon-btn" href="#" aria-label="Menu">
                                <i class="ficon feather icon-menu"></i>
                            </a>
                        </li>
                    </ul>
                    <nav class="admin-neotech-breadcrumb" aria-label="breadcrumb">
                        <ol class="neo-breadcrumb mb-0">
                            <li class="neo-breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">
                                    <i class="feather icon-home"></i>
                                    {{ __('site.home') }}
                                </a>
                            </li>
                            @if (Route::currentRouteName() != 'admin.dashboard')
                                <li class="neo-breadcrumb-sep" aria-hidden="true">/</li>
                                <li class="neo-breadcrumb-item active" aria-current="page">
                                    {{ __('routes.' . \Request::route()->getAction()['title']) }}
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>

                <div class="admin-neotech-navbar-actions d-flex align-items-center">
                    <ul class="nav navbar-nav float-right resp-wrap-icon">
                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link neo-icon-btn" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (lang() == 'ar')
                                    <i class="flag-icon flag-icon-sa"></i>
                                    <span class="selected-language d-none d-sm-inline">عربي</span>
                                @else
                                    <i class="flag-icon flag-icon-us"></i>
                                    <span class="selected-language d-none d-sm-inline">English</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item" href="{{ url('admin/lang/ar') }}" data-language="ar">
                                    <i class="flag-icon flag-icon-sa"></i> عربي
                                </a>
                                <a class="dropdown-item" href="{{ url('admin/lang/en') }}" data-language="en">
                                    <i class="flag-icon flag-icon-us"></i> English
                                </a>
                            </div>
                        </li>

                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link neo-icon-btn" id="layout-mode" aria-label="Toggle dark mode">
                                <i class="ficon feather icon-moon" onclick="changeMode()"></i>
                            </a>
                        </li>

                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link neo-icon-btn nav-link-expand" aria-label="Fullscreen">
                                <i class="ficon feather icon-maximize"></i>
                            </a>
                        </li>

                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link neo-icon-btn position-relative" href="{{ route('admin.admins.notifications') }}" aria-label="Notifications">
                                @if (auth('admin')->user()->unreadNotifications->count() > 0)
                                    <span class="neo-badge-pill">{{ auth('admin')->user()->unreadNotifications->count() }}</span>
                                @endif
                                <i class="ficon feather icon-bell"></i>
                            </a>
                        </li>

                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600">{{ auth('admin')->user()->name }}</span>
                                    <span class="user-status">{{ __('admin.available') }}</span>
                                </div>
                                <span class="neo-avatar-wrap">
                                    <img class="round" src="{{ auth('admin')->user()->image }}" alt="{{ auth('admin')->user()->name }}" height="40" width="40">
                                    <span class="neo-online-dot" aria-hidden="true"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ url('admin/profile') }}">
                                    <i class="feather icon-settings"></i> {{ __('site.profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ url('admin/logout') }}">
                                    <i class="feather icon-power"></i> {{ __('site.logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
