{{-- Services Section Tab --}}
<div class="tab-pane fade" id="services_section" role="tabpanel" aria-labelledby="services_section-tab">
    @foreach ($intro_services as $service)
        <div class="row">
            <div class="col-12">
                <div class="imgMontg text-center">
                    <div class="dropBox">
                        <div class="textCenter">
                            <div class="imagesUploadBlock">
                                <label class="uploadImg">
                                    <span><i class="feather icon-image"></i></span>
                                    <input type="file" accept="image/*" name="services[{{ $service->id }}][image]" class="imageUploader">
                                </label>
                                <div class="uploadedBlock">
                                    <img src="{{ $service->image }}">
                                    <button class="close"><i class="feather icon-trash-2"></i></button>
                                </div>
                            </div>
                            <span>{{ __('admin.services_image') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach (languages() as $lang)
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="first-name-column">
                            {{ __("admin.services_title_{$lang}") }}
                        </label>
                        <div class="controls">
                            <input type="text" name="{{ 'services' .'[' . $service->id . ']' . '[' . 'title' . ']' . '[' . $lang . ']' }}" class="form-control"
                                value="{{ $service->getTranslation('title', $lang) }}" class="form-control"
                                placeholder="{{ __("admin.services_title_{$lang}") }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    
        <div class="row">
            @foreach (languages() as $lang)
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="first-name-column">
                            {{ __("admin.services_description_{$lang}") }}
                        </label>
                        <div class="controls">
                            <textarea name="{{ 'services' .'[' . $service->id . ']' . '[' . 'description' . ']' . '[' . $lang . ']' }}" class="form-control"
                                placeholder="{{ __("admin.services_description_{$lang}") }}">{{ $service->getTranslation('description', $lang) }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
