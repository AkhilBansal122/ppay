<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Comission;
use App\Models\MetaLog;
use DB;
use App\Models\Transaction;
use App\Models\VirtualAccount;
use App\Models\ServiceCharge;
use Illuminate\Support\Facades\Http;

class CommonController extends Controller
{
            function __construct(){
    $this->middleware('permission:commission', ['only' => ['index','store','create','edit','destroy','update']]);

        $this->Model = new Comission;
        $this->columns = [
            "id",
            'user_id',
            'type',
            'comission_amount',
            'comission_percentage',
            'created_at'
        ];

    }


    public function index(){
        return view('admin.commission.index');
    }
       public function getData(Request $request)
        {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }
        // $request->upload_type =2;
        $records = Comission::fetchData($request, $this->columns);
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
            $data['user_id']=$value->user->name;
           $data['type'] = match($value->type) {
                    'PAYOUT' => '<span class="badge bg-danger">Payout</span>',
                    'PAYIN'  => '<span class="badge bg-success">Payin</span>',
                    default  => '<span class="badge bg-secondary">N/A</span>',
                };
                $data['comission_percentage']=$value->comission_percentage ?? 'N/A';
            $data['comission_amount'] = $value->comission_amount ?? 'N/A';
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
    public function getTransferMode($mode_number){
        switch ($mode_number) {
            case 4:
                return "NEFT";
                break;
            case 5:
                return "IMPS";
                break;
            case 13:
                return "RTGS";
                break;
            case 14:
                return "UPI";
                break;
            default:
                return "IMPS";
          }
    }

    public function getPayoutComission($user_id,$payout_amount){
        $comissions = Comission::where('user_id',$user_id)
                            ->where('type','PAYOUT')
                            ->get();
            $comission = 0;
            if(!empty($comissions) && !empty($comissions[0]) && !empty($comissions[0]['comission_percentage'])){
                $comissions = $comissions->toArray();
                if($payout_amount <= $comissions[0]['comission_amount']){
                    $comission = $comissions[0]['comission_percentage'];
                }else if($payout_amount > $comissions[0]['comission_amount'] && $payout_amount < $comissions[2]['comission_amount']){
                    $comission = $comissions[1]['comission_percentage'];
                }else{
                    $comission = $comissions[2]['comission_percentage'];
                }
            }else{
                if(!empty($payout_amount) && $payout_amount <=1000){
                    $comission = 3.5;
                }else if(!empty($payout_amount) && $payout_amount >=1001 && $payout_amount <25001){
                    $comission = 5.5;
                }else{
                    $comission = 8.5;
                }
            }

            $comission_amount = $comission;
            $gst = ($comission_amount*18)/100;

            $total_required_amount = $payout_amount + $comission_amount + $gst;

            $result = array(
                "comission_amount"=>$comission_amount,
                "gst"=>$gst,
                "total_required_amount"=>$total_required_amount
            );

            return $result;
    }

    public function updateWallet($action, $amount, $id ){
        if(!empty($action) && !empty($amount) && !empty($id)){
            if($action == 'ADD'){
                DB::beginTransaction();
                try {
                    DB::statement('UPDATE wallets set is_approved =1, amount = amount + '.$amount.' where id ='.$id.'');
                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                }
            }else if ($action == 'SUB'){
                DB::beginTransaction();
                try {
                    DB::statement('UPDATE wallets set  amount = amount - '.$amount.' where id ='.$id.'');
                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                }
            }
        }

    }

    public function createLog($ref_id, $user_id, $type, $decription, $data){

        $ml = new MetaLog();
        if(!empty($ref_id)){
            $ml->ref_id = $ref_id;
        }
        if(!empty($user_id)){
            $ml->user_id = $user_id;
        }
        if(!empty($type)){
            $ml->type = $type;
        }
        if(!empty($decription)){
            $ml->decription = $decription;
        }
        if(!empty($data)){
            $ml->data = json_encode($data);
        }
        $ml->save();
    }

