<?php

namespace App\Admin\Controllers;

use App\Models\GoodModule;
use App\Models\Slide;
use Illuminate\Http\Request;
use Storage;

class GoodModuleController extends BaseController
{
    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
       $good_modules = GoodModule::orderBy('id','desc')->get();
       $slides = Slide::orderBy('id','desc')->get();

       return view('admin.good_module.index', compact('good_modules','slides'));
    }

    //新增页面
    public function create(Request $request){

        return view('admin.good_module.create');
    }


    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){

        $req = $request->only('name','sort');

        $mod = GoodModule::create($req);

        if($mod){
            $module_image_list = $request->list;
            $tmp = collect([]);
            foreach ($module_image_list as $item){
                $image_url = $this->upload($item['image_file']);
                $tmp->push(['good_id' => $item['good_id'],'image_url' => $image_url]);
            }
            $result = $mod->good_module_images()->createMany($tmp->all());
            return redirect(route('good_modules.index'))->with('success', trans('common.create.success'));
        }else{
            return redirect(route('good_modules.index'))->with('error', trans('common.create.fail'));
        }
    }

    //编辑请求
    public function edit(Request $request, $id){

        $detail = GoodModule::find($id);

        return view('admin.good_module.edit', compact('detail'));
    }

    //编辑保存
    public function update(Request $request, $id){

        $gm = GoodModule::find($id);

        $update_data = $request->only([
            'sort',
            'name',
        ]);

        $update_data = collect($update_data);

        $module_image_file = $request->file('module_image_file');

        $image_url = $module_image_file ? $this->upload($module_image_file) : null;

        if($image_url){
            $update_data = $update_data->merge(['image_url' => $image_url]);
        }

        $result = GoodModule::where('id', $id)->update($update_data->all());

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

        $gm = GoodModule::find($id);
        $res = $gm->delete();
        $msg = $res ? trans('common.delete.success') : trans('common.delete.fail');

        return returned($res, $msg);

    }

    //do upload
    protected function upload($file){

        # 允许上传的扩展名
        $allow_extensions = ['jpg','jpeg','png','gif'];

//        if(!$file->isValid())
//        {
//            admin_error('文件无效,附件上传失败,请联系管理员');
//        }

        # 扩展名
        $extension = strtolower($file->extension());

        if(!in_array(strtolower($extension), $allow_extensions))
        {
            session()->flash('error','文件类型不正确,当前文件后缀:'.$extension);
            return false;
        }

        # 文件大小
        $file_size = $file->getClientSize();

        if($file_size > AttachmentController::FILE_LIMIT)
        {
            session()->flash('error','超过文件大小限制10MB');
            return false;
        }

        $doc_path = AttachmentController::ATTACHMENT_PATH.date('Y').'/'.date('m').'/'.date('d');

        $filename = md5(time().rand(0,100)).'.'.$extension;

        Storage::makeDirectory($doc_path);

        $result = Storage::disk('public')->putFileAs($doc_path, $file, $filename);

        # 保存文件并, 路径,
        return $result ? asset('storage/'.$result) : false;
    }

}
