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

                                                <label class="col-sm-1 control-label">
                                                    下单时间
                                                </label>

                                                <div class="col-sm-3 date_type_start_end">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" id="created_at_start" placeholder="发布时间" name="start_date" value="{{$search['start_date']}}">
                                                        <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                                                        <input type="text" class="form-control" id="created_at_end" placeholder="发布时间" name="end_date" value="{{$search['end_date']}}">
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

                                                <div class="col-md-1"></div>

                                                <div class="col-md-1">
                                                    <select class="form-control status" name="search_item">
                                                        <option></option>
                                                        @foreach($search_items as $key=>$item)
                                                            <option value="{{$key}}" @if($search['search_item'] == $key)selected @endif>{{$item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>

                                                        <input type="text" class="form-control keywords" placeholder="请输入对应筛选项值" name="keywords" value="{{$search['keywords']}}">
                                                    </div>
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
                                    <li><a href="#" class="grid-batch-1" data-toggle="modal" data-target="#auditModalBatch">批量审核 </a></li>
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
                                    订单号
                                </th>
                                <th>
                                    下单IP
                                </th>
                                <th>
                                    下单总价({{$money_sign}})
                                </th>

                                <th>
                                    订单状态
                                </th>
                                <th>
                                    下单时间
                                </th>

                                <th>
                                    收货人信息
                                </th>

                                <th>
                                    收货人地址
                                </th>

                                <th>
                                    留言
                                </th>
                                <th>
                                    单品名/SKU信息
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
                                           data-title="客服备注">{{$order->remark ?: '+'}}
                                        </a>

                                    </td>
                                    <td>{{$order->ip}}</td>
                                    <td>{{$order->price}}</td>
                                    <td>
                                        <span style="color: @if($order->status == 1)green @else red @endif "
                                              title="审核记录"
                                              data-container="body"
                                              data-toggle="popover"
                                              data-placement="right"
                                              data-trigger="hover"
                                              data-html="true"
                                              data-content="<table style='width:400px;' class='table'><tr><th>审核时间</th><th>审核人</th><th>审核信息</th></tr>
                                                    @foreach($order->audit_logs as $audit_log)
                                                      <tr><td>{{$audit_log->created_at}}</td><td>{{$audit_log->admin_user->username}}</td><td>{{$audit_log->remark}}</td></tr>
                                                    @endforeach
                                              </table>"
                                        >
                                        {{array_get($status, $order->status) }}
                                        </span>
                                    </td>
                                    <td style="width:6%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->created_at}}
                                    </td>
                                    <td>
                                        {{$order->receiver_name}}<br />
                                        {{$order->receiver_phone}}<br />
                                        {{$order->receiver_email}}
                                    </td>
                                    <td style="width:15%; word-break:break-all; word-wrap:break-word; white-space:inherit">
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
                                            <span>{{$sku->good->name. '【' .$sku->sku_id. '】 ' .$sku->s1_name.' '.$sku->s2_name.' '.$sku->s3_name. ' x'. $order_sku->sku_nums }}</span><br>
                                        @endforeach
                                    </td>
                                    <td style="width:6%; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$order->last_audited_at}}
                                    </td>
                                    <td>{{$order->admin_user ? $order->admin_user->username : ''}}</td>
                                    <td>
                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                <li><a href="#" data-toggle="modal" data-target="#editModal" data-remote="{{route('good_orders.edit',['id' => $order->id])}}">编辑</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#auditModal_{{$order->id}}">审核</a></li>
                                                <li><a href="#" id ="disable_{{$order->id}}" data-id="{{$order->id}}" data-title="删除" data-action="disable" class="grid-row-action">删除</a></li>

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

                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="title" class="col-sm-2 asterisk control-label">选择状态</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="status" name="status" style="width: 200px;" required="1">
                                                                            <option></option>
                                                                            @foreach($status as $key=>$s)
                                                                                <option value="{{$key}}">{{$s}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br />
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="title" class="col-sm-2 asterisk control-label">填写审核信息</label>
                                                                    <div class="col-sm-6">
                                                                        <div><textarea cols="30" rows="3" name="remark" required="1"></textarea></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="_method" value="put" />
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                            <button type="submit" class="btn btn-primary">提交</button>
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

    <script>

        $(function () {
            $('#created_at_start').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN"});
            $('#created_at_end').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN","useCurrent":false});
            $("#created_at_start").on("dp.change", function (e) {
                $('#created_at_end').data("DateTimePicker").minDate(e.date);
            });
            $("#created_at_end").on("dp.change", function (e) {
                $('#created_at_start').data("DateTimePicker").maxDate(e.date);
            });

            $(".status").select2({
                placeholder: {"id":"","text":"\u9009\u62e9"},
                "allowClear":true
            });
        });

        //禁用
        $("a[id*=disable_],a[id*=enable_]").click(function(){
            var title = $(this).data('title');
            var id = $(this).data('id');
            var action = $(this).data('action');
            swal({
                title: "确认要" + title + "吗?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/good_orders/' +id,
                            data: {
                                _method:'delete',
                                _token:"{{csrf_token()}}",
                                action:action,
                            },
                            success: function (data) {
                                //异步修改数据
                                // console.log(data);
                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(data) {
                console.log(data);
                var result = data.value;
                if (typeof result === 'object') {
                    if (result.success) {
                        swal(result.msg, '', 'success').then(function(msg){
                            console.log(msg);
                            if(msg.value == true){
                                window.location.reload();
                            }
                        });

                    } else {
                        swal(result.msg, '', 'error');
                    }
                }
            });
        })

        //加备注
        $("a[id*='update_remark_']").editable({
            value :'',
            params: function(params) {
                //originally params contain pk, name and value
                params._method = 'put';
                params._token = "{{csrf_token()}}";
                return params;
            },
            success: function(response, newValue) {
                console.log(response);
                if(!response.success) {
                    return response.msg; //msg will be shown in editable form
                }else{
                    //成功
                    location.reload();
                }
            },

            error: function(response, newValue) {
                if(response.status === 500) {
                    return '服务器内部错误,请联系管理员';
                }
                if(response.status == 403) {
                    return  response.responseJSON.message
                } else {
                    return response.responseText;
                }
            },
        });

        //批量删除
        $("#batch_delete").click(function(){
            var title = '批量删除';
            var order_ids = $.admin.grid.selected();
            if(order_ids.length == 0){
                swal('需要选择至少一条数据','','error');
                return false;
            }
            swal({
                title: "确认要" + title + "吗?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/good_orders/batch_destroy',
                            data: {
                                // _method:'post',
                                _token:"{{csrf_token()}}",
                                order_ids: order_ids,
                            },
                            success: function (data) {
                                //异步修改数据
                                // console.log(data);
                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(data) {
                console.log(data);
                var result = data.value;
                if (typeof result === 'object') {
                    if (result.success) {
                        swal(result.msg, '', 'success').then(function(msg){
                            console.log(msg);
                            if(msg.value == true){
                                window.location.reload();
                            }
                        });

                    } else {
                        swal(result.msg, '', 'error');
                    }
                }
            });
        })

        //批量审核
        $("#batch_audit_submit").click(function(){
            var order_ids = $.admin.grid.selected();
            if(order_ids.length == 0){
                swal('请选择一条数据','','error');
                return false;
            }

            $("#order_ids").val(order_ids);

            var status = $("#fm_batch_audit").find("select[name=status]").val();

            if(!status){
                $(".status_error_tips").show();
                return false;
            }else{
                $(".status_error_tips").hide();
            }
            var remark = $("#fm_batch_audit").find("textarea[name=remark]").val();

            if(!remark){
                $(".remark_error_tips").show();
                return false;
            }else{
                $(".remark_error_tips").hide();
            }


            $("#fm_batch_audit").submit();

        })

        //分页
        $(".grid-per-pager").on('change', function(e){
            $("#select_per_page").val($(this).val());
            $("#fm").submit();
        })



        $('.grid-row-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {

            var id = $(this).data('id');

            if (this.checked) {
                $.admin.grid.select(id);
                $(this).closest('tr').css('background-color', '#ffffd5');
            } else {
                $.admin.grid.unselect(id);
                $(this).closest('tr').css('background-color', '');
            }
        }).on('ifClicked', function () {

            var id = $(this).data('id');

            if (this.checked) {
                $.admin.grid.unselect(id);
            } else {
                $.admin.grid.select(id);
            }

            var selected = $.admin.grid.selected().length;

            if (selected > 0) {
                $('.grid-select-all-btn').show();
            } else {
                $('.grid-select-all-btn').hide();
            }

            $('.grid-select-all-btn .selected').html("已选择 {n} 项".replace('{n}', selected));
        });

        $('.grid-select-all').iCheck({checkboxClass:'icheckbox_minimal-blue'});

        $('.grid-select-all').on('ifChanged', function(event) {
            if (this.checked) {
                $('.grid-row-checkbox').iCheck('check');
            } else {
                $('.grid-row-checkbox').iCheck('uncheck');
            }
        }).on('ifClicked', function () {
            if (this.checked) {
                $.admin.grid.selects = {};
            } else {
                $('.grid-row-checkbox').each(function () {
                    var id = $(this).data('id');
                    $.admin.grid.select(id);
                });
            }

            var selected = $.admin.grid.selected().length;

            if (selected > 0) {
                $('.grid-select-all-btn').show();
            } else {
                $('.grid-select-all-btn').hide();
            }

            $('.grid-select-all-btn .selected').html("已选择 {n} 项".replace('{n}', selected));
        });


    </script>

    <script data-exec-on-popstate>

        $(function () {
            (function ($) {
                $('.table-responsive').on('show.bs.dropdown', function () {
                    $('.table-responsive').css("overflow", "inherit" );
                });

                $('.table-responsive').on('hide.bs.dropdown', function () {
                    $('.table-responsive').css("overflow", "auto");
                })
            })(jQuery);


        });


        $('.container-refresh').off('click').on('click', function() {
            $.admin.reload();
            $.admin.toastr.success('刷新成功 !', '', {positionClass:"toast-top-center"});
        });
    </script>
@endsection
