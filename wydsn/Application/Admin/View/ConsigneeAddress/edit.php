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
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="__JS__/area.js"></script>
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
                    <h3>当前位置：商城系统 &raquo; 收货地址管理 &raquo; 编辑收货地址<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$msg.id}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" >所属用户</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="user_account"
                                           value="{$msg.user_account}" placeholder="必填，请填写用户名/手机号码/邮箱">
                                </div>
                            </div>
                            <div class="layui-form-item" id="area-picker">
                                <label class="layui-form-label" >地区</label>
                                <div class="layui-input-inline">
                                    <select name="province" id="province" class="province-selector" data-value="{$msg['province']}" 
                                            style="width:100px"></select>
                                </div>
                                <div class="layui-input-inline">
                                    <select name="city" id="city" class="city-selector" data-value="{$msg['city']}" 
                                            style="width:100px"></select>
                                </div>
                                <div class="layui-input-inline">
                                    <select name="county" id="county" class="county-selector" data-value="{$msg['county']}" 
                                            style="width:100px"></select>
                                </div>
                                
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >详细地址</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="detail_address"
                                           value="{$msg.detail_address}" placeholder="必填，请输入详细地址">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >公司名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="company" value="{$msg.company}"
                                           placeholder="请输入公司名称">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >收件人</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="consignee" value="{$msg.consignee}"
                                           placeholder="必填，请填写收件人">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >联系电话</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="contact_number"
                                           value="{$msg.contact_number}" placeholder="必填，请填写联系电话">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >邮编</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="postcode" value="{$msg.postcode}"
                                           placeholder="请填写邮编">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" >是否默认</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_default"
                                               value="Y" <?php if ($msg['is_default'] == 'Y') {
                                            echo 'checked';
                                        } ?> title="是">
                                        <input type="radio" name="is_default"
                                               value="N" <?php if ($msg['is_default'] == 'N') {
                                            echo 'checked';
                                        } ?> title="否">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑收货地址
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
<script>
    //配置插件目录
    layui.config({
        base: '__LAYUIADMIN__/mods/'
        , version: '1.0'
    });
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'layarea'], function () {
        var layer = layui.layer
            , form = layui.form
            , layarea = layui.layarea;

        layarea.render({
            elem: '#area-picker',
            change: function (res) {
                //选择结果
                console.log(res);
            }
        });
    });
</script>
</body>
</html>