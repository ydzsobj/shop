<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">编辑商品</h4>
</div>

<link rel="stylesheet" href={{ asset("css/admin/sku_style.css") }} />
<style>
    .sku-type-val>div{
        overflow: hidden;
    }
</style>

<!-- /.box-header -->
<!-- form start -->
<form action="{{route('products.update',['id' =>  $detail->id ])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

    <div class="box-body">

        <div class="fields-group">

            <div class="form-group  ">

                <label for="name" class="col-sm-2 asterisk control-label">产品名称</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="name" name="name" value="{{ $detail->name }}" class="form-control name" placeholder="输入" required="1" />

                    </div>


                </div>
            </div>
            <div class="form-group  ">

                <label for="english_name" class="col-sm-2 asterisk control-label">英文名称</label>

                <div class="col-sm-8">

                    <div class="input-group">

                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                        <input type="text" id="english_name" name="english_name" value="{{ $detail->english_name }}" class="form-control english_name" placeholder="输入" required="1" />

                    </div>
                </div>
            </div>


        </div>

        <div class="fields-group" style="margin-left:120px;">
            <div>
                <ul class="attr_ul">
                    @foreach($attributes as $key=>$attr)
                        <li>
                        <label>
                            <input type="checkbox" name="product_attr[{{ $attr->id }}]" class="sku_name" value="{{ $attr->name }}"  data-id="{{ $attr->id }}"
                            @if(in_array($attr->id, $detail->attrs->pluck('attr_id')->toArray()))
                            checked
                            @endif
                            />
                            {{ $attr->name }}
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="clear"></div>
            <div class="sku-type-val"></div>

            <div class="skuTable"></div>

        </div>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">

        <div class="col-md-2">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input type="hidden" name="_method" value="put"   />
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

 <script src="{{ asset('js/admin/product/createskutable.js') }}"></script>

 <script>
    //  console.log('empty', $('.sku-type-val').html())

     $(".sku_name:visible").each(function(i){
        if($(this).prop('checked')){

            var str='<div class="'+$(this).val()+' ">' +'<ul class="SKU_TYPE">'
                        +'<li is_required="1" propid="4" sku-type-name="'+$(this).val()+'"><em>*</em>' + $(this).val()+'：</li>'
                    +'</ul>';

            var attr_id = $(this).data('id');

            var attr_values = {!! $format_attr_values !!};
            var formart_attr_value_ids = {!! $formart_attr_value_ids !!};
            var data = attr_values[attr_id];

            var ul_str = '<ul class="attr_ul">';
            for(var i=0;i<data.length;i++){
                var checked = formart_attr_value_ids.indexOf(data[i].id) != -1 ? 'checked': '';
                ul_str += '<li><label>' +
                    '<input type="checkbox" class="sku_value" ' + checked +' name="product_attr[' + attr_id +'][' + data[i].id +']" propvalid="' + data[i].id+'" value="' + data[i].name +'" />' + data[i].name +
                    '</label></li>'
            }

            ul_str += '</ul></div>';
            str += ul_str;
            $('.sku-type-val').append(str);

            alreadySetSkuVals = {!! $formart_skus  !!};


        }
     })

     skutable()

 </script>

 <script>
        var trs = $("table tr:not(:first)");
                //声明一个盒子
                var array = [];
                //循环你所要选择的行
                $.each(trs, function (i, val) {
                    var tr = val;
                    var json = { ChannelID: "", txtSortId: 0 }
                    json.ChannelID = $(tr).attr('propvalids')
                    json.txtSortId = $(tr).find("input.setting_sku_price").val()
                    //全加入
                    array.push(json);
                });

                var attr_values = {!! $format_attr_values !!};

                $('.modal:visible').on('change','.sku_name',function(){
                    console.log('888',$(this).parents('.modal').attr('id'), $(this).css('visibility'));
                    if($(this).prop('checked') && $(this).css('visibility') == 'visible'){

                        var str='<div class="'+$(this).val()+' ">' +'<ul class="SKU_TYPE">'
                                    +'<li is_required="1" propid="4" sku-type-name="'+$(this).val()+'"><em>*</em>' + $(this).val()+'：</li>'
                                +'</ul>';

                        var attr_id = $(this).data('id');

                        var data = attr_values[attr_id];

                        var ul_str = '<ul class="attr_ul">';
                        for(var i=0;i<data.length;i++){
                            ul_str += '<li><label>' +
                                '<input type="checkbox" class="sku_value"  name="product_attr[' + attr_id +'][' + data[i].id +']" propvalid="' + data[i].id+'" value="' + data[i].name +'" />' + data[i].name +
                                '</label></li>'
                        }

                        ul_str += '</ul></div>';
                        str += ul_str;
                        $('.sku-type-val').append(str);

                    }else{
                        var a='.'+$(this).val()
                        $(a).remove()
                    }

                    // alreadySetSkuVals = {!! $formart_skus  !!};

                    skutable()
                })



    </script>

