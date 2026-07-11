<style>
    .delete_all_button {
        display: none !important;
    }
    .delete_all_button.show {
        display: inline-block !important;
    }
</style>
<div class="neo-index-actions">
    <div class="buttons neo-actions">

        @isset($addbutton)
            <a href="{{ $addbutton }}" class="btn neo-action-btn neo-action-btn--primary waves-effect waves-light">
                <i class="feather icon-plus" aria-hidden="true"></i>
                <span>{{ __('admin.add') }}</span>
            </a>
        @endisset

        @isset($deletebutton)
            <button type="button" data-route="{{ $deletebutton }}"
                class="btn neo-action-btn neo-action-btn--danger waves-effect waves-light delete_all_button">
                <i class="feather icon-trash" aria-hidden="true"></i>
                <span>{{ __('admin.delete_selected') }}</span>
            </button>
        @endisset

        @isset($extrabuttons)
            {{ $extrabuttonsdiv }}
        @endif

        <button type="button" class="reloadTable btn neo-action-btn neo-action-btn--refresh waves-effect waves-light">
            <i class="feather icon-refresh-cw" aria-hidden="true"></i>
            <span>{{ __('admin.refresh') }}</span>
        </button>
    </div>
</div>
