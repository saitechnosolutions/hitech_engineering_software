$(document).on("submit", "#taskReportFilter", function(e) {
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


            if ($.fn.DataTable.isDataTable("#taskReport")) {
                $("#taskReport").DataTable().clear().destroy();
            }

            var columns = [
               { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'title', name: 'title' },
                { data: 'task_date', name: 'task_date' },
                { data: 'task_details', name: 'task_details' },
                { data: 'status', name: 'status' },
                { data: 'assigned_to.name', name: 'assigned_to' },
                { data: 'priority', name: 'priority' },
                { data: 'task_type', name: 'task_type' },

            ];

            $("#taskReport").DataTable({
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
