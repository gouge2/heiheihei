<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <style>
        .titles {height: 42px;line-height: 50px;}
        .layui-input-block{margin-left: 145px;}
        .layui-text {background-color: #FFFFFF;}
        .layui-form {background-color: #FFFFFF;}
        .diy-lab {width: 140px;}
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <blockquote class="layui-elem-quote layui-text titles">
            <div class="ibox-title">
                <h3>当前位置：系统设置 &raquo; 小程序配置设置</h3>
            </div>
        </blockquote>
        <form action="__ACTION__"  class="form-horizontal layui-form"  lay-filter="appletsf" method="post" enctype="multipart/form-data">
            <div class="layui-form-item">
                <div class="layui-form-item">
                    <label class="layui-form-label diy-lab">小程序上架开关</label>
                    <div class="layui-input-block">
                        <input type="radio" name="putaway_switch" value="1" <?php if ($msg['putaway_switch'] == 1) echo 'checked'; ?> title="开">
                        <input type="radio" name="putaway_switch" value="0" <?php if ($msg['putaway_switch'] == 0) echo 'checked'; ?> title="关">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">AppId</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="appid" style="width: 50%;">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">AppSecret</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="appsecret" style="width: 50%;">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">红包开关</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="red_packet_switch" lay-skin="switch" lay-text="ON|OFF">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">主播PK开关</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="anchor_pk_switch" lay-skin="switch" lay-text="ON|OFF">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label" style="width: 10%;">小程序商城开关</label>
                <div class="layui-input-block">
                    <?php if(!empty($msgs['platform_system'])) $msgs['mall_method'] = explode(",",$msgs['platform_system']);?>
                    <input type="checkbox" name="mall_method[jd]" title="京东" <?php if(in_array('jd',$msgs['mall_method'])) echo 'checked'; ?>>
                    <input type="checkbox" name="mall_method[pdd]" title="拼多多" <?php if(in_array('pdd',$msgs['mall_method'])) echo 'checked'; ?>>

                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab"></label>
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="btns">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"  charset="utf-8"></script>
<script>
    let data = <?php echo $msg;?>;
    layui.use(['form'], function(){
        var form = layui.form;
        form.val('appletsf',data);
        form.on('submit(btns)');
    });
</script>

</body>
</html>