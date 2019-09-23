//删除
$("a[id*=delete_]").click(function(){
    var title = $(this).data('title');
    var url = $(this).data('url');
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
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method:'delete',
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