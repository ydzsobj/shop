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
}
