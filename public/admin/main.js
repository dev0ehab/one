var lang = '{{ app()->getLocale() }}';
    
var select2         =   document.querySelector('.select2');
if(select2){
    $('.select2').select2({
        dir: $('html').attr('dir'),

    });
}
var date            =   document.querySelector('.date');
if(date){
    flatpickr('.date', {
        disableMobile: true,
        locale: lang,
        dateFormat: "Y-m-d",
        mode: "single",
    });
}