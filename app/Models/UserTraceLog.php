<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTraceLog extends Model
{
    protected $table = 'user_trace_logs';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'device',
        'lang',
        'referer_url',
        'access_url',
        'good_id',
        'country',
        'area'

    ];
}
