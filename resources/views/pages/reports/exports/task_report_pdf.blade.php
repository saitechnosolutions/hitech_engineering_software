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

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Task Title</th>
                    <th>Task Details</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Task Date</th>
                    <th>Next Run Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['task_title'] }}</td>
                        <td>{{ $row['task_details'] }}</td>
                        <td>{{ $row['assigned_to'] }}</td>
                        <td>{{ $row['status'] }}</td>
                        <td>{{ $row['task_date'] }}</td>
                        <td>{{ $row['next_run_date'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No data available for the selected filters.</p>
        </div>
    @endif
</body>

</html>