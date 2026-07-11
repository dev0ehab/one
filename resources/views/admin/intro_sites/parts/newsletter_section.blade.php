{{-- Newsletter Section Tab --}}
<div class="tab-pane fade" id="newsletter_section" role="tabpanel" aria-labelledby="newsletter_section-tab">

    {{-- الصورة الرئيسية (subscribe_image) --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-2 col-sm-4 col-6">
            <div class="imgMontg text-center">
                <div class="dropBox">
                    <div class="textCenter">
                        <div class="imagesUploadBlock">
                            <label class="uploadImg">
                                <span><i class="feather icon-image"></i></span>
                                <input type="file" accept="image/*" name="subscribe_image" class="imageUploader">
                            </label>
                            <div class="uploadedBlock">
                                <img src="{{ $intro_images['subscribe_image'] ?? asset('uploads/defaults/default.png') }}"
                                    alt="subscribe_image">
                                <button type="button" class="close"><i class="feather icon-trash-2"></i></button>
                            </div>
                        </div>
                        <span>{{ __('admin.subscribe_image') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- العناوين --}}
    <div class="row">
        @foreach (languages() as $lang)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __("admin.subscribe_title_{$lang}") }}</label>
                    <div class="controls">
                        <input type="text" name="subscribe_title[{{ $lang }}]" class="form-control"
                            value="{{ $intro_texts['subscribe_title_' . $lang] ?? '' }}"
                            placeholder="{{ __("admin.subscribe_title_{$lang}") }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- الوصف --}}
    <div class="row">
        @foreach (languages() as $lang)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __("admin.subscribe_description_{$lang}") }}</label>
                    <div class="controls">
                        <textarea name="subscribe_description[{{ $lang }}]" class="form-control"
                            placeholder="{{ __("admin.subscribe_description_{$lang}") }}">{{ $intro_texts['subscribe_description_' . $lang] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- الصور الإضافية --}}
    <div class="row justify-content-center">
        @for ($i = 1; $i <= 5; $i++)
            <div class="col-md-2 col-sm-4 col-6 mb-4">
                <div class="imgMontg text-center">
                    <div class="dropBox">
                        <div class="textCenter">
                            <div class="imagesUploadBlock">
                                <label class="uploadImg">
                                    <span><i class="feather icon-image"></i></span>
                                    <input type="file" accept="image/*" name="app_data_{{ $i }}" class="imageUploader">
                                </label>
                                <div class="uploadedBlock">
                                    <img src="{{ $intro_images['app_data_' . $i] ?? asset('uploads/defaults/default.png') }}"
                                        alt="app_data_{{ $i }}">
                                    <button type="button" class="close"><i class="feather icon-trash-2"></i></button>
                                </div>
                            </div>
                            <span>{{ __('admin.app_data_' . $i) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
