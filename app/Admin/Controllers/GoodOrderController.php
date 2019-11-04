<?php

namespace App\Admin\Controllers;

use App\Events\AuditOrderSuccessEvent;
use App\Exports\GoodOrdersExport;
use App\Models\Good;
use App\Models\GoodAuditLog;
use App\Models\GoodOrder;
use App\Models\GoodOrderSku;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodSku;
use Auth;
use Excel;

class GoodOrderController extends BaseController
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

        //时间搜索项
        $date_search_items = config('order.date_search_items');

        $group_orders = $orders->groupBy('receiver_phone');

        return view('admin.good_order.index', compact('orders', 'search', 'status','search_items','date_search_items','group_orders'));
    }


    /**
     * @param Request $request
     * @param $good_order_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_audit(Request $request, $good_order_id){
        //状态
        $status = config('order.status');

        return view('admin.good_order.create_audit', compact('good_order_id','status'));
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

        $res = $good_order->audit($status);

        if($res) {
            //记录审核日志
            event(new AuditOrderSuccessEvent($good_order, $remark));

            $msg = trans('order.audit.success');
        }else{
            $msg = trans('order.audit.fail');
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

        $detail = GoodOrder::with(['order_skus'])->where('id',$id)->first();

        //获取所有产品下的sku
        $order_skus = $detail->order_skus;

        $order_skus = $order_skus->map(function($order_sku){
            $sku = ($order_sku->sku_info);
            $order_sku->sku_list = $sku->product_skus($order_sku->good_id);
            return $order_sku;
        });

        // dd($order_skus);

        $pay_types = config('order.pay_types');
        $status = config('order.status');

        return view('admin.good_order.edit', compact('detail','pay_types','status', 'order_skus'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id){

        $rq = $request->only('receiver_name','receiver_phone' ,'short_address','province', 'city', 'area','postcode');

        $sku_attr_values = $request->post('sku_attr_values');

        $go = GoodOrder::find($id);
        if(!$go){
            return redirect()->route('good_orders.index')->with('error',trans('order.not_exist'));
        }

        $rq['address'] = $rq['province'] . '/' .$rq['city']. '/'. $rq['area'];

        $res = GoodOrder::where('id',$id)->update($rq);

        if($res && $sku_attr_values){
            foreach($sku_attr_values as $id=>$sku_id){
                GoodOrderSku::where('id', $id)->update(['sku_id' => $sku_id]);
            }
        }
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

    /**
     * 批量审核
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function batch_audit(Request $request){

        $order_ids = $request->post('order_ids');

        $order_ids = explode(',', $order_ids);

        $status = $request->post('status');
        $remark = $request->post('remark');

        $res = GoodOrder::whereIn('id', $order_ids)->update([
            'status' => $status,
            'last_audited_at' => Carbon::now(),
            'last_audited_admin_user_id' => Admin::user()->id
        ]);

        if($res){
            //批量增加审核日志
            $insert_data = collect([]);
            foreach ($order_ids as $order_id){
                $data = [
                    'good_order_id' => $order_id,
                    'status' => $status,
                    'admin_user_id' => Admin::user()->id,
                    'remark' => $remark,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $insert_data->push($data);
            }

            GoodAuditLog::insert($insert_data->all());

        }

        $msg = $res ? trans('order.audit.success') : trans('order.audit.fail');

        $alert_type = $res ? 'success' : 'error';

        return redirect()->route('good_orders.index')->with($alert_type, $msg);

    }

    //批量删除
    public function batch_destroy(Request $request){

        $order_ids = $request->post('order_ids');

        $res = GoodOrder::whereIn('id',$order_ids)->delete();

        $msg = $res ? trans('order.delete.success') : trans('order.delete.fail');

        return returned($res, $msg);
    }

}
