@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/index_page.css') }}">
@endsection

@section('content')
    <div class="content-body">

        <div class="neo-index-toolbar mb-2">

            <x-admin.buttons extrabuttons="true" addbutton="{{ route('admin.providers.create') }}"
                deletebutton="{{ route('admin.providers.deleteAll') }}"
            >
                <x-slot name="extrabuttonsdiv">
                    <a type="button" data-toggle="modal" data-target="#notify"
                        class="btn neo-action-btn neo-action-btn--notify waves-effect waves-light notify" data-id="all">
                        <i class="feather icon-bell" aria-hidden="true"></i>
                        <span>{{ __('admin.send_notification') }}</span>
                    </a>
                    <a class="btn neo-action-btn neo-action-btn--export waves-effect waves-light"
                        href="{{ url(route('admin.master-export', 'Provider')) }}">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        <span>{{ __('admin.export') }}</span>
                    </a>
                </x-slot>
            </x-admin.buttons>

            <x-admin.filter datefilter="true" order="true" :searchArray="[
                'trade_name' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.trade_name'),
                ],
                'phone' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.phone'),
                ],
                'is_approved' => [
                    'input_type' => 'select',
                    'rows' => [
                        'pending' => ['name' => __('admin.pending'),  'id' => 'pending'],
                        '1'       => ['name' => __('admin.accepted'), 'id' => 1],
                        '0'       => ['name' => __('admin.rejected'), 'id' => 0],
                    ],
                    'input_name' => __('admin.approval_status'),
                ],
                'is_blocked' => [
                    'input_type' => 'select',
                    'rows' => [
                        '1' => ['name' => __('admin.prohibited'), 'id' => 1],
                        '2' => ['name' => __('admin.unspoken'),   'id' => 0],
                    ],
                    'input_name' => __('admin.ban_status'),
                ],
                'city_id' => [
                    'input_type' => 'select',
                    'rows' => \App\Models\City::with('country')->get()->map(function($city) {
                        return [
                            'name' => ($city->getTranslations('name')[app()->getLocale()] ?? $city->name)
                                      . ' (' . ($city->country->getTranslations('name')[app()->getLocale()] ?? $city->country->name) . ')',
                            'id' => $city->id,
                        ];
                    })->toArray(),
                    'input_name' => __('admin.city'),
                ],
            ]" />

        </div>

        <div class="table_content_append">

        </div>
    </div>

    {{-- delete all script --}}
        @include('admin.shared.deleteAll')
    {{-- delete all script --}}
    {{-- delete one user script --}}
    @include('admin.shared.deleteOne')
    {{-- delete one user script --}}
    {{-- delete one user script --}}
    @include('admin.shared.filter_js', ['index_route' => url('admin/providers')])
    {{-- delete one user script --}}
    {{-- notify users model --}}
    <x-admin.NotifyAll route="{{ route('admin.providers.notify') }}" />
    {{-- notify users model --}}
@endsection

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
    @include('admin.shared.deleteAll')
    @include('admin.shared.deleteOne')
    @include('admin.shared.filter_js', ['index_route' => url('admin/providers')])
    @include('admin.shared.notify')
    <script>
        $(document).ready(function() {

            // Block / Unblock
            $(document).on('click', '.block_user', function(e) {
                e.preventDefault();
                var btn = $(this);
                $.ajax({
                    url: '{{ route('admin.providers.block') }}',
                    method: 'post',
                    data: { id: btn.data('id') },
                    dataType: 'json',
                    beforeSend: function() {
                        btn.html('<span class="spinner-border spinner-border-sm"></span>').attr('disabled', true);
                    },
                    success: function(response) {
                        Swal.fire({ position: 'top-start', type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(function() { getData(); }, 1000);
                    },
                });
            });

            // Accept provider
            $(document).on('click', '.accept-provider', function(e) {
                e.preventDefault();
                var btn = $(this);
                var id  = btn.data('id');
                $.ajax({
                    url: '{{ route('admin.providers.accept') }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', id: id },
                    dataType: 'json',
                    beforeSend: function() {
                        btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
                    },
                    success: function(response) {
                        Swal.fire({ position: 'top-start', type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(function() { getData(); }, 1200);
                    },
                    error: function(xhr) {
                        Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                        btn.html('<i class="la la-check"></i> {{ __('admin.accept') }}').prop('disabled', false);
                    }
                });
            });

            // Reject provider (with reason prompt)
            $(document).on('click', '.reject-provider', function(e) {
                e.preventDefault();
                var btn = $(this);
                var id  = btn.data('id');
                Swal.fire({
                    title: '{{ __('admin.reject_request') }}',
                    text: '{{ __('admin.are_you_sure_reject') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('admin.reject') }}',
                    confirmButtonClass: 'btn btn-danger',
                    cancelButtonText: '{{ __('admin.cancel') }}',
                    cancelButtonClass: 'btn btn-secondary ml-1',
                    buttonsStyling: false,
                    input: 'textarea',
                    inputPlaceholder: '{{ __("admin.rejection_reason") }}',
                    inputValidator: function(value) {
                        if (!value) return '{{ __("admin.rejection_reason_required") }}';
                    }
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('admin.providers.reject') }}',
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}', id: id, reason: result.value },
                            dataType: 'json',
                            beforeSend: function() {
                                btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
                            },
                            success: function(response) {
                                Swal.fire({ position: 'top-start', type: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
                                setTimeout(function() { getData(); }, 1200);
                            },
                            error: function(xhr) {
                                Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                                btn.html('<i class="la la-close"></i> {{ __('admin.reject') }}').prop('disabled', false);
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
