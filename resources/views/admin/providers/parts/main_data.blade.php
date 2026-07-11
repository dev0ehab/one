<div class="card store">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-12 mb-3">
                <div class="form-group text-center">
                    <label for="image-column">{{ __('admin.image') }}</label>
                    <div class="controls">
                        <img src="{{ $row->image }}" alt="{{ $row->trade_name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="trade-name-column">{{ __('admin.trade_name') }}</label>
                    <div class="controls">
                        <input type="text" name="trade_name" disabled value="{{ $row->trade_name }}" class="form-control"
                            placeholder="{{ __('admin.trade_name') }}" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="owner-name-column">{{ __('admin.owner_name') }}</label>
                    <div class="controls">
                        <input type="text" name="owner_name" disabled value="{{ $row->owner_name }}" class="form-control"
                            placeholder="{{ __('admin.owner_name') }}" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="phone-column">{{ __('admin.phone_number') }}</label>
                    <div class="controls">
                        <input type="text" name="phone" disabled value="{{ $row->phone }}" class="form-control"
                            placeholder="{{ __('admin.enter_phone_number') }}" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="email-column">{{ __('admin.email') }}</label>
                    <div class="controls">
                        <input type="email" name="email" disabled value="{{ $row->email ?? '' }}" class="form-control"
                            placeholder="{{ __('admin.enter_the_email') }}"
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="country-code-column">{{ __('admin.country_code') }}</label>
                    <div class="controls">
                        <input type="text" name="country_code" disabled value="{{ $row->country_code }}" class="form-control"
                            placeholder="{{ __('admin.country_code') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="status-column">{{ __('admin.status') }}</label>
                    <div class="controls">
                        <select name="is_blocked" disabled class="select2 form-control" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                            <option value>{{ __('admin.select_the_blocking_status') }}
                            </option>
                            <option {{ $row->is_blocked == 1 ? 'selected' : '' }}
                                value="1">{{ __('admin.prohibited') }}</option>
                            <option {{ $row->is_blocked == 0 ? 'selected' : '' }}
                                value="0">{{ __('admin.unspoken') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="approval-status-column">{{ __('admin.approval_status') }}</label>
                    <div class="controls">
                        @if($row->is_approved == 1)
                            <span class="btn btn-sm round btn-outline-success btn-block">
                                {{ __('admin.accepted') }} <i class="la la-check font-medium-2"></i>
                            </span>
                        @elseif(is_null($row->is_approved))
                            <span class="btn btn-sm round btn-outline-warning btn-block">
                                {{ __('admin.pending') }} <i class="la la-clock-o font-medium-2"></i>
                            </span>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-success accept-provider" data-id="{{ $row->id }}">
                                    <i class="la la-check"></i> {{ __('admin.accept') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-danger reject-provider" data-id="{{ $row->id }}">
                                    <i class="la la-close"></i> {{ __('admin.reject') }}
                                </button>
                            </div>
                        @elseif($row->is_approved == 0)
                            <span class="btn btn-sm round btn-outline-danger btn-block">
                                {{ __('admin.rejected') }} <i class="la la-close font-medium-2"></i>
                            </span>
                            @if($row->rejection_reason)
                                <div class="mt-2">
                                    <small class="text-danger"><strong>{{ __('admin.rejection_reason') }}:</strong> {{ $row->rejection_reason }}</small>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="notify-column">{{ __('admin.notification_status') }}</label>
                    <div class="controls">
                        <select name="is_notify" disabled class="select2 form-control">
                            <option value>{{ __('admin.select_notification_status') }}
                            </option>
                            <option {{ $row->is_notify == 1 ? 'selected' : '' }}
                                value="1">{{ __('admin.enabled') }}</option>
                            <option {{ $row->is_notify == 0 ? 'selected' : '' }}
                                value="0">{{ __('admin.disabled') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="active-column">{{ __('admin.active_status') }}</label>
                    <div class="controls">
                        <select name="is_active" disabled class="select2 form-control">
                            <option {{ $row->is_active == 1 ? 'selected' : '' }}
                                value="1">{{ __('admin.active') }}</option>
                            <option {{ $row->is_active == 0 ? 'selected' : '' }}
                                value="0">{{ __('admin.inactive') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="rating-column">{{ __('admin.average_rating') }}</label>
                    <div class="controls">
                        <div class="input-group">
                            <input type="text" disabled value="{{ number_format($row->avg_rate ?? 0, 1) }}" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ ($row->avg_rate ?? 0) >= $i ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($row->city)
            <div class="col-md-12 col-12">
                <hr>
                <h5>{{ __('admin.location_information') }}</h5>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="city-column">{{ __('admin.city') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->city->getTranslations('name')[app()->getLocale()] ?? $row->city->name }}" class="form-control">
                    </div>
                </div>
            </div>

<label for="lat-column">{{ __('admin.latitude') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->lat }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="lng-column">{{ __('admin.longitude') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->lng }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="map-desc-column">{{ __('admin.map_description') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->map_desc }}" class="form-control">
                    </div>
                </div>
            </div>
            @endif

@if($row->category)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="category-column">{{ __('admin.category') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->category->getTranslations('name')[app()->getLocale()] ?? $row->category->name }}" class="form-control">
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-12 col-12">
                <hr>
                <h5>{{ __('admin.bank_information') }}</h5>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="bank-name-column">{{ __('admin.bank_name') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->bank_name }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="account-name-column">{{ __('admin.account_name') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->account_name }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="account-num-column">{{ __('admin.account_num') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->account_num }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="iban-column">{{ __('admin.iban') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->iban }}" class="form-control">
                    </div>
                </div>
            </div>

<!-- Additional Information -->
            <div class="col-md-12 col-12">
                <hr>
                <h5>{{ __('admin.additional_information') }}</h5>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="created-at">{{ __('admin.created_at') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-' }}" class="form-control">
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="is-open">{{ __('admin.is_currently_open') }}</label>
                    <div class="controls">
                        <span class="badge {{ $row->is_open ? 'badge-success' : 'badge-danger' }} badge-lg">
                            {{ $row->is_open ? __('admin.open') : __('admin.closed') }}
                        </span>
                        @if($row->today_work_hours)
                            <small class="d-block mt-1">{{ __('admin.today_hours') }}: {{ $row->today_work_hours }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
