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

    <link href="__ADMIN_CSS__/img.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css"/>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <style>
        body #preview {
            overflow: hidden !important;
        }

        #st a {
            width: 100px;
            margin-right: 61px;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：插件 &raquo; 插件管理</h3>
                </div>
                <div class="ibox-content">
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="width: 25%">插件名称</th>
                                    <th style="width: 25%">说明</th>
                                    <th style="width: 25%">状态</th>
                                    <th style="width: 25%">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <foreach name="list" item="l">
                                    <tr>
                                        <td>多商户
                                            <h4 style="position: relative; margin-top: -17px;margin-left: 50px;color: red;">
                                                【测试版】</h4></td>
                                        <td>多商户系统</td>
                                        <td>
                                            <?php $cat_str = $l['type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$l['id']}" lay-skin="switch" lay-text="开通|关闭"
                                                   lay-filter="cat_show" {$cat_str}>
                                        </td>
                                        <td>
                                            <?php $none = $l['type'] ? 'block' : 'none'; ?>
                                            <div id='st' style="display: <?php echo $none; ?>">
                                                <a class="layui-btn pull-right" target="_blank"
                                                   href="/merchant/web/index.php?c=site&a=entry&m=shop&do=web&r=merch">进入后台</a>
                                                <a style="width: 142px;" class="layui-btn pull-right"
                                                   href="__CONTROLLER__/setUp"" >商户入驻设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>纽元通海外支付</td>
                                        <td>开启后可以使用海外支付</td>
                                        <td>
                                            <?php $cat_str1 = $l['latipay_type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$list['latipay_type']}" lay-skin="switch"
                                                   lay-text="开通|关闭"
                                                   lay-filter="cat_show1" {$cat_str1}>
                                        </td>
                                        <td>
                                            <?php $none1 = $l['latipay_type'] ? 'block' : 'none'; ?>
                                            <div id='st1' style="display: <?php echo $none1; ?>">
                                                <a style="width: 142px;margin-right: 119px;"
                                                   class="layui-btn pull-right"
                                                   href="__CONTROLLER__/../LatipayConfig/setUp?id=1"" >参数设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Twitter登录</td>
                                        <td>开启后可以使用推特登录</td>
                                        <td>
                                            <?php $cat_str2 = $l['twitter_type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$list['twitter_type']}" lay-skin="switch"
                                                   lay-text="开通|关闭"
                                                   lay-filter="cat_show2" {$cat_str2}>
                                        </td>
                                        <td>
                                            <?php $none2 = $l['twitter_type'] ? 'block' : 'none'; ?>
                                            <div id='st2' style="display: <?php echo $none2; ?>">
                                                <a style="width: 142px;margin-right: 119px;"
                                                   class="layui-btn pull-right"
                                                   href="__CONTROLLER__/../LatipayConfig/setUp?id=2"" >参数设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Facebook登录</td>
                                        <td>开启后可以使用Facebook登录</td>
                                        <td>
                                            <?php $cat_str3 = $l['facebook_type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$list['facebook_type']}" lay-skin="switch"
                                                   lay-text="开通|关闭"
                                                   lay-filter="cat_show3" {$cat_str3}>
                                        </td>
                                        <td>
                                            <?php $none3 = $l['facebook_type'] ? 'block' : 'none'; ?>
                                            <div id='st3' style="display: <?php echo $none3; ?>">
                                                <a style="width: 142px;margin-right: 119px;background-color: #7a8685;"
                                                   class="layui-btn pull-right">不需设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PayPal支付</td>
                                        <td>开启后可以使用PayPal支付功能</td>
                                        <td>
                                            <?php $cat_str4 = $l['paypal_type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$list['paypal_type']}" lay-skin="switch"
                                                   lay-text="开通|关闭"
                                                   lay-filter="cat_show4" {$cat_str4}>
                                        </td>
                                        <td>
                                            <?php $none4 = $l['paypal_type'] ? 'block' : 'none'; ?>
                                            <div id='st4' style="display: <?php echo $none4; ?>">
                                                <a style="width: 142px;margin-right: 119px;"
                                                   class="layui-btn pull-right"
                                                   href="__CONTROLLER__/../LatipayConfig/setUp?id=3"" >参数设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>多语言</td>
                                        <td>多语言功能</td>
                                        <td>
                                            <?php $cat_str5 = $l['paypal_type'] ? 'checked' : ''; ?>
                                            <input type="checkbox" value="{$list['paypal_type']}" lay-skin="switch"
                                                   lay-text="开通|关闭"
                                                   lay-filter="cat_show5" {$cat_str5}>
                                        </td>
                                        <td>
                                            <?php $none5 = $l['paypal_type'] ? 'block' : 'none'; ?>
                                            <div id='st5' style="display: <?php echo $none5; ?>">
                                                <a style="width: 142px;margin-right: 119px;" class="layui-btn pull-right"
                                                   href="__MODULE__/MultiLanguage/countryList">多语言设置</a>
                                            </div>
                                        </td>
                                    </tr>
                                </foreach>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script type="text/javascript">
    layui.use('form', function () {
        var form = layui.form;

        // 多商户开关
        form.on('switch(cat_show)', function (data) {
            let sw = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭多商户后会造成商户所上架的商品全部下架（数据不会清除，只是做商品下架处理），订单无法继续向下一步流程执行，请确认是否关闭多商户功能？';
            let msg2 ='是否已经仔细阅读上一提示警告内容，再次确认是否关闭多商户功能?';
            let url = "__CONTROLLER__/catShow";
            let std = 'st';
            Plug_in_switch(std,sw,cid,msg1,msg2,url);
        });

        //  纽元通支付
        form.on('switch(cat_show1)', function (data) {
            let sw1 = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid1 = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭纽元通将停用该支付功能？';
            let msg2 = '是否已经仔细阅读上一提示警告内容，再次确认是否关闭纽元通功能?';
            let url = "__CONTROLLER__/../LatipayConfig/catShow";
            let std1 = 'st1';
            Plug_in_switch(std1, sw1, cid1, msg1, msg2, url);
        });

        //  推特
        form.on('switch(cat_show2)', function (data) {
            let sw2 = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid2 = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭推特将停用该登录功能？';
            let msg2 = '是否已经仔细阅读上一提示警告内容，再次确认是否关闭推特功能?';
            let url = "__CONTROLLER__/../LatipayConfig/catShow";
            let std2 = 'st2';
            Plug_in_switch(std2, sw2, cid2, msg1, msg2, url);
        });

        //  Facebook
        form.on('switch(cat_show3)', function (data) {
            let sw3 = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid3 = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭Facebook将停用该登录功能？';
            let msg2 = '是否已经仔细阅读上一提示警告内容，再次确认是否关闭Facebook功能?';
            let url = "__CONTROLLER__/../LatipayConfig/catShow";
            let std3 = 'st3';
            Plug_in_switch(std3, sw3, cid3, msg1, msg2, url);
        });

        //  paypal
        form.on('switch(cat_show4)', function (data) {
            let sw4 = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid4 = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭PayPal将停用该支付功能？';
            let msg2 = '是否已经仔细阅读上一提示警告内容，再次确认是否关闭PayPal功能?';
            let url = "__CONTROLLER__/../LatipayConfig/catShow";
            let std4 = 'st4';
            Plug_in_switch(std4, sw4, cid4, msg1, msg2, url);
        });

        //  多语言
        form.on('switch(cat_show5)', function (data) {
            let sw5 = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid5 = data.value;                  // 开关value值，也可以通过data.elem.value得到
            let msg1 = '请谨慎执行此项操作，关闭多语言将停用该支付功能？';
            let msg2 = '是否已经仔细阅读上一提示警告内容，再次确认是否关闭多语言l功能?';
            let url = "__CONTROLLER__/../LatipayConfig/catShow";
            let std5 = 'st5';
            Plug_in_switch(std5, sw5, cid5, msg1, msg2, url);
        });

        function Plug_in_switch(std, sw, cid, msg1, msg2, url) {
            if (sw == 2) {
                swal({
                        title: msg1,
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: "去意已决",
                        cancelButtonText: "我再想想",
                        confirmButtonColor: "#b7a7a7",
                        closeOnConfirm: false
                    }, function (isConfirm) {
                        if (isConfirm) {
                            swal({
                                    title: msg2,
                                    text: "",
                                    type: "warning",
                                    showCancelButton: true,
                                    cancelButtonText: "取消",
                                    confirmButtonColor: "#b7a7a7",
                                    confirmButtonText: "确认关闭",
                                    closeOnConfirm: false
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        swal({
                                            title: "关闭成功！",
                                            type: "success"
                                        }, function () {
                                            $.ajax({
                                                type: "POST",
                                                url: url,
                                                data: {"sw": sw, "cid": cid, "std": std}
                                            });
                                            document.getElementById(std).style.display = "none";
                                        })
                                    } else {
                                        swal({
                                            title: "已取消",
                                            text: "您取消了关闭操作！",
                                            type: "error"
                                        })
                                        location.reload();
                                    }
                                }
                            )
                        } else {
                            swal({
                                title: "已取消",
                                text: "您取消了关闭操作！",
                                type: "error"
                            })
                            location.reload();
                        }
                    }
                )

            } else {
                document.getElementById(std).style.display = "block";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"sw": sw, "cid": cid, "std": std}
                });
            }
        }
    });
</script>
<style>
    .sweet-alert button.cancel {
        background-color: rgb(221, 107, 85);
    }
</style>
</body>
</html>