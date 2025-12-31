$(document).on("submit", ".collectionSubmission", function (e) {
    e.preventDefault();

    var form = this;
    var url = form.getAttribute("action");
    let preloader = document.getElementById("preloader");
    if (preloader) preloader.style.display = "block";

    axios
        .post(url, new FormData(form), {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log(response.data);
            if (response.data && response.data.status === "success") {
                // Find the first DataTable in the report section
                var table = $(".table-responsive table").first();
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().clear().destroy();
                }

                var columns = [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1,
                    },
                    { data: "customer_name", name: "Company Name" },
                    { data: "batch", name: "Batch" },
                    { data: "quotation_no", name: "Quotation No" },
                    { data: "quotation_date", name: "Quotation Date" },
                    { data: "rm", name: "RM" },
                    {
                        data: "total_collectable_amount",
                        name: "Quotation Amount",
                    },
                    { data: "received_amount", name: "Received Amount" },
                    { data: "pending_amount", name: "Pending Amount" },
                ];

                table.DataTable({
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
            if (preloader) preloader.style.display = "none";
        });
});

// Function to update export links with current filter parameters
function updateExportLinks() {
    var formData = new FormData(
        document.querySelector(".collectionSubmission")
    );
    var params = new URLSearchParams();

    for (var pair of formData.entries()) {
        if (pair[1]) {
            // Only add if value is not empty
            params.append(pair[0], pair[1]);
        }
    }

    var queryString = params.toString();

    // Update Excel export link
    var excelUrl = "/collection-report-export-excel?" + queryString;
    $("#excelExport").attr("href", excelUrl);

    // Update PDF export link
    var pdfUrl = "/collection-report-export-pdf?" + queryString;
    $("#pdfExport").attr("href", pdfUrl);
}

// Initialize export links on page load
$(document).ready(function () {
    updateExportLinks();
});
