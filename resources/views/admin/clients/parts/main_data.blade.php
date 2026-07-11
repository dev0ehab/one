<div class="card store">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-12 mb-3">
                <div class="form-group text-center">
                    <label for="image-column">{{ __('admin.image') }}</label>
                    <div class="controls">
                        <img src="{{ $row->image }}" alt="{{ $row->name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.name') }}</label>
                    <div class="controls">
                        <input type="text" name="name" disabled value="{{ $row->name }}" class="form-control"
                            placeholder="{{ __('admin.write_the_name') }}" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.phone_number') }}</label>
                    <div class="controls">
                        <input type="text" name="phone" disabled value="{{ $row->phone }}" class="form-control"
                            placeholder="{{ __('admin.enter_phone_number') }}" required
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.email') }}</label>
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
                    <label for="points-column">{{ __('admin.points') }}</label>
                    <div class="controls d-flex align-items-center">
                        <input type="number" name="points" id="points-input" value="{{ $row->points }}" class="form-control"
                            placeholder="{{ __('admin.points') }}" step="1" min="0">
                        <button type="button" class="btn btn-primary ml-2" id="update-points-btn">
                            <i class="feather icon-save"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="wallet-balance-column">{{ __('admin.wallet_balance') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ round($row->wallet?->balance ?? 0, 2) . ' ' . __('site.currency') }}" class="form-control bg-light"
                            placeholder="{{ __('admin.wallet_balance') }}">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-6">
                <div class="form-group">
                    <label for="first-name-column">{{ __('admin.status') }}</label>
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
                    <label for="activation-column">{{ __('admin.activation') }}</label>
                    <div class="controls">
                        <select name="is_active" disabled class="select2 form-control">
                            <option value>{{ __('admin.select_activation_status') }}
                            </option>
                            <option {{ $row->is_active == 1 ? 'selected' : '' }}
                                value="1">{{ __('admin.activate') }}</option>
                            <option {{ $row->is_active == 0 ? 'selected' : '' }}
                                value="0">{{ __('admin.dis_activate') }}</option>
                        </select>
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

            @if($row->mainLocation)
            <div class="col-md-12 col-12">
                <hr>
                <h5>{{ __('admin.location_information') }}</h5>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="country-column">{{ __('admin.country') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->mainLocation->country->getTranslations('name')[app()->getLocale()] ?? $row->mainLocation->country->name }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="city-column">{{ __('admin.city') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->mainLocation->city->getTranslations('name')[app()->getLocale()] ?? $row->mainLocation->city->name }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="lat-column">{{ __('admin.latitude') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->mainLocation->lat }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="lng-column">{{ __('admin.longitude') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->mainLocation->lng }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="map-desc-column">{{ __('admin.map_description') }}</label>
                    <div class="controls">
                        <input type="text" disabled value="{{ $row->mainLocation->map_desc }}" class="form-control">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
