@extends('admin.layout.master')

@section('content')
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.review_details') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('admin.image') }}</label>
                                        <div class="controls">
                                            <img src="{{ $introreview->image }}" alt="{{ __('admin.review_image') }}" 
                                                class="img-fluid" style="max-width: 300px; max-height: 300px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('admin.comment') }} ({{ __('admin.ar') }})</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $introreview->getTranslation('comment', 'ar') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('admin.comment') }} ({{ __('admin.en') }})</label>
                                        <div class="controls">
                                            <p class="form-control-static">{{ $introreview->getTranslation('comment', 'en') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-center mt-3">
                                    <a href="{{ route('admin.introreviews.edit', ['id' => $introreview->id]) }}" 
                                        class="btn btn-primary mr-1 mb-1">
                                        {{ __('admin.edit') }}
                                    </a>
                                    <a href="{{ route('admin.introreviews.index') }}" 
                                        class="btn btn-outline-warning mr-1 mb-1">
                                        {{ __('admin.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
