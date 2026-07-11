



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
                    <h4 class="card-title">{{__('admin.edit_provider')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{route('admin.providers.update', $provider->id)}}" class="store form-horizontal parsley"
                            novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <!-- Provider Images -->
                                    <div class="col-12">
                                        <div class="imgMontg col-12 text-center">
                                            <div class="row">


                                                {{-- <div class="col-md-6">
                                                    <label>{{__('admin.image')}}</label>
                                                    <div class="dropBox">
                                                        <div class="textCenter">
                                                            <div class="imagesUploadBlock">
                                                                <label class="uploadImg">
                                                                    <span><i class="feather icon-image"></i></span>
                                                                    <input type="file" accept="image/*" name="image"
                                                                        class="imageUploader">
                                                                </label>
                                                                @if($provider->image)
                                                                    <img src="{{ $provider->image }}" alt="" style="max-width: 100px; max-height: 100px;">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>{{__('admin.owner_image')}}</label>
                                                    <div class="dropBox">
                                                        <div class="textCenter">
                                                            <div class="imagesUploadBlock">
                                                                <label class="uploadImg">
                                                                    <span><i class="feather icon-image"></i></span>
                                                                    <input type="file" accept="image/*" name="owner_image"
                                                                        class="imageUploader">
                                                                </label>
                                                                @if($provider->owner_image)
                                                                    <img src="{{ $provider->owner_image }}" alt="" style="max-width: 100px; max-height: 100px;">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                    <x-admin.image name="image" :model="$provider" />
                                                    <x-admin.image name="owner_image" :model="$provider" />
                                                    {{-- <x-admin.image name="commercial_image" :model="$merchant" /> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="trade_name">{{__('admin.trade_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="trade_name" class="form-control"
                                                    value="{{ $provider->trade_name }}"
                                                    placeholder="{{__('admin.trade_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="owner_name">{{__('admin.owner_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="owner_name" class="form-control"
                                                    value="{{ $provider->owner_name }}"
                                                    placeholder="{{__('admin.owner_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone">{{__('admin.phone')}}</label>
                                            <div class="controls">
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ $provider->phone }}"
                                                    placeholder="{{__('admin.phone')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email">{{__('admin.email')}}</label>
                                            <div class="controls">
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ $provider->email }}"
                                                    placeholder="{{__('admin.email')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country_code">{{__('admin.country_code')}}</label>
                                            <div class="controls">
                                                <input type="text" name="country_code" class="form-control"
                                                    value="{{ $provider->country_code }}"
                                                    placeholder="{{__('admin.country_code')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Category and Location -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="category_id">{{__('admin.category')}}</label>
                                            <div class="controls">
                                                <select name="category_id" class="form-control" required>
                                                    <option value="">{{__('admin.select_category')}}</option>
                                                    @php
                                                        $categories = \App\Models\Category::where('type', \App\Enums\CategoryTypeEnum::PROVIDER)
                                                            ->whereNull('parent_id')
                                                            ->get();
                                                    @endphp
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $provider->category_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->getTranslations('name')[app()->getLocale()] ?? $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country_id">{{__('admin.country')}}</label>
                                            <div class="controls">
                                                <select name="country_id" id="country_id" class="form-control" required>
                                                    <option value="">{{__('admin.select_country')}}</option>
                                                    @php
                                                        $countries = \App\Models\Country::all();
                                                    @endphp
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $provider->city && $provider->city->country_id == $country->id ? 'selected' : '' }}>
                                                            {{ $country->getTranslations('name')[app()->getLocale()] ?? $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city_id">{{__('admin.city')}}</label>
                                            <div class="controls">
                                                <select name="city_id" id="city_id" class="form-control" required>
                                                    <option value="">{{__('admin.select_city')}}</option>
                                                    @if($provider->city)
                                                        <option value="{{ $provider->city->id }}" selected>{{ $provider->city->getTranslations('name')[app()->getLocale()] ?? $provider->city->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="address">{{__('admin.address')}}</label>
                                            <div class="controls">
                                                <input type="text" name="address" id="address" class="form-control"
                                                    value="{{ $provider->address ?? '' }}"
                                                    placeholder="{{__('admin.address')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lat">{{__('admin.latitude')}}</label>
                                            <div class="controls">
                                                <input type="text" name="lat" class="form-control"
                                                    value="{{ $provider->lat }}"
                                                    placeholder="{{__('admin.latitude')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lng">{{__('admin.longitude')}}</label>
                                            <div class="controls">
                                                <input type="text" name="lng" class="form-control"
                                                    value="{{ $provider->lng }}"
                                                    placeholder="{{__('admin.longitude')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map_desc">{{__('admin.map_description')}}</label>
                                            <div class="controls">
                                                <textarea name="map_desc" class="form-control" rows="3"
                                                    placeholder="{{__('admin.map_description')}}" required>{{ $provider->map_desc }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="social_phone">{{__('admin.social_phone')}}</label>
                                            <div class="controls">
                                                <input type="text" name="social_phone" class="form-control"
                                                    value="{{ $provider->phone }}"
                                                    placeholder="{{__('admin.social_phone')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="social_email">{{__('admin.social_email')}}</label>
                                            <div class="controls">
                                                <input type="email" name="social_email" class="form-control"
                                                    value="{{ $provider->social_email }}"
                                                    placeholder="{{__('admin.social_email')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp">{{__('admin.whatsapp')}}</label>
                                            <div class="controls">
                                                <input type="text" name="whatsapp" class="form-control"
                                                    value="{{ $provider->phone }}"
                                                    placeholder="{{__('admin.whatsapp')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- License Image -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="license_image">{{__('admin.license_image')}}</label>
                                            <div class="controls">
                                                <input type="file" name="license_image" class="form-control"
                                                    accept="image/*">
                                                @if($provider->license_image)
                                                    <img src="{{ $provider->license_image }}" alt="" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="bank_name">{{__('admin.bank_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="bank_name" class="form-control"
                                                    value="{{ $provider->bank_name }}"
                                                    placeholder="{{__('admin.bank_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="account_name">{{__('admin.account_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="account_name" class="form-control"
                                                    value="{{ $provider->account_name }}"
                                                    placeholder="{{__('admin.account_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="account_num">{{__('admin.account_num')}}</label>
                                            <div class="controls">
                                                <input type="text" name="account_num" class="form-control"
                                                    value="{{ $provider->account_num }}"
                                                    placeholder="{{__('admin.account_num')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="iban">{{__('admin.iban')}}</label>
                                            <div class="controls">
                                                <input type="text" name="iban" class="form-control"
                                                    value="{{ $provider->iban }}"
                                                    placeholder="{{__('admin.iban')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Work Days -->
                                    <div class="col-12">
                                        <hr>
                                        <h5>{{ __('admin.work_days') }}</h5>
                                        <div id="work-days-container">
                                            <!-- Work days form fields -->
                                        </div>
                                        <button type="button" class="btn btn-primary"
                                            id="add-work-day">{{ __('admin.add_work_day') }}</button>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{__('admin.edit')}}</button>
                                        <a href="{{ url()->previous() }}" class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
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
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Day translations
    const dayTranslations = {
        saturday: '{{ __('admin.saturday') }}',
        sunday: '{{ __('admin.sunday') }}',
        monday: '{{ __('admin.monday') }}',
        tuesday: '{{ __('admin.tuesday') }}',
        wednesday: '{{ __('admin.wednesday') }}',
        thursday: '{{ __('admin.thursday') }}',
        friday: '{{ __('admin.friday') }}'
    };

    const deleteText = '{{ __('admin.delete') }}';

    // Work day name placeholders
    const exampleMorningShiftAr = '{{ __('admin.example_morning_shift_ar') }}';
    const exampleMorningShiftEn = '{{ __('admin.example_morning_shift_en') }}';

    // Handle country change
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        var citySelect = $('#city_id');

        if (countryId) {
            // Clear current cities
            citySelect.html('<option value="">جاري التحميل...</option>');

            // Fetch cities for selected country
            $.ajax({
                url: '{{ url("admin/get-cities-by-country") }}',
                method: 'GET',
                data: { country_id: countryId },
                success: function(data) {
                    citySelect.html('<option value="">اختر المدينة</option>');
                    $.each(data, function(key, city) {
                        var selected = city.id == {{ $provider->city ? $provider->city->id : 'null' }} ? 'selected' : '';
                        citySelect.append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                    });
                },
                error: function() {
                    citySelect.html('<option value="">خطأ في تحميل المدن</option>');
                }
            });
        } else {
            citySelect.html('<option value="">اختر المدينة</option>');
        }
    });

    // Handle work days management
    let workDayIndex = 0;

    $('#add-work-day').on('click', function() {
        addWorkDayField();
    });

    function addWorkDayField(day = null, startAt = null, endAt = null, nameAr = null, nameEn = null) {
        const workDayHtml = `
            <div class="work-day-item border rounded p-3 mb-3" data-index="${workDayIndex}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.day') }}</label>
                            <select name="work_days[${workDayIndex}][day]" class="form-control" required>
                                <option value="">{{ __('admin.select_day') }}</option>
                                <option value="saturday" ${day === 'saturday' ? 'selected' : ''}>${dayTranslations.saturday}</option>
                                <option value="sunday" ${day === 'sunday' ? 'selected' : ''}>${dayTranslations.sunday}</option>
                                <option value="monday" ${day === 'monday' ? 'selected' : ''}>${dayTranslations.monday}</option>
                                <option value="tuesday" ${day === 'tuesday' ? 'selected' : ''}>${dayTranslations.tuesday}</option>
                                <option value="wednesday" ${day === 'wednesday' ? 'selected' : ''}>${dayTranslations.wednesday}</option>
                                <option value="thursday" ${day === 'thursday' ? 'selected' : ''}>${dayTranslations.thursday}</option>
                                <option value="friday" ${day === 'friday' ? 'selected' : ''}>${dayTranslations.friday}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.start_time') }}</label>
                            <input type="time" name="work_days[${workDayIndex}][start_at]" class="form-control" value="${startAt || ''}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.end_time') }}</label>
                            <input type="time" name="work_days[${workDayIndex}][end_at]" class="form-control" value="${endAt || ''}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.name') }} ({{ __('admin.arabic') }})</label>
                            <input type="text" name="work_days[${workDayIndex}][name][ar]" class="form-control" value="${nameAr || ''}" placeholder="${exampleMorningShiftAr}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('admin.name') }} ({{ __('admin.english') }})</label>
                            <input type="text" name="work_days[${workDayIndex}][name][en]" class="form-control" value="${nameEn || ''}" placeholder="${exampleMorningShiftEn}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-work-day">
                                <i class="fa fa-trash"></i> ${deleteText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#work-days-container').append(workDayHtml);
        
        // Add validation for the newly added work day
        const $newItem = $('#work-days-container .work-day-item').last();
        const $startAt = $newItem.find('input[name*="[start_at]"]');
        const $endAt = $newItem.find('input[name*="[end_at]"]');
        
        // Validate on change
        $startAt.on('change', function() {
            validateWorkDayTime($(this).closest('.work-day-item'));
        });
        
        $endAt.on('change', function() {
            validateWorkDayTime($(this).closest('.work-day-item'));
        });
        
        workDayIndex++;

        // Update delete buttons visibility
        updateDeleteButtonsVisibility();
    }

    // Function to validate work day time
    function validateWorkDayTime($workDayItem) {
        const $startAt = $workDayItem.find('input[name*="[start_at]"]');
        const $endAt = $workDayItem.find('input[name*="[end_at]"]');
        const $endAtFormGroup = $endAt.closest('.form-group');
        
        // Remove previous error messages
        $endAtFormGroup.find('.text-danger.work-time-error').remove();
        $startAt.removeClass('border-danger');
        $endAt.removeClass('border-danger');
        
        const startTime = $startAt.val();
        const endTime = $endAt.val();
        
        if (startTime && endTime) {
            // Convert time strings to comparable format
            const start = new Date('2000-01-01T' + startTime + ':00');
            const end = new Date('2000-01-01T' + endTime + ':00');
            
            if (end <= start) {
                $endAt.addClass('border-danger');
                $startAt.addClass('border-danger');
                const errorMsg = '{{ __('validation.after', ['attribute' => __('admin.end_time'), 'date' => __('admin.start_time')]) }}';
                $endAtFormGroup.append('<span class="text-danger d-block mt-1 work-time-error">' + errorMsg + '</span>');
                return false;
            }
        }
        
        return true;
    }

    // Validate all work days before form submission
    function validateAllWorkDays() {
        let isValid = true;
        $('.work-day-item').each(function() {
            if (!validateWorkDayTime($(this))) {
                isValid = false;
            }
        });
        return isValid;
    }

    // Add validation to existing work day fields (for dynamically added ones)
    $(document).on('change', 'input[name*="work_days"][name*="start_at"]', function() {
        validateWorkDayTime($(this).closest('.work-day-item'));
    });

    $(document).on('change', 'input[name*="work_days"][name*="end_at"]', function() {
        validateWorkDayTime($(this).closest('.work-day-item'));
    });

    $(document).on('click', '.remove-work-day', function() {
        $(this).closest('.work-day-item').remove();
        updateDeleteButtonsVisibility();
    });

    function updateDeleteButtonsVisibility() {
        const workDayCount = $('.work-day-item').length;
        $('.work-day-item').each(function() {
            const $item = $(this);
            const $deleteBtnContainer = $item.find('.remove-work-day').closest('.col-md-2');

            if (workDayCount <= 1) {
                if ($deleteBtnContainer.length > 0) {
                    $deleteBtnContainer.remove();
                }
            } else {
                if ($deleteBtnContainer.length === 0) {
                    const $nameEnCol = $item.find('.col-md-2').last();
                    $nameEnCol.after(`
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block remove-work-day">
                                    <i class="fa fa-trash"></i> ${deleteText}
                                </button>
                            </div>
                        </div>
                    `);
                }
            }
        });
    }

    // Load existing data on page load
    @if($provider->workDays && $provider->workDays->count() > 0)
        @foreach($provider->workDays as $workDay)
            @php
                $startAt = \Carbon\Carbon::parse($workDay->start_at)->format('H:i');
                $endAt = \Carbon\Carbon::parse($workDay->end_at)->format('H:i');
                $nameAr = $workDay->getTranslations('name')['ar'] ?? '';
                $nameEn = $workDay->getTranslations('name')['en'] ?? '';
            @endphp
            addWorkDayField('{{ $workDay->day }}', '{{ $startAt }}', '{{ $endAt }}', '{{ addslashes($nameAr) }}', '{{ addslashes($nameEn) }}');
        @endforeach
    @else
        // Add one empty work day field if none exist
        setTimeout(function() {
            addWorkDayField();
        }, 500);
    @endif

    // Update delete buttons visibility after initial load
    setTimeout(function() {
        updateDeleteButtonsVisibility();
        // Validate existing work days after loading
        $('.work-day-item').each(function() {
            validateWorkDayTime($(this));
        });
    }, 1000);

    // Validate work days before form submission
    $(document).on('submit', '.store', function(e) {
        if (!validateAllWorkDays()) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            // Scroll to first error
            const $firstError = $('.work-time-error').first();
            if ($firstError.length > 0) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
            }
            
            return false;
        }
    });
});
</script>
@include('admin.shared.addImage')

@include('admin.shared.submitEditForm')

{{-- Custom validation error handler for work_days --}}
<script>
$(document).ready(function() {
    // Intercept AJAX errors to handle work_days validation
    $(document).ajaxError(function(event, xhr, settings) {
        // Only handle errors from the provider update form
        if (settings.url && settings.url.indexOf('providers') >= 0 && xhr.responseJSON && xhr.responseJSON.errors) {
            setTimeout(function() {
                $.each(xhr.responseJSON.errors, function(key, value) {
                    if (key.indexOf('work_days') >= 0) {
                        var errorMessage = Array.isArray(value) ? value[0] : value;
                        var parts = key.split('.');
                        
                        // Remove previous work_days errors
                        $('.work-day-error').remove();
                        $('.work-days-general-error').remove();
                        
                        // Handle work_days errors
                        if (parts.length >= 3) {
                            // Specific field error (e.g., work_days.0.end_at)
                            var index = parseInt(parts[1]);
                            var fieldName = parts[2];
                            
                            // Find the work day item by index
                            var $workDayItems = $('#work-days-container .work-day-item');
                            if ($workDayItems.length > index) {
                                var $workDayItem = $workDayItems.eq(index);
                                var $field = $workDayItem.find('input[name*="[' + fieldName + ']"], select[name*="[' + fieldName + ']"]');
                                
                                if ($field.length > 0) {
                                    $field.addClass('border-danger');
                                    var $formGroup = $field.closest('.form-group');
                                    if ($formGroup.length > 0) {
                                        $formGroup.find('.text-danger.work-day-error').remove();
                                        $formGroup.append('<span class="text-danger d-block mt-1 work-day-error">' + errorMessage + '</span>');
                                    }
                                }
                            }
                        } else if (key === 'work_days') {
                            // General work_days error
                            var $workDaysContainer = $('#work-days-container');
                            if ($workDaysContainer.length > 0) {
                                $workDaysContainer.find('.alert.work-days-general-error').remove();
                                $workDaysContainer.prepend('<div class="alert alert-danger mb-3 work-days-general-error">' + errorMessage + '</div>');
                                
                                // Scroll to error
                                setTimeout(function() {
                                    $('html, body').animate({
                                        scrollTop: $workDaysContainer.offset().top - 100
                                    }, 500);
                                }, 100);
                            } else {
                                // Show as alert if container not found
                                Swal.fire({
                                    type: 'error',
                                    title: '{{ __('admin.error') }}',
                                    text: errorMessage,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                            }
                        }
                    }
                });
            }, 300);
        }
    });
});
</script>
@endsection
