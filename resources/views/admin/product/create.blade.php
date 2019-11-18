@extends('admin.layout')
@section('content')
<link rel="stylesheet" href={{ asset("css/admin/sku_style.css") }} />
<style>
    .sku-type-val>div{
        overflow: hidden;
    }
</style>


    <section class="content-header">
        <h1>
            新增商品
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
                                <a href="{{route('products.index')}}" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('products.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return submit_check();">

                        <div class="box-body">

                            <div class="fields-group">

                                <div class="form-group  ">

                                    <label for="name" class="col-sm-2 asterisk control-label">产品名称</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="name" name="name" value="" class="form-control name" placeholder="输入" required="1" />

                                        </div>
                                    </div>

                                </div>
                                <div class="form-group  ">

                                    <label for="english_name" class="col-sm-2 asterisk control-label">英文名称</label>

                                    <div class="col-sm-8">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="english_name" name="english_name" value="" class="form-control english_name" placeholder="输入" required="1" />

                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="fields-group" style="margin-left:165px;">
                                <div>
                                    <ul class="attr_ul">
                                        @foreach($attributes as $key=>$attr)
                                            <li><label><input type="checkbox" name="product_attr[{{ $attr->id }}]" class="sku_name" value="{{ $attr->name }}"  data-id="{{ $attr->id }}" />{{ $attr->name }}</label></li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="clear"></div>
                                <div class="sku-type-val"></div>

                                <div class="skuTable"></div>

                            </div>

                            <div style="margin-left:180px;">
                                    <span style="color:red;">（此处绑定的SKU编码要与ERP系统保持一致）</span>
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

<script>
    var model_id = null;
    var initSku = null;
</script>

 <script src="{{ asset('js/admin/product/createskutable.js') }}"></script>

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
                console.log(array)
                var attr_values = {!! $format_attr_values !!};
                console.log('attr_values',attr_values);
                $('body').on('change','.sku_name',function(){
                    console.log($(this).prop('checked'))
                    if($(this).prop('checked')){

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

                    skutable()
                })

    </script>

    <script src="{{ asset('js/admin/product/sku_check.js') }}"></script>


@endsection
