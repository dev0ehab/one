@extends('intro_site.layouts.master')

@section('content')
<div class="body-content intro-page intro-inner-page">
    <div class="container">
        <h1 class="intro-inner-page__title">@lang('intro_site.delete_account')</h1>
        <div class="intro-inner-page__body text-center">
            <form class="store" method="POST" novalidate>
                @csrf
                <p class="mb-4 lead">{{ __('intro_site.want_delete_account') }}</p>
                <div class="d-flex align-items-center justify-content-center flex-wrap intro-delete-actions">
                    <button type="button" class="btn btn-success submit_button">
                        <span class="btnText">@lang('intro_site.yes')</span>
                    </button>
                    <button type="button" class="btn btn-danger submit_button">
                        <span class="btnText">@lang('intro_site.no')</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
