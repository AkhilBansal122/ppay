<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{User,Transaction,Wallet,Payout,ServiceCharge,Comission};


use Response;
class ApiController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Check credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Authenticated user
        $user = Auth::user();

        // Generate token (requires Laravel Sanctum)
        $token = $user->createToken('APIToken')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function fetchTXDetail(Request $request)
    {


        $txId = $request->id;
        $user = Auth::user();

        if(!empty($txId)){
            $tx = Transaction::where('id', $txId)
                ->where('user_id',$user->id)->first();

            if (!empty($tx)) {
                return Response::json([
                    'message' => 'Transaction fetched successfully',
                    'data' => $tx,
                    'success' => true,
                ], 200);
            }else{
                return Response::json([
                    'message' => 'Transaction not found!',
                    'success' => false,
                ], 404);
            }
        }else{
            return Response::json([
            'message' => 'Invalid request',
            'success' => false,
            ], 404);
        }

    }


    //=====================================================================

    private function getComissionNew($getUsers, $amount, $type)
    {
        $total_charges = $charges= 0;
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
        /**
     * Fetch wallet API
     */
        public function fetchWallet(Request $request)
    {
          $user = Auth::user();

        $wallet = Wallet::where('user_id', $user->id)->select('amount')->first();
        if (!empty($wallet)) {
            return Response::json([
                'message' => 'Wallet fetched successfully',
                'data' => $wallet,
                'success' => true,
            ], 200);
        }else{
            return Response::json([
                'message' => 'Wallet not found!',
                'success' => false,
            ], 404);
        }
    }

    public function transfer(Request $request) // API version of single upload for user
{
    $deducted_amount = 0;

    $validated = $request->validate([
        'transfer_by' => 'required',
        'account_number' => ['required', 'regex:/^\d{9,18}$/'],
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
    dd($request->all());
    DB::beginTransaction();

    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        if ($user->api_status == 0) {
            return response()->json(['status' => false, 'message' => 'API access disabled. Please contact support team.']);
        }

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['amount' => 0]);

        if ($validated['transfer_amount'] > $wallet->amount) {
            return response()->json(['status' => false, 'message' => 'Insufficient wallet balance']);
        }

        // Calculate commission
        if ($user->payout_commission_in_percent == 1) {
            $deducted_amount = $this->getComissionNew($user, $validated['transfer_amount'], 'percent');
        } else {
            $deducted_amount = $this->getComissionNew($user, $validated['transfer_amount'], 'rupees');
        }

        // Save payout
        $payout = Payout::create(array_merge($validated, [
            'user_id' => $user->id,
            'upload_type' => 1
        ]));

        $wallet_last_balance = (float)$wallet->amount;
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
        // Simulated API response (replace with live API call)
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

        RequestLog::create([
            'status' => "payout_response",
            'type' => 'payout',
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->getClientIp(),
            'end_point' => $request->path(),
            'data' => $response,
        ]);

        $response = json_decode($response, true);

        if (isset($response['status']) && $response['status'] === true) {
            $orderId = $response['data']['data']['orderId'];
            $total_wallet_balance = $wallet_last_balance - $validated['transfer_amount'];

            $transaction = Transaction::create([
                'transaction_id' => $orderId,
                'user_id' => $user->id,
                'order_id' => $orderId,
                'wallet_id' => $wallet->id,
                'type' => 'payout',
                'last_balance' => $wallet_last_balance,
                'amount' => $validated['transfer_amount'],
                'balance' => $total_wallet_balance,
                'reference' => $payout->id,
                'description' => 'Payout request created',
                'status' => $response['data']['success'] == true ? 'success' : 'failed',
                'upload_type' => 1,
                'remark' => $request->remark,
                'response_data' => json_encode($response),
                'initiator_id' => $request->ifsc
            ]);

            ServiceCharge::create([
                'gst' => $deducted_amount['gst'],
                'amount' => $validated['transfer_amount'],
                'charge' => $deducted_amount['charges'],
                'total_charge' => $deducted_amount['total_charges'],
                'type' => 'PAYOUT',
                'ref_id' => $transaction->id,
                'ref_type' => "TRANSACTION",
                'is_charged' => 1,
                'source' => 'PPAY'
            ]);
        }

        DB::commit();
        return response()->json(['status' => true, 'message' => 'Payout saved successfully!', 'data' => $transaction ?? null]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => false, 'message' => $e->getMessage()]);
    }
}



    // public function transfer(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'amount' => 'required|numeric|min:1',
    //             'ifsc' => 'required|string|size:11',
    //             'accountno' => 'required|string|min:9|max:18',
    //             'name' => 'required|string|max:255',
    //             'branch' => 'required|string|max:255',
    //             'paymode' => 'required|in:IMPS,NEFT,RTGS',
    //             'remarks' => 'sometimes|string|max:255',
    //              'mode' => 'required|in:bank,upi,wallet'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Validation error',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }


    //         $transferDetails = [
    //             'amount' => $request->amount,
    //             'ifsc' => $request->ifsc,
    //             'accountno' => $request->accountno,
    //             'name' => $request->name,
    //             'branch' => $request->branch,
    //             'paymode' => $request->paymode,
    //             'remarks' => $request->remarks ?? 'Transfer',
    //             'mode' => $request->mode
    //         ];
    //     dd($transferDetails);
    //       //  $response = $this->universepayApi('/transfer', 'POST', $transferDetails);

    //         // Log the transfer request
    //         RequestLog::create([
    //             'status'     => "transfer_response",
    //             'type'       => 'transfer',
    //             'user_agent' => $request->header('User-Agent'),
    //             'ip'         => $request->getClientIp(),
    //             'end_point'  => $request->path(),
    //             'data'       => json_encode($response),
    //         ]);

    //         return response()->json($response, $response['success'] ? 200 : 400);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Server error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


}
