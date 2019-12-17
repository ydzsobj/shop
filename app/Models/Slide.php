<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    //
    protected $table = 'slides';

    protected $fillable = [
        'image_url',
        'sort',
        'good_id',
        'country_id'
    ];

    /**
     * @return $this
     */
    public function good(){
        return $this->belongsTo(Good::class)->withDefault();
    }
}
