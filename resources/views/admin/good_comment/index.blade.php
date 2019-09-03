@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            评论管理
        </h1>
    </section>
    <section class="content">

        <style>
            .popover{ max-width:500px;}
        </style>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row"><div class="col-md-12"><div class="box">

                    <div class="box-header with-border " id="filter-box">
                        <form action="{{route('good_comments.index')}}" class="form-horizontal" method="get" id="fm">

                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">

                                            <div class="form-group">

                                                <label class="col-sm-1 control-label">单品名</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control status" name="good_id">
                                                        <option></option>
                                                        @foreach($good_names as $good_id=>$name)
                                                            <option value="{{$good_id}}" @if($search['good_id'] == $good_id) selected @endif>{{$name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">类型</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="type_id">
                                                        <option></option>
                                                        @foreach($type_list as $k=>$type)
                                                            <option value="{{$k}}" @if($search['type_id'] == $k)selected @endif>{{$type}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">审核状态</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="audit_status">
                                                        <option></option>
                                                        @foreach($audit_status as $key=>$status)
                                                            <option value="{{$key}}" @if($search['audit_status'] == $key)selected @endif>{{$status}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-8">
                                            <input type="hidden" name="per_page" id="select_per_page" value="@if($search['per_page']){{$search['per_page']}}@endif" />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="btn-group pull-left">
                                                <button class="btn btn-info submit btn-sm"><i
                                                            class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                                            </div>
                                            <div class="btn-group pull-left " style="margin-left: 10px;">
                                                <a href="{{route('good_comments.index')}}" class="btn btn-default btn-sm"><i
                                                            class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>

                    <div  class="box box-default box-solid">
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <div class="box-body" style="display: block;">
                            共 {{$good_comments->total()}}条
                        </div><!-- /.box-body -->
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    单品名
                                </th>
                                <th>
                                    评价内容
                                </th>
                                <th>
                                    晒图
                                </th>
                                <th>
                                    评价人
                                </th>
                                <th>
                                    评价人电话
                                </th>
                                <th>
                                    星标
                                </th>
                                <th>
                                    审核状态
                                </th>
                                <th>
                                    审核人
                                </th>
                                <th>
                                    评价时间
                                </th>
                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($good_comments as $comment)
                                <tr>
                                    <td>{{$comment->id}}</td>
                                    <td style="width:10%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$comment->good->name}}<br />
                                    </td>
                                    <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$comment->comment}}<br />
                                    </td>
                                    <td>
                                        @foreach($comment->comment_images as $comment_image)
                                            <span class=""
                                                 title=""
                                                 data-container="body"
                                                 data-toggle="popover"
                                                 data-placement="right"
                                                 data-trigger="hover"
                                                 data-html="true"
                                                 data-content="<img src='{{$comment_image->image_url}}' class='img-thumbnail'  />"
                                            >
                                                <img src='{{$comment_image->image_url}}' class='img-thumbnail'  style="width:60px;height: 60px;"/>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>{{$comment->name}}</td>
                                    <td>{{$comment->show_phone}}</td>
                                    <td>{{$comment->star_scores}}</td>
                                    <td>
                                        @if($comment->audited_at)
                                            <span style="color:green;">已审核</span>
                                        @else
                                            <span style="color: red">未审核</span>
                                        @endif
                                    </td>
                                    <td>{{$comment->admin_user ? $comment->admin_user->username : ''}}</td>
                                    <td style="width:6%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$comment->created_at}}
                                    </td>
                                    <td>
                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                <li><a href="#" data-toggle="modal" data-target="#editModal_{{$comment->id}}" data-remote="{{route('good_comments.edit',['id' => $comment->id])}}">编辑</a></li>
                                                <li><a href="#" id="audit_{{$comment->id}}" data-id="{{$comment->id}}" class="grid-row-action">审核</a></li>
                                                <li><a href="#" id ="delete_{{$comment->id}}" data-id="{{$comment->id}}" data-title="删除" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal_{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:80%">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"></h4>
                                                    </div>
                                                    <div class="modal-body"></div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                        <button type="button" class="btn btn-primary">提交更改</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal -->
                                        </div>

                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="box-footer clearfix ">

                        <div class="pull-right">
                            <!-- Previous Page Link -->
                            {{$good_comments->appends($search)->links('vendor.pagination.default')}}
                        </div>

                        <label class="control-label pull-right" style="margin-right: 10px;margin-top: 20px; font-weight: 100;">

                            <small>显示</small>&nbsp;
                            <select class="input-sm grid-per-pager" name="per-page">
                                @foreach(['10','20','30','50','100'] as $per_page)
                                    <option value="{{$per_page}}" @if($search['per_page'] == $per_page) selected @endif >{{$per_page}}</option>
                                @endforeach

                            </select>
                            &nbsp;<small>条</small>
                        </label>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </section>

@endsection


@section('script')

    <script src="{{URL::asset('/js/admin/common.js')}}"></script>

    <script src="{{URL::asset('js/admin/good_comment/index.js')}}"></script>

@endsection
