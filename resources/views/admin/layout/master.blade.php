@include('admin.layout.partial.header')
<div id="neo-backdrop" aria-hidden="true"></div>
@include('admin.layout.partial.navbar')
@include('admin.layout.partial.sidebar')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        @yield('breadcrumb')
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="dashboard-ecommerce">
                <div class="neo-page-wrapper">
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
</div>
@include('admin.layout.partial.footer')
