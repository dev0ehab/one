{{-- Stats Section Tab --}}
<div class="tab-pane fade" id="stats_section" role="tabpanel" aria-labelledby="stats_section-tab">
    @foreach ($intro_stats as $stat)

        <div class="row">
            <div class="col-12">
                <hr class="mt-3">
                <h4 class="text-center">{{ __('admin.' . $stat->key) }}</h4>
                <hr class="mb-3">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="imgMontg text-center">
                    <div class="dropBox">
                        <div class="textCenter">
                            <div class="imagesUploadBlock">
                                <label class="uploadImg">
                                    <span><i class="feather icon-image"></i></span>
                                    <input type="file" accept="image/*" name="stats[{{ $stat->id }}][image]" class="imageUploader">
                                </label>
                                <div class="uploadedBlock">
                                    <img src="{{ $stat->image }}">
                                    <button class="close"><i class="feather icon-trash-2"></i></button>
                                </div>
                            </div>
                            <span>{{ __('admin.stats_image') }}</span>
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
                            {{ __("admin.stats_title_{$lang}") }}
                        </label>
                        <div class="controls">
                            <input type="text" name="{{ 'stats' .'[' . $stat->id . ']' . '[' . 'title' . ']' . '[' . $lang . ']' }}" class="form-control"
                                value="{{ $stat->getTranslation('title', $lang) }}" class="form-control"
                                placeholder="{{ __("admin.stats_title_{$lang}") }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="row">
                <div class="col-md-12 col-12">
                    <div class="form-group">
                        <label for="first-name-column">
                            {{ __("admin.stats_value") }}
                        </label>
                        <div class="controls">
                            <input type="text" name="{{ 'stats' .'[' . $stat->id . ']' . '[' . 'value' . ']' }}" class="form-control"
                                value="{{ $stat->value }}" class="form-control"
                                placeholder="{{ __("admin.stats_value") }}">
                        </div>
                    </div>
                </div>
        </div>
    
    @endforeach
</div>
