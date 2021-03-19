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
    <script src="__ADMIN_JS__/userRegister.js"></script>
</head>
<style>
    .new_label {width: auto; padding: 9px 3px;}
</style>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action=""  class="form-horizontal layui-form" lay-filter="mod_form" method="post" enctype="multipart/form-data">

                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 110px;">比例设置</label>
                                    <label class="layui-form-label new_label">1<?php echo $msg['gift_money_cn'] ? $msg['gift_money_cn'] : '鹿角'; ?>=</label>
                                    <div class="layui-input-inline" style="width: 40px;">
                                        <input type="text" name="gift_d_ratio" placeholder="" lay-verify="number" autocomplete="off" class="layui-input" value="{$msg.gift_d_ratio}">
                                    </div>
                                    <label class="layui-form-label new_label"><?php echo $msg['gift_deer_cn'] ? $msg['gift_deer_cn'] : '翠花币'; ?></label>
                                </div>
                                
                                <div class="layui-inline">
                                    <label class="layui-form-label new_label">1元=</label>
                                    <div class="layui-input-inline" style="width: 50px;">
                                        <input type="text" name="gift_r_ratio" placeholder="" lay-verify="number" autocomplete="off" class="layui-input" value="{$msg.gift_r_ratio}">
                                    </div>
                                    <label class="layui-form-label new_label"><?php echo $msg['gift_deer_cn'] ? $msg['gift_deer_cn'] : '翠花币'; ?></label>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label new_label">主播扣费百分比</label>
                                    <div class="layui-input-inline" style="width: 40px;">
                                        <input type="text" name="gift_cost" placeholder="" lay-verify="number" autocomplete="off" class="layui-input" value="{$msg.gift_cost}">
                                    </div>
                                    <label class="layui-form-label new_label">%</label>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label new_label">兑换{$msg.gift_money_cn}最少金额</label>
                                    <div class="layui-input-inline" style="width: 40px;">
                                    <input type="text" name="gift_convert_min" placeholder="" lay-verify="number" autocomplete="off" class="layui-input" value="{$msg.gift_convert_min}">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label new_label">提取最少金额</label>
                                    <div class="layui-input-inline" style="width: 40px;">
                                    <input type="text" name="gift_extract_min" placeholder="" lay-verify="number" autocomplete="off" class="layui-input" value="{$msg.gift_extract_min}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 110px;">礼物单位中文名</label>
                                    <div class="layui-input-inline" style="width: 120px;margin:0">
                                        <input type="text" name="gift_money_cn" lay-verify="required" value="{$msg.gift_money_cn}" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 110px;">礼物转化货币中文名</label>
                                    <div class="layui-input-inline" style="width: 120px;margin:0">
                                        <input type="text" name="gift_deer_cn" lay-verify="required" value="{$msg.gift_deer_cn}" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">礼物单位描述</label>
                                <div class="layui-input-block">
                                    <textarea type="text" name="gift_money_dsc" placeholder="礼物单位的描述" autocomplete="off" class="layui-textarea" lay-verify="required">{$msg.gift_money_dsc}</textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">货币描述</label>
                                <div class="layui-input-block">
                                    <textarea type="text" name="gift_deer_dsc" placeholder="礼物转化货币描述" autocomplete="off" class="layui-textarea" lay-verify="required">{$msg.gift_deer_dsc}</textarea>
                                </div>
                            </div>

                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label" style="width: 110px;">礼物货币提取描述</label>
                                <div class="layui-input-block">
                                    <textarea type="text" name="gift_wd_dsc" placeholder="礼物货币提取描述" autocomplete="off" class="layui-textarea"  lay-verify="required">{$msg.gift_wd_dsc}</textarea>
                                </div>
                            </div>
                            

                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit" id="LAY-user-back-submit" value="提交">
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