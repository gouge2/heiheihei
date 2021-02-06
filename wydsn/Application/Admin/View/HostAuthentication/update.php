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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->
    <script>
        $(document).ready(function () {

            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：直播管理 &raquo; 编辑实名认证信息<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回实名认证列表 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <!--<ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#tab-1" aria-expanded="true">订单基本信息</a>
                            </li>
                        </ul>-->
                        <form action="__ACTION__/id/{$msg.real_id}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="real_id" value="{$msg['real_id']}">
                            <div class="tab-content">
                                <!-- 订单基本信息  -->
                                <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">真实姓名</label>
                                        <div class="layui-input-block">
                                            <input type="text" readonly class="layui-input" name="real_name" value="{$msg['real_name']}"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">身份证号</label>
                                        <div class="layui-input-block">
                                            <input type="text" readonly class="layui-input" name="real_card"
                                                   value="{$msg['real_card']}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">审核状态</label>
                                        <div class="layui-input-block">
                                            <select class="layui-input m-b" name="real_status">
                                                <option value="check" <?php if ($msg['real_status'] == 'check') {
                                                    echo 'selected';
                                                } ?> >审核中
                                                </option>
                                                <option value="fail" <?php if ($msg['real_status'] == 'fail') {
                                                    echo 'selected';
                                                } ?> >未通过
                                                </option>
                                                <option value="pass" <?php if ($msg['real_status'] == 'pass') {
                                                    echo 'selected';
                                                } ?> >审核通过
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">审核失败原因</label>
                                        <div class="layui-input-block">
                                            <textarea class="layui-input" rows="5" name="fail_explain">{$msg.fail_explain}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- 订单基本信息  -->

                                <div class="layui-form-item layui-layout-admin">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;提交
                                        </button>
                                        <button class="layui-btn layui-btn-primary" type="reset"><i
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>