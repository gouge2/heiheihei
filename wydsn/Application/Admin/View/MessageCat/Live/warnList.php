<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet"> -->
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
    <style>
        body #preview {
            overflow: hidden!important;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：直播管理 &raquo; 房间列表 &raquo; 警告提醒</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="javascript:;" onclick="send()">发送提醒</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <input type="hidden" value="{$rid}" id="send_rid" />

                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>房间ID</th>
                                    <th>内容</th>
                                    <th>发送时间</th>
                                </tr>
                                </thead>

                                <tbody>
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['room_id']}</td>
                                        <td>{$l['text']}</td>
                                        <td>{$l['send_time']}</td>
                                    </tr>
                                </foreach>
                                </tbody>
                            </table>
                        </form>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script type="text/javascript">

    // 发送提醒
    function send() {
        let rid = $("#send_rid").val();

        if (rid > 0) {
            layer.open({
                type: 2, 
                content: '__CONTROLLER__/warnSend',
                title: '发送提醒',
                area: ['40%', '300px'],
                btn: ['立即发送', '取消'],
                yes: function(index, layero) {
                    var iframeWindow    = window['layui-layer-iframe'+ index],
                    submitID            = 'LAY-user-back-submit',
                    submit              = layero.find('iframe').contents().find('#'+ submitID);
                    submitID            = 'LAY-user-front-submit';

                    // 传值
                    layero.find('iframe').contents().find('input[name="rid"]').val(rid);

                    // 监听提交
                    iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                        var field = data.field;     // 获取提交的字段
                        // 请求提交
                        $.ajax({
                            url: '__CONTROLLER__/warnSend',
                            type: 'post',
                            data: field,
                            success: function(res) {
                                res = JSON.parse(res);
                                if (res.code == 'succ') {
                                    layer.closeAll();           // 关闭弹层
                                    swal({title:res.msg, text:"", type:"success"},function(){location.reload();});
                                } else {
                                    swal({title:res.msg, text:"", type:"error"});
                                }
                            }
                        });

                        return false;               // 禁止跳转，否则会提交两次，且页面会刷新
                    });

                    // 触发提交
                    submit.trigger('click');
                }
            });
        } else {
            swal({title:res.msg, text:"没有房间号，不可以使用发送提醒！", type:"error"});
        }
    }

</script>
</body>
</html>