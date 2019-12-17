<?php

namespace App\Models;

use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use itbdw\Ip\IpLocation;

class GoodOrder extends Model
{
    protected $table = 'good_orders';

    use SoftDeletes;

    protected $page_size = 20;

    protected $fillable = [
        'sn',
        'ip',
        'price',
        'total_off',
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
        'coupon_code_id',

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

    public function coupon_code(){
        return $this->belongsTo(CouponCode::class);
    }

    /**
     * 列表数据
     * @param $request
     * @return array
     */
    public function get_data($request){

        $filter_keywords = $request->get('filter_keywords');

        $base_query =  GoodOrder::with(['order_skus','admin_user', 'coupon_code', 'audit_logs' => function($query){
            $query->orderBy('created_at', 'desc');
        }])->filterKeywords($filter_keywords);

        list($query, $search) = $this->query_conditions($base_query, $request);

        $search = collect($search)->put('filter_keywords', $filter_keywords);

        $data = $query->select(
            'good_orders.*'
        )
            ->orderBy('good_orders.id', 'desc')
            ->paginate($this->page_size);

        return [$data, $search->all()];
    }

    public function get_api_data($request){

        $base_query =  GoodOrder::with(['order_skus']);

        $today = Carbon::today();

        $pre_day = Carbon::today()->subDays(3);

        // dd($today, $pre_day);

        $base_query->whereBetween('created_at', [$pre_day, $today->endOfDay() ]);

        $data = $base_query->select(
            'good_orders.*'
        )
            ->orderBy('good_orders.id', 'desc')
            ->get();

        return $data;
    }

    /**
     * 模糊搜索
     */
    public function scopeFilterKeywords($query, $filter_keywords){
        if($filter_keywords){
            return $query->where(function($sub_query) use ($filter_keywords){
                $sub_query->where('receiver_name', 'like', '%'. $filter_keywords. '%')
                    ->orWhere('receiver_phone', 'like', '%'. $filter_keywords. '%');
            });
        }else{
            return $query;
        }
    }

    /**
     * 条件筛选
     * @param $request
     * @param $base_query
     */
    protected function query_conditions($base_query, $request){

        //默认30天数据
//        list($start_date, $end_date) = recent_thirty_days();

        //时间筛选项
        $date_search_item = $request->post('date_search_item');
        //筛选时间
        $start_date = $request->get('start_date') ?: '';
        $end_date = $request->get('end_date') ?: '';

        if($start_date && $end_date){
            if($date_search_item == self::AUDIT_DATE_SEARCH_ITEM_CODE){
                $base_query->whereBetween('good_orders.last_audited_at', [$start_date, $end_date]);
            }else{
                $base_query->whereBetween('good_orders.created_at', [$start_date, $end_date]);
            }
        }

        //审核状态筛选
        $status = $request->get('status');
        if(!is_null($status)){
            $base_query->where('good_orders.status', $status);
        }

         //ID筛选
         $search_id = $request->get('search_id');
         if($search_id){
             $base_query->where('good_orders.id', $search_id);
         }

          //国家筛选
          $country_id = $request->get('country_id');
          if($country_id){
              $base_query->where('good_orders.country_id', $country_id);
          }


        //筛选过滤选中的id
        $filter_order_ids = $request->get('filter_order_ids');
        if($filter_order_ids){
            $base_query->whereIn('good_orders.id', explode(',', $filter_order_ids));
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


        $search = compact('start_date','end_date','status','keywords','per_page','search_item','date_search_item','search_id', 'country_id');

        return [$base_query, $search];
    }

    public function export($request){

        $filter_keywords = $request->get('filter_keywords');

        $base_query =  GoodOrder::with(['order_skus'])->filterKeywords($filter_keywords);

        list($query, $search) = $this->query_conditions($base_query, $request);

        $orders = $query->select(
            'good_orders.id',
            'good_orders.created_at',
            'good_orders.last_audited_at',
            'good_orders.sn',
            'good_orders.receiver_name',
            'good_orders.postcode',
            'good_orders.receiver_phone',
            'good_orders.province',
            'good_orders.city',
            'good_orders.area',
            'good_orders.short_address',
             DB::raw('(price - total_off) as price'),
            'good_orders.status',
            'good_orders.remark'

        )
            ->orderBy('good_orders.id', 'desc')
            ->get();

        $pay_types = config('order.pay_types');
        $status = config('order.status');

        foreach ($orders as $order){
            $order->status_name = array_get($status, $order->status, '');
            $order->currency_code = config('money_sign','');
            $order->country_name = config('global_area','');
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

    /**
     * @return string
     */
    public function getIpCountryAttribute(){
        $ip = long2ip($this->attributes['ip']);
        $ip_info = IpLocation::getLocation($ip);
        if(isset($ip_info['error'])){
            return '(' .$ip_info['error'] . ')';
        }
        $country = $ip_info['country'] ?? '';
        return $country ? '('.$country . ')' : '';
    }

//    public function getPriceAttribute($value){
//        return config('money_sign').$value;
//    }
}
