@extends('admin.layout')
@section('content')

    <section class="content-header">
        <h1>
            添加轮播图
        </h1>

        <!-- breadcrumb start -->

        <!-- breadcrumb end -->

    </section>

    <section class="content">

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
                    <form action="{{route('slides.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                        <div class="box-body">

                            <div class="fields-group">

                                <div class="form-group ">

                                    <label for="sort" class="col-sm-2 asterisk control-label">排序</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="sort" name="sort" value="" class="form-control sort" placeholder="数字越大优先展示" required="1" />

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="slide_image_file" class="col-sm-2 asterisk control-label">封面主图({!! $upload_config['image_tips'] !!})</label>

                                    <div class="col-sm-8">

                                        <input type="file" class="form-control slide_image_file" name="slide_image_file" required="1" />

                                    </div>
                                </div>

                                <div class="form-group ">

                                    <label for="good_id" class="col-sm-2 asterisk control-label">绑定商品</label>

                                    <div class="col-sm-8">

                                        <select class="form-control good_id" name="good_id" required="1">

                                        </select>

                                    </div>
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

            $(".slide_image_file").fileinput({
                "overwriteInitial": true,
                "initialPreviewAsData": true,
                "browseLabel": "\u6d4f\u89c8",
                "cancelLabel": "\u53d6\u6d88",
                "showRemove": false,
                "showUpload": false,
                "showCancel": false,
                "dropZoneEnabled": false,
                {{--"uploadUrl": '/admin/upload',--}}
                {{--"uploadExtraData": {--}}
                    {{--'_token': '{{csrf_token()}}',--}}
                    {{--'_method': 'post'--}}
                {{--},--}}

                {{--"deleteUrl": "/admin/upload/1",--}}
                {{--"deleteExtraData": {--}}
                    {{--"_token": "{{csrf_token()}}",--}}
                    {{--"_method": "delete"--}}
                {{--},--}}
                "fileActionSettings": {"showRemove": true, "showDrag": false},
                "msgPlaceholder": "请选择图片",
                "allowedFileTypes": ["image"],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            // $('.main_image_url').on('fileremoved', function(event, id, index) {
            //     console.log('id = ' + id + ', index = ' + index);
            // });

            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
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

        });
    </script>

@endsection
