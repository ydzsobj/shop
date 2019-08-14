<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodAuditLog extends Model
{
    protected $table = 'good_audit_logs';

    protected $fillable = [

        'admin_user_id',
        'status',
        'remark',
        'good_order_id',

    ];

    function admin_user(){
        return $this->belongsTo(AdminUser::class);
    }
}
