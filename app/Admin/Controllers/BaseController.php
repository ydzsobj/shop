<?php

namespace App\Admin\Controllers;

use App\Models\GoodCategory;
use App\Models\GoodModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    function __construct(Request $request)
    {

        View::share('erp_api_domain', env('ERP_API_DOMAIN', ''));
        View::share('shop_front_url', env('SHOP_FRONT_URL', ''));
        View::share('global_area', config('global_area'));
        View::share('global_lang', config('global_lang'));
        View::share('money_sign', config('money_sign'));

        $good_categories = GoodCategory::pluck('name','id');
        $good_modules = GoodModule::pluck('name','id');

        View::share('good_categories', $good_categories);
        View::share('good_modules', $good_modules);

        //上传配置
        View::share('upload_config', config('upload'));
        //国家配置
        View::share('country_list', config('country.country_list'));


    }

    //do upload
    protected function upload($file){

        # 允许上传的扩展名
        $allow_extensions = ['jpg','jpeg','png','gif','mp4','avi'];

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
