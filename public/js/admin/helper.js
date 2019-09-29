function randomString(length) {
    var chars = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
    return result.toUpperCase();
}
// var rString = randomString(8);

//复制
function copy(value){

    const input = document.createElement('input');
    document.body.appendChild(input);
    input.setAttribute('value', value);
    input.select();
    if (document.execCommand('copy')) {
        document.execCommand('copy');
        console.log('复制成功');
        swal('复制'  + '成功', '', 'success');
    }
    document.body.removeChild(input);
}