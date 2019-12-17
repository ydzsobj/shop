@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            订单管理
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
                        <form action="{{route('good_orders.index')}}" class="form-horizontal" method="get" id="fm">

                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">
                                            <div class="form-group">

                                                <div class="col-md-2">
                                                    <select class="form-control status" name="date_search_item">
                                                        <option></option>
                                                        @foreach($date_search_items as $key=>$item)
                                                            <option value="{{$key}}" @if($search['date_search_item'] == $key)selected @endif>{{$item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 date_type_start_end">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" id="created_at_start" placeholder="开始时间" name="start_date" value="{{$search['start_date']}}">
                                                        <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                                                        <input type="text" class="form-control" id="created_at_end" placeholder="结束时间" name="end_date" value="{{$search['end_date']}}">
                                                    </div>
                                                </div>

                                                <label class="col-sm-1 control-label">状态</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="status">
                                                        <option></option>
                                                        @foreach($status as $k=>$s)
                                                            <option value="{{$k}}" @if(!is_null($search['status']) && $search['status'] == $k)selected @endif>{{$s}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">ID</label>
                                                <div class="col-sm-2">
                                                    <input class="form-control " name="search_id" placeholder="请输入ID" value="{{$search['search_id']}}"/>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">
                                            <div class="form-group">
                                                <div class="col-md-1">
                                                    <select class="form-control status" name="search_item">
                                                        <option></option>
                                                        @foreach($search_items as $key=>$item)
                                                            <option value="{{$key}}" @if($search['search_item'] == $key)selected @endif>{{$item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>

                                                        <input type="text" class="form-control keywords" placeholder="请输入对应筛选项值" name="keywords" value="{{$search['keywords']}}">
                                                    </div>
                                                </div>

                                                <label class="col-sm-1 control-label">模糊搜索</label>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control filter_keywords" placeholder="收货人/收货电话" name="filter_keywords" value="{{$search['filter_keywords']}}">
                                                </div>

                                                <label class="col-sm-1 control-label">国家</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="country_id">
                                                        <option></option>
                                                        @foreach($country_list as $key=>$country)
                                                            <option value="{{$key}}" @if($search['country_id'] == $key) selected @endif>{{$country['name']}}</option>
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
                                                <a href="{{route('good_orders.index')}}" class="btn btn-default btn-sm"><i
                                                            class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <div class="pull-left">
                            <div class="btn-group grid-select-all-btn" style="display:none;margin-right: 5px;">
                                <a class="btn btn-sm btn-default"><span class="hidden-xs selected"></span></a>
                                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="grid-batch-0" id="batch_delete">批量删除 </a></li>
                                    <li><a class="grid-batch-1" id="batch_export">批量导出 </a></li>
                                    <li><a href="#" class="grid-batch-2" data-toggle="modal" data-target="#auditModalBatch">批量审核 </a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="pull-right">

                            &nbsp;&nbsp;&nbsp;
                            <div class="btn-group pull-right" style="margin-right: 10px">
                                <a class="btn btn-sm btn-twitter" title="导出" href="{{route('good_orders.export', $search)}}"><i class="fa fa-download"></i>
                                    <span class="hidden-xs"> 导出</span></a>
                            </div>

                        </div>

                    </div>

                    <div  class="box box-default box-solid">
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <div class="box-body" style="display: block;">
                            共 {{$orders->total()}}条
                        </div><!-- /.box-body -->
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="column-__row_selector__"> <input type="checkbox" class="grid-select-all" />&nbsp;</th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    SN
                                </th>
                                <th>
                                    IP
                                </th>
                                <th>
                                    总价
                                </th>
                                <th>
                                    货币
                                </th>

                                <th>
                                    状态
                                </th>
                                <th>
                                    下单时间
                                </th>

                                <th>
                                    收货人
                                </th>

                                <th>
                                    收货地址
                                </th>

                                <th>
                                    留言
                                </th>
                                <th>
                                    单品名/SKU
                                </th>

                                <th>
                                    审核时间
                                </th>
                                <th>
                                    审核人
                                </th>
                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($orders as $order)
                                <tr>
                                    <td class="column-__row_selector__" class="column-__row_selector__">
                                        <input type="checkbox" class="grid-row-checkbox" data-id="{{$order->id}}" />
                                    </td>
                                    <td>{{$order->id}}</td>
                                    <td style="width:10%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->sn}}<br />

                                        <a href="#"
                                           title="客服备注"
                                           id="update_remark_{{$order->id}}"
                                           data-type="text"
                                           data-pk="{{$order->id}}"
                                           data-value="{{$order->remark}}"
                                           data-url="/admin/good_orders/{{$order->id}}/update_remark"
                                           data-title="客服备注">
                                            {{$order->remark ?: '+'}}
                                        </a>

                                    </td>
                                    <td>
                                        {{$order->ip}}<br />
                                        {{$order->ip_country}}</td>
                                    <td>

                                        @if($order->coupon_code)
                                            <a href="#"
                                               title="使用了优惠码,此单优惠了 {{$order->total_off}}"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               >
                                                {{$order->price}}
                                            </a>

                                        @else
                                            {{$order->price}}
                                        @endif
                                    </td>
                                    <td>
                                        {{ collect(array_get($country_list, $order->country_id))->get('money_sign') }}
                                    </td>
                                    <td>
                                        <span style="color: @if($order->status == 1)green @elseif($order->status == 2) red @else orange @endif "
                                              title="审核记录"
                                              data-container="body"
                                              data-toggle="popover"
                                              data-placement="right"
                                              data-trigger="hover"
                                              data-html="true"
                                              data-content="<table style='width:400px;' class='table'><tr><th>审核时间</th><th>审核人</th><th>审核信息</th></tr>
                                                    @foreach($order->audit_logs as $audit_log)
                                                      <tr><td>{{$audit_log->created_at}}</td><td>{{$audit_log->admin_user->name}}</td><td>{{$audit_log->remark}}</td></tr>
                                                    @endforeach
                                              </table>"
                                        >
                                        {{array_get($status, $order->status) }}
                                        </span>
                                    </td>
                                    <td style="width:5%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->created_at}}
                                    </td>
                                    <td>
                                        {{$order->receiver_name}}<br />
                                        <span class="receiver_phone_{{$order->id}}">{{$order->receiver_phone}}</span><br />
                                        {{$order->receiver_email}}
                                    </td>
                                    <td style="width:18%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->address}}<br />
                                        {{$order->short_address}}
                                    </td>
                                    <td>
                                        <span style="width: 70px;"
                                             title=""
                                             data-container="body"
                                             data-toggle="popover"
                                             data-placement="right"
                                             data-trigger="hover"
                                             data-content="{{$order->leave_word}}"
                                        >
                                            @if($order->leave_word)<a style="color: #0d6aad">查看</a>@endif
                                        </span>

                                    </td>
                                    <td style="width:20%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        @foreach($order->order_skus as $order_sku)
                                            @php($sku = $order_sku->sku_info)
                                            <span>{{$sku->good->name. '【' .$sku->sku_id. '】 ' .\App\Models\ProductAttributeValue::get_show_name($sku->good_id, [$sku->s1,$sku->s2,$sku->s3]). ' x'. $order_sku->sku_nums }}</span><br>
                                        @endforeach
                                    </td>
                                    <td style="width:6%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->last_audited_at}}
                                    </td>
                                    <td>{{$order->admin_user ? $order->admin_user->name : ''}}</td>
                                    <td>
                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                <li><a href="#" data-toggle="modal" data-target="#editModal_{{$order->id}}" data-remote="{{route('good_orders.edit',['id' => $order->id])}}">编辑</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#auditModal_{{$order->id}}" data-remote="{{route('good_orders.create_audit',['id' => $order->id])}}">审核</a></li>
                                                <li><a href="#" id ="disable_{{$order->id}}" data-id="{{$order->id}}" data-title="删除" data-action="disable" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="auditModal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:50%;">
                                                <form action="{{route('good_orders.audit',['id' => $order->id])}}" class="form-horizontal" method="post">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel">审核订单</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </form>
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

    <script>

        @foreach($group_orders as $orders)
            @if(count($orders) > 1)
                var ids = JSON.parse("{{$orders->pluck('id')}}");
                console.log(ids,ids.length);
                for(var i=0;i<ids.length;i++){
                    $(".receiver_phone_" + ids[i]).css('color','red');
                }
            @endif
        @endforeach

    </script>

@endsection
