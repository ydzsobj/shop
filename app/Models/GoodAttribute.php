<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodAttribute extends Model
{
    //
    protected $table = 'good_attributes';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_id',
        'attr_id',
        'attr_name',
        'show_name',
        'sort',
    ];

    public function attribute_values(){

        return $this->hasMany(GoodAttributeValue::class);
    }
}
