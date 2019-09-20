<?php

namespace App\Admin\Controllers;

use App\Models\GoodSku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodSkuController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_price(Request $request, $id){

        $price = $request->post('value');

        $gs = GoodSku::find($id);

        $gs->price = $price;

        $res = $gs->save();

        $msg = $res ? trans('common.update.success') : trans('common.update.fail');

        return returned($res, $msg);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_disabled_at(Request $request){

        $sku_ids = $request->post('sku_ids');

        $action = $request->post('action');

        switch ($action){
            case 'disable':
                $disabled_at = Carbon::now();
                break;

            case 'enable':
                $disabled_at = null;
                break;

            default:
                return response()->json(['success' => false, 'msg' => '参数不对']);
        }

        $res = GoodSku::whereIn('id',$sku_ids)->update(['disabled_at' => $disabled_at]);

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function batch_update_price(Request $request){

        $sku_ids = $request->post('sku_ids');
        $price = $request->post('price');

        $sku_ids = explode(',', $sku_ids);

        $res = GoodSku::whereIn('id',$sku_ids)->update(['price' => $price]);

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        $alert = $res ? 'success' : 'error';

        return redirect()->route('goods.index')->with($alert, $msg);

    }
}
