<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Component Name</th>
                <th>Unit Price</th>
                <th>Stock Quantity</th>
                <th>Stock Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($components as $index => $component)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $component->component_name }}</td>
                    <td>{{ $component->unit_price }}</td>
                    <td>{{ $component->stock_qty }}</td>
                    <td>{{ $component->stock_qty <= 50 ? 'Low Stock' : 'Available' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the system.</p>
    </div>
</body>

</html>