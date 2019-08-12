@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            产品库
        </h1>
    </section>
    <section class="content">

        <style>
            .operate_account{
                cursor:pointer;
            }
        </style>
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
        <div class="row"><div class="col-md-12"><div class="box">

                    <div class="box-header with-border " id="filter-box">
                        <form action="{{route('products.index')}}" class="form-horizontal" method="get" id="fm">
                            <div class="row">
                                <div>
                                    <div class="box-body">
                                        <div class="fields-group">
                                            <div class="form-group">

                                                <label class="col-sm-1 control-label">
                                                    搜索
                                                </label>
                                                <div class="col-sm-5">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>

                                                        <input type="text" class="form-control keywords" placeholder="产品名称" name="keywords" value="@if($search['keywords'])  {{$search['keywords']}} @endif">
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-8">
                                            <input type="hidden" name="per_page" id="select_per_page" value="@if($search['per_page']){{$search['per_page']}}@endif" />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="btn-group pull-left">
                                                <button class="btn btn-info submit btn-sm"><i
                                                            class="fa fa-search"></i>&nbsp;&nbsp;搜索</button>
                                            </div>
                                            <div class="btn-group pull-left " style="margin-left: 10px;">
                                                <a href="{{route('products.index')}}" class="btn btn-default btn-sm"><i
                                                            class="fa fa-undo"></i>&nbsp;&nbsp;重置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>

                    <div  class="box box-default box-solid">
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                        <div class="box-body" style="display: block;">
                            共 {{$products->count}}条
                        </div><!-- /.box-body -->
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    主图
                                </th>
                                <th>
                                    名称
                                </th>
                                <th>
                                    价格
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($products->data as $product)
                                <tr>
                                    <td>{{$product->id}}</td>
                                    <td style="width: 80px;">
                                        <div style="width: 70px;"
                                             title="{{$product->product_name}}"
                                             data-container="body"
                                             data-toggle="popover"
                                             data-placement="right"
                                             data-trigger="hover"
                                             data-html="true"
                                             data-content="<img src='{{$erp_api_domain}}{{$product->product_image}}' class='img-thumbnail'  />"
                                        >
                                            <img src='{{$erp_api_domain}}{{$product->product_image}}' class='img-thumbnail' />
                                        </div>
                                    </td>
                                    <td>{{$product->product_name}}</td>
                                    <td>{{$product->product_price}}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                    </div>


                    <div class="box-footer clearfix ">

                        <div class="pull-right">
                            <ul class="pagination">
                                @for($i=1; $i<=$pages;$i++)
                                <li @if(intval($search['page']) == $i)class="active" @endif><a href="{{route('products.index',['page' => $i,'keywords' => $search['keywords'], 'per_page' => $search['per_page'] ])}}" >{{$i}}</a></li>
                                @endfor

                            </ul>
                        </div>

                        <label class="control-label pull-right" style="margin-right: 10px;margin-top: 20px; font-weight: 100;">

                            <small>显示</small>&nbsp;
                            <select class="input-sm grid-per-pager" name="per-page">
                                @foreach(['10','20','30','50','100'] as $per_page)
                                    <option value="{{$per_page}}" @if($search['per_page'] == $per_page) selected @endif >{{$per_page}}</option>
                                @endforeach

                            </select>
                            &nbsp;<small>条</small>
                        </label>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </section>

@endsection


@section('script')

    <script>

        $(function () {
            $('#created_at_start').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN"});
            $('#created_at_end').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN","useCurrent":false});
            $("#created_at_start").on("dp.change", function (e) {
                $('#created_at_end').data("DateTimePicker").minDate(e.date);
            });
            $("#created_at_end").on("dp.change", function (e) {
                $('#created_at_start').data("DateTimePicker").maxDate(e.date);
            });

            $(".status").select2({
                placeholder: {"id":"","text":"\u9009\u62e9"},
                "allowClear":true
            });
        });



        $(".grid-per-pager").on('change', function(e){
            $("#select_per_page").val($(this).val());
            $("#fm").submit();
        })



    </script>


@endsection
