<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
        protected $fillable = [
        'ip',
        'end_point',
        'user_agent',
        'data',

        'type'

    ];

   public static function fetchData($request, $columns) {

        $query =RequestLog::where('id', '!=', '');

        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= "' . date("Y-m-d", strtotime($request->from_date)) . '"');
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= "' . date("Y-m-d", strtotime($request->end_date)) . '"');
        }

    $orderColumn = $columns[$request->order_column] ?? 'id';
    $orderDir = $request->order_dir ?? 'desc';

    return $query->orderBy($orderColumn, $orderDir);
    }


}
