<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">编辑轮播图</h4>
</div>
<!-- form start -->
<form action="{{route('slides.update',['id' => $detail->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container>

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


            <div class="form-group ">

                <label for="sort" class="col-sm-2 asterisk control-label">排序</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="sort" name="sort" value="{{$detail->sort}}" class="form-control sort" placeholder="数字越大优先展示" required="1" />

                    </div>

                </div>
            </div>

            <div class="form-group  ">

                <label for="slide_image_file" class="col-sm-2 asterisk control-label">封面主图</label>

                <div class="col-sm-8">

                    <input type="file" class="form-control slide_image_file" name="slide_image_file" />

                </div>
            </div>

            <div class="form-group ">

                <label for="good_id" class="col-sm-2 asterisk control-label">绑定商品 @if($detail->good->name)(已绑定单品：{{$detail->good->name}}) @endif</label>
                <div class="col-sm-8">

                    <select style="width: 280px;" class="form-control good_id" name="good_id" value="{{$detail->good_id}}"  required="1">

                    </select>

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

            $(".status").select2({
                placeholder: {"id":"","text":"\u9009\u62e9"},
                "allowClear":true
            });

            $(".slide_image_file").fileinput({
                "initialPreview": [
                    '{{ $detail->image_url}}',
                ],
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
                "fileActionSettings": {"showRemove": false, "showDrag": false},
                "msgPlaceholder": "请选择图片",
                "allowedFileTypes": ["image"],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            // $('.main_image_url').on('fileremoved', function(event, id, index) {
            //     console.log('id = ' + id + ', index = ' + index);
            // });

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

        })
    </script>
