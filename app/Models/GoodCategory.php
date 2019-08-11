<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodCategory extends Model
{
    //
    protected $table = 'good_categories';

    public function goods(){
        return $this->hasMany(Good::class, 'category_id','id');
    }
}
