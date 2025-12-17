$("#end_date").hide();
$("#start_date").hide();
$("#repeating_type").hide();

$(document).on("change", "#task_type", function(){
    var taskType = $(this).val();
    alert(taskType);
    if(taskType == "single_time")
    {
        $("#start_date").show();
         $("#end_date").hide();
         $("#repeating_type").hide();
    }
    else
    {
        $("#end_date").show();
        $("#start_date").show();
        $("#repeating_type").show();
    }
});
