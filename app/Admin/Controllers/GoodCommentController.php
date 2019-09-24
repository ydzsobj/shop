<?php

namespace App\Admin\Controllers;

use App\Http\Requests\UpdateGoodCommentRequest;
use App\Models\Good;
use App\Models\GoodComment;
use App\Models\GoodCommentImage;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodCommentController extends BaseController
{
    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $gm = new GoodComment();
        list($search, $good_comments) = $gm->get_data($request);

        $good_names = Good::orderBy('id', 'desc')->pluck('name', 'id');
        $audit_status = config('comment.audit_status');
        $type_list = config('comment.type_list');

        return view('admin.good_comment.index', compact('good_comments', 'search', 'type_list', 'good_names', 'audit_status'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){

        $detail = GoodComment::find($id);
        $star_scores = config('comment.star_scores');
        $comment_images = $detail->comment_images;

        $comment_image_urls = '';
        if($comment_images->count() >0){
            $comment_image_urls = $comment_images->map(function($item){
                return $item->image_url;
            });

            $comment_image_urls = str_replace(['[',']'], '', json_encode($comment_image_urls));
        }

        return view('admin.good_comment.edit', compact('detail','star_scores','comment_image_urls'));
    }

    /**
     * @param UpdateGoodCommentRequest $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateGoodCommentRequest $request, $id){

        $gm = GoodComment::find($id);

        $req = $request->only('comment','name','phone','star_scores');

        $result = GoodComment::where('id', $id)->update($req);

        if($result){
            $comment_image_files = $request->file('comment_image_files');

            //保存晒图
            $image_urls = [];
            if($comment_image_files && count($comment_image_files) > 0){
                foreach ($comment_image_files as $comment_image_file){
                    $url = $this->upload($comment_image_file);
                    if($url){
                        array_push($image_urls,['image_url' => $url]);
                    }
                }

                //删除原来的
                GoodCommentImage::where('good_comment_id',$id)->delete();
                $gm->comment_images()->createMany($image_urls);
            }


            return back()->with('success', trans('common.update.success'));
        }else{
            return back()->with('error', trans('common.update.fail'));
        }
    }

    /**
     * 审核评价
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_audited_at(Request $request, $id){

        $gm = GoodComment::find($id);

        $gm->admin_user_id = Admin::user()->id;

        $gm->audited_at = Carbon::now();

        $res = $gm->save();

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){

        $res = GoodComment::where('id', $id)->delete();

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg ]);
    }
}
