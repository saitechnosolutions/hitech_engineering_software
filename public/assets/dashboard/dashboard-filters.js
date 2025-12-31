// Initialize DataTable
var orderTable = $("#orderStatusreport").DataTable({
    columns: [
        { title: "Order ID" },
        { title: "Customer" },
        { title: "Status" },
        { title: "Delivery" },
        { title: "Value" },
        { title: "Payments" },
    ],
    columnDefs: [{ orderable: false, targets: [2, 3, 4, 5] }],
});

// Handle Filter Form Submission
$(document).on("submit", ".orderstatusSubmission", function (e) {
    e.preventDefault();

    let form = this;
    let url = form.getAttribute("action");
    let preloader = document.getElementById("preloader");
    if (preloader) preloader.style.display = "block";

    axios
        .post(url, new FormData(form))
        .then((response) => {
            orderTable.clear();

            if (
                response.data.status === "success" &&
                response.data.data.length > 0
            ) {
                response.data.data.forEach((row) => {
                    orderTable.row.add([
                        `<a href="#" style="color:#ec1c24;font-weight:bold">${row.quotation_no}</a>`,
                        row.customer_name ?? "-",
                        `<span class="badge bg-secondary text-white" style="width:150px">${
                            row.status ?? "-"
                        }</span>`,
                        row.delivery ?? "-",
                        row.value ?? "₹0.00",
                        row.payments ?? "₹0.00",
                    ]);
                });
            } else {
                orderTable.row.add([
                    '<td colspan="6" class="text-center fw-bold text-muted">No records found</td>',
                ]);
            }

            orderTable.draw(false);
        })
        .catch((error) => {
            console.error(error);
            toastr.error("Something went wrong");
        })
        .finally(() => {
            if (preloader) preloader.style.display = "none";
        });
});
