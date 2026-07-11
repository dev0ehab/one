<div class="neo-index-filter">
    <button type="button" class="btn-searchTable neo-filter-toggle" aria-expanded="false" aria-controls="neo-filter-panel">
        <span class="neo-filter-toggle__icon" aria-hidden="true">
            <i class="feather icon-sliders"></i>
        </span>
        <span class="neo-filter-toggle__label">{{ __('admin.filter') }}</span>
        <span class="neo-filter-toggle__chevron" aria-hidden="true">
            <i class="feather icon-chevron-down"></i>
        </span>
    </button>

    <div class="searchTable neo-filter-panel" id="neo-filter-panel">
        <div class="forma">
            <div class="neo-filter-panel__head">
                <h3 class="neo-filter-panel__title">{{ __('admin.filter_according') }}</h3>
                <button type="button" class="neo-filter-panel__close btnClose" aria-label="{{ __('admin.close') }}">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="neo-filter-panel__fields">
                @isset($order)
                    <div class="form-group">
                        <label for="first-name-column">{{ __('admin.sort_by') }}</label>
                        <div class="controls border">
                            <i class="fa fa-times clean-input"></i>
                            <select name="order" class="form-control search-input select2">
                                <option value>{{ __('admin.choose') }}</option>
                                <option value="ASC">{{ __('admin.ascending') }}</option>
                                <option value="DESC" selected>{{ __('admin.descending') }}</option>
                            </select>
                        </div>
                    </div>
                @endisset

                @if(isset($datefilter) && $datefilter)
                    <div class="form-group">
                        <label for="first-name-column">{{ __('admin.beginning_date') }}</label>
                        <div class="controls border" id="start">
                            <input type="date" name="created_at_min" class="form-control search-input" data-input>
                            <i class="fa fa-times clear-input" data-clear></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first-name-column">{{ __('admin.end_date') }}</label>
                        <div class="controls border" id="end">
                            <input type="date" name="created_at_max" class="form-control search-input" data-input>
                            <i class="fa fa-times clear-input" data-clear></i>
                        </div>
                    </div>
                @endif

                @isset($searchArray)
                    @foreach ($searchArray as $key => $value)
                        <div class="form-group">
                            <label for="first-name-column">{{ $value['input_name'] }}</label>
                            <div class="controls border">
                                <i class="fa fa-times clean-input"></i>
                                @if ($value['input_type'] == 'text')
                                    <input type="text" name="{{ $key }}" class="form-control search-input"
                                        placeholder="{{ __('site.write') . $value['input_name'] }}">
                                @elseif ($value['input_type'] == 'select')
                                    <select name="{{ $key }}" class="form-control search-input select2">
                                        <option value>{{ __('admin.choose') }}</option>
                                        @foreach ($value['rows'] as $row)
                                            <option value="{{ $row['id'] }}" @selected(isset(request('searchArray')[$key]) && request('searchArray')[$key] == $row['id'])>
                                                {{ isset($value['row_name']) ? $row[$value['row_name']] : $row['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif ($value['input_type'] == 'date')
                                    <input type="date" name="{{ $key }}" class="form-control search-input"
                                        placeholder="{{ __('site.write') . $value['input_name'] }}">
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</div>
