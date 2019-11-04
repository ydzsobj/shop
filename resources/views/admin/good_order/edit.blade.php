<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">编辑订单</h4>
</div>

<form action="{{route('good_orders.update',['id' => $detail->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

<table class="table" style="margin-left: 150px;width: 80%;">
    <tr><td>订单编号</td><td>{{$detail->sn}}</td></tr>
    <tr><td>订单总金额</td><td>{{ $money_sign . $detail->price}}</td></tr>
    <tr><td>支付方式</td><td>{{array_get($pay_types, $detail->pay_type_id, '')}}</td></tr>
    <tr><td>订单状态</td><td>{{array_get($status, $detail->status, '')}}</td></tr>
    <tr><td>下单时间</td><td>{{$detail->created_at}}</td></tr>
    <tr><td>商品信息</td>
        <td>
            <table class="table">
                <tr><th>单品名称</th><th>数量</th><th>SKU编码</th><th>属性</th></tr>
                @foreach($order_skus as $key=>$order_sku)
                    @php($sku = $order_sku->sku_info)
                    <tr>
                        <td>{{$sku->good->name}}</td>
                        <td>{{$order_sku->sku_nums}}</td>
                        <td>{{ $sku->sku_id }}</td>
                        <td>
                                <select class="form-control status" style="width:160px;" name="sku_attr_values[{{ $order_sku->id }}]">
                                    <option></option>
                                    @foreach($order_sku->sku_list as $key=>$sku_attr_value_name)
                                        <option value="{{$key}}" @if($order_sku->sku_id == $key) selected @endif>{{$sku_attr_value_name}}</option>
                                    @endforeach

                                </select>
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>

</table>
<!-- form start -->


    <div class="box-body">

        <div class="fields-group">

            <div class="form-group  ">

                <label for="receiver_name" class="col-sm-2 asterisk control-label">收货人姓名</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="receiver_name" name="receiver_name" value="{{$detail->receiver_name}}" class="form-control receiver_name" placeholder="" required="1" />

                    </div>
                </div>
            </div>

            <div class="form-group  ">

                <label for="receiver_phone" class="col-sm-2 asterisk control-label">收货人电话</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="receiver_phone" name="receiver_phone" value="{{$detail->receiver_phone}}" class="form-control receiver_phone" placeholder="" required="1" />

                    </div>
                </div>
            </div>

            <div class="form-group">

                <label for="province" class="col-sm-2 asterisk control-label">省/市/区</label>

                <div class="col-sm-2">
                    <input type="text" id="province" name="province" value="{{$detail->province}}" class="form-control province" placeholder="" required="1" />
                </div>

                <div class="col-sm-2">
                    <input type="text" id="city" name="city" value="{{$detail->city}}" class="form-control city" placeholder="" required="1" />

                </div>

                <div class="col-sm-2">
                    <input type="text" id="area" name="area" value="{{$detail->area}}" class="form-control area" placeholder=""  required="1" />

                </div>
            </div>

            <div class="form-group">

                <label for="short_address" class="col-sm-2 asterisk control-label">详细地址</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="short_address" name="short_address" value="{{$detail->short_address}}" class="form-control short_address" placeholder="" required="1" />

                    </div>
                </div>
            </div>

            <div class="form-group">

                <label for="postcode" class="col-sm-2 asterisk control-label">邮编</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="postcode" name="postcode" value="{{$detail->postcode}}" class="form-control postcode" placeholder="" required="1" />

                    </div>
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

    <script src="{{asset('js/admin/common.js')}}"></script>
    <script>

        $(function () {

            $(".single_select").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});

            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
            });


        });
    </script>
