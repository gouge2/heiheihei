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

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="" class="form-horizontal layui-form" lay-filter="mod_form" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="id">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">广告头像</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="avatar" value="" placeholder="请上传广告头像图片"
                                           autocomplete="off" class="layui-input" lay-verify="required">
                                </div>
                                <button type="button" class="layui-btn" id="cover"><i class="layui-icon"></i>上传图片
                                </button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary cover_file"
                                        style="margin-left: 15px;display: none;" onclick="preview_mc('img')">预览图片
                                </button>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">广告主名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="advertiser_name" value="" autocomplete="off"
                                           class="layui-input" lay-verify="required">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">广告标题</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" value="" autocomplete="off" class="layui-input" lay-verify="required">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">封面图片</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="cover_url" value="" placeholder="请上传视频封面图片"
                                           autocomplete="off" class="layui-input" lay-verify="required">
                                </div>
                                <button type="button" class="layui-btn" id="cover2"><i class="layui-icon"></i>上传图片
                                </button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary cover_file"
                                        style="margin-left: 15px;display: none;" onclick="preview_mc('img2')">预览图片
                                </button>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">视频地址</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="media_url" lay-verify="required" value=""
                                           placeholder="请上传视频地址" autocomplete="off" class="layui-input">
                                </div>
                                <button type="button" class="layui-btn" id="media"><i class="layui-icon"></i>上传视频
                                </button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary media_file"
                                        style="margin-left: 15px;display: none;" onclick="preview_mc('mp4')">预览视频
                                </button>

                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">渠道链接</label>
                                <div class="layui-input-block">
                                    <input type="text" name="channel" value="" autocomplete="off"
                                           class="layui-input" lay-verify="required">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">功能类型</label>
                                <div>
                                    <div class="layui-input-block">
                                        <select class="layui-input m-b" name="type" lay-filter="aihaos" required="required"" id="source">
                                        <foreach name="advertSource" item="vo">
                                            <option value="{$vo.id}" <?php echo ($vo['id'] == $short['type']) ? 'selected' : ''; ?> >{$vo.name}</option>
                                        </foreach>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item hdlx" style="display: none">
                                <label class="layui-form-label form-labal-tilte">活动类型</label>
                                <div class="layui-input-block">
                                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="1" title="商品ID"  <?php if ($short['diy_id'] == 1) echo 'checked'; ?>>
                                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="2" title="分类" <?php if ($short['diy_id'] == 2) echo 'checked'; ?>>
                                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="3" title="活动类型" <?php if ($short['diy_id'] == 3) echo 'checked'; ?>>
                                    <input type="radio" lay-filter="diy_radio" name="diy_id"  value="4" title="自定义" <?php if ($short['diy_id'] == 4) echo 'checked'; ?>>
                                </div>
                            </div>
                            <div class="layui-form-item cat_1" style="display: none">
                                <label class="layui-form-label form-labal-tilte">分类</label>
                                <div class="layui-input-inline">
                                    <select name="advert_catgray" lay-filter="aihaod" class="two_cate">
                                        <option value="">请选择类别</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item cat_2" style="display: none">
                                <label class="layui-form-label form-labal-tilte">活动</label>
                                <div class="layui-input-inline">
                                    <select name="advert_cat" lay-filter="aihao" id="advert_cat_dis">
                                        <option value="">请选择活动</option>
                                        <option value="1" id="tb_option_1" disabled=disabled>快抢商品（淘宝）</option>
                                        <option value="2" id="jd_option_2" disabled=disabled>9块9专场（京东）</option>
                                        <option value="3" id="jd_option_3" disabled=disabled>精选好货（京东）</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item cat_3">
                                <label class="layui-form-label form-labal-tilte">商品ID</label>
                                <div class="layui-input-block layui-input-in">
                                    <input type="text" class="layui-input" name="advert_cat_id" value="{$short['advert_cat_id']}">
                                    <span class="layui-form-mid layui-word-aux">建议填入123456，123457</span>
                                </div>
                            </div>
                            <div class="layui-form-item cat_4">
                                <label class="layui-form-label form-labal-tilte">关键词</label>
                                <div class="layui-input-block layui-input-in">
                                    <input type="text" class="layui-input" name="advert_word" value="{$short['advert_word']}">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">开始日期</label>
                                <div class="layui-input-block">
                                    <input id="start" class="layui-input" name="start_time" lay-verify="datetime"
                                           placeholder="" style="width: 92%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">结束日期</label>
                                <div class="layui-input-block">
                                    <input id="end" class="layui-input" name="end_time" lay-verify="datetime"
                                           style="width: 92%;" placeholder="">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">预备接口</label>
                                <div class="layui-input-block">
                                    <input type="text" name="interface" value="" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">状态</label>
                                <div class="layui-input-block">
                                    <?php
                                    foreach ($short['status'] as $k => $v) {
                                        $che = (isset($short['is_status']) && $short['is_status'] == $k) ? 'checked="true"' : '';
                                        echo '<input type="radio" name="is_status" value="' . $k . '" title="' . $v['name'] . '" ' . $che . ' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>' . $v['name'] . '</div>
                                                  </div>';
                                    }
                                    ?><p style="position: absolute;margin-top: -25px;margin-left: 18%;color: red;">注意: 广告结束日期要大于当前日期，否则无法上架!</p>
                                </div>
                            </div>

                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit"
                                       id="LAY-user-back-submit" value="提交">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    let short_id = "{$short.id}";
    let cover_avatar = "{$short.cover_avatar}";   // 图片预览地址
    let cover_show = "{$short.cover_show}";   // 视频封面预览地址
    let media_show = "{$short.media_show}";   // 短视频预览地址

    // 显示视频预览
    if (media_show != '') {
        $('.media_file').show();
    }

    // 显示图片预览
    if ((cover_avatar != '' || cover_show != '') && short_id != 0) {
        $('.cover_file').show();
    }

    // 图片框改变
    $('input[name="cover_url"]').on("input", function (e) {
        cover_show = e.delegateTarget.value;
    });

    // 视频框改变
    $('input[name="media_url"]').on("input", function (e) {
        let str = e.delegateTarget.value;

        if (!!str.match(/\.mp4$/)) {
            media_show = str;
            $('.media_file').show();
        } else {
            $('.media_file').show();
        }

        $('.media_file_a').hide();
    });

    layui.use(['laydate', 'upload'], function () {
        let laydate = layui.laydate;
        let upload = layui.upload;
        let form = layui.form;

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "id": "{$short.id}",
            "avatar": "{$short.avatar}",
            "channel": "{$short.channel_link}",
            "title": "{$short.title}",
            "start_time": "{$short.start_time}",
            "end_time": "{$short.end_time}",
            "cover_url": "{$short.cover_url}",
            "media_url": "{$short.media_url}",
            "advertiser_name": "{$short.advertiser_name}",
            "interface": "{$short.preparation_interface}",
            "advert_cat_id":"{$short.advert_cat_id}",
            "advert_word":"{$short.advert_word}",
        });

        // 日历插件
        laydate.render({
            elem: '#start', //指定元素
            type: 'datetime'
        });
        laydate.render({
            elem: '#end', //指定元素
            type: 'datetime'
        });

        // 图片上传
        upload.render({
            elem: '#cover', //绑定元素
            url: '__CONTROLLER__/upload', //上传接口
            accept: 'images',
            data: {type: 'img'},
            done: function (ret) {   //上传完毕回调
                if (ret.code == 'succ') {
                    $('input[name="avatar"]').val(ret.data.url);
                    $('.cover_file').show();
                    cover_avatar = ret.data.show_url;
                    layer.msg('上传成功');
                } else {
                    layer.msg(ret.msg);
                }
            }
        });

        upload.render({
            elem: '#cover2', //绑定元素
            url: '__CONTROLLER__/upload', //上传接口
            accept: 'images',
            data: {type: 'img'},
            done: function (ret) {   //上传完毕回调
                if (ret.code == 'succ') {
                    $('input[name="cover_url"]').val(ret.data.url);
                    $('.cover_file').show();
                    cover_show = ret.data.show_url;
                    layer.msg('上传成功');
                } else {
                    layer.msg(ret.msg);
                }
            }
        });

        // 视频上传
        let uploadVideo = upload.render({
            elem: '#media', //绑定元素
            url: '__CONTROLLER__/upload', //上传接口
            accept: 'video',
            data: {type: 'mp4'},
            done: function (ret) {   // 上传完毕回调
                if (ret.code == 'succ') {
                    $('input[name="media_url"]').val(ret.data.url);
                    $('.media_file').show();
                    media_show = ret.data.show_url;
                    layer.msg('上传成功');
                } else {
                    layer.msg(ret.msg);
                }
            }
        });

    });

    // 图片/视频预览
    function preview_mc(type) {
        let html = '<video controls="" autoplay="" name="media" style="width: 372px!important;margin: 0 auto;display: block" >' +
            '<source src="' + media_show + '" type="video/mp4">' +
            '</video>';
        if (type == 'img') {
            html = '<img style="width: 372px!important;margin: 0 auto;display: block" src="' + cover_avatar + '">';
        }
        if (type == 'img2') {
            html = '<img style="width: 372px!important;margin: 0 auto;display: block" src="' + cover_show + '">';
        }

        if (type == 'mp4' || type == 'img' || type == 'img2') {
            parent.layer.open({
                type: 1,
                title: false,                 //  不显示标题栏
                closeBtn: false,
                shadeClose: true,
                offset: 'auto',
                id: 'preview',
                area: ['50%', '660px'],
                shade: 0.8,
                btnAlign: 'c',
                moveType: 1, //拖拽模式，0或者1
                content: '<div style="background-color: rgb(0, 0, 0);">' + html + '</div>',
            });
        }
    }

    layui.use(['form', 'upload'], function() {
        var $ = layui.$;
        var form = layui.form;
        let tvalue = '';
        form.on('select(aihaos)', function(data){
            switch (parseInt(data.value)) {
                case 2:
                    tvalue = 'tb';
                    $("#tb_option_1").attr("disabled",false);
                    $("#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                case 3:
                    tvalue = 'jd';
                    $("#tb_option_1").attr("disabled",true);
                    $("#jd_option_2,#jd_option_3").attr("disabled",false);
                    form.render('select');
                    $(".hdlx").css("display","block");
                    break;
                case 4:
                    tvalue = 'pdd';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                case 25:
                    tvalue = 'self';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                default:
                    tvalue = '';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","none");
                    break;
            }

            if (tvalue) {
                let url = '/dmooo.php/System/getSourceList?type=' + tvalue;
                $.get(url,function(data) {
                    if (data.length !== 0) {
                        let arrs = eval(data);
                        $(".two_cate").empty();
                        $.each(arrs,function(index,item){
                            $(".two_cate").append(new Option(item.name,item.cat_id));
                        });
                        form.render("select");
                    }
                    tvalue = '';
                });
            } else $(".two_cate").empty();form.render("select");

        });

        form.on('radio(diy_radio)', function(data){
            let source = $("#source option:selected").val();
            let source_type = '';
            switch (parseInt(source)) {
                case 2:
                    source_type = 'tb';
                    break;
                case 3:
                    source_type = 'jd';
                    break;
                case 4:
                    source_type = 'pdd';
                    break;
                case 25:
                    source_type = 'self';
                    break;
            }
            switch (parseInt(data.value)) {
                case 1:
                    $(".cat_1,.cat_2,.cat_4").css("display", "none");
                    $(".cat_3").css("display", "block");
                    $(".two_cate").empty();
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_word":""});
                    break;
                case 2:
                    $(".cat_1").css("display", "block");
                    $(".cat_2,.cat_3,.cat_4").css("display", "none");
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_cat_id":""});
                    if (source_type) {
                        let url = '/dmooo.php/System/getSourceList?type=' + source_type;
                        $.get(url,function(data){
                            if (data.length !== 0) {
                                let arrs = eval(data);
                                $(".two_cate").empty();
                                $.each(arrs,function(index,item){
                                    $(".two_cate").append(new Option(item.name,item.cat_id));
                                    $('.two_cate option[value="{$short['advert_catgray']}"]').attr("selected", "selected");
                                });
                                layui.form.render("select");
                            }
                        });
                    } else $(".two_cate").empty();layui.form.render("select");
                    break;
                case 3:
                    $(".cat_1,.cat_3,.cat_4").css("display", "none");
                    $(".cat_2").css("display", "block");
                    $(".two_cate").empty();
                    break;
                default:
                    $(".cat_1,.cat_2,.cat_3").css("display", "none");
                    $(".cat_4").css("display", "block");
                    $(".two_cate").empty();
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_cat_id":""});
                    break;
            }
        });
    });
</script>
</body>
</html>