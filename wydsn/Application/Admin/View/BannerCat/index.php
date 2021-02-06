<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">-->
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
    <script type="text/javascript">
        function del(id)
        {
            /* if(id!='') {
                swal({
                    title:"分类下的广告图会一起删除，确定要删除该分类吗？",
                    text:"",
                    type:"warning",
                    showCancelButton:true,
                    cancelButtonText:"取消",
                    confirmButtonColor:"#DD6B55",
                    confirmButtonText:"删除",
                    closeOnConfirm:false
                },function(){
                    $.ajax({
                        type:"POST",
                        url:'/taokeyun.php/BannerCat/del',
                        dataType:"html",
                        data:"id="+id,
                        success:function(msg)
                        {
                            if(msg=='1')
                            {
                                swal({
                                    title:"删除成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }else if (msg=='2'){
                                swal({
                                    title:"该分类不可删除！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            } else {
                                swal({
                                    title:"操作失败！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            }
                        }
                    });
                })
            } */
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 内容管理 &raquo; Banner/广告分类管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__CONTROLLER__/add" method="post" role="form" class="form-inline layui-form">
                        <div class="layui-row layui-col-space17">
                            <!-- 分类名称：<input type="text" placeholder="" name="title" class="form-control"> -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">分类名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="title" required placeholder="请输入标题" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-input-inline">
                                    <button class="layui-btn layuiadmin-btn-admin "  lay-submit lay-filter="LAY-user-back-search">添加分类</button>
                                </div>
                            </div>
                    
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>分类名称</th>
                                    <th>创建时间</th>
                                    <th>查看广告图列表</th>
                                    <th>显示</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['title']}</td>
                                        <td>{$l['createtime']}</td>
                                        <td><a href="__MODULE__/Banner/index/cat_id/{$l.id}" class="layui-btn">点击查看广告图列表</a></td>
                                        <td>
                                                <?php
                                                    $cat_str =  $l['is_show'] ? 'checked' : '';
                                                ?>
                                                
                                                <input type="checkbox" value="{$l['id']}" lay-skin="switch" lay-text="是|否" lay-filter="cat_show" {$cat_str}>
                                            </td>
                                        <td>
                                            <a href="__CONTROLLER__/edit/id/{$l.id}" title="修改">
                                                <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                            <!-- <a href="javascript:;" onclick="del({$l.id});" title="删除">
                                                <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                            </a> -->
                                        </td>
                                    </tr>
                                </foreach>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script type="text/javascript">
    layui.use('form', function() {
        var form = layui.form;

        // 分类显示、隐藏开关
        form.on('switch(cat_show)', function(data) {
            let sw   = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let bid  = data.value;                  // 开关value值，也可以通过data.elem.value得到
            // ajax请求
            $.ajax({
                type:"POST",
                url:'__CONTROLLER__/bannerCatShow',
                data:{"sw":sw,"bid":bid}
            });
        }); 

    });
</script>
</body>
</html>