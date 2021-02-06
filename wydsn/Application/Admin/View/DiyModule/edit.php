<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：样式DIY设置 &raquo; 功能模块管理 &raquo; 编辑模块管理<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$msg.id}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_id" value="{$msg.id}">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">功能模块ID</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="new_id" value="{$msg.id}" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">功能模块类型</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" disabled value="{$msg.type}" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">功能模块名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="name" value="{$msg.name}" placeholder="功能模块名称" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">是否外链接</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_link" value="Y" <?php if($msg['is_link']=='Y'){echo 'checked';}?> title="是" >
                                        <input type="radio" name="is_link" value="N" <?php if($msg['is_link']=='N'){echo 'checked';}?> title="否">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">功能模块链接</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="link" value="{$msg.link}" placeholder="功能模块链接" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">功能模块图标</label>
                                <div class="layui-input-block">
                                    <?php if($msg['icon']){echo '<img src="'.$msg['icon'].'" height="120"/>';}else {echo '暂无';} ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">上传新图标</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" accept="image/*" class="layui-input" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" value="{$msg.sort}" style="width: 94%;">
                                    <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">是否首页显示</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_index_show" value="Y" <?php if($msg['is_index_show']=='Y'){echo 'checked';}?> title="是" >
                                    <input type="radio" name="is_index_show" value="N" <?php if($msg['is_index_show']=='N'){echo 'checked';}?> title="否">
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>
