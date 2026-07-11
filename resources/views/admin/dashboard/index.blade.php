@extends('admin.layout.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/charts/apexcharts.css') }}">
@endsection
@section('content')

    @php
        $metricCards = [
            [
                'variant' => 'clients',
                'icon' => 'icon-users',
                'label' => __('admin.clients'),
                'value' => $totalClients ?? 0,
                'recent' => $recentClients ?? 0,
                'hint' => __('admin.dash_metric_clients_desc'),
                'route' => 'admin.clients.index',
            ],
            [
                'variant' => 'providers',
                'icon' => 'icon-briefcase',
                'label' => __('admin.providers'),
                'value' => $totalProviders ?? 0,
                'recent' => $recentProviders ?? 0,
                'hint' => __('admin.dash_metric_providers_desc'),
                'route' => 'admin.providers.index',
            ],
            [
                'variant' => 'contacts',
                'icon' => 'icon-message-square',
                'label' => __('admin.contacts'),
                'value' => $totalContacts ?? 0,
                'recent' => $recentContacts ?? 0,
                'hint' => __('admin.dash_metric_contacts_desc'),
                'route' => 'admin.all_contacts',
            ],
            [
                'variant' => 'notifications',
                'icon' => 'icon-bell',
                'label' => __('admin.notifications'),
                'value' => $totalNotifications ?? 0,
                'recent' => null,
                'hint' => __('admin.dash_metric_notifications_desc'),
                'route' => 'admin.notifications.index',
            ],
            [
                'variant' => 'admins',
                'icon' => 'icon-shield',
                'label' => __('admin.admins'),
                'value' => $totalAdmins ?? 0,
                'recent' => null,
                'hint' => __('admin.dash_metric_admins_desc'),
                'route' => 'admin.admins.index',
            ],
            [
                'variant' => 'cities',
                'icon' => 'icon-map-pin',
                'label' => __('admin.cities'),
                'value' => $totalCities ?? 0,
                'recent' => null,
                'hint' => __('admin.dash_metric_cities_desc'),
                'route' => 'admin.cities.index',
            ],
            [
                'variant' => 'roles',
                'icon' => 'icon-lock',
                'label' => __('admin.roles'),
                'value' => $totalRoles ?? 0,
                'recent' => null,
                'hint' => __('admin.dash_metric_roles_desc'),
                'route' => 'admin.roles.index',
            ],
        ];
    @endphp

    <div class="neo-dash-hero row mb-4">
        <div class="col-12 mb-3">
            <div class="neo-dash-hero-inner">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <p class="neo-dash-kicker mb-0">{{ auth('admin')->user()->name }}</p>
                        <h1 class="neo-dash-title mb-1">{{ __('routes.main_page') }}</h1>
                        <p class="neo-dash-date mb-0">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                </div>

                <p class="neo-dash-snapshot-hint mb-3">{{ __('admin.dash_platform_snapshot') }}</p>

                <div class="neo-metric-grid">
                    @foreach ($metricCards as $card)
                        @php
                            $hasRoute = \Illuminate\Support\Facades\Route::has($card['route']);
                            $tag = $hasRoute ? 'a' : 'div';
                            $href = $hasRoute ? route($card['route']) : null;
                        @endphp
                        <{{ $tag }}
                            @if ($hasRoute) href="{{ $href }}" @endif
                            class="neo-metric-card neo-metric-card--{{ $card['variant'] }}"
                            @if (!$hasRoute) tabindex="0" @endif
                        >
                            <div class="neo-metric-card-body">
                                <div class="neo-metric-icon">
                                    <i class="feather {{ $card['icon'] }}"></i>
                                </div>
                                <div class="neo-metric-content">
                                    <div class="neo-metric-label">{{ $card['label'] }}</div>
                                    <div class="neo-metric-value">{{ number_format($card['value']) }}</div>
                                    @if ($card['recent'] !== null)
                                        <div class="neo-metric-meta">
                                            <span class="neo-metric-badge {{ $card['recent'] > 0 ? 'neo-metric-badge--up' : 'neo-metric-badge--muted' }}">
                                                @if ($card['recent'] > 0)
                                                    +{{ number_format($card['recent']) }}
                                                @else
                                                    0
                                                @endif
                                                {{ __('admin.dash_this_week') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <span class="neo-metric-spark" aria-hidden="true"></span>
                            </div>
                            <div class="neo-metric-hover-panel">
                                <p class="neo-metric-hint">{{ $card['hint'] }}</p>
                                @if ($hasRoute)
                                    <span class="neo-metric-cta">
                                        {{ __('admin.view_section') }}
                                        <i class="feather icon-arrow-{{ lang() == 'ar' ? 'left' : 'right' }}"></i>
                                    </span>
                                @endif
                            </div>
                        </{{ $tag }}>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(count($menus) > 0)
        <h2 class="neo-dash-quick-title">{{ __('admin.dashboard') }}</h2>
        <div class="row mb-4">
            @foreach($menus as $menu)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                    <a href="{{ $menu['url'] }}" class="neo-quick-card">
                        <div class="neo-quick-card-inner">
                            <div class="d-flex align-items-center gap-3">
                                <div class="neo-quick-card-icon">
                                    <i class="feather {!! $menu['icon'] !!}"></i>
                                </div>
                                <div>
                                    <div class="neo-quick-card-count">{{ $menu['count'] }}</div>
                                    <div class="neo-quick-card-label">{{ $menu['name'] }}</div>
                                </div>
                            </div>
                            <span class="neo-quick-card-arrow">
                                <i class="feather icon-arrow-{{ lang() == 'ar' ? 'left' : 'right' }}"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <div class="neo-charts-section dashboard-section">
        <h2 class="neo-dash-charts-title">{{ __('admin.dash_charts_title') }}</h2>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="neo-chart-card neo-chart-card--tall">
                    <div class="neo-chart-card-header">
                        <div>
                            <h3 class="neo-chart-card-title">{{ __('admin.dash_chart_monthly_activity') }}</h3>
                            <p class="neo-chart-card-subtitle mb-0">{{ __('admin.dash_chart_monthly_activity_desc') }}</p>
                        </div>
                        <div class="neo-chart-legend">
                            <span class="neo-chart-legend-item neo-chart-legend-item--clients">
                                <span class="neo-chart-legend-dot"></span>{{ __('admin.clients') }}
                            </span>
                            <span class="neo-chart-legend-item neo-chart-legend-item--contacts">
                                <span class="neo-chart-legend-dot"></span>{{ __('admin.contacts') }}
                            </span>
                        </div>
                    </div>
                    <div id="neo-chart-monthly-activity" class="neo-chart-canvas">
                        <div class="neo-chart-skeleton"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-3">
                <div class="neo-chart-card neo-chart-card--tall">
                    <div class="neo-chart-card-header">
                        <div>
                            <h3 class="neo-chart-card-title">{{ __('admin.dash_chart_new_users') }}</h3>
                            <p class="neo-chart-card-subtitle mb-0">{{ __('admin.dash_chart_new_users_desc') }}</p>
                        </div>
                    </div>
                    <div id="neo-chart-new-users" class="neo-chart-canvas">
                        <div class="neo-chart-skeleton"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-3">
                <div class="neo-chart-card neo-chart-card--tall">
                    <div class="neo-chart-card-header">
                        <div>
                            <h3 class="neo-chart-card-title">{{ __('admin.dash_chart_distribution') }}</h3>
                            <p class="neo-chart-card-subtitle mb-0">{{ __('admin.dash_chart_distribution_desc') }}</p>
                        </div>
                    </div>
                    <div id="neo-chart-distribution" class="neo-chart-canvas neo-chart-canvas--donut">
                        <div class="neo-chart-skeleton"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="neo-chart-card">
                    <div class="neo-chart-card-header">
                        <div>
                            <h3 class="neo-chart-card-title">{{ __('admin.dash_chart_weekly_registrations') }}</h3>
                            <p class="neo-chart-card-subtitle mb-0">{{ __('admin.dash_chart_weekly_registrations_desc') }}</p>
                        </div>
                    </div>
                    <div id="neo-chart-weekly" class="neo-chart-canvas neo-chart-canvas--compact">
                        <div class="neo-chart-skeleton"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="neo-chart-card">
                    <div class="neo-chart-card-header">
                        <div>
                            <h3 class="neo-chart-card-title">{{ __('admin.dash_chart_system_overview') }}</h3>
                            <p class="neo-chart-card-subtitle mb-0">{{ __('admin.dash_chart_system_overview_desc') }}</p>
                        </div>
                    </div>
                    <div id="neo-chart-system" class="neo-chart-canvas neo-chart-canvas--compact">
                        <div class="neo-chart-skeleton"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/charts_functions.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof ApexCharts === 'undefined') {
                return;
            }

            var isDark = document.body.classList.contains('dark-layout');
            var isRtl = document.documentElement.getAttribute('dir') === 'rtl';
            var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.06)';
            var labelColor = isDark ? '#a0a0a0' : '#64748b';
            var fontFamily = 'Inter, Tajawal, sans-serif';
            var months = @json($chartMonths ?? []);
            var noDataText = @json(__('admin.dash_chart_no_data'));

            function mountChart(selector, options) {
                var el = document.querySelector(selector);
                if (!el) {
                    return null;
                }
                el.innerHTML = '';
                var chart = new ApexCharts(el, options);
                chart.render();
                return chart;
            }

            function baseChart(height) {
                return {
                    height: height,
                    toolbar: { show: false },
                    fontFamily: fontFamily,
                    animations: { enabled: true, easing: 'easeinout', speed: 550 }
                };
            }

            mountChart('#neo-chart-monthly-activity', {
                chart: Object.assign({ type: 'area' }, baseChart(300)),
                series: [
                    { name: @json(__('admin.clients')), data: @json($chartClients ?? []) },
                    { name: @json(__('admin.contacts')), data: @json($chartContacts ?? []) }
                ],
                colors: ['#475569', '#0ea5e9'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.06, stops: [0, 90, 100] }
                },
                stroke: { curve: 'smooth', width: 2.5 },
                dataLabels: { enabled: false },
                grid: { borderColor: gridColor, strokeDashArray: 4 },
                xaxis: {
                    categories: months,
                    labels: { style: { colors: labelColor, fontWeight: 600 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: labelColor },
                        formatter: function (val) { return Math.round(val); }
                    },
                    min: 0
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: isRtl ? 'left' : 'right',
                    labels: { colors: labelColor }
                },
                markers: { size: 0, hover: { size: 6 } },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    shared: true,
                    intersect: false,
                    y: { formatter: function (val) { return Math.round(val).toLocaleString(); } }
                },
                noData: { text: noDataText }
            });

            mountChart('#neo-chart-new-users', {
                chart: Object.assign({ type: 'bar' }, baseChart(300)),
                series: [
                    { name: @json(__('admin.clients')), data: @json($chartClients ?? []) },
                    { name: @json(__('admin.providers')), data: @json($chartProviders ?? []) }
                ],
                colors: ['#475569', '#0ea5e9'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '48%',
                        borderRadius: 6,
                        dataLabels: { position: 'top' }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                grid: { borderColor: gridColor, strokeDashArray: 4 },
                xaxis: {
                    categories: months,
                    labels: { style: { colors: labelColor, fontWeight: 600 } }
                },
                yaxis: {
                    labels: {
                        style: { colors: labelColor },
                        formatter: function (val) { return Math.round(val); }
                    },
                    min: 0
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: isRtl ? 'left' : 'right',
                    labels: { colors: labelColor }
                },
                tooltip: { theme: isDark ? 'dark' : 'light', shared: true, intersect: false },
                noData: { text: noDataText }
            });

            var pieLabels = [
                @json(__('admin.clients')),
                @json(__('admin.providers')),
                @json(__('admin.admins'))
            ];
            var pieSeries = @json(array_values($pieDistribution ?? []));

            mountChart('#neo-chart-distribution', {
                chart: Object.assign({ type: 'donut' }, baseChart(280)),
                series: pieSeries,
                labels: pieLabels,
                colors: ['#475569', '#0ea5e9', '#10b981'],
                stroke: { width: 2, colors: [isDark ? '#1e1e1e' : '#fff'] },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return Math.round(val) + '%'; },
                    style: { fontSize: '11px', fontWeight: 700 }
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    labels: { colors: labelColor }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '62%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: @json(__('admin.dash_chart_total')),
                                    color: labelColor,
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce(function (a, b) {
                                            return a + b;
                                        }, 0).toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: { formatter: function (val) { return val.toLocaleString(); } }
                },
                noData: { text: noDataText }
            });

            mountChart('#neo-chart-weekly', {
                chart: Object.assign({ type: 'line' }, baseChart(260)),
                series: [
                    { name: @json(__('admin.clients')), data: @json($chartWeekClients ?? []) },
                    { name: @json(__('admin.providers')), data: @json($chartWeekProviders ?? []) },
                    { name: @json(__('admin.contacts')), data: @json($chartWeekContacts ?? []) }
                ],
                colors: ['#475569', '#0ea5e9', '#8b5cf6'],
                stroke: { curve: 'smooth', width: 2.5 },
                markers: { size: 4, strokeWidth: 2, hover: { size: 7 } },
                grid: { borderColor: gridColor, strokeDashArray: 4 },
                xaxis: {
                    categories: @json($chartWeekDays ?? []),
                    labels: { style: { colors: labelColor, fontWeight: 600 } }
                },
                yaxis: {
                    labels: {
                        style: { colors: labelColor },
                        formatter: function (val) { return Math.round(val); }
                    },
                    min: 0
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: isRtl ? 'left' : 'right',
                    labels: { colors: labelColor }
                },
                tooltip: { theme: isDark ? 'dark' : 'light', shared: true, intersect: false },
                noData: { text: noDataText }
            });

            mountChart('#neo-chart-system', {
                chart: Object.assign({ type: 'bar' }, baseChart(260)),
                series: [{
                    name: @json(__('admin.statistics')),
                    data: @json(array_values($systemOverview ?? []))
                }],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6,
                        barHeight: '55%',
                        distributed: true
                    }
                },
                colors: ['#8b5cf6', '#f59e0b', '#ec4899', '#6366f1'],
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return Math.round(val).toLocaleString(); },
                    style: { fontSize: '11px', fontWeight: 700, colors: ['#fff'] },
                    offsetX: isRtl ? -8 : 8
                },
                xaxis: {
                    categories: [
                        @json(__('admin.contacts')),
                        @json(__('admin.notifications')),
                        @json(__('admin.cities')),
                        @json(__('admin.roles'))
                    ],
                    labels: { style: { colors: labelColor } }
                },
                yaxis: {
                    labels: { style: { colors: labelColor, fontWeight: 600 } }
                },
                grid: { borderColor: gridColor, strokeDashArray: 4 },
                legend: { show: false },
                tooltip: { theme: isDark ? 'dark' : 'light' },
                noData: { text: noDataText }
            });
        });
    </script>
@endsection
