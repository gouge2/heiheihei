<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-10">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 自营商城分销设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">

                            <form action="__CONTROLLER__/distribution"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">

                                <div class="tab-content">

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">是否开启分销</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="is_distribution" value="Y" <?php if($msg['is_distribution']=='Y') echo 'checked'; ?> title="是">
                                                 <input type="radio" name="is_distribution" value="N" <?php if($msg['is_distribution']=='N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">自营分销结算周期</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="rebate_times" value="{$msg['rebate_times']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>

                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                            <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>