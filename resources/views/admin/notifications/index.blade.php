@extends('admin.layout.master')

@section('css')
    
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection

@section('content')
    <section class="users-edit">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <x-admin.notification.tabs />
                    <div class="tab-content">
                        <div class="tab-pane active" id="notify" aria-labelledby="notify-tab" role="tabpanel">
                            <x-admin.notification.form type="notify">

                                <x-admin.notification.textarea name="body_form_ar" col="6"
                                    label="{{ __('admin.the_message_in_arabic') }}" />

                                <x-admin.notification.textarea name="body_form_en" col="6"
                                    label="{{ __('admin.the_message_in_english') }}" />
                            </x-admin.notification.form>
                        </div>

                        <div class="tab-pane " id="sms" aria-labelledby="sms-tab" role="tabpanel">
                            <x-admin.notification.form type="sms">

                                <x-admin.notification.textarea name="sms_body" label="{{ __('admin.text_of_message') }}" col="12"/>

                            </x-admin.notification.form>

                        </div>

                        <div class="tab-pane " id="email" aria-labelledby="email-tab" role="tabpanel">
                            <x-admin.notification.form type="form_email">

                                <x-admin.notification.textarea name="email_body" label="{{ __('admin.email_content') }}" col="12" />

                            </x-admin.notification.form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<x-admin.notification.js />
