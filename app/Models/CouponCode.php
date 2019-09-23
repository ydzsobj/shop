<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CouponCode extends Model
{
    use SoftDeletes;

    protected $table = 'coupon_codes';

    protected $page_size = 20;

    /**
     * 百分比
     */
    const TYPE_PERCENT = 1;
    /**
     * 固定金额
     */
    const TYPE_FIXED = 2;
    /**
     * 满减
     */
    const TYPE_FULL_REDUCTION = 3;

    /**
     * 未开始
     */
    const STATUS_NO_START = 1;
    /**
     * 生效中
     */
    const STATUS_RUNNING = 2;
    /**
     * 已结束（失效）
     */
    const STATUS_FINISHED = 3;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'type_id',
        'apply_type_id',
        'good_id',
        'min_money',
        'min_mount',
        'start_date',
        'end_date',
        'targetable_type',
        'targetable_id',
        'admin_user_id',
        'status'

    ];

    public function targetable(){
        return $this->morphTo();
    }

    /**
     * @return $this
     */
    public function admin_user(){
        return $this->belongsTo(AdminUser::class)->withDefault();
    }

    /**
     * @return $this
     */
    public function good(){
        return $this->belongsTo(Good::class)->withDefault();
    }

    public function get_date($request){

        $base_query = self::with('admin_user', 'good');

        list($base_query, $search) = $this->query_conditions($base_query,$request);

        $data = $base_query->select('coupon_codes.*')
            ->orderBy('id', 'desc')
            ->paginate($this->page_size);

        return [$search, $data];
    }

    /**
     * 条件筛选
     * @param $request
     * @param $base_query
     */
    protected function query_conditions($base_query, $request){

        //类型
        $type_id = $request->get('type_id');
        if($type_id){
            $base_query->where('type_id', $type_id);
        }

        //状态
        $status = $request->get('status');
        if($status){
            $base_query->where('status', $status);
        }

        //单品类型
        $good_id = $request->get('good_id');
        if($good_id){
            $base_query->where('good_id', $good_id);
        }

        //分页大小
        $per_page = $request->get('per_page') ?: $this->page_size;
        $this->page_size = $per_page;

        $search = compact('per_page','type_id', 'good_id', 'status');

        return [$base_query, $search];
    }
}
