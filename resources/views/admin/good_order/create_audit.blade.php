<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">审核订单</h4>
</div>
<!-- form start -->
<form action="{{route('good_orders.audit',['id' => $good_order_id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
    <div class="box-body">

        <div class="fields-group">

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

            <div class="form-group">
                <label for="title" class="col-sm-2 asterisk control-label">填写审核信息</label>
                <div class="col-sm-6">
                    <div><textarea cols="30" rows="3" name="remark" required="1"></textarea></div>
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

            $(".status").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});
            
            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
            });


        });
    </script>
