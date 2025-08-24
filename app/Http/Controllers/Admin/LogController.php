<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use  App\Models\User;
use  App\Models\Comission;
use  App\Models\RequestLog;
use Barryvdh\DomPDF\Facade\Pdf;


use App\Models\UserBank;
use Illuminate\Support\Arr;
use DB;
use Auth;
class LogController extends Controller
{


        function __construct(){
    $this->middleware('permission:log', ['only' => ['index','store','create','edit','destroy','update']]);

        $this->Model = new RequestLog;
        $this->columns = [
            "id",
            'type',
            'user_agent',
            'end_point',
            'created_at'
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.log.index');
    }

    public function getData(Request $request)
    {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        // $request->upload_type =2;
        $records = RequestLog::fetchData($request, $this->columns);
        $total = $records->get();
        if (isset($request->start)) {
            $banners = $records->offset($request->start)->limit($request->length)->get();
        } else {
            $banners = $records->offset($request->start)->limit(count($total))->get();
        }
        $result = [];
        $i = 1;
        foreach ($banners as $value) {
            $data = [];

            $data['srno'] = $i++;
            $data['id'] = $value->id;
            $data['type'] = str_replace('_', ' ', $value->type);
            $data['user_agent'] = $value-> user_agent;
            $data['end_point'] = $value-> end_point;
            $data['created_at'] = dateFormat($value->created_at); // Assuming created_at is a Carbon instance
            $result[] = $data;
        }

        $data = json_encode([
            'data' => $result,
            'recordsTotal' => count($total),
            'recordsFiltered' => count($total),
        ]);
        return $data;
    }

        public function export(Request $request)
{
    // Filter data
    $records = $this->filterData($request);
    if ($request->format === 'csv') {
        return $this->exportCSV($records);
    } elseif ($request->format === 'pdf') {
        return $this->exportPDF($records);
    }

    return back()->with('error', 'Invalid export format selected.');
}
public function filterData(Request $request)
{
       $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        // $request->upload_type =2;
        $request->user_id = Auth::user()->id!=1 ? Auth::user()->id :'';
        $records = RequestLog::fetchData($request, $this->columns);
        $total = $records->get();
        if (isset($request->start)) {
            $banners = $records->offset($request->start)->limit($request->length)->get();
        } else {
            $banners = $records->offset($request->start)->limit(count($total))->get();
        }
        return $banners;
}
protected function exportPDF($records)
{
    $data = [
        'records' => $records,
        'title'   => 'Log Report',
        'date'    => now()->format('d-M-Y H:i'),
    ];

    $pdf = Pdf::loadView('exports.log_pdf', $data)
              ->setPaper('a4', 'landscape'); // or portrait

    return $pdf->download("log" . now()->format('Ymd_His') . ".pdf");
}

protected function exportCSV($records)
{
    $filename = "log_" . now()->format('Ymd_His') . ".csv";
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($records) {
        $file = fopen('php://output', 'w');
        // Header row
        fputcsv($file, [ "Sr No",'Type', 'User Agent', 'End Point', 'Data', 'Date']);

        foreach ($records as $key=> $row) {
            fputcsv($file, [
                $key+1,
                $row->type ?? 'N/A',
                $row->user_agent ?? 'N/A',
                $row->end_point ?? 'N/A',
                $row->data ?? 'N/A',

                dateFormat($row->created_at)
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
?>
