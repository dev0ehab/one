@if($row->status == 'pending')
<div class="card">
    <div class="card-header">
        <h4>{{ __('admin.request_actions') }}</h4>
    </div>
    <div class="card-body">
        <div class="d-flex flex-column">
            <button type="button" class="btn btn-success mb-2 approve-request" data-id="{{ $row->id }}">
                <i class="feather icon-check mr-1"></i>
                {{ __('admin.approve_request') }}
            </button>
            
            <button type="button" class="btn btn-danger reject-request" data-id="{{ $row->id }}">
                <i class="feather icon-x mr-1"></i>
                {{ __('admin.reject_request') }}
            </button>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-header">
        <h4>{{ __('admin.request_status') }}</h4>
    </div>
    <div class="card-body">
        <div class="text-center">
            <span class="badge badge-lg 
                @if($row->status == 'approved') badge-success
                @else badge-danger @endif" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ __('admin.' . $row->status) }}
            </span>
            
            @if($row->status == 'rejected' && $row->reason)
                <div class="mt-3">
                    <h6>{{ __('admin.rejection_reason') }}:</h6>
                    <p class="text-muted">{{ $row->reason }}</p>
                </div>
            @endif
            
            @if($row->status == 'approved' && $row->walletTransaction)
                <div class="mt-3">
                    <h6>{{ __('admin.transaction_id') }}:</h6>
                    <p class="text-muted">{{ $row->walletTransaction->id }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
