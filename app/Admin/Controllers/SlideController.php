<?php

namespace App\Admin\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Storage;

class SlideController extends BaseController
{

    //新增页面
    public function create(Request $request){

        return view('admin.slide.create');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){

        $req = $request->only('sort', 'good_id', 'country_id');

        $module_image_file = $request->file('slide_image_file');

        $image_url = $module_image_file ? $this->upload($module_image_file) : null;

        $insert_data = array_merge($req,['image_url' => $image_url]);

        $mod = Slide::create($insert_data);

        if($mod){
            return redirect(route('good_modules.index'))->with('success', trans('common.create.success'));
        }else{
            return redirect(route('good_modules.index'))->with('error', trans('common.create.fail'));
        }
    }

    //编辑请求
    public function edit(Request $request, $id){

        $detail = Slide::find($id);

        return view('admin.slide.edit', compact('detail'));
    }

    //编辑保存
    public function update(Request $request, $id){

        $gm = Slide::find($id);

        $update_data = $request->only([
            'sort',
            'good_id',
            'country_id'
        ]);

        $update_data = collect($update_data);

        $module_image_file = $request->file('slide_image_file');

        $image_url = $module_image_file ? $this->upload($module_image_file) : null;

        if($image_url){
            $update_data = $update_data->merge(['image_url' => $image_url]);
        }

        $result = Slide::where('id', $id)->update($update_data->all());

        if($result){
            return redirect(route('good_modules.index'))->with('success', trans('common.update.success'));
        }else{
            return redirect(route('good_modules.index'))->with('error', trans('common.update.fail'));
        }
    }

    /**
     * @param Request $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){

        $gm = Slide::find($id);
        $res = $gm->delete();
        $msg = $res ? trans('common.delete.success') : trans('common.delete.fail');

        return returned($res, $msg);

    }

}
