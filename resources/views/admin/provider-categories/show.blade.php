@extends('admin.layout.master')

@section('css')
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{__('admin.show')}}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <x-admin.image name="image" :model="$providerCategory->image" :show="true" :label="__('image')" />
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.category_name')}} - {{__('site.name_en')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $providerCategory->getTranslations('name')['en'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{__('admin.category_name')}} - {{__('site.name_ar')}}</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $providerCategory->getTranslations('name')['ar'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-center mt-3">
                                    <a href="{{ route('admin.provider-categories.edit', $providerCategory->id) }}" class="btn btn-primary mr-1 mb-1">{{__('admin.edit')}}</a>
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection

