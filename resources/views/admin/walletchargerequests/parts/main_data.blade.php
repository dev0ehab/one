<div class="card store">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.request_id') }}</label>
                    <div class="controls">
                        <input type="text" name="id" disabled value="{{ $row->id }}" class="form-control"
                            placeholder="{{ __('admin.request_id') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.user_type') }}</label>
                    <div class="controls">
                        <input type="text" name="user_type" disabled 
                            value="@if($row->wallet->walletable_type == 'App\Models\Client'){{ __('admin.client') }}@elseif($row->wallet->walletable_type == 'App\Models\Provider'){{ __('admin.provider') }}@else{{ __('admin.unknown') }}@endif" 
                            class="form-control" placeholder="{{ __('admin.user_type') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.user_name') }}</label>
                    <div class="controls">
                        <input type="text" name="user_name" disabled value="{{ $row->wallet->walletable->name ?? __('admin.unknown') }}" class="form-control"
                            placeholder="{{ __('admin.user_name') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.amount') }}</label>
                    <div class="controls">
                        <input type="text" name="amount" disabled value="{{ $row->amount }} {{ __('site.currency') }}" class="form-control"
                            placeholder="{{ __('admin.amount') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.status') }}</label>
                    <div class="controls">
                        <input type="text" name="status" disabled value="{{ __('admin.' . $row->status) }}" class="form-control"
                            placeholder="{{ __('admin.status') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.created_at') }}</label>
                    <div class="controls">
                        <input type="text" name="created_at" disabled value="{{ $row->created_at->format('Y-m-d H:i:s') }}" class="form-control"
                            placeholder="{{ __('admin.created_at') }}">
                    </div>
                </div>
            </div>

            @if($row->reason)
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.reason') }}</label>
                    <div class="controls">
                        <textarea name="reason" disabled class="form-control" rows="3" placeholder="{{ __('admin.reason') }}">{{ $row->reason }}</textarea>
                    </div>
                </div>
            </div>
            @endif

            @if($row->image)
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.payment_receipt') }}</label>
                    <div class="controls">
                        <img src="{{ $row->image }}" alt="{{ __('admin.payment_receipt') }}" class="img-fluid" style="max-width: 300px;">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
