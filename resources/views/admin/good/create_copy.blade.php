<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">复制单品</h4>
</div>
<form action="{{route('goods.store_copy',['id' => $good_id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

    <div class="modal-body">
        <div class="box-body">

            <div class="fields-group">

                <div class="form-group  ">

                    <label for="name" class="col-sm-2 asterisk control-label">单品名</label>

                    <div class="col-sm-6">

                        <div class="input-group">

                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                            <input type="text" id="name" name="name" class="form-control name" placeholder="输入 单品名" required="1" />

                        </div>
                    </div>
                </div>

                <div class="form-group  ">

                    <label for="admin_user_id" class="col-sm-2 asterisk control-label">所属人</label>

                    <div class="col-sm-8">

                        <div class="input-group">
                            <select class="form-control status" name="admin_user_id" required="1">
                                <option></option>
                                @foreach($admin_users as $key=>$admin_user)
                                    <option value="{{$key}}">{{$admin_user}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>
<script>
    $(".status").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});
</script>