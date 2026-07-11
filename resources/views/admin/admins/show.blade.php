@extends('admin.layout.master')

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('admin.show') }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class=" store form-horizontal parsley">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="imgMontg col-12 text-center">
                                            <div class="dropBox">
                                                <div class="textCenter">
                                                    <div class="imagesUploadBlock">
                                                        <label class="uploadImg">
                                                            <span><i class="feather icon-image"></i></span>
                                                            <input type="file" accept="image/*" name="image"
                                                                class="imageUploader">
                                                        </label>
                                                        <div class="uploadedBlock">
                                                            <img src="{{ $admin->image }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.name') }}</label>
                                            <div class="controls">
                                                <input type="text" name="name" value="{{ $admin->name }}"
                                                    class="form-control" placeholder="{{  __('admin.write_the_name') }}"
                                                    required
                                                    data-validation-required-message="{{ __('admin.this_field_is_required') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.phone_number') }}</label>
                                            <div class="controls">
                                                <input type="number" name="phone" value="{{ $admin->phone }}"
                                                    class="form-control"
                                                    placeholder="{{ __('admin.enter_phone_number') }}" required
                                                    data-validation-required-message="{{  __('admin.this_field_is_required') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.email') }}</label>
                                            <div class="controls">
                                                <input type="email" name="email" value="{{ $admin->email }}"
                                                    class="form-control" placeholder="{{ __('admin.enter_the_email') }}"
                                                    required
                                                    data-validation-required-message="{{  __('admin.this_field_is_required') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.password') }}</label>
                                            <div class="controls">
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.status') }}</label>
                                            <div class="controls">
                                                <select name="block" class="select2 form-control" required
                                                    data-validation-required-message="{{  __('admin.this_field_is_required') }}">
                                                    <option value>{{ __('admin.select_the_blocking_status') }}</option>
                                                    <option {{ $admin->is_blocked == 1 ? 'selected' : '' }} value="1">
                                                        {{ __('admin.prohibited') }}</option>
                                                    <option {{ $admin->is_blocked == 0 ? 'selected' : '' }} value="0">
                                                        {{ __('admin.unspoken') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">{{ __('admin.validity') }}</label>
                                            <div class="controls">
                                                <select name="role_id" class="select2 form-control" required
                                                    data-validation-required-message="{{  __('admin.this_field_is_required') }}">
                                                    <option value>{{ __('admin.select_the_validity') }}</option>
                                                    @foreach ($roles as $role)
                                                    <option {{ $role->id == $admin->role_id ? 'selected' : '' }}
                                                        value="{{ $role->id }}">{{ $role->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <a href="{{ url()->previous() }}" type="reset"
                                            class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
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
<script>
    $('.store input').attr('disabled', true)
    $('.store textarea').attr('disabled', true)
    $('.store select').attr('disabled', true)
</script>
@endsection