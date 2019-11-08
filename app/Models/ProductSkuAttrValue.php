<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSkuAttrValue extends Model
{
    protected $table = 'product_sku_attr_values';

    protected $fillable = [
        'product_sku_id',
        'attr_value_id',
        'attr_value_name'
    ];
}
