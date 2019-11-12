function submit_check(){

    var target_model = model_id ? $("#editModal_" + model_id).find('.setting_sku_price'): $(".setting_sku_price");

    target_model.each(function(i,item){
        $(this).css({'border-color': ''});
    })

    var sku_arr = [];

    target_model.each(function(a,b){
        console.log(a,b);
        sku_arr.push($(this).val());
    })

    var checked_obj = isRepeat(sku_arr);

    if(checked_obj){
        alert('sku有重复' + checked_obj);
        $("input[value=" + checked_obj +"]").css({'border-color':'red'});
        return false;
    }else{
        return true;
    }
}

// function check_repeat(ary){
//     var nary = ary.sort();
//     for(var i = 0; i < nary.length - 1; i++) {
//     if(nary[i].val() == nary[i + 1].val()) {
//         return nary[i];
//         }
//     }
// }

function isRepeat(ary) {
    var s = ary.join(",") + ",";
    for (var i = 0; i < ary.length; i++) {
        if (s.replace(ary[i] + ",", "").indexOf(ary[i] + ",") > -1) {
            // alert("有重复：" + ary[i]);
            return ary[i];
        }
    }
}

