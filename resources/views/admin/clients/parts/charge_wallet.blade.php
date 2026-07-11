<div class="card border-left-success">
    <div class="card-header d-flex justify-content-between">
        <h4>{{ __('admin.add_or_deduct_balance') }}</h4>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>{{ __('admin.current_balance') }}:</strong>
            <span class="text-success font-weight-bold available_balance">
                {{ round($row->wallet?->balance ?? 0, 2) . ' ' . __('site.currency') }}
            </span>
        </div>
        
        <form class="updateBalance parsley form-horizontal"
            action="{{ route('admin.clients.updateBalance', ['id' => $row->id]) }}" method="POST"
            enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row">
                <div class="controls col-8">
                    <input type="number" name="balance" min="1"  id="balance" class="form-control"
                           step="any"
                        placeholder="{{  __('admin.amount') }}" required
                        data-parsley-required-message="{{__('admin.this_field_is_required')}}"
                        data-parsley-min-message="{{__('validation.min.numeric', ['attribute' => __('admin.amount'), 'min' => 1])}}">
                    <div class="text-danger mt-1 balance-error" style="display:none;"></div>
                </div>

                <div class="col-4">
                    <select name="type" id="" class="form-control" required>
                        <option value="0">{{ __('admin.charge') }}</option>
                        <option value="1">{{ __('admin.debt') }}</option>
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button type="submit"
                    class="submit-button btn  btn-labeled btn-labeled-right ml-auto legitRipple btn-primary mt-3"> <i
                        class="feather icon-navigation"></i>{{ __('admin.send') }}</button>
            </div>
        </form>
    </div>
</div>
