

$(document).on("submit", "#employeeFilter", function(e) {
    e.preventDefault();

    var form = this;

    var url = form.getAttribute('action');
    const preloader = document.getElementById('preloader');
    preloader.style.display = 'block';
    axios.post(url, form, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {
        console.log(response.data);
        if (response.data && response.data.status === 'success') {


            if ($.fn.DataTable.isDataTable("#employeeWiseProductionReport")) {
                $("#employeeWiseProductionReport").DataTable().clear().destroy();
            }

            var columns = [
               { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'employee_name', name: 'employee_name' },
                { data: 'quotation_no', name: 'quotation_no' },
                { data: 'product_name', name: 'product_name' },
                { data: 'team_name', name: 'team_name' },
                { data: 'product_qty', name: 'product_qty' },

            ];

            $("#employeeWiseProductionReport").DataTable({
                data: response.data.data,
                columns: columns,

                processing: true,
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']]

            });

            // Update export links after successful data load
            updateExportLinks();

        } else {
            toastr.error(response.data.message || 'Unexpected response from the server.');
        }
    })
    .catch(error => {
        console.error(error);
        toastr.error("Error fetching data");
    })
    .finally(function () {
        // Hide preloader after response
        preloader.style.display = 'none';
    });

});

// Function to update export links with current filter parameters
function updateExportLinks() {
    var formData = new FormData(document.querySelector("#employeeFilter"));
    var params = new URLSearchParams();

    for (var pair of formData.entries()) {
        if (pair[1]) {
            // Only add if value is not empty
            params.append(pair[0], pair[1]);
        }
    }

    var queryString = params.toString();

    // Update Excel export link
    var excelUrl = "/employee-wise-production-report-export-excel?" + queryString;
    $("#excelExport").attr("href", excelUrl);

    // Update PDF export link
    var pdfUrl = "/employee-wise-production-report-export-pdf?" + queryString;
    $("#pdfExport").attr("href", pdfUrl);
}

// Initialize export links on page load
$(document).ready(function () {
    updateExportLinks();
});
