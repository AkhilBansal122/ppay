<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
        'balance',
        'reference',
        'description',
        'transaction_id',
        'module_type',
        'order_id',
        'response_data',
        'status','upload_type',
        'remark',
        'initiator_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public static  function fetchRecentData($request, $columns) {

        $query =Transaction::where('id', '!=', '');
        $query->where('is_active',1);


        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
                if (isset($request->search)) {
            $searchValue = $request->search;

           $query->where(function ($q) use ($searchValue) {
                $q->where('transaction_id', 'like', '%' . $searchValue . '%')
                ->orWhere('type', 'like', '%' . $searchValue . '%')
                ->orWhere('amount', 'like', '%' . $searchValue . '%')
              ->orWhere('balance', 'like', '%' . $searchValue . '%')
                ->orWhere('upload_type', 'like', '%' . $searchValue . '%')
              ->orWhere('status', 'like', '%' . $searchValue . '%');

            });

                }
        if(isset($request->user_id) &&  !empty($request->user_id)){
            $query->where('user_id',$request->user_id);

        }
            // $query->where('upload_type',$request->upload_type);


        if (isset($request->order_column)) {
            $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $customers = $query->orderBy('id', 'desc');
        }
        return $customers;
    }

    public static  function fetchPayInData($request, $columns) {

        $query =Transaction::where('id', '!=', '');
        $query->whereIn('type', ['payin', 'WALLETLOAD']);
       $query->where('is_active',1);

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
               if (isset($request->search)) {
            $searchValue = $request->search;

           $query->where(function ($q) use ($searchValue) {
                $q->where('transaction_id', 'like', '%' . $searchValue . '%')
                ->orWhere('type', 'like', '%' . $searchValue . '%')
                ->orWhere('amount', 'like', '%' . $searchValue . '%')
              ->orWhere('balance', 'like', '%' . $searchValue . '%')
                ->orWhere('upload_type', 'like', '%' . $searchValue . '%')
              ->orWhere('status', 'like', '%' . $searchValue . '%');

            });

                }
        if(isset($request->user_id) &&  !empty($request->user_id)){
            $query->where('user_id',$request->user_id);

        }
            // $query->where('upload_type',$request->upload_type);


        if (isset($request->order_column)) {
            $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $customers = $query->orderBy('id', 'desc');
        }
        return $customers;
    }
    public static  function fetchPayOutData($request, $columns) {

        $query =Transaction::where('id', '!=', '');
       $query->where('type',"payout");
       $query->where('is_active',1);

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
               if (isset($request->search)) {
            $searchValue = $request->search;

           $query->where(function ($q) use ($searchValue) {
                $q->where('transaction_id', 'like', '%' . $searchValue . '%')
                ->orWhere('type', 'like', '%' . $searchValue . '%')
                ->orWhere('amount', 'like', '%' . $searchValue . '%')
              ->orWhere('balance', 'like', '%' . $searchValue . '%')
                ->orWhere('upload_type', 'like', '%' . $searchValue . '%')
              ->orWhere('status', 'like', '%' . $searchValue . '%');

            });

                }
        if(isset($request->user_id) &&  !empty($request->user_id)){
            $query->where('user_id',$request->user_id);

        }
            // $query->where('upload_type',$request->upload_type);


        if (isset($request->order_column)) {
            $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $customers = $query->orderBy('id', 'desc');
        }
        return $customers;
    }

    public static  function fetchledgersData($request, $columns) {

        $query =Transaction::where('id', '!=', '');
        $query->whereIn('type', ['payout', 'payin','WALLETLOAD','REVERTWALLE']);
       $query->where('is_active',1);

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
        if (isset($request->search)) {
            $searchValue = $request->search;

           $query->where(function ($q) use ($searchValue) {
                $q->where('transaction_id', 'like', '%' . $searchValue . '%')
                ->orWhere('type', 'like', '%' . $searchValue . '%')
                ->orWhere('amount', 'like', '%' . $searchValue . '%')
              ->orWhere('balance', 'like', '%' . $searchValue . '%')
                ->orWhere('upload_type', 'like', '%' . $searchValue . '%')
              ->orWhere('status', 'like', '%' . $searchValue . '%');

            });

                }
        if(isset($request->user_id) &&  !empty($request->user_id)){
            $query->where('user_id',$request->user_id);

        }
            // $query->where('upload_type',$request->upload_type);


        if (isset($request->order_column)) {
            $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $customers = $query->orderBy('id', 'desc');
        }
        return $customers;
    }
}
