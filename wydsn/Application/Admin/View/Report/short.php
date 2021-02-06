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
                    <h3>当前位置：视频管理 &raquo; 视频举报</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">

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
                    
                    <div class="layui-row layui-col-space17">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>举报人ID/昵称</th>
                                <th>举报人头像</th>
                                <th>举报类型</th>
                                <th>视频ID</th>
                                <th>证据内容</th>
                                <th>证据图片</th>
                                <th>被举报人ID/昵称</th>
                                <th>被举报人头像</th>
                                <th>状态</th>
                                <th>举报时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
    
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['id']}</td>
                                    <td>
                                        <?php
                                            $user_avatar = '';
                                            if (isset($ulist[$l['user_id']])) {
                                                echo $l['user_id'] .' / '. $ulist[$l['user_id']]['nickname'];
                                                $user_avatar   = is_url($ulist[$l['user_id']]['avatar']) ? $ulist[$l['user_id']]['avatar'] : WEB_URL . $ulist[$l['user_id']]['avatar'];
                                            } else {
                                                echo $l['user_id'];
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <img src="{$user_avatar}" style="max-width:80px;">
                                    </td>
                                    <td>{$cat_list[$l['cat_id']]['name']}</td>
                                    <td>{$l['short_id']}</td>
                                    <td>{$l['cause']}</td>
                                    <td>
                                        <?php
                                            if ($l['photo']) {
                                                foreach ($l['photo'] as $val) {
                                                    $tem = is_url($val) ? $val : WEB_URL . $val;
                                                    echo '<img src="'. $tem .'" style="max-width:100px;">';
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $by_avatar = '';
                                            if (isset($ulist[$l['by_id']])) {
                                                echo $l['by_id'] .' / '. $ulist[$l['by_id']]['nickname'];
                                                $by_avatar   = is_url($ulist[$l['by_id']]['avatar']) ? $ulist[$l['by_id']]['avatar'] : WEB_URL . $ulist[$l['by_id']]['avatar'];
                                            } else {
                                                echo $l['by_id'];
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <img src="{$by_avatar}" style="max-width:80px;">
                                    </td>
                                    <td>{$search['status'][$l['is_status']]['name']}</td>
                                    <td>{$l['add_time']}</td>
                                    <td>
                                        <div class="layui-table-cell laytable-cell-1-0-13"> 
                                            <a class="layui-btn layui-btn-normal layui-btn-xs" href="javascript:;" onclick="mod_state('{$l.id}')">完成处理</a>
                                            <a class="layui-btn layui-btn-primary layui-btn-xs"  href="javascript:;" onclick="del('{$l.id}');">删除</a>
                                        </div>
                                    </td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script type="text/javascript">

        // 完成处理事件
        function mod_state(id) {
            if (id != '') {
                swal({
                    title:"确定完成处理吗？！",
                    text:"",
                    type:"warning",
                    showCancelButton:true,
                    cancelButtonText:"取消",
                    confirmButtonColor:"#1E9FFF",
                    confirmButtonText:"确定",
                    closeOnConfirm:false
                },
                function() {
                    $.ajax({
                        type:"POST",
                        url:'__CONTROLLER__/reportMod',
                        dataType:"html",
                        data:"id="+id,
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

        // 删除事件
        function del(id) {
            if (id != '') {
                swal({
                    title:"确定要删除该举报吗？删除后将无法恢复！！！",
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
                        url:'__CONTROLLER__/reportDel',
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

    </script>
</body>
</html>