/**
 * Created by User on 25.04.2016.
 */

$(document).ready(function() {


    // CHOSEN
    // =================================================================
    // Require Chosen
    // http://harvesthq.github.io/chosen/
    // =================================================================


    /*подгружаем список лпу*/
    $.get(
        "ajax/getlpulist",
        {

        },
        function (data) {
            jQuery.each(data.lpu_list, function(i, val) {
               console.info(val);
                $("#lpu-select").append('<option value="'+val.LPUCODE+'">'+val.LPUCODE+" | "+val.NAME+'</option>');
            });

            $('#lpu-select').chosen();


        }, "json"
    ); //$.get  END
});