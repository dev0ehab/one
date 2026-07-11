@extends('intro_site.layouts.master')

@section('content')
<!-- Start body-content -->

<div class="body-content intro-page">
    {{-- start slider section --}}
    @include('intro_site.parts.slider')
    {{-- end slider section --}}
    {{-- start about app section --}}
    @include('intro_site.parts.about')
    {{-- end about app section --}}
    {{-- our services section --}}
    @include('intro_site.parts.services')
    {{-- our services section --}}
    {{-- start how work section --}}
    @include('intro_site.parts.how_work')
    {{-- end how work section --}}
    {{-- start fqs section --}}
    @include('intro_site.parts.fqs')
    {{-- end fqs section --}}
    {{-- start partners section --}}
    @include('intro_site.parts.partners')
    {{-- end partners section --}}
    {{-- start contact section --}}
    @include('intro_site.parts.contact_us')
    {{-- end contact section --}}

    {{-- {!! $settings['live_chat'] !!} --}}
</div>

<!-- end body-content -->
@endsection
