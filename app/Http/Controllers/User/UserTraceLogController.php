<?php

namespace App\Http\Controllers\User;

use App\Models\UserTraceLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use itbdw\Ip\IpLocation;

class UserTraceLogController extends Controller
{

    /**
     * @api {post} /api/user/trace_logs  1.9 保存用户行为轨迹
     * @apiName post_trace_logs
     * @apiGroup User
     *
     * @apiParam {String} [device] 用户设备信息
     * @apiParam {String} [lang] 设备语言
     * @apiParam {String} [referer_url] 来源地址
     * @apiParam {String} [access_url] 访问地址
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
    public function store(Request $request){

        $req = $request->only('device','lang', 'referer_url','access_url', 'good_id');

        $ip = $request->getClientIp();

        $req['ip'] = ip2long($ip);

        $ip_info = IpLocation::getLocation($ip);

        if($ip_info){
            $country = $ip_info['country'] ?? '';
            $area = $ip_info['area'] ?? '';
            $province = $ip_info['province'] ?? '';
            $city = $ip_info['city'] ?? '';
        }

        $res = UserTraceLog::create(array_merge($req, compact('country','area','province','city')));

        $success = $res ? true : false;

        $msg = $res ? '添加成功': '添加失败';

        return returned($success, $msg);
    }
}
