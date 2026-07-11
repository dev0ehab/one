@extends('admin.layout.master')
{{-- extra css files --}}
@section('css')

  <link rel="stylesheet" type="text/css"
    href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection
{{-- extra css files --}}

@section('content')
  <!-- // Basic multiple Column Form section start -->
  <section id="multiple-column-form">
    <div class="row match-height">
    <div class="col-12">
      <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ __('admin.add') }}</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
        <form method="POST" action="{{ route('admin.admins.store') }}" class=" store form-horizontal parsley"
          novalidate>
          @csrf
          <div class="form-body">
          <div class="row">
            <div class="col-12">
            <div class="imgMontg col-12 text-center">
              <div class="dropBox">
              <div class="textCenter">
                <div class="imagesUploadBlock">
                <label class="uploadImg">
                  <span><i class="feather icon-image"></i></span>
                  <input type="file" accept="image/*" name="image" class="imageUploader">
                </label>
                </div>
              </div>
              </div>
            </div>
            </div>

            <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="first-name-column">{{  __('admin.name') }}</label>
              <div class="controls">
              <input type="text" name="name" class="form-control"
                placeholder="{{  __('admin.write_the_name') }}" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}">
              </div>
            </div>
            </div>
            <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="first-name-column">{{ __('admin.phone_number') }}</label>
              <div class="controls">
              <input type="number" name="phone" class="form-control"
                placeholder="{{  __('admin.enter_phone_number') }}" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}">
              </div>
            </div>
            </div>
            <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="first-name-column">{{ __('admin.email') }}</label>
              <div class="controls">
              <input type="email" name="email" class="form-control"
                placeholder="{{  __('admin.enter_the_email') }}" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}"
                data-validation-email-message="{{__('admin.email_formula_is_incorrect')}}">
              </div>
            </div>
            </div>
            <div class="col-md-6 col-12">
            <div class="form-group">
              <label for="first-name-column">{{ __('admin.password')  }}</label>
              <div class="controls">
              <input type="password" name="password" class="form-control" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}">
              </div>
            </div>
            </div>

            {{-- <div class="col-md-12 col-12">
            <div class="form-group">
              <label for="first-name-column">{{ __('admin.status')}}</label>
              <div class="controls">
              <select name="is_blocked" class="select2 form-control" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}">
                <option value>{{ __('admin.select_the_blocking_status') }}</option>
                <option value="1">{{ __('admin.prohibited') }}</option>
                <option value="0" selected>{{ __('admin.unspoken')}}</option>
              </select>
              </div>
            </div>
            </div> --}}
            <div class="col-md-12 col-12">
            <div class="form-group">
              <label for="first-name-column">{{ __('admin.validity')}}</label>
              <div class="controls">
              <select name="role_id" class="select2 form-control" required
                data-validation-required-message="{{ __('admin.this_field_is_required') }}">
                <option value>{{  __('admin.select_the_validity') }}</option>
                @foreach ($roles as $role)
          <option value="{{ $role->id }}">{{ $role->name }}
          </option>
        @endforeach
              </select>
              </div>
            </div>
            </div>

            <div class="col-12 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.add') }}</button>
            <a href="{{ url()->previous() }}" type="reset"
              class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back') }}</a>
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
  <script src="{{ asset('admin/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js') }}">
  </script>
  <script src="{{ asset('admin/app-assets/js/scripts/forms/validation/form-validation.js') }}">
  </script>
  <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}">
  </script>
  <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}">
  </script>

  {{-- show selected image script --}}
  @include('admin.shared.addImage')
  {{-- show selected image script --}}

  {{-- submit add form script --}}
  @include('admin.shared.submitAddForm')
  {{-- submit add form script --}}

@endsection