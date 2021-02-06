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
                    <h3>当前位置：直播管理 &raquo; 直播礼物</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-left" href="javascript:;" onclick="gift_cn()">相关配置</a>
                    <a class="layui-btn pull-right" href="javascript:;" onclick="gift_mod('0')">新增礼物</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>图标</th>
                                    <th>价格（<?php echo GIFT_MONEY_CN; ?>）</th>
                                    <th>排序</th>
                                    <th>显示</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
        
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['gift_id']}</td>
                                        <td>{$l['gift_name']}</td>
                                        <td>
                                            <?php
                                                $gift_cover = (is_url($l['gift_cover']) ? $l['gift_cover'] : WEB_URL . $l['gift_cover']);
                                                $gift_url   = (is_url($l['gift_url']) ? $l['gift_url'] : WEB_URL . $l['gift_url']);
                                            ?>
                                            <img src="{$gift_cover}" style="max-width:80px;">
                                        </td>
                                        <td>{$l['gift_price']}</td>
                                        <td>
                                            <input name="sort" value="{$l['sort']}" class="form-control" style="width:30px;text-align:center" gid="{$l['gift_id']}">
                                        </td>
                                        <td>
                                            <?php
                                                $cat_str =  $l['is_show'] ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" value="{$l['gift_id']}" lay-skin="switch" lay-text="是|否" lay-filter="cat_show" {$cat_str}>
                                        </td>
                                        <td>
                                            <a class="layui-btn layui-bg-green layui-btn-xs" onclick="preview_gift('{$l.show_url}');">预览效果</a>
                                            <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;" onclick="gift_mod('{$l.gift_id}')">编辑</a>
                                            <a class="layui-btn layui-btn-primary layui-btn-xs"  href="javascript:;" onclick="del('{$l.gift_id}');">删除</a>
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
            let sw   = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let gid  = data.value;                  // 开关value值，也可以通过data.elem.value得到
            // ajax请求
            $.ajax({
                type:"POST",
                url:'__CONTROLLER__/giftShow',
                data:{"sw":sw,"gid":gid}
            });
        }); 

    });

    // 删除事件
    function del(id) {
        if (id != '') {
            swal({
                title:"确定要删除该礼物吗？删除后将无法恢复！！！",
                text:"",
                type:"warning",
                showCancelButton:true,
                cancelButtonText:"取消",
                confirmButtonColor:"#DD6B55",
                confirmButtonText:"删除",
                closeOnConfirm:false
            },
            function() {
                $.ajax({
                    type:"POST",
                    url:'__CONTROLLER__/giftDel',
                    dataType:"html",
                    data:"id="+id,
                    success:function(msg) {
                        if (msg=='1') {
                            swal({title:"删除成功！", text:"", type:"success"},function(){location.reload();})
                        } else {
                            swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                        }
                    }
                });
            })
        }
    }

    // 排序框改变
    $('input[name="sort"]').on("blur", function(e) {
        let sort = e.delegateTarget.value,
            gid  = e.currentTarget.getAttribute("gid");
        // ajax请求
        $.ajax({
            type:"POST",
            url:'__CONTROLLER__/giftSort',
            data:{"sort":sort, "gid":gid},
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
    function gift_mod(id) {
        layer.open({
            type: 2, 
            content: '__CONTROLLER__/mod/gid/' + id,
            title: '添加/编辑礼物',
            area: ['80%', '600px'],
            btn: ['立即提交', '取消'],
            yes: function(index, layero) {
                var iframeWindow    = window['layui-layer-iframe'+ index],
                submitID            = 'LAY-user-back-submit',
                submit              = layero.find('iframe').contents().find('#'+ submitID);
                submitID            = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                    var field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__CONTROLLER__/mod',
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

    // 相关配置
    function gift_cn() {
        layer.open({
            type: 2, 
            content: '__MODULE__/System/LiveGiftCnSet',
            title: '相关配置',
            area: ['75%', '650px'],
            btn: ['立即提交', '取消'],
            yes: function(index, layero) {
                var iframeWindow    = window['layui-layer-iframe'+ index],
                submitID            = 'LAY-user-back-submit',
                submit              = layero.find('iframe').contents().find('#'+ submitID);
                submitID            = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                    var field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__MODULE__/System/LiveGiftCnSet',
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

</script>
</body>
</html>