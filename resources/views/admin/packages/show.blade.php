@extends('admin.layout.master')

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ __('admin.package_details') }}</h4>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('admin.back') }}</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="store form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>{{ __('site.name_' . $lang) }}</label>
                                                <input type="text" class="form-control" value="{{ $package->getTranslation('name', $lang) }}" disabled>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>{{ __('site.description_' . $lang) }}</label>
                                                <textarea class="form-control" rows="3" disabled>{{ $package->getTranslation('description', $lang) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.package_price') }}</label>
                                            <input type="text" class="form-control" value="{{ number_format($package->price, 2) }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.ads_count') }}</label>
                                            <input type="text" class="form-control" value="{{ $package->ads_count }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.duration_days') }}</label>
                                            <input type="text" class="form-control" value="{{ $package->duration_days }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.sort_order') }}</label>
                                            <input type="text" class="form-control" value="{{ $package->sort }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.status') }}</label>
                                            <input type="text" class="form-control"
                                                value="{{ $package->is_active ? __('admin.active') : __('admin.inactive') }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('admin.package_subscriptions_count') }}</label>
                                            <input type="text" class="form-control" value="{{ $package->subscriptions()->count() }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-center mt-3">
                                    <a href="{{ route('admin.packages.edit', ['id' => $package->id]) }}" class="btn btn-primary mr-1">
                                        <i class="feather icon-edit"></i> {{ __('admin.edit') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
