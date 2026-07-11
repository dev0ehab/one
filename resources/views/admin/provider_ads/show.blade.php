@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection

@section('content')
<div class="content-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">تفاصيل الإعلان #{{ $ad->id }}</h4>
            <a href="{{ route('admin.provider-ads.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="feather icon-arrow-right"></i> {{ __('admin.back') }}
            </a>
        </div>
        <div class="card-body">
            <div class="row">

                {{-- Provider info --}}
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">{{ __('admin.provider') }}</label>
                    <p>
                        @if($ad->provider)
                            <img src="{{ $ad->provider->image }}" width="35" height="35"
                                 class="rounded-circle mr-1" alt="">
                            {{ $ad->provider->trade_name }}
                        @else —
                        @endif
                    </p>
                </div>

                {{-- Package --}}
                @if($ad->subscription?->package)
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">الباقة</label>
                    <p>{{ $ad->subscription->package->name }}</p>
                </div>
                @endif

                {{-- Title --}}
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">عنوان الإعلان</label>
                    <p>{{ $ad->title }}</p>
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">{{ __('admin.approval_status') }}</label>
                    <p>
                        @if($ad->status === 'approved')
                            <span class="badge badge-success">{{ __('admin.accepted') }}</span>
                        @elseif($ad->status === 'pending')
                            <span class="badge badge-warning">{{ __('admin.pending') }}</span>
                        @else
                            <span class="badge badge-danger">{{ __('admin.rejected') }}</span>
                        @endif
                    </p>
                </div>

                {{-- Reject reason --}}
                @if($ad->reject_reason)
                <div class="col-md-12 mb-2">
                    <label class="font-weight-bold">{{ __('admin.rejection_reason') }}</label>
                    <p class="text-danger">{{ $ad->reject_reason }}</p>
                </div>
                @endif

                {{-- Description --}}
                @if($ad->description)
                <div class="col-md-12 mb-2">
                    <label class="font-weight-bold">الوصف</label>
                    <p>{{ $ad->description }}</p>
                </div>
                @endif

                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">تاريخ البداية</label>
                    <p>{{ $ad->start_at?->format('Y-m-d H:i') ?? '—' }}</p>
                </div>

                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">{{ __('admin.price') }}</label>
                    <p>{{ number_format((float) $ad->price, 2) }}</p>
                </div>

                {{-- Dates --}}
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">{{ __('admin.created_at') }}</label>
                    <p>{{ $ad->created_at->format('Y-m-d H:i') }}</p>
                </div>

                @if($ad->approved_at)
                <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">تاريخ الموافقة</label>
                    <p>{{ $ad->approved_at->format('Y-m-d H:i') }}</p>
                </div>
                @endif

                {{-- Images --}}
                @if($ad->images->isNotEmpty())
                <div class="col-md-12 mb-3">
                    <label class="font-weight-bold">{{ __('admin.images') }}</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($ad->images as $img)
                            <img src="{{ $img->image }}" width="120" height="120"
                                 class="rounded border" style="object-fit:cover;" alt="">
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Action buttons (only if pending) --}}
                @if($ad->status === 'pending')
                <div class="col-md-12 mt-3">
                    <button class="btn btn-success approve-ad mr-1" data-id="{{ $ad->id }}">
                        <i class="la la-check"></i> {{ __('admin.accept') }}
                    </button>
                    <button class="btn btn-danger reject-ad" data-id="{{ $ad->id }}">
                        <i class="la la-close"></i> {{ __('admin.reject') }}
                    </button>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.approve-ad', function(e) {
        e.preventDefault();
        var btn = $(this), id = btn.data('id');
        Swal.fire({
            title: '{{ __('admin.accept') }}', type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success', cancelButtonClass: 'btn btn-secondary ml-1',
            confirmButtonText: '{{ __('admin.accept') }}', cancelButtonText: '{{ __('admin.cancel') }}',
            buttonsStyling: false,
        }).then(function(r) {
            if (!r.value) return;
            $.post('/admin/provider-ads/' + id + '/approve', { _token: '{{ csrf_token() }}' })
             .done(function(res) {
                Swal.fire({ type: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                setTimeout(function() { window.location.reload(); }, 1300);
             })
             .fail(function(xhr) {
                Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}' });
             });
        });
    });

    $(document).on('click', '.reject-ad', function(e) {
        e.preventDefault();
        var btn = $(this), id = btn.data('id');
        Swal.fire({
            title: '{{ __('admin.reject') }}', type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __('admin.reject') }}', confirmButtonClass: 'btn btn-danger',
            cancelButtonText: '{{ __('admin.cancel') }}', cancelButtonClass: 'btn btn-secondary ml-1',
            buttonsStyling: false,
            input: 'textarea',
            inputPlaceholder: '{{ __("admin.rejection_reason") }}',
            inputValidator: function(v) { if (!v) return '{{ __("admin.rejection_reason_required") }}'; }
        }).then(function(r) {
            if (!r.value) return;
            $.post('/admin/provider-ads/' + id + '/reject', { _token: '{{ csrf_token() }}', reason: r.value })
             .done(function(res) {
                Swal.fire({ type: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                setTimeout(function() { window.location.reload(); }, 1300);
             })
             .fail(function(xhr) {
                Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}' });
             });
        });
    });
});
</script>
@endsection
