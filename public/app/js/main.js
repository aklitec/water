require(['jquery', 'moment', 'datetimepicker','select'], function () {

    // datetimepicker
    $(document).ready(function () {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            showClear: true,
            showClose: true,
        });


        if($('.select2').length) {
            $('.select2').select2({
                width: '100%',
                 allowClear: true,
                placeholder: "Choisir ..."
            })
        }

        /* $("#selectClient").on("select2:select", function (e) {
             $("#clientId").val(e.params.data.id)
         });*/

        $(".select2").select2({
            ajax: {
                dataType: 'json',
                url:"/water_meter/api/client/",
                delay: 150,
                data: function (params) {
                    return  {
                        searchTerm: params.term
                    }
                },
                processResults: function(response){
                    return {
                        results: $.map(response, function(item) {
                            return {
                                id: item.id,
                                text: item.text,
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength:0,
        });



        /* $('.datetimepicker').datetimepicker({
             timepicker:false,
             format : 'd-m-Y'
         });*/

        $("#prevYear").on('click' ,function () {
            var currentYear =Number($("#currentYear").text());
            var id =$(this).attr('id');
            return manageYears(id)
        });

        if ($("#Table:not(:empty)") .length) {
            $("#spinner").show();
        } else {
            $("#spinner").hide();
        }

        $("#nextYear").on('click', function () {
            var id =$(this).attr('id');
            return manageYears(id)
        });

        function manageYears(id) {
            let cY;
            let nextY;
            let  prevY;
            var currentYear =Number($("#currentYear").text()); // 2019
            if(id==='prevYear'){
                cY = currentYear-1; // 2018
                nextY=currentYear; // 2019
                prevY=currentYear-2; // 2017

            }else if(id==='nextYear'){
                cY = currentYear+1; // 2019
                nextY=currentYear+2; //2020
                prevY=currentYear; //2018
            }

            $("#currentYear").text(cY);
            $("#prevYear").text(prevY);
            $("#nextYear").text(nextY);
        }


        $('.custom-file-input').on('change', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });


        $("#consumption_currentRecord").keyup(function (e) {
            $("#consumption_consumption").val($("#consumption_currentRecord").val() - $("#consumption_previousRecord").val());
            //let cc = $("#consumption_consumption").val();
            let cpm = 5;
            /*if (cc <= 5) {
                cpm = 5
            } else {
                cpm = 7
            }*/
            $("#consumption_costPerMeterCube").val(cpm)
        });


        $('.custom-file-input').on('change', function (event) {
            const inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });


    });

    $(".alert").delay(3000).fadeOut(1000, function () {
        $(this).alert('close');
    });


    //////  MODAL ////////////


});








