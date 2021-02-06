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
                    <h3>当前位置：直播管理 &raquo; 房间列表 &raquo;回放记录</h3>
                </div>
                <div class="ibox-content">
                    
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>封面</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>开播时长</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
        
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['short_name']}</td>
                                        <td><img src="{$l.cover_url}" style="max-width:100px;"></td>
                                        <td>
                                            <?php
                                                if (isset($slist[$l['site_id']])) {
                                                    echo $slist[$l['site_id']]['start_time'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if (isset($slist[$l['site_id']])) {
                                                    echo $slist[$l['site_id']]['end_time'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if (isset($slist[$l['site_id']])) {
                                                    $diff = strtotime($slist[$l['site_id']]['end_time']) - strtotime($slist[$l['site_id']]['start_time']);
                                                    echo date('H:i:s', $diff);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $href = U('Short/putLook', ['tag' => $l['id']]);
                                                echo "<a class=\"layui-btn layui-bg-green layui-btn-xs\" href=\"". $href ."\" target=\"_blank\" >查看回放</a>";
                                            ?>
                                            <a class="layui-btn layui-btn-primary layui-btn-xs"  href="javascript:;" onclick="del('{$l.id}');">删除</a>
                                        </td>
                                    </tr>
                                </foreach>
                                <tr style="display: <?php echo ($delmsg ? 'block' : 'none')?> "><td style="color: brown;"> {$delmsg}</td></tr></tr>
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
                title:"确定要删除该回放吗？删除后将无法恢复！！！",
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

</script>
</body>
</html>