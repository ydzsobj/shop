<?php

namespace App\Http\Controllers\User;

use App\Models\GoodCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodCategoryController extends Controller
{

     /**
      * @api {get} /api/user/good_categories  1.4 类别列表
      * @apiName GetGoodCategories
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
      *            "category_id": 1,
      *            "name": "数码科技"
      *        },
      *        {
      *            "category_id": 2,
      *            "name": "美妆"
      *        },
      *        {
      *            "category_id": 3,
      *            "name": "电子商品"
      *        }
      *    ]
      *}
      */
    public function index(Request $request)
    {
       $good_categories = GoodCategory::orderBy('sort', 'desc')->select('id as category_id','show_name as name')->get();

       return returned(true,'', $good_categories);
    }
}
