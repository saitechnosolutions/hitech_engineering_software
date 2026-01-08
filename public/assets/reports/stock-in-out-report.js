$(document).on("submit", "#stockInOutReportFilter", function(e) {
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


            if ($.fn.DataTable.isDataTable("#stock-in-out-table")) {
                $("#stock-in-out-table").DataTable().clear().destroy();
            }

            var columns = [
               { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'product_id', name: 'product_id' },
                { data: 'inward_qty', name: 'inward_qty' },
                { data: 'inward_date', name: 'inward_date' },
                { data: 'outward_qty', name: 'outward_qty' },
                { data: 'outward_date', name: 'outward_date' },
                { data: 'quotation_id', name: 'quotation_id' },
                { data: 'quotation_batch_id', name: 'quotation_batch_id' },
                { data: 'stock_status', name: 'stock_status' },

            ];

            $("#stock-in-out-table").DataTable({
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
