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
    <script src="__ADMIN_JS__/userRegister.js"></script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action=""  class="form-horizontal layui-form" lay-filter="mod_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">

                            <div class="layui-form-item">
                                <div class="layui-inline" style="width: 100%">
                                    <label class="layui-form-label" style="width: 110px;">模块名称</label>
                                    <div class="layui-input-inline" style="width: 50%;">
                                        <input type="text" name="title" value="<?php echo isset($info['title']) ? $info['title'] : ''; ?>" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline" style="width: 100%">
                                    <label class="layui-form-label" style="width: 110px;">布局样式</label>
                                    <div class="layui-input-inline" style="width: 40%;">
                                        <?php if($id > 0){ ?>
                                            <input type="radio" name="layout" value="2" autocomplete="off" class="layui-input" <?php if(isset($info['layout']) && $info['layout'] == 2){ ?>checked<?php } ?> disabled>
                                        <?php }else{ ?>
                                            <input type="radio" name="layout" value="2" autocomplete="off" class="layui-input" <?php if(isset($info['layout']) && $info['layout'] == 2){ ?>checked<?php } ?>>
                                        <?php } ?>
                                        <span>一行两个</span>
                                    </div>
                                    <div class="layui-input-inline" style="width: 40%;">
                                        <?php if($id > 0){ ?>
                                            <input type="radio" name="layout" value="4" autocomplete="off" class="layui-input" <?php if(isset($info['layout']) && $info['layout'] == 4){ ?>checked<?php } ?> disabled>
                                        <?php }else{ ?>
                                            <input type="radio" name="layout" value="4" autocomplete="off" class="layui-input" <?php if(isset($info['layout']) && $info['layout'] == 4){ ?>checked<?php } ?>>
                                        <?php } ?>
                                        <span>一行四个</span>
                                    </div>
                                </div>
                                <div class="layui-inline" style="width: 100%">
                                    <label class="layui-form-label" style="width: 110px;">行间距</label>
                                    <div class="layui-input-inline" style="width: 10%;">
                                        <input type="number" name="line_height" value="<?php echo isset($info['line_height']) ? $info['line_height'] : ''; ?>" autocomplete="off" class="layui-input" style="display: inline-block">
                                    </div>
                                    <span>px</span>
                                </div>

                                <div class="layui-inline" style="width: 100%">
                                    <label class="layui-form-label" style="width: 110px;">排序</label>
                                    <div class="layui-input-inline" style="width: 10%;">
                                        <input type="number" name="weight" value="<?php echo isset($info['weight']) ? $info['weight'] : ''; ?>" autocomplete="off" class="layui-input" style="display: inline-block">
                                    </div>
                                </div>
                                <div class="layui-inline" style="width: 100%">
                                    <label class="layui-form-label" style="width: 110px;">是否显示</label>
                                    <div class="layui-input-inline" style="width: 50%;">
                                        <input type="checkbox" name="state" value="1" autocomplete="off" class="layui-input" <?php if(isset($info['state']) && $info['state'] == 1){ ?>checked<?php } ?> />
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit" id="LAY-user-back-submit" value="提交">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>

</script>
</body>
</html>