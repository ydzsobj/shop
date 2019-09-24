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
     * 系统评价
     */
    const TYPE_SYSTEM = 1;

    /**
     * 客户评价
     */
    const TYPE_USER = 2;

    /**
     * @审核通过
     */
    const NO_AUDIT = 1;

    /**
     * 审核通过
     */
    const AUDIT_PASSWED = 2;


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
        'admin_user_id',
        'created_at'

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

    /**
     * @param $request
     * @return array
     */
    public function get_data($request){

        $good_id = $request->get('good_id');

        $audit_status = $request->get('audit_status');

        $type_id = $request->get('type_id');

        $per_page = $request->get('per_page') ?: $this->page_size;

        $search = compact('good_id','per_page','type_id', 'audit_status');

        $data = self::with(['good','comment_images'])

            //单品筛选
            ->when($good_id, function($query) use($good_id){
                $query->where('good_id', $good_id);
            })
            //审核状态
            ->when($audit_status, function($query) use ($audit_status){
                if($audit_status == self::NO_AUDIT){
                    $query->whereNull('audited_at');
                }else if($audit_status == self::AUDIT_PASSWED){
                    $query->whereNotNull('audited_at');
                }
            })
            //评价类型
            ->when($type_id, function($query) use($type_id){
                $query->where('type_id', $type_id);
            })
            ->orderBy('id','desc')
            ->paginate($per_page);

        return [$search, $data];
    }

    //隐藏手机号显示
    public function getPhoneAttribute($value){
       return hidden_mobile($value);
    }

    public function getShowPhoneAttribute(){
        return $this->attributes['show_phone'] = $this->attributes['phone'];
    }

}
