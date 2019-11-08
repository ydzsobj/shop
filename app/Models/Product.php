<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'english_name'

    ];

    public function attrs(){
        return $this->hasMany(ProductAttribute::class);
    }

    public function skus(){
        return $this->hasMany(Produ);
    }
}
