@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            优惠码管理
        </h1>
    </section>
    <section class="content">

        <style>
            .operate_account{
                cursor:pointer;
            }
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
                        <form action="{{route('coupon_codes.index')}}" class="form-horizontal" method="get" id="fm">
                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">
                                            <div class="form-group">

                                                <label class="col-sm-1 control-label">单品名称</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control" name="good_id" id="good_id">
                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">优惠类型</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="type_id">
                                                        <option></option>
                                                        @foreach($type_list as $key=>$type)
                                                            <option value="{{$key}}" @if(isset($search['type_id']) && $search['type_id'] == $key) selected @endif>{{$type}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">状态</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="status">
                                                        <option></option>
                                                        @foreach($status_list as $key=>$status)
                                                            <option value="{{$key}}" @if(isset($search['status']) && $search['status'] == $key) selected @endif>{{$status}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">适用类型</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="apply_type_id">
                                                        <option></option>
                                                        @foreach($apply_type_list as $key=>$type)
                                                            <option value="{{$key}}" @if(isset($search['apply_type_id']) && $search['apply_type_id'] == $key) selected @endif>{{$type}}</option>
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
                                                <a href="{{route('coupon_codes.index')}}" class="btn btn-default btn-sm"><i
                                                            class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="pull-right">
                            <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                <a href="{{route('coupon_codes.create')}}" class="btn btn-sm btn-success" title="新增">
                                    <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;创建优惠码</span>
                                </a>
                            </div>
                            &nbsp;&nbsp;&nbsp;


                        </div>

                    </div>

                    <div  class="box box-default box-solid">
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <div class="box-body" style="display: block;">
                            共 {{$coupon_codes->total()}}条
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
                                    优惠码
                                </th>
                                <th>
                                    绑定单品
                                </th>
                                <th>
                                    优惠类型
                                </th>
                                <th>
                                    适用类型
                                </th>
                                <th>
                                    开始-结束时间
                                </th>
                                <th>
                                    状态
                                </th>
                                <th>
                                    添加人
                                </th>
                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($coupon_codes as $coupon_code)
                                <tr>
                                    <td>{{$coupon_code->id}}</td>
                                    <td>
                                        {{$coupon_code->code}}<br />
                                        <a onclick="copy('{{$coupon_code->code}}');">复制</a></td>
                                    <td>{{$coupon_code->good->name}}</td>
                                    <td>
                                        @php($targetable = $coupon_code->targetable)
                                        {{\App\Models\CouponCode::formart_type_info($coupon_code->type_id,$targetable)}}
                                    </td>
                                    <td>{{array_get($apply_type_list, $coupon_code->apply_type_id)}}</td>
                                    <td>
                                        {{$coupon_code->start_date}}<br />
                                        {{$coupon_code->end_date}}
                                    </td>
                                    <td>
                                        <span style="color: @if($coupon_code->status == 1)orange @elseif($coupon_code->status == 2) green @else red @endif">
                                            {{array_get($status_list,$coupon_code->status)}}
                                        </span>
                                    </td>
                                    <td>{{$coupon_code->admin_user->name}}</td>
                                    <td>
                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
{{--                                                <li><a href="#" data-toggle="modal" data-target="#editModal_{{$coupon_code->id}}" data-remote="{{route('coupon_codes.edit',['id' => $coupon_code->id])}}">编辑</a></li>--}}
                                                <li><a href="#" id ="delete_{{$coupon_code->id}}" data-id="{{$coupon_code->id}}" data-title="删除" data-url="{{route('coupon_codes.destroy', ['id' => $coupon_code->id])}}" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal_{{$coupon_code->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:100%">
                                                <div class="modal-content">
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
                            {{$coupon_codes->appends($search)->links()}}
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

    <script src="{{asset('js/admin/helper.js')}}"></script>
    <script src="{{asset('js/admin/common.js')}}"></script>
    <script src="{{asset('js/admin/good/search.js')}}" ></script>

    <script src="{{asset('js/admin/delete.js')}}"></script>

@endsection
