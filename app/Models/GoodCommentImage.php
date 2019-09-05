<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodCommentImage extends Model
{

    protected $table = 'good_comment_images';


    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_comment_id',
        'image_url'

    ];
}
