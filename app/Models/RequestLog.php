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


}
