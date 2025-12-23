
/*
 Template Name: Annex - Bootstrap 4 Admin Dashboard
 Author: Mannatthemes
 Website: www.mannatthemes.com
 File: Morris init js
 */

!function($) {
    "use strict";

    var Dashboard = function() {};

    //creates line chart
    Dashboard.prototype.createLineChart = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          hideHover: 'auto',
          gridLineColor: '#eef0f2',
          resize: true, //defaulted to true
          lineColors: lineColors
        });
    },


    //creates area chart
    Dashboard.prototype.createAreaChart = function(element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 3,
            lineWidth: 2,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            resize: true,
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            lineColors: lineColors,
            lineWidth: 0,
            fillOpacity: 0.1,
            xLabelMargin: 10,
            yLabelMargin: 10,
            grid: false,
            axes: false,
            pointSize: 0
        });
    },

    //creates Donut chart
    Dashboard.prototype.createDonutChart = function(element, data, colors) {
        Morris.Donut({
            element: element,
            data: data,
            resize: true,
            colors: colors
        });
    },

    Dashboard.prototype.init = function() {

        //create line chart
        var $data  = [
            { y: '2009', a: 0, b: 0, c: 0 },
            { y: '2010', a: 150,  b: 30, c: 50 },
            { y: '2011', a: 20,  b: 50, c: 150 },
            { y: '2012', a: 150,  b: 80, c: 40 },
            { y: '2013', a: 20,  b: 110, c: 150 },
            { y: '2014', a: 50,  b: 150, c: 40 },
            { y: '2015', a: 150, b: 170, c: 130 }
          ];
        this.createLineChart('multi-line-chart', $data, 'y', ['a', 'b', 'c'], ['Series A', 'Series B', 'Series C'], ['#007BFF', '#00bcd2', '#e785da']);

        //creating area chart
        var $areaData = [
            {y: '2011', a: 10, b: 15},
            {y: '2012', a: 30, b: 35},
            {y: '2013', a: 10, b: 25},
            {y: '2014', a: 55, b: 45},
            {y: '2015', a: 30, b: 20},
            {y: '2016', a: 40, b: 35},
            {y: '2017', a: 10, b: 25},
            {y: '2018', a: 25, b: 30}
        ];
        this.createAreaChart('morris-area-chart', 0, 0, $areaData, 'y', ['a', 'b'], ['Series A', 'Series B'], ['#00c292', '#03a9f3']);

        //creating donut chart
        var $donutData = [
            {label: "USA", value: 12},
            {label: "Canada", value: 30},
            {label: "London", value: 20}
        ];
        this.createDonutChart('morris-donut-chart', $donutData, ['#40a4f1', '#5b6be8', '#c1c5e2']);
    },
    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard


   $(document).on("click", ".addProductionQty", function() {

    var id = $(this).data("productid");
    var quotationid = $(this).data("quotationid");
    var quantity = $(this).data("quantity");
    var datatype = $(this).data("datatype");
    var team = $(this).data("team");


    axios.get('/getBomDetails/' + id + '/' + datatype)
    .then(response => {
        const dataType = response.data.dataType;

        const data = response.data.data;
        const productName = response.data.productName;

        $("#productName").text(productName);
        $("#productionHistoryBody").html("");

        let rows = "";

        data.forEach(item => {
            rows += `
                <tr>
                    <td>${dataType === 'product' ? item.bom_category : item.bom_name}</td>
                    <td>${item.bom_qty * quantity}</td>
                    <td>
                        <input type="text"
                               name="received_qty[${item.id}]"
                               class="form-control"
                               value="${item.received_qty ?? ''}">
                    </td>
                </tr>
            `;
        });

        $("#productionHistoryBody").html(rows);
    })

        .catch(error => {
            console.error(error);
            $("#productionHistoryDetails").html('<p>Error loading production history.</p>');
        });

    $("#productionHistoryModal").modal('show');
    $("#product_id").val(id);
    $("#orderedQty").text(" - Qty : "+quantity);
    $("#quotationid").val(quotationid);
    $("#productionType").val(datatype);
    $("#team").val(team);
});







    $(document).on("click", ".allocateEmployee", function(){
        var productid = $(this).data("productid");
        var quotationid = $(this).data("quotationid");

        $("#allocateEmployeeModal").modal('show');
        $("#product_id_employee").val(productid);
        $("#production_quotationid").val(quotationid);
    });

   $(document).on("click", ".showProductionHistory", function() {
    var id = $(this).data("id");

    $("#productionHistoryShowModal").modal('show');
    $("#production_id").val(id);

    // Clear previous table
    $("#productionHistoryDetails").html('');

    axios.get('/getProductionDetails/' + id)
        .then(response => {
            console.log(response.data);
            const data = response.data;

            if (data.length === 0) {
                $("#productionHistoryDetails").html('<p class="text-danger text-center" style="font-size:20px;font-weight:bold">No production history found.</p>');
                return;
            }

            // Build table
            let table = '<table class="table table-bordered">';
            table += `<thead>
            <tr>
                <th>Date</th>
                <th>Quotation No</th>
                <th>Product Name</th>
                <th>BOM Name</th>
                <th>Completed Quantity</th>
                </tr>
            </thead>`;
            table += '<tbody>';

            data.forEach(item => {
                table += `<tr>
                    <td>${item.entry_date}</td>
                    <td>${item.production_stage.quotation.quotation_no}</td>
                    <td>${item.production_stage.product.product_name}</td>
                    <td>${item.production_stage.bom.bom_name}</td>
                    <td>${item.completed_qty}</td>
                </tr>`;
            });

            table += '</tbody></table>';

            // Append to modal
            $("#productionHistoryDetails").html(table);

        })
        .catch(error => {
            console.error(error);
            $("#productionHistoryDetails").html('<p>Error loading production history.</p>');
        });
});


  $(document).on("click", ".statusUpdate", function(){
    var quotationId = $(this).data("quotationid");
    var productid   = $(this).data("productid");
    var bomid       = $(this).data("bom-id");
    var stage       = $(this).data("stage");

    Swal.fire({
        title: "Are you sure?",
        text: "You have Completed this BOM",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Complete!",
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: '/productionUpdate',
                data: {
                    quotation_id: quotationId,
                    product_id: productid,
                    bom_id: bomid,
                    stage: stage,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data && data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(data.message || 'Unexpected server response.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Request failed.");
                }
            });
        }

    });

});

  $(document).on("click", ".completeProduct", function(){
    var quotationId = $(this).data("quotationid");
    var productid   = $(this).data("productid");
    var bomid       = $(this).data("bom-id");
    var stage       = $(this).data("stage");

    Swal.fire({
        title: "Are you sure?",
        text: "You have Completed this Product",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Complete!",
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: '/productComplete',
                data: {
                    quotation_id: quotationId,
                    product_id: productid,
                    bom_id: bomid,
                    stage: stage,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data && data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(data.message || 'Unexpected server response.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Request failed.");
                }
            });
        }

    });

});

