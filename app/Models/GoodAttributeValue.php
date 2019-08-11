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
        'remote_id',
        'name',
        'thumb_url',
        'is_show',
    ];

}
