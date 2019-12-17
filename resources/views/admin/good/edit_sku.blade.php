<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">配置SKU（共{{$good->skus->count()}}个）</h4>
</div>


<div class="btn-group grid-select-all-btn" style="display:none;margin: 5px;">
    <a class="btn btn-sm btn-default"><span class="hidden-xs selected"></span></a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="#" class="" data-toggle="modal" data-target="#SetPriceModalBatch_{{$good->id}}">设置单价</a></li>
        <li><a href="#" class="" id="disable_list" data-action="disable">禁用</a></li>
        <li><a href="#" class="" id="enable_list" data-action="enable">启用</a></li>
    </ul>
</div>




@if($good->skus->count() >0)
    <div class="modal-body">

        <table class="table">
            <tr>
                <th class="column-__row_selector__">
                    <input type="checkbox" class="grid-select-all" />&nbsp;</th>
                <th>SKUID</th><th>单品名称</th><th>属性规格</th><th>价格  {{ collect(array_get($country_list, $good->country_id))->get('money_sign') }}</th><th>启用状态</th>
            </tr>

            @foreach($good->skus as $sku)
                <tr>
                    <td class="column-__row_selector__">
                        <input type="checkbox" class="grid-row-checkbox" data-id="{{$sku->id}}" />
                    </td>
                    <td>
                        {{'['. $sku->sku_id .']'}}
                    </td>
                    <td>
                        {{$good->name}}
                    </td>
                    <td>
                        @if($sku->s1_name)
                            {{$sku->s1_name}}
                        @endif
                        @if($sku->s2_name)
                            /{{$sku->s2_name}}
                        @endif
                        @if($sku->s3_name)
                            /{{$sku->s3_name}}
                        @endif
                    </td>
                    <td>
                        <a href="#"
                           title="设置价格"
                           id="update_sku_price_{{$sku->id}}"
                           data-type="text"
                           data-pk="{{$sku->id}}"
                           data-value="{{$sku->price}}"
                           data-url="/admin/good_skus/{{$sku->id}}/update_price"
                           data-title="设置价格">{{$sku->price}}
                        </a>

                    </td>
                    <td>
                        @if($sku->disabled_at)
                            <span style="color: red">已停用</span>
                        @else
                            <span style="color: green">启用中</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>
    </div>
@endif

<div class="modal fade" id="SetPriceModalBatch_{{$good->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog" style="width:50%;">
<form action="{{route('good_skus.batch_update_price')}}" class="form-horizontal" method="post" id="fm_batch_update_price">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">批量设置单价</h4>
</div>
<div class="modal-body">

<div class="row">
<div class="form-group">
<label for="price" class="col-sm-2 asterisk control-label">价格</label>
<div class="col-sm-6">
<input class="form-control price" name="price" id="set_price" required="1"/>
</div>
</div>
</div>
    <div class="modal-footer">
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="sku_ids" id="sku_ids" />
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <button type="button" class="btn btn-primary" id="batch_update_price_submit">提交</button>
</div>
</div>
</div><!-- /.modal-content -->
</form>
</div><!-- /.modal -->
</div>

<script src="{{asset('js/admin/common.js')}}"></script>
<script>
    //批量设置价格
    $("button[id=batch_update_price_submit]").click(function () {

        var sku_ids = $.admin.grid.selected();
        if($.admin.grid.selected().length == 0){
            swal('请先选择一条数据','','error');
            return false;
        }
        $("#sku_ids").val(sku_ids);
        var price = $("#set_price").val();
        if(!price){
            swal('价格必填','','error');
            return false;
        }
        $("#fm_batch_update_price").submit();

    })

    //隐藏sku
    $("a[id=disable_list], a[id=enable_list]").click(function(){
        var action = $(this).data('action');
        var title = action == 'disable' ? '禁用' : '启用';
        var sku_ids = $.admin.grid.selected();

        if($.admin.grid.selected().length == 0){
            swal('请先选择一条数据','','error');
            return false;
        }

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
                        url: '/admin/good_skus/update_disabled_at',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _method:'put',
                            action : action,
                            sku_ids:sku_ids,
                        },
                        success: function (data,id) {
                            //异步修改数据
                            // console.log(data);
                            resolve(data,id);
                        }
                    });
                });
            }
        }).then(function(data,id) {
            console.log(data,id);
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

    //fileinput
    $("a[id*='update_sku_price_']").editable({
        value :'',
        params: function(params) {
            //originally params contain pk, name and value
            params._method = 'put';
            params._token = $('meta[name="csrf-token"]').attr('content');
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

    //关闭model
    $('div[id*=SetPriceModalBatch_]').on('hidden.bs.modal', function () {
        // 执行一些动作...
        console.log('close 2');
        window.location.reload();
    })
</script>
