<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<footer class="footer footer-static footer-light admin-neotech-footer">
    <p class="clearfix blue-grey lighten-2 mb-0" >
        <span class="float-md-left d-block d-md-inline-block mt-25" >
            {{ __('admin.copyrights') }} &copy; {{ \Carbon\Carbon::now()->year }} ,
            {{ __('admin.all_rights_reserved') }}
        </span>

        <span class="float-md-right d-none d-md-block">
            <a href="https://cs.sa/" class="merchant-name" rel="follow"
                target="_blank">{{ __('admin.powered_by') }}</a>
            <a href="mailto:sales@cs.sa"><i class="feather icon-mail pink"></i></a>
            <a href="tel:920005929"><i class="feather icon-phone pink"></i></a>
        </span>

    </p>
</footer>

<script src="{{ asset('admin/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/neo-loader.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/components.js') }}"></script>
<script src="{{ asset('admin/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/flatpickr.js') }}"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/{{ app()->getLocale() }}.js"></script>
<script src="{{ asset('admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/forms/select/form-select2.js') }}"></script>
<script src="{{ asset('admin/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.2/dist/parsley.js"></script>
<script src="{{ asset('admin/parsely-n18i/' . app()->getLocale() . '.js') }}"></script>

<x-admin.alert />
{{--
<x-socket /> --}}

@yield('js')
@stack('js')

<script src="{{ asset('admin/assets/js/premium-ui.js') }}"></script>

<script>
    $(document).ready(function () {

        $('.parsley').parsley();

        $(document).on('click', '.page-link', function (e) {
            $('.delete_all_button').hide();
        });

        $('#beginning_date').on('change', function () {
            const minDate = $(this).val();
            $('#stop_date').attr('min', minDate);
        });

        $('#stop_date').on('change', function () {
            const maxDate = $(this).val();
            $('#beginning_date').attr('max', maxDate);
        });
    });
</script>

@stack('js-after')

</body>

</html>