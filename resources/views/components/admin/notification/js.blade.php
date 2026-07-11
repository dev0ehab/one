@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
     
    
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.select2').addClass('w-100');
            $(document).on('submit', '.notify-form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action')
                $.ajax({
                    url: url,
                    method: 'post',
                    data: new FormData($(this)[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".send-notify-button").html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disable', true)
                    },
                    success: (response) => {
                        $(".text-danger").remove()
                        $('.store input').removeClass('border-danger')
                        $(".send-notify-button").html("{{ __('admin.send') }}").attr('disable',
                            false)
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.send_successfully') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        $(this).trigger("reset")
                    },
                    error: function(xhr) {
                        $(".send-notify-button").html("{{ __('admin.send') }}").attr('disable',
                            false)
                        $(".text-danger").remove()
                        $('.store input').removeClass('border-danger')

                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('.store input[name=' + key + ']').addClass(
                                'border-danger')
                            $('.store input[name=' + key + ']').after(
                                `<span class="mt-5 text-danger">${value}</span>`);
                            $('.store select[name=' + key + ']').after(
                                `<span class="mt-5 text-danger">${value}</span>`);
                            $('.notify-form textarea[name=' + key + ']').after(
                                `<span class="mt-5 text-danger">${value}</span>`);

                        });
                    },
                });

            });
        });
    </script>
@endsection
