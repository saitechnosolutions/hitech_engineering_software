$(document).on("submit", ".paymentcollectionSubmission", function (e) {
    e.preventDefault();

    let form = this;
    let url = form.getAttribute("action");
    let preloader = document.getElementById("preloader");

    preloader.style.display = "block";

    axios
        .post(url, new FormData(form))
        .then((response) => {
            if (response.data.status === "success") {
                $("#totalReceivedAmount").text("₹" + response.data.total);
                // Destroy existing table
                if ($.fn.DataTable.isDataTable("#dataTable_three")) {
                    $("#dataTable_three").DataTable().clear().destroy();
                }

                // Reinitialize with filtered data
                $("#dataTable_three").DataTable({
                    data: response.data.data,
                    processing: true,
                    responsive: true,
                    pageLength: 10,
                    order: [[1, "desc"]],
                    columns: [
                        {
                            data: null,
                            render: (data, type, row, meta) => meta.row + 1,
                            className: "text-center",
                        },
                        {
                            data: "payment_date",
                            className: "text-center",
                        },
                        {
                            data: "quotation_no",
                            className: "text-center",
                        },
                        {
                            data: "customer_name",
                            className: "text-center",
                        },
                        {
                            data: "amount",
                            render: (data) => "₹" + parseFloat(data).toFixed(2),
                            className: "text-center",
                        },
                        {
                            data: "remarks",
                            className: "text-center",
                        },
                    ],
                });
            } else {
                toastr.error(response.data.message || "No records found");
            }
        })
        .catch(() => {
            toastr.error("Something went wrong while filtering");
        })
        .finally(() => {
            preloader.style.display = "none";
        });
});
