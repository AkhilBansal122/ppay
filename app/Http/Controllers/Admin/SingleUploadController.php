<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\RequestLog;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Comission;
use App\Models\CommonController;
use App\Models\ServiceCharge;

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

    public function store(Request $request) //single upload for user
    {
        $deducted_amount = 0;

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

            $user = Auth::user();
            $userId = $user->id;
            // $user->payout_commission_in_percent = (int)$user->payout_commission_in_percent;

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

            // Get wallet
            // Get or Create Wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $userId], // condition
                ['amount' => 0]        // default values if not exists
            );

            // Check balance
            if ($validated['transfer_amount'] > $wallet->amount) {
                return back()
                    ->withErrors(['transfer_amount' => 'Insufficient wallet balance'])
                    ->withInput();
            }

            //---Login for payout if checkbox check then payout in % if uncheck then payout in rupees (payout amy + gst)
            // $this->getComissionNew($userId);
            if($user->payout_commission_in_percent==1){ ///1 for %  for rupees
                $deducted_amount =  $this->getComissionNew($user,$validated['transfer_amount'],'percent');
            }else if($user->payout_commission_in_percent==0){ //0 for rupees
                $deducted_amount =  $this->getComissionNew($user,$validated['transfer_amount'],'rupees');
            }
            //---Login for payout if checkbox check then payout in % if uncheck then payout in rupees

            // Save payout
            $payout = Payout::create(array_merge($validated, [
                'user_id'     => $userId,
                'upload_type' => 1
            ]));


            $wallet_last_balance = (float)$wallet->amount; //wallet previous balance before amount deduction
            // Deduct from wallet
            $wallet->decrement('amount', $validated['transfer_amount']);
            $wallet->save();

            // ============= Need to uncomment for live txn ================
            // $transferData = [
            //     'amount'     => $deducted_amount['amount'],
            //     'ifsc'       => $request->ifsc,
            //     'accountno'  => $request->account_number,
            //     'name'       => $request->account_holder_name,
            //     'branch'     =>$request->bank_name,
            //     'paymode'    => $request->payment_mode,
            //     'remarks'    => $request->remark,
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

             // Save response log
            RequestLog::create([
                'status'     => "payout_repsonse",
                'type'       => 'payout',
                'user_agent' => $request->header('User-Agent'),
                'ip'         => $request->getClientIp(),
                'end_point'  => $request->path(),
                'data'       => $response,
            ]);

            // 1. Decode API response JSON into PHP array
            $response = json_decode($response, true);

            // 2. Safety check for 'status'
            if (isset($response['status']) && $response['status'] === true) {

                $orderId = $response['data']['data']['orderId'];

                $total_wallet_balance = ($wallet_last_balance - $validated['transfer_amount']); //50-10
                // Create Transaction
                $transaction = Transaction::create([
                    'transaction_id' => $orderId,
                    'user_id'        => $userId,
                    'order_id'       => $orderId,
                    'wallet_id'      => $wallet->id,
                    'type'           => 'payout',
                    'last_balance'   => $wallet_last_balance,
                    'amount'         => $validated['transfer_amount'],
                    'balance'        => $total_wallet_balance,
                    'reference'     => $payout->id,
                    'description'   => 'Payout request created',
                    'status' => $response['data']['success'] == true  ? 'success' : 'failed',
                    'upload_type'   => 1,
                    'remark'    => $request->remark,
                    'response_data' => json_encode($response), // Will be cast to JSON if field is json in DB
                    'initiator_id' => $request->ifsc
                ]);


                // 3. Create entry in service_charges table
                $serviceCharge =  new ServiceCharge();
                $serviceCharge->gst = $deducted_amount['gst']; //amount
                $serviceCharge->amount = $validated['transfer_amount']; //10 rs
                $serviceCharge->charge = $deducted_amount['charges'];  //2 rs
                $serviceCharge->total_charge = $deducted_amount['total_charges']; //4 rs
                $serviceCharge->type = 'PAYOUT';
                $serviceCharge->ref_id = $transaction->id; //txn table id
                $serviceCharge->ref_type = "TRANSACTION";
                $serviceCharge->is_charged  = 1;
                $serviceCharge->source == 'PPAY';
                $serviceCharge->save();

            }

            DB::commit();
            return redirect()->route('singleupload.index')->with('success', 'Payout saved successfully!');

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    //$userId,$validated['transfer_amount'],'percent'
    private function getComissionNew($getUsers, $amount, $type)
    {
        $total_charges = $charges= 0;
        $gstAmount= 0;
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
