<?php

namespace App\Admin\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_show_name(Request $request, $id){

       $attr =  ProductAttribute::find($id);

       $show_name = $request->post('value');

       $attr->show_name = $show_name;

       $res = $attr->save();

       $msg = $res ? trans('common.update.success') : trans('common.update.success');

       return returned($res, $msg);
    }
}
