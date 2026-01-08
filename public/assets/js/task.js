$("#end_date").hide();
$("#start_date").hide();
$("#repeating_type").hide();

$(document).on("change", "#task_type", function(){
    var taskType = $(this).val();

    if(taskType == "single_time")
    {
        $("#start_date").show();
         $("#end_date").hide();
         $("#repeating_type").hide();
         $(".end_date").attr('required', false);
    }
    else
    {
        $("#end_date").show();
        $("#start_date").show();
        $("#repeating_type").show();
        $(".end_date").attr('required', true);
    }
});

$("#task_type").trigger("change");
