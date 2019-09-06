<?php
/**
 * @订单配置
*/

use \App\Models\GoodOrder;

return [

    'status' => [
        GoodOrder::NOT_AUDIT_TYPE => '未审核',
        GoodOrder::AUDIT_PASSED_TYPE => '审核通过',
        GoodOrder::AUDIT_REFUSED_TYPE => '审核拒绝'
    ],

    'pay_types' => [
        1 => '货到付款',
        2 => '在线支付',
    ],

    'search_items' => [
        GoodOrder::SEARCH_ITEM_ORDER_SN_CODE => GoodOrder::SEARCH_ITEM_ORDER_SN,
        GoodOrder::SEARCH_ITEM_GOOD_NAME_CODE => GoodOrder::SEARCH_ITEM_GOOD_NAME,
        GoodOrder::SEARCH_ITEM_SKUID_CODE => GoodOrder::SEARCH_ITEM_SKUID
    ],

    'date_search_items' => [
        GoodOrder::ORDER_DATE_SEARCH_ITEM_CODE => GoodOrder::ORDER_DATE_SEARCH_ITEM,
        GoodOrder::AUDIT_DATE_SEARCH_ITEM_CODE => GoodOrder::AUDIT_DATE_SEARCH_ITEM,
    ],
];




?>