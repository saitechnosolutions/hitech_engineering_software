
function create(form, url, datatable) {
    let formData = new FormData(form);
    const preloader = document.getElementById('preloader');

    preloader.style.display = 'block';

    axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(function (response) {

        console.log(response.data.status);
        if (response.data && response.data.status === 'success') {

            toastr.success(response.data.message);
            form.reset();
            console.log(response.data.redirectTo);
            if (response.data.redirectTo) {
                window.location.href = response.data.redirectTo;
            }

            $("#userCreateModal, #createCategoryModal, #consigneeCreateModal, #cargosCreateModal, #vendorsCreateModal, #vehicleCreateModal, #createPodEntry, #expenseCreateModal, #createCommisionBillEntry, #truckOwnerPaymentModal, #roleCreateModal, #createBatchModal, #productionHistoryModal, #allocatedispatchEmployeeModal, #createPaymentModal" ).modal('hide');

            datatable.ajax.reload(null, false);
        } else {
            toastr.error(response.data.message || 'Unexpected response from the server.');
        }
    })
    .catch(function (error) {
        if (error.response) {
            if (error.response.status === 422) {
                let errors = error.response.data.errors;
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0]);
                    }
                }
            } else {
                toastr.error(error.response.data?.message || 'An error occurred. Please try again.');
            }
        }
    })
    .finally(function () {
        preloader.style.display = 'none';
    });
}




$(document).on("submit", "#createUserForm, #createConsignorForm, #createFormSubmit", function(e) {
    e.preventDefault();
    var form = this;
    var url = form.getAttribute('action');
    var tableId = $(form).data('datatable-id');

    if (tableId) {
        var datatable = $(`#${tableId}`).DataTable();
        create(form, url, datatable);
    } else {
        create(form, url, datatable == null);
        // console.error('DataTable ID not found!');
    }
});
