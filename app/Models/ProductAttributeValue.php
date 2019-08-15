<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    //
    protected $table = 'product_attribute_values';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'product_attribute_id',
        'attr_value_id',
        'attr_value_name',
        'thumb_url',
        'show_name',
    ];

}
