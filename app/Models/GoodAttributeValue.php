<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodAttributeValue extends Model
{
    //
    protected $table = 'good_attribute_values';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_attribute_id',
        'attr_value_id',
        'attr_value_name',
        'show_name',
        'thumb_url',
    ];

}
