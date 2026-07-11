{{-- Landing Section Tab --}}
<div class="tab-pane fade show active" id="landing_section" role="tabpanel" aria-labelledby="landing_section-tab">
    <!-- Content for Home tab -->

    <div class="row">
        <div class="col-4">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="landing_background" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['landing_background_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.landing_background_image') }}</span>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-4">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="landing_image_no_1" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['landing_image_1'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.landing_image_1') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-4">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="landing_image_no_2" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['landing_image_2'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.landing_image_2') }}</span>
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
                        {{ __("admin.landing_title_{$lang}") }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ 'landing_title' . '[' . $lang . ']' }}" class="form-control"
                            value="{{ $intro_texts['landing_title_'.$lang] }}" class="form-control"
                            placeholder="{{ __("admin.landing_title_{$lang}") }}">
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
                        {{ __("admin.landing_sub_title_{$lang}") }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ 'landing_sub_title' . '[' . $lang . ']' }}" class="form-control"
                            value="{{ $intro_texts['landing_sub_title_'.$lang] }}" class="form-control"
                            placeholder="{{ __("admin.landing_sub_title_{$lang}") }}">
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
                        {{ __("admin.landing_description_{$lang}") }}
                    </label>
                    <div class="controls">
                        <textarea name="{{ 'landing_description' . '[' . $lang . ']' }}" class="form-control"
                            placeholder="{{ __("admin.landing_description_{$lang}") }}">{{ $intro_texts['landing_description_'.$lang] }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
