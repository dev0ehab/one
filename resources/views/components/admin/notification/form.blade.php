<form action="{{ route('admin.notifications.send') }}" method="POST" enctype="multipart/form-data" class="notify-form parsley"
    novalidate>
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="row">
        {{ $slot }}

        <x-admin.notification.notification-senders />
        <x-admin.notification.btn-submit />
    </div>
</form>
