<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <style>
        .form-labal-tilte {width: 115px;}
        .layui-input-block{margin-left: 145px;}
        #demo1{width: 140px;height: 140px;padding-left: 145px;}
        .layui-word-aux {margin-left: 145px;}
        .layui-text {background-color: #FFFFFF;}
        .layui-form {background-color: #FFFFFF;}
    </style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <blockquote class="layui-elem-quote layui-text">
            <div class="ibox-title">
                <h3>当前位置：系统设置 &raquo; APP配置设置</h3>
            </div>
        </blockquote>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>小程序首页配置</legend>
        </fieldset>

        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">APP Tab背景图片</label>
                <div class="layui-input-inline" style="width: 280px">
                    <input type="text" name="tab_img_name" autocomplete="off" disabled placeholder="上传图片" class="layui-input imgs">
                </div>
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                    <div class="layui-upload-list ">
                        <img class="layui-upload-img form-labal-tilte" id="demo1">
                        <p id="demoText"></p>
                        <span class="layui-form-mid layui-word-aux">建议尺寸750*300像素,只支持PNG格式图片</span>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">APP Tab背景颜色</label>
                <div class="layui-input-inline">
                    <input type="text" name="tab_bg_col" value="{$msg['tab_bg_col']}" placeholder="请选择颜色" class="layui-input" id="test-form-input1">
                </div>
                <div class="layui-inline" style="left: -11px;">
                    <div id="test-form1"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">APP Tab文字颜色</label>
                <div class="layui-input-inline">
                    <input type="text" name="tab_word_col" value="{$msg['tab_word_col']}" placeholder="请选择颜色" class="layui-input" id="test-form-input2">
                </div>
                <div class="layui-inline" style="left: -11px;">
                    <div id="test-form2"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">APP首页菜单来源</label>
                <div class="layui-input-inline">
                    <select name="source_head" lay-filter="aihao" class="noneEvent">
                        <option value=""></option>
                        <foreach name="headSource" item="vo">
                            <option class="noneEvent" value="{$vo.id}" <?php echo $vo['selected']?'selected':''; ?> >{$vo.name}</option>
                        </foreach>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block ">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="btns" id="sub">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"  charset="utf-8"></script>
<script>
    let tab_bg_col = '<?php echo $msg['tab_bg_col'];?>';
    let tab_word_col = '<?php echo $msg['tab_word_col'];?>';
    let tab_img = '<?php echo $msg['tab_img'];?>';

    layui.use(['form', 'upload','colorpicker'], function(){
        var $ = layui.$;
        var form = layui.form
            ,layer = layui.layer
            ,colorpicker = layui.colorpicker
            ,upload = layui.upload;

        $('#demo1').attr('src', tab_img);
        $('.imgs').val(tab_img);
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '__ACTION__' //改成您自己的上传接口
            ,auto: false
            ,method: 'post'
            ,exts: 'png'
            ,field: 'tab_img'
            ,bindAction: '#sub'
            ,acceptMime: 'image/png'
            ,choose: function (obj) {

                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result);
                    $('.imgs').val(file.name);
                });
            }
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        colorpicker.render({
            elem: '#test-form1'
            ,color: tab_bg_col
            ,done: function(color){
                $('#test-form-input1').val(color);
            }
        });
        colorpicker.render({
            elem: '#test-form2'
            ,color: tab_word_col
            ,done: function(color){
                $('#test-form-input2').val(color);
            }
        });

        //监听提交
        form.on('submit(btns)', function(data){

        });

    });
</script>

</body>
</html>