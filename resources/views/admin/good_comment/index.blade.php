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
                                    单品展示名
                                </th>
                                <th>
                                    评价内容
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
                                    <td style="width:10%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$comment->comment}}<br />
                                    </td>
                                    <td>{{$comment->name}}</td>
                                    <td>{{$comment->phone}}</td>
                                    <td>{{$comment->star_scores}}</td>
                                    <td>
                                        @if($comment->audited_at)
                                            <span>已审核</span>
                                        @else
                                            <span>未审核</span>
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

                                                <li><a href="#" data-toggle="modal" data-target="#editModal" data-remote="{{route('good_comments.edit',['id' => $comment->id])}}">编辑</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#auditModal_{{$comment->id}}">审核</a></li>
                                                <li><a href="#" id ="disable_{{$comment->id}}" data-id="{{$comment->id}}" data-title="删除" data-action="disable" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:100%">
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


                    <div class="modal fade" id="auditModalBatch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width:50%;">
                            <form action="{{route('good_orders.batch_audit')}}" class="form-horizontal" method="post" id="fm_batch_audit">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">批量审核订单</h4>
                                    </div>
                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="title" class="col-sm-2 asterisk control-label">选择状态</label>
                                                <div class="col-sm-6">
                                                    <select class="status form-control" name="status" style="width: 200px;" required="1">
                                                        <option></option>
                                                        @foreach($status as $key=>$s)
                                                            <option value="{{$key}}">{{$s}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span style="color: red;display: none;" class="status_error_tips">选择不能为空</span>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="title" class="col-sm-2 asterisk control-label">填写审核信息</label>
                                                <div class="col-sm-6">
                                                    <div>
                                                        <textarea cols="30" rows="3" class="form-control" name="remark" required="1"></textarea>
                                                        <span style="color: red;display: none;" class="remark_error_tips">审核信息不能为空</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        {{--<input type="hidden" name="_method" value="put" />--}}
                                        <input type="hidden" name="order_ids" id="order_ids" />
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        <button type="button" class="btn btn-primary" id="batch_audit_submit">提交</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </form>
                        </div><!-- /.modal -->
                    </div>

                    <div class="box-footer clearfix ">

                        <div class="pull-right">
                            <!-- Previous Page Link -->
                            {{$orders->appends($search)->links('vendor.pagination.default')}}
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
    <script src="{{URL::asset('/js/admin/good_order/index.js')}}"></script>

@endsection
