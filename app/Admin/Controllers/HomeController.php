<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Good;
use App\Models\GoodOrder;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;

class HomeController extends BaseController
{
    public function index(Content $content)
    {

        $goods_count = Good::count();
        $orders_count = GoodOrder::count();
        $admin_users_count = AdminUser::count();
        return view('admin.index.index',compact('goods_count','orders_count','admin_users_count'));
    }
}
