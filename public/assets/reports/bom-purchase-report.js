$(document).on("submit", "#bomPurchaseReportFilter", function(e) {
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


            if ($.fn.DataTable.isDataTable("#bom-purchase-report-table")) {
                $("#bom-purchase-report-table").DataTable().clear().destroy();
            }

            var columns = [
               { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'batch', name: 'batch' },
                { data: 'quotation', name: 'quotation' },
                { data: 'bom', name: 'bom' },
                { data: 'bom_category', name: 'bom_category' },
                { data: 'unit', name: 'unit' },
                { data: 'total_unit', name: 'total_unit' },

            ];

            $("#bom-purchase-report-table").DataTable({
                data: response.data.data,
                columns: columns,

                processing: true,
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']]

            });

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
