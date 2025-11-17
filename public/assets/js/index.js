$(document).ready(function () {
    // Add Row
    $("#addRow").click(function () {

         $(".js-example-basic-single").select2("destroy");

        setTimeout(function () {
            $(".js-example-basic-single").select2();
        }, 100);

        var newRow = $("#stagesTable tbody tr:first").clone(); 
        newRow.find("select").val("");
        newRow.find('input[type="text"]').val('');
        $("#stagesTable tbody").append(newRow);
    });



    // Remove Row
    $(document).on("click", ".removeRow", function () {
        if ($("#stagesTable tbody tr").length > 1) {
            $(this).closest("tr").remove();
        } else {
            alert("At least one row is required!");
        }
    });


});



