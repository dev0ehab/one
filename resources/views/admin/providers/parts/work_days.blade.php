<div class="content-body">
    <!-- Statistics Cards -->
    @if($row->workDays && $row->workDays->count() > 0)
        @php
            $totalWorkDays = $row->workDays->count();
            $uniqueDays = $row->workDays->pluck('day')->unique()->count();
            $totalHours = 0;

            foreach($row->workDays as $workDay) {
                $start = strtotime($workDay->start_at);
                $end = strtotime($workDay->end_at);
                $hours = ($end - $start) / 3600;
                $totalHours += $hours;
            }

            $avgHoursPerDay = $uniqueDays > 0 ? $totalHours / $uniqueDays : 0;
        @endphp

        <div class="row mb-4">
            <!-- Total Work Periods -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="avatar-initial bg-primary rounded">
                                    <i class="fa fa-clock text-white"></i>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="fw-bold d-block mb-1">{{ __('admin.total_work_periods') }}</span>
                            <h3 class="card-title mb-2">{{ $totalWorkDays }}</h3>
                            <small class="text-success fw-bold">
                                <i class="fa fa-arrow-up"></i> {{ __('admin.time_period') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Days -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="avatar-initial bg-success rounded">
                                    <i class="fa fa-calendar text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="fw-bold d-block mb-1">{{ __('admin.working_days') }}</span>
                            <h3 class="card-title mb-2">{{ $uniqueDays }}</h3>
                            <small class="text-success fw-bold">
                                <i class="fa fa-arrow-up"></i> {{ __('admin.out_of_7_days') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Hours -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="avatar-initial bg-info rounded">
                                    <i class="fa fa-hourglass-half text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="fw-bold d-block mb-1">{{ __('admin.total_hours') }}</span>
                            <h3 class="card-title mb-2">{{ number_format($totalHours, 1) }}</h3>
                            <small class="text-info fw-bold">
                                <i class="fa fa-clock"></i> {{ __('admin.hours_weekly') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Hours per Day -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="avatar-initial bg-warning rounded">
                                    <i class="fa fa-chart-line text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <span class="fw-bold d-block mb-1">{{ __('admin.average_hours_daily') }}</span>
                            <h3 class="card-title mb-2">{{ number_format($avgHoursPerDay, 1) }}</h3>
                            <small class="text-warning fw-bold">
                                <i class="fa fa-chart-bar"></i> {{ __('admin.hours_daily') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Work Days Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('admin.work_days') }}</h4>
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

                        @if($row->workDays && $row->workDays->count() > 0)
                            @php
                                // ترتيب الأيام حسب ترتيب الأسبوع
                                $dayOrder = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

                                // ترتيب workDays حسب اليوم ثم حسب وقت البداية
                                $sortedWorkDays = $row->workDays->sort(function($a, $b) use ($dayOrder) {
                                    $dayA = array_search($a->day, $dayOrder);
                                    $dayB = array_search($b->day, $dayOrder);

                                    if ($dayA === $dayB) {
                                        return strtotime($a->start_at) - strtotime($b->start_at);
                                    }

                                    return $dayA - $dayB;
                                })->values();
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><i class="fa fa-calendar"></i> {{ __('admin.day') }}</th>
                                            <th><i class="fa fa-play-circle text-success"></i> {{ __('admin.start_time') }}</th>
                                            <th><i class="fa fa-stop-circle text-danger"></i> {{ __('admin.end_time') }}</th>
                                            <th><i class="fa fa-clock"></i> {{ __('admin.duration') }}</th>
                                            <th><i class="fa fa-tag"></i> {{ __('admin.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sortedWorkDays as $workDay)
                                            @php
                                                $start = strtotime($workDay->start_at);
                                                $end = strtotime($workDay->end_at);
                                                $duration = ($end - $start) / 3600; // ساعات
                                                $startTime = date('H:i', $start);
                                                $endTime = date('H:i', $end);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong class="text-primary">{{ __('admin.' . $workDay->day) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">{{ $startTime }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-danger">{{ $endTime }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ number_format($duration, 1) }} {{ __('admin.hour') }}</span>
                                                </td>
                                                <td>
                                                    @if(($workDay->getTranslations('name')['ar'] ?? '-') !== '-' || ($workDay->getTranslations('name')['en'] ?? '-') !== '-')
                                                        <div class="small">
                                                            @if(($workDay->getTranslations('name')['ar'] ?? '-') !== '-')
                                                                <div><strong>{{ __('admin.arabic') }}:</strong> <span class="text-primary">{{ $workDay->getTranslations('name')['ar'] }}</span></div>
                                                            @endif
                                                            @if(($workDay->getTranslations('name')['en'] ?? '-') !== '-')
                                                                <div><strong>{{ __('admin.english') }}:</strong> <span class="text-secondary">{{ $workDay->getTranslations('name')['en'] }}</span></div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge badge-light">{{ __('admin.default') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="alert alert-light border flex-grow-1 mr-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-info-circle text-info fa-lg mr-2"></i>
                                                <div>
                                                    <strong>{{ __('admin.note') }}:</strong> {{ __('admin.work_days_note') }}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWorkDayModal">
                                            <i class="fa fa-plus"></i> إضافة معاد
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">{{ __('admin.no_work_days_defined') }}</h4>
                                <p class="text-muted">{{ __('admin.no_work_days_description') }}</p>
                                <button class="btn btn-primary" disabled>
                                    <i class="fa fa-plus"></i> {{ __('admin.add_work_days') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
