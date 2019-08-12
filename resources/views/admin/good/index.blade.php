@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            商品管理
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
                        <form action="{{route('goods.index')}}" class="form-horizontal" method="get" id="fm">

                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">
                                            <div class="form-group">

                                                <label class="col-sm-1 control-label">
                                                    发布时间
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

                                                <label class="col-sm-1 control-label">单品类型</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="category_id">
                                                        <option></option>
                                                        @foreach($good_categories as $key=>$category)
                                                            <option value="{{$key}}" @if($search['category_id'] == $key) selected @endif>{{$category}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <label class="col-sm-1 control-label">产品名称</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="product_id" id="product_id">
                                                        {{--<option></option>--}}
                                                        {{--<option value="1">产品1</option>--}}
                                                        {{--<option value="2">产品2</option>--}}

                                                    </select>
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

                                                <label class="col-sm-1 control-label">
                                                    关键词搜索
                                                </label>
                                                <div class="col-sm-3">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>

                                                        <input type="text" class="form-control keywords" placeholder="单品名/单品展示名" name="keywords" value="{{$search['keywords']}}">
                                                    </div>
                                                </div>
                                                {{--<label class="col-sm-1 control-label">所属模块</label>--}}
                                                {{--<div class="col-sm-2">--}}
                                                    {{--<select class="form-control status" name="good_module_id">--}}
                                                        {{--<option></option>--}}
                                                        {{--@foreach($good_modules as $key=>$module)--}}
                                                            {{--<option value="{{$key}}" @if($search['good_module_id'] == $key) selected @endif>{{$module}}</option>--}}
                                                        {{--@endforeach--}}

                                                    {{--</select>--}}
                                                {{--</div>--}}


                                                <label class="col-sm-1 control-label">状态</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control status" name="status">
                                                        <option></option>
                                                        <option value="1" @if($search['status'] == 1)selected @endif>启用</option>
                                                        <option value="2" @if($search['status'] == 2)selected @endif>禁用</option>
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
                                                <a href="{{route('goods.index')}}" class="btn btn-default btn-sm"><i
                                                            class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="pull-right">
                            <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                <a href="{{route('goods.create')}}" class="btn btn-sm btn-success" title="新增">
                                    <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;发布</span>
                                </a>
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="btn-group pull-right" style="margin-right: 10px">
                                <a class="btn btn-sm btn-twitter" title="导出" href="{{route('goods.export')}}"><i class="fa fa-download"></i>
                                    <span class="hidden-xs"> 导出</span></a>
                            </div>

                        </div>

                    </div>

                    <div  class="box box-default box-solid">
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <div class="box-body" style="display: block;">
                            共 {{$goods->total()}}条
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
                                    封面主图
                                </th>
                                <th>
                                    单品名
                                </th>
                                <th>
                                    单品展示名
                                </th>
                                <th>
                                    单品价格({{$money_sign}})
                                </th>
                                <th>
                                    单品类别
                                </th>

                                <th>
                                    发布时间
                                </th>
                                <th>
                                    发布人
                                </th>

                                <th>
                                    状态
                                </th>

                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($goods as $good)
                                <tr>
                                    <td>{{$good->id}}</td>
                                    <td style="width: 80px;">
                                        <div style="width: 70px;"
                                             title="{{$good->title}}"
                                             data-container="body"
                                             data-toggle="popover"
                                             data-placement="right"
                                             data-trigger="hover"
                                             data-html="true"
                                             data-content="<img src='{{$good->main_image_url}}' class='img-thumbnail'  />"
                                        >
                                            <img src='{{$good->main_image_url}}' class='img-thumbnail' />
                                        </div>
                                    </td>
                                    <td>{{$good->name}}</td>
                                    <td style="width:300px; word-break:break-all; word-wrap:break-word; white-space:inherit">
                                        {{$good->title}}
                                    </td>
                                    <td>{{$good->price}}</td>
                                    <td>{{$good->category->name}}</td>

                                    <td>{{$good->created_at}}</td>

                                    <td>{{$good->admin_user->username}}</td>

                                    <td>
                                        @if($good->deleted_at)
                                            <span style="color: red">已禁用</span>
                                        @else
                                            <span style="color: green">启用</span>
                                        @endif
                                    </td>
                                    <td>

                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                @if(!$good->deleted_at)
                                                    <li><a target="_blank" href="{{$shop_front_url}}/#/goods?goodsId={{$good->id}}">预览</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#editModal_{{$good->id}}" data-remote="{{route('goods.edit',['id' => $good->id])}}">编辑</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#SetAttributeModal_{{$good->id}}">SKU配置</a></li>
                                                <li><a href="#" id ="disable_{{$good->id}}" data-id="{{$good->id}}" data-title="禁用" data-action="disable" class="grid-row-action">禁用</a></li>
                                                @else
                                                    <li><a href="#" id ="enable_{{$good->id}}" data-id="{{$good->id}}" data-title="启用" data-action="enable" class="grid-row-action">启用</a></li>
                                                @endif

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal_{{$good->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <div class="modal fade" id="SetAttributeModal_{{$good->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:80%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel">配置SKU（共{{$good->skus->count()}}个）</h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <table class="table">
                                                            <tr>
                                                                <th class="column-__row_selector__">
                                                                    <input type="checkbox" class="grid-select-all" />&nbsp;</th>
                                                                <th>SKUID</th><th>单品名称</th><th>属性规格</th><th>价格（{{$money_sign}}）</th><th>启用状态</th>
                                                            </tr>
                                                        @foreach($good->skus as $sku)
                                                            <tr>
                                                                <td class="column-__row_selector__">
                                                                    <input type="checkbox" class="grid-row-checkbox" data-id="{{$sku->id}}" />
                                                                </td>
                                                                <td>
                                                                    {{'['. $sku->sku_id .']'}}
                                                                </td>
                                                                <td>
                                                                    {{$good->name}}
                                                                </td>
                                                                <td>
                                                                    @if($sku->s1_name)
                                                                        {{$sku->s1_name}}
                                                                    @endif
                                                                    @if($sku->s2_name)
                                                                        /{{$sku->s2_name}}
                                                                    @endif
                                                                    @if($sku->s3_name)
                                                                        /{{$sku->s3_name}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a href="#"
                                                                       title="设置价格"
                                                                       id="update_sku_price_{{$sku->id}}"
                                                                       data-type="text"
                                                                       data-pk="{{$sku->id}}"
                                                                       data-value="{{$sku->price}}"
                                                                       data-url="/admin/good_skus/{{$sku->id}}/update_price"
                                                                       data-title="设置价格">{{$sku->price}}
                                                                    </a>

                                                                </td>
                                                                <td>
                                                                    @if($sku->disabled_at)
                                                                        <span style="color: red">已停用</span>
                                                                    @else
                                                                        <span style="color: green">启用中</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" id="disable_list_{{$sku->id}}" data-action="disable">禁用</button>
                                                        <button type="button" class="btn btn-primary" id="enable_list_{{$sku->id}}" data-action="enable">启用</button>
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
                            {{$goods->appends($search)->links()}}
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
                            url: '/admin/goods/' +id,
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


        //隐藏sku
        $("button[id*=disable_list_], button[id*=enable_list_]").click(function(){
            var action = $(this).data('action');
            var title = action == 'disable' ? '禁用' : '启用';
            var sku_ids = $.admin.grid.selected();

            if($.admin.grid.selected().length == 0){
                swal('请先选择一条数据','','error');
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
                            url: '/admin/good_skus/update_disabled_at',
                            data: {
                                _method:'put',
                                _token:"{{csrf_token()}}",
                                action : action,
                                sku_ids:sku_ids,
                            },
                            success: function (data,id) {
                                //异步修改数据
                                // console.log(data);
                                resolve(data,id);
                            }
                        });
                    });
                }
            }).then(function(data,id) {
                console.log(data,id);
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


        $(".grid-per-pager").on('change', function(e){
            $("#select_per_page").val($(this).val());
            $("#fm").submit();
        })

        //加备注
        $("a[id*='update_sku_price_']").editable({
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
                    // location.reload();
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

        //选择产品
        $("#product_id").select2({
            language: {
                inputTooShort: function () {
                    return "请输入产品关键字";
                }
            },
            "allowClear": true,
            "placeholder": {"id": "", "text": "请选择"},
            ajax: {
                url: "{{$erp_api_domain}}/api/product",
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        keywords: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data, params) {
                    console.log(data, params);
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.page * 30) < data.count
                        }
                    };
                },
                cache: true
            },
            // placeholder: 'Search for a repository',
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo (repo) {
            console.log(repo);
            if (repo.loading) {
                return repo.text;
            }

            var erp_api_domain = "{{$erp_api_domain}}";

            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'>" +
                "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + erp_api_domain + repo.product_image + "' /></div>" +
                // "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.product_name);

            return $container;
        }
        function formatRepoSelection (repo) {
            return repo.product_name;
        }



    </script>

    <script data-exec-on-popstate>

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

            console.log($.admin.grid.selected());

            if (selected > 0) {
                $('.grid-select-all-btn').show();
            } else {
                $('.grid-select-all-btn').hide();
            }

        });

        $('.grid-select-all').iCheck({checkboxClass:'icheckbox_minimal-blue'});
        $('.grid-select-all').on('ifChanged', function(event) {
            if (this.checked) {
                $('.grid-row-checkbox:visible').iCheck('check');
            } else {
                $('.grid-row-checkbox:visible').iCheck('uncheck');
            }
        }).on('ifClicked', function () {
            if (this.checked) {
                $.admin.grid.selects = {};
            } else {
                $('.grid-row-checkbox:visible').each(function () {
                    var id = $(this).data('id');
                    console.log('id=' + id);
                    $.admin.grid.select(id);
                });
            }
        });

        $('div[id*=SetAttributeModal_]').on('hidden.bs.modal', function () {
            // 执行一些动作...
            $.admin.grid.selects = {};
        })


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
