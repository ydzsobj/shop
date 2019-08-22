<?php

namespace App\Admin\Controllers;

use App\Events\BindGoodAttributeEvent;
use App\Events\BindProductAttributeEvent;
use App\Exports\GoodsExport;
use App\Http\Requests\CopyGood;
use App\Http\Requests\StoreGood;
use App\Http\Requests\UpdateGood;
use App\Models\AdminUser;
use App\Models\Good;
use App\Models\GoodAttribute;
use App\Models\GoodCategory;
use App\Models\GoodImage;
use App\Models\GoodModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Support\Facades\Route;
use Storage;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Log;


/**
 * Class GoodController
 * @package App\Admin\Controllers
 */
class GoodController extends BaseController
{
    //首页
    public function index(Request $request){

        $gd = new Good();
        list($goods, $search) = $gd->get_data($request);

        $admin_users = AdminUser::pluck('username','id');

        //生成排序链接
        $sort_links = $this->build_sort_links($request);

        return view('admin.good.index',compact('goods','search', 'admin_users','sort_links'));
    }

    //新增页面
    public function create(Request $request){

        return view('admin.good.create');
    }

    //新增
    public function store(StoreGood $request){

        $insert_data = $request->only([
            'title',
            'name',
            'original_price',
            'price',
            'product_id',
            'product_name',
            'category_id',
            'good_module_id',
            'show_comment',
            'detail_desc',
            'size_desc',
            'fb_pix'
        ]);

        if(is_null($request->post('show_comment'))){
            $insert_data['show_comment'] = 0;
        }

        $pay_types = json_encode($request->post('pay_types'));

        list($main_image_url, $main_video_url, $list_image_urls) = $this->upload_file($request);

        //添加商品
        $mod = Good::create(array_merge(
            $insert_data,
            [
                'admin_user_id' => Admin::user()->id,
                'main_image_url' => $main_image_url,
                'main_video_url' => $main_video_url ?? null,
                'pay_types' => $pay_types
            ]
        ));

        if($mod && count($list_image_urls)){
            $result = $mod->list_images()->createMany($list_image_urls);
        }

        if($mod){

            //绑定默认属性
            event(new BindProductAttributeEvent($mod));

            return redirect(route('goods.index'))->with('success', trans('common.create.success'));
        }else{
            return redirect(route('goods.create'))->with('error', trans('common.create.fail'));
        }

    }

    //编辑保存
    public function update(UpdateGood $request, $id){

        $gd = Good::find($id);
        if(!$gd){
            return redirect(route('goods.index'))->with('error', trans('good.not_exist'));
        }

        $update_data = $request->only([
            'title',
            'name',
            'original_price',
            'price',
//            'product_id',
            'product_name',
            'category_id',
            'good_module_id',
            'show_comment',
            'detail_desc',
            'size_desc',
            'fb_pix'
        ]);

        if(is_null($request->post('show_comment'))){
            $update_data['show_comment'] = 0;
        }

        $pay_types = json_encode($request->post('pay_types'));

        $update_data = collect($update_data)->merge(['pay_types' => $pay_types]);

        list($main_image_url, $main_video_url, $list_image_urls) = $this->upload_file($request);

        if($main_image_url){
            $update_data = collect($update_data)->merge(['main_image_url' => $main_image_url]);
        }

        if($main_video_url){
           $update_data = collect($update_data)->merge(['main_video_url' => $main_video_url]);
        }

        $result = Good::where('id', $id)->update($update_data->all());

        if($result){
            if(count($list_image_urls) >0){
                //删除原来的
                GoodImage::where('good_id',$id)->delete();
                //添加新的
                $gd->list_images()->createMany($list_image_urls);
            }
            return redirect(route('goods.index'))->with('success', trans('common.update.success'));
        }else{
            return redirect(route('goods.index'))->with('error', trans('common.update.fail'));
        }


    }

    //编辑请求
    public function edit(Request $request, $id){

        $detail = Good::find($id);
        $list_images = $detail->list_images;

        $list_image_urls = '';
        if($list_images->count() >0){
            $list_image_urls = $list_images->map(function($item){
                return $item->image_url;
            });

            $list_image_urls = str_replace(['[',']'], '', json_encode($list_image_urls));
        }

        return view('admin.good.edit', compact('detail','list_image_urls'));
    }

