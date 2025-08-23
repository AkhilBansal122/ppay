<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comission extends Model
{
    use HasFactory;
    protected $guarded =[];
        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function fetchData($request, $columns) {

        $query =Comission::where('id', '!=', '');

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }
        if (isset($request['search']) && !empty($request['search'])) {
            $searchValue = $request['search'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('type', 'like', '%' . $searchValue . '%')
                    ->orWhere('comission_amount',  $searchValue)
                    ->orWhere('comission_percentages',  $searchValue);
                });
        }
    $orderColumn = $columns[$request->order_column] ?? 'id';
    $orderDir = $request->order_dir ?? 'desc';

    return $query->orderBy($orderColumn, $orderDir);
    }

}
