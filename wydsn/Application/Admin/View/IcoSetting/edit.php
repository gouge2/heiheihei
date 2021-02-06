<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script src="__ADMIN_JS__/userRegister.js"></script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="" class="form-horizontal layui-form" lay-filter="mod_form" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="gid">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">标题</label>
                                <div class="layui-input-inline" style="width: 300px;margin:0">
                                    <input type="text" name="ico_name" lay-verify="required" value=""
                                           placeholder="请输入标题" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">url地址</label>
                                <div class="layui-input-inline" style="width: 180px;margin:0">
                                    <input type="text" name="ico_url" lay-verify="required" value=""
                                           placeholder="请输入url地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">图标</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="ico_images" value="" placeholder="请上传图标" autocomplete="off"
                                           class="layui-input" lay-verify="required">
                                </div>
                                <button type="button" class="layui-btn" id="ico_images"><i class="layui-icon"></i>上传图片
                                </button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary cover_file"
                                        style="margin-left: 15px;display: none;" onclick="preview_gift('1')">预览图片
                                </button>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">排序</label>
                                <div class="layui-input-inline" style="width: 180px;margin:0">
                                    <input type="text" name="sort" lay-verify="number" value=""
                                           placeholder="请输入排序值，最高100" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">显示</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_show" value="1" title="是" checked="">
                                    <input type="radio" name="is_show" value="0" title="否">
                                </div>
                            </div>

                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit" id="LAY-user-back-submit" value="提交">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    let show_cover = "{$gift.show_cover}",
        show_url = "{$gift.show_url}";

    layui.use([], function () {
        let form = layui.form;
        let upload = layui.upload;

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "gid": "{$list.ico_id}",
            "ico_name": "{$list.ico_name}",
            "ico_url": "{$list.ico_url}",
            "ico_images": "{$list.ico_image}",
            "sort": "{$list.sort}"
        });

        // 图片上传
        let uploadImg = upload.render({
            elem: '#ico_images', //绑定元素
            url: '__MODULE__/IcoSetting/upload', //上传接口
            accept: 'images',
            data: {type: 'img'},
            done: function (ret) {   //上传完毕回调
                if (ret.code == 'succ') {
                    $('input[name="ico_images"]').val(ret.data.url);
                    $('.cover_file').show();
                    show_cover = ret.data.show_url;
                    show_url = ret.data.show_url;
                    layer.msg('上传成功');
                } else {
                    layer.msg(ret.msg);
                }
            }
        });
    });

    // 显示图片预览
    if (show_cover != '') {
        $('.cover_file').show();
    }

    // 预览图片或效果
    function preview_gift(t) {
        let v_url = t ? (t == 1 ? show_cover : show_url) : '';
        parent.layer.open({
            type: 1,
            title: false,                   //  不显示标题栏
            closeBtn: false,
            shadeClose: true,
            offset: 'auto',
            id: 'preview',
            area: 'auto',
            shade: 0.8,
            btnAlign: 'c',
            moveType: 1, //拖拽模式，0或者1
            content: '<div style="background-color: rgb(0, 0, 0);">' +
                '<img src="' + v_url + '" style="width:200px;" />' +
                '</div>',
        });
    }

</script>
</body>
</html>