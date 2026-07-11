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

            <x-admin.buttons extrabuttons="true" addbutton="{{ route('admin.clients.create') }}"
                deletebutton="{{ route('admin.clients.deleteAll') }}"
            >
                <x-slot name="extrabuttonsdiv">
                    <a type="button" data-toggle="modal" data-target="#notify"
                        class="btn neo-action-btn neo-action-btn--notify waves-effect waves-light notify" data-id="all">
                        <i class="feather icon-bell" aria-hidden="true"></i>
                        <span>{{ __('admin.send_notification') }}</span>
                    </a>
                    <a class="btn neo-action-btn neo-action-btn--export waves-effect waves-light"
                        href="{{ url(route('admin.master-export', 'Client')) }}">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        <span>{{ __('admin.export') }}</span>
                    </a>
                </x-slot>
            </x-admin.buttons>

            <x-admin.filter datefilter="true" order="true" :searchArray="[
                'name' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.name'),
                ],
                'phone' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.phone'),
                ],
                'email' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.email'),
                ],
                'is_blocked' => [
                    'input_type' => 'select',
                    'rows' => [
                        '1' => [
                            'name' => 'محظور',
                            'id' => 1,
                        ],
                        '2' => [
                            'name' => 'غير محظور',
                            'id' => 0,
                        ],
                    ],
                    'input_name' => __('admin.ban_status'),
                ],
                'mainLocation->city->id' => [
                    'input_type' => 'select',
                    'rows' => \App\Models\City::with('country')->get()->map(function($city) {
                        return [
                            'name' => $city->getTranslations('name')[app()->getLocale()] ?? $city->name . ' (' . ($city->country->getTranslations('name')[app()->getLocale()] ?? $city->country->name) . ')',
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
    @include('admin.shared.filter_js', ['index_route' => url('admin/clients')])
    {{-- delete one user script --}}
    {{-- notify users model --}}
    <x-admin.NotifyAll route="{{ route('admin.clients.notify') }}" />
    {{-- notify users model --}}
@endsection

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
    @include('admin.shared.deleteAll')
    @include('admin.shared.deleteOne')
    @include('admin.shared.filter_js', ['index_route' => url('admin/clients')])
    @include('admin.shared.notify')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.block_user', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('admin.clients.block') }}',
                    method: 'post',
                    data: {
                        id: $(this).data('id')
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            ).attr('disable', true)
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        setTimeout(function() {
                            getData()
                        }, 1000);
                    },
                });

            });

            $(document).on('click', '.toggle_landing', function(e) {
                e.preventDefault();
                var $btn = $(this);
                $.ajax({
                    url: '{{ route('admin.clients.toggleLanding') }}',
                    method: 'post',
                    data: {
                        id: $btn.data('id')
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disable', true);
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        setTimeout(function() {
                            getData()
                        }, 1000);
                    },
                });
            });
        });
    </script>
@endsection
