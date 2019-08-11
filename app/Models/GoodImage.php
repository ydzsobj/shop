<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodImage extends Model
{

    protected $table = 'good_images';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_id',
        'image_url'

    ];

    public function good(){
        return $this->belongsTo(Good::class,'good_id','id');
    }
}
