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
        .room_manage {
            display: inline-block;
            position:relative;
            width: 150px;
            margin-top: 10%;
        }
        .room_manage ul {
            position:absolute;
            top: 20px;
            width: 150px;
            background: #fff;
            padding: 5px 0;
            z-index: 99;
            border: 1px solid rgba(0, 0, 0, .3);
            border-radius: 5px;
        }
        .room_manage ul li {
            margin: 3px 0;
            text-indent: 1em;
            height: 25px;
            padding: 3px 0;
        }
        .room_manage ul li:hover {
            background: #eee;
        }
        .none {
            display: none;
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
                            <label class="layui-form-label" style='width:100px'>用户{$app_name}号/UID</label>
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
                    <a class="layui-btn pull-right" href="javascript:;" onclick="live_mod('0','4')">创建房间</a>
                    <a class="layui-btn pull-right" href="javascript:;" onclick="live_set()" style="margin-right: 15px;">相关配置</a>
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>封面</th>
                                    <th>主播昵称/{$app_name}号/id</th>
                                    <th>分类</th>
                                    <th>状态</th>
                                    <th>观看量</th>
                                    <th>是否推荐</th>
                                    <th>排序</th>
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
                                        <td>
                                            <?php $none = $l['is_recommend'] ? 'block' : 'none'; ?>
                                            <input id="{$l['room_id']}" name="sort" value="{$l['sort']}" class="form-control" cid = "{$l.room_id}" style="width:30px;text-align:center;display: <?php echo $none; ?>">
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
                                            <div class="room_manage">
                                                <a class="layui-btn layui-btn-normal layui-btn-xs manage_btn" href="javascript:;" >管理</a>
                                                <ul class="none" >
                                                    <li><a href="javascript:;" onclick="live_mod('{$l.room_id}','{$l.is_status}')">编辑直播间</a></li>
                                                    <li><a href="__MODULE__/LiveBullet/index/room_id/{$l.room_id}">评论管理</a></li>
                                                    <li><a href="__MODULE__/LiveBan/index/room_id/{$l.room_id}">禁播日志</a></li>
                                                    <li><a href="__MODULE__/Short/getPutList/user_id/{$l.user_id}">回放记录</a></li>
                                                    <li><a href="javascript:;" onclick="open_push('{$l.push}')">推流信息</a></li>
                                                    <li><a href="javascript:;" onclick="live_code('{$l.room_id}')">直播间小程序码</a></li>
                                                    <?php
                                                        if (in_array($l['is_status'], [2,4])) {
                                                            $href = U('LiveGoods/fsList', ['uid' => $l['user_id'], 'r_state' => $l['is_status']]);
                                                            echo "<li><a href=\"javascript:;\" onclick=\"good_mange('". $href ."')\">商品管理（假直播）</a></li>";
                                                        }
                                                    ?>
                                                    
                                                </ul>
                                            </div>
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
<script src="__ADMIN_JS__/jquery.qrcode.min.js"></script>
<script type="text/javascript">
    layui.use('form', function(){
        var form = layui.form;

        // 是否推荐开关
        form.on('switch(recommend)', function(data) {
            let sw   = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let rid  = data.value;                  // 开关value值，也可以通过data.elem.value得到
            // ajax请求
            if (sw == 2) {
                document.getElementById(rid).style.display = "none";
                $.ajax({
                    type:"POST",
                    url:'__CONTROLLER__/recommendMod',
                    data:{"sw":sw,"rid":rid}
                });
            } else {
                document.getElementById(rid).style.display = "block";
                $.ajax({
                    type:"POST",
                    url:'__CONTROLLER__/recommendMod',
                    data:{"sw":sw,"rid":rid}
                });
                location.reload();
            }
        }); 

    });

    // 排序框改变
    $('input[name="sort"]').on("blur", function(e) {
        let sort = e.delegateTarget.value,
            cid  = e.currentTarget.getAttribute("cid");
        // ajax请求
        $.ajax({
            type:"POST",
            url:'__CONTROLLER__/catSort',
            data:{"sort":sort, "cid":cid},
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

    // 管理按钮
    $('.manage_btn').click( function () {
        $(this).parent('.room_manage').find('ul').toggle('none');
    });

    // 添加/编辑
    function live_mod(id,st) {
        if (st != 5) {
            layer.open({
                type: 2, 
                content: '__CONTROLLER__/mod/room_id/' + id,
                title: '添加/编辑房间',
                area: ['90%', '750px'],
                btn: ['立即提交', '取消'],
                yes: function(index, layero) {
                    var iframeWindow    = window['layui-layer-iframe'+ index],
                    submitID            = 'LAY-user-back-submit',
                    submit              = layero.find('iframe').contents().find('#'+ submitID);
                    submitID            = 'LAY-user-front-submit';

                    // 监听提交
                    iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                        let field       = data.field,     // 获取提交的字段
                            is_state   = data.field.is_status,
                            media_str   = data.field.media_url;
                        console.log(layer);
                        
                        // 视频地址是否正确
                        if (is_state == 2) {
                            if (media_str != '') {
                                let mp4_str     = media_str.slice(-4),
                                    m3u8_str    = media_str.slice(-5);

                                if (mp4_str != '.mp4' && mp4_str != '.MP4' && m3u8_str != '.m3u8' && m3u8_str != '.M3U8') {
                                    layer.msg('视频地址不正确，请填.mp4或者.m3u8结尾的地址！！！');
                                    return false;
                                }
                            } else {
                                layer.msg('请填写视频地址！');
                                return false;
                            }
                            
                        }    

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
                                    layer.closeAll();           // 关闭弹层
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
            swal({title:"请解除禁播状态后操作！", text:"", type:"error"});
        }
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

    // 商品管理
    function good_mange(url) {
        layer.open({
            type: 2, 
            content: url,
            title: '商品管理',
            area: ['90%', '90%']
        }); 
    }

    // 推流信息
    function open_push(url) {
        let u_str = url.toString();
        layer.open({
            type: 1, 
            content: `<div id="code" style="display: inline-block;margin-top: 43px;padding-left:120px;"></div>
                      <div style="line-height: 22px; padding:20px 20px;"><span>${u_str}</span></div>`,
            title: '推流信息',
            area: ['470px', '400px'],
            success: function(index, layero) {
                // 写入二维码
                $("#code").qrcode({
                    width: 220, 
                    height: 220, 
                    text: u_str,
                }); 
            }
        }); 
    }

    // 直播间小程序码
    function live_code(rid) {
        // 请求提交
        $.ajax({
            url: '__CONTROLLER__/getAppletCode',
            type: 'post',
            data: {"rid":rid},
            success: function(res) {
                res = JSON.parse(res);
                if (res.code == 'succ') {
                    layer.open({
                        type: 1, 
                        content: `<div style="text-align: center;padding-top: 30px;"><img style="width:220px; height:220px;" src="${res.data.img_url}" /></div>`,
                        title: '直播间小程序码',
                        area: ['350px', '380px']
                    }); 
                } else {
                    swal({title:res.msg, text:"", type:"error"});
                } 
            }
        });
    }

    // 相关配置
    function live_set() {
        layer.open({
            type: 2, 
            content: '__MODULE__/System/LiveRoomSet',
            title: '相关配置',
            area: ['40%', '350px'],
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
                        url: '__MODULE__/System/LiveRoomSet',
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