    public function createPayoutTransaction($request,$comission_amount,$gst, $total_required_amount,$wallet_amount, $api_author)
    {
        // Create new wallet
		$tx = new Transaction();
		$tx->user_id = $request->user->id;
		$tx->initiator_id = config('app.eko_initiator_id');
        $tx->client_ref_id = $request->get('referenceNumber');
        $tx->service_code = config('app.eko_service_code');
        $tx->payment_mode = $request->get('transferMode');
        $tx->recipient_name = $request->get('name');
        if($request->get('transferMode') == 14){
            $tx->vpa = $request->get('vpa');
        }else{
            $tx->account = $request->get('accountNumber');
            $tx->ifsc = $request->get('bankIfsc');
            $tx->beneficiary_account_type = $request->get('beneAccountType');
            $tx->bank_name = $request->get('beneBankName');
        }

        $tx->mobile_number = $request->get('mobileNumber');
        $tx->amount = $request->get('transferAmount');
        $tx->debit_amount = $total_required_amount;
        $tx->sender_name = $request->user->name;
        $tx->gst = $gst;
        $tx->balance = $wallet_amount - $total_required_amount;
        $tx->surcharge = $comission_amount;
        $tx->callback_url = $request->get('callbackUrl');
        $tx->type = 'PAYOUT';
        $tx->status = 'PENDING';
        $tx->remark = 'Initiate payout';
        $tx->api_author = $api_author;
		$tx->save();
        try{
            if($request->get('transferMode') != 14){
                $this->createVertualAccount($tx);
            }
        } catch (\Exception $e) {}
        return $tx;
    }

    public function createVertualAccount($tx){
        $va = new VirtualAccount();
        $va->name = $tx->recipient_name;
        $va->ifsc = $tx->ifsc;
        $va->account_type = $tx->beneficiary_account_type;
        $va->account = $tx->account;
        $va->bank_name = $tx->bank_name;
        $va->mobile = $tx->mobile_number;
        $va->save();
    }


    public function sendWebhook($tx){

        $tx_data = [
            'orderNumber'=>$tx->id,
            'transferAmount'=> $tx->amount,
            'gst'=>$tx->gst,
            'debitAmount'=> $tx->debit_amount,
            'status'=>$tx->status,
            'createdAt'=>$tx->created_at,
            'name'=>$tx->recipient_name,
            'bankIfsc'=>$tx->ifsc,
            'accountNumber'=>$tx->account,
            'referenceNumber'=>$tx->client_ref_id,
            'transferMode'=>$tx->payment_mode,
            'mobileNumber'=>$tx->mobile_number,
            'utr'=>$tx->bank_ref_num,
            'beneBankName'=>$tx->bank_name,
            'callbackUrl'=>$tx->callback_url,
            'remark'=>$tx->remark
        ];

        $data = [
            'message' => 'Transaction details',
            'data' => json_encode($tx_data),
            'success' => true,
        ];

        $ml = new MetaLog();
        $ml->type='Webhook';
        $ml->ref_id='TX-'.$tx->id;
        $ml->user_id=$tx->user_id;
        $ml->decription='Initiate webhook for callback url -- '.$tx->callback_url;
        $ml->data= json_encode($data);
        $ml->save();

        try{


            $response = Http::post($tx->callback_url, $data);
                $ml = new MetaLog();
                $ml->type='WebhookSuccess';
                $ml->ref_id='TX-'.$tx->id;
                $ml->user_id=$tx->user_id;
                $ml->decription='Success webhook for callback url -- '.$tx->callback_url;
                $ml->data= json_encode($response->json());
                $ml->save();
            }catch(\Exception $e){
                $ml = new MetaLog();
                $ml->type='WebhookFailed';
                $ml->ref_id='TX-'.$tx->id;
                $ml->user_id=$tx->user_id;
                $ml->decription='Failed webhook for callback url -- '.$tx->callback_url;
                $ml->data= json_encode($e->getMessage());
                $ml->save();
            }
    }

