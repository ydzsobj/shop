<?php

namespace App\Admin\Controllers;

use App\Models\UserTraceLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTraceLogController extends Controller
{

    /**
     * @ 轨迹列表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $tl = new UserTraceLog();

        list($user_trace_logs, $search) = $tl->get_data($request);

        return view('admin.user_trace_log.index', compact('user_trace_logs','search'));
    }
}
