<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\ServiceCharge;
use Carbon\Carbon;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class DashboardController extends Controller
{
        function __construct(){
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


    // public function index()
    // {
    //             $universal_balance=0;
    //             $wallert_balance=0;
    //             $payIn=0;
    //             $payOut=0;
    //             if (auth()->check() &&  auth()->user()->id == 1) {

    //                 $response = universepay_api('/balance', 'POST', []);
    //                 if (isset($response['status']) && $response['status'] === true) {
    //                     $universal_balance = isset($response['data']['balance']) ? (float) $response['data']['balance'] : 0.0;
    //                 }
    //             }

    //             if (auth()->check() &&  auth()->user()->id != 1) {
    //                $wallet =  Wallet::where("user_id",auth()->user()->id)->first();
    //                 $universal_balance = $wallet->amount ?? 0.0;

    //                 $today = Carbon::today('Asia/Kolkata');

    //                 $payOut = Transaction::where('user_id', auth()->id())
    //                     ->where('type', 'payout')
    //                     ->where('status', 'success')
    //                     ->whereDate('created_at', $today)
    //                     ->sum('amount');

    //                 $payIn = Transaction::where('user_id', auth()->id())
    //                     ->where('type', 'payin')
    //                     ->where('status', 'success')
    //                     ->whereDate('created_at', $today)
    //                     ->sum('amount');
    //         }
    //             return view('admin.layouts.dashboard',compact('universal_balance','wallert_balance','payIn','payOut'));
    // }


    public function index()
    {
                $universal_balance=0;
                $wallert_balance=0;
                $payIn=0;
                $payOut=0;
                $admin_payin_earning=0;
                $admin_payout_earning=0;
                $today = Carbon::today('Asia/Kolkata');

                if (auth()->check() &&  auth()->user()->id == 1) {

                    $response = universepay_api('/balance', 'POST', []);
                    if (isset($response['status']) && $response['status'] === true) {
                        $universal_balance = isset($response['data']['balance']) ? (float) $response['data']['balance'] : 0.0;
                    }

                    //Get today payin only from tx table
                    $admin_payin = Transaction::whereIn('type', ['payin', 'WALLETLOAD'])
                        ->where('status', 'success')
                        ->whereDate('created_at', $today)
                        ->sum('amount');
                    $payIn = $admin_payin;
                    //Get todays payout form tx table
                    $admin_payout = Transaction::where('type', 'payout')
                        ->where('status', 'success')
                        ->whereDate('created_at', $today)
                        ->sum('amount');
                    $payOut  =$admin_payout;
                    //Get Payin earning
                    $admin_payin_earning = ServiceCharge::where('type', 'PAYIN')
                        ->where('is_charged', 1)
                        ->whereDate('created_at', $today)
                        ->sum('total_charge');


                    //Get payout earning
                    $admin_payout_earning = ServiceCharge::where('type', 'PAYOUT')
                        ->where('is_charged', 1)
                        ->whereDate('created_at', $today)
                        ->sum('total_charge');

                }

                if (auth()->check() &&  auth()->user()->id != 1) {
                   $wallet =  Wallet::where("user_id",auth()->user()->id)->first();
                    $universal_balance = $wallet->amount ?? 0.0;

                    $payOut = Transaction::where('user_id', auth()->id())
                        ->where('type', 'payout')
                        ->where('status', 'success')
                        ->whereDate('created_at', $today)
                        ->sum('amount');

                    $payIn = Transaction::where('user_id', auth()->id())
                        ->where('type', 'WALLETLOAD')
                        ->where('status', 'success')
                        ->whereDate('created_at', $today)
                        ->sum('amount');
            }
                return view('admin.layouts.dashboard',compact('universal_balance','wallert_balance','payIn','payOut','admin_payin_earning','admin_payout_earning'));
    }


 public function getData(Request $request)
    {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        // $request->upload_type =2;
        $request->user_id = Auth::user()->id!=1 ? Auth::user()->id :'';
        $records = Transaction::fetchRecentData($request, $this->columns);
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
                                        'WALLETLOAD' => '<span class="badge bg-info ">Wallet Load</span>',
                    'REVERTWALLETLOAD' => '<span class="badge bg-warning ">Revert Wallet Load</span>',
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
        $records = Transaction::fetchRecentData($request, $this->columns);
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
        'title'   => 'Transactions Report',
        'date'    => now()->format('d-M-Y H:i'),
    ];

    $pdf = Pdf::loadView('exports.transactions_pdf', $data)
              ->setPaper('a4', 'landscape'); // or portrait

    return $pdf->download("transactions_" . now()->format('Ymd_His') . ".pdf");
}

protected function exportCSV($records)
{
    $filename = "transactions_" . now()->format('Ymd_His') . ".csv";
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($records) {
        $file = fopen('php://output', 'w');
        // Header row
        fputcsv($file, [ "Sr No",'Transaction ID', 'Type', 'Amount', 'Balance',"Upload Type", 'Status', 'Date']);

        foreach ($records as $key=> $row) {
            fputcsv($file, [
                $key+1,
                $row->transaction_id ?? 'N/A',
                ucfirst($row->type),
                $row->amount,
                $row->balance,
                $row->upload_type == 1 ? 'Single' :"Bulk" ?? 'N/A',
                ucfirst($row->status),
                dateFormat($row->created_at)
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
