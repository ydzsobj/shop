<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table =  'attributes';

    public function attr_values(){
       return $this->hasMany(AttributeValue::class);
    }
}