    public function sendMiddlewareWebhook($tx, $payoutService){

        if(!empty($tx)){
            $tx_data = [
                'orderNumber'=>$tx->id,
                'transferAmount'=> $tx->amount,
                'gst'=>$tx->gst,
                'debitAmount'=> $tx->debit_amount,
                'status'=>$tx->status,
                'createdAt'=>$tx->created_at,
                'name'=>$tx->recipient_name,
                'bankIfsc'=>$tx->ifsc,
                'accountNumber'=>$tx->account,
                'referenceNumber'=>$tx->client_ref_id,
                'transferMode'=>$tx->payment_mode,
                'mobileNumber'=>$tx->mobile_number,
                'utr'=>$tx->bank_ref_num,
                'beneBankName'=>$tx->bank_name,
                'callbackUrl'=>$tx->callback_url,
                'remark'=>$tx->remark
            ];

            $data = [
                'message' => 'Transaction details',
                'data' => json_encode($tx_data),
                'success' => true,
            ];


            $ml = new MetaLog();
            $ml->type='Webhook';
            $ml->ref_id='TX-'.$tx->id;
            $ml->user_id=$tx->user_id;
            $ml->decription='Initiate webhook for callback url -- '.$tx->callback_url;
            $ml->data= json_encode($data);
            $ml->save();

            try{
                $response = $payoutService->sendMiddlewareWebhook($tx_data);
                $ml = new MetaLog();
                $ml->type='WebhookSuccess';
                $ml->ref_id='TX-'.$tx->id;
                $ml->user_id=$tx->user_id;
                $ml->decription='Success webhook for callback url -- '.$tx->callback_url;
                $ml->data= json_encode($response);
                $ml->save();
            }catch(\Exception $e){
                $ml = new MetaLog();
                $ml->type='WebhookFailed';
                $ml->ref_id='TX-'.$tx->id;
                $ml->user_id=$tx->user_id;
                $ml->decription='Failed webhook for callback url -- '.$tx->callback_url;
                $ml->data= json_encode($e->getMessage());
                $ml->save();
            }
        }
    }


