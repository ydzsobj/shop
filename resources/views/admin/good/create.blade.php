@extends('admin.layout')
@section('content')

    <section class="content-header">
        <h1>
            发布商品
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

        <div class="row"><div class="col-md-12"><div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">创建</h3>
                        <div class="box-tools">
                            <div class="btn-group pull-right" style="margin-right: 5px">
                                <a href="{{route('goods.index')}}" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('goods.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                        <div class="box-body">

                            <div class="fields-group">

                            <div class="form-group">

                                    <label for="country_id" class="col-sm-2 asterisk control-label">所属国家</label>

                                    <div class="col-sm-8">

                                        <select class="form-control single_select" style="width: 100%;" name="country_id" required="1" >
                                            <option></option>
                                            @foreach($country_list as $key=>$country)
                                                <option value="{{$key}}">{{$country['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="title" class="col-sm-2 asterisk control-label">单品展示名</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="title" name="title" value="" class="form-control title" placeholder="输入 网页标题名" required="1" />

                                        </div>


                                    </div>
                                </div>
                                <div class="form-group  ">

                                    <label for="name" class="col-sm-2 asterisk control-label">单品名</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="name" name="name" value="" class="form-control name" placeholder="输入 单品名" required="1" />

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="about" class="col-sm-2 control-label">商品简介(<span style="color: red;">最大255个字符</span>)</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <textarea type="text" id="about" name="about" value="" class="form-control about" placeholder="输入 商品简介"></textarea>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  ">

                                    <label for="original_price" class="col-sm-2 asterisk control-label">原价</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="original_price" name="original_price" value="" class="form-control original_price" placeholder="输入 原价" required="1" />

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="price" class="col-sm-2 asterisk control-label">单品价格</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="price" name="price" value="" class="form-control price" placeholder="输入 单品原价" required="1" />

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="product_id" class="col-sm-2 asterisk control-label">选择产品</label>

                                    <div class="col-sm-8">

                                        <select class="form-control" style="width: 100%;" id="product_id" name="product_id" required="1" >
                                            {{--<option></option>--}}
                                            {{--<option value="1" >产品1</option>--}}
                                            {{--<option value="2" >产品2</option>--}}
                                            {{--<option value="3" >产品3</option>--}}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="category_id" class="col-sm-2 asterisk control-label">单品类型</label>

                                    <div class="col-sm-8">

                                        <select class="form-control single_select" style="width: 100%;" name="category_id" required="1" >
                                            <option></option>
                                            @foreach($good_categories as $key=>$category)
                                                <option value="{{$key}}">{{$category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{--<div class="form-group">--}}

                                    {{--<label for="good_module_id" class="col-sm-2 control-label">所属模块</label>--}}

                                    {{--<div class="col-sm-8">--}}

                                        {{--<select class="form-control single_select" style="width: 100%;" name="good_module_id">--}}
                                            {{--<option></option>--}}
                                            {{--@foreach($good_modules as $key=>$module)--}}
                                                {{--<option value="{{$key}}">{{$module}}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group">

                                    <label for="pay_types" class="col-sm-2 asterisk control-label">付款方式</label>

                                    <div class="col-sm-8">

                                        <select class="form-control single_select" style="width: 100%;" name="pay_types[]" multiple="multiple" required="1" >
                                            <option value="pay_arrived" >货到付款</option>
                                            {{--<option value="paypal" >paypal支付</option>--}}
                                        </select>
                                        <input type="hidden" name="pay_types[]" />
                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="fb_pix" class="col-sm-2 control-label">fb像素</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="fb_pix" name="fb_pix" value="" class="form-control fb_pix" placeholder="" />

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="main_image_file" class="col-sm-2 asterisk control-label">封面主图</label>

                                    <div class="col-sm-8">

                                        <input type="file" class="form-control main_image_file" name="main_image_file" required="1" />

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="list_image_files" class="col-sm-2 control-label">轮播图(可选择多张,{!! $upload_config['image_tips'] !!},<span style="color: red;">请保持每张图片大小一致</span>)</label>

                                    <div class="col-sm-8">

                                        <input type="file" id="list_image_files" class="form-control list_image_files" name="list_image_files[]" multiple />

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="main_video_file" class="col-sm-2 control-label">封面视频({!! $upload_config['video_tips'] !!})</label>

                                    <div class="col-sm-8">

                                        <input type="file" class="form-control main_video_file" name="main_video_file" />

                                    </div>
                                </div>


                                <div class="form-group  ">

                                    <label for="show_comment" class="col-sm-2  control-label">是否开启评论模块（默认开启）</label>

                                    <div class="col-sm-8">

                                        <input type="checkbox" name="show_comment" value="1" checked />

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="show_coupon_code" class="col-sm-2  control-label">是否开启优惠码输入（默认关闭）</label>

                                    <div class="col-sm-8">

                                        <input type="checkbox" name="show_coupon_code" />

                                    </div>
                                </div>

                                <div class="form-group  ">

                                    <label for="detail_desc" class="col-sm-2 asterisk control-label">商品描述({!! $upload_config['image_tips'].$upload_config['video_tips'] !!})</label>

                                    <div class="col-sm-8">
                                        <textarea name="detail_desc"></textarea>

                                    </div>
                                </div>

                                {{--<div class="form-group  ">--}}

                                    {{--<label for="size_desc" class="col-sm-2 asterisk control-label">商品规格</label>--}}

                                    {{--<div class="col-sm-8">--}}
                                        {{--<textarea name="size_desc"></textarea>--}}

                                    {{--</div>--}}
                                {{--</div>--}}


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

    <script src="{{admin_asset('/vendor/kindeditor/kindeditor/kindeditor-all.js?version=3.14')}}"></script>
    <script src="{{admin_asset('/vendor/kindeditor/kindeditor/lang/zh-CN.js')}}"></script>
    <script src="{{admin_asset('/vendor/kindeditor/fileupload/jquery.ui.widget.js')}}"></script>
    <script src="{{admin_asset('/vendor/kindeditor/fileupload/jquery.iframe-transport.js')}}"></script>
    <script src="{{admin_asset('/vendor/kindeditor/fileupload/jquery.fileupload.js')}}"></script>

    <script>
        $(function () {

            $(".main_image_file").fileinput({
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
                "allowedFileExtensions": ['jpg','jpeg','png','gif'],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            // $('.main_image_url').on('fileremoved', function(event, id, index) {
            //     console.log('id = ' + id + ', index = ' + index);
            // });

            $("#list_image_files").fileinput({
                "initialPreview" :[],
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "\u6d4f\u89c8",
                "cancelLabel": "\u53d6\u6d88",
                "showRemove": false,
                "showUpload": false,
                "showCancel": false,
                "dropZoneEnabled": true,
                {{--"uploadUrl": '/admin/upload',--}}
                {{--"uploadExtraData": {--}}
                    {{--'_token': '{{csrf_token()}}',--}}
                    {{--'_method': 'post'--}}
                {{--},--}}
                {{--"deleteUrl": "/admin/upload/null",--}}
                {{--"deleteExtraData": {--}}
                    {{--"_token": "{{csrf_token()}}",--}}
                    {{--"_method": "delete"--}}
                {{--},--}}
                "fileActionSettings": {"showRemove": true, "showDrag": false},
                "msgPlaceholder": "\u9009\u62e9\u56fe\u7247",
                "allowedFileExtensions": ['jpg','jpeg','png','gif'],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            $('#list_image_urls').on('fileselectnone', function(event) {
                console.log("Huh! No files were selected.");
                return false;
            });

            $(".main_video_file").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "\u6d4f\u89c8",
                "cancelLabel": "\u53d6\u6d88",
                "showRemove": false,
                "showUpload": false,
                "showCancel": false,
                "dropZoneEnabled": false,
                // "uploadUrl": '/admin/upload',
                        {{--"uploadExtraData": {--}}
                        {{--'_token': '{{csrf_token()}}',--}}
                        {{--'_method': 'post'--}}
                        {{--},--}}
                        {{--"deleteUrl": "/admin/upload/null",--}}
                        {{--"deleteExtraData": {--}}
                        {{--"_token": "{{csrf_token()}}",--}}
                        {{--"_method": "delete"--}}
                        {{--},--}}
                "fileActionSettings": {"showRemove": true, "showDrag": false},
                "msgPlaceholder": "选择视频文件",
                "allowedFileTypes": ["video"],
                "allowedFileExtensions":['mp4','avi'],
                "maxFileSize": "{{$upload_config['video_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            // $('.main_image_url').on('fileselect', function(event, numFiles, label) {
            //     console.log("fileselect");
            //     $('.main_image_url').fileinput('upload');
            // })
            //
            // $('.main_image_url').on('fileuploaded', function(event, previewId, index, fileId) {
            //     console.log('File uploaded', previewId, index, fileId);
            // });

            $(".single_select").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});

            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
            });

            $("input[name='show_comment'],input[name='show_coupon_code']").bootstrapSwitch({
                onText : '开',
                offText : '关',
                onSwitchChange: function (event, state) {
                    console.log(state);
                    if (state == true) {
                        $(this).val("1");
                        console.log($(this).val());
                    } else {
                        $(this).val("0");
                    }
                }
            });

            //编辑器
            KindEditor.create('textarea[name="detail_desc"],textarea[name="size_desc"]',{
                width : '100%',   //宽度
                height : '320px',   //高度
                resizeType : '0',   //禁止拖动
                allowFileManager : true,   //允许对上传图片进行管理
                uploadJson :   '/yxx/kindeditor/upload', //文件上传地址
                fileManagerJson : '/yxx/kindeditor/manager',   //文件管理地址
                deleteUrl  : '/yxx/kindeditor/delete', //文件删除地址
                urlType : 'domain',   //带域名的路径
                extraFileUploadParams: {
                    '_token':"{{csrf_token()}}"
                },
                formatUploadUrl: true,   //自动格式化上传后的URL
                autoHeightMode: false,   //开启自动高度模式
                allowImageRemote: false,
                afterBlur: function () { this.sync(); } ,  //同步编辑器数据

                // items: [
                //     'source', '|', 'undo', 'redo', '|', 'preview', 'image', 'multiimage', 'media',
                //     ],

                imageSizeLimit : '{{$upload_config['image_max']}}K', //批量上传图片单张最大容量
                imageUploadLimit : 20 //批量上传图片同时上传最多个数
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
                    url: "/admin/select_products",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            keywords: params.term, // search term
                            page: params.page,
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        console.log(data, params);
                        params.page = params.page || 1;

                        return {
                            results: data.data.data,
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

                // var erp_api_domain = "{{$erp_api_domain}}";

                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__avatar'>" +
                    // "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + erp_api_domain + repo.product_image + "' /></div>" +
                    // "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    // "<div class='select2-result-repository__description'></div>" +
                    // "<div class='select2-result-repository__statistics'>" +
                    // "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
                    // "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
                    // "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
                    // "</div>" +
                    // "</div>" +
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
