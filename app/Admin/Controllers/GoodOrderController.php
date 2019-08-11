<?php

namespace App\Admin\Controllers;

use App\Events\AuditOrderSuccessEvent;
use App\Exports\GoodOrdersExport;
use App\Models\Good;
use App\Models\GoodOrder;
use App\Models\GoodOrderSku;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;

class GoodOrderController extends Controller
{

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        //列表
        $go = new GoodOrder();
        list($orders, $search) = $go->get_data($request);

        //状态
        $status = config('order.status');

        //搜索项
        $search_items = config('order.search_items');

        return view('admin.good_order.index', compact('orders', 'search', 'status','search_items'));
    }

    /**
     * 审核订单
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function audit(Request $request, $id){

        $good_order = GoodOrder::find($id);

        if(!$good_order){
            return redirect()->route('good_orders.index')->with('error', trans('order.not_exist'));
        }

        $status = $request->post('status');
        $remark = $request->post('remark');

        $good_order->status = $status;
        $good_order->last_audited_at = Carbon::now();
        $good_order->last_audited_admin_user_id = Admin::user()->id;
        $res = $good_order->save();

        if($res) {
            //记录审核日志
            event(new AuditOrderSuccessEvent($good_order, $remark));

            $msg = '审核成功';
        }else{
            $msg = '审核失败';
        }

        $alert_type = $res ? 'success' : 'error';

        return redirect()->route('good_orders.index')->with($alert_type, $msg);

    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){

        $detail = GoodOrder::find($id);

        $order_skus = $detail->order_skus;

        $pay_types = config('order.pay_types');
        $status = config('order.status');

        $total_price = $order_skus->map(function($item){
            return $item->price * $item->sku_nums;
        })->sum();

        $total_price = config('money_sign').$total_price;

        return view('admin.good_order.edit', compact('detail','total_price','pay_types','status'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id){

        $rq = $request->only('receiver_name','receiver_phone','address','short_address');

        $go = GoodOrder::find($id);
        if(!$go){
            return redirect()->route('good_order.index')->with('error',trans('order.not_exist'));
        }

        $res = GoodOrder::where('id',$id)->update($rq);
        $msg = $res ? trans('common.update.success') : trans('common.update.fail');
        $alert_type = $res ? 'success':'error';

        return redirect()->route('good_orders.index')->with($alert_type, $msg);
    }

    /**
     * 删除 禁用
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){

        $go = GoodOrder::find($id);
        if(!$go){
            return returned(false, trans('order.not_exist'));
        }

        $res = $go->delete();
        $msg = $res ? trans('common.delete.success') : trans('common.delete.fail');

        return returned($res, $msg);

    }

    /**
     * 加客服备注
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_remark(Request $request, $id){

        $remark = $request->post('value');
        if(!$remark){
            return returned(false,trans('order.remark.required'));
        }

        $go = GoodOrder::find($id);
        if(!$go){
            return returned(false, trans('order.not_exist'));
        }

        $go->remark = $remark;
        $res = $go->save();

        $msg = $res ? trans('common.update.success') : trans('common.update.fail');

        return returned($res, $msg);

    }

    public function export(Request $request){

        $go = new GoodOrder();
        $data = $go->export($request);
        return Excel::download(new GoodOrdersExport($data), '订单导出'.date('y-m-d H_i_s').'.xlsx');
    }

}
