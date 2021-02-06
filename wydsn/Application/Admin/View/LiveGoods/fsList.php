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
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="javascript:;" onclick="fs_add('{$uid}', '{$sid}')">新增商品</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商品来源</th>
                                    <th>商品ID</th>
                                    <th>商品标题</th>
                                    <th>商品图片</th>
                                    <th>原价/现价</th>
                                    <th>优惠金额</th>
                                    <th>佣金</th>
                                    <?php
                                        if (!$sid) {
                                            echo "<th>排序</th>".
                                                 "<th>讲解状态</th>";
                                        }
                                    ?>
                                    <th>添加时间</th>
                                    <th style="width:130px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
        
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['from_cn']}</td>
                                        <td>{$l['goods_id']}</td>
                                        <td><?php echo isset($l['goods']['goods_name']) ? $l['goods']['goods_name'] : ''; ?></td>
                                        <td>
                                            <?php 
                                                $img = isset($l['goods']['img']) ? $l['goods']['img'] : ''; 
                                            ?>
                                            <img src="{$img}" height="50px">
                                        </td>
                                        <td>
                                            <?php echo isset($l['goods']['old_price']) ? $l['goods']['old_price'] .' / '. $l['goods']['price'] : ''; ?>
                                        </td>
                                        <td><?php echo isset($l['goods']['coupon_amount']) ? $l['goods']['coupon_amount'] : ''; ?></td>
                                        <td><?php echo isset($l['goods']['commission']) ? $l['goods']['commission'] : ''; ?></td>

                                        <?php
                                            if (!$sid) {
                                                echo "<td>".
                                                        "<input name=\"sort\" value=\"". $l['sort'] ."\" class=\"form-control\" style=\"width:30px;text-align:center\" cid=\"". $l['id'] ."\">".
                                                      "</td>".
                                                      "<td>". $l['explain_cn'] ."</td>";
                                            }
                                        ?>
                                        <td>{$l['add_time']}</td>
                                        <td>
                                            <?php
                                                if (!$sid) {
                                                    if ($l['is_explain'] == 'load') {
                                                        echo "<a class=\"layui-btn layui-btn-warm layui-btn-xs\"  href=\"javascript:;\" onclick=\"explain('". $l['id'] ."', '". $l['user_id'] ."', '2');\">结束讲解</a>";

                                                    } else {
                                                        echo "<a class=\"layui-btn layui-btn-normal layui-btn-xs\"  href=\"javascript:;\" onclick=\"explain('". $l['id'] ."', '". $l['user_id'] ."', '1');\">开始讲解</a>";
                                                        
                                                    }
                                                }

                                                if (in_array($r_state, [3, 4])) {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\"  href=\"javascript:;\" onclick=\"del('". $l['id'] ."');\">删除</a>";
                                                }
                                            ?>
                                            
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
    // 删除事件
    function del(id) {
        if (id != '') {
            swal({
                title:"确定要删除该商品吗？删除后将无法恢复！！！",
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
                    url:'__CONTROLLER__/slgDel',
                    dataType:"html",
                    data:"id="+id,
                    success:function(msg) {
                        if (msg == '1') {
                            swal({title:"删除成功！", text:"", type:"success"},function(){location.reload();})
                        } else {
                            if (msg == '-1' ) {
                                swal({title:"房间正常直播中，操作失败！", text:"", type:"error"},function(){location.reload();})
                            } else {
                                swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                            }
                        }
                    }
                });
            })
        }
    }

    // 开始或者结束 讲解
    function explain(id, uid, type) {
        if (id != '' && uid != '' && type != '') {
            $.ajax({
                type:"POST",
                url:'__CONTROLLER__/fakeExplainMod',
                data:{"id":id,"uid":uid,"type":type},
                success:function(msg) {
                    if (msg=='1') {
                        swal({title:"操作成功！", text:"", type:"success"},function(){location.reload();})
                    } else {
                        swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                    }
                }
            });
        }
    }

    // 排序框改变
    $('input[name="sort"]').on("blur", function(e) {
        let sort = e.delegateTarget.value,
            cid  = e.currentTarget.getAttribute("cid");
        // ajax请求
        $.ajax({
            type:"POST",
            url:'__CONTROLLER__/slgSort',
            data:{"sort":sort, "cid":cid},
            success:function(msg) {
                if (msg == '1') {
                    swal({title:"操作成功！", text:"", type:"success"},function(){location.reload();})
                } else {
                    if (msg == '-1') {
                        swal({title:"房间正常直播中，操作失败！", text:"", type:"error"},function(){location.reload();})
                    } else {
                        swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                    }
                }
            }
        });
    }).on("keyup",function(e){
        if(e.key == 'Enter'){
            $(this).blur();
        }
    });

    // 添加假直播或短视频商品
    function fs_add(uid, sid) {
        layer.open({
            type: 2, 
            content: '__CONTROLLER__/fsAdd/uid/' + uid + '/sid/' + sid,
            title: '添加商品',
            area: ['80%', '350px'],
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
                        url: '__CONTROLLER__/fsAdd',
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