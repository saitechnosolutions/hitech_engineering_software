<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Barcode</title>

<style>
    body {
        font-family: Arial;
        margin: 0;
        padding: 10px;
        font-size: 13px;
    }

    /* FIXED LABEL SIZE */
    .label-table {
        width: 100mm !important;      /* LABEL WIDTH */
        {{--  height: 80mm !important;      /* LABEL HEIGHT */  --}}
        border: 1px solid black;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .label-table td,
    .label-table th {
        {{--  border: 1px solid black;  --}}
        padding: 5px;
        vertical-align: top;
    }
    .barcode-stretch {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    width: 100%;
    letter-spacing: 9px;

}

    /* PRINT SETTINGS */
    @media print {
        body {
            margin: 0;
        }
        .label-table {
            page-break-inside: avoid;
        }
    }
</style>
</head>

<body>

<table class="label-table">
    <tr>
        <td style="vertical-align: bottom; width:40mm;">
            <img src="/assets/images/logo.png" style="width:38mm">
        </td>
        <td style="line-height:1.5; font-size:8px;">
            <span style="font-weight:bold;font-size:9px">Manufactured By</span><br>
            <span style="font-weight:bold;font-size:10px;text-transform:uppercase">
                Hi-tech Engineering
            </span><br>
            No: 2/109, Nattamangalam Main Road, Maniyanur,<br>
            Near Vinayagar Temple, Salem - 636010.
        </td>
    </tr>

    <tr>
        <td style="font-weight: bold; font-size:11px;line-height:1.5">
            <span style="margin-bottom:5px">SKU &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $products?->part_number }}</span><br>
            <span style="margin-top:5px">Part Name : {{ $products->product_name }}</span><br>

            <svg id="barcode" style="margin-top:5px;width:100%"></svg>
            <div id="barcodeText" class="barcode-stretch" style="font-size:8px;text-align:center;"></div>
        </td>

        <td style="font-size:11px;vertical-align:bottom;font-weight:bold;line-height:1.7">
            <span>Color &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $products->variation }}</span><br>
            <span>Material &nbsp;&nbsp;&nbsp;&nbsp;&nbsp: Mild Steel</span><br>
            <span>Net Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 1 Nos</span>
        </td>
    </tr>

    <tr style="border:0px solid transparent">
        <td colspan="2" style="font-size:18px;font-weight:bold;text-align:left;border:0px solid transparent">
    MRP Rs. {{ number_format($products->mrp_price, 2) }}/Nos<br>
    <span style="display:block; text-align:left;font-size:8px">(Incl of All taxes)</span>
</td>

    </tr>

    <tr>
        <td colspan="2" style="text-align:right;font-size:10px">
            Manufacturing Country : India
        </td>
    </tr>

    <tr>
        <td colspan="2" style="font-size:10px;border-top:1px solid black">
            For Customer Support : +91-6383745690, info@hiexhaust.com<br>
            Buy Our Products Online at www.hiexhaust.com
        </td>
    </tr>
</table>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    let partNumber = {{ $products?->part_number }};

    JsBarcode("#barcode", partNumber, {
        format: "CODE128",
        displayValue: false,
        width: 1.6,
        height: 45,
        margin: 0
    });

    document.getElementById("barcodeText").textContent = partNumber;
</script>

</body>
</html>
