require(['jquery', 'datetimepicker'], function () {

    // datetimepicker



    $(document).ready(function () {

        $(".alert").delay(3000).fadeOut(1000, function() {
            $(this).alert('close');
        });

            $(document).ready(function() {
                $('.datetimepicker').datetimepicker({
                    timepicker:false,
                    format : 'd-m-Y'
                });
            })

    });
});


