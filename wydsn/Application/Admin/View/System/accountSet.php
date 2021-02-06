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
    <script>
        function vipEmpower()
        {
            $.ajax({
                type:"POST",
                url:'/taokeyun.php/System/vipEmpower',
                dataType:"html",
                success:function(msg)
                {
                    //打开新页面
                    // window.open(msg);
                    //在当前窗口打开页面
                    location.replace(msg)
                }
            });
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 应用账号配置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <h3><strong style="color:red;">友情提示：该页面账号配置参数由专业技术人员配置，请勿随意修改！请妥善保管，请勿泄露！</strong></h3>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">淘宝客账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">拼多多账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-3" aria-expanded="false">极光推送账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-4" aria-expanded="false">支付宝账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-12" aria-expanded="false">微信支付</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-13" aria-expanded="false">腾讯云配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-5" aria-expanded="false">京东账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-6" aria-expanded="false">联盟推广位</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-7" aria-expanded="false">mob短信配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-8" aria-expanded="false">唯品会账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-9" aria-expanded="false">快电团油配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-10" aria-expanded="false">快递鸟配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-11" aria-expanded="false">任务系统配置</a>
                                </li>

                            </ul>
                            <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">

                                <div class="tab-content">
                                    <!-- 淘宝客账号   -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客AppID</label>
                                            <div class="layui-input-block" style="width: 94%;">
                                                <input type="text" class="layui-input" name="tbk_appid" value="{$msg['tbk_appid']}" placeholder="" style="width: 94%;">
<!--                                                <span class="layui-form-mid layui-word-aux">请填写淘宝客AppID</span>-->
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客App key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_appkey" value="{$msg['tbk_appkey']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客App secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_appsecret" value="{$msg['tbk_appsecret']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客PID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_pid" value="{$msg['tbk_pid']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客广告位ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_adzone_id" value="{$msg['tbk_adzone_id']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客渠道专用PID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_relation_pid" value="{$msg['tbk_relation_pid']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">维易淘宝客key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wy_appkey" value="{$msg['wy_appkey']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">大淘客App key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="dtk_appkey" value="{$msg['dtk_appkey']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">大淘客App secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="dtk_appsecret" value="{$msg['dtk_appsecret']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">大淘客订单开关</label>
                                            <div class="layui-input-block">
                                                <input type="radio" class="layui-input" name="dtk_search_order" title="否" value="0" <?php if($msg['dtk_search_order']==0){?>checked<?php }?> style="width: 94%;">
                                                <input type="radio" class="layui-input" name="dtk_search_order" title="是" value="1" <?php if($msg['dtk_search_order']==1){?>checked<?php }?>  style="width: 94%;">
                                                <span class="layui-form-mid layui-word-aux">
                                                    温馨提示：该选项主要针对2020年8月份之后申请不了淘宝订单查询权限的客户，改用大淘客订单查询权限。不清楚请联系对接人员
                                                    大淘客开放平台订单查询权限申请需要先申请高效转链权限并前三天有连续使用，这个将在您开启开关并配置好大淘客参数后自动调用
                                                    您只需要在大淘客开放平台申请好应用后，添加高效转链接口，把参数填写在下面，等待三天后，再去添加订单查询接口即可使用
                                                </span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝系统开关</label>
                                            <div class="layui-input-block">
                                                <input type="radio" class="layui-input" name="tbxt_switch" title="否" value="0" <?php if($msg['tbxt_switch']==0){?>checked<?php }?> style="width: 94%;">
                                                <input type="radio" class="layui-input" name="tbxt_switch" title="是" value="1" <?php if($msg['tbxt_switch']==1){?>checked<?php }?>  style="width: 94%;">
                                            </div>
                                        </div>
                                        <!--重复了，隐藏
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">广告位ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="adzone_id" value="{$msg['adzone_id']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        -->
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">联盟授权码</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="auth_code" value="{$msg['auth_code']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 拼多多账号  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多client_id</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_client_id" value="{$msg['pdd_client_id']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多client_secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_client_secret" value="{$msg['pdd_client_secret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多推广位pid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_pid" value="{$msg['pdd_pid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多系统开关</label>
                                            <div class="layui-input-block">
                                                <input type="radio" class="layui-input" name="pddxt_switch" title="否" value="0" <?php if($msg['pddxt_switch']==0){?>checked<?php }?> style="width: 94%;">
                                                <input type="radio" class="layui-input" name="pddxt_switch" title="是" value="1" <?php if($msg['pddxt_switch']==1){?>checked<?php }?>  style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 极光推送账号  -->
                                    <div id="tab-3" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">极光推送key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jpush_key" value="{$msg['jpush_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">极光推送secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jpush_secret" value="{$msg['jpush_secret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 支付宝账号  -->
                                    <div id="tab-4" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝appid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_appid" value="{$msg['alipay_appid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝私钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_private_key" value="{$msg['alipay_private_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝公钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_public_key" value="{$msg['alipay_public_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 京东账号  -->
                                    <div id="tab-5" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">京东用户id</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jd_unionid" value="{$msg['jd_unionid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">授权key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jd_auth_key" value="{$msg['jd_auth_key']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">安卓appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="android_appkey" value="{$msg['android_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">安卓appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="android_appsecret" value="{$msg['android_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">IOS appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ios_appkey" value="{$msg['ios_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">IOS appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ios_appsecret" value="{$msg['ios_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">京推推appid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jingtuitui_appid" value="{$msg['jingtuitui_appid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">京推推appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jingtuitui_appkey" value="{$msg['jingtuitui_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">京东系统开关</label>
                                            <div class="layui-input-block">
                                                <input type="radio" class="layui-input" name="jdxt_switch" title="否" value="0" <?php if($msg['jdxt_switch']==0){?>checked<?php }?> style="width: 94%;">
                                                <input type="radio" class="layui-input" name="jdxt_switch" title="是" value="1" <?php if($msg['jdxt_switch']==1){?>checked<?php }?>  style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 联盟推广位置  -->
                                    <div id="tab-6" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 1</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[1]" value="{$msg['tk_pid'][1]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 2</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[2]" value="{$msg['tk_pid'][2]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 3</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[3]" value="{$msg['tk_pid'][3]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 4</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[4]" value="{$msg['tk_pid'][4]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 5</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[5]" value="{$msg['tk_pid'][5]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 6</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[6]" value="{$msg['tk_pid'][6]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 7</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[7]" value="{$msg['tk_pid'][7]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 8</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[8]" value="{$msg['tk_pid'][8]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 9</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[9]" value="{$msg['tk_pid'][9]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 10</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[10]" value="{$msg['tk_pid'][10]}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- mob账号  -->
                                    <div id="tab-7" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">mob账号appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="mob_appkey" value="{$msg['mob_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">mob账号appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="mob_appsecret" value="{$msg['mob_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">mob短信模版配置</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="mob_template" value="{$msg['mob_template']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">mob短信国际模版配置</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="int_mob_template" value="{$msg['int_mob_template']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;"><a  class="layui-btn pull-right" href="__CONTROLLER__/countryCode" "="">国家编码配置</a></label>
                                        </div>
                                    </div>

                                    <!-- 唯品会账号  -->
                                    <div id="tab-8" class="tab-pane" style="padding-top: 10px">
                                        <h3><strong style="color:red;">友情提示：唯品会授权无需点击下方编辑提交！</strong></h3><br>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">唯品会账号授权</label>
                                            <div class="layui-input-block">
                                                <button type="button" class="layui-btn layui-btn-xm" onclick="vipEmpower();">点击唯品会授权或更换</button>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">唯品会授权token</label>
                                            <div class="layui-input-block">
                                                <div style="padding-top: 8px">{$msg['vip_accesstoken']}</div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">唯品会授权过期时间</label>
                                            <div class="layui-input-block">
                                                <div style="padding-top: 8px;color:red;">{$msg['vip_expiresdate']}</div>
                                            </div>
                                        </div>
<!--                                        <div class="layui-form-item">-->
<!--                                            <label class="layui-form-label" style="width: 190px;">唯品会账号appkey</label>-->
<!--                                            <div class="layui-input-block">-->
<!--                                                <input type="text" class="layui-input" name="vip_appkey" value="{$msg['vip_appkey']}" style="width: 94%;">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="layui-form-item">-->
<!--                                            <label class="layui-form-label" style="width: 190px;">唯品会账号appsecret</label>-->
<!--                                            <div class="layui-input-block">-->
<!--                                                <input type="text" class="layui-input" name="vip_appsecret" value="{$msg['vip_appsecret']}" style="width: 94%;">-->
<!--                                            </div>-->
<!--                                        </div>-->
                                    </div>

                                    <!-- 快电团油配置  -->
                                    <div id="tab-9" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油渠道名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ty_channel_name" value="{$msg['ty_channel_name']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油渠道编码</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ty_channel_coding" value="{$msg['ty_channel_coding']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ty_key" value="{$msg['ty_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ty_secret" value="{$msg['ty_secret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油环境类型</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="ty_type" value="1" <?php if($msg['ty_type']=='1') echo 'checked'; ?> title="测试">
                                                <input type="radio" name="ty_type" value="2" <?php if($msg['ty_type']=='2') echo 'checked'; ?> title="正式">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">根据团油平台申请状态勾选和填写</span>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">团油链接</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ty_link" value="{$msg['ty_link']}" style="width: 94%;">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">根据团油环境类型填写相应链接</span>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">快电key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="kd_key" value="{$msg['kd_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 快递鸟配置  -->
                                    <div id="tab-10" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">快递鸟用户ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="kdn_id" value="{$msg['kdn_id']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">快递鸟apikey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="kdn_apikey" value="{$msg['kdn_apikey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 任务系统配置  -->
                                    <div id="tab-11" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">渠道别名</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="task_name" value="{$msg['task_name']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">渠道秘钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="task_pwd" value="{$msg['task_pwd']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>



                                    <!-- 微信支付 -->
                                    <div id="tab-12" class="tab-pane" style="padding-top: 10px">
                                        <h3><strong style="color:red;">友情提示：使用前请先开通微信支付的APP支付权限，并且和开放平台的应用做好绑定！</strong></h3><br>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">开放平台APPID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wxpay_appid" value="{$msg['wxpay_appid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">开放平台APPSECRET</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wxpay_appsecret" value="{$msg['wxpay_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">商户号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wxpay_merchid" value="{$msg['wxpay_merchid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">api密钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wxpay_apikey" value="{$msg['wxpay_apikey']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item layui-form-text">
                                            <label class="layui-form-label" style="width: 190px;">apiclient_cert：</label>
                                            <div class="layui-input-inline">
                                                <textarea name="wxpay_cert" placeholder="请下载证书复制apiclient_cert.pem的内容" class="layui-textarea" style="min-width: 500px">{$msg['wxpay_cert']}</textarea>

                                            </div>

                                        </div>

                                        <div class="layui-form-item layui-form-text">
                                            <label class="layui-form-label" style="width: 190px;">apiclient_key：</label>
                                            <div class="layui-input-inline">
                                                <textarea name="wxpay_key" placeholder="请下载证书复制apiclient_key.pem的内容" class="layui-textarea" style="min-width: 500px">{$msg['wxpay_key']}</textarea>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- 腾讯云配置 -->
                                    <div id="tab-13" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云secretId</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_secretid" value="{$msg['tencent_secretid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云secretKey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_secretkey" value="{$msg['tencent_secretkey']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯IM sdkappid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_im_sdkappid" value="{$msg['tencent_im_sdkappid']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯IM key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_im_key" value="{$msg['tencent_im_key']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯IM admin</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_im_admin" value="{$msg['tencent_im_admin']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播 key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_live_key" value="{$msg['tencent_live_key']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播回调 key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_live_call_key" value="{$msg['tencent_live_call_key']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播推流域名</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_live_push_domain" value="{$msg['tencent_live_push_domain']}" style="width: 94%;">
                                                <span class="layui-form-mid layui-word-aux">多个以中文‘，’逗号分开</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播拉流域名</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_live_pull_domain" value="{$msg['tencent_live_pull_domain']}" style="width: 94%;">
                                                <span class="layui-form-mid layui-word-aux">多个以中文‘，’逗号分开</span>
                                            </div>
                                        </div>


                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播 Licence</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_licence_url" value="{$msg['tencent_licence_url']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云直播 Licence_key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_licence_key" value="{$msg['tencent_licence_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">腾讯云点播 Licence</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tencent_licence_url_ugc" value="{$msg['tencent_licence_url_ugc']}" style="width: 94%;">
                                            </div>
                                        </div>

                                    </div>

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