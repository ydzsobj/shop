<?php

namespace App\Admin\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodAttribute;

class GoodAttributeController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_show_name(Request $request, $id){

       $attr =  GoodAttribute::find($id);

       $show_name = $request->post('value');

       $attr->show_name = $show_name;

       $res = $attr->save();

       $msg = $res ? trans('common.update.success') : trans('common.update.success');

       return returned($res, $msg);
    }
}
