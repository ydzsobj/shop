<?php
use App\Models\CouponCode;
return [


    //优惠类型
    'type_list' => [
        CouponCode::TYPE_PERCENT => '折扣百分比',
        CouponCode::TYPE_FIXED => '固定金额',
        CouponCode::TYPE_FULL_REDUCTION => '满减'
    ],

    //
    'apply_type_list' => [
        CouponCode::APPLY_TYPE_GOOD => '特定商品',
        CouponCode::APPLY_TYPE_ORDER => '订单'
    ],

    'status' => [
        CouponCode::STATUS_NO_START => '未生效',
        CouponCode::STATUS_RUNNING => '生效中',
        CouponCode::STATUS_FINISHED => '已失效',
    ],
];




?>