@extends('admin.layout.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Node host:port -->
    <meta name="NODE_HOST" content="{{ env('NODE_HOST') }}">
    <meta name="NODE_PORT" content="{{ env('NODE_PORT') }}">
    <meta name="APP_URL" content="{{ env('APP_URL') }}">
    <meta name="NODE_MODE" content="{{ env('NODE_MODE') }}">
    <meta name="user_id" content="{{ $user->id }}">
    <meta name="lang" content="{{ lang() }}">
    <meta name="user_type" content="Admin">
    <meta name="user_name" content="{{ $user->name }}">
    <meta name="user_avatar" content="{{ $user->image }}">
    <meta name="order_num" content="{{ $order->id }}">
    <meta name="room_id" content="{{ $room->id }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/chat.css') }}">
@endsection

@section('content')

    <div class="content-body">

        <div id="app">
            <chat-component :members='@json($members)' :messages='@json($messages)'
                :room='@json($room)' :status="{{ true }}"></chat-component>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('chat/js/modules/vue.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.3"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-chat-scroll@1.3.3/dist/vue-chat-scroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.43/builds/moment-timezone-with-data.min.js"></script>

    <script src="{{ asset('chat/js/modules/vue-socketio.js') }}"></script>
    <script src="{{ asset('chat/js/modules/httpVueLoader.min.js') }}"></script>
    <script src="{{ asset('chat/js/app.js') }}"></script>
@endsection