    //删除 禁用
    public function destroy(Request $request, $id){

        $gd = Good::withTrashed()
            ->where('id', $id)
            ->first();

        $action = $request->post('action');

        switch ($action){
            case 'disable':
                $res = $gd->delete();
                break;
            case 'enable':

                //软删除
                if($gd->trashed()){
                    $res = $gd->restore();
                }

                break;

            default:
                return response()->json(['success'=>false,'msg' => trans('common.params.error')]);
        }

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg ]);
    }

    /**
     * @param $request
     * @return array
     */
    public function upload_file($request){

        $main_image_file = $request->file('main_image_file');

        $list_image_files = $request->file('list_image_files');

        $main_video_file = $request->file('main_video_file');

        $main_image_url = $main_image_file ? $this->upload($main_image_file) : null;

        $main_video_url = $main_video_file ? $this->upload($main_video_file) : null ;

        //加轮播图
        $list_image_urls = [];
        if($list_image_files && count($list_image_files) > 0){
            foreach ($list_image_files as $list_image_file){
                $url = $this->upload($list_image_file);
                if($url){
                    array_push($list_image_urls,['image_url' => $url]);
                }
            }
        }

        return [$main_image_url, $main_video_url, $list_image_urls];

    }

    /**
     * 商品导出
     * @param Request $request
     */
    public function export(Request $request){

        $gd = new Good();
        $data = $gd->export($request);

        return Excel::download(new GoodsExport($data), '商品导出'.date('y-m-d H_i_s').'.xlsx');
    }

    //do upload
    protected function upload($file){

        # 允许上传的扩展名
        $allow_extensions = ['jpg','jpeg','png','gif','mp4','mpeg'];

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

    public function search(Request $request){

        $keywords = $request->get('keywords');

        if(!$keywords){
            return returned(false,'关键词不能为空');
        }

        $result = Good::where('name', 'like', '%'. $keywords.'%')
            ->select('main_image_url','name','id')
            ->paginate(20);


        return $result;
    }

    /**
     * 单品复制
     * @param CopyGood $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function copy(CopyGood $request, $id){

        $name = $request->post('name');

        $admin_user_id = $request->post('admin_user_id');

        $good = Good::find($id);

        $list_images = $good->list_images()->select('good_id','image_url')->get();

        $copy_data = $good->replicate();

        $copy_data->name = $name;
        $copy_data->admin_user_id = $admin_user_id;

        $result = $copy_data->save();

        if($result){
            //绑定默认属性
            event(new BindProductAttributeEvent($copy_data));

            //轮播图复制
            if($list_images->count() > 0){
                $list_images = $list_images->map(function($item) use ($copy_data){
                    $item->good_id = $copy_data->id;
                    return $item->toArray();
                });

                $copy_data->list_images()->createMany($list_images->all());
            }
        }

        $msg = $result ? trans('good.copy.success') : trans('good.copy.fail');

        $alert_type =  $result ? 'success' : 'error';

        return redirect(route('goods.index'))->with($alert_type, $msg);
    }

    /**
     * 处理排序
     * @param $sort_field
     * @param $sort_type
     */
    protected function sorter($request, $current_sort_field){

        $sort_field = $request->query('sort_field');
        $sort_type = $request->query('sort_type');

        $icon = 'fa-sort';
        $type = 'desc';

        if($sort_field == $current_sort_field){
            $type = $sort_type == 'desc' ? 'asc' : 'desc';
            $icon .= "-amount-{$sort_type}";
        }

        $query = $request->all();
        $query = array_merge($query, ['sort_field' => $current_sort_field, 'sort_type' => $type ]);

        $url = route(Route::currentRouteName(), $query);

        return "<a class=\"fa fa-fw $icon\" href=\"$url\"></a>";

    }

    /**
     * 生成排序链接
     * @param $request
     * @return array
     */
    protected function build_sort_links($request){

        $sort_fields = ['price'];

        $sort_links = [];

        foreach ($sort_fields as $sort_field){
            $sort_links[$sort_field]  = $this->sorter($request, $sort_field);
        }

        return $sort_links;
    }

}
