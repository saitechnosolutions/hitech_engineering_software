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