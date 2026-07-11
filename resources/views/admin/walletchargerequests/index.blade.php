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

            <x-admin.buttons extrabuttons="true">
                <x-slot name="extrabuttonsdiv">
                    <a class="btn neo-action-btn neo-action-btn--export waves-effect waves-light"
                        href="{{ url(route('admin.master-export', 'WalletChargeRequest')) }}">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        <span>{{ __('admin.export') }}</span>
                    </a>
                </x-slot>
            </x-admin.buttons>

            <x-admin.filter datefilter="true" order="true" :searchArray="[
                'amount' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.amount'),
                ],
                'status' => [
                    'input_type' => 'select',
                    'rows' => [
                        '1' => [
                            'name' => __('admin.pending'),
                            'id' => 'pending',
                        ],
                        '2' => [
                            'name' => __('admin.approved'),
                            'id' => 'approved',
                        ],
                        '3' => [
                            'name' => __('admin.rejected'),
                            'id' => 'rejected',
                        ],
                    ],
                    'input_name' => __('admin.status'),
                ],
            ]" />

        </div>

        <div class="table_content_append">

        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
    @include('admin.shared.deleteAll')
    @include('admin.shared.deleteOne')
    @include('admin.shared.filter_js', ['index_route' => url('admin/wallet-charge-requests')])
    <script>
        $(document).ready(function() {
            // Approve request
            $(document).on('click', '.approve-request', function(e) {
                e.preventDefault();
                var requestId = $(this).data('id');
                var button = $(this);
                
                Swal.fire({
                    title: "{{ __('admin.approve_request') }}",
                    text: "{{ __('admin.enter_amount_to_approve') }}",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('admin.approve') }}',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonText: '{{ __('admin.cancel') }}',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    input: 'number',
                    inputPlaceholder: '{{ __("admin.amount") }}',
                    inputAttributes: {
                        min: '1',
                        step: 'any'
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return '{{ __("admin.amount_required") }}';
                        }
                        if (parseFloat(value) <= 0) {
                            return '{{ __("admin.amount_must_be_greater_than_zero") }}';
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route("admin.wallet-charge-requests.approve", ":id") }}'.replace(':id', requestId),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                amount: result.value
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
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
                                });
                                setTimeout(function() {
                                    getData();
                                }, 1000);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'error',
                                    title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                button.html('{{ __("admin.approve") }}').prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // Reject request
            $(document).on('click', '.reject-request', function(e) {
                e.preventDefault();
                var requestId = $(this).data('id');
                var button = $(this);
                
                Swal.fire({
                    title: "{{ __('admin.reject_request') }}",
                    text: "{{ __('admin.are_you_sure_reject') }}",
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
                    inputValidator: (value) => {
                        if (!value) {
                            return '{{ __("admin.rejection_reason_required") }}';
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route("admin.wallet-charge-requests.reject", ":id") }}'.replace(':id', requestId),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                reason: result.value
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
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
                                });
                                setTimeout(function() {
                                    getData();
                                }, 1000);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'error',
                                    title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                button.html('{{ __("admin.reject") }}').prop('disabled', false);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
