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
    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->
    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');
        var ues = UE.getEditor('editors');

    </script>
    <style>
        h3 {font-weight: revert;}
    </style>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：插件 &raquo; 多商户 &raquo; 商户入驻设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <form action="__CONTROLLER__/setUp" class="form-horizontal layui-form" method="post"
                                  enctype="multipart/form-data">

                                <div class="tab-content">
                                    <h3>入驻开关</h3>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">
                                            入驻开关</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="settle_in"
                                                   value="2" <?php if ($msg['settle_in'] == '2') echo 'checked'; ?>
                                                   title="开启">
                                            <input type="radio" name="settle_in"
                                                   value="1" <?php if ($msg['settle_in'] == '1') echo 'checked'; ?>
                                                   title="关闭">
                                        </div>
                                    </div>
                                    <h3>入驻权限设置</h3>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">开店权限</label>
                                        <div class="layui-input-block">
                                            <?php if (!empty($msg['authority'])) $msg['authority_method'] = explode(",", $msg['authority']); ?>
                                            <input type="checkbox" name="authority[1]"
                                                   title="会员" <?php if (in_array('1', $msg['authority_method'])) echo 'checked'; ?>>
                                            <input type="checkbox" name="authority[2]"
                                                   title="铂金" <?php if (in_array('2', $msg['authority_method'])) echo 'checked'; ?>>
                                            <input type="checkbox" name="authority[3]"
                                                   title="钻石" <?php if (in_array('3', $msg['authority_method'])) echo 'checked'; ?>>
                                            <input type="checkbox" name="authority[4]"
                                                   title="至尊" <?php if (in_array('4', $msg['authority_method'])) echo 'checked'; ?>>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">是否开启实名认证</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="verified"
                                                   value="2" <?php if ($msg['verified'] == '2') echo 'checked'; ?>
                                                   title="开启">
                                            <input type="radio" name="verified"
                                                   value="1" <?php if ($msg['verified'] == '1') echo 'checked'; ?>
                                                   title="关闭">
                                        </div>
                                    </div>
                                    <h3>保证金设置</h3>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">是否缴纳保证金</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="margin"
                                                   value="2" <?php if ($msg['margin'] == '2') echo 'checked'; ?>
                                                   title="开启">
                                            <input type="radio" name="margin"
                                                   value="1" <?php if ($msg['margin'] == '1') echo 'checked'; ?>
                                                   title="关闭">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">输入金额</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="total_amount"
                                                   value="{$msg['total_amount']}" placeholder="" style="width: 90%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">支付/提现方式</label>
                                        <div class="layui-input-block">
                                            <?php if (!empty($msg['payment'])) $msg['payment_method'] = explode(",", $msg['payment']); ?>
                                            <input type="checkbox" name="payment[alipay]"
                                                   title="支付宝" <?php if (in_array('alipay', $msg['payment_method'])) echo 'checked'; ?>>
                                            <input type="checkbox" name="payment[wxpay]"
                                                   title="微信" <?php if (in_array('wxpay', $msg['payment_method'])) echo 'checked'; ?>>
                                            <input type="checkbox" name="payment[wxpayMini]"
                                                   title="小程序支付" <?php if (in_array('wxpayMini', $msg['payment_method'])) echo 'checked'; ?>>
                                            <if condition='$msg[latipay_type] eq 1'>
                                                <input type="checkbox" name="payment[int_wx]" title="国际微信" <?php if(in_array('int_wx',$msg['payment_method'])) echo 'checked'; ?>>
                                                <input type="checkbox" name="payment[int_ali]" title="国际支付宝" <?php if(in_array('int_ali',$msg['payment_method'])) echo 'checked'; ?>>
                                            </if>
                                            <if condition='$msg[paypal_type] eq 1'>
                                                <input type="checkbox" name="payment[paypal]" title="PayPal支付" <?php if(in_array('paypal',$msg['payment_method'])) echo 'checked'; ?>>
                                            </if>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">保证金说明</label>
                                        <div class="layui-input-block">
                                            <script name="description" id="editor" type="text/plain" style="height:300px;width: 99%;">
                                                    <?php
                                                $content=htmlspecialchars_decode(html_entity_decode($msg['description']));
                                                $content=str_replace("&#39;", '"', $content);
                                                echo $content;
                                                ?>
                                                </script>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">店铺入驻介绍</label>
                                        <div class="layui-input-block">
                                            <script name="introduction" id="editors" type="text/plain" style="height:300px;width: 99%;">
                                                    <?php
                                                $contents=htmlspecialchars_decode(html_entity_decode($msg['introduction']));
                                                $contents=str_replace("&#39;", '"', $contents);
                                                echo $contents;
                                                ?>
                                                </script>
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>