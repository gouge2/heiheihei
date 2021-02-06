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
                    <h3>当前位置：直播管理 &raquo; 房间列表 &raquo;评论管理</h3>
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
                            <label class="layui-form-label" style='width:100px'>用户来鹿号/UID</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="u_str" value="{$search['u_str']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:70px'>评论内容</label>
                            <div class="layui-input-inline" style="width: 300px">
                                <input type="text" placeholder="" name="text" value="{$search['text']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">禁言</label>
                            <div class="layui-input-inline" style="width: 100px">
                            <select class="form-control" name="mute">
                                <option value="" >请选择</option>
                                <?php
                                    foreach ($search['mute'] as $k => $v) {
                                        $che = $v['sel'] ? 'selected="selected"' : '';
                                        echo '<option value="'. $k .'" '. $che .' >'. $v['name'] .'</option>';
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">踢出</label>
                            <div class="layui-input-inline" style="width: 100px">
                            <select class="form-control" name="kikc">
                                <option value="" >请选择</option>
                                <?php
                                    foreach ($search['kikc'] as $k => $v) {
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
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>房间号</th>
                                    <th>昵称/来鹿号/id</th>
                                    <th>头像</th>
                                    <th>评论内容</th>
                                    <th>评论时间</th>
                                    <th>禁言</th>
                                    <th>踢出</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
        
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['room_id']}</td>
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
                                                $cover_url = '';
                                                if (isset($ulist[$l['user_id']]) && $ulist[$l['user_id']]['avatar']) {
                                                    $cover_url = (is_url($ulist[$l['user_id']]['avatar']) ? $ulist[$l['user_id']]['avatar'] : WEB_URL . $ulist[$l['user_id']]['avatar']);
                                                }
                                            ?>
                                            <img src="{$cover_url}" style="max-width:80px; max-height:80px;">
                                        </td>
                                        <td>{$l['text']}</td>
                                        <td>{$l['add_time']}</td>
                                        <td>
                                            <?php
                                                $is_mute  = $l['is_mute'] + 1;
                                                if (isset($search['mute'][$is_mute])) {
                                                    echo $search['mute'][$is_mute]['name'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $is_kikc  = $l['is_kikc'] + 1;
                                                if (isset($search['kikc'][$is_kikc])) {
                                                    echo $search['kikc'][$is_kikc]['name'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($l['is_mute'] == 0) {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\" href=\"javascript:;\" onclick=\"handle('". $l['id'] ."', '2')\">禁言</a>";
                                                } else {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\" href=\"javascript:;\" onclick=\"handle('". $l['id'] ."', '3')\">取消禁言</a>";
                                                }

                                                if ($l['is_kikc'] == 0) {
                                                    echo "<a class=\"layui-btn layui-btn-primary layui-btn-xs\" href=\"javascript:;\" onclick=\"handle('". $l['id'] ."', '1')\">踢出房间</a>";
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

    // 禁言、取消禁言、踢出房间
    function handle(id, type) {
        if (id != '') {
            let t_str   = (type == '2') ? "确定要禁言该用户吗？" : ( (type == '3') ? "确定要取消禁言吗？" : "确定要将该用户踢出直播间吗？");
            let b_text  = (type == '2') ? "禁言" : ( (type == '3') ? "取消禁言" : "踢出");

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
                    url:'__CONTROLLER__/handle',
                    data:{"id":id,"type":type},
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