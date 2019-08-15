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
                    url: '/admin/goods/' +id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method:'delete',
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


//隐藏sku
$("button[id*=disable_list_], button[id*=enable_list_]").click(function(){
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


$(".grid-per-pager").on('change', function(e){
    $("#select_per_page").val($(this).val());
    $("#fm").submit();
})

//加备注
$("a[id*='update_sku_price_'], a[id*='update_product_attr_name_'],a[id*='update_product_attr_value_name_']").editable({
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

//选择产品
$("#product_id").select2({
    language: {
        inputTooShort: function () {
            return "请输入产品关键字";
        }
    },
    "allowClear": true,
    "placeholder": {"id": "", "text": "请选择"},
    ajax: {
        url: "{{$erp_api_domain}}/api/product",
        dataType: 'json',
        delay: 500,
        data: function (params) {
            return {
                keywords: params.term, // search term
                page: params.page,
            };
        },
        processResults: function (data, params) {
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

    var erp_api_domain = "{{$erp_api_domain}}";

    var $container = $(
        "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + erp_api_domain + repo.product_image + "' /></div>" +
        // "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'></div>" +
        "</div>"
    );

    $container.find(".select2-result-repository__title").text(repo.product_name);

    return $container;
}
function formatRepoSelection (repo) {
    return repo.product_name;
}