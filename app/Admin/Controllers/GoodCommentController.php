<?php

namespace App\Admin\Controllers;

use App\Models\GoodComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodCommentController extends Controller
{
    //
    public function index(Request $request){

        $gm = new GoodComment();
        $good_comments = $gm->get_data($request);

        return view('admin.good_comment.index', compact('good_comments'));
    }
}
