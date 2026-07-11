@php
    // حساب الإحصائيات للبروفايدر
    $startDate = now()->startOfMonth();
    $endDate = now()->endOfMonth();

    $reservationsQuery = $row->reservations()->whereBetween('created_at', [$startDate, $endDate]);
    $reservations = $reservationsQuery->get();

    $totalCompletedRevenue = $reservations->where('status', \App\Enums\ReservationStatusEnum::COMPLETED)->sum('total');
    $totalSoldPackages = $reservations->where('status', \App\Enums\ReservationStatusEnum::COMPLETED)->count();
    $totalCustomers = $reservations->where('status', \App\Enums\ReservationStatusEnum::COMPLETED)->unique('client_id')->count();
    $totalCompletedReservations = $reservations->where('status', \App\Enums\ReservationStatusEnum::COMPLETED)->count();
    $totalCancelledReservations = $reservations->where('status', \App\Enums\ReservationStatusEnum::CANCELLED)->count();

    // إحصائيات إضافية
    $totalPackages = $row->packages->count();
    $totalDoctors = $row->doctors->count();
    $totalRates = $row->rates->count();
    $avgRating = $row->avg_rate ?? 0;

    // إحصائيات هذا الشهر الماضي للمقارنة
    $lastMonthStart = now()->subMonth()->startOfMonth();
    $lastMonthEnd = now()->subMonth()->endOfMonth();

    $lastMonthReservations = $row->reservations()->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->get();
    $lastMonthRevenue = $lastMonthReservations->where('status', \App\Enums\ReservationStatusEnum::COMPLETED)->sum('total');
@endphp

<div class="content-body">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Revenue -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-success rounded">
                                <i class="fa fa-dollar-sign text-white"></i>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cardOpt1">
                                <a class="dropdown-item" href="#">{{ __('admin.view_details') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.total_revenue') }}</span>
                        <h3 class="card-title mb-2">{{ number_format($totalCompletedRevenue, 2) }}</h3>
                        <small class="text-success fw-bold">
                            <i class="fa fa-arrow-up"></i> {{ $lastMonthRevenue > 0 ? number_format((($totalCompletedRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0 }}% {{ __('admin.from_last_month') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sold Packages -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-primary rounded">
                                <i class="fa fa-shopping-cart text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.packages_sold') }}</span>
                        <h3 class="card-title mb-2">{{ $totalSoldPackages }}</h3>
                        <small class="text-primary fw-bold">
                            <i class="fa fa-box"></i> {{ __('admin.packages_this_month') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-info rounded">
                                <i class="fa fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.total_customers') }}</span>
                        <h3 class="card-title mb-2">{{ $totalCustomers }}</h3>
                        <small class="text-info fw-bold">
                            <i class="fa fa-user"></i> {{ __('admin.unique_customer') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Reservations -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-warning rounded">
                                <i class="fa fa-check-circle text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.completed_reservations') }}</span>
                        <h3 class="card-title mb-2">{{ $totalCompletedReservations }}</h3>
                        <small class="text-warning fw-bold">
                            <i class="fa fa-calendar-check"></i> {{ __('admin.completed_reservation') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="row mb-4">
        <!-- Cancelled Reservations -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-danger rounded">
                                <i class="fa fa-times-circle text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.cancelled_reservations') }}</span>
                        <h3 class="card-title mb-2">{{ $totalCancelledReservations }}</h3>
                        <small class="text-danger fw-bold">
                            <i class="fa fa-ban"></i> {{ __('admin.cancelled_reservation') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Packages -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-secondary rounded">
                                <i class="fa fa-box text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.total_packages') }}</span>
                        <h3 class="card-title mb-2">{{ $totalPackages }}</h3>
                        <small class="text-secondary fw-bold">
                            <i class="fa fa-list"></i> {{ __('admin.available_package') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Doctors -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-dark rounded">
                                <i class="fa fa-user-md text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.total_doctors') }}</span>
                        <h3 class="card-title mb-2">{{ $totalDoctors }}</h3>
                        <small class="text-dark fw-bold">
                            <i class="fa fa-stethoscope"></i> {{ __('admin.doctor') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial bg-success rounded">
                                <i class="fa fa-star text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <span class="fw-bold d-block mb-1">{{ __('admin.average_rating') }}</span>
                        <h3 class="card-title mb-2">{{ number_format($avgRating, 1) }}</h3>
                        <div class="mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $avgRating >= $i ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <small class="text-success fw-bold">
                            <i class="fa fa-thumbs-up"></i> {{ __('admin.from_ratings') }} {{ $totalRates }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart Placeholder -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('admin.monthly_performance') }}</h4>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="reload" class="text-muted"><i class="fa fa-refresh"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fa fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.monthly_performance_chart') }}</h5>
                            <p class="text-muted">{{ __('admin.monthly_performance_description') }}</p>
                            <div class="row text-center mt-3">
                                <div class="col-md-3">
                                    <div class="border p-2 rounded">
                                        <h4 class="text-primary">{{ number_format($totalCompletedRevenue, 0) }}</h4>
                                        <small>{{ __('admin.revenue_this_month') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-2 rounded">
                                        <h4 class="text-success">{{ $totalCompletedReservations }}</h4>
                                        <small>{{ __('admin.completed_reservations_this_month') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-2 rounded">
                                        <h4 class="text-info">{{ $totalCustomers }}</h4>
                                        <small>{{ __('admin.new_customers_this_month') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border p-2 rounded">
                                        <h4 class="text-warning">{{ number_format($avgRating, 1) }}</h4>
                                        <small>{{ __('admin.average_rating_this_month') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Information -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-light border">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fa fa-calendar-check text-primary"></i>
                        <strong>{{ __('admin.report_period') }}:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                    </div>
                    <div>
                        <small class="text-muted">{{ __('admin.last_update') }}: {{ now()->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