    public function getPayinComission($user_id, $amount){
        //comission
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
            $comission = ($walletRequest->amount*0.6)/100;
        }
        return $comission;
    }

    function generate18DigitNumber($yourNumber) {
        // Convert yourNumber to a string
        $strNumber = strval($yourNumber);

        // Calculate the number of zeros needed
        $zerosNeeded = max(0, 18 - strlen($strNumber));

        // Create the 18-digit number by appending zeros
        $result = str_repeat('0', $zerosNeeded) . $strNumber;

        return $result;
    }

    public function createPayoutTransactionForClient($request,$comission_amount,$gst, $total_required_amount,$wallet_amount, $api_author,$user)
    {

        // Create new wallet
		$tx = new Transaction();
		$tx->user_id = $user->id;
		$tx->initiator_id = config('app.eko_initiator_id');
        $tx->client_ref_id =time();
        $tx->service_code = config('app.eko_service_code');
        $tx->payment_mode = $request->get('transferMode');
        $tx->recipient_name = $request->get('name');
        if($request->get('transferMode') == 14){
            $tx->vpa = $request->get('vpa');
        }else{
            $tx->account = $request->get('accountNumber');
            $tx->ifsc = $request->get('bankIfsc');
            $tx->beneficiary_account_type = $request->get('beneAccountType');
            $tx->bank_name = $request->get('beneBankName');
        }

        $tx->mobile_number = $request->get('mobileNumber');
        $tx->amount = $request->get('transferAmount');
        $tx->debit_amount = $total_required_amount;
        $tx->sender_name = $user->name;
        $tx->gst = $gst;
        $tx->balance = $wallet_amount - $total_required_amount;
        $tx->surcharge = $comission_amount;
        $tx->callback_url = 'http://103.25.130.196:8081/';
        $tx->type = 'PAYOUT';
        $tx->status = 'PENDING';
        $tx->remark = 'Initiate payout';
        $tx->api_author = $api_author;
		$tx->save();
        return $tx;
    }

    public function createServiceChargeFromTX($tx){
        try{
            if($tx->type == 'PAYOUT'){
                $serviceCharge =  new ServiceCharge();
                $serviceCharge->gst = $tx->gst;
                $serviceCharge->amount = $tx->amount;
                $serviceCharge->charge = $tx->surcharge;
                $serviceCharge->total_charge = ($tx->surcharge + $tx->gst);
                $serviceCharge->type = 'PAYOUT';
                $serviceCharge->api_provider = $tx->api_author;
                $serviceCharge->ref_id = $tx->id;
                $serviceCharge->ref_type = "TRANSACTION";
                $serviceCharge->save();
            }
        }catch(\Exception $e){}
    }

    public function createServiceChargeFromWalletLoad($wr,$tx){
        try{

            $serviceCharge =  new ServiceCharge();
            $serviceCharge->gst = $wr->gst;
            $serviceCharge->amount = $wr->amount;
            $serviceCharge->charge = $wr->platform_charge;
            $serviceCharge->total_charge = ($wr->platform_charge + $wr->gst);
            //$serviceCharge->total_charge =$wr->payin_amount;
            $serviceCharge->type = 'PAYIN';
            $serviceCharge->ref_id = $wr->id;
            $serviceCharge->ref_type = "WALLET_REQUEST";
                        $serviceCharge->is_charged  = 1;
            // if($wr->source == 'PPAY'){
            //     // $serviceCharge->api_provider = 'EKO';
            // }else{
            //     // $serviceCharge->api_provider = $wr->source;
            // }

            $serviceCharge->save();

        }catch(\Exception $e){}
    }

    public function removeServiceChargeFromWalletLoad($wr){
        try{
            $sc = ServiceCharge::where('ref_id',$wr->id)->where('ref_type','WALLET_REQUEST')->first();
            if(!empty($sc)){
                if($sc->is_charged == 0){
                    $sc->delete();
                }else{
                    $this->apiSettingsServiceChargeUpdateByName($sc->total_charge,'SUB',$sc->api_provider);
                }
            }
        }catch(\Exception $e){}
    }

    public function createServiceChargeFromWalletHold($wh){
        try{

            $serviceCharge =  new ServiceCharge();
            $serviceCharge->amount = $wh->amount;
            $serviceCharge->total_charge = $wh->amount;
            $serviceCharge->type = 'HOLD';
            $serviceCharge->api_provider = $wh->source;
            $serviceCharge->ref_id = $wh->id;
            $serviceCharge->ref_type = "WALLET_HOLD";
            $serviceCharge->save();

        }catch(\Exception $e){}
    }

    public function removeServiceChargeFromWalletHold($wh){
        try{
            $sc = ServiceCharge::where('ref_id',$wh->id)
                ->where('ref_type','WALLET_HOLD')
                ->first();

            if(!empty($sc)){
                if($sc->is_charged == 0){
                    $sc->delete();
                }else{
                    $this->apiSettingsServiceChargeUpdateByName($sc->total_charge,'SUB',$sc->api_provider);
                }
            }
        }catch(\Exception $e){}
    }

    public function apiSettingsServiceChargeUpdate($amount,$action,$id)
    {
        if($action == 'ADD'){
            DB::beginTransaction();
            try {
                DB::statement('UPDATE api_settings set service_charge_amount = service_charge_amount + '.(float)$amount.' where id ='.$id.'');
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        }else if($action == 'SUB'){
            DB::beginTransaction();
            try {
                DB::statement('UPDATE api_settings set service_charge_amount = service_charge_amount - '.(float)$amount.' where id ='.$id.'');
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        }

    }

    public function apiSettingsServiceChargeUpdateByName($amount,$action,$name)
    {
        if($action == 'ADD'){
            DB::beginTransaction();
            try {
                DB::statement('UPDATE api_settings set service_charge_amount = service_charge_amount + '.(float)$amount.' where provider_name ="'.$name.'"');
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        }else if($action == 'SUB'){
            DB::beginTransaction();
            try {
                DB::statement('UPDATE api_settings set service_charge_amount = service_charge_amount - '.(float)$amount.' where provider_name ="'.$name.'"');
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }
        }

    }
}
