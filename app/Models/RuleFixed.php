<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleFixed extends Model
{
    protected $table = 'rule_fixed';

    protected $page_size = 20;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'money',
    ];

    public function coupon_code(){
        return $this->morphOne(CouponCode::class,'targetable');
    }

}
