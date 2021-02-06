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
</head>
<style>
    .new_label {
        padding: 9px 5px;
        width: 125px;
        margin-right: 5px;
    }
</style>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action=""  class="form-horizontal layui-form" lay-filter="mod_form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label new_label">淘宝自动更新模式</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="checkSource" value="1" <?php echo $checkSource == 1 ? 'checked' :''; ?>>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label new_label">自定义商品来源</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="checkSource" value="2" <?php echo $checkSource == 2 ? 'checked' :''; ?>>
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
</body>
</html>