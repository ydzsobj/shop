<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">配置属性<span style="color:red;">(目前支持第一个属性自定义预览图片)</span></h4>
</div>

@if($good->good_attributes->count() >0)
<form action="{{route('good_attribute_values.update_thumb_url')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
<div class="modal-body">
        <table class="table">
            <tr>
                <th colspan="2">属性</th>
                <th>属性值</th>
            </tr>

            @foreach($good->good_attributes as $key=>$attribute)
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
                                data-url="/admin/good_attributes/{{$attribute->id}}/update_show_name"
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
                                                data-url="/admin/good_attribute_values/{{$attribute_value->id}}/update_show_name"
                                                data-title="设置展示名">{{$attribute_value->show_name}}
                                        </a>
                                    </td>
                                    @if($key == 0)
                                    <td>
                                        @if($attribute_value->thumb_url)
                                        <input type="file" name="attr_images[{{ $attribute_value->id }}]" />
                                        <div style="width: 70px;"
                                            title="预览图"
                                            data-container="body"
                                            data-toggle="popover"
                                            data-placement="right"
                                            data-trigger="hover"
                                            data-html="true"
                                            data-content="<img src='{{$attribute_value->thumb_url}}' class='img-thumbnail'  />"
                                            >
                                                <img src='{{$attribute_value->thumb_url}}' class='img-thumbnail' />
                                        </div>
                                        @else
                                            <input type="file" name="attr_images[{{ $attribute_value->id }}]" />
                                        @endif

                                    </td>
                                    @endif

                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="box-footer">

            <div class="col-md-2">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="_method" value="put"   />
                <input type="hidden" name="product_id" value="{{ $good->product_id }}"   />
            </div>

            <div class="col-md-8">

                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>

    </form>

@endif

<script src="{{asset('js/admin/common.js')}}"></script>
<script>

    $(function () {
        $("[data-toggle='popover']").popover();
    });

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
