@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('admin.edit')}}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{{route('admin.provider-categories.update' , ['id' => $providerCategory->id])}}" class="store" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <x-admin.image name="image" :model="$providerCategory" />
                                    
                                    <div class="row">
                                        @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="name-{{ $lang }}">{{ __('site.name_' . $lang) }}</label>
                                                <div class="controls">
                                                    <input type="text" name="name[{{ $lang }}]" id="name-{{ $lang }}" class="form-control" 
                                                           value="{{ $providerCategory->getTranslations('name')[$lang] ?? '' }}" 
                                                           placeholder="{{ __('site.write') . __('site.name_' . $lang) }}" required 
                                                           data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{__('admin.update')}}</button>
                                        <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
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
    <script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>

     {{-- show selected image script --}}
         @include('admin.shared.addImage')
     {{-- show selected image script --}}

     {{-- submit edit form script --}}
         @include('admin.shared.submitEditForm')
     {{-- submit edit form script --}}
@endsection

