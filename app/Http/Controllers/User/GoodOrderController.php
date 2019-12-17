<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CheckCouponCode;
use App\Http\Requests\StoreGoodOrder;
use App\Models\CouponCode;
use App\Models\GoodOrder;
use App\Models\GoodOrderSku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\sendSms;
use App\Models\ServicePhone;

class GoodOrderController extends Controller
{

    /**
     * @api {post} /api/user/good_orders  1.2 提交订单
     * @apiName postGoodOrder
     * @apiGroup User
     *
     * @apiParam {Array} cart_data 购物车数据
     * @apiParam {string} receiver_name 收货人姓名
     * @apiParam {string} receiver_phone 收货人电话
     * @apiParam {string} [receiver_email] 收货人邮件
     * @apiParam {string} address 收货地址
     * @apiParam {string} short_address 短地址
     * @apiParam {string} [leave_word] 留言
     * @apiParam {string} [postcode] 邮编
     * @apiParam {string} [coupon_code_id] 优惠码id
     * @apiParam {Number} country_id 国家ID
     * @apiParam {string} [total_off] 计算优惠后的价格
     *
     * @apiParamExample {json} Request-Example:
     *{
     *  "cart_data" : [
     *  	{"sku_id" : 1001,"price" : 99,"sku_nums" : 1,"good_id" : 24},
     *  	{"sku_id" : 1002,"price" : 99,"sku_nums" : 2,"good_id" : 24},
     *  	{"sku_id" : 2001,"price" : 199,"sku_nums" : 1,"good_id" : 25}
     *  	],
     *  	"receiver_name" : "sky",
     *  	"receiver_phone" : "19918112221",
     *  	"receiver_email" : "qqqqqq8888@gmail.com",
     *  	"address" : "北京海淀区上地三街",
     *  	"short_address":"清河大街888号小米大厦",
     *  	"leave_word":"尽快发货~~~~~",
     *  	"postcode":"470000",
     *  	"coupon_code_id": 15,
     *  	"country_id": 1,
     *  	"total_off": 999000,
     *}
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *{
     *    "success": true,
     *    "msg": "提交订单成功",
     *    "data": {
     *        "price": "$200.89",
     *        "ip": "192.168.1.133",
     *        "sn": "2019080797505710",
     *        "receiver_name": "sky",
     *        "receiver_phone": "19918112221",
     *        "receiver_email": "qqqqqq8888@gmail.com",
     *        "address": "北京海淀区上地三街",
     *        "short_address": "清河大街888号小米大厦",
     *        "leave_word": "尽快发货~~~~~",
     *        "updated_at": "2019-08-07 12:55:54",
     *        "created_at": "2019-08-07 12:55:54",
     *        "id": 18,
     *  	  "coupon_code_id": 15
     *    }
     *}
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *   {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "cart_data": [
     *            "购物车数据不能为空"
     *        ],
     *        "address": [
     *            "收货地址不能为空"
     *        ]
     *    },
     *    "status_code": 422
     *}
     */
    public function store(StoreGoodOrder $request){

        $ip = ip2long($request->ip());

        $cart_data = $request->post('cart_data');

        $cart_data = collect(
           $cart_data
        );

//        dd($cart_data);

        $address = [
            'receiver_name' => $request->post('receiver_name'),
            'receiver_phone' => $request->post('receiver_phone'),
            'receiver_email' => $request->post('receiver_email'),
            'address' => $request->post('address'),
            'short_address' => $request->post('short_address'),
            'leave_word' => $request->post('leave_word'),
            'postcode' => $request->post('postcode'),
        ];

        $cart_data = $cart_data->map(function($item){
            $item['price'] = round($item['price']/100,2);
            return $item;
        });

        $skus_price = $cart_data->map(function($item){
            return $item['price'] * $item['sku_nums'];
        });

        //省市区拆分
        if(strpos($request->post('address'), '/') !== false){
            list($province, $city, $area) = explode('/', $request->post('address'));
        }

        //优惠
        $total_off = $request->post('total_off');
        $total_off = $total_off ? round($total_off/100, 2) : 0;

        $insert_data = [
            'price' => $skus_price->sum() - $total_off,
            'total_off' => $total_off,
            'ip' => $ip,
            'sn' => generate_sn(),
            'coupon_code_id' => $request->post('coupon_code_id'),
            'country_id' => $request->post('country_id'),
        ];

        $go = GoodOrder::create(array_merge($insert_data, $address, compact('province', 'city', 'area')));

        if($go){

            //发送短信
            if(ServicePhone::check_available() >0){
                // sendSms::dispatch($go)->onQueue('sms');
            }

            $go->order_skus()->createMany($cart_data->all());
            return returned(true, '提交订单成功', $go);
        }else{
            return returned(false, '提交订单失败');
        }
    }

