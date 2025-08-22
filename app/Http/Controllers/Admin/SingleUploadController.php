<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\RequestLog;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SingleUploadController extends Controller
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                return view('admin.singleupload.index');

    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        return view('admin.singleupload.create');
    }

    public function store(Request $request)
    {
            $validated = $request->validate([
                'transfer_by' => 'required',
                'account_number' => [
                    'required',
                    'regex:/^\d{9,18}$/'
                ],
                'account_holder_name' => 'required',
                    'ifsc' => ['required', 'regex:/^[A-Z]{4}0[0-9]{6}$/'],
                'bank_name' => 'required',
                'transfer_amount' => 'required|numeric|min:1',
                'payment_mode' => 'required',
                'remark' => 'nullable',
            ], [
                'transfer_by.required' => 'Transfer by is required.',

                'account_number.required' => 'Account number is required.',
                'account_number.regex' => 'Account number must be 9 to 18 digits and contain only numbers.',

                'account_holder_name.required' => 'Account holder name is required.',

                'ifsc.required' => 'IFSC code is required.',
                'ifsc.regex' => 'The IFSC code format is invalid. Example: SBIN0001234',
                'bank_name.required' => 'Bank name is required.',

                'transfer_amount.required' => 'Transfer amount is required.',
                'transfer_amount.numeric' => 'Transfer amount must be a number.',
                'transfer_amount.min' => 'Transfer amount must be at least 1.',

                'payment_mode.required' => 'Payment mode is required.',
            ]);
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $wallet = Wallet::where('user_id', $userId)->first();

            if (!$wallet) {
                return back()->withErrors([
                    'error' => "Wallet not found. Please contact support team."
                ]);
            }


            // Get wallet
// Get or Create Wallet
$wallet = Wallet::firstOrCreate(
    ['user_id' => $userId], // condition
    ['amount' => 0]        // default values if not exists
);

// Check balance
if ($wallet->amount < $validated['transfer_amount']) {
    return back()
        ->withErrors(['transfer_amount' => 'Insufficient wallet balance'])
        ->withInput();
}

// Save payout
$payout = Payout::create(array_merge($validated, [
    'user_id'     => $userId,
    'upload_type' => 1
]));

// Save request log
RequestLog::create([
    'status'     => "payout_request",
    'type'       => 'payout',
    'user_agent' => $request->header('User-Agent'),
    'ip'         => $request->getClientIp(),
    'end_point'  => $request->path(),
    'data'       => json_encode($request->all()),
]);

// Deduct from wallet
$wallet->decrement('amount', $validated['transfer_amount']);
$wallet->save();
                            // $response = universepay_api('/balance', 'POST', []);
  // If $response is a JSON string
$response = '{
    "status": true,
    "data": {
        "success": true,
        "data": {
            "orderId": "134895854",
            "udf1": "optional Data1",
            "udf2": "optional Data2",
            "udf3": "optional Data3",
            "status": "Completed",
            "transactionId": "",
            "creationDateTime": "2025-08-15T17:55:40.000000+05:30"
        },
        "message": "Payment initiated successfully...!!!",
        "errors": null,
        "exception": null
    }
}';


// 1. Decode API response JSON into PHP array
$response = json_decode($response, true);

// 2. Safety check for 'status'
if (isset($response['status']) && $response['status'] === true) {

        $orderId = $response['data']['data']['orderId'] ?? '000000';
    // Create Transaction
    $transaction = Transaction::create([
        'order_id'=>$orderId,
        'response_data' => json_encode($response), // Will be cast to JSON if field is json in DB
            'status' => $response['data']['success'] == true  ? 'success' : 'failed',
        'user_id'       => $userId,
        'wallet_id'     => $wallet->id,
        'type'          => 'payout',
        'amount'        => $validated['transfer_amount'],
        'balance'       => $wallet->amount,
        'reference'     => $payout->id,
        'description'   => 'Payout request created',
    ]);

    // 3. Generate transaction_id

    // $monthShort = date('M'); // Aug, Sep, etc.
    $monthShort = strtoupper(date('M')); // aug, sep, etc.
    $lastId = $transaction->id; // ID of the record just inserted

    $transactionId = "PP{$orderId}{$monthShort}{$lastId}";

    // 4. Update transaction with transaction_id
Transaction::where('id', $lastId)->update([
    'transaction_id' => $transactionId,
    'upload_type'=>1
]);
    // 5. Create RequestLog entry
    RequestLog::create([
        'type'       => 'transaction',
        'user_agent' => $request->header('User-Agent'),
        'ip'         => $request->getClientIp(),
        'end_point'  => $request->path(),
        'data'       => json_encode($request->all()),
    ]);
}



            DB::commit();
            return redirect()->route('singleupload.index')->with('success', 'Payout saved successfully!');

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getData(Request $request)
    {
                $request->upload_type =1;
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }

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

    public function statusChange(Request $request)
    {
       return statusChange($request,$this->Model);
    }
}
