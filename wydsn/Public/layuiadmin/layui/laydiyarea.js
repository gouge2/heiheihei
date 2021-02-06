//配置插件目录
layui.config({
    base: '__LAYUIADMIN__/mods/'
    , version: '1.0'
});
//一般直接写在一个js文件中
layui.use(['layer', 'form', 'layarea'], function () {
    var layer = layui.layer
        , form = layui.form
        , layarea = layui.layarea;

    layarea.render({
        elem: '#area-picker',
        change: function (res) {
            //选择结果
            console.log(res);
        }
    });
});