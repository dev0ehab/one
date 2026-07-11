<script>
    $(document).ready(function () {
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                newestOnTop: true,
                positionClass: 'toast-top-right',
                preventDuplicates: true,
                timeOut: '3600',
                extendedTimeOut: '900',
                showDuration: '320',
                hideDuration: '280',
                showMethod: 'slideDown',
                hideMethod: 'fadeOut',
                rtl: {{ lang() == 'ar' ? 'true' : 'false' }},
                toastClass: 'toast intro-toast'
            };
        }

        @if(Session::has('success'))
            toastr.success(@json(Session::get('success')));
        @elseif(Session::has('danger'))
            toastr.error(@json(Session::get('danger')));
        @endif

        @if (count($errors) > 0)
            @foreach(array_reverse($errors->all()) as $error)
                toastr.error(@json($error));
            @endforeach
        @endif
    });
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


<script>
    $( document ).ajaxSuccess(function( event, request, settings ,response ) {
        if (response.type == 'notAuth') {
            toastr.error(response.msg)
        }
    });
</script>
