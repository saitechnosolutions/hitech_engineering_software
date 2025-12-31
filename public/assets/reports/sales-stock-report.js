$(document).on("submit", "#sales-stock-report-filter-form", function (e) {
    e.preventDefault();

    var form = this;

    var url = form.getAttribute("action");
    const preloader = document.getElementById("preloader");
    if (preloader) {
        preloader.style.display = "block";
    }
    axios
        .post(url, form, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log(response.data);
            if (response.data && response.data.status === "success") {
                if ($.fn.DataTable.isDataTable("#sales-stock-table")) {
                    $("#sales-stock-table").DataTable().clear().destroy();
                }

                var columns = [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1,
                    },
                    { data: "quotation_no", name: "quotation_no" },
                    { data: "quotation_date", name: "quotation_date" },
                    { data: "customer", name: "customer" },
                    { data: "batch_date", name: "batch_date" },
                    {
                        data: "total_collectable_amount",
                        name: "total_collectable_amount",
                    },
                ];

                $("#sales-stock-table").DataTable({
                    data: response.data.data,
                    columns: columns,
                    processing: true,
                    responsive: true,
                    pageLength: 10,
                    order: [[1, "asc"]],
                });

                // Update export links after successful data load
                updateExportLinks();
            } else {
                toastr.error(
                    response.data.message ||
                        "Unexpected response from the server."
                );
            }
        })
        .catch((error) => {
            console.error(error);
            toastr.error("Error fetching data");
        })
        .finally(function () {
            // Hide preloader after response
            if (preloader) {
                preloader.style.display = "none";
            }
        });
});

function updateExportLinks() {
    var formData = new FormData(document.querySelector(".reportSubmission"));
    var params = new URLSearchParams();

    for (var pair of formData.entries()) {
        if (pair[1]) {
            // Only add if value is not empty
            params.append(pair[0], pair[1]);
        }
    }

    var queryString = params.toString();

    // Update Excel export link
    var excelUrl = "/sales-stock-report-export-excel?" + queryString;
    $("#excelExport").attr("href", excelUrl);

    // Update PDF export link
    var pdfUrl = "/sales-stock-report-export-pdf?" + queryString;
    $("#pdfExport").attr("href", pdfUrl);
}

// Initialize export links on page load
$(document).ready(function () {
    updateExportLinks();
});
