@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/index_page.css')}}">
@endsection

@section('content')
<div class="content-body">

    <div class="neo-index-toolbar mb-2">

        <x-admin.buttons 
            deletebutton="{{ route('admin.reports.deleteAll') }}"
        />

        <x-admin.filter 
            datefilter="true" 
            order="true" 
            :searchArray="[
                'subject' => [
                    'input_type' => 'text' , 
                    'input_name' => __('admin.report_text') , 
                ] ,
                'ip' => [
                    'input_type' => 'text' , 
                    'input_name' => __('admin.ip') , 
                ] ,
                
            ]" 
        />

    </div>

    <div class="table_content_append">
        
    </div>
</div>
    
@endsection

@section('js')

    <script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>

    {{-- delete all script --}}
        @include('admin.shared.deleteAll')
    {{-- delete all script --}}

    {{-- delete one user script --}}
        @include('admin.shared.deleteOne')
    {{-- delete one user script --}}

    {{-- delete one user script --}}
        @include('admin.shared.filter_js' , [ 'index_route' => url('admin/reports')])
    {{-- delete one user script --}}
@endsection
