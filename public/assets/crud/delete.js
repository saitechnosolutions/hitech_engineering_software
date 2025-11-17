$(document).on("click", ".deleteBtn", function(){

    var table = $(this).data("table");
    var url = $(this).data("url");
    var row = $(this).closest('tr');

    Swal.fire({

        title: "Are you sure?",
        text: "You want to delete this record!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",

}).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
            method: "GET",
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                if (data && data.status === 'success') {
                    toastr.success(data.message);
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(data.message || 'Unexpected server response.');
                }
            },
            error: function (xhr) {
                toastr.error("Failed to delete the record.");
            }
        });
    }
});

});
