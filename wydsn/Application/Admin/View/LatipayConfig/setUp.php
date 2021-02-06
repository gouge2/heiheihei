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
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：插件 &raquo; 纽元通支付设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">

                            <form action="__ACTION__" class="form-horizontal layui-form" method="post"
                                  enctype="multipart/form-data">

                                <div class="tab-content">
                                    <input type="hidden" name="id" value="1">
                                    <!-- 纽元通账号  -->
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 190px;">纽元通用户id</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="latipay_uid"
                                                   value="{$msg['latipay_uid']}" style="width: 94%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 190px;">纽元通钱包id</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="latipay_wallet"
                                                   value="{$msg['latipay_wallet']}" style="width: 94%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 190px;">纽元通秘钥</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="latipay_key"
                                                   value="{$msg['latipay_key']}" style="width: 94%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 190px;">纽元通支付网关</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="latipay_url"
                                                   value="{$msg['latipay_url']}" style="width: 94%;">
                                        </div>
                                    </div>

                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th>类型名称</th>
                                            <th>实时汇率(今日)</th>
                                            <th>系统使用汇率</th>
                                            <th>汇率换算</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>今日新西兰元(纽元)兑换人民币汇率</td>
                                                <td>{$msg['exchange']}</td>
                                                <td><input type="text" class="layui-input" name="my_exchange" value="{$msg['my_exchange']}" style="width: 94%;"></td>
                                                <td>
                                                    <?php if (!empty($msg['my_exchange'])) $msg['exchange'] = $msg['my_exchange'] ?>
                                                    1新西兰元兑换{$msg['exchange']}人民币 1NZD={$msg['exchange']}CNY
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                            <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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