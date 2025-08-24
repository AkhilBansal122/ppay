<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h2 { margin: 0; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Generated at: {{ $date }}</p>

    <table>
        <thead>
            <tr>
<th>Sr No</th>

                <th>User Name</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Platform Charge</th>
                <th>GST</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $key=> $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->user_name ?? 'N/A' }}</td>
                    <td>{{ number_format($row->amount, 2) }}</td>
                    <td>{{ $row->remark ?? 'N/A' }}</td>
                    <td>{{ number_format($row->platform_charge, 2) }}</td>
                    <td>{{ number_format($row->gst, 2) }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{  dateFormat($row->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
