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
     * 展示评价
     */
    const TYPE_SHOW = 1;

    /**
     *客户评价
     */
    const TYPE_USER = 2;
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'good_id',
        'comment',
        'type_id',
        'comment',
        'name',
        'phone',
        'star_scores',
        'audited_at',
        'admin_user_id'

    ];

    /**
     * @return $this
     */
    public function good(){
        return $this->belongsTo(Good::class)->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment_images(){
        return $this->hasMany(GoodCommentImage::class);
    }

    public function admin_user(){
        return $this->belongsTo(AdminUser::class)->withDefault();
    }

    public function get_data($request){

        $good_id = $request->get('good_id');

        $per_page = $request->get('per_page') ?: $this->page_size;

        $search = compact('good_id','per_page');

        $data = self::with(['good','comment_images'])
            ->when($good_id, function($query) use($good_id){
                $query->where('good_id', $good_id);
            })
            ->orderBy('id','desc')
            ->paginate($this->page_size);

        return [$search, $data];
    }

}
