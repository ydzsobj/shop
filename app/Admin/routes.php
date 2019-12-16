<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    //首页统计
    $router->get('/', 'HomeController@index')->name('admin.home');
    //文件上传
    $router->resource('/upload', 'AttachmentController',['only' => ['store', 'destroy']]);

    //商品管理
    $router->resource('/goods','GoodController')->except(['show']);
    //复制商品
    $router->get('/goods/{id}/create_copy', 'GoodController@create_copy')->name('goods.create_copy');
    $router->post('/goods/{id}/store_copy', 'GoodController@store_copy')->name('goods.store_copy');
    //sku配置
    $router->get('/goods/{id}/edit_sku','GoodController@edit_sku')->name('goods.edit_sku');
    //属性配置
    $router->get('/goods/{id}/edit_attr', 'GoodController@edit_attr')->name('goods.edit_attr');
    //商品导出
    $router->get('/goods/export', 'GoodController@export')->name('goods.export');
    //更新sku价格
    $router->put('/good_skus/{id}/update_price','GoodSkuController@update_price');
    //批量更新价格
    $router->put('/good_skus/batch_update_price','GoodSkuController@batch_update_price')->name('good_skus.batch_update_price');
    //sku启用禁用
    $router->put('/good_skus/update_disabled_at', 'GoodSkuController@update_disabled_at');

    //订单管理
    $router->resource('/good_orders', 'GoodOrderController')->except(['store','show']);
    //审核订单
    $router->get('/good_orders/{id}/create_audit','GoodOrderController@create_audit')->name('good_orders.create_audit');
    $router->put('/good_orders/{id}/audit','GoodOrderController@audit')->name('good_orders.audit');
    //加客服备注
    $router->put('good_orders/{id}/update_remark', 'GoodOrderController@update_remark');
    //订单导出
    $router->get('/good_orders/export', 'GoodOrderController@export')->name('good_orders.export');
    //批量审核
    $router->post('/good_orders/batch_audit', 'GoodOrderController@batch_audit')->name('good_orders.batch_audit');
    //批量删除
    $router->post('/good_orders/batch_destroy', 'GoodOrderController@batch_destroy')->name('good_orders.batch_delete');

    //类别管理
    $router->resource('/categories','CategoryController');
    //模块管理
    $router->resource('/good_modules','GoodModuleController');
    //轮播图管理
    $router->resource('/slides','SlideController')->except(['index']);

    //搜索商品
    $router->get('/search_goods', 'GoodController@search');

    //产品库
    $router->resource('/products', 'ProductController');
    //设置产品属性别名
    // $router->put('/product_attributes/{id}/update_show_name', 'ProductAttributeController@update_show_name');
    $router->put('/good_attributes/{id}/update_show_name', 'GoodAttributeController@update_show_name');
    //设置产品属性值别名
    // $router->put('/product_attribute_values/{id}/update_show_name', 'ProductAttributeValueController@update_show_name');
    $router->put('/good_attribute_values/{id}/update_show_name', 'GoodAttributeValueController@update_show_name');
    //设置属性值预览图片
    // $router->put('/product_attribute_values/update_thumb_urls', 'ProductAttributeValueController@update_thumb_url')->name('product_attribute_values.update_thumb_url');
    $router->put('/good_attribute_values/update_thumb_urls', 'GoodAttributeValueController@update_thumb_url')->name('good_attribute_values.update_thumb_url');

    //商品评价列表 | 删除
    $router->resource('/good_comments', 'GoodCommentController');
    //审核评价
    $router->put('/good_comments/{id}/update_audited_at', 'GoodCommentController@update_audited_at')
        ->name('good_comments.update_audited_at');
    //新增评价
    $router->get('/goods/{id}/create_comment', 'GoodController@create_comment')->name('goods.create_comment');
    $router->post('/goods/store_comment', 'GoodController@store_comment')->name('goods.store_comment');

    //访问记录
    $router->resource('/user_trace_logs','UserTraceLogController');

    //优惠码管理
    $router->resource('/coupon_codes','CouponCodeController');

    //属性管理
    $router->resource('/attributes','AttributeController');
    //属性值管理
    $router->resource('/attribute_values','AttributeValueController');
    //获取属性下的属性值
    $router->get('/get_attr_values/{id}', 'AttributeController@get_attr_value');

    //获取产品列表
    $router->get('/select_products', 'ProductController@select_products');

    //配置客服电话
    $router->resource('/service_phones', 'ServicePhoneController');



});
