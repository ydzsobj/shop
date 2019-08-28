<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodModuleImage extends Model
{
    //
    protected $table = 'good_module_images';

    protected $fillable = [
        'image_url',
        'sort',
        'good_id',
        'good_module_id'
    ];

    function good(){
        return $this->belongsTo(Good::class)->withDefault();
    }
}
