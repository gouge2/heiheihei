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
                    <h3>当前位置：系统设置 &raquo; 图片管理</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <form action="__CONTROLLER__/pictureManagement" class="form-horizontal layui-form" method="post"
                                  enctype="multipart/form-data">

                                <div class="tab-content">

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">登录注册背景图片</label>
                                        <div class="layui-input-block">
                                            <input type="file" class="layui-input" name="logoImg" accept="image/*"
                                                   value=""
                                                   style="width: 17%;"><h4>推荐尺寸：1125*2436</h4>
                                            <img src="__ADMIN_IMG__/login_img.png?t=<?php echo time(); ?>" width="72"
                                                 height="72" alt="暂无">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">登录logo图</label>
                                        <div class="layui-input-block">
                                            <input type="file" class="layui-input" name="loginImg" accept="image/*"
                                                   value=""
                                                   style="width: 17%;"><h4>推荐尺寸：459*186</h4>
                                            <img src="__ADMIN_IMG__/logo_img.png?t=<?php echo time(); ?>" width="72"
                                                 height="72" alt="暂无">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">初始启动图</label>
                                        <div class="layui-input-block">
                                            <input type="file" class="layui-input" name="screenImg" accept="image/*"
                                                   value=""
                                                   style="width: 17%;"><h4>推荐尺寸：1125*2436</h4>
                                            <img src="__ADMIN_IMG__/screen_img.png?t=<?php echo time(); ?>" width="72"
                                                 height="72" alt="暂无">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">直播间标识图</label>
                                        <div class="layui-input-block">
                                            <input type="file" class="layui-input" name="liveImg" accept="image/*"
                                                   value=""
                                                   style="width: 17%;"><h4>推荐尺寸：192*66</h4>
                                            <img src="__ADMIN_IMG__/live_img.png?t=<?php echo time(); ?>" width="72"
                                                 height="72" alt="暂无">
                                        </div>
                                    </div>

                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑
                                            </button>
                                            <button type="reset" class="layui-btn layui-btn-primary"><i
                                                        class="fa fa-refresh"></i>&nbsp;重置
                                            </button>
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
<style>
    h4 {
        position: absolute;
        margin-top: -26px;
        margin-left: 326px;
    }
</style>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>