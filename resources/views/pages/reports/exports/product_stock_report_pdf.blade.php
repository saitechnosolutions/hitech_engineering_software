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
                <th>S.No</th>
                <th>Product Name</th>
                <th>Part Number</th>
                <th>Variation</th>
                <th>Color</th>
                <th>Material</th>
                <th>Available Quantity</th>
            </tr>
        </thead>
        <tbody>
            @if($products->count() > 0)
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->part_number }}</td>
                        <td>{{ $product->variation ?? '-' }}</td>
                        <td>{{ $product->color ?? '-' }}</td>
                        <td>{{ $product->material ?? '-' }}</td>
                        <td>{{ $product->stock_qty }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    @if($products->count() == 0)
        <div style="text-align: center; padding: 40px; color: #666; font-style: italic;">
            <p>No data found for the product stock report.</p>
        </div>
    @endif
</body>

</html>