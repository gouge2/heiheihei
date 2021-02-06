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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript">
        function del(id)
        {
            if(id!='') {
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
                        url:'/taokeyun.php/BkCat/del',
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
                            }else {
                                swal({
                                    title:"操作失败！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            }
                        }
                    });
                })
            }
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 内容管理 &raquo; 宫格版块分类管理</h3>
                </div>
                <div class="ibox-content">
<!--                    <h4 style="color: red">宫格分类管理及其列表都不可删除只能修改</h4>-->
<!--                    <form action="__CONTROLLER__/add" method="post" role="form" class="form-inline">-->
<!--                        <div class="layui-form-item">-->
<!--                            <label class="layui-form-label" style='width:100px'>分类名称</label>-->
<!--                            <div class="layui-input-inline">-->
<!--                                <input type="text" name="title" required placeholder="请输入标题" autocomplete="off" class="layui-input pull-right">-->
<!--                            </div>-->
<!--                            <div class="layui-input-inline">-->
<!--                                <button class="layui-btn layuiadmin-btn-admin"  lay-submit lay-filter="LAY-user-back-search">添加分类</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </form>-->
                    <a class="layui-btn pull-right" href="javascript:;" onclick="info(0)">添加宫格</a>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>分类名称</th>
                                <th>创建时间</th>
                                <th>查看宫格图列表</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['id']}</td>
                                    <td>{$l['title']}</td>
                                    <td>{$l['createtime']}</td>
                                    <td><a href="__MODULE__/Bk/index/cat_id/{$l.id}" class="layui-btn">点击查看宫格图列表</a></td>
                                    <td>
<!--                                        <a href="__CONTROLLER__/edit/id/{$l.id}" title="修改">-->
<!--                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;-->
<!--                                        </a>-->
                                        <a href="javascript:void(0);" title="修改" onclick="info({$l['id']})">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
<!--                                        <a href="javascript:;" onclick="del({$l.id});" title="删除">-->
<!--                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;-->
<!--                                        </a>-->
                                    </td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    function info(id) {
        layer.open({
            type: 2,
            content: '__CONTROLLER__/info?id='+id,
            title: '添加/编辑宫格',
            area: ['50%', '450px'],
            btn: ['保存', '取消'],
            yes: function(index, layero) {
                let iframeWindow    = window['layui-layer-iframe'+ index],
                    submitID            = 'LAY-user-back-submit',
                    submit              = layero.find('iframe').contents().find('#'+ submitID);
                submitID            = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                    let field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__CONTROLLER__/save',
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