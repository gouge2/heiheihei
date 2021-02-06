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
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <blockquote class="layui-elem-quote layui-text titles">
            <div class="ibox-title">
                <h3>当前位置：系统设置 &raquo; 导航栏自定义</h3>
            </div>
        </blockquote>
        <form action="__ACTION__"  class="form-horizontal layui-form"  lay-filter="appletsf" method="post" enctype="multipart/form-data">
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">安卓端</label>
                <div class="layui-input-block">
                    <volist name="list" id="vo">
                        <input type="checkbox" name="nav[{$key}]" title="{$vo}">
                    </volist>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">苹果端</label>
                <div class="layui-input-block">
                    <volist name="list" id="vo">
                        <input type="checkbox" name="nav_ios[{$key}]" title="{$vo}">
                    </volist>
                </div>

            </div>
            <div class="layui-form-item">
                <label class="layui-form-label diy-lab">小程序端</label>
                <volist name="applet_list" id="vo">
                    <div class="layui-inline">
                        <input type="checkbox" name="nav_applet[{$key}]" title="{$vo}">
                    </div>
                </volist>
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    let data = <?php echo $resource;?>;
    layui.use(['form'], function(){layui.form.val('appletsf',data);});
</script>
</body>
</html>