@extends('admin.layout.master')

{{-- extra css files --}}
@section('css')
<link rel="stylesheet" type="text/css"
    href="{{asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@endsection
{{-- extra css files --}}  

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('admin.add')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{route('admin.clients.store')}}" class="store form-horizontal parsley"
                            novalidate>
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="imgMontg col-12 text-center">
                                            <div class="dropBox">
                                                <div class="textCenter">
                                                    <div class="imagesUploadBlock">
                                                        <label class="uploadImg">
                                                            <span><i class="feather icon-image"></i></span>
                                                            <input type="file" accept="image/*" name="image"
                                                                class="imageUploader">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{__('admin.name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="{{__('admin.write_the_name')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{__('admin.phone_number')}}</label>
                                            <div class="controls">
                                                <input type="number" name="phone" class="form-control"
                                                    placeholder="{{__('admin.enter_phone_number')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}"
                                                    data-parsley-number-message="{{__('admin.the_phone_number_ must_not_have_charachters_or_symbol')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{__('admin.country_code')}}</label>
                                            <div class="controls">
                                                <input type="number" name="country_code" class="form-control"
                                                    placeholder="{{__('admin.country_code')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}"
                                                    data-parsley-number-message="{{__('admin.the_phone_number_ must_not_have_charachters_or_symbol')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.email')}}</label>
                                            <div class="controls">
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="{{__('admin.enter_the_email')}}"
                                                    data-parsley-type-message="{{__('admin.email_formula_is_incorrect')}}"
                                                    required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country_id">{{ __('admin.country') }}</label>
                                            <div class="controls">
                                                <select name="country_id" id="country_id" class="select2 form-control" required
                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                    <option value="">{{ __('admin.select_country') }}</option>
                                                    @foreach(\App\Models\Country::all() as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->getTranslations('name')[app()->getLocale()] ?? $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city_id">{{ __('admin.city') }}</label>
                                            <div class="controls">
                                                <select name="city_id" id="city_id" class="select2 form-control" required
                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                    <option value="">{{ __('admin.select_city') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lat">{{ __('admin.latitude') }}</label>
                                            <div class="controls">
                                                <input type="number" step="any" name="lat" id="lat" class="form-control"
                                                    placeholder="{{__('admin.enter_latitude')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lng">{{ __('admin.longitude') }}</label>
                                            <div class="controls">
                                                <input type="number" step="any" name="lng" id="lng" class="form-control"
                                                    placeholder="{{__('admin.enter_longitude')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="map_desc">{{ __('admin.map_description') }}</label>
                                            <div class="controls">
                                                <input type="text" name="map_desc" id="map_desc" class="form-control"
                                                    placeholder="{{__('admin.enter_map_description')}}" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}"
                                                    maxlength="191">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{__('admin.status')}}</label>
                                            <div class="controls">
                                                <select name="is_blocked" class="select2 form-control" required
                                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}">
                                                    <option value>{{__('admin.select_the_blocking_status')}}</option>
                                                    <option value="1">{{__('admin.prohibited')}}</option>
                                                    <option value="0">{{__('admin.unspoken')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <button type="submit"
                                            class="btn btn-primary mr-1 mb-1 submit_button">{{__('admin.add')}}</button>
                                        <a href="{{ url()->previous() }}" type="reset"
                                            class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>

{{-- show selected image script --}}
@include('admin.shared.addImage')
{{-- show selected image script --}}
@include('admin.shared.addImage')
{{-- show selected image script --}}

{{-- submit add form script --}}
@include('admin.shared.submitAddForm')
{{-- submit add form script --}}

<script>
    $(document).ready(function() {
        // Load all cities on page load
        var allCities = @json(\App\Models\City::with('country')->get()->map(function($city) {
            return [
                'id' => $city->id,
                'name' => $city->getTranslations('name')[app()->getLocale()] ?? $city->name,
                'country_id' => $city->country_id
            ];
        }));
        
        // Load cities when country is selected
        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            var citySelect = $('#city_id');
            
            // Clear existing options
            citySelect.html('<option value="">{{ __('admin.select_city') }}</option>');
            
            if (countryId) {
                // Filter cities by country
                var filteredCities = allCities.filter(function(city) {
                    return city.country_id == countryId;
                });
                
                // Add filtered cities to select
                $.each(filteredCities, function(index, city) {
                    citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                });
            }
        });
    });
</script>
@endsection
