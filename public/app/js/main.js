require(['jquery', 'moment', 'datetimepicker'], function () {

    // datetimepicker
    $(document).ready(function () {
        $('.datetimepicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD',
            locale: 'ar-ma',
            useCurrent: false,
            showClear: true,
            showClose: true,
        });
        $(".alert").delay(3000).fadeOut(1000, function() {
            $(this).alert('close');
        });
    });
});


