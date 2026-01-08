<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Challan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;

            color: #333;
            margin: 0;
            padding: 0px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
            padding:0;
        }

        .header p {
            margin: 0px 0;
            font-size: 14px;
            color: #666;
        }

        .quotation-card {
            border: 1px solid #ddd;

            page-break-inside: avoid;
        }

        .quotation-header {
            background-color: #f8f9fa;
            font-size: 13px;

            border: 1px solid #333;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .quotation-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .quotation-info {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0 0;
        }

        .product-section {
            padding: 15px;
        }

        .product-header {
            background-color: #e9ecef;


            font-weight: bold;
        }

        .product-item {
            border: 1px solid #eee;
            margin-bottom: 10px;
            padding: 10px;
        }

        .product-details {
            display: flex;
            margin-bottom: 10px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid #ddd;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .product-meta {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .bom-section {
            margin-top: 10px;
        }

        .bom-header {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            color: #333;
        }

        .bom-item {
            background-color: #f8f9fa;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 11px;
        }

        .bom-category {
            font-weight: bold;
            color: #495057;
        }

        .bom-details {
            color: #6c757d;
            margin-top: 3px;
        }

        .status-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .status-info {
            font-size: 11px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
            margin-right: 5px;
            margin-bottom: 3px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        @media print {
            body {
                padding: 15px;
            }

            .quotation-card {
                page-break-inside: avoid;
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Hi-Tech Engineering</h1>
        <h4>Delivery Challan</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>DC No</th>
                <td>{{ $deliveryChallanDetails->delivery_challan_id }}</td>
                <th>DC Date</th>
                <td>{{ formatDate($deliveryChallanDetails->delivery_challan_date) }}</td>
            </tr>

                @if($productionHistorys = App\Models\ProductionHistory::where('delivery_challan_id', $deliveryChallanDetails->id)
    ->where('delivery_challan_status', 'created')
    ->get())

    @foreach($productionHistorys->groupBy('quotation_id') as $history)

        <tr>
            <th colspan="4">
                Quotation No : {{ $history->first()->quotation->quotation_no }}
                | Batch : {{ $history->first()->quotation->batch_date }}
                | Customer Name : {{ $history->first()->quotation->customer->customer_name }}
            </th>
        </tr>

        <tr>
            <th colspan="3">Product Name</th>
            <th>Qty</th>
        </tr>

        @foreach($history->groupBy('product_id') as $productGroup)
            <tr>
                <td colspan="3">
                    {{ $productGroup->first()->product->product_name }}
                </td>
                <td>
                    {{ $productGroup->sum('completed_qty') }}
                </td>
            </tr>
        @endforeach

    @endforeach
@endif
<tr>
                <th>Transporter Sign</th>
                <td></td>
                <th>Receiver Sign</th>
                <td></td>
            </tr>

        </tbody>
    </table>


</body>

</html>

