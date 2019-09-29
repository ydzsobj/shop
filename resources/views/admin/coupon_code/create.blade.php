@extends('admin.layout')
@section('content')

    <section class="content-header">
        <h1>
            创建优惠码
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
                        <h3 class="box-title">新增</h3>
                        <div class="box-tools">
                            <div class="btn-group pull-right" style="margin-right: 5px">
                                <a href="{{route('coupon_codes.index')}}" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('coupon_codes.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                        <div class="box-body">

                            <div class="fields-group">

                                <div class="form-group  ">

                                    <label for="code" class="col-sm-2 asterisk control-label">优惠码</label>

                                    <div class="col-sm-4">

                                        <div class="input-group">

                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                            <input type="text" id="code" name="code" value="" class="form-control code" placeholder="输入 优惠码" required="1" />
                                        </div>

                                    </div>

                                    <div class="col-sm-2">
                                        <label class="control-label">
                                            <a id="create_code_auto" style="cursor: pointer;">自动生成</a>
                                        </label>
                                    </div>

                                </div>

                                <div class="form-group  ">

                                    <label for="start_date" class="col-sm-2 asterisk control-label">生效日期</label>

                                    <div class="col-sm-4">

                                        <div class="input-group input-group-sm">

                                            <input type="text" id="start_date" name="start_date" value="" class="form-control start_date" placeholder="输入开始时间" required="1" />
                                            <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
                                            <input type="text" id="end_date" name="end_date" value="" class="form-control end_date" placeholder="输入结束时间" required="1" />

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--区域2-->
                        <br />
                        <div class="fields-group">
                            <div class="form-group">

                                <label for="apply_type_id" class="col-sm-2 asterisk control-label">适用于</label>

                                <div class="col-sm-2">

                                    <select class="form-control single_select" style="width: 100%;" name="apply_type_id" required="1" >
                                        <option></option>
                                        @foreach($apply_type_list as $key=>$apply_type)
                                            <option value="{{$key}}">{{$apply_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group good_content_div" style="display: none;">

                                <label for="good_id" class="col-sm-2 asterisk control-label">选择商品</label>

                                <div class="col-sm-4">

                                    <select class="form-control" style="width: 100%;" id="good_id" name="good_id" >

                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--区域3-->
                        <br />
                        <div class="fields-group">

                            <div class="form-group">

                                <label for="type_id" class="col-sm-2 asterisk control-label">优惠类型</label>

                                <div class="col-sm-2">

                                    <select class="form-control single_select" style="width: 100%;" name="type_id" required="1" >
                                        <option></option>
                                        @foreach($type_list as $key=>$type)
                                            <option value="{{$key}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group content_div content_div_1" style="display: none;">

                                <label for="percent" class="col-sm-2 asterisk control-label">百分比折扣</label>

                                <div class="col-sm-2">

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                        <input type="text" id="percent" name="percent" value="" class="form-control percent" placeholder="输入百分比" />
                                        <span class="input-group-addon" style="border-left: 0;">%</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">
                                        <span style="color: red">（输入1-99的数字; 示例：填写80,表示价格打8折）</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group content_div content_div_2" style="display: none;" >

                                <label for="fixed_money" class="col-sm-2 asterisk control-label">固定金额</label>

                                <div class="col-sm-2">

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>

                                        <input type="text" id="fixed_money" name="fixed_money" value="" class="form-control fixed_money" placeholder="输入优惠金额"  />
                                        <span class="input-group-addon" style="border-left: 0;">{{$money_sign}}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="control-label">
                                        <span style="color: red">（输入的是要优惠的金额；示例：填写100，表示价格优惠100）</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group content_div content_div_3" style="display: none;">

                                <label for="amount" class="col-sm-2 asterisk control-label">满减</label>

                                <div class="col-sm-4">

                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon" style="border-right: 0;">满</span>
                                        <input type="text" name="full_reduction[amount]" value="" class="form-control amount" placeholder="输入购买数量"  />
                                        <span class="input-group-addon" style="border-left: 0; border-right: 0;">减</span>
                                        <input type="text"  name="full_reduction[money]" value="" class="form-control money" placeholder="输入满减金额" />
                                        <span class="input-group-addon" style="border-left: 0;">{{$money_sign}}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">

                            <div class="col-md-2">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                            </div>

                            <div class="col-md-8">

                                <div class="btn-group pull-left">
                                    <button type="submit" class="btn btn-primary">提交</button>
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

    <script src="{{asset('js/admin/helper.js')}}"></script>

    <script>
        $(function () {

            var date = new Date();
            console.log(date);
            $('#start_date').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN","minDate":date});
            $('#end_date').datetimepicker({"format":"YYYY-MM-DD","locale":"zh-CN","minDate":date});
            $("#start_date").on("dp.change", function (e) {
                $('#end_date').data("DateTimePicker").minDate(e.date);
            });

            //自动生成
            $("#create_code_auto").click(function(){
                $("#code").val(randomString(8));
            })

            //类别变动
            $("select[name='type_id']").change(function(){
                $(".content_div").hide();
                var type_id = $(this).val();
                console.log(type_id);
                $(".content_div_" + type_id).css({'display': 'block'});

            })

            //适用类型变动
            $("select[name='apply_type_id']").change(function(){
                var apply_type_id = $(this).val();
                if(apply_type_id == 1){
                    $(".good_content_div").show();
                }else{
                    $(".good_content_div").hide();
                }
            })

            $(".single_select").select2({"allowClear": true, "placeholder": {"id": "", "text": "请选择"}});

            $('.container-refresh').off('click').on('click', function () {
                $.admin.reload();
                $.admin.toastr.success('刷新成功 !', '', {positionClass: "toast-top-center"});
            });


            //选择产品
            $("#good_id").select2({
                language: {
                    inputTooShort: function () {
                        return "请输入单品关键字";
                    }
                },
                "allowClear": true,
                "placeholder": {"id": "", "text": "请选择"},
                ajax: {
                    url: "/admin/search_goods",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            keywords: params.term, // search term
                            page: params.page,
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        console.log(data, params);
                        params.page = params.page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 30) < data.count
                            }
                        };
                    },
                    cache: true
                },
                // placeholder: 'Search for a repository',
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo (repo) {
                console.log(repo);
                if (repo.loading) {
                    return repo.text;
                }

                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__avatar'>" +
                    "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + repo.main_image_url + "' /></div>" +
                    // "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "</div>"
                );

                $container.find(".select2-result-repository__title").text(repo.name);

                return $container;
            }
            function formatRepoSelection (repo) {
                return repo.name;
            }
        });
    </script>

@endsection
