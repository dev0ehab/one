{{-- Sections Section Tab --}}
<div class="tab-pane fade" id="sections_section" role="tabpanel" aria-labelledby="sections_section-tab">
    <div class="row">
        @foreach (languages() as $lang)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">
                        {{ __("admin.sections_title_{$lang}") }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ 'sections_title' . '[' . $lang . ']' }}" class="form-control"
                            value="{{ $intro_texts['sections_title_'.$lang] }}" class="form-control"
                            placeholder="{{ __("admin.sections_title_{$lang}") }}">
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
                        {{ __("admin.sections_description_{$lang}") }}
                    </label>
                    <div class="controls">
                        <textarea name="{{ 'sections_description' . '[' . $lang . ']' }}" class="form-control"
                            placeholder="{{ __("admin.sections_description_{$lang}") }}">{{ $intro_texts['sections_description_'.$lang] }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="sections_main_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['sections_main_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.sections_main_image') }}</span>
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <div class="row">
        <div class="col-6">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="sections_clients_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['sections_clients_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.sections_clients_image') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="sections_organizations_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['sections_organizations_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.sections_organizations_image') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="sections_providers_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['sections_providers_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.sections_providers_image') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="sections_merchants_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['sections_merchants_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.sections_merchants_image') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
