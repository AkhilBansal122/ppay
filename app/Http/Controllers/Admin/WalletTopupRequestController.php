<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Models\WalletRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Transaction;
use App\Models\Comission;
use App\Models\Wallet;
use App\Models\ServiceCharge;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RequestLog;


class WalletTopupRequestController extends Controller
{
        function __construct(){
    $this->middleware('permission:wallet-topup-request', ['only' => ['index','store','create','edit','destroy','update']]);

        $this->Model = new WalletRequest;
        $this->columns = [
            "id",
            'user_id',
            'requested_user_id',
            'amount',
            'remark',
            'platform_charge',
            'gst',
            'status',
            'created_at',

        ];

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                return view('admin.wwalletTopUpRequest.index');

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
    public function updateWalletRequestStatus(Request $request) {
        try{

            $input = $request->all();
            $walletRequest = WalletRequest::findOrFail($input['id']);
            $getUsers = User::where('id',$walletRequest->user_id)->first();

            $gst = $getUsers->gst;


            $validated = $request->validate([
                'status' => 'required',
                'id' => 'required'
            ]);

            if($input['status'] == 'APPROVED'){

                $validated = $request->validate([
                        'utr_no' => 'required'
                    ]);

            }
            if($input['status'] == 'REVERTED'){
            $validated = $request->validate([
                    'remark' => 'required'
                ]);
            }

            $response = array();
            if(!empty($walletRequest)){
            // dd($walletRequest);
                if($walletRequest->status == 'PENDING' || $walletRequest->status == 'APPROVED')
                {
                    if($input['status'] == 'REVERTED' && $walletRequest->status == 'APPROVED')
                    {
                        $walletRequest->remark = $input['remark'];
                        $walletRequest->status = $input['status'];
                        $walletRequest->save();
                        $wallet = Wallet::where('user_id', $walletRequest->user_id)->first();
                        ServiceCharge::where("ref_id", $walletRequest->id)->update(['is_charged' => 0]);

                        $tx = new Transaction();
                        if(!empty($wallet)){
                            $tx->wallet_id = $wallet->id;
                            $tx->last_balance = $wallet->amount;
                            $wallet->is_approved = 1;
                            $wallet->save();
                            //$wallet->amoun
                            (new CommonController)->updateWallet('SUB', $walletRequest->payin_amount, $wallet->id);

                        }

                        // Create tx
                        $tx->user_id = $walletRequest->user_id;
                        //                    $tx->debit_amount = $walletRequest->payin_amount;
                        $tx->amount = $walletRequest->amount;
                        $tx->type = 'REVERTWALLETLOAD';
                        $tx->initiator_id = $walletRequest->utr_no;
                        $tx->balance = $wallet->amount - $walletRequest->payin_amount;
                        $tx->status = 'success';
                        $tx->remark = 'Reverted Wallet Load';
                        $tx->save();
                        (new CommonController)->removeServiceChargeFromWalletLoad($walletRequest);
                        $response['status'] = true;
                        $response['message'] = 'Request Revert updated successfully';

                        $walletRequest->save();
                        RequestLog::create([
                            'status'     => "REVERTED",
                            'type'       => 'REVERTED',
                            'user_agent' => $request->header('User-Agent'),
                            'ip'         => $request->getClientIp(),
                            'end_point'  => $request->path(),
                               'data'       => json_encode([
                            'wallet_request' => $walletRequest->toArray(),
                            'wallet'         => $wallet ? $wallet->toArray() : null,
                            'transaction'    => $tx->toArray(),
                            'service_charge' => ServiceCharge::where("ref_id", $walletRequest->id)->get()->toArray(),
                             ]),
                        ]);


                        echo json_encode($response);

                    }else if($input['status'] == 'APPROVED' && $walletRequest->status == 'PENDING'){

                        $walletRequest->is_updated =true;
                        $walletRequest->save();
                      //  $walletRequest->utr_no = $input['utr_no'];
                        $walletRequest->status = $input['status'];
                        $datas=$this->getComissionNew($getUsers,$walletRequest->user_id,$walletRequest->amount);
                        $walletRequest->platform_charge =$datas['charges'];//chargs;
                        $walletRequest->gst = $datas['gst'];

                        // $walletRequest->payin_amount = $walletRequest->amount - ($walletRequest->platform_charge);
                        $walletRequest->payin_amount = $walletRequest->amount - ($walletRequest->platform_charge +  $walletRequest->gst); //payin platform fees + GST amount
                        $walletRequest->save();

                        $wallet = Wallet::where('user_id', $walletRequest->user_id)->first();
                        $tx = new Transaction();
                        $wallet->is_approved=1;
                        $last_amount = $wallet->amount;
                        $wallet->amount = $wallet->amount + $walletRequest->payin_amount; //Add in wallet table
                        $wallet->save();
                        if(!empty($wallet)){
                            $tx->last_balance = $last_amount;
                            $tx->wallet_id = $wallet->id;
                        }

                        // Create tx
                        $tx->user_id = $walletRequest->user_id;
                        // $tx->credit_amount = $walletRequest->payin_amount;
                        $tx->amount = $walletRequest->amount;
                        $tx->type = 'WALLETLOAD';
                        $tx->initiator_id = $walletRequest->utr_no;
                        $tx->balance = $wallet->amount;
                        $tx->status = 'success';
                        $tx->remark = 'Wallet Load';
                        $tx->save();

                        (new CommonController)->createServiceChargeFromWalletLoad($walletRequest,$tx);
                        $walletRequest->save();
                        $response['status'] = true;
                        $response['message'] = 'Request updated successfully';
                           RequestLog::create([
                            'status'     => "APPROVED",
                            'type'       => 'APPROVED',
                            'user_agent' => $request->header('User-Agent'),
                            'ip'         => $request->getClientIp(),
                            'end_point'  => $request->path(),
                                'data'       => json_encode([
                            'wallet_request' => $walletRequest->fresh()->toArray(), // latest values
                            'wallet'         => $wallet ? $wallet->fresh()->toArray() : null,
                            'transaction'    => $tx->fresh()->toArray(),
                            'service_charge' => ServiceCharge::where("ref_id", $walletRequest->id)->get()->toArray(),
                        ]),
                        ]);


                        echo json_encode($response);

                    }
                    else if($input['status'] == 'DECLINED' && $walletRequest->status == 'PENDING')
                    {
                        $walletRequest->status = $input['status'];
                        $walletRequest->save();

                        $response['status'] = true;
                        $response['message'] = 'Request updated successfully';
                        $walletRequest->save();
                         RequestLog::create([
                        'status'     => "DECLINED",   //
                        'type'       => "DECLINED",
                        'user_agent' => $request->header('User-Agent'),
                        'ip'         => $request->getClientIp(),
                        'end_point'  => $request->path(),
                        'data'       => json_encode([
                            'wallet_request' => $walletRequest->fresh()->toArray(),
                            'wallet'         => Wallet::where('user_id', $walletRequest->user_id)->first()?->toArray(),
                            'transaction'    => [],
                            'service_charge' => [],
                        ]),
                    ]);
                        echo json_encode($response);
                    }
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Request Not Found!';

                echo json_encode($response);
            }

        }catch (\Exception $e) {
       //     dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
}
    private function getComissionNew($getUsers,$user_id,$amount){
                    // $api_status = $getUsers->api_status;
                    $charges= 0;
                    $total_charges=0;

                    $gst = (float)$getUsers->gst;   // e.g. 5
                    $amount = (float)$amount;       // e.g. 1000
                    //
                    $gstAmount = ($amount * $gst) / 100;   // GST value in %


                    /// pay In Commision alway in %
                $comission = Comission::where('user_id',$user_id) ->where('type','PAYIN')->first();
                 if ($comission) {
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

                }
            return [
                'gst'           => $gstAmount,
                'total_charges' => $total_charges,
                'charges'       => $charges,
                'amount'        => $amount,
            ];
    }
    public function updateWalletRequestStatus1(Request $request)
    {
        try{


        //  dd($request->all());
        $input = $request->all();
       //     dd($input);
        $validated = $request->validate([
		    'status' => 'required',
            'id' => 'required'
            ]);

        if($input['status'] == 'APPROVED'){

        $validated = $request->validate([
                'utr_no' => 'required'
            ]);

        }
        if($input['status'] == 'REVERTED'){
           $validated = $request->validate([
                'remark' => 'required'
            ]);
        }
        $response = array();
        $walletRequest = WalletRequest::findOrFail($input['id']);
        if(!empty($walletRequest)){
            if($walletRequest->status == 'PENDING' || $walletRequest->status == 'APPROVED'){
     //  dd($walletRequest);

                if($input['status'] == 'APPROVED' && $walletRequest->status == 'PENDING'){
                    $walletRequest->is_updated =true;

                    $walletRequest->save();
                     //    dd($walletRequest);
                  //  $walletRequest->utr_no = $input['utr_no'];
                    $walletRequest->status = $input['status'];


                    $walletRequest->platform_charge = $this->getComission($walletRequest->user_id,$walletRequest->amount);
                  //  dd($walletRequest);
                    // $walletRequest->gst = ($walletRequest->platform_charge*18)/100;
                    // $walletRequest->payin_amount = $walletRequest->amount - ($walletRequest->platform_charge + $walletRequest->gst);
                    $walletRequest->payin_amount = $walletRequest->amount - ($walletRequest->platform_charge);

                    $walletRequest->save();

                    $wallet = Wallet::where('user_id', $walletRequest->user_id)->first();
                    $tx = new Transaction();
                    if(!empty($wallet)){
                        $tx->last_balance = $wallet->amount;
                        $tx->wallet_id = $wallet->id;
                        (new CommonController)->updateWallet( $wallet->is_approved==1 ? 'SUB' :'ADD', $walletRequest->payin_amount, $wallet->id);
                    }

                    // Create tx
                    $tx->user_id = $walletRequest->user_id;
                  //  $tx->credit_amount = $walletRequest->payin_amount;
                    $tx->amount = $walletRequest->amount;
                    $tx->type = 'WALLETLOAD';
                    $tx->initiator_id = $walletRequest->utr_no;
                    $tx->balance = $wallet->amount + $walletRequest->payin_amount;
                    $tx->status = 'success';
                    $tx->remark = 'Wallet Load';
                    $tx->save();

                    $response['status'] = true;
                    $response['message'] = 'Request updated successfully';
                    (new CommonController)->createServiceChargeFromWalletLoad($walletRequest,$tx);
                }else if($input['status'] == 'REVERTED' && $walletRequest->status == 'APPROVED'){
                    $walletRequest->remark = $input['remark'];
                    $walletRequest->status = $input['status'];
                    $walletRequest->save();
                    $wallet = Wallet::where('user_id', $walletRequest->user_id)->first();
                    $tx = new Transaction();
                    if(!empty($wallet)){
                        $tx->wallet_id = $wallet->id;
                        $tx->last_balance = $wallet->amount;
                        $wallet->is_approved = 1;
                        $wallet->save();

                        (new CommonController)->updateWallet('SUB', $walletRequest->payin_amount, $wallet->id);

                    }

                    // Create tx
                    $tx->user_id = $walletRequest->user_id;
                    //                    $tx->debit_amount = $walletRequest->payin_amount;
                    $tx->amount = $walletRequest->amount;
                    $tx->type = 'REVERTWALLETLOAD';
                    $tx->initiator_id = $walletRequest->utr_no;
                    $tx->balance = $wallet->amount - $walletRequest->payin_amount;
                    $tx->status = 'success';
                    $tx->remark = 'Reverted Wallet Load';
                    $tx->save();
                    (new CommonController)->removeServiceChargeFromWalletLoad($walletRequest);

                } else if($input['status'] == 'DECLINED' && $walletRequest->status == 'PENDING'){
                    $walletRequest->status = $input['status'];
                    $walletRequest->save();
                }

                $response['status'] = true;
                $response['message'] = 'Request updated successfully';
                $walletRequest->save();
                    echo json_encode($response);

            }
            else{
                $response['status'] = false;
                $response['message'] = 'Invalid Request!';
                    echo json_encode($response);

            }

        }else{
            $response['status'] = false;
            $response['message'] = 'Request Not Found!';

    echo json_encode($response);

        }
                } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    private function getComission($user_id, $amount){
        //comission
       // dd($user_id);
        $comissions = Comission::where('user_id',$user_id)
        ->where('type','PAYIN')
        ->get();

        $comission = 0;
        if(!empty($comissions) && !empty($comissions[0]) && !empty($comissions[0]['comission_percentage'])){
        $comissions = $comissions->toArray();
        if($amount <= $comissions[0]['comission_amount']){
            $comission = $comissions[0]['comission_percentage'];
        }else if($amount > $comissions[0]['comission_amount'] && $amount < $comissions[2]['comission_amount']){
            $comission = $comissions[1]['comission_percentage'];
        }else{
            $comission = $comissions[2]['comission_percentage'];
        }
            $comission = ($amount*$comission)/100;
        }
        else{
            $WalletRequest = WalletRequest::where('user_id',$user_id)->first();
            // dd($WalletRequest);
            $comission = ($WalletRequest->amount*0.6)/100;
        }
        return $comission;
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
        $records = $this->Model->fetchData($request, $this->columns);
        $total = $records->get();
        if (isset($request->start)) {
            $banners = $records->offset($request->start)->limit($request->length)->get();
        } else {
            $banners = $records->offset($request->start)->limit(count($total))->get();
        }
        $result = [];
        $current_time = strtotime(date("h:i:sa"));
        $i = 1;
        foreach ($banners as $value) {
            $data = [];

            $data['srno'] = $i++;
            $data['id'] = $value->id;
            $data['name'] = $value->user_name;
            $data['remark'] = $value->remark;
            $data['gst'] = $value->gst ?? 0.00;
            $data['platform_charge'] = $value->platform_charge ?? 0.00;
            $data['requested_by']= $value->requested_by_name;
            $data['status'] = $value->status;
            if($value->status == 'APPROVED'){
					$data['status'] = '<span class="badge bg-success">APPROVED</span>';
				}else if ($value->status == 'DECLINED'){
					$data['status'] = '<span class="badge bg-danger">DECLINED</span>';
				}else if ($value->status == 'REVERTED'){
					$data['status'] = '<span class="badge bg-warning">REVERTED</span>';
				}
                else{
                    $data['status'] = '<span class="badge bg-warning">PENDING</span>';
                }

            $data['amount'] = $value->amount ?? 'N/A';
            $approve ="'APPROVED'";
				$decline ="'DECLINED'";
                $revert ="'REVERTED'";
                $retry ="'RETRY'";
                $minutes = round(abs($current_time - strtotime($value->created_at)) / 60,2);
                if($value->source == 'PPAY'){
                    if($value->status == 'PENDING'){
                        $data['action'] = '
                                        <div style="display: table-caption;">
                                        <td>
                                            <a class="btn btn-icon btn-sm action-'.$value->id.'" onclick="event.preventDefault();updateWalletRequestStatus('.$value->id.', '.$approve.','.$value->requested_user_id.');" title="Update status" href="javascript:void(0)">
                                                <span class="badge bg-info">Approve</span>
                                            </a><br>
                                            <a class="btn btn-icon btn-sm action-'.$value->id.'" onclick="event.preventDefault();updateWalletRequestStatus('.$value->id.', '.$decline.','.$value->requested_user_id.');" title="Update status" href="javascript:void(0)">
                                                <span class="badge bg-info">Decline</span>
                                            </a>

                                        </td>
                                        </div>
                                    ';
                    }else if($value->status == 'APPROVED' && $minutes < 120){
                        $data['action'] = '<a class="btn btn-icon btn-sm action-'.$value->id.'" onclick="event.preventDefault();updateWalletRequestStatus('.$value->id.', '.$revert.','.$value->requested_user_id.');" title="Update status" href="javascript:void(0)">
                        <span class="badge bg-info">Revert</span>
                    </a>';
                    }
                    else{
                        $data['action'] = '-';
                    }
                } else if ($value->status == 'PENDING'){
                                $data['action'] = '
                                        <div style="display: table-caption;">
                                        <a class="btn btn-icon btn-sm action-'.$value->id.'" onclick="event.preventDefault();updateWalletRequestStatus('.$value->id.', '.$retry.');" title="Update status" href="javascript:void(0)">
                                        <span class="badge bg-info">Retry</span>
                                        </a><br>
                                        <td>
                                            <a class="btn btn-icon btn-sm action-'.$value->id.'" onclick="event.preventDefault();updateWalletRequestStatus('.$value->id.', '.$decline.');" title="Update status" href="javascript:void(0)">
                                                <span class="badge bg-info">Decline</span>
                                            </a>

                                        </td>
                                        </div>
                                ';
                }
                else{
                    $data['action'] = '-';
                }

            $data['created_at'] = dateFormat($value->created_at); // Assuming created_at is a Carbon instance
            $data['updated_at'] = dateFormat($value->updated_at); // Assuming created_at is a Carbon instance

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
        $records = WalletRequest::fetchData($request, $this->columns);
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
        'title'   => 'Wallet Request Report',
        'date'    => now()->format('d-M-Y H:i'),
    ];

    $pdf = Pdf::loadView('exports.wallet_request_pdf', $data)
              ->setPaper('a4', 'landscape'); // or portrait

    return $pdf->download("wallet_Request_" . now()->format('Ymd_His') . ".pdf");
}

protected function exportCSV($records)
{
    $filename = "wallet_request_" . now()->format('Ymd_His') . ".csv";
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($records) {
        $file = fopen('php://output', 'w');
        // Header row
        fputcsv($file, [ "Sr No",'User Name', 'Amounr', 'Remark', 'Platform Charge',"GST", 'Status', 'Date']);

        foreach ($records as $key=> $row) {
            fputcsv($file, [
                $key+1,
                $row->user_name ?? 'N/A',
                number_format($row->amount, 2),
                $row->remark,
                number_format($row->platform_charge, 2),
                number_format($row->gst, 2),

                ucfirst($row->status),
                dateFormat($row->created_at)
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
