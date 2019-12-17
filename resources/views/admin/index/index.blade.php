@extends('admin.layout')
@section('content')
    <section class="content-header">
        <h1>
            首页
        </h1>
    </section>
    <section class="content">


        {{-- <div  class="box box-primary box-solid" style="width: 90%">
            <div class="box-header with-border">
                <h3 class="box-title">全局配置</h3>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">

                <table class="table">
                    <tr>
                        <td>当前地区 : {{$global_area}} <a href="{{route('config.index')}}">&nbsp;&nbsp;[去设置]</a></td>
                        <td>货币符号 : {{$money_sign}} <a href="{{route('config.index')}}">&nbsp;&nbsp;[去设置]</a></td>
                        <td>语言类型 : {{$global_lang}} <a href="{{route('config.index')}}">&nbsp;&nbsp;[去设置]</a></td>
                    </tr>
                </table>
            </div><!-- /.box-body -->
        </div> --}}

        <div  class="box box-info box-solid" style="width: 90%">
            <div class="box-header with-border">
                <h3 class="box-title">统计信息</h3>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">

                <table class="table">
                    <tr>
                        <td>总商品数 : <a href="{{route('goods.index')}}">{{$goods_count}} &nbsp;&nbsp;&nbsp;[发布商品]</a></td>
                        <td>总订单数 : <a href="{{route('good_orders.index')}}">{{$orders_count}} &nbsp;&nbsp;&nbsp;[查看订单]</a></td>
                        <td>用户数 : <a href="{{route('admin.auth.users.index')}}">{{$admin_users_count}} &nbsp;&nbsp;&nbsp;[管理用户]</a></td>
                    </tr>

                </table>
            </div><!-- /.box-body -->
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

        //验证表单
        $("div[id*=EditModal_], #add").find('form .submit').click(function(){
            var tb = $(this).parents('form').find('.table');
            console.log(tb);
            var submit = true;
            tb.find('input').each(function(){
                if($(this).val() == ''){
                    submit = false;
                    $(this).parent('td').find('.error_tip').remove();
                    $(this).parent('td').append("<span class=\"error_tip\" style=\"color: red\"><br />该项必填</span>");
                }else{
                    $(this).parent('td').find('.error_tip').remove();
                }
            })

            if(submit){
                $(this).parents('form').submit();
            }
        })

    </script>
@endsection
