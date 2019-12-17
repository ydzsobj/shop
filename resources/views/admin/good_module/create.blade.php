@extends('admin.layout')
@section('content')

    <section class="content-header">
        <h1>
            添加模块
        </h1>

        <!-- breadcrumb start -->

        <!-- breadcrumb end -->

    </section>

    <section class="content">

        {{--<style>--}}
            {{--.good_id{width:300px;}--}}
        {{--</style>--}}

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row"><div class="col-md-12"><div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">创建</h3>
                        <div class="box-tools">
                            <div class="btn-group pull-right" style="margin-right: 5px">
                                <a href="{{route('good_modules.index')}}" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('good_modules.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                        <div class="box-body">

                            <div class="fields-group">

                            <div class="form-group ">

                                    <label for="country_id" class="col-sm-2 asterisk control-label">所属国家</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <select class="form-control status" name="country_id" required="1">
                                                <option></option>
                                                @foreach($country_list as $key=>$country)
                                                    <option value="{{$key}}">{{$country['name']}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="name" class="col-sm-2 asterisk control-label">名称</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="name" name="name" value="" class="form-control name" placeholder="输入模块名称" required="1" />

                                        </div>


                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="show_name" class="col-sm-2 asterisk control-label">展示名称</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="show_name" name="show_name" value="" class="form-control show_name" placeholder="输入模块展示名称" required="1" />

                                        </div>


                                    </div>
                                </div>

                                <div class="form-group ">

                                    <label for="sort" class="col-sm-2 asterisk control-label">排序</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="sort" name="sort" value="" class="form-control sort" placeholder="数字越大优先展示" required="1" />

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">

                                    <!-- Editable table -->
                                    <div class="card">
                                        <h3 class="card-header text-center font-weight-bold text-uppercase py-4">绑定商品(宽高参考：第一张:206*214,其它206*107)</h3>
                                        <div class="card-body">
                                            <div id="table" class="table-editable">
                                                {{--<span class="table-add mb-3 mr-2" style="margin-left: 100px;">--}}
                                                    {{--<a href="#" class="text-success">--}}
                                                        {{--<i class="fa fa-plus" aria-hidden="true"></i>--}}
                                                    {{--</a>--}}
                                                {{--</span>--}}
                                        <table class="table text-center" id="list_table">
                                            <thead>
                                            <tr>
                                                <th class="text-center">展示图片({!! $upload_config['image_tips'] !!})</th>
                                                <th class="text-center">绑定商品</th>
                                                {{--<th class="text-center">操作</th>--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width:30%">
                                                        <input type="file" class="form-control " name="list[][image_file]" required="1" />
                                                    </td>
                                                    <td>
                                                        <select class="form-control good_id" name="list[][good_id]" required="1">
                                                            {{--<option>123</option>--}}
                                                        </select>
                                                    </td>
                                                    {{--<td>--}}
                                                    {{--<span class="table-remove">--}}
                                                        {{--<button type="button" class="btn btn-danger remove btn-rounded btn-sm my-0">删除</button>--}}
                                                    {{--</span>--}}
                                                    {{--</td>--}}
                                                </tr>

                                                <tr>
                                                    <td style="width:30%">
                                                        <input type="file" class="form-control" name="list[][image_file]" required="1" />
                                                    </td>
                                                    <td class="">
                                                        <select class="form-control good_id" name="list[][good_id]" required="1" >
                                                            {{--<option>49</option>--}}
                                                        </select>
                                                    </td>
                                                    {{--<td>--}}
                                                    {{--<span class="table-remove">--}}
                                                        {{--<button type="button" class="btn btn-danger remove btn-rounded btn-sm my-0">删除</button>--}}
                                                    {{--</span>--}}
                                                    {{--</td>--}}
                                                </tr>
                                                <tr>
                                                    <td style="width:30%">
                                                        <input type="file" class="form-control" name="list[][image_file]" required="1" />
                                                    </td>
                                                    <td>
                                                        <select class="form-control good_id" name="list[][good_id]" required="1" >

                                                        </select>
                                                    </td>
                                                    {{--<td>--}}
                                                    {{--<span class="table-remove">--}}
                                                        {{--<button type="button" class="btn btn-danger remove btn-rounded btn-sm my-0">删除</button>--}}
                                                    {{--</span>--}}
                                                    {{--</td>--}}
                                                </tr>
                                            </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                                                <!-- Editable table -->
                                </div>


                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">

                            <div class="col-md-2">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                            </div>

                            <div class="col-md-8">

                                <div class="btn-group pull-right">
                                    <button type="submit" class="btn btn-primary">提交</button>
                                </div>



                                <div class="btn-group pull-left">
                                    <button type="reset" class="btn btn-warning">重置</button>
                                </div>
                            </div>
                        </div>


                        <!-- /.box-footer -->
                    </form>
                </div>

            </div></div>

    </section>

@endsection


@section('script')

    <script>
        $(function () {

            init();

            $(".table-add").click(function(){
                var clone_tr = $("#list_table tbody tr").eq(0).clone();
                $("#list_table tbody").append(clone_tr);
                $(".remove").click(function () {
                    $(this).parents('tr').remove();
                })

                // init();
            })

            //删除
            $(".remove").click(function () {
                $(this).parents('tr').remove();
            })

            $(".single_select").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});

            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
            });


            function init(){

                $("input[type='file']").fileinput({
                    "overwriteInitial": true,
                    "initialPreviewAsData": true,
                    "browseLabel": "\u6d4f\u89c8",
                    "cancelLabel": "\u53d6\u6d88",
                    "showRemove": false,
                    "showUpload": false,
                    "showCancel": false,
                    "dropZoneEnabled": false,
                    "fileActionSettings": {"showRemove": true, "showDrag": false},
                    "msgPlaceholder": "请选择图片",
                    "allowedFileTypes": ["image"],
                    "maxFileSize": "{{$upload_config['image_max']}}",
                    "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
                });

                //选择产品
                $(".good_id").select2({
                    language: {
                        inputTooShort: function () {
                            return "请输入单品名关键字";
                        }
                    },
                    "allowClear": true,
                    "placeholder": {"id": "", "text": "请选择"},
                    ajax: {
                        url: "/admin/search_goods",
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

                    var $container = $(
                        "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__avatar'>" +
                        "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + repo.main_image_url + "' /></div>" +
                        // "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'></div>" +
                        "</div>"
                    );

                    $container.find(".select2-result-repository__title").text(repo.name);
                    // $container.find(".select2-result-repository__description").text(repo.description);
                    // $container.find(".select2-result-repository__forks").append(repo.forks_count + " Forks");
                    // $container.find(".select2-result-repository__stargazers").append(repo.stargazers_count + " Stars");
                    // $container.find(".select2-result-repository__watchers").append(repo.watchers_count + " Watchers");

                    return $container;
                }
                function formatRepoSelection (repo) {
                    return repo.name;
                }
            }



        });

        $(".status").select2({
            placeholder: {"id":"","text":"\u9009\u62e9"},
            "allowClear":true
        });
    </script>

@endsection
