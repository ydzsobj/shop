<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table = 'product_skus';

    protected $fillable = [
        'product_id',
        'sku_code',
        'price',
        'attr_value_names',
        'sku_image'
    ];

    public function attr_values(){

        return $this->hasMany(ProductSkuAttrValue::class);
    }

    public static function check_sku_code($sku_code, $product){

        return self::where('sku_code', $sku_code)->where('product_id', '<>', $product->id)->first();
    }
}
