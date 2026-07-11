@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('admin.edit') }} — {{ $package->name }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.packages.update', ['id' => $package->id]) }}" class="store form-horizontal parsley" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="name-{{ $lang }}">{{ __('site.name_' . $lang) }}</label>
                                                <div class="controls">
                                                    <input type="text" name="name[{{ $lang }}]" id="name-{{ $lang }}" class="form-control"
                                                        value="{{ $package->getTranslation('name', $lang) }}" required
                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="description-{{ $lang }}">{{ __('site.description_' . $lang) }}</label>
                                                <div class="controls">
                                                    <textarea name="description[{{ $lang }}]" id="description-{{ $lang }}" class="form-control" rows="3">{{ $package->getTranslation('description', $lang) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="price">{{ __('admin.package_price') }}</label>
                                            <div class="controls">
                                                <input type="number" step="0.01" min="0" name="price" id="price" class="form-control"
                                                    value="{{ $package->price }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="ads_count">{{ __('admin.ads_count') }}</label>
                                            <div class="controls">
                                                <input type="number" min="1" name="ads_count" id="ads_count" class="form-control"
                                                    value="{{ $package->ads_count }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="duration_days">{{ __('admin.duration_days') }}</label>
                                            <div class="controls">
                                                <input type="number" min="1" name="duration_days" id="duration_days" class="form-control"
                                                    value="{{ $package->duration_days }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="sort">{{ __('admin.sort_order') }}</label>
                                            <div class="controls">
                                                <input type="number" min="0" name="sort" id="sort" class="form-control"
                                                    value="{{ $package->sort }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <x-admin.switch name="is_active" :model="$package" :label="true" :ajax="false" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-center mt-3">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.update') }}</button>
                                    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
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

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
    @include('admin.shared.submitEditForm')
@endsection
