{{-- Contact Us Section Tab --}}
<div class="tab-pane fade" id="contact_us_section" role="tabpanel" aria-labelledby="contact_us_section-tab">
    <div class="row">
        @foreach (languages() as $lang)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">
                        {{ __("admin.contact_title_{$lang}") }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ 'contact_title' . '[' . $lang . ']' }}" class="form-control"
                            value="{{ $intro_texts['contact_title_'.$lang] }}" class="form-control"
                            placeholder="{{ __("admin.contact_title_{$lang}") }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
