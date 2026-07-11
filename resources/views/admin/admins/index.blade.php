@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/index_page.css')}}">
@endsection



@section('content')
    <div class="content-body">
        <div class="neo-index-toolbar mb-2">
            <x-admin.buttons addbutton="{{ route('admin.admins.create') }}"
                deletebutton="{{ route('admin.admins.deleteAll') }}" extrabuttons="true">
                <x-slot name="extrabuttonsdiv">
                    <a class="btn neo-action-btn neo-action-btn--export waves-effect waves-light"
                        href="{{ url(route('admin.master-export', 'Admin')) }}">
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

        ]" />
        </div>
        <div class="table_content_append">

        </div>
    </div>
@endsection

@section('js')

    <script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>
    @include('admin.shared.deleteAll')
    @include('admin.shared.deleteOne')
    @include('admin.shared.filter_js', ['index_route' => url('admin/admins')])
    <script>
        $(document).ready(function () {
            $(document).on('click', '.block_user', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{url("admin/admins/block")}}',
                    method: 'post',
                    data: { id: $(this).data('id') },
                    dataType: 'json',
                    beforeSend: function () {
                        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disable', true)
                    },
                    success: function (response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        setTimeout(function () {
                            window.location.reload()
                        }, 1000);
                    },
                });

            });
        });
    </script>
@endsection