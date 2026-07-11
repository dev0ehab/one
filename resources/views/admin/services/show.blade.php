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

                                    <x-admin.image :show="true" name="image" :model="$service->image" :show="true" />

                                    <x-admin.input :show="true" name="title" :model="$service" :multi_lang="true" attr="required" />

                                    <x-admin.textarea :show="true" :has_editor="true" name="description" :show :model="$service"
                                        :multi_lang="true" attr="required" />

                                    <x-admin.switch name="is_active" :show="true" :model="$service" :label="true" :ajax="false"
                                        attr="disabled" />





                                    <h2 class="py-2"> {{ __('admin.parts') }} </h2>

                                    <div class="row">
                                        @foreach ($service->parts as $part)
                                            <div class="col-12">

                                                <x-admin.image :show="true" name="image" :model="$part->image" />
                                                <x-admin.textarea :show="true" :has_editor="true" :model="$part" name="description"
                                                    :multi_lang="true" attr="required" />
                                            </div>
                                        @endforeach
                                    </div>

                                    </button>
                                </div>




                                <h2 class="py-2"> {{ __('admin.importants') }} </h2>


                                <div class="row">
                                    @foreach ($service->importants as $important)
                                        <div class="col-12">
                                            <x-admin.input  :show="true" name="title" :multi_lang="true" :model="$important"
                                                attr="required" />
                                        </div>
                                    @endforeach
                                </div>


                        </div>


                        <div class="row">

                            <div class="col-12 d-flex justify-content-center mt-3">
                                <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">
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
    <script>
        $('.store input').css('opacity', '1')
        $('.store input').attr('disabled', true)
        $('.store textarea').attr('disabled', true)
        $('.store select').attr('disabled', true)
    </script>
@endsection
