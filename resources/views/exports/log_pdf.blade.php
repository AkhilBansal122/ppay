<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            table-layout: auto; /* content के हिसाब से width लेगा */
        }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; word-wrap: break-word; }
        th { background: #f2f2f2; }
        h2 { margin: 0; }

        /* specific column widths */
        th:nth-child(1), td:nth-child(1) { width: 5%;  text-align: center; }  /* Sr No */
        th:nth-child(2), td:nth-child(2) { width: 10%; } /* Type */
        th:nth-child(3), td:nth-child(3) { width: 20%; } /* User Agent */
        th:nth-child(4), td:nth-child(4) { width: 20%; } /* End Point */
        th:nth-child(5), td:nth-child(5) { width: 30%; } /* Data */
        th:nth-child(6), td:nth-child(6) { width: 15%; } /* Date */
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Generated at: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Type</th>
                <th>User Agent</th>
                <th>End Point</th>
                <th>Data</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $key=> $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ str_replace('_',' ',$row->type) }}</td>
                    <td>{{ $row->user_agent }}</td>
                    <td>{{ $row->end_point }}</td>
                    <td>{{ $row->data }}</td>
                    <td>{{ dateFormat($row->created_at) }}</td>
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
