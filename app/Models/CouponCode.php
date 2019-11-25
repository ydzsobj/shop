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
     * 适用特定商品类型
     */
    const APPLY_TYPE_GOOD = 1;

    /**
     * 适用订单
     */
    const APPLY_TYPE_ORDER = 2;

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

        //适用类型
        $apply_type_id = $request->get('apply_type_id');
        if($apply_type_id){
            $base_query->where('apply_type_id', $apply_type_id);
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

        $search = compact('per_page','type_id', 'good_id', 'status','apply_type_id');

        return [$base_query, $search];
    }

    public function by_code($code){

        $code = strtoupper($code);

        return self::where('code',$code)->where('status', self::STATUS_RUNNING)->first();
    }

    //计算购物车每种商品优惠后的价格
    public function count_good_price($good_id,$items){

        $total_nums = $items->sum('sku_nums');

        $total_price = $items->map(function($item){
           return $item['price'] * $item['sku_nums'];
        })->sum();

        $rule = $this->targetable;

        if($this->good_id == $good_id){
            switch ($this->type_id){

                case self::TYPE_PERCENT:
                    //折扣
                    $after_price = round($total_price * ($rule->percent/100), 2);
                    break;

                case self::TYPE_FIXED:
                    //固定金额
                    $after_price = round($total_price - $total_nums * $rule->money * 100, 2);
                    break;

                case self::TYPE_FULL_REDUCTION:
                    //满减
                    if($total_nums >= $rule->amount || count($items) >= $rule->amount){
                        $after_price = round($total_price - $rule->money*100, 2);
                    }else{
                        return [false, '数量不够，享受不了优惠'];
                    }
                    break;

                default:
                    return [false, '找不到优惠类型'];
            }
            return [$total_price - $after_price, self::formart_type_info($this->type_id, $rule) ];

        }else{
            return [false, '优惠码不适用该商品'];
        }

    }

    //计算订单优惠金额
    public function count_order_price($cart_data){

        $total_nums = $cart_data->sum('sku_nums');
        $total_price = $cart_data->map(function($item){
            return $item['price'] * $item['sku_nums'];
        })->sum();

        $rule = $this->targetable;

        switch ($this->type_id){

            case self::TYPE_PERCENT:
                //折扣
                $after_price = round($total_price * ($rule->percent/100), 2);
                break;

            case self::TYPE_FIXED:
                //固定金额
                $after_price = round($total_price - $rule->money*100, 2);
                break;

            case self::TYPE_FULL_REDUCTION:
                //满减
                if($total_nums >= $rule->amount){
                    $after_price = round($total_price - $rule->money*100, 2);
                }else{
                    return [false, '数量不够，享受不了优惠'];
                }
                break;

            default:
                return [false, '找不到优惠类型'];
        }
        return [$total_price - $after_price, self::formart_type_info($this->type_id, $rule) ];

    }

    /**
     * @param $type_id
     * @param $rule
     * @return string
     */
    static public function formart_type_info($type_id, $rule){

        $type_list = config('coupon.type_list');

        $type_name = array_get($type_list, $type_id);

        if($type_id == self::TYPE_PERCENT){
            $msg = ' 折扣'.$rule->percent. '%';
        }else if($type_id == self::TYPE_FIXED){
            $msg = ' 减去'.$rule->money;
        }else if($type_id == self::TYPE_FULL_REDUCTION){
            $msg = ' 购买数量满'.$rule->amount.'件, 减去'.$rule->money;
        }

        return $type_name.';'. $msg;
    }

}
