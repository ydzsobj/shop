@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            模块管理
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

                        <div class="pull-right">
                            <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                <a href="{{route('good_modules.create')}}" class="btn btn-sm btn-success" title="新增">
                                    <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
                                </a>
                            </div>

                        </div>

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
                                    所属国家
                                </th>

                                <th>
                                    展示位图片
                                </th>

                                <th>
                                    模块名称
                                </th>
                                <th>
                                    展示名称
                                </th>
                                <th>
                                    排序
                                </th>

                                <th>
                                    创建时间
                                </th>

                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($good_modules as $module)
                                <tr>
                                    <td>{{$module->id}}</td>
                                    <td>
                                        {{ collect(array_get($country_list, $module->country_id))->get('name') }}
                                    </td>
                                    <td>
                                        @foreach($module->good_module_images as $good_module_image)
                                        <div class="col-md-2"
                                             title="绑定单品：{{$good_module_image->good->name}}"
                                             data-container="body"
                                             data-toggle="popover"
                                             data-placement="right"
                                             data-trigger="hover"
                                             data-html="true"
                                             data-content="<img src='{{$good_module_image->image_url}}' class='img-thumbnail'  />"
                                        >
                                            <img src='{{$good_module_image->image_url}}' class='img-thumbnail'  style="width:60px;height: 60px;"/>
                                        </div>
                                       @endforeach
                                    </td>
                                    <td>{{$module->name}}</td>
                                    <td>{{$module->show_name}}</td>
                                    <td>{{$module->sort}}</td>
                                    <td>{{$module->created_at}}</td>
                                    <td>

                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                <li><a href="#" data-toggle="modal" data-target="#editModal_{{$module->id}}" data-remote="{{route('good_modules.edit',['id' => $module->id])}}">编辑</a></li>
                                                <li><a href="#" id ="disable_{{$module->id}}" data-id="{{$module->id}}" data-title="删除" data-action="disable" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModal_{{$module->id}}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

                    <!-- /.box-body -->
                </div>
            </div>
        </div>


        <section class="content-header">
            <h1>
                轮播图(上传多张图时 ,请保持每张图片宽高一致)
            </h1>
        </section>
        <div class="row"><div class="col-md-12"><div class="box">

                    <div class="box-header with-border " id="filter-box">

                        <div class="pull-right">
                            <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                <a href="{{route('slides.create')}}" class="btn btn-sm btn-success" title="新增">
                                    <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增轮播图</span>
                                </a>
                            </div>

                        </div>

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
                                    所属国家
                                </th>

                                <th>
                                    图片
                                </th>

                                <th>
                                    绑定单品名
                                </th>

                                <th>
                                    排序
                                </th>

                                <th>
                                    创建时间
                                </th>

                                <th>
                                    操作
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($slides as $slide)
                                <tr>
                                    <td>{{$slide->id}}</td>
                                    <td>{{ collect(array_get($country_list, $slide->country_id))->get('name') }}</td>
                                    <td style="width: 80px;">
                                        <div style="width: 70px;"
                                             title=""
                                             data-container="body"
                                             data-toggle="popover"
                                             data-placement="right"
                                             data-trigger="hover"
                                             data-html="true"
                                             data-content="<img src='{{$slide->image_url}}' class='img-thumbnail' width='260px' height='260px'  />"
                                        >
                                            <img src='{{$slide->image_url}}' class='img-thumbnail' />
                                        </div>
                                    </td>
                                    <td>{{$slide->good->name}}</td>
                                    <td>{{$slide->sort}}</td>
                                    <td>{{$slide->created_at}}</td>
                                    <td>

                                        <div class="grid-dropdown-actions dropdown">
                                            <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 50px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">

                                                <li><a href="#" data-toggle="modal" data-target="#editModalSlide_{{$slide->id}}" data-remote="{{route('slides.edit',['id' => $slide->id])}}">编辑</a></li>
                                                <li><a href="#" id ="slide_disable_{{$slide->id}}" data-id="{{$slide->id}}" data-title="删除" data-action="disable" class="grid-row-action">删除</a></li>

                                            </ul>
                                        </div>

                                        <!-- 模态框（Modal） -->
                                        <div class="modal fade" id="editModalSlide_{{$slide->id}}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" style="width:60%">
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
        $("a[id*=disable_]").click(function(){
            var title = $(this).data('title');
            var id = $(this).data('id');
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
                            url: '/admin/good_modules/' +id,
                            data: {
                                _method:'delete',
                                _token:"{{csrf_token()}}"
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

        //禁用
        $("a[id*=slide_disable_]").click(function(){
            var title = $(this).data('title');
            var id = $(this).data('id');
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
                            url: '/admin/slides/' +id,
                            data: {
                                _method:'delete',
                                _token:"{{csrf_token()}}"
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
