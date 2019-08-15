<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    //
    protected $table = 'product_attributes';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'attr_id',
        'attr_name',
        'sort',
        'show_name'

    ];

    public function attribute_values(){

        return $this->hasMany(ProductAttributeValue::class);
    }
}
