<div class="card">
    <div class="card-header">
        <h4>{{ __('admin.send_notify') }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.notify') }}" class="form notify-form parsley" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="notify">
            <input type="hidden" name="id" value="{{ $row->id }}">

            <textarea name="body_form_ar" class="form-control" rows="3" cols="1" required
                placeholder="{{  __('admin.write') }} {{  __('admin.the_message_in_arabic') }}"
                data-parsley-required-message="{{__('admin.this_field_is_required')}}"></textarea>
            <div class="error error_body_ar"></div>
            <br>

            <textarea name="body_form_en" class="form-control" rows="3" cols="1" required
                placeholder="{{  __('admin.write') }} {{  __('admin.the_message_in_english') }}"
                data-parsley-required-message="{{__('admin.this_field_is_required')}}"></textarea>
            <div class="error error_body_en"></div>
            <br>
            <hr>

            <div class="d-flex align-items-center">
                <button type="submit"
                    class="btn  btn-labeled btn-labeled-right ml-auto legitRipple btn-primary send-notify-button">
                    <i class="feather icon-navigation"></i>
                    {{ __('admin.send') }}</button>
            </div>
        </form>
    </div>
</div>
