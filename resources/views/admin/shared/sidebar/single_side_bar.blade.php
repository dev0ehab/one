<li class="nav-item neo-nav-item {{ $value['routeName'] == Route::currentRouteName() ? 'active' : '' }}">
    <a href="{{ route($value['routeName']) }}" class="neo-nav-link">
        <span class="neo-nav-icon-wrap">{!! $value['icon'] !!}</span>
        <span class="menu-title neo-nav-label">{{ __('routes.' . $value['title']) }}</span>
    </a>
</li>
