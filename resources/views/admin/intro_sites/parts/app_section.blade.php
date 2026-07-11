<div class="tab-pane fade" id="app_section" role="tabpanel" aria-labelledby="app_section-tab">

    <div class="row">
        <div class="col-12">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
        
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="app_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['app_image'] }}">
                                <button class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                            <span>{{ __('admin.app_image') }}</span>
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
                        {{ __("admin.app_title_{$lang}") }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ 'app_title' . '[' . $lang . ']' }}" class="form-control"
                            value="{{ $intro_texts['app_title_'.$lang] }}" class="form-control"
                            placeholder="{{ __("admin.app_title_{$lang}") }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="first-name-column">
                    {{ __("admin.android_app_link") }}
                </label>
                <div class="controls">
                    <input type="text" name="{{ 'android_app_link' }}" class="form-control"
                        value="{{ $intro_texts['android_app_link'] }}" class="form-control"
                        placeholder="{{ __("admin.android_app_link") }}">
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="first-name-column">
                    {{ __("admin.ios_app_link") }}
                </label>
                <div class="controls">
                    <input type="text" name="{{ 'ios_app_link' }}" class="form-control"
                        value="{{ $intro_texts['ios_app_link'] }}" class="form-control"
                        placeholder="{{ __("admin.ios_app_link") }}">
                </div>
            </div>
        </div>
    </div>
</div>