<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">配置属性</h4>
</div>

@if($good->product_attributes->count() >0)
    <div class="modal-body">

        <table class="table">
            <tr>
                <th colspan="2">属性</th>
                <th colspan="1">属性值</th>
            </tr>

            @foreach($good->product_attributes as $attribute)
                <tr>
                    <td>
                        {{$attribute->attr_name}}
                    </td>
                    <td>展示名称：<a href="#"
                                title="设置展示名"
                                id="update_product_attr_name_{{$attribute->id}}"
                                data-type="text"
                                data-pk="{{$attribute->id}}"
                                data-value="{{$attribute->show_name}}"
                                data-url="/admin/product_attributes/{{$attribute->id}}/update_show_name"
                                data-title="设置展示名">{{$attribute->show_name}}
                        </a>
                    </td>
                    <td>
                        <table class="table">
                            @foreach($attribute->attribute_values as $attribute_value)
                                <tr>
                                    <td>{{$attribute_value->attr_value_name}}</td>
                                    <td>展示名称：<a href="#"
                                                title="设置展示名"
                                                id="update_product_attr_value_name_{{$attribute_value->id}}"
                                                data-type="text"
                                                data-pk="{{$attribute_value->id}}"
                                                data-value="{{$attribute_value->show_name}}"
                                                data-url="/admin/product_attribute_values/{{$attribute_value->id}}/update_show_name"
                                                data-title="设置展示名">{{$attribute_value->show_name}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach

        </table>
    </div>

@endif

<script src="{{asset('js/admin/common.js')}}"></script>
<script>
    //fileinput
    $("a[id*='update_product_attr_name_'],a[id*='update_product_attr_value_name_']").editable({
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
</script>