<div class="col-md-{{ $col }} col-12">
    <div class="form-group">
        <label for="first-name-column">{{ $label }}</label>
        <div class="controls">
            <textarea name="{{ $name }}" class="form-control" cols="30" rows="10" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}"></textarea>
        </div>
    </div>
</div>
