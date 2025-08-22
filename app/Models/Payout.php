<?php

// app/Models/Payout.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'upload_type',
        'transfer_by',
        'account_number',
        'account_holder_name',
        'ifsc',
        'bank_name',
        'transfer_amount',
        'payment_mode',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fetchData($request, $columns) {

        $query =Payout::where('id', '!=', '');

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
        if (isset($request['search']['value']) && !empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->where('account_number', 'like', '%' . $searchValue . '%');
            });
        }
            $query->where('upload_type',$request->upload_type);


        // if (isset($request->order_column)) {
        //     $customers = $query->orderBy($columns[$request->order_column], $request->order_dir);
        // } else {
        //     $customers = $query->orderBy('id', 'desc');
        // }
    $orderColumn = $columns[$request->order_column] ?? 'id';
    $orderDir = $request->order_dir ?? 'desc';

    return $query->orderBy($orderColumn, $orderDir);
    }
}
