<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreGoodCommentRequest;
use App\Models\GoodComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodCommentController extends Controller
{

    /**
     * @api {post} /api/user/good_comments  1.7 提交商品评价
     * @apiName postGoodComments
     * @apiGroup User
     *
     * @apiParam {Number} good_id 商品id
     * @apiParam {String} comment 评价
     *
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *{
     *    "success": true,
     *    "msg": "",
     *    "data": {
     *        "good_id": "96",
     *        "comment": "测试评价123",
     *        "updated_at": "2019-08-27 09:53:33",
     *        "created_at": "2019-08-27 09:53:33",
     *        "id": 3
     *    }
     *}
     */
    public function store(StoreGoodCommentRequest $request){

        $req = $request->only('good_id','comment');

        $result = GoodComment::create($req);

        $success = $result ? true : false;

        return returned($success, '', $result);
    }
}
