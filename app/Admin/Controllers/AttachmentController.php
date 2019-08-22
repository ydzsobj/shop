<?php

namespace App\Admin\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;


class AttachmentController extends Controller
{

    # 附件目录
    const ATTACHMENT_PATH = 'uploads/image/';

    # 上传文件大小
    const FILE_LIMIT = 10240000;


    public function store(Request $request)
    {

        # 允许上传的扩展名
        $allow_extensions = ['xlsx','xls','csv','docx','doc','jpg','jpeg','png','gif'];
        $allow_extensions_str = implode(',',$allow_extensions);

        # 文件
        $file = $request->file('file');

        $file_name = $file->getClientOriginalName();

        if(!$file->isValid())
        {
            fail('文件无效,附件上传失败,请联系管理员');
        }

        # 扩展名
        $extension = pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);

        if(!in_array(strtolower($extension), $allow_extensions))
        {
            fail('只允许上传指定格式文件ext:'.$allow_extensions_str);
        }

        # 文件大小
        $file_size = $file->getClientSize();

        if($file_size > AttachmentController::FILE_LIMIT)
        {
            fail('超过文件大小限制4MB');
        }

        $doc_path = AttachmentController::ATTACHMENT_PATH.date('Y').'/'.date('m').'/'.date('d');

        $filename = md5(time()).'.'.$extension;

        Storage::makeDirectory($doc_path);

        $result = Storage::disk('public')->putFileAs($doc_path, $file, $filename);

        # 保存文件并, 路径,

        if($result){
            return response()->json(['success' => true, 'msg' => '上传成功', 'path' => $result]);
        }else{
            return response()->json(['success' => false, 'msg' => '上传失败']);
        }

    }

    public function destroy(Request $request , $id)
    {
        return json_encode([
            'msg'  => '文件删除成功',
            'code' => 200,
        ]);
        $data    = $request->all();
        $del_url = preg_replace("/[\/|\\\]storage/",'',$data['del_url']);

        if ($data['dir'] == 'dir') {
            $del_res = Storage::disk('public')->deleteDirectory($del_url);
        } else if ($data['dir'] == 'file') {
            $del_res = Storage::disk('public')->delete($del_url);
        }
        if ($del_res) {   //检测是否删除
            $res = [
                'msg'  => '文件删除成功',
                'code' => 200,
            ];
        } else {
            $res = [
                'msg'  => '文件删除失败',
                'code' => 400,
            ];
        }
        return json_encode($res);
    }


}
