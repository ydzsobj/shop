<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulePercent extends Model
{
    protected $table = 'rule_percents';

    protected $page_size = 20;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'percent',

    ];

    public function coupon_code(){
        return $this->morphOne(CouponCode::class,'targetable');
    }
}
