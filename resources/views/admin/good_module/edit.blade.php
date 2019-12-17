<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">编辑模块</h4>
</div>
<!-- form start -->
<form action="{{route('good_modules.update',['id' => $detail->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container>

    <div class="box-body">

        <div class="fields-group">

        <div class="form-group ">

                <label for="country_id" class="col-sm-2 asterisk control-label">所属国家</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <select class="form-control status" name="country_id" required="1">
                            <option></option>
                            @foreach($country_list as $key=>$country)
                                <option value="{{$key}}" @if($detail->country_id == $key) selected @endif>{{$country['name']}}</option>
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

                        <input type="text" id="name" name="name" value="{{$detail->name}}" class="form-control name" placeholder="输入模块名称" required="1" />

                    </div>


                </div>
            </div>

            <div class="form-group  ">

                <label for="show_name" class="col-sm-2 asterisk control-label">展示名称</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="show_name" name="show_name" value="{{$detail->show_name}}" class="form-control show_name" placeholder="输入展示名称" required="1" />

                    </div>


                </div>
            </div>

            <div class="form-group ">

                <label for="sort" class="col-sm-2 asterisk control-label">排序</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="sort" name="sort" value="{{$detail->sort}}" class="form-control sort" placeholder="数字越大优先展示" required="1" />

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
                            <table class="table table-bordered table-responsive-md table-striped text-center" id="list_table">
                                <thead>
                                <tr>
                                    <th class="text-center">展示图片</th>
                                    <th class="text-center">绑定商品</th>
                                    {{--<th class="text-center">操作</th>--}}
                                </tr>
                                </thead>
                                <tbody>

                                {{--<tr>--}}
                                    {{--<td style="width:30%">--}}
                                        {{--<input type="file" class="form-control" name="list[][image_file]" />--}}
                                    {{--</td>--}}
                                    {{--<td style="width:30%;">--}}


                                        {{--<select class="form-control" name="list[][good_id]" required="1">--}}
                                            {{--<option>123</option>--}}
                                        {{--</select>--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<input type="text" class="form-control" value="123" name="list[][id]" required="1"/>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}

                                @foreach($detail->good_module_images as $key=>$good_module_image)
                                <tr>
                                    <td style="width:30%">
                                        <input type="file" class="form-control image_file_{{$good_module_image->id}}" name="list[{{$key}}][image_file]" />
                                    </td>
                                    <td style="width:30%;">

                                        <span>已绑单品:{{$good_module_image->good->name}}</span><br />
                                        <select style="width:280px;" class="form-control good_id_{{$good_module_image->good->id}}" name="list[{{$key}}][good_id]" required="1">
                                            <option value="{{$good_module_image->good->id}}" selected="selected">{{$good_module_image->good->name}}</option>
                                        </select>

                                        <input type="hidden" class="form-control good_module_image_{{$good_module_image->id}}" value="{{$good_module_image->id}}" name="list[{{$key}}][id]" />
                                    </td>
                                    {{--<td>--}}
                                        {{--<span class="table-remove">--}}
                                            {{--<button type="button" class="btn btn-danger remove btn-rounded btn-sm my-0">删除</button>--}}
                                        {{--</span>--}}
                                    {{--</td>--}}
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Editable table -->
            </div>


        </div>


        </div>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">

        <div class="col-md-2">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input type="hidden" name="_method" value="put" />
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

    <script>
        $(function () {

            @foreach($detail->good_module_images as $good_module_image)

            $(".image_file_{{$good_module_image->id}}").fileinput({
                "initialPreview": [
                    "{{$good_module_image->image_url}}"
                ],
                "overwriteInitial": true,
                "initialPreviewAsData": true,
                "browseLabel": "\u6d4f\u89c8",
                "cancelLabel": "\u53d6\u6d88",
                "showRemove": false,
                "showUpload": false,
                "showCancel": false,
                "dropZoneEnabled": false,
                "fileActionSettings": {"showRemove": false, "showDrag": false},
                "msgPlaceholder": "请选择图片",
                "allowedFileTypes": ["image"],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            @endforeach

            //选择产品
            $("select[class*=good_id_]").select2({
            // $(".good_id_29").select2({
                language: {
                    inputTooShort: function () {
                        return "请输入单品名称关键字";
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
                    "<img class='img-thumbnail' width=\"60px\" height=\"60px\"  src='" + repo.main_image_url + "' /></div>" +
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

            // curr_obj.val('888').trigger('change');

            // $(".good_id_29").append('<option value="initial2" selected="selected">initial2</option>');
            // $(".good_id_29").trigger('change');

        })

        $(".status").select2({
            placeholder: {"id":"","text":"\u9009\u62e9"},
            "allowClear":true
        });
    </script>
