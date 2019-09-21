<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodOrderSku extends Model
{
    protected $table = 'good_order_skus';

    protected $fillable = [
        'good_order_id',
        'good_id',
        'sku_id',
        'sku_nums',
        'price'
    ];

    public function sku_info(){
       return $this->hasOne(GoodSku::class, 'good_id', 'good_id')->where('sku_id',$this->sku_id);
    }

    public function good(){
        return $this->belongsTo(Good::class)->withDefault()->withTrashed();
    }

    public function good_order(){
        return $this->belongsTo(GoodOrder::class)->withDefault();
    }
}
