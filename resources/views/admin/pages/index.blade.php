@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/index_page.css')}}">
@endsection

@section('content')

    <div class="content-body">

        <div class="neo-index-toolbar mb-2">
            <x-admin.buttons />

            <x-admin.filter :datefilter=false order="true" :searchArray="[
            'title' => [
                'input_type' => 'text',
                'input_name' => __('admin.name'),
            ],
            'content' => [
                'input_type' => 'text',
                'input_name' => __('admin.content'),
            ],
        ]" />

        </div>
        <div class="table_content_append">
        </div>
    </div>

@endsection

@section('js')

    <script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>
    @include('admin.shared.deleteAll')
    @include('admin.shared.deleteOne')
    @include('admin.shared.filter_js', ['index_route' => url('admin/pages')])
@endsection