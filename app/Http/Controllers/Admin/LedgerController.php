<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

use Auth;
class LedgerController extends Controller
{
        function __construct(){
    $this->middleware('permission:Ledger', ['only' => ['index','store','create','edit','destroy','update']]);

        $this->Model = new Transaction;
        $this->columns = [
            "id",
            'user_id',
            'transaction_id',
            'type',
            'amount',
            'balance',
            'description',
            'status',
            'upload_type',
            'created_at'
        ];

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.ledgers.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
        public function getData(Request $request)
        {
        $request->search = $request->statusValue ?? $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        // $request->upload_type =2;
        $request->user_id = Auth::user()->id!=1 ? Auth::user()->id :'';
        $records = Transaction::fetchledgersData($request, $this->columns);
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
            $data['transaction_id'] = $value->transaction_id ?? 'N/A';
           $data['type'] = match($value->type) {
                    'payout' => '<span class="badge bg-danger">Payout</span>',
                    'payin'  => '<span class="badge bg-success">Payin</span>',
                    'WALLETLOAD' => '<span class="badge bg-info">Wallet Load</span>',
                    'REVERTWALLETLOAD' => '<span class="badge bg-warning">Revert Wallet Load</span>',

                    default  => '<span class="badge bg-secondary">N/A</span>',
                };
            $data['amount'] = $value->amount ?? 'N/A';
            $data['balance']=$value->balance ?? 'N/A';
            $data['description']=$value->description ?? 'N/A';
                                  $data['status'] = match($value->status) {
                'success' => '<span class="badge bg-success">Success</span>',
                'failed'  => '<span class="badge bg-danger">Failed</span>',
                'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                default   => '<span class="badge bg-secondary">N/A</span>',
            };

            $data['upload_type'] = $value->upload_type == 1 ? 'Single' :"Bulk" ?? 'N/A';
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
        $records = Transaction::fetchledgersData($request, $this->columns);
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
        'title'   => 'Ledgers Report',
        'date'    => now()->format('d-M-Y H:i'),
    ];

    $pdf = Pdf::loadView('exports.ledgers_pdf', $data)
              ->setPaper('a4', 'landscape'); // or portrait

    return $pdf->download("Ledgers_" . now()->format('Ymd_His') . ".pdf");
}

protected function exportCSV($records)
{
    $filename = "Ledgers_" . now()->format('Ymd_His') . ".csv";
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($records) {
        $file = fopen('php://output', 'w');
        // Header row
        fputcsv($file, [ "Sr No",'Transaction ID', 'Type', 'Amount', 'Balance',"Upload Type", 'Status','Description', 'Date']);

        foreach ($records as $key=> $row) {
            fputcsv($file, [
                $key+1,
                $row->transaction_id ?? 'N/A',
                ucfirst($row->type),
                $row->amount,
                $row->balance,
                $row->upload_type == 1 ? 'Single' :"Bulk" ?? 'N/A',
                ucfirst($row->status),
                $row->description ??'N/A',

                dateFormat($row->created_at)
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
