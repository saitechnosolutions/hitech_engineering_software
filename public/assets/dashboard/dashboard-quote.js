// Initialize DataTable
var quoteTable = $("#QuotesStatustable").DataTable({
    columns: [
        { title: "Date" },
        { title: "Quotation No" },
        { title: "Customer Name" },
        { title: "Status" },
        { title: "Value" },
    ],
    columnDefs: [{ orderable: false, targets: [2, 3, 4] }],
});

// Filter Form Submission
$(document).on("submit", ".QuotestatusSubmission", function (e) {
    e.preventDefault();
    let form = this;
    let url = form.getAttribute("action");
    let preloader = document.getElementById("preloader");
    if (preloader) preloader.style.display = "block";

    axios
        .post(url, new FormData(form))
        .then((response) => {
            quoteTable.clear();

            if (
                response.data.status === "success" &&
                response.data.data.length > 0
            ) {
                response.data.data.forEach((row) => {
                    quoteTable.row.add([
                        row.date,
                        `<a href="#" style="color:red;font-weight:bold">${row.quotation_no}</a>`,
                        row.customer_name ?? "-",
                        `<span class="badge bg-success text-white">${
                            row.status ?? "-"
                        }</span>`,
                        row.value ?? "₹0.00",
                    ]);
                });
            } else {
                quoteTable.row.add([
                    '<td colspan="5" class="text-center text-muted fw-bold">No records found</td>',
                ]);
            }

            quoteTable.draw(false);
            if (preloader) preloader.style.display = "none";
        })
        .catch((err) => {
            console.error(err);
            toastr.error("Something went wrong");
            if (preloader) preloader.style.display = "none";
        });
});
