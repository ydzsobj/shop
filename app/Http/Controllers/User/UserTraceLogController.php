<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreTraceLogRequest;
use App\Models\UserTraceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use itbdw\Ip\IpLocation;
use App\Jobs\SaveTraceLog;

class UserTraceLogController extends Controller
{

    /**
     * @api {post} /api/user/trace_logs  1.9 保存用户行为轨迹
     * @apiName post_trace_logs
     * @apiGroup User
     *
     * @apiParam {String} device 用户设备信息
     * @apiParam {String} [lang] 设备语言
     * @apiParam {String} referer_url 来源地址
     * @apiParam {String} access_url 访问地址
     * @apiParam {Number} [good_id] 商品ID
     *
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *{
     *    "success": true,
     *    "msg": "添加成功",
     *    "data": []
     *}
     */
    public function store(StoreTraceLogRequest $request){

        $req = $request->only('device','lang', 'referer_url','access_url', 'good_id');

        $req['access_time'] = Carbon::now();

        $ip = $request->getClientIp();

        $req['ip'] = ip2long($ip);

        //加入队列
        SaveTraceLog::dispatch($req,$ip)->delay(Carbon::now()->addSeconds(10));

    }
}
