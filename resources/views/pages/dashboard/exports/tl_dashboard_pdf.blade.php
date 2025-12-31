<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .quotation-card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .quotation-header {
            background-color: #f8f9fa;
            font-size: 13px;
            padding: 10px;
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
            padding: 10px;
            margin-bottom: 10px;
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
        <h1>Hi Tech Solutions</h1>
        <p>Generated Date: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Details</th>
                <th>BOM Name</th>
                <th>BOM Category</th>
                <th>Unit</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @if($quotations->count() > 0)
                @foreach($quotations as $quotation)
                    @php
                        $batchDetails = App\Models\QuotationBatch::whereJsonContains('quotation_ids', $quotation->id)->first();
                        $quotationCount = App\Models\QuotationProductionStages::with('bom')
                            ->where('quotation_id', $quotation->id)
                            ->whereIn('team_id', $teamIds)
                            ->where('status', 'pending')
                            ->get()
                            ->count();
                    @endphp

                    @if($quotationCount != 0)
                        @php
                            $quotationHeader = 'Quotation No: ' . $quotation->quotation_no . ' | Batch: ' . (formatDate($batchDetails?->batch_date) ?? 'N/A') . ' | Priority: ' . ($batchDetails?->priority ?? 'N/A') . ' | Customer: ' . ($quotation->customer?->customer_name ?? 'N/A');
                        @endphp

                        @if($quotation->quotationProducts)
                            @foreach($quotation->quotationProducts as $quotationProduct)
                                @php
                                    $bomCount = App\Models\QuotationProductionStages::with('bom')
                                        ->where('quotation_id', $quotation->id)
                                        ->where('product_id', $quotationProduct->product_id)
                                        ->whereIn('team_id', $teamIds)
                                        ->where('status', 'pending')
                                        ->get()
                                        ->count();
                                @endphp

                                @if($bomCount != 0)
                                    @php
                                        $boms = App\Models\QuotationProductionStages::with('bom')
                                            ->where('quotation_id', $quotation->id)
                                            ->where('product_id', $quotationProduct->product_id)
                                            ->whereIn('team_id', $teamIds)
                                            ->where('status', 'pending')
                                            ->orderBy('id')
                                            ->get();

                                        if ($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team') {
                                            $list = $boms->unique(function ($item) {
                                                return $item->bom->bom_category;
                                            });
                                        } else {
                                            $list = $boms;
                                        }

                                        $firstBom = true;
                                    @endphp

                                    @foreach($list as $bom)
                                        <tr>
                                            @if($firstBom)
                                                @php $firstBom = false; @endphp
                                                <td>
                                                    {{ $quotationHeader }}<br><br>
                                                    <strong>{{ $quotationProduct->product->part_number }}</strong><br>
                                                    {{ $quotationProduct->product->product_name }}<br>
                                                    Variation: {{ $quotationProduct->product->variation }}<br>
                                                    <strong>Qty: {{ $quotationProduct->quantity }}</strong>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td>
                                                @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                    {{ $bom->bom->bom_category }}
                                                @else
                                                    {{ $bom?->bom?->bom_name }}
                                                @endif
                                            </td>

                                            <td>
                                                @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                    -
                                                @else
                                                    {{ $bom?->bom?->bom_category }}
                                                @endif
                                            </td>

                                            <td>
                                                @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                    -
                                                @else
                                                    {{ $bom?->bom?->bom_unit }}
                                                @endif
                                            </td>

                                            <td>
                                                @if($roleName == 'Team Leader - Packing Team' || $roleName == 'Team Leader - Dispatch Team')
                                                    @php
                                                        $receivedCompletedQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)
                                                            ->where('product_id', $quotationProduct->product_id)
                                                            ->where('bom_id', $bom->bom_id)
                                                            ->where('production_type', 'product')
                                                            ->where('team_name', null)
                                                            ->sum('completed_qty');

                                                        $packingQty = App\Models\ProductionHistory::where('quotation_id', $quotation->id)
                                                            ->where('product_id', $quotationProduct->product_id)
                                                            ->where('bom_id', $bom->bom_id)
                                                            ->where('production_type', 'product')
                                                            ->where('team_name', 'packing')
                                                            ->sum('completed_qty');

                                                        $totqty = $bom->bom->bom_qty * $quotationProduct->quantity;
                                                    @endphp
                                                    R: {{ $receivedCompletedQty }}<br>
                                                    A: {{ $packingQty }}<br>
                                                    T: {{ $totqty }}
                                                @else
                                                    {{ $bom?->bom?->bom_qty }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>

    @if($quotations->count() == 0)
        <div class="no-data">
            <p>No quotations found for the current user role and permissions.</p>
        </div>
    @endif
</body>

</html>