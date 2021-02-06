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

    <link href="__ADMIN_CSS__/img.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css"/>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <style>
        body #preview {
            overflow: hidden !important;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 应用账号配置</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="javascript:;" onclick="info(0)">添加国家编码</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="width: 20%">ID</th>
                                    <th style="width: 20%">国家名称</th>
                                    <th style="width: 20%">编号</th>
                                    <th style="width: 20%">是否显示</th>
                                    <th style="width: 20%">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <foreach name="msg" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['country']}</td>
                                        <td>{$l['code']}</td>
                                        <td>
                                            <if condition='$l[type] eq 1'>显示
                                                <else/>
                                                隐藏
                                            </if>
                                        </td>
                                        <td><a href="javascript:void(0);" title="修改" onclick="info({$l['id']})">编辑</a>
                                        </td>
                                    </tr>
                                </foreach>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    function info(id) {
        layer.open({
            type: 2,
            content: '__CONTROLLER__/countryAdd?id=' + id,
            title: '添加/编辑国家代码',
            area: ['50%', '450px'],
            btn: ['保存', '取消'],
            yes: function (index, layero) {
                let iframeWindow = window['layui-layer-iframe' + index],
                    submitID = 'LAY-user-back-submit',
                    submit = layero.find('iframe').contents().find('#' + submitID);
                submitID = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
                    let field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__CONTROLLER__/countryCode',
                        type: 'post',
                        data: field,
                        success: function (res) {
                            res = JSON.parse(res);
                            if (res.code == 'succ') {
                                layer.closeAll();           // 关闭弹层
                                swal({title: res.msg, text: "", type: "success"}, function () {
                                    location.reload();
                                });
                            } else {
                                swal({title: res.msg, text: "", type: "error"});
                            }
                        }
                    });
                    return false;               // 禁止跳转，否则会提交两次，且页面会刷新
                });

                // 触发提交
                submit.trigger('click');
            }
        });
    }
</script>
</body>
</html>