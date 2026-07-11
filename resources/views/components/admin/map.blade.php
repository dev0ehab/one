@props(['model' => null])

@isset($model)
    @if ($model->lat && $model->lng && $model->map_desc)
        <div class="form-group mt-3">
            <label for="address">
                {{ __('admin.map_desc') }}
            </label>
            <input type="text" id="address-input" name="map_desc" class="form-control map-input"
                value="{{ $model->map_desc }}">
            <input type="hidden" name="lat" id="address-latitude" value="{{ $model->lat }}" />
            <input type="hidden" name="lng" id="address-longitude" value="{{ $model->lng }}" />
        </div>
        <div id="address-map-container" style="width:100%;height:400px; ">
            <div style="width: 100%; height: 100%" id="address-map"></div>
        </div>
    @else
        <div class="form-group mt-3">
            <label for="address">
                {{ __('admin.map_desc') }}
            </label>
            <input type="text" id="address-input" name="map_desc" class="form-control map-input">
            <input type="hidden" name="lat" id="address-latitude" value="0" />
            <input type="hidden" name="lng" id="address-longitude" value="0" />
        </div>
        <div id="address-map-container" style="width:100%;height:400px; ">
            <div style="width: 100%; height: 100%" id="address-map"></div>
        </div>
    @endif
@else
    <div class="form-group mt-3">
        <label for="address">
            {{ __('admin.map_desc') }}
        </label>
        <input type="text" id="address-input" name="map_desc" class="form-control map-input">
        <input type="hidden" name="lat" id="address-latitude" value="0" />
        <input type="hidden" name="lng" id="address-longitude" value="0" />
    </div>
    <div id="address-map-container" style="width:100%;height:400px; ">
        <div style="width: 100%; height: 100%" id="address-map"></div>
    </div>
@endisset


@push('js')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ googleMapKey() }}&libraries=places,marker&callback=initialize"
        async defer></script>

    <script src="{{ asset('js/map.js') }}"></script>
@endpush