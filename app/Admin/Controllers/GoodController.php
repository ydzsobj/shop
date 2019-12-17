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
use App\Models\GoodComment;
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

        $country_list = config('country.country_list');

        //生成排序链接
        $sort_links = $this->build_sort_links($request);

        return view('admin.good.index',compact('goods','search', 'admin_users','sort_links', 'country_list'));
    }

    //新增页面
    public function create(Request $request){

        $country_list = config('country.country_list');

        return view('admin.good.create', compact('country_list'));
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
            'fb_pix',
            'about',
            'show_coupon_code',
            'country_id'
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
            // event(new BindProductAttributeEvent($mod));
            event(new BindGoodAttributeEvent($mod));

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
            'fb_pix',
            'about',
            'show_coupon_code',
        ]);

        $list_image_clear_flag = $request->post('list_image_clear_flag');
        $video_clear_flag = $request->post('video_clear_flag');

        if(is_null($request->post('show_comment'))){
            $update_data['show_comment'] = 0;
        }

        if(!$request->post('show_coupon_code')){
            $update_data['show_coupon_code'] = 0;
        }

        $pay_types = json_encode($request->post('pay_types'));

        $update_data = collect($update_data)->merge(['pay_types' => $pay_types]);

        list($main_image_url, $main_video_url, $list_image_urls) = $this->upload_file($request);

        if($main_image_url){
            $update_data = collect($update_data)->merge(['main_image_url' => $main_image_url]);
        }

        if($main_video_url){
           $update_data = collect($update_data)->merge(['main_video_url' => $main_video_url]);
        }else{
            if($video_clear_flag){
                //清除视频
                $update_data = collect($update_data)->merge(['main_video_url' => null]);
            }
        }

        $result = Good::where('id', $id)->update($update_data->all());

        if($result){
            if(count($list_image_urls) >0){
                //删除原来的
                GoodImage::where('good_id',$id)->delete();
                //添加新的
                $gd->list_images()->createMany($list_image_urls);
            }else{
                if($list_image_clear_flag){
                    //清除轮播
                    GoodImage::where('good_id',$id)->delete();
                }
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
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_sku(Request $request, $id){
        $good = Good::find($id);
        return view('admin.good.edit_sku', compact('good'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_attr(Request $request, $id){
        $good = Good::find($id);
        return view('admin.good.edit_attr', compact('good'));
    }


    /**
     * @param Request $request
     * @param $good_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_copy(Request $request, $good_id){
        $admin_users = AdminUser::pluck('username','id');
        return view('admin.good.create_copy', compact('good_id', 'admin_users'));
    }

    /**
     * 单品复制
     * @param CopyGood $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store_copy(CopyGood $request, $id){

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
            // event(new BindProductAttributeEvent($copy_data));
            event(new BindGoodAttributeEvent($copy_data));

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


    /**
     * @param Request $request
     * @param $good_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_comment(Request $request, $good_id){
        $star_scores = config('comment.star_scores');
        $rand_mobile = rand_mobile();
        return view('admin.good.create_comment',compact('good_id','star_scores','rand_mobile'));
    }

    /**
     * @param Request $request
     * @param $good_id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store_comment(Request $request){

        $req = $request->only('good_id','comment','name','phone','star_scores','created_at');

        $comment_image_files = $request->file('comment_image_files');

        $req['type_id'] = GoodComment::TYPE_SYSTEM;

        $mod = GoodComment::create($req);

        if($mod){
            //保存晒图
            $image_urls = [];
            if($comment_image_files && count($comment_image_files) > 0){
                foreach ($comment_image_files as $comment_image_file){
                    $url = $this->upload($comment_image_file);
                    if($url){
                        array_push($image_urls,['image_url' => $url]);
                    }
                }
            }
            $mod->comment_images()->createMany($image_urls);
            return redirect(route('goods.index'))->with('success', trans('common.create.success'));
        }else{
            return redirect(route('goods.index'))->with('error', trans('common.create.fail'));
        }

    }

}
