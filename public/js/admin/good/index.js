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
        url: "/admin/select_products",
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
                results: data.data.data,
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
        // "<img class='thumbnail' width=\"60px\" height=\"60px\"  src='" + erp_api_domain + repo.product_image + "' /></div>" +
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

$('div[id*=SetSkuModal_]').on('hidden.bs.modal', function () {
    // 执行一些动作...
    console.log('close 1');
    if($.admin.grid.selected().length >0){
        window.location.reload();
    }

})
