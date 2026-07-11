@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/index_page.css') }}">
@endsection

@section('content')
<div class="content-body">

    {{-- Status tabs --}}
    <div class="mb-1 d-flex align-items-center flex-wrap gap-1">
        <a href="{{ url('admin/provider-ads') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
            {{ __('admin.all') }}
        </a>
        <a href="{{ url('admin/provider-ads?status=pending') }}"
           class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="la la-clock-o"></i> {{ __('admin.pending') }}
            @php $pendingCount = \App\Models\ProviderAd::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="badge badge-light ml-1">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ url('admin/provider-ads?status=approved') }}"
           class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="la la-check"></i> {{ __('admin.accepted') }}
        </a>
        <a href="{{ url('admin/provider-ads?status=rejected') }}"
           class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            <i class="la la-close"></i> {{ __('admin.rejected') }}
        </a>
    </div>

    <div class="table_content_append"></div>
</div>

@include('admin.shared.filter_js', ['index_route' => url('admin/provider-ads' . (request('status') ? '?status=' . request('status') : ''))])
@endsection

@section('js')
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
@include('admin.shared.filter_js', ['index_route' => url('admin/provider-ads' . (request('status') ? '?status=' . request('status') : ''))])

<script>
$(document).ready(function() {

    // Approve ad
    $(document).on('click', '.approve-ad', function(e) {
        e.preventDefault();
        var btn = $(this);
        var id  = btn.data('id');

        Swal.fire({
            title: '{{ __('admin.accept') }}',
            text: '{{ __('admin.are_you_sure') }}',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-secondary ml-1',
            confirmButtonText: '{{ __('admin.accept') }}',
            cancelButtonText: '{{ __('admin.cancel') }}',
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '/admin/provider-ads/' + id + '/approve',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    beforeSend: function() { btn.prop('disabled', true); },
                    success: function(response) {
                        Swal.fire({ type: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                        setTimeout(function() { getData(); }, 1200);
                    },
                    error: function(xhr) {
                        Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });

    // Reject ad
    $(document).on('click', '.reject-ad', function(e) {
        e.preventDefault();
        var btn = $(this);
        var id  = btn.data('id');

        Swal.fire({
            title: '{{ __('admin.reject') }}',
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
                    url: '/admin/provider-ads/' + id + '/reject',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', reason: result.value },
                    dataType: 'json',
                    beforeSend: function() { btn.prop('disabled', true); },
                    success: function(response) {
                        Swal.fire({ type: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                        setTimeout(function() { getData(); }, 1200);
                    },
                    error: function(xhr) {
                        Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });

});
</script>
@endsection
