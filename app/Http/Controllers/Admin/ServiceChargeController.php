<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\ServiceCharge;
use App\Models\WithdrawAccount;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CommonController;
use App\Services\PayoutService;
use App\Http\Requests\PayoutRequest;
use App\Models\ServiceChargeTransfer;
use Response;
use App\Models\User;
use App\Models\Comission;



class ServiceChargeController extends Controller
{
           public function __construct()
    {
        $this->Model = new ServiceCharge;
                $this->columns = [
            "id",
            'ref_id',
            'ref_type',
           'type',
            'amount',
            'charge',
            'total_charge',
            'payment_mode',
            'created_at',        ];


        $this->middleware('permission:service-charges', ['only' => ['index','create']]); //to access index and show function atleat one of mentioned permission should be assigned
    }

    public function index()
    {
        $title = 'Service Charge';
        $balance = array();
        $balance['is_super_admin']    = Auth::user()->is_super_admin;
        $providers = [];
        $users = User::select("id","name",'first_name','last_name')->where("id","<>",1)->where("status",1)->get();

        return view('admin.service-charge.index',compact('title','balance','providers','users'));
    }

    /**
     * get ladger for ajax request especially
     *
     * @return \Illuminate\Http\Response
     */
    public function getServiceCharge(Request $request){
		$columns = array(
			0 => 'id',
			2 => 'ref_id',
			3 => 'ref_type',
            4 => 'type',
			5 => 'amount',
			10 => 'created_at'
		);

		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		$search = $request->input('search.value');

		$query = ServiceCharge::query();

		if(!empty($search)){
			$query = $query->orWhere('id','like',"{$search}%");
			$query = $query->orWhere('ref_id','like',"{$search}%");
		}

		$query = $query->select('*');
		$totalFiltered  = $query->count();

		$query = $query->offset($start);
		$query = $query->limit($limit);
		$query = $query->orderBy($order,$dir);
		$serviceCharges = $query->get();

		$data = array();

		if($serviceCharges){
			foreach($serviceCharges->toArray() as $r){
				$nestedData['id'] = $r['id'];
                $nestedData['api_provider'] = $r['api_provider'];
				$nestedData['ref_id'] = $r['ref_id'];
				$nestedData['ref_type'] = $r['ref_type'];
				$nestedData['type'] = $r['type'];
                $nestedData['amount'] = $r['amount'];
                $nestedData['gst'] = $r['gst'];
				$nestedData['charge'] = $r['charge'];
				$nestedData['total_charge'] = $r['total_charge'];
				$nestedData['created_at'] = date('d-m-Y h:i:s A',strtotime($r['created_at']));
				$nestedData['action'] = '';

				if($r['is_charged'] == '1'){
					$nestedData['is_charged'] = '<span class="badge bg-success">YES</span>';
				}else{
					$nestedData['is_charged'] = '<span class="badge bg-danger">NO</span>';
				}

				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> 0,
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);

		echo json_encode($json_data);

	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {

        $title = 'Add Account';
	    return view('admin.service-charge.add-account',compact('title'));
    }

  public function addServices(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'user_id'               => 'required|exists:users,id',
        'comission_amount'      => 'required|numeric|min:1',
        'comission_percentage'  => 'required|numeric|min:1',
        'type'                  => 'required|string|max:255',
    ], [
        'user_id.required'              => 'Select User is required.',
        'user_id.exists'                => 'Selected user does not exist.',
        'comission_amount.required'     => 'Commission Amount is required.',
        'comission_amount.numeric'      => 'Commission Amount must be a number.',
        'comission_amount.min'          => 'Commission Amount must be at least 1.',
        'comission_percentage.required' => 'Commission Percentage is required.',
        'comission_percentage.numeric'  => 'Commission Percentage must be a number.',
        'comission_percentage.min'      => 'Commission Percentage must be at least 1.',
        'type.required'                 => 'Type is required.',
    ]);

    // Create and save the commission record
    $commission = new Comission(); // Make sure this model exists
    $commission->user_id              = $request->user_id;
    $commission->comission_percentage = $request->comission_percentage;
    $commission->comission_amount     = $request->comission_amount;
    $commission->type                 = $request->type; // ← you're validating it, now saving it too

    $commission->save();

    return redirect()
        ->back() // Change this to correct route if needed
        ->with('success', 'Commission entry saved successfully!');
}
  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $this->validate($request, [
		    'name' => 'required',
			'ifsc' => 'required',
		    'account_type' => 'required',
		    'account' => 'required',
            'bank_name' => 'required',
            'mobile' => 'required'
	    ]);

