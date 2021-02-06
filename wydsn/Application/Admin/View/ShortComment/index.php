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
                    <h3>当前位置：视频管理 &raquo; 评论列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:50px'>视频ID</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="short_id" value="{$search['short_id']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:60px'>用户UID</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="uid" value="{$search['uid']}" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:70px'>评论内容</label>
                            <div class="layui-input-inline" style="width: 300px">
                                <input type="text" placeholder="" name="text" value="{$search['text']}" class="layui-input">
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
                    <a class="layui-btn pull-right" href="javascript:;" onclick="comm_mod('0')">添加评论</a>
                    <div class="layui-row layui-col-space17">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>视频ID</th>
                                <th>用户</th>
                                <th>评论内容</th>
                                <th>点赞数</th>
                                <th>回复数</th>
                                <th>发布时间</th>
                                <th>显示状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
    
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['id']}</td>
                                    <td>{$l['short_id']}</td>
                                    <td>
                                        <?php
                                            if (isset($ulist[$l['user_id']])) {
                                                echo '('. $l['user_id'] .')'. $ulist[$l['user_id']];
                                            } else {
                                                echo '('. $l['user_id'] .')';
                                            }
                                        ?>
                                    </td>
                                    <td>{$l['text']}</td>
                                    <td>{$l['praise_num']}</td>
                                    <td>{$l['reply_num']}</td>
                                    <td>{$l['add_time']}</td>
                                    <td>
                                        <?php
                                            if (isset($search['status'][$l['is_status']])) {
                                                echo $search['status'][$l['is_status']]['name'];
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="layui-table-cell laytable-cell-1-0-13"> 
                                            <a class="layui-btn layui-btn-normal layui-btn-xs" href="javascript:;" onclick="look('{$l.id}')">查看回复</a> 
                                            <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;" onclick="comm_mod('{$l.id}')">编辑</a>
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

        // 删除事件
        function del(id) {
            if (id != '') {
                swal({
                    title:"确定要删除该评论吗？删除后将无法恢复！！！",
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
                        url:'__CONTROLLER__/del',
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

        // 添加/编辑
        function comm_mod(id) {
            layer.open({
                type: 2, 
                content: '__CONTROLLER__/mod/id/' + id,
                title: '添加/编辑评论',
                area: ['70%', '500px'],
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

        // 查看回复
        function look(pid) {
            layer.open({
                type: 2, 
                content: '__CONTROLLER__/index/pid/' + pid,
                title: '查看评论',
                area: ['90%', '90%']
            }); 
        }

    </script>
</body>
</html>