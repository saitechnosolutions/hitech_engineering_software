$(document).on("change", ".quotationproduct", function(){
    var productId = $(this).val();
    var row = $(this).closest("tr");

    axios.get('/quotationproduct/'+productId)
        .then(response => {
            console.log(response.data.brand);
            row.find(".hsn_no").val(response.data.hsn_code);
            row.find(".part_no").val(response.data.part_number);
            row.find(".rate").val(response.data.mrp_price);
            row.find(".amount").val(response.data.mrp_price * 1);
        })
        .catch(error => {
            console.error(error);
        });
});


function getTotalByCustomerType(quantity, rate, wholesale_price) {
    var customerType = $("#customer_type").val();

    if (customerType === 'mrp_customer') {
        return rate * quantity;
    } else {
        return wholesale_price * quantity;
    }
}


// function calculateRowAmount($row) {
//     var quantity = parseFloat($row.find(".quantity").val()) || 0;
//     var rate = parseFloat($row.find(".rate").val()) || 0;
//     var wholesale_price = parseFloat($row.find(".wholesale_price").val()) || 0;
//     var disc_percentage = parseFloat($row.find(".disc_percentage").val()) || 0;
//     var customerType = $("#customer_type").val();


//     if(customerType == 'mrp_customer')
//     {
//         var total = rate * quantity;
//     }
//     else
//     {
//         var total = wholesale_price * quantity;
//     }

//     var discount = total * (disc_percentage / 100);
//     var amount = total - discount;

//     $row.find(".amount").val(amount.toFixed(2));
//     return amount;
// }

function calculateRowAmount($row) {
    var quantity = parseFloat($row.find(".quantity").val()) || 0;
    var rate = parseFloat($row.find(".rate").val()) || 0;
    var wholesale_price = parseFloat($row.find(".wholesale_price").val()) || 0;
    var disc_percentage = parseFloat($row.find(".disc_percentage").val()) || 0;

    var total = getTotalByCustomerType(quantity, rate, wholesale_price);

    var discount = total * (disc_percentage / 100);
    var amount = total - discount;

    $row.find(".amount").val(amount.toFixed(2));
    return amount;
}

$(document).on("change", "#customer_type", function() {
    $("tr").each(function() {
        calculateRowAmount($(this));
    });
    calculateGSTAndTotal();
});



function calculateSubtotal() {
    var subtotal = 0;
    $("tr").each(function() {
        var amount = parseFloat($(this).find(".amount").val()) || 0;
        subtotal += amount;
    });
    $(".subtotal").val(subtotal.toFixed(2));
    return subtotal;
}


function calculateGSTAndTotal() {
    var subtotal = calculateSubtotal();

    var customer_type = $("#customer_type").val();
    var cgstPerc = parseFloat($(".cgst_percentage").val()) || 0;
    var cgstAmount = subtotal * (cgstPerc / 100);
    $(".cgst_amount").val(cgstAmount.toFixed(2));


    var sgstPerc = parseFloat($(".sgst_percentage").val()) || 0;
    var sgstAmount = subtotal * (sgstPerc / 100);
    $(".sgst_amount").val(sgstAmount.toFixed(2));


    var igstPerc = parseFloat($(".igst_percentage").val()) || 0;
    var igstAmount = subtotal * (igstPerc / 100);
    $(".igst_amount").val(igstAmount.toFixed(2));

    if(customer_type == 'mrp_customer')
    {
        var totalAmount = subtotal;
    }
    else
    {
        var totalAmount = subtotal + cgstAmount + sgstAmount + igstAmount;

    }
    $(".total_amount").val(totalAmount.toFixed(2));
}

$(document).on("input", ".quantity, .disc_percentage, .cgst_percentage, .sgst_percentage, .igst_percentage", function() {


    var $row = $(this).closest("tr");
    calculateRowAmount($row);
    calculateGSTAndTotal();
});

$(document).on('change', "#customer_id", function() {
    var customerId = $("#customer_id").val();
    axios.get('/get-customer-details/'+customerId)
    .then(function (response) {
         if(response.data.state_id == 29)
         {
            $(".cgst_percentage").val(9);
            $(".sgst_percentage").val(9);
             $(".igst_percentage").val('');
         }
         else
         {
            $(".cgst_percentage").val('');
            $(".sgst_percentage").val('');
            $(".igst_percentage").val(18);
         }
    })
    .catch(function (error) {
        console.error(error);
    });
});


$("#option_1_data").show();
$("#option_2_data, #option_3_data, #option_4_data, #option_5_data, #option_6_data").hide();

$("#option_1").addClass("active");
$(document).on("click", "#option_1, #option_2, #option_3, #option_4, #option_5, #option_6", function() {

    $("#option_1_data, #option_2_data, #option_3_data, #option_4_data, #option_5_data, #option_6_data").hide();
     $(".option-box").removeClass("active");
      $(this).addClass("active");
    const id = $(this).attr('id');
    $("#" + id + "_data").show();
});



$(document).on("click", ".moveToProduction", function() {
    var id = $(this).data("id");

    Swal.fire({
        title: "Are you sure?",
        text: "You want to move to production!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Move to Production!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: "/move_to_production",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                success: function (data) {
                    if (data && data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(data.message || 'Unexpected server response.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Failed to move to production.");
                }
            });
        }
    });
});


$(function () {
    $('.genealogy-tree ul').hide();
    $('.genealogy-tree>ul').show();
    $('.genealogy-tree ul.active').show();
    $('.genealogy-tree li').on('click', function (e) {
        var children = $(this).find('> ul');
        if (children.is(":visible")) children.hide('fast').removeClass('active');
        else children.show('fast').addClass('active');
        e.stopPropagation();
    });
});



$(document).on("click", ".invoiceComplete", function() {
    var id = $(this).data("id");

    $("#invoiceApproveModal").modal('show');
    $("#invoiceid").val(id);

    // Swal.fire({
    //     title: "Are you sure?",
    //     text: "This quotation invoice completed",
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Yes",
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         $.ajax({
    //             method: "POST",
    //             url: "/invoice_status_completed",
    //             data: {
    //                 _token: $('meta[name="csrf-token"]').attr('content'),
    //                 id: id
    //             },
    //             success: function (data) {
    //                 if (data && data.status === 'success') {
    //                     toastr.success(data.message);
    //                     setTimeout(function () {
    //                         location.reload();
    //                     }, 1000);
    //                 } else {
    //                     toastr.error(data.message || 'Unexpected server response.');
    //                 }
    //             },
    //             error: function (xhr) {
    //                 toastr.error("Failed to move to production.");
    //             }
    //         });
    //     }
    // });
});
