//禁用
$("a[id*=disable_],a[id*=enable_]").click(function(){
    var title = $(this).data('title');
    var id = $(this).data('id');
    var action = $(this).data('action');
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
                    url: '/admin/good_orders/' +id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method:'delete',
                        // _token:"{{csrf_token()}}",
                        action:action,
                    },
                    success: function (data) {
                        //异步修改数据
                        // console.log(data);
                        resolve(data);
                    }
                });
            });
        }
    }).then(function(data) {
        console.log(data);
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

//加备注
$("a[id*='update_remark_']").editable({
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

//批量删除
$("#batch_delete").click(function(){
    var title = '批量删除';
    var order_ids = $.admin.grid.selected();
    if(order_ids.length == 0){
        swal('需要选择至少一条数据','','error');
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
                    url: '/admin/good_orders/batch_destroy',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        // _method:'post',
                        // _token:"{{csrf_token()}}",
                        order_ids: order_ids,
                    },
                    success: function (data) {
                        //异步修改数据
                        // console.log(data);
                        resolve(data);
                    }
                });
            });
        }
    }).then(function(data) {
        console.log(data);
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

//批量审核
$("#batch_audit_submit").click(function(){
    var order_ids = $.admin.grid.selected();
    if(order_ids.length == 0){
        swal('请选择一条数据','','error');
        return false;
    }

    $("#order_ids").val(order_ids);

    var status = $("#fm_batch_audit").find("select[name=status]").val();

    if(!status){
        $(".status_error_tips").show();
        return false;
    }else{
        $(".status_error_tips").hide();
    }
    var remark = $("#fm_batch_audit").find("textarea[name=remark]").val();

    if(!remark){
        $(".remark_error_tips").show();
        return false;
    }else{
        $(".remark_error_tips").hide();
    }


    $("#fm_batch_audit").submit();

})

//批量导出
$("#batch_export").click(function(){
    var title = '批量导出';
    var order_ids = $.admin.grid.selected();
    if(order_ids.length == 0){
        swal('需要选择至少一条数据','','error');
        return false;
    }
    var params = '';
    var search = location.search;
    var filter = 'filter_order_ids=' + order_ids.join(',');
    console.log(filter);
    if(search){
        params = search + '&' + filter;
    }else{
        params = '?' + filter;
    }
    console.log(params);
    location.href = '/admin/good_orders/export' + params;

})
