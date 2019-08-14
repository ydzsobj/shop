<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodSku extends Model
{
    protected  $table = 'good_skus';

    protected $fillable = [
        'sku_id',
        'good_id',
        's1',
        's1_name',
        's2',
        's2_name',
        's3',
        's3_name',
        'price',
        'stock',
        'thumb_url',
        'disabled_at'

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function good(){
       return $this->belongsTo(Good::class)->withDefault();
    }

}
