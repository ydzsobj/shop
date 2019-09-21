$(function () {
    (function ($) {
        $('.table-responsive').on('show.bs.dropdown', function () {
            $('.table-responsive').css("overflow", "inherit" );
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
            $('.table-responsive').css("overflow", "auto");
        })
    })(jQuery);


    //时间日期
    var date = new Date();
    console.log(date);
    $('#created_at_start').datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh-CN","maxDate" :date});
    $('#created_at_end').datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh-CN","useCurrent":true,"maxDate" :date});
    // $("#created_at_start").on("dp.change", function (e) {
    //     $('#created_at_end').data("DateTimePicker").minDate(e.date);
    // });
    $("#created_at_end").on("dp.change", function (e) {
        $('#created_at_start').data("DateTimePicker").maxDate(e.date);
    });

    $(".status").select2({
        placeholder: {"id":"","text":"\u9009\u62e9"},
        "allowClear":true
    });

    //全选
    $('.grid-row-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $.admin.grid.select(id);
            $(this).closest('tr').css('background-color', '#ffffd5');
        } else {
            $.admin.grid.unselect(id);
            $(this).closest('tr').css('background-color', '');
        }
    }).on('ifClicked', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $.admin.grid.unselect(id);
        } else {
            $.admin.grid.select(id);
        }

        var selected = $.admin.grid.selected().length;

        if (selected > 0) {
            $('.grid-select-all-btn').show();
        } else {
            $('.grid-select-all-btn').hide();
        }

        $('.grid-select-all-btn .selected').html("已选择 {n} 项".replace('{n}', selected));
    });

    $('.grid-select-all').iCheck({checkboxClass:'icheckbox_minimal-blue'});

    $('.grid-select-all').on('ifChanged', function(event) {
        if (this.checked) {
            $('.grid-row-checkbox').iCheck('check');
        } else {
            $('.grid-row-checkbox').iCheck('uncheck');
        }
    }).on('ifClicked', function () {
        if (this.checked) {
            $.admin.grid.selects = {};
        } else {
            $('.grid-row-checkbox:visible').each(function () {
                var id = $(this).data('id');
                $.admin.grid.select(id);
                console.log(id);
            });
        }

        var selected = $.admin.grid.selected().length;

        if (selected > 0) {
            $('.grid-select-all-btn').show();
        } else {
            $('.grid-select-all-btn').hide();
        }

        $('.grid-select-all-btn .selected').html("已选择 {n} 项".replace('{n}', selected));
    });

});


//头部刷新
$('.container-refresh').off('click').on('click', function() {
    $.admin.reload();
    $.admin.toastr.success('刷新成功 !', '', {positionClass:"toast-top-center"});
});

//分页
$(".grid-per-pager").on('change', function(e){
    $("#select_per_page").val($(this).val());
    $("#fm").submit();
})