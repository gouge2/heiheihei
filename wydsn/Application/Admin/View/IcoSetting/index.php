<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <h3>当前位置：ICO管理 &raquo; ICO设置</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="javascript:;" onclick="ico_add('0')">新增ICO</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>图标</th>
                                    <th>URL</th>
                                    <th>排序</th>
                                    <th>显示</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['ico_id']}</td>
                                        <td>{$l['ico_name']}</td>
                                        <td>
                                            <img src="{$l['ico_image']}" style="max-width:80px;">
                                        </td>
                                        <td>{$l['ico_url']}</td>
                                        <td>
                                            <input name="sort" value="{$l['sort']}" class="form-control" style="width:30px;text-align:center" sid="{$l['ico_id']}">
                                        </td>
                                        <td>
                                            <?php
                                            $cat_str =  ($l['is_show'] ==1) ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" value="{$l['ico_id']}" lay-skin="switch" lay-text="是|否" lay-filter="cat_show" {$cat_str}>
                                        </td>
                                        <td>
                                            <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;" onclick="ico_add('{$l.ico_id}')">编辑</a>
                                        </td>
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
    layui.use('form', function(){
        var form = layui.form;

        // 分类显示、隐藏开关
        form.on('switch(cat_show)', function(data) {
            let sw   = data.elem.checked ? 1 : 0;
            let id  = data.value;
            // ajax请求
            $.ajax({
                type:"POST",
                url:'__CONTROLLER__/editshow',
                data:{"sw":sw,"id":id},
            });
        });
    });

    // 排序框改变
    $('input[name="sort"]').on("blur", function(e) {
        let sort = e.delegateTarget.value,
            id  = e.currentTarget.getAttribute("sid");
        // ajax请求
        $.ajax({
            type:"POST",
            url:'__CONTROLLER__/editSort',
            data:{"sort":sort, "id":id},
            success:function(msg) {
                if (msg=='1') {
                    swal({title:"操作成功！", text:"", type:"success"},function(){location.reload();})
                } else {
                    swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                }
            }
        });
    }).on("keyup",function(e){
        if(e.key == 'Enter'){
            $(this).blur();
        }
    });

    // 添加/编辑
    function ico_add(id) {
        layer.open({
            type: 2,
            content: '__CONTROLLER__/edit/gid/' + id,
            title: '添加/编辑礼物',
            area: ['650px', '450px'],
            btn: ['立即提交', '取消'],
            yes: function(index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index],
                    submitID = 'LAY-user-back-submit',
                    submit = layero.find('iframe').contents().find('#' + submitID);
                submitID = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                    var field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__CONTROLLER__/edit',
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
    }

    // 预览效果
    function preview_gift(v_url) {
        let svga_str    = v_url.slice(-5);

        if (svga_str == '.svga') {
            let href = '__CONTROLLER__/lookSvga/img_url/' + window.btoa(v_url);
            window.open(href);
            // layer.msg('svga图片暂不支持预览！！！');
            return false;
        }

        layer.open({
            type: 1,
            title: false,                   //  不显示标题栏
            closeBtn: false,
            shadeClose: true,
            offset: 'auto',
            id:'preview',
            area: 'auto',
            shade: 0.8,
            btnAlign: 'c',
            moveType: 1, //拖拽模式，0或者1
            content: '<div style="background-color: rgb(0, 0, 0);">'+
                '<img src="'+ v_url +'" style="width:200px;" />'+
                '</div>',
        });
    }

</script>
</body>
</html>