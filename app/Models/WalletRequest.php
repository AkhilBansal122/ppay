<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WalletRequest extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requestedBy(){
        return $this->belongsTo(User::class, 'requested_user_id');
    }
        public function fetchData($request, $columns) {

    $query = WalletRequest::query()
        ->select(
            'wallet_requests.*',
            'users.name as user_name',
            'requested_by.name as requested_by_name'
        )
        ->join('users', 'wallet_requests.user_id', '=', 'users.id')
        ->leftJoin('users as requested_by', 'wallet_requests.requested_user_id', '=', 'requested_by.id');

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
    if (!empty($request['search']['value'])) {
        $search = $request['search']['value'];

        $query->where(function ($q) use ($search) {
            $q->whereHas('userId', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('requestedBy', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            })
            ->orWhere('remark', 'like', "%{$search}%")
            ->orWhereDate('created_at', 'like', "%{$search}%");
        });
    }

    $orderColumn = $columns[$request->order_column] ?? 'id';
    $orderDir = $request->order_dir ?? 'desc';

    return $query->orderBy($orderColumn, $orderDir);
    }
}
