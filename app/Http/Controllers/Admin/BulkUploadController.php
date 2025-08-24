<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\YourModel; // Replace with your actual model
use Maatwebsite\Excel\Facades\Excel; // If using Excel

use App\Models\Payout;
use App\Models\RequestLog;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BulkUploadController extends Controller
{
        function __construct(){
        $this->middleware('permission:Single-Upload', ['only' => ['index','store','create','edit','destroy','update']]);
        $this->Model = new Payout;
        $this->uploadPath = 'uploads/admin/Payout/';
        $this->columns = [
            "id",
            'user_id',
            'transfer_by',
            'account_number',
            'account_holder_name',
            'ifsc',
            'bank_name',
            'transfer_amount',
            'payment_mode',
            'remark',
            'status',
            'created_at'
        ];

    }

        public function index()
    {
                return view('admin.bulkupload.index');

    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {

        return view('admin.bulkupload.create');
    }


public function store(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt,xlsx,xls',
    ]);

    $file = $request->file('file');
    $data = array_map('str_getcsv', file($file->getRealPath()));
                $userId = Auth::id();

                $wallet = Wallet::where('user_id', $userId)->first();

            if (!$wallet) {
                return back()->withErrors([
                    'error' => "Wallet not found. Please contact support team."
                ]);
            }
    // Remove header row
    $headers = array_shift($data);

    // Validate empty rows
    $emptyRows = [];
    foreach ($data as $index => $numericRow) {
        if (count(array_filter($numericRow, 'trim')) !== count($numericRow)) {
            $emptyRows[] = $index + 2; // +2 to account for header + 1-based index
        }
    }
    if (!empty($emptyRows)) {
        return back()->withErrors([
            'file' => 'The following rows have empty fields: ' . implode(', ', $emptyRows)
        ]);
    }

    // Prepare all valid rows
    $bulks = [];
    foreach ($data as $numericRow) {
        $bulks[] = [
            'account_number'       => $numericRow[0],
            'account_holder_name'  => $numericRow[1],
            'bank_name'            => $numericRow[2],
            'ifsc'                 => $numericRow[3],
            'transfer_amount'      => $numericRow[4],
            'payment_mode'         => $numericRow[5],
            'remark'               => $numericRow[6],
            'transfer_by'          => 'bank',
        ];
    }

    DB::beginTransaction();
    try {
       // $userId = Auth::id();
        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->firstOrFail();

        foreach ($bulks as $bulk) {
            // Check wallet balance
            if ($wallet->amount < $bulk['transfer_amount']) {
                DB::rollBack();
                return back()->withErrors([
                    'transfer_amount' => "Insufficient wallet balance for account: {$bulk['account_number']}"
                ]);
            }

            // Save payout
            $payout = Payout::create(array_merge($bulk, [
                'user_id' => $userId,
                'upload_type'=>2
            ]));

            // Log payout request
            RequestLog::create([
                'status'     => "payout_request",
                'type'       => 'payout',
                'user_agent' => $request->header('User-Agent'),
                'ip'         => $request->getClientIp(),
                'end_point'  => $request->path(),
                'data'       => json_encode($bulk),
            ]);

            // Deduct from wallet
            $wallet->amount -= $bulk['transfer_amount'];
            $wallet->save();
                            // $response = universepay_api('/balance', 'POST', []);

            // Simulated API response
            $response = '{
                "status": true,
                "data": {
                    "success": true,
                    "data": {
                        "orderId": "134895854",
                        "status": "Completed"
                    },
                    "message": "Payment initiated successfully...!!!"
                }
            }';
            $response = json_decode($response, true);

            if (!empty($response['status']) && $response['status'] === true) {
                $orderId = $response['data']['data']['orderId'] ?? '000000';
                $transaction = Transaction::create([
                    'order_id'      => $orderId,
                    'response_data' => json_encode($response),
                    'status'        => $response['data']['success'] ? 'success' : 'failed',
                    'user_id'       => $userId,
                    'wallet_id'     => $wallet->id,
                    'type'          => 'payout',
                    'amount'        => $bulk['transfer_amount'],
                    'balance'       => $wallet->amount,
                    'reference'     => $payout->id,
                    'description'   => 'Payout request created',
                ]);

                $transactionId =$orderId;// "PP{$orderId}" . strtoupper(date('M')) . $transaction->id;
                $transaction->update(['transaction_id' => $transactionId,'upload_type'=>2]);

                RequestLog::create([
                    'type'       => 'transaction',
                    'user_agent' => $request->header('User-Agent'),
                    'ip'         => $request->getClientIp(),
                    'end_point'  => $request->path(),
                    'data'       => json_encode($bulk),
                ]);
            }
        }

        DB::commit();
        return redirect()->route('bulkUpload.index')->with('success', 'Bulk payout processed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

 public function getData(Request $request)
    {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        $request->upload_type =2;
        $records = $this->Model->fetchData($request, $this->columns);
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
$data['transfer_by'] = $value->transfer_by
    ? str_replace('_', ' ', $value->transfer_by)
    : 'N/A';
$data['account_number'] = $value->account_number ?? 'N/A';
            $data['account_holder_name'] = $value->account_holder_name ?? 'N/A';
                        $data['ifsc'] = $value->ifsc ?? 'N/A';
                        $data['bank_name']=$value->bank_name ?? 'N/A';
$data['transfer_amount']=$value->transfer_amount ?? 'N/A';
                        $data['payment_mode']=$value->payment_mode ?? 'N/A';
                        $data['remark'] = $value->remark ?? 'N/A';


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
}
