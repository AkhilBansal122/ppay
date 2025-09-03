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
use App\Models\ServiceCharge;
use App\Models\Comission;
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


public function store(Request $request)  //bulk upload (payout)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt,xlsx,xls',
    ]);
    $user = Auth::user();

    $file = $request->file('file');
    $data = array_map('str_getcsv', file($file->getRealPath()));
    $userId = $user->id;
    if( $user->api_status== 0){  //check if api enabled
        return back()->withErrors([
            'error' => "API access disabled. Please contact support team."
        ]);
    }

    $wallet = Wallet::where('user_id', $userId)->first();

    if (!$wallet) {
        return back()->withErrors([
            'error' => "Wallet not found. Please contact support team."
        ]);
    }
        $wallet = Wallet::firstOrCreate(
        ['user_id' => $userId], // condition
        ['amount' => 0]        // default values if not exists
    );


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

            if($user->payout_commission_in_percent==1){ ///1 for %  for rupees
                $deducted_amount =  $this->getComissionNew($user,$bulk['transfer_amount'],'percent');
            }else if($user->payout_commission_in_percent==0){ //0 for rupees
                $deducted_amount =  $this->getComissionNew($user,$bulk['transfer_amount'],'rupees');
            }

            // Save payout
            $payout = Payout::create(array_merge($bulk, [
                'user_id' => $userId,
                'upload_type'=>2
            ]));

            $wallet_last_balance = (float)$wallet->amount; //wallet previous balance before amount deduction
            // Deduct from wallet
            $wallet->decrement('amount', $bulk['transfer_amount']);
            $wallet->save();


             // ============= Need to uncomment for live txn ================
            // $transferData = [
            //     'amount'     => $deducted_amount['amount'],
            //     'ifsc'       => $bulk['ifsc'],
            //     'accountno'  => $bulk['account_number'],
            //     'name'       => $bulk['account_holder_name'],
            //     'branch'     =>$bulk['bank_name'],
            //     'paymode'    => $bulk['payment_mode'],
            //     'remarks'    => $bulk['remark'],
            //     'mode'       =>'bank',
            // ];
            // $response = universepay_api('/transfer', 'POST', $transferData);
            // ============= Need to uncomment for live txn ================

            // Save request log
            // RequestLog::create([
            //     'status'     => "payout_request",
            //     'type'       => 'payout',
            //     'user_agent' => $request->header('User-Agent'),
            //     'ip'         => $request->getClientIp(),
            //     'end_point'  => $request->path(),
            //     'data'       => json_encode($transferData),
            // ]);

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

            //create payout response log
            RequestLog::create([
                'status'     => "payout_repsonse",
                'type'       => 'payout',
                'user_agent' => $request->header('User-Agent'),
                'ip'         => $request->getClientIp(),
                'end_point'  => $request->path(),
                'data'       => $response,
            ]);

            $response = json_decode($response, true);

            if (!empty($response['status']) && $response['status'] === true)
            {
                $orderId = $response['data']['data']['orderId'] ?? 0;

                $total_wallet_balance = $wallet_last_balance - $bulk['transfer_amount']; //50-10

                // Create Transaction
                $transaction = Transaction::create([
                    'transaction_id' => $orderId,
                    'user_id'        => $userId,
                    'order_id'       => $orderId,
                    'wallet_id'      => $wallet->id,
                    'type'           => 'payout',
                    'last_balance'   => $wallet_last_balance,
                    'amount'         => $bulk['transfer_amount'],
                    'balance'        => $total_wallet_balance,
                    'reference'     => $payout->id,
                    'description'   => 'Payout request created',
                    'status' => $response['data']['success'] == true  ? 'success' : 'failed',
                    'upload_type'   => 2,
                    'remark'    => $bulk['remark'],
                    'response_data' => json_encode($response), // Will be cast to JSON if field is json in DB
                    'initiator_id' => $bulk['ifsc']
                ]);


                // 3. Create entry in service_charges table
                $serviceCharge =  new ServiceCharge();
                $serviceCharge->gst = $deducted_amount['gst']; //amount
                $serviceCharge->amount = $bulk['transfer_amount']; //10 rs
                $serviceCharge->charge = $deducted_amount['charges'];  //2 rs
                $serviceCharge->total_charge = $deducted_amount['total_charges']; //4 rs
                $serviceCharge->type = 'PAYOUT';
                $serviceCharge->ref_id = $transaction->id; //txn table id
                $serviceCharge->ref_type = "TRANSACTION";
                $serviceCharge->is_charged  = 1;
                $serviceCharge->source == 'PPAY';
                $serviceCharge->save();

            }
        }

        DB::commit();
        return redirect()->route('bulkUpload.index')->with('success', 'Bulk payout processed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    private function getComissionNew($getUsers, $amount, $type)
    {
        $total_charges = $charges= 0;
        $gstAmount=0;
        $dec_amount=0;
        $user_id  = $getUsers->id;
        $amount = (float)$amount;       // e.g. 1000
        $comission = Comission::where('user_id',$user_id) ->where('type','PAYOUT')->first(); //payout
        if ($comission)
        {
            if($type == 'percent')
            {
                $gst = (float)$getUsers->gst;   // e.g. 5
                $gstAmount = ($amount * $gst) / 100;   // GST value in %
                // Determine percentage based on ranges
                if ($amount <= $comission->commission1) {

                    $percentage = $comission->percentage1;
                    $charges = ($amount * $percentage) / 100;
                    $total_charges = $charges+$gstAmount;

                } elseif ($amount > $comission->commission1 && $amount <= $comission->commission2) {
                    $percentage = $comission->percentage2;
                    $charges = ($amount * $percentage) / 100;
                    $total_charges = $charges+$gstAmount;

                } elseif ($amount > $comission->commission2 && $amount <= $comission->commission3) {
                    $percentage = $comission->percentage3;
                    $charges = ($amount * $percentage) / 100;
                    $total_charges = $charges+$gstAmount;
                }

            }else if($type == 'rupees'){

                $gstAmount = (float)$getUsers->gst;   // e.g. 5

                if ($amount <= $comission->commission1) {

                    $charges = $comission->percentage1;
                    $total_charges = $charges+$gstAmount; //2+2

                } elseif ($amount > $comission->commission1 && $amount <= $comission->commission2) {
                    $charges = $comission->percentage2;
                    $total_charges = $charges+$gstAmount;

                } elseif ($amount > $comission->commission2 && $amount <= $comission->commission3) {
                    $charges = $comission->percentage3;
                    $total_charges = $charges+$gstAmount;
                }

            }

            $dec_amount = $amount - $total_charges; //10-4 = 6
        }

        return [
            'gst'           => $gstAmount,  //rupees 2rs
            'total_charges' => $total_charges, //4 rs
            'charges'       => $charges, //2 rs
            'amount'        => $dec_amount  //6 rs
        ];
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
           $getTransation= Transaction::where("reference",$value->id)->first();

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


                                                          $data['status'] = match($getTransation->status) {
                'success' => '<span class="badge bg-success">Success</span>',
                'failed'  => '<span class="badge bg-danger">Failed</span>',
                'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                default   => '<span class="badge bg-secondary">N/A</span>',
            };



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
