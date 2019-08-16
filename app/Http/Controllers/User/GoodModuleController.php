<?php

namespace App\Http\Controllers\User;

use App\Models\GoodModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodModuleController extends Controller
{

    /**
     * @api {get} /api/user/good_modules  1.5 模块列表
     * @apiName GetGoodModules
     * @apiGroup User
     *
     *
     * @apiSuccess {Array} data 列表数据
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *    "success": true,
     *    "msg": "",
     *    "data": [
     *        {
     *            "good_module_id": 4,
     *            "name": "秒杀促销"
     *        },
     *        {
     *            "good_module_id": 1,
     *            "name": "好货推荐"
     *        },
     *        {
     *            "good_module_id": 2,
     *            "name": "热卖商品"
     *        }
     *    ]
     *}
     */
    public function index(Request $request)
    {
        $good_modules = GoodModule::orderBy('sort', 'desc')->select('id as good_module_id','show_name as name')->get();

        return returned(true,'', $good_modules);
    }
}
