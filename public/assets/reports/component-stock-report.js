$(document).on("submit", ".componentstockSubmission", function (e) {
    e.preventDefault();

    let form = this;
    let url = form.getAttribute("action");

    const preloader = document.getElementById("preloader");
    if (preloader) preloader.style.display = "block";

    let formData = new FormData(form);

    axios
        .post(url, formData)
        .then(function (response) {
            if (response.data.status === "success") {
                // Destroy existing DataTable
                if ($.fn.DataTable.isDataTable("#component-stock-table")) {
                    $("#component-stock-table").DataTable().clear().destroy();
                }

                $("#component-stock-table tbody").empty();
                $("#component-stock-table ").DataTable({
                    data: response.data.data || [],
                    processing: true,
                    responsive: true,
                    pageLength: 10,
                    order: [[1, "asc"]],
                    columns: [
                        {
                            data: null,
                            title: "S.No",
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row, meta) {
                                return (
                                    meta.row + meta.settings._iDisplayStart + 1
                                );
                            },
                        },

                        { data: "component_name", title: "Component Name" },
                        { data: "price", title: "Price" },
                        { data: "unit_price", title: "Unit Price" },
                        { data: "stock_qty", title: "Available Quantity" },
                    ],
                });

                // Update export links after successful data load
                updateExportLinks();
            } else {
                toastr.error(
                    response.data.message || "Unexpected server response"
                );
            }
        })
        .catch(function (error) {
            console.error(error);
            toastr.error("Error fetching data");
        })
        .finally(function () {
            if (preloader) preloader.style.display = "none";
        });
});

// Function to update export links with current filter parameters
function updateExportLinks() {
    var formData = new FormData(
        document.querySelector(".componentstockSubmission")
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
    var excelUrl = "/component-stock-report-export-excel?" + queryString;
    $("#excelExport").attr("href", excelUrl);

    // Update PDF export link
    var pdfUrl = "/component-stock-report-export-pdf?" + queryString;
    $("#pdfExport").attr("href", pdfUrl);
}

// Initialize export links on page load
$(document).ready(function () {
    updateExportLinks();
});
