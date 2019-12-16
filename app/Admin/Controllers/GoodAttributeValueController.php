<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodAttributeValue;

class GoodAttributeValueController extends BaseController
{
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_show_name(Request $request, $id){

        $attr_value =  GoodAttributeValue::find($id);

        $show_name = $request->post('value');

        $attr_value->show_name = $show_name;

        $res = $attr_value->save();

        $msg = $res ? trans('common.update.success') : trans('common.update.success');

        return returned($res, $msg);
    }

    public function update_thumb_url(Request $request){

        // dd($request->all());

        $product_id = $request->post('product_id');

        $attr_images = $request->attr_images;

        if($attr_images && count($attr_images) >0){
            foreach($attr_images as $id=>$image){
                $image_url = $this->upload($image);
                if($image_url){
                    GoodAttributeValue::where(['id' => $id])->update(['thumb_url' => $image_url ]);
                }
            }
        }

        return back()->with('success','成功');
    }
}
