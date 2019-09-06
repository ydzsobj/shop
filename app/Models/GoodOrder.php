<?php

namespace App\Models;

use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodOrder extends Model
{
    protected $table = 'good_orders';

    use SoftDeletes;

    protected $page_size = 20;

    protected $fillable = [
        'sn',
        'ip',
        'price',
        'status',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'address',
        'short_address',
        'leave_word',
        'remark',
        'province',
        'city',
        'area',
        'postcode',

    ];

    /**
     * 未审核
     */
    const NOT_AUDIT_TYPE = 0;

    /**
     * @审核通过
     */
    const AUDIT_PASSED_TYPE = 1;
    /**
     * @审核拒绝
     */
    const AUDIT_REFUSED_TYPE = 2;

    /**
     * @搜索条件
     */
    const SEARCH_ITEM_ORDER_SN_CODE = 'order_sn';
    const SEARCH_ITEM_ORDER_SN = '订单号';

    const SEARCH_ITEM_GOOD_NAME_CODE = 'good_name';
    const SEARCH_ITEM_GOOD_NAME = '单品名';

    const SEARCH_ITEM_SKUID_CODE = 'sku_id';
    const SEARCH_ITEM_SKUID = 'SKUID';

    const ORDER_DATE_SEARCH_ITEM_CODE = 'order';
    const ORDER_DATE_SEARCH_ITEM = '下单时间';

    const AUDIT_DATE_SEARCH_ITEM_CODE = 'audit';
    const AUDIT_DATE_SEARCH_ITEM = '审核时间';


    public function order_skus(){
        return $this->hasMany(GoodOrderSku::class);
    }

    public function admin_user(){
        return $this->belongsTo(AdminUser::class, 'last_audited_admin_user_id', 'id');
    }

    public function audit_logs(){
        return $this->hasMany(GoodAuditLog::class);
    }

    /**
     * 列表数据
     * @param $request
     * @return array
     */
    public function get_data($request){

        $base_query =  GoodOrder::with(['order_skus','admin_user','audit_logs' => function($query){
            $query->orderBy('created_at', 'desc');
        }]);

        list($query, $search) = $this->query_conditions($base_query, $request);

        $data = $query->select(
            'good_orders.*'
        )
            ->orderBy('good_orders.id', 'desc')
            ->paginate($this->page_size);

        return [$data, $search];
    }

    /**
     * 条件筛选
     * @param $request
     * @param $base_query
     */
    protected function query_conditions($base_query, $request){

        //默认30天数据
        list($start_date, $end_date) = recent_thirty_days();

        //时间筛选项
        $date_search_item = $request->post('date_search_item');
        //筛选时间
        $start_date = $request->get('start_date') ?: $start_date;
        $end_date = $request->get('end_date') ?: $end_date;

        if($date_search_item == self::AUDIT_DATE_SEARCH_ITEM_CODE){
            $base_query->whereBetween('good_orders.last_audited_at', [$start_date, Carbon::parse($end_date)->endOfDay()]);
        }else{
            $base_query->whereBetween('good_orders.created_at', [$start_date, Carbon::parse($end_date)->endOfDay()]);
        }

        //审核状态筛选
        $status = $request->get('status');
        if(!is_null($status)){
            $base_query->where('good_orders.status', $status);
        }

        //关键词
        $keywords = $request->get('keywords');
        $search_item = $request->get('search_item');
        if($keywords && $search_item){
            switch ($search_item){
                case self::SEARCH_ITEM_ORDER_SN_CODE:
                    $base_query->where('good_orders.sn', $keywords);
                    break;

                case self::SEARCH_ITEM_GOOD_NAME_CODE://筛选单品名
                    $good = Good::where('name', $keywords)->first();

                    $good_order_ids =  GoodOrderSku::where('good_id',$good ? $good->id:null)
                        ->whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                        ->pluck('good_order_id')
                        ->unique();

                    $base_query->whereIn('good_orders.id', $good_order_ids);

                    break;
                case self::SEARCH_ITEM_SKUID_CODE://筛选skuid
                    $good_order_ids = GoodOrderSku::where('sku_id', $keywords)
                        ->whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                        ->pluck('good_order_id')
                        ->unique();

                    $base_query->whereIn('good_orders.id', $good_order_ids);

                    break;
                default:
                    break;
            }
        }

        //当前权限
        $admin_user = Admin::user();
        if($admin_user->isAdministrator() || $admin_user->isRole('leader') || $admin_user->isRole('customer')){

        }else{
            $good_order_ids = GoodOrderSku::leftJoin('goods', 'goods.id','good_order_skus.good_id')
                ->where('goods.admin_user_id', $admin_user->id)
                ->whereBetween('good_order_skus.created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->pluck('good_order_id')
                ->unique();

            $base_query->whereIn('good_orders.id', $good_order_ids);
        }

        //分页大小
        $per_page = $request->get('per_page') ?: $this->page_size;
        $this->page_size = $per_page;


        $search = compact('start_date','end_date','status','keywords','per_page','search_item','date_search_item');

        return [$base_query, $search];
    }

    public function export($request){

        $base_query =  GoodOrder::with(['order_skus','admin_user']);

        list($query, $search) = $this->query_conditions($base_query, $request);

        $orders = $query->select(
            'good_orders.id',
            'good_orders.sn',
            'good_orders.receiver_name',
            'good_orders.postcode',
            'good_orders.receiver_phone',
            'good_orders.province',
            'good_orders.city',
            'good_orders.area',
            'good_orders.short_address',
            'good_orders.price',

            'good_orders.status',
            'good_orders.pay_type_id'

        )
            ->orderBy('good_orders.id', 'desc')
            ->get();

        $pay_types = config('order.pay_types');
        $status = config('order.status');

        foreach ($orders as $order){
            $order->sn = ' '.$order->sn;
            $order->receiver_phone = ' '.$order->receiver_phone;
            $order_skus = $order->order_skus;
            $sku_str = '';
            $sku_desc_str = '';
            $product_name_str = '';
            $product_english_name_str = '';
            $total_nums = 0;
            foreach ($order_skus as $order_sku){
                $sku = $order_sku->sku_info;
                $sku_str .= "[$sku->sku_id] ".$sku->good->name .' '. $sku->s1_name . $sku->s2_name. $sku->s3_name. ' x'. $order_sku->sku_nums;
                $sku_str .= "\r\n";

                $sku_desc_str .= "[$sku->sku_id] ". $sku->good->title .' '. ProductAttributeValue::get_show_name([$sku->s1,$sku->s2,$sku->s3]). ' x'. $order_sku->sku_nums;
                $sku_desc_str .= "\r\n";

                //产品名称
                $product_name_str .= $sku->good->product->name;
                $product_name_str .= "\r\n";

                $product_english_name_str .= $sku->good->product->english_name;
                $product_english_name_str .= "\r\n";

                $total_nums += $order_sku->sku_nums;
            }
            $order->status_str = array_get($status, $order->status, '');
            $order->pay_type_str = array_get($pay_types, $order->pay_type_id, '');
            $order->product_name_str = $product_name_str;
            $order->product_english_name_str = $product_english_name_str;
            $order->sku_str = $sku_str;
            $order->sku_desc_str = $sku_desc_str;
            $order->total_nums = $total_nums;
            unset(
                $order->id,
                $order->pay_type_id,
                $order->status
            );
        }

//        dd($orders->toArray());

        return $orders;

    }

    /**
     * @审核更新
     * @param $status
     * @param $good_order
     * @return mixed
     */
    public function audit($status){
        $this->status = $status;
        $this->last_audited_at = Carbon::now();
        $this->last_audited_admin_user_id = Admin::user()->id;
        return $this->save();
    }

    /**
     * @param $value
     * @return string
     */
    public function getIpAttribute($value){
        return long2ip($value);
    }

//    public function getPriceAttribute($value){
//        return config('money_sign').$value;
//    }
}
