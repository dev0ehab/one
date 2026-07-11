<script>
    // Function to check if any items are selected and show/hide delete button accordingly
    function checkSelectedItems() {
        var count = 0;
        $(".checkSingle").each(function () {
            if (this.checked)
                count++;
        });
        
        if (count > 0) {
            $('.delete_all_button').show().addClass('show');
        } else {
            $('.delete_all_button').hide().removeClass('show');
            $("#checkedAll").prop("checked", false);
        }
    }
    
    // Hide button initially
    $('.delete_all_button').hide().removeClass('show');
    
    // Check on page load
    $(document).ready(function() {
        checkSelectedItems();
    });
    
    // Monitor for table updates and hide button if no items selected
    var observer = new MutationObserver(function(mutations) {
        var hasCheckboxes = $('.checkSingle').length > 0;
        if (hasCheckboxes) {
            setTimeout(function() {
                checkSelectedItems();
            }, 100);
        } else {
            $('.delete_all_button').hide();
        }
    });
    
    // Start observing when table content changes
    $(document).ready(function() {
        var targetNode = document.querySelector('.table_content_append');
        if (targetNode) {
            observer.observe(targetNode, {
                childList: true,
                subtree: true
            });
        }
    });
    
    $(document).on('change', '#checkedAll', function () {
        if (this.checked) {
            $(".checkSingle").each(function (index, element) {
                this.checked = true;
            })
            $('.delete_all_button').show().addClass('show');
        } else {
            $(".checkSingle").each(function () {
                this.checked = false;
            })
            $('.delete_all_button').hide().removeClass('show');
        }
    });
    
    $(document).on('click', '.checkSingle', function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function () {
                if (!this.checked)
                    isAllChecked = 1;
            })
            if (isAllChecked == 0) { 
                $("#checkedAll").prop("checked", true); 
            }
            $('.delete_all_button').show().addClass('show');
        } else {
            checkSelectedItems();
        }
    });
    
    $('.delete_all_button').on('click', function (e) {
        e.preventDefault()
        Swal.fire({
            title: "{{__('admin.are_you_want_to_continue')}}",
            text: "{{__('admin.are_you_want_to_complete_the_delete_selected_process')}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__('admin.confirm')}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: '{{__('admin.cancel')}}',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then((result) => {
            if (result.value) {
                var usersIds = [];
                $('.checkSingle:checked').each(function () {
                    var id = $(this).attr('id');
                    usersIds.push({
                        id: id,
                    });
                });
    
                var requestData = JSON.stringify(usersIds);
    
                if (usersIds.length > 0) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: $(this).data('route'),
                        data: { data: requestData },
                        success: function (msg) {
                            if (msg == 'success') {
                                $('.delete_all_button').hide().removeClass('show')
                                Swal.fire({
                                        position: 'top-start',
                                        type: 'success',
                                        title: '{{__('admin.the_selected_has_been_successfully_deleted')}}',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                })
                                getData({ 'searchArray': searchArray() });
                            }
                        },
                        error: function (response) {
                            Swal.fire({
                                position: 'top-start',
                                type: 'warning',
                                title: response.responseJSON.message,
                                showConfirmButton: false,
                                timer: 1500,
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            })
                        }
                    });
                }
            }
        })
    });
    </script>
    