    /**
     * @api {get} /api/user/order_notice  1.8 获取下单数据
     * @apiName getOrderNotice
     * @apiGroup User
     *
     * @apiParam {string} receiver_name 收货人姓名
     * @apiParam {float} price 单价
     * @apiParam {Number} sku_nums 单品数量
     * @apiParam {string} title 商品展示名称
     * @apiParam {datetime} created_at 下单时间
     *
     * @apiParamExample {json} Request-Example:
     *    "success": true,
     *    "msg": "",
     *    "data": [
     *        {
     *            "sku_nums": 1,
     *            "price": "1.00",
     *            "title": "new-1603",
     *            "receiver_name": "skyfee",
     *            "created_at": "2019-09-04 08:49:31"
     *        },
     *        {
     *            "sku_nums": 1,
     *            "price": "18.00",
     *            "title": "111",
     *            "receiver_name": "测试",
     *            "created_at": "2019-09-04 07:48:22"
     *        },
     *        {
     *            "sku_nums": 1,
     *            "price": "1.00",
     *            "title": "111",
     *            "receiver_name": null,
     *            "created_at": null
     *        }
     *    ]
     *}
     *
     */
    public function order_notice(Request $request){

         $data = GoodOrderSku::leftJoin('good_orders','good_orders.id','good_order_skus.good_order_id')
            ->leftJoin('goods','goods.id','good_order_skus.good_id')
            ->whereNull('good_orders.deleted_at')
             //审核通过的
            ->where('good_orders.status', GoodOrder::AUDIT_PASSED_TYPE)
            ->select(
                'good_order_skus.sku_nums',
                'good_order_skus.price',
                'goods.title',
                'good_orders.receiver_name',
                'good_orders.created_at'
            )
            ->orderBy('good_order_skus.id', 'desc')
            ->take(3)
            ->get();

         return returned(true,'', $data);
    }

    /**
     * @api {post} /api/user/check_coupon_code  2.0 验证优惠码
     * @apiName check_coupon_code
     * @apiGroup User
     *
     * @apiParam {Array} cart_data 购物车数据
     * @apiParam {string} coupon_code 优惠码
     *
     * @apiParamExample {json} Request-Example:
     *{
     *  "cart_data" : [
     *  	{"sku_id" : 1001,"price" : 99,"sku_nums" : 1,"good_id" : 24},
     *  	{"sku_id" : 1002,"price" : 99,"sku_nums" : 2,"good_id" : 24},
     *  	{"sku_id" : 2001,"price" : 199,"sku_nums" : 1,"good_id" : 25}
     *  	],
     *  "coupon_code" : "BQM1RS4I",
     *}
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *{
     *    "success": true,
     *    "msg": "优惠码有效",
     *    "data": {
     *        "detail": [
     *            {
     *                "sku_id": 665032028,
     *                "price": 900,
     *                "sku_nums": 2,
     *                "good_id": 111,
     *                "off": 30,
     *                "msg": "参与活动满减;购买数量满2件, 减去金额30.00"
     *            },
     *            {
     *                "sku_id": 1002,
     *                "price": 9900,
     *                "sku_nums": 2,
     *                "good_id": 24,
     *                "off": 0,
     *                "msg": "优惠码不适用该商品"
     *            },
     *            {
     *                "sku_id": 2001,
     *                "price": 199,
     *                "sku_nums": 1,
     *                "good_id": 25,
     *                "off": 0,
     *                "msg": "优惠码不适用该商品"
     *            }
     *        ],
     *
     *        "total_off": 30,//一共减去的金额
     *        "coupon_code_id": 15 //优惠码id,提交订单时如果有需要加上
     *    }
     *}
     */
    public function check_coupon_code(CheckCouponCode $request){

        $coupon_code = $request->post('coupon_code');

        $cart_data = collect($request->post('cart_data'));

        $cop = new CouponCode();
        $code = $cop->by_code($coupon_code);

        if(!$code){
            return returned(false,'优惠码无效');
        }

        //区分适用类型
        switch ($code->apply_type_id){

            //单个商品
            case CouponCode::APPLY_TYPE_GOOD:

                $cart_data = $cart_data->groupBy('good_id');

                $detail = collect([]);

                $cart_data->map(function($item,$good_id) use ($code,$detail){
                    list($success, $msg) = $code->count_good_price($good_id, $item);
                    if($success){
                        $off = $success;
                    }else{
                        $off = 0;
                    }
                    return $detail->push(['off' => $off, 'msg' => $msg ,'good_id' => $good_id]);
                });

                $total_off = $detail->sum('off');

                break;

            //订单
            case CouponCode::APPLY_TYPE_ORDER:
                list($success, $msg) = $code->count_order_price($cart_data);
                if($success){
                    $total_off = $success;
                    $desc = $msg;
                }else{
                    $desc = $msg;
                    $total_off = 0;
                }
                break;

            default:
                return returned(false,'优惠码类型不符合条件');
        }

        $coupon_code_id = $code->id;

        $total_off = round($total_off, 2);

        return returned(true, '优惠码有效',
            compact(
                'total_off',
                'desc',
                'coupon_code_id',
                'detail'
            )
        );

    }

    //开放订单信息api
    public function order_info(Request $request){

        $go = new GoodOrder();

        $data = $go->get_api_data($request);

        return returned(true, 'ok', $data);
    }

}
