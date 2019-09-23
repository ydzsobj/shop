<?php
use App\Models\CouponCode;
return [


    //优惠类型
    'type_list' => [
        1 => '折扣百分比',
        2 => '固定金额',
        3 => '满减'
    ],

    //
    'apply_type_list' => [
        1 => '单品',
//        2 => '订单'
    ],

    'status' => [
        CouponCode::STATUS_NO_START => '未生效',
        CouponCode::STATUS_RUNNING => '生效中',
        CouponCode::STATUS_FINISHED => '已失效',
    ],
];




?>