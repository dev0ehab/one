@extends('admin.layout.master')

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('admin.view') . ' ' . __('admin.pages')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="show form-horizontal">
                            <div class="form-body">

                                <x-admin.image name="image" :model="$page->image" :show="true" />

                                <div class="row">
                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">{{__('site.title_' . $lang)}}</label>
                                                <div class="controls">
                                                    <input type="text" value="{{$page->getTranslations('title')[$lang]}}"
                                                        name="title[{{$lang}}]" class="form-control merchant-name" style="opacity: 1"
                                                        placeholder="{{__('site.write') . __('site.title_' . $lang)}}"
                                                        required
                                                        data-validation-required-message="{{__('admin.this_field_is_required')}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    </div>

                                    <x-admin.textarea :has_editor="true" name="content" :show="true" :model="$page" :multi_lang="true" attr="required" />


                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <a href="{{ url()->previous() }}" type="reset"
                                            class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
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
    $('.show input').attr('disabled', true)
    $('.show textarea').attr('disabled', true)
    $('.show select').attr('disabled', true)
</script>
@endsection