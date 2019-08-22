<?php

namespace App\Admin\Controllers;

use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductAttributeValueController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_show_name(Request $request, $id){

        $attr_value =  ProductAttributeValue::find($id);

        $show_name = $request->post('value');

        $attr_value->show_name = $show_name;

        $res = $attr_value->save();

        $msg = $res ? trans('common.update.success') : trans('common.update.success');

        return returned($res, $msg);
    }
}
