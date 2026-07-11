<li class="nav-item neo-nav-group {{ in_array(substr(Route::currentRouteName(), 6), $value['child']) ? 'has-sub sidebar-group-active open' : 'has-sub' }}">
    <a href="javascript:void(0);" class="neo-nav-link">
        <span class="neo-nav-icon-wrap">{!! $value['icon'] !!}</span>
        <span class="menu-title neo-nav-label">{{ __('routes.' . $value['title']) }}</span>
        <span class="neo-nav-chevron" aria-hidden="true">
            <i class="feather icon-chevron-down"></i>
        </span>
    </a>
    <ul class="menu-content neo-nav-submenu">
        @foreach ($value['child'] as $child)
            @if (isset($routes_data['"admin.' . $child . '"']) && $routes_data['"admin.' . $child . '"']['title'] && $routes_data['"admin.' . $child . '"']['sub_link'])
                <li class="neo-nav-subitem {{ ('admin.' . $child) == Route::currentRouteName() ? 'active' : '' }}">
                    <a href="{{ route('admin.' . $child) }}" class="neo-nav-sublink">
                        <span class="neo-nav-subdot" aria-hidden="true"></span>
                        <span class="neo-nav-sublabel">{{ __('routes.' . $routes_data['"admin.' . $child . '"']['title']) }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</li>
