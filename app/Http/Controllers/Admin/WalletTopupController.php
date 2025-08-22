<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Models\WalletRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Wallet;
class WalletTopupController extends Controller
{
       public function __construct()
    {
        $this->middleware('permission:wallet-topup-request', ['only' => ['index','show']]); //to access index and show function atleat one of mentioned permission should be assigned
        $this->middleware('permission:wallet-topup', ['only' => ['create','store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select("id","name",'first_name','last_name')->where("id","<>",1)->where("status",1)->get();
        return view('admin.wwalletTopUp.index',compact('users'));
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
        $validated = $request->validate([
    'user_id'   => 'required',
    'amount' => 'required|numeric|min:1',
    'remark' => 'required|string|max:255',
], [
    'user_id.required'   => 'Select User is required.',
    'amount.required' => 'Amount is required.',
    'amount.numeric'  => 'Amount must be a number.',
    'amount.min'      => 'Amount must be at least 1.',
    'remark.required' => 'Remark is required.',
    'remark.max'      => 'Remark may not be greater than 255 characters.',
]);

        $input = $request->all();
        $pin = 8659;
        // if($input['amount'] > 300000){
        //     if(empty($input['pin']) || $input['pin'] != $pin){
        //         return redirect()->back()
        // ->withErrors(['amount' => 'Invalid Pin, Pin Required if amount greater than 300000'])
        // ->withInput(); // ✅ This preserves old input values

        //     }
        // }
        $wallet = Wallet::where("user_id", $input['user_id'])->first();

        if (!$wallet) {
            $wallet = new Wallet();
            $wallet->user_id = $input['user_id'];
            $wallet->amount = 0;//$input['amount'];
            $wallet->save();
        }
        $walletRequest = WalletRequest::where('requested_user_id', Auth::id())
                            ->orderBy('id','desc')
                            ->limit(1)
                            ->get();


        if(!empty($walletRequest) && count($walletRequest)>0){
            $request_time = strtotime($walletRequest->toArray()[0]['created_at']);
            $current_time = strtotime(date("h:i:sa"));
            $minutes = round(abs($current_time - $request_time) / 60,2);
            $amount = $walletRequest->toArray()[0]['amount'];

            if($amount == $input['amount'] && $minutes <= 1 ){

                                return redirect()->back()
        ->withErrors(['amount' => 'llet Request already submitted with this amount!'])
        ->withInput(); // ✅ This preserves old input values

            }
        }


        $walletRequest = new WalletRequest();
        $walletRequest->user_id = $input['user_id'];
        $walletRequest->amount = $input['amount'];
        $walletRequest->remark = $input['remark'];
        $walletRequest->requested_user_id =  Auth::id();
        $walletRequest->status = 'PENDING';
        $walletRequest->source = 'METASPAY';
        $walletRequest->save();
        return redirect()->route('wallet-topup-request.index')->with('success', 'Great! Wallet Request has been saved successfully!');
    }

}