	    $input = $request->all();
        try{
            $w_account = new WithdrawAccount();
            $w_account->name = $input['name'];
            $w_account->ifsc = $input['ifsc'];
            $w_account->account_type = $input['account_type'];
            $w_account->account = $input['account'];
            $w_account->bank_name = $input['bank_name'];
            $w_account->mobile = $input['mobile'];
            $is_hold_payout = array_key_exists('is_hold_payout', $input);
            if ($is_hold_payout == false) {
                $w_account->is_hold_payout = 0;
            } else {
                $w_account->is_hold_payout = 1;
            }
            $w_account->save();

            Session::flash('success_message', 'Great! Account added successfully!');
            $input = null;
        }catch(\Exception $e){

            Session::flash('error_message', 'Account no already exists!!! '.$e->getMessage());
        }
	    return redirect()->back()->withInput($input);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	    $w_account = WithdrawAccount::find($id);

        $this->validate($request, [
		    'name' => 'required',
			'ifsc' => 'required',
		    'account_type' => 'required',
		    'account' => 'required',
            'bank_name' => 'required',
            'mobile' => 'required',
	    ]);
        $input = $request->all();
        try{
            $w_account->name = $input['name'];
            $w_account->ifsc = $input['ifsc'];
            $w_account->account_type = $input['account_type'];
            $w_account->account = $input['account'];
            $w_account->bank_name = $input['bank_name'];
            $w_account->mobile = $input['mobile'];
            $is_hold_payout = array_key_exists('is_hold_payout', $input);
            if ($is_hold_payout == false) {
                $w_account->is_hold_payout = 0;
            } else {
                $w_account->is_hold_payout = 1;
            }
            $w_account->save();
            Session::flash('success_message', 'Great! Account saved successfully!');
        }catch(\Exception $e){

            Session::flash('error_message', 'Account no already exists!!! '.$e->getMessage());
        }
	    return redirect()->back();
    }


 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = WithdrawAccount::find($id);
	    return view('admin.service-charge.edit-account', ['title' => 'Edit Account', 'account'=>$account]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        $title = 'Accounts';
	    return view('admin.service-charge.accounts',compact('title'));
    }

     /**
     * get ladger for ajax request especially
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccounts(Request $request){
		$columns = array(
			0 => 'id',
			1 => 'name',
            2 => 'mobile',
			3 => 'account',
			4 => 'account_type',
            5 => 'bank_name',
			6 => 'ifsc',
            7 => 'created_at'
		);

		$totalData = WithdrawAccount::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');


		if(empty($request->input('search.value'))){
            $virtualAccounts = WithdrawAccount::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = WithdrawAccount::count();
		}else{
			$search = $request->input('search.value');
                $virtualAccounts = WithdrawAccount::orWhere('mobile','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();

            $totalFiltered = WithdrawAccount::orWhere('mobile','like',"%{$search}%")
				->count();
		}
		$data = array();
		if($virtualAccounts){
			foreach($virtualAccounts->toArray() as $r){
                $edit_url = route('service_charge.edit',$r['id']);
				$nestedData['id'] = $r['id'];
                $nestedData['name'] = $r['name'];
				$nestedData['mobile'] = $r['mobile'];
				$nestedData['account'] = $r['account'];
                $nestedData['bank_name'] = $r['bank_name'];
                $nestedData['ifsc'] = $r['ifsc'];
				$nestedData['created_at'] = date('d-m-Y h:i:s A',strtotime($r['created_at']));
                if($r['account_type'] == 1){
                    $nestedData['account_type'] = 'SAVING';
                }else{
                    $nestedData['account_type'] = 'CURRENT';
                }
                if($r['is_hold_payout'] == 1){
                    $nestedData['is_hold_payout'] = '<span class="badge bg-success">YES</span>';
				}else{
					$nestedData['is_hold_payout'] = '<span class="badge bg-danger">NO</span>';
				}
                $nestedData['action'] = '
                                <div>
                                <td>

                                    <a title="Edit User" class="btn btn-icon btn-sm btn-success"
                                       href="'.$edit_url.'">
                                       <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </td>
                                </div>
                            ';
				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);

		echo json_encode($json_data);

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
$data['ref_id'] = $value->ref_id;
$data['ref_type'] = $value->ref_type ?? 'N/A';
            $data['type'] = $value->type ?? 'N/A';
                        $data['amount'] = $value->amount ?? 'N/A';
                        $data['gst']=$value->gst ?? 'N/A';
$data['charge']=$value->charge ?? 'N/A';
                        $data['total_charge']=$value->total_charge ?? 'N/A';
				if($value->is_charged == '1'){
					$data['is_charged'] = '<span class="badge bg-success">YES</span>';
				}else{
					$data['is_charged'] = '<span class="badge bg-danger">NO</span>';
				}


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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function withdraw($id)
    {

        $accounts = WithdrawAccount::all();
        $all_accounts = array();
        foreach ($accounts as $key => $value) {
            $all_accounts[$value->id] = $value->name.' - '.$value->bank_name.' - '.$value->account;
        }

        $provider = [];
	    return view('admin.service-charge.withdraw', ['title' => 'Withdraw Amount', 'accounts'=>$all_accounts, 'provider'=>$provider]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submitWithdraw(Request $request, PayoutService $payoutService)
    {
        $this->validate($request, [
		    'account' => 'required',
			'amount' => ['required','numeric','min:100','max:500000'],
            'provider' => 'required'
	    ]);

        $input = $request->all();
        $provider =[];//= ApiSetting::find($input['provider']);
        $account = WithdrawAccount::find($input['account']);
        $user    = Auth::user();

        if(!empty($provider) && !empty($account)){
            if($provider->provider_name == 'EKO'){
                if ($provider->service_charge_amount > $input['amount']){
                    $last_balance = $provider->service_charge_amount;
                    $provider->service_charge_amount = $provider->service_charge_amount - $input['amount'];
                    $provider->save();

                    $sct = $this->createServiceChargeTransfer($input,'EKO',$user->id,$account->id);

                    $params = [
                        'initiator_id'=> config('app.eko_initiator_id'),
                        'client_ref_id'=>'ST-'.$sct->id,
                        'service_code'=> config('app.eko_service_code'),
                        'payment_mode'=>5,
                        'recipient_name'=>strtolower($account->name),
                        'account'=>$account->account,
                        'ifsc'=>$account->ifsc,
                        'amount'=>$input['amount'],
                        'sender_name'=>strtolower($user->name),
                        'beneficiary_account_type'=>$account->account_type,
                    ];
                    $sct->payload = json_encode($params);
                    $sct->last_balance = $last_balance;
                    $sct->save();
                    try{
                        (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'Calling EKO Payout API - ST id '.$sct->id, $params);
                        $response = $payoutService->initiateEkoPayout($params);
                        (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'API response from EKO - and ST id -'.$sct->id, $response);
                        $bank_ref_num = '';
                        if(!empty($response) && isset($response->response_status_id) && $response->response_status_id == 0){
                            if(!empty($response->data['bank_ref_num'])) { $bank_ref_num = $response->data['bank_ref_num']; }
                            $sct->response = json_encode($response);
                            $sct->tid = $response->data['tid'];
                            $sct->save();
                            if(($response->data['tx_status'] == 0 || $response->data['tx_status'] == '0') && !empty($bank_ref_num)){
                                $sct->utr = $bank_ref_num;
                                $sct->status = 'SUCCESS';
                                $sct->save();
                                Session::flash('success_message', 'Withdraw Successfully!!!');
                                return redirect()->route('service_charge.serviceChargeTransfer');
                            }else{
                                Session::flash('error_message', 'Withdraw Pending!!!');
                                return redirect()->route('service_charge.serviceChargeTransfer');
                            }
                        }else{
                            Session::flash('error_message', 'Withdraw Pending!!!');
                            return redirect()->route('service_charge.serviceChargeTransfer');
                        }
                    }catch (\Exception $e) {
                        (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'Exception for ST id  - '.$sct->id, $e->getMessage());
                        Session::flash('error_message', 'Exception while withdraw!!!');
                    }

                }else{
                    Session::flash('error_message', 'Withdraw amount should be less then balance!!!');
                }
            }else if($provider->provider_name == 'ISERVEU'){
                $this->iserVeuWithdraw($provider,$input,$user,$account,$payoutService);
            }
            else{
                Session::flash('error_message', 'Provider Not enabled!!!');
            }
        } else {
            Session::flash('error_message', 'Invalid provider or account!!!');
        }

	    return redirect()->back()->withInput($input);
    }

    private function iserVeuWithdraw($provider,$input,$user,$account,$payoutService){
        if ($provider->service_charge_amount > $input['amount']){
            $last_balance = $provider->service_charge_amount;
            $provider->service_charge_amount = $provider->service_charge_amount - $input['amount'];
            $provider->save();
            $sct = $this->createServiceChargeTransfer($input,'ISERVEU',$user->id,$account->id);
            $pincode = 751024;
            $latlong = "22.8031731,88.7874172";
            $bank_ref_num = '';
            $params = array(
                "beneName"=>strtolower($account->name),
                "beneAccountNo"=>$account->account,
                "beneifsc"=>$account->ifsc,
                "benePhoneNo"=>(int)$account->mobile,
                "beneBankName"=>$account->bank_name,
                "clientReferenceNo"=>(new CommonController)->generate18DigitNumberForServiceCharge($sct->id),
                "amount"=>$input['amount'],
                "fundTransferType"=>'IMPS',
                "pincode"=>$pincode,
                "custName"=>strtolower($user->name),
                "custMobNo"=>(int)$account->mobile,
                "custIpAddress"=>"103.25.130.196",
                "latlong"=>$latlong,
                "paramA"=>"Payout A",
                "paramB"=>"Payout B"
            );
            $sct->payload = json_encode($params);
            $sct->last_balance = $last_balance;
            $sct->save();
            try{
                (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'Calling ISERVEU Payout API - ST id '.$sct->id, $params);
                $response = $payoutService->initiateIserVeuPayout($params,0);
                (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'API response from ISERVEU - and ST id -'.$sct->id, $response);
                if(!empty($response)){
                    if(!empty($response->rrn)) { $bank_ref_num = $response->rrn; }
                    $sct->response = json_encode($response);
                    $sct->tid = $response->transactionId;
                    $sct->utr = $bank_ref_num;
                    $sct->save();
                    if(!empty($response)  && !empty($response->status) && $response->status == 'SUCCESS' && $response->subStatus == 0){
                        $sct->status = 'SUCCESS';
                        $sct->save();
                        Session::flash('success_message', 'Withdraw Successfully!!!');
                        return redirect()->route('service_charge.serviceChargeTransfer');
                    }else if($response->status == 'FAILED'){
                        $provider->service_charge_amount = $provider->service_charge_amount + $input['amount'];
                        $provider->save();
                        $sct->status = 'FAILED';
                        $sct->save();
                        Session::flash('error_message', 'Withdraw Failed!!!');
                    }
                    else{
                        Session::flash('error_message', 'Withdraw Pending!!!');
                        return redirect()->route('service_charge.serviceChargeTransfer');
                    }
                }else{
                    Session::flash('error_message', 'Withdraw Pending!!!');
                    return redirect()->route('service_charge.serviceChargeTransfer');
                }
            }catch (\Exception $e) {
                (new CommonController)->createLog('ST-'.$sct->id, $user->id, 'SERVICE-CHARGE-TRANSFER', 'Exception for ST id  - '.$sct->id, $e->getMessage());
                Session::flash('error_message', 'Exception while withdraw!!!');
            }

        }else{
            Session::flash('error_message', 'Withdraw amount should be less then balance!!!');
        }
    }

    private function createServiceChargeTransfer($input,$api_provider,$user_id,$withdraw_account_id){
        $sct = new ServiceChargeTransfer();
        $sct->amount = $input['amount'];
        $sct->status = 'PENDING';
        $sct->api_provider = $api_provider;
        $sct->remark = 'Service charge transfer';
        $sct->admin_id = $user_id;
        $sct->withdraw_account_id = $withdraw_account_id;
        $sct->save();
        return $sct;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serviceChargeTransfer()
    {
        $title = 'Transfers';
	    return view('admin.service-charge.transfers',compact('title'));
    }

     /**
     * get ladger for ajax request especially
     *
     * @return \Illuminate\Http\Response
     */
    public function getServiceChargeTransfers(Request $request){
		$columns = array(
			0 => 'id',
			8 => 'utr',
            9 => 'status',
            11 => 'created_at'
		);

		$totalData = ServiceChargeTransfer::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

		$query = ServiceChargeTransfer::query();
		$query = $query->with('adminId:id,name');
        $query = $query->with('withdrawAccountId:id,account,name,bank_name');

		if(!empty($from_date) && !empty($to_date)){
			$query = $query->whereDate('created_at','>=',$from_date);
			$query = $query->whereDate('created_at','<=',$to_date);
		}

		if(!empty($status)){
			$query = $query->where('status', $status);
		}
		if(!empty($search)){
			$query = $query->orWhere('id','like',"{$search}%");
			$query = $query->orWhere('utr','like',"{$search}%");
		}

		$query = $query->select('service_charge_transfers.id',
            'service_charge_transfers.admin_id',
			'service_charge_transfers.utr',
			'service_charge_transfers.withdraw_account_id',
			'service_charge_transfers.amount',
			'service_charge_transfers.last_balance',
			'service_charge_transfers.status',
			'service_charge_transfers.last_balance',
			'service_charge_transfers.remark',
			'service_charge_transfers.created_at',
			'service_charge_transfers.api_provider',

		);
		$totalFiltered  = $query->count();

		$query = $query->offset($start);
		$query = $query->limit($limit);
		$query = $query->orderBy($order,$dir);
		$transfers = $query->get();


		$data = array();
		if($transfers){
			foreach($transfers->toArray() as $r){
                $edit_url = route('service_charge.edit',$r['id']);
				$nestedData['id'] = $r['id'];
                $nestedData['provider'] = $r['api_provider'];
				$nestedData['bank_name'] = $r['withdraw_account_id']['bank_name'];
                $nestedData['account'] = $r['withdraw_account_id']['account'];
				$nestedData['name'] = $r['withdraw_account_id']['name'];
                $nestedData['amount'] = $r['amount'];
                $nestedData['last_balance'] = $r['last_balance'];
                $nestedData['utr'] = $r['utr'];
                $nestedData['status'] = $r['status'];
                $nestedData['withdraw_by'] = $r['admin_id']['name'];
				$nestedData['created_at'] = date('d-m-Y h:i:s A',strtotime($r['created_at']));
                if($r['status'] == 'SUCCESS'){
					$nestedData['status'] = '<span class="badge bg-success">'.$r['status'].'</span>';
				}else{
					$nestedData['status'] = '<span class="badge bg-danger">'.$r['status'].'</span>';
				}
				$data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);

		echo json_encode($json_data);

	}

    public function refreshTransferStatus(Request $request, PayoutService $payoutService){

        $providers = ApiSetting::all();
        foreach ($providers as $key => $provider) {
            try{
                if($provider->is_refresh_in_progress == 0){
                    $provider->is_refresh_in_progress = 1;
                    $provider->save();
                    if($provider->provider_name == 'EKO'){
                        $this->updateEkoTransferStatus($provider,$payoutService);
                    }
                }
            }catch(\Exception $e){}
            $provider->is_refresh_in_progress = 0;
            $provider->save();
        }
        return Response::json([
            'message' => 'All Status refresh successfully',
            'status' => true,
        ], 200);
    }


    private function updateEkoTransferStatus($provider,$payoutService){
        $transfers = ServiceChargeTransfer::where('api_provider','EKO')
            ->where('status','PENDING')
            ->get();
            $params = [
                'initiator_id'=> config('app.eko_initiator_id')
            ];

        foreach ($transfers as $key => $tf) {

            $txDetails = $payoutService->getEkoPayoutTxDetailsWithClientRef('ST-'.$tf->id, $params);
            if(!empty($txDetails) && $txDetails->response_status_id == 0 && !empty($txDetails->data)){
                if($txDetails->data['tx_status'] == 0 || $txDetails->data['tx_status'] == '0'){
                    $tf->status = 'SUCCESS';
                    $tf->utr  = $txDetails->data['bank_ref_num'];
                    $tf->save();
                } else if($txDetails->data['tx_status'] == 1 || $txDetails->data['tx_status'] == 4 ){
                    $tf->status = 'FAILED';
                    $tf->save();
                }
            }else if(!empty($txDetails) && !empty($txDetails->data)){
                if($txDetails->data['tx_status'] == 1){
                    $tf->status = 'FAILED';
                    $tf->save();
                }
            }
        }
    }

}
