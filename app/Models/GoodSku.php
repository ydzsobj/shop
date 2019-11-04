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
       return $this->belongsTo(Good::class)->withDefault()->withTrashed();
    }

    public function get_sku_name($sku = null){

        $sku = $sku ?: $this;

        $sku_name = '';

        if($sku->s1_name){
            $sku_name .= $sku->s1_name .' ';
        }

        if($sku->s2_name){
            $sku_name .= $sku->s2_name .' ';
        }

        if($sku->s3_name){
            $sku_name .= $sku->s3_name .' ';
        }

        return $sku_name;
    }

    /**
     * @获取所有sku
     */
    public function product_skus($good_id){

        $good_skus = self::where('good_id', $good_id)->get();

        $sku_list = $good_skus->map(function($good_sku){
                return [ 'id' => $good_sku->sku_id ,'name' => $good_sku->get_sku_name()];
        });

        return $sku_list->pluck('name','id');

    }
}
