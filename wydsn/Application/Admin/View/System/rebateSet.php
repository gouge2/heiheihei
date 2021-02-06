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
        $(document).ready(function(){
            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 返利设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_rebate_method" value="{$msg['rebate_method']}">
                            <input type="hidden" name="old_rebate_time" value="{$msg['rebate_time']}">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">订单返利方式</label>
                                <div class="layui-input-block" style="width: 80%;">
                                    <input type="radio" name="rebate_method" value="1" <?php if($msg['rebate_method']=='1') echo 'checked'; ?> title="每月某天">
                                    <input type="radio" name="rebate_method" value="2" <?php if($msg['rebate_method']=='2') echo 'checked'; ?> title="订单结算后多少天">

                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">订单返利时间</label>
                                <div class="layui-input-block" style="width: 80%;">
                                    <input type="text" class="layui-input" name="rebate_time" value="{$msg['rebate_time']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">如果返利方式是每月某天则此天结算上月已结算的订单佣金给用户，反之订单结算n天后，分佣到用户账户(可提现)</span>
                            </div>
<!--                            <div class="layui-form-item">-->
<!--                                <label class="layui-form-label" style="width: 120px;">自营订单返利时间</label>-->
<!--                                <div class="layui-input-block" style="width: 80%;">-->
<!--                                    <input type="text" class="layui-input" name="rebate_times" value="{$msg['rebate_times']}" placeholder="" style="width: 80%;">-->
<!--                                </div>-->
<!--                                <span class="layui-form-mid layui-word-aux">订单结算n天后，分佣到用户账户(可提现)</span>-->
<!--                            </div>-->

                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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