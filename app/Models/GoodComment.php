<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodComment extends Model
{
    use SoftDeletes;

    protected $table = 'good_comments';

    protected $page_size = 20;


    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_id',
        'comment',

    ];

}
