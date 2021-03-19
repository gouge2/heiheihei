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
                    <h3>当前位置：直播管理 &raquo; 房间列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:50px'>房间ID</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="room_id" value="{$search['room_id']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:100px'>用户翠花号/UID</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="u_str" value="{$search['u_str']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">分类</label>
                            <div class="layui-input-inline">
                            <select class="form-control" name="cat_id">
                                <option value="" >请选择</option>
                                <?php
                                    foreach ($cat_list as $k => $v) {
                                        $che = ( $k == $search['cat_id']) ? 'selected="selected"' : '';
                                        echo '<option value="'. $k  .'" '. $che .' >'. $v .'</option>';
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">状态</label>
                            <div class="layui-input-inline">
                            <select class="form-control" name="status">
                                <option value="" >请选择</option>
                                <?php
                                    foreach ($search['status'] as $k => $v) {
                                        $che = $v['sel'] ? 'selected="selected"' : '';
                                        echo '<option value="'. $k .'" '. $che .' >'. $v['name'] .'</option>';
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                            </div>
                        </div>

                    </form>
                    <a class="layui-btn pull-right" href="javascript:;" onclick="live_mod('0')">创建房间</a>
                    <a class="layui-btn pull-right" href="javascript:;" style="margin-right: 20px;" onclick="live_cat()">直播分类</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>封面</th>
                                    <th>主播昵称/翠花号/id</th>
                                    <th>分类</th>
                                    <th>状态</th>
                                    <th>观看量</th>
                                    <th>是否推荐</th>
                                    <th>开始时间</th>
                                    <th>播流地址</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
        
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['room_id']}</td>
                                        <td>{$l['room_name']}</td>
                                        <td>
                                            <?php
                                                if ($l['cover_url']) {
                                                    $cover_url = (is_url($l['cover_url']) ? $l['cover_url'] : WEB_URL . $l['cover_url']);
                                                } else {
                                                    if (isset($ulist[$l['user_id']]) && $ulist[$l['user_id']]['avatar']) {
                                                        $cover_url = (is_url($ulist[$l['user_id']]['avatar']) ? $ulist[$l['user_id']]['avatar'] : WEB_URL . $ulist[$l['user_id']]['avatar']);
                                                    }
                                                }
                                            ?>
                                            <img src="{$cover_url}" style="max-width:100px;">
                                        </td>
                                        <td>
                                            <?php
                                                if (isset($ulist[$l['user_id']])) {
                                                    echo $ulist[$l['user_id']]['nickname'] .' / ';
                                                }
                                                if (isset($clist[$l['user_id']])) {
                                                    echo $clist[$l['user_id']] .' / ';
                                                }
                                                echo $l['user_id'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($l['cat_id'] && isset($cat_list[$l['cat_id']])) {
                                                    echo $cat_list[$l['cat_id']];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if (isset($search['status'][$l['is_status']])) {
                                                    echo $search['status'][$l['is_status']]['name'];
                                                }
                                            ?>
                                        </td>
                                        <td></td>
                                        <td>
                                            <?php
                                                $rec_str =  $l['is_recommend'] ? 'checked' : '';
                                            ?>
                                            <input type="checkbox" value="{$l.room_id}" lay-skin="switch" lay-text="是|否" lay-filter="recommend" {$rec_str}>
                                        </td>
                                        <td>{$l['recent_time']}</td>
                                        <td></td>
                                        <td>
                                            <!-- <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;" onclick="live_mod('{$l.room_id}')">编辑</a> -->
                                            <?php
                                                if ($l['is_status'] == 5) {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\" href=\"javascript:;\" onclick=\"room_ban('". $l['room_id'] ."', 'Y')\">启用</a>";
                                                } else {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\" href=\"javascript:;\" onclick=\"room_ban('". $l['room_id'] ."', 'N')\">禁播</a>";
                                                }
                                            ?>
                                            
                                            <a class="layui-btn layui-btn-danger layui-btn-xs" href="__CONTROLLER__/warnList/rid/{$l.room_id}">警告提醒</a>
                                            <a class="layui-btn layui-btn-normal layui-btn-xs" href="">管理</a>
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

        // 是否推荐开关
        form.on('switch(recommend)', function(data) {
            let sw   = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let rid  = data.value;                  // 开关value值，也可以通过data.elem.value得到
            // ajax请求
            $.ajax({
                type:"POST",
                url:'__CONTROLLER__/recommendMod',
                data:{"sw":sw,"rid":rid}
            });
        }); 

    });

    // 直播分类
    function live_cat() {
        layer.open({
            type: 2, 
            content: '__MODULE__/LiveCat/index',
            title: '查看分类',
            area: ['90%', '90%']
        }); 
    } 

    // 添加/编辑
    function live_mod(id) {
        layer.open({
            type: 2, 
            content: '__CONTROLLER__/mod/room_id/' + id,
            title: '添加/编辑房间',
            area: ['90%', '700px'],
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

    // 禁播、启用事件
    function room_ban(id, type) {
        if (id != '') {
            let t_str   = (type == 'Y') ? "确定要启用直播间吗？启用后可正常直播！" : "确定要拉黑直播间吗？拉黑后将无法直播！！！";
            let b_text  = (type == 'Y') ? "启用" : "拉黑";

            swal({
                title:t_str,
                text:"",
                type:"warning",
                showCancelButton:true,
                cancelButtonText:"取消",
                confirmButtonColor:"#DD6B55",
                confirmButtonText:b_text,
                closeOnConfirm:false
            },
            function() {
                $.ajax({
                    type:"POST",
                    url:'__CONTROLLER__/roomBan',
                    data:{"rid":id},
                    success:function(msg) {
                        if (msg=='1') {
                            swal({title:"操作成功！", text:"", type:"success"},function(){location.reload();})
                        } else {
                            swal({title:"操作失败！", text:"", type:"error"},function(){location.reload();})
                        }
                    }
                });
            })
        }
    }

    </script>
</body>
</html>