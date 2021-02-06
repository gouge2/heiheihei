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
                    <h3>当前位置：商城系统 &raquo; 发票信息管理 &raquo; 添加发票<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__" class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">发票类型</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="type" value="1" checked title='企业'>
                                        <input type="radio" name="type" value="2" title='个人'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">所属用户</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="user_account"
                                           placeholder="必填，请填写用户名/手机号码/邮箱">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">发票抬头</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="purchaser"
                                           placeholder="必填，请输入公司名称或个人姓名">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">纳税人识别号</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="taxpayer_id"
                                           placeholder="请输入企业纳税人识别号">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">开户行</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="bank" placeholder="请填写开户行名称">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">账号</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="account" placeholder="请填写账号">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">联系电话</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="contact" placeholder="请填写联系电话">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">收件地址</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="address" placeholder="请填写收件地址">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">收件人</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="linkman" placeholder="请填写收件人">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否默认</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_default" value="Y" checked title='是'>
                                        <input type="radio" name="is_default" value="N" title='否'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;添加发票
                                    </button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i
                                                class="fa fa-refresh"></i>&nbsp;重置
                                    </button>
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