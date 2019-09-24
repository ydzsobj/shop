<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">编辑评价</h4>
</div>

<table class="table" style="margin-left: 150px;width: 80%;">
    <tr><td>单品名</td><td>{{$detail->good->name}}</td></tr>
    <tr><td>审核状态</td><td>@if($detail->audited_at)<span style="color: green;">已审核</span>@else <span style="color: red;">未审核</span> @endif</td></tr>
    {{--<tr><td>评价时间</td><td>{{$detail->created_at}}</td></tr>--}}

</table>
<!-- form start -->
<form action="{{route('good_comments.update',['id' => $detail->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

    <div class="box-body">

        <div class="fields-group">

            <div class="form-group">

                <label for="name" class="col-sm-2 asterisk control-label">评价人名称</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="name" name="name" value="{{$detail->name}}" class="form-control name" placeholder="" required="1" />

                    </div>
                </div>
            </div>

            <div class="form-group  ">

                <label for="phone" class="col-sm-2 asterisk control-label">联系电话</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="phone" name="phone" value="{{$detail->show_phone}}" class="form-control phone" placeholder="" required="1" />

                    </div>
                </div>
            </div>


            <div class="form-group">

                <label for="star_scores" class="col-sm-2 asterisk control-label">星级评分</label>

                <div class="col-sm-8">

                    <select class="form-control single_select" style="width: 100%;" name="star_scores" required="1" >
                        <option></option>
                        @foreach($star_scores as $key=>$value)
                            <option value="{{$key}}" @if($key == $detail->star_scores)selected @endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">

                <label for="comment" class="col-sm-2 asterisk control-label">评价内容</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <textarea type="text" id="comment" name="comment" class="form-control comment" placeholder="" required="1">{{$detail->comment}}</textarea>

                    </div>
                </div>
            </div>

            <div class="form-group  ">

                <label for="created_at" class="col-sm-2 asterisk control-label">评价时间</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" class="form-control" id="created_at" placeholder="评价时间" name="created_at" value="{{$detail->created_at}}" required="1" />

                    </div>
                </div>
            </div>

            <div class="form-group">

                <label for="comment_image_files" class="col-sm-2 control-label">晒图(可选择多张)</label>

                <div class="col-sm-8">

                    <input type="file" id="comment_image_files" class="form-control comment_image_files" name="comment_image_files[]" multiple />

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

            $('#created_at').datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh-CN"});

            $("#comment_image_files").fileinput({
                @if($comment_image_urls)
                "initialPreview" :[
                    {!! $comment_image_urls !!}
                ],
                @endif
                "overwriteInitial": true,
                "initialPreviewAsData": true,
                "browseLabel": "\u6d4f\u89c8",
                "cancelLabel": "\u53d6\u6d88",
                "showRemove": false,
                "showUpload": false,
                "showCancel": false,
                "dropZoneEnabled": true,
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
                "fileActionSettings": {"showRemove": false, "showDrag": false},
                "msgPlaceholder": "\u9009\u62e9\u56fe\u7247",
                "allowedFileTypes": ["image"],
                "maxFileSize": "{{$upload_config['image_max']}}",
                "msgSizeTooLarge": "{!! $upload_config['msg'] !!}",
            });

            $(".single_select").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});

        });


    </script>
