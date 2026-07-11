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
                    <h4 class="card-title">{{__('admin.add_provider')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{route('admin.providers.store')}}" class="store form-horizontal parsley"
                            novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <!-- Provider Images -->
                                    <div class="col-12">
                                        <div class="imgMontg col-12 text-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>{{__('admin.image')}}</label>
                                                    <div class="dropBox">
                                                        <div class="textCenter">
                                                            <div class="imagesUploadBlock">
                                                                <label class="uploadImg">
                                                                    <span><i class="feather icon-image"></i></span>
                                                                    <input type="file" accept="image/*" name="image"
                                                                        class="imageUploader" required>
                                                                </label>
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
                                                                        class="imageUploader" required>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="trade_name">{{__('admin.trade_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="trade_name" class="form-control"
                                                    placeholder="{{__('admin.trade_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="owner_name">{{__('admin.owner_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="owner_name" class="form-control"
                                                    placeholder="{{__('admin.owner_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone">{{__('admin.phone')}}</label>
                                            <div class="controls">
                                                <input type="text" name="phone" class="form-control"
                                                    placeholder="{{__('admin.phone')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email">{{__('admin.email')}}</label>
                                            <div class="controls">
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="{{__('admin.email')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country_code">{{__('admin.country_code')}}</label>
                                            <div class="controls">
                                                <input type="text" name="country_code" class="form-control"
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
                                                        <option value="{{ $category->id }}">
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
                                            <label for="city_id">{{__('admin.city')}}</label>
                                            <div class="controls">
                                                <select name="city_id" id="city_id" class="form-control" required>
                                                    <option value="">{{__('admin.select_city')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="address">{{__('admin.address')}}</label>
                                            <div class="controls">
                                                <input type="text" name="address" id="address" class="form-control"
                                                    placeholder="{{__('admin.address')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lat">{{__('admin.latitude')}}</label>
                                            <div class="controls">
                                                <input type="text" name="lat" class="form-control"
                                                    placeholder="{{__('admin.latitude')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="lng">{{__('admin.longitude')}}</label>
                                            <div class="controls">
                                                <input type="text" name="lng" class="form-control"
                                                    placeholder="{{__('admin.longitude')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map_desc">{{__('admin.map_description')}}</label>
                                            <div class="controls">
                                                <textarea name="map_desc" class="form-control" rows="3"
                                                    placeholder="{{__('admin.map_description')}}" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="social_phone">{{__('admin.social_phone')}}</label>
                                            <div class="controls">
                                                <input type="text" name="social_phone" class="form-control"
                                                    placeholder="{{__('admin.social_phone')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="social_email">{{__('admin.social_email')}}</label>
                                            <div class="controls">
                                                <input type="email" name="social_email" class="form-control"
                                                    placeholder="{{__('admin.social_email')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp">{{__('admin.whatsapp')}}</label>
                                            <div class="controls">
                                                <input type="text" name="whatsapp" class="form-control"
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
                                                    accept="image/*" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Information -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="bank_name">{{__('admin.bank_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="bank_name" class="form-control"
                                                    placeholder="{{__('admin.bank_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="account_name">{{__('admin.account_name')}}</label>
                                            <div class="controls">
                                                <input type="text" name="account_name" class="form-control"
                                                    placeholder="{{__('admin.account_name')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="account_num">{{__('admin.account_num')}}</label>
                                            <div class="controls">
                                                <input type="text" name="account_num" class="form-control"
                                                    placeholder="{{__('admin.account_num')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="iban">{{__('admin.iban')}}</label>
                                            <div class="controls">
                                                <input type="text" name="iban" class="form-control"
                                                    placeholder="{{__('admin.iban')}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr>
                                        <h5>{{__('admin.work_days')}}</h5>
                                        <!-- Work days will be added here dynamically -->
                                        <div id="work-days-container">
                                            <!-- Work days form fields -->
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-work-day">{{__('admin.add_work_day')}}</button>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{__('admin.add')}}</button>
                                        <button type="reset" class="btn btn-outline-warning mr-1 mb-1">{{__('admin.reset')}}</button>
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
        saturday: '{{__('admin.saturday')}}',
        sunday: '{{__('admin.sunday')}}',
        monday: '{{__('admin.monday')}}',
        tuesday: '{{__('admin.tuesday')}}',
        wednesday: '{{__('admin.wednesday')}}',
        thursday: '{{__('admin.thursday')}}',
        friday: '{{__('admin.friday')}}'
    };

    // Work day name placeholders
    const exampleMorningShiftAr = '{{__('admin.example_morning_shift_ar')}}';
    const exampleMorningShiftEn = '{{__('admin.example_morning_shift_en')}}';

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
                        citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
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

    function addWorkDayField() {
        const workDayHtml = `
            <div class="work-day-item border rounded p-3 mb-3" data-index="${workDayIndex}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{__('admin.day')}}</label>
                            <select name="work_days[${workDayIndex}][day]" class="form-control" required>
                                <option value="">{{__('admin.select_day')}}</option>
                                <option value="saturday">${dayTranslations.saturday}</option>
                                <option value="sunday">${dayTranslations.sunday}</option>
                                <option value="monday">${dayTranslations.monday}</option>
                                <option value="tuesday">${dayTranslations.tuesday}</option>
                                <option value="wednesday">${dayTranslations.wednesday}</option>
                                <option value="thursday">${dayTranslations.thursday}</option>
                                <option value="friday">${dayTranslations.friday}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>من</label>
                            <input type="time" name="work_days[${workDayIndex}][start_at]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>إلى</label>
                            <input type="time" name="work_days[${workDayIndex}][end_at]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الاسم عربي</label>
                            <input type="text" name="work_days[${workDayIndex}][name][ar]" class="form-control" placeholder="${exampleMorningShiftAr}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الاسم إنجليزي</label>
                            <input type="text" name="work_days[${workDayIndex}][name][en]" class="form-control" placeholder="${exampleMorningShiftEn}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-work-day" data-index="${workDayIndex}">
                                <i class="fa fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#work-days-container').append(workDayHtml);
        workDayIndex++;
        
        // Update delete buttons visibility
        updateDeleteButtonsVisibility();
    }

    $(document).on('click', '.remove-work-day', function() {
        $(this).closest('.work-day-item').remove();
        // Update delete buttons visibility after removal
        updateDeleteButtonsVisibility();
    });

    function updateDeleteButtonsVisibility() {
        const workDayCount = $('.work-day-item').length;
        $('.remove-work-day').each(function() {
            if (workDayCount <= 1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }

    setTimeout(function() {
        addWorkDayField();

        setTimeout(function() {
            updateDeleteButtonsVisibility();
        }, 200);
    }, 100);

    // Handle form submission with validation error handling
    $('.store').on('submit', function(e) {
        // Remove previous validation errors
        $(this).find('.error').remove();
        $(this).find('.is-invalid').removeClass('is-invalid');

        // Collect work days data
        $('.work-day-item').each(function(index) {
            let day = $(this).find('select[name="day"]').val();
            let startAt = $(this).find('input[name="start_at"]').val();
            let endAt = $(this).find('input[name="end_at"]').val();
            let nameAr = $(this).find('input[name="name_ar"]').val();
            let nameEn = $(this).find('input[name="name_en"]').val();

            if (day && startAt && endAt) {
                // Create hidden inputs for work days
                let form = $('.store');
                form.append('<input type="hidden" name="work_days[' + index + '][day]" value="' + day + '">');
                form.append('<input type="hidden" name="work_days[' + index + '][start_at]" value="' + startAt + '">');
                form.append('<input type="hidden" name="work_days[' + index + '][end_at]" value="' + endAt + '">');
                form.append('<input type="hidden" name="work_days[' + index + '][name][ar]" value="' + (nameAr || '') + '">');
                form.append('<input type="hidden" name="work_days[' + index + '][name][en]" value="' + (nameEn || '') + '">');
            }
        });
    });
});

// Custom validation error handler for work_days
$(document).ready(function() {
    // Override AJAX setup to intercept errors
    var originalAjax = $.ajax;
    $.ajax = function(options) {
        var originalError = options.error;
        options.error = function(xhr) {
            // Call original error handler first
            if (originalError) {
                originalError.apply(this, arguments);
            }
            
            // Then handle work_days errors
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                setTimeout(function() {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        if (key.indexOf('work_days') >= 0) {
                            var errorMessage = Array.isArray(value) ? value[0] : value;
                            var parts = key.split('.');
                            
                            if (parts.length >= 3) {
                                var index = parseInt(parts[1]); // e.g., 0 from "work_days.0.end_at"
                                var fieldName = parts[2]; // e.g., "end_at" from "work_days.0.end_at"
                                
                                // Find the work day item by index
                                var $workDayItems = $('#work-days-container .work-day-item');
                                if ($workDayItems.length > index) {
                                    var $workDayItem = $workDayItems.eq(index);
                                    var $field = $workDayItem.find('input[name*="[' + fieldName + ']"], select[name*="[' + fieldName + ']"]');
                                    
                                    if ($field.length > 0) {
                                        $field.addClass('border-danger');
                                        var $formGroup = $field.closest('.form-group');
                                        if ($formGroup.length > 0) {
                                            // Remove any existing error message
                                            $formGroup.find('.text-danger.work-day-error').remove();
                                            $formGroup.append('<span class="text-danger d-block mt-1 work-day-error">' + errorMessage + '</span>');
                                        }
                                    } else {
                                        // If field not found, show error in the work day item
                                        $workDayItem.find('.alert.work-day-error').remove();
                                        $workDayItem.prepend('<div class="alert alert-danger mb-2 work-day-error">' + errorMessage + '</div>');
                                    }
                                }
                            }
                        }
                    });
                }, 300);
            }
        };
        return originalAjax.call(this, options);
    };
});
</script>

@include('admin.shared.addImage')
@include('admin.shared.submitAddForm')

@endsection
