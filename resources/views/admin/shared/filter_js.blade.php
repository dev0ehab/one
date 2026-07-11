<script>
    getData()
    // input date js
    var $list = $(":input[type='date']");
        $(window).on('load', function() {
            if ($($list).length > 0) {
                $(document).find($list).addClass("custom-input-date");
                $(document).find($list).parents(".controls").addClass("parent-input-date");
            }
    
            var statrtLength        =   $('#start').length;
            var endLength           =   $('#start').length;
    
    
            if(statrtLength > 0 && endLength > 0){
                var start = flatpickr(document.querySelector('#start'), {
                    wrap: true,
                    disableMobile: true,
                    locale: "{{ app()->getLocale() }}",
                    dateFormat: "m-d-Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        end.set('minDate', dateStr);
                    }
                });
    
                var end = flatpickr(document.querySelector('#end'), {
                    wrap: true,
                    disableMobile: true,
                    locale: "{{ app()->getLocale() }}",
                    dateFormat: "m-d-Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        start.set('maxDate', dateStr);
                    }
                });
            }
    
        });
    

        $(".btn-searchTable").on("click", function(e) {
            e.stopPropagation();
            var $btn = $(this);
            var $panel = $btn.closest(".neo-index-filter").find(".searchTable");
            var isOpen = $btn.toggleClass("active").hasClass("active");
            $panel.toggleClass("active", isOpen);
            $btn.attr("aria-expanded", isOpen ? "true" : "false");
            $(".layout_").toggleClass("active", isOpen && !$btn.closest(".neo-index-toolbar").length);
        });

        $(".btnClose").on("click", function () {
            var $filter = $(this).closest(".neo-index-filter");
            $filter.find(".searchTable").removeClass("active");
            $filter.find(".btn-searchTable").removeClass("active").attr("aria-expanded", "false");
            $(".layout_").removeClass("active");
        });

        $(".layout_").on("click", function () {
            $(".btn-searchTable.active").click();
        });

    function searchArray() {
        var searchArray = {} ;
        $('.search-input').each(function(key, input) {
            searchArray[$(this).attr('name')] = $(this).val()
        });
        return  searchArray
    }

    $(document).on('change', '.search-input', function (e) {
        e.preventDefault();
        getData({'searchArray' : searchArray()} )
        var currentHref = $('a[href*="export"]').attr('href');
        var queryString = $.param(searchArray());

        if (currentHref.includes('?')) {
            // If it does, append the new query parameter with '&'
            currentHref += '&' + queryString;
        } else {
            // If it doesn't, append the new query parameter with '?'
            currentHref += '?' + queryString;
        }
        $('a[href*="export"]').attr('href', currentHref);
    });

    $(document).on('click', '.reloadTable', function (e) {
        e.preventDefault();
        $('.delete_all_button').hide().removeClass('show');
        getData();
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        getData({page : $(this).attr('href').split('page=')[1]  , 'searchArray' : searchArray() } )
    });

    $('.table_loader').fadeOut('slow');


    function getData(array) {
        $.ajax({
            type: "get",
            url: "{{$index_route}}",
            data: array,
            dataType: "json",
            cache: false ,
            beforeSend: function() {
                $('.table_loader').fadeIn('slow');
            },
            success: function (response) {
                $('.table_content_append').html(response.html)
                $('.table_loader').fadeOut('slow');
                // Hide delete button after loading new data
                $('.delete_all_button').hide().removeClass('show');
                // Uncheck all checkboxes
                $('.checkSingle').prop('checked', false);
                $('#checkedAll').prop('checked', false);
            }
        });
    }
    
    $('.clean-input').on('click' ,function(){
        $(this).siblings('input').val(null);
        $(this).siblings('select').val(null);
        getData({'searchArray' : searchArray()} )
    });
</script>