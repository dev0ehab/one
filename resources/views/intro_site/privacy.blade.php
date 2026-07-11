@extends('intro_site.layouts.master')

@section('content')
<div class="body-content intro-page intro-inner-page">
    <div class="container">
        <h1 class="intro-inner-page__title">{{ __('intro_site.privacy') }}</h1>
        <div class="intro-inner-page__body">
            {!! $settings['privacy_' . lang()] !!}
        </div>
    </div>
</div>
@endsection
