@extends('admin.layout.master')

{{-- extra css files --}}
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection
{{-- extra css files --}}

@section('content')
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.edit') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.financials.update', ['id' => $financial->id]) }}"
                                class=" store form-horizontal parsley" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="form-body">

                                    <x-admin.image name="image" :model="$financial" />

                                    <x-admin.input name="title" :model="$financial" :multi_lang="true" attr="required" />
                                    
                                    <x-admin.input name="sub_title" :model="$financial" :multi_lang="true" attr="required" />
                                    
                                    <x-admin.textarea :has_editor="true" name="description" :model="$financial" :multi_lang="true" attr="required" />

                                    <x-admin.switch name="is_active" :model="$financial" :label="true" :ajax="false" />


                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">
                                                {{ __('admin.update') }}
                                            </button>
                                            <a href="{{ url()->previous() }}" type="reset"
                                                class="btn btn-outline-warning mr-1 mb-1">
                                                {{ __('admin.back') }}
                                            </a>
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
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>

    {{-- show selected image script --}}
    @include('admin.shared.addImage')
    {{-- show selected image script --}}

    {{-- submit edit form script --}}
    @include('admin.shared.submitEditForm')
    {{-- submit edit form script --}}
@endsection
