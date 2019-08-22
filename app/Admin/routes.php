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
    $router->post('/goods/{id}/copy', 'GoodController@copy')->name('goods.copy');
    //商品导出
    $router->get('/goods/export', 'GoodController@export')->name('goods.export');
    //更新sku价格
    $router->put('/good_skus/{id}/update_price','GoodSkuController@update_price');
    //sku启用禁用
    $router->put('/good_skus/update_disabled_at', 'GoodSkuController@update_disabled_at');

    //订单管理
    $router->resource('/good_orders', 'GoodOrderController')->except(['store','show']);
    //审核订单
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
    $router->resource('/products', 'ProductController')->only(['index']);
    //设置产品属性别名
    $router->put('/product_attributes/{id}/update_show_name', 'ProductAttributeController@update_show_name');
    //设置产品属性值别名
    $router->put('/product_attribute_values/{id}/update_show_name', 'ProductAttributeValueController@update_show_name');



});