$(document).on("click", ".allocateDispatchTeam", function(){

    var quotationId = $(this).data("quotationid");

    $("#allocatedispatchEmployeeModal").modal('show');
    $("#dispatch_quotationid").val(quotationId);
});

$(document).on("click", ".productDispatched", function(){
    var quotationId = $("#dispatch_quotation_id").val();

    Swal.fire({
        title: "Are you sure?",
        text: "You have Dispatch this Quotation",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Dispatched!",
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: '/productDispatch',
                data: {
                    quotation_id: quotationId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data && data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(data.message || 'Unexpected server response.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Request failed.");
                }
            });
        }

    });
});

$(document).on("click", ".partialDispatched", function(){

     var quotationId = $("#dispatch_quotation_id").val();


});

$(document).on("click", ".partialDispatched", function() {

   var quotationId = $("#dispatch_quotation_id").val();


    axios.get('/getQuotationDetails/' + quotationId)
.then(response => {

    $("#dispatchModal").modal('hide');
    $("#partialDispatchModal").modal('show');

    const data = response.data.data;   // this is an object
    const products = data.quotation_products; // THIS is array
    console.log(products);
    let rows = "";

    products.forEach(item => {
        rows += `
            <tr>
                <td>${data.quotation_no}</td>
                <td>${item.product.product_name}</td>
                <td>${item.quantity}</td>
                <td>${item.partial_qty}</td>
                <td>
                    <input type="text" name="qty[]" class="form-control" max="${item.quantity - item.partial_qty}">
                    <input type="hidden" name="product_id[]" value="${item.product.id}" class="form-control" >
                    <input type="hidden" name="quotation_id" value="${data.id}" class="form-control" >
                </td>
            </tr>
        `;
    });

    $("#partialDispatchBody").html(rows);
    })

        .catch(error => {
            console.error(error);
            $("#productionHistoryDetails").html('<p>Error loading production history.</p>');
        });


});


$(document).on("click", ".updateStockOnProduct", function(){

     var quotationId = $("#dispatch_quotation_id").val();


    Swal.fire({
        title: "Are you sure?",
        text: "You have Dispatch this Quotation in Hitech",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Dispatched!",
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: '/productDispatchForHitech',
                data: {
                    quotation_id: quotationId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data && data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(data.message || 'Unexpected server response.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Request failed.");
                }
            });
        }

    });
});



}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Dashboard.init();
}(window.jQuery);




