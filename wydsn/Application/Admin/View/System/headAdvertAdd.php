<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <style>
        .diys {height: 40px;line-height: 50px;}
        .fa-right {float: right;margin-top: 5px;}
        .form-labal-tilte {width: 115px;}
        .layui-input-block{margin-left: 145px;}
        .layui-input-in {width: 30%;}
        .layui-text {background-color: #FFFFFF;}
        .layui-form {background-color: #FFFFFF;}
    </style>
</head>
<body class="layui-bg-gray">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <blockquote class="layui-elem-quote layui-text diys">
            <div class="ibox-title">
                <h3 class="layui-inline">当前位置：系统设置 &raquo; APP首页活动设置 &raquo; <?php echo $resout['id'] == ''?'添加活动':'修改活动'; ?></h3>
                <a class="layui-btn fa-right" href="__CONTROLLER__/headAdvertSet">返回上一页 <i class="fa fa-angle-double-right"> >> </i></a></h3>
            </div>
        </blockquote>
        <form action="__ACTION__" lay-filter="forms"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
            <div class="layui-hide">
                <input type="text" name="id" lay-verify="title" value="{$resout['id']}"class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">模块</label>
                <div class="layui-input-inline">
                    <select name="advert_modular" lay-filter="modula">
                        <option value="1">好物推荐</option>
                        <option value="2">限时秒杀</option>
                        <option value="3">为你推荐</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">活动名称</label>
                <div class="layui-input-block layui-input-in">
                    <input type="text" name="advert_title" lay-verify="title" value="{$resout['advert_title']}" required="required" autocomplete="off" placeholder="请输入活动标题（如：好物推荐）" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">背景图片</label>
                <div class="layui-input-inline layui-input-in" style="width: 280px">
                    <input type="text" name="head_img_name" value="{$resout['advert_img']}" autocomplete="off" disabled placeholder="上传图片" class="layui-input imgs">
                </div>
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="img_upload">上传图片</button>
                    <button type="button" class="layui-btn" id="img_delete">删除图片</button>
                    <div class="layui-upload-list "><p id="demoText"></p></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">所属客户端</label>
                <div class="layui-input-inline layui-input-in">
                    <select name="advert_client" lay-filter="aihao_client" required="required">
                        <option value="">请选择客户端</option>
                        <option value="app" <?php echo $resout['advert_client'] == 'app'?'selected':''; ?> >APP</option>
                        <option value="applets" <?php echo $resout['advert_client'] == 'applets'?'selected':''; ?>  >小程序</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">活动来源</label>
                <div class="layui-input-inline ">
                    <select name="advert_source" lay-filter="aihaos" required="required"">
                    <option value="">请选择活动来源</option>
                    <foreach name="advertSource" item="vo">
                        <option id="source_{$vo.id}" value="{$vo.id}" <?php echo $vo['selected']?'selected':''; ?> >{$vo.name}</option>
                    </foreach>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">活动类型</label>
                <div class="layui-input-block">
                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="1" title="商品ID" <?php echo $resout['diy_id'] == 1?'checked':''; ?> checked="">
                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="2" title="分类" <?php echo $resout['diy_id'] == 2?'checked':''; ?> >
                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="3" title="活动类型" <?php echo $resout['diy_id'] == 3?'checked':''; ?> >
                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="4" title="自定义" <?php echo $resout['diy_id'] == 4?'checked':''; ?> >
                </div>
            </div>
            <div class="layui-form-item cat_1">
                <label class="layui-form-label form-labal-tilte">分类</label>
                <div class="layui-input-inline">
                    <select name="advert_catgray" lay-filter="aihaod" class="two_cate">
                        <option value="">请选择类别</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item cat_2">
                <label class="layui-form-label form-labal-tilte">活动</label>
                <div class="layui-input-inline">
                    <select name="advert_cat" lay-filter="aihao" id="advert_cat_dis">
                        <option value="">请选择活动</option>
                        <option value="1" id="tb_option_1">快抢商品（淘宝）</option>
                        <option value="2" id="jd_option_2">9块9专场（京东）</option>
                        <option value="3" id="jd_option_3">精选好货（京东）</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item cat_3">
                <label class="layui-form-label form-labal-tilte">商品ID</label>
                <div class="layui-input-block layui-input-in">
                    <input type="text" class="layui-input" name="advert_cat_id" value="">
                    <span class="layui-form-mid layui-word-aux">建议填入123456，123457</span>
                </div>
            </div>
            <div class="layui-form-item cat_4">
                <label class="layui-form-label form-labal-tilte">关键词</label>
                <div class="layui-input-block layui-input-in">
                    <input type="text" class="layui-input" name="advert_word" value="">
                </div>
            </div>
            <div class="layui-form-item cat_5">
                <div class="layui-inline">
                    <label class="layui-form-label form-labal-tilte">佣金区间</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="advert_amount_min" placeholder="￥" autocomplete="off" value="{$resout['advert_amount_min']}" class="layui-input">
                    </div>
                    <div class="layui-form-mid">~</div>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="advert_amount_max" placeholder="￥" autocomplete="off" value="{$resout['advert_amount_max']}" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item cat_6">
                <div class="layui-inline">
                    <label class="layui-form-label form-labal-tilte">价格区间</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="advert_price_min" placeholder="￥" autocomplete="off" value="{$resout['advert_price_min']}" class="layui-input">
                    </div>
                    <div class="layui-form-mid">~</div>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="advert_price_max" placeholder="￥" autocomplete="off" value="{$resout['advert_price_max']}" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item cat_7">
                <label class="layui-form-label form-labal-tilte">是否有券</label>
                <div class="layui-input-block">
                    <input type="radio" name="advert_coupon" value="1" <?php echo $resout['advert_coupon'] == 1?'checked':''; ?> title="有">
                    <input type="radio" name="advert_coupon" value="2" <?php echo $resout['advert_coupon'] == 2?'checked':''; ?> title="无">
                    <input type="radio" name="advert_coupon" value="3" <?php echo $resout['advert_coupon'] == 3?'checked':''; ?>  title="全部" checked="">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label form-labal-tilte">开关</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="advert_switch_open" <?php echo $resout['advert_switch'] == 1?'checked':''; ?> lay-skin="switch" lay-text="ON|OFF">
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
    let resout = '<?php echo json_encode($resout);?>';
    resouts =  eval('(' + resout + ')');
    let diy_id = '';
    if (resouts){
        diy_id = resouts['diy_id'];
    }
    let files = '';
    let resultImg = '';
    layui.use(['form', 'upload'], function(){
        var $ = layui.$;
        var form = layui.form
            ,layer = layui.layer
            ,upload = layui.upload;

        if (resouts){
            form.val('forms',resouts);
            $("[name='advert_modular']").attr("disabled", true);
            $("[name='advert_client']").attr("disabled", true);
            form.render('select');
        } else {
            dataF = form.val('forms');
            diy_id = dataF['diy_id'];
            $("[name='advert_modular']").removeAttr("disabled");
            $("[name='advert_client']").removeAttr("disabled");
            form.render('select');
        }
        if( diy_id == 1){
            $(".cat_1").css("display", "none");
            $(".cat_2").css("display", "none");
            $(".cat_3").css("display", "block");
            $(".cat_4").css("display", "none");
            $(".cat_5").css("display", "none");
            $(".cat_6").css("display", "none");
            $(".cat_7").css("display", "none");
        } else if (diy_id == 2) {
            $(".cat_1").css("display", "block");
            $(".cat_2").css("display", "none");
            $(".cat_3").css("display", "none");
            $(".cat_4").css("display", "none");
            $(".cat_5").css("display", "block");
            $(".cat_6").css("display", "block");
            $(".cat_7").css("display", "block");
            let url = '/dmooo.php/System/getSourceList?type=' + resouts.advert_source;
            $.get(url,function(data){
                if (data.length !== 0) {
                    let arrs = eval(data);
                    $(".two_cate").empty();
                    $.each(arrs,function(index,item){
                        $(".two_cate").append(new Option(item.name,item.cat_id));
                    });
                    layui.form.render("select");
                }
            });
        } else if (diy_id == 3) {
            $(".cat_1").css("display", "none");
            $(".cat_2").css("display", "block");
            $(".cat_3").css("display", "none");
            $(".cat_4").css("display", "none");
            $(".cat_5").css("display", "block");
            $(".cat_6").css("display", "block");
            $(".cat_7").css("display", "block");
        }
        var uploadInst = upload.render({
            elem: '#img_upload'
            ,url: '__ACTION__' //改成您自己的上传接口
            ,auto: false
            ,method: 'post'
            ,exts: 'png'
            ,field: 'head_imgs'
            ,bindAction: '#sub'
            ,acceptMime: 'image/png'
            ,choose: function (obj) {
                obj.preview(function(index, file, result){
                    files = file;
                    resultImg = result;
                    $('.imgs').val(file.name);
                });
            }
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                // obj.preview(function(index, file, result){
                //     $('#demo1').attr('src', result); //图片链接（base64）
                // });
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
        form.on('select(aihaos)', function(data){
            //data.value 得到被选中的值
            let url = '/dmooo.php/System/getSourceList?type=' + data.value;
            $.get(url,function(data){
                if (data.length !== 0) {
                    let arrs = eval(data);
                    $(".two_cate").empty();
                    $.each(arrs,function(index,item){
                        $(".two_cate").append(new Option(item.name,item.cat_id));
                    });
                    form.render("select");
                }
            });
            if (data.value == "tb") {
                $("#tb_option_1").attr("disabled",false);
                $("#jd_option_2").attr("disabled","disabled");
                $("#jd_option_3").attr("disabled","disabled");
                form.render('select');
            } else if (data.value == "jd") {
                $("#tb_option_1").attr("disabled","disabled");
                $("#jd_option_2").attr("disabled",false);
                $("#jd_option_3").attr("disabled",false);
                form.render('select');
            } else {
                $("#tb_option_1").attr("disabled","disabled");
                $("#jd_option_2").attr("disabled","disabled");
                $("#jd_option_3").attr("disabled","disabled");
            }
        });
        form.on('select(aihao_client)', function(data){
            if (data.value == 'applets') {
                $("#tb_option_1").attr("disabled","disabled");
                $("#source_tb").attr("disabled","disabled");
                form.render('select');
            } else {
                $("#tb_option_1").attr("disabled",false);
                $("#source_tb").attr("disabled",false);
                form.render('select');
            }
            form.val('forms',{"advert_source":""});
            form.val('forms',{"advert_catgray":""});
        });

        form.on('radio(diy_radio)', function(data){
            let source = form.val('forms');
            if(data.value == 1){
                $(".cat_1").css("display", "none");
                $(".cat_2").css("display", "none");
                $(".cat_3").css("display", "block");
                $(".cat_4").css("display", "none");
                $(".cat_5").css("display", "none");
                $(".cat_6").css("display", "none");
                $(".cat_7").css("display", "none");
                $(".two_cate").empty();
                form.val('forms',{"advert_cat":""});
                form.val('forms',{"advert_word":""});
                form.val('forms',{"advert_amount_min":""});
                form.val('forms',{"advert_amount_max":""});
                form.val('forms',{"advert_price_min":""});
                form.val('forms',{"advert_price_max":""});

            } else if (data.value == 2) {
                $(".cat_1").css("display", "block");
                $(".cat_2").css("display", "none");
                $(".cat_3").css("display", "none");
                $(".cat_4").css("display", "none");
                $(".cat_5").css("display", "block");
                $(".cat_6").css("display", "block");
                $(".cat_7").css("display", "block");
                form.val('forms',{"advert_cat":""});
                form.val('forms',{"advert_cat_id":""});
                let url = '/dmooo.php/System/getSourceList?type=' + source.advert_source;
                $.get(url,function(data){
                    if (data.length !== 0) {
                        let arrs = eval(data);
                        $(".two_cate").empty();
                        $.each(arrs,function(index,item){
                            $(".two_cate").append(new Option(item.name,item.cat_id));
                        });
                        layui.form.render("select");
                    }
                });
            } else if (data.value == 3) {
                $(".cat_1").css("display", "none");
                $(".cat_2").css("display", "block");
                $(".cat_3").css("display", "none");
                $(".cat_4").css("display", "none");
                $(".cat_5").css("display", "block");
                $(".cat_6").css("display", "block");
                $(".cat_7").css("display", "block");
                $(".two_cate").empty();

            } else {
                $(".cat_1").css("display", "none");
                $(".cat_2").css("display", "none");
                $(".cat_3").css("display", "none");
                $(".cat_4").css("display", "block");
                $(".cat_5").css("display", "block");
                $(".cat_6").css("display", "block");
                $(".cat_7").css("display", "block");
                $(".two_cate").empty();
                form.val('forms',{"advert_cat":""});
                form.val('forms',{"advert_cat_id":""});
            }
        });
        //监听提交
        form.on('submit(btns)', function(data){

        });
        $('#img_delete').on('click', function(){
            console.log(files);
            var data = form.val('forms');
            if (data.head_img_name !== "") {
                if (files.length == ""){
                    imgsrc = data.head_img_name;
                } else {
                    imgsrc = resultImg;
                }
                layer.open({
                    type: 1
                    ,title: '确认删除此图片吗？'
                    ,area: ['320px', '270px']
                    ,shade: 0
                    ,maxmin: false
                    ,content: '<img width="320" height="170" src='+imgsrc+' >'
                    ,btn: ['确认', '取消']
                    ,yes: function(){
                        if (files.length == ""){
                            let url = '/dmooo.php/System/deletsImg';
                            $.post(url,{id:data.id,head_img_name:data.head_img_name},function (res) {
                                if (res.code == 'succ'){
                                    form.val('forms',{head_img_name:""});
                                    layer.closeAll();
                                    layer.msg('删除成功');
                                }
                            },'json')
                        } else {
                            uploadInst.config.elem.next()[0].value = '';
                            $('.imgs').val('');
                            layer.closeAll();
                            layer.msg('删除成功');
                        }
                    }
                    ,btn2: function(){
                        layer.closeAll();
                    }
                    ,zIndex: layer.zIndex //重点1
                    ,success: function(layero){
                        layer.setTop(layero); //重点2
                    }
                });
            } else {
                layer.msg('无图可删');
            }
        });
    });
</script>

</body>
</html>