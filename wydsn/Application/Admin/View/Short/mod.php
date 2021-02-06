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
                        <form action=""  class="form-horizontal layui-form" lay-filter="mod_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id">
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">用户uid</label>
                                <div class="layui-input-inline" style="width: 110px;margin:0">
                                    <input type="text" name="user_id" lay-verify="required|number" value="" placeholder="请输入用户uid" autocomplete="off" class="layui-input">
                                </div>
                                <img class="user_img" src="" height="35px" style="margin-left:30px; display:none;">
                                <label class="user_name" style="white-space: nowrap; padding-left:10px; display:none; text-align:left;"></label>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">封面图片</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="cover_url" value="" placeholder="请上传视频封面图片" autocomplete="off" class="layui-input" lay-verify="required">
                                </div>
                                <button type="button" class="layui-btn" id="cover"><i class="layui-icon"></i>上传图片</button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary cover_file" style="margin-left: 15px;display: none;" onclick="preview_mc('img')">预览图片</button>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">视频地址</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="media_url" lay-verify="required" value="" placeholder="请上传视频地址" autocomplete="off" class="layui-input">
                                </div>
                                <button type="button" class="layui-btn" id="media"><i class="layui-icon"></i>上传视频</button>
                                <input class="layui-upload-file" type="file">
                                <?php 
                                    echo "<button type=\"button\" class=\"layui-btn layui-btn-primary media_file\" style=\"margin-left: 15px;display: none;\" onclick=\"preview_mc('mp4')\">预览视频</button>";
                                    $href = U('Short/shortLook', ['tag' => $short['id']]);
                                    echo "<a class=\"layui-btn layui-btn-primary media_file_a\" style=\"margin-left: 15px;display: none;\" href=\"". $href ."\" target=\"_blank\" >预览视频</a>";
                                ?>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">描述内容</label>
                                <div class="layui-input-block">
                                    <textarea type="text" name="short_name" placeholder="请输入描述内容" autocomplete="off" class="layui-textarea" lay-verify="required"></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 110px;">获赞数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="praise_num" placeholder="请输入获赞数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                
                                <div class="layui-inline">
                                    <label class="layui-form-label">评论数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="comment_num" placeholder="请输入评论数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">转发数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="forward_num" placeholder="请输入转发数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">发布时间</label>
                                <div class="layui-input-inline" style="width: 30%">
                                    <input type="text" name="create_time" value="2020-08-07 14:18:43" placeholder="时间格式：2010-10-10 10:10:10" autocomplete="off" class="layui-input" id="time" lay-key="1">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">推荐</label>
                                <div class="layui-input-block">
                                    <?php
                                        foreach ($short['recommend'] as $k => $v) {
                                            $che = (isset($short['is_recommend']) && $short['is_recommend'] == $k) ? 'checked="true"' : '';
                                            echo '<input type="radio" name="is_recommend" value="'. $k .'" title="'. $v .'" '. $che .' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>'. $v .'</div>
                                                  </div>';
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">状态</label>
                                <div class="layui-input-block">
                                    <?php
                                        foreach ($short['status'] as $k => $v) {
                                            $che = (isset($short['is_status']) && $short['is_status'] == $k) ? 'checked="true"' : '';
                                            echo '<input type="radio" name="is_status" value="'. $k .'" title="'. $v['name'] .'" '. $che .' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>'. $v['name'] .'</div>
                                                  </div>';
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="layui-form-item goods_div">
                                <label class="layui-form-label" style="width: 110px;">绑定商品</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <div class="layui-tab layui-tab-card">
                                        <ul class="layui-tab-title">
                                            <li class="layui-this">淘宝</li>
                                            <li>京东</li>
                                            <li>拼多多</li>
                                            <li>唯品会</li>
                                            <li>自营</li>
                                        </ul>
                                        <div class="layui-tab-content" style="height: 100px;">
                                            <div class="layui-tab-item layui-show">
                                                <label class="layui-form-label">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[tb]" value="{$short.goods.tb}" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[jd]" value="{$short.goods.jd}" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[pdd]" value="{$short.goods.pdd}" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[vip]" value="{$short.goods.vip}" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[self]" value="{$short.goods.self}" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div style="float:left; color:red;">注意：只能绑定一个商品，按顺序拿第一个商品ID</div>
                                        </div>
                                    </div>
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
<script>
    let short_id    = "{$short.id}"; 
    let cover_show  = "{$short.cover_show}";   // 图片预览地址
    let media_show  = "{$short.media_show}";   // 短视频预览地址
    let media_tag   = "{$short.media_tag}";   
    

    // 显示视频预览
    if (media_show != '') {
        if (media_tag == 1) {
            $('.media_file').show();
        } else {
            $('.media_file_a').show();
        } 
    }

    // 显示图片预览
    if (cover_show != '' && short_id != 0) {
        $('.cover_file').show();
    }

    // 图片框改变
    $('input[name="cover_url"]').on("input", function(e) {
        cover_show = e.delegateTarget.value;
    });

    // 视频框改变
    $('input[name="media_url"]').on("input", function(e) {
        let str = e.delegateTarget.value;

        if (!!str.match(/\.mp4$/)) {
            media_show = str;
            $('.media_file').show();
        } else {
            $('.media_file').show();   
        }

        $('.media_file_a').hide();
    });

    // 用户ID改变
    $('input[name="user_id"]').on("input", function(e) {
        let uid = e.delegateTarget.value;

        if (uid > 0) {
            // 请求提交
            $.ajax({
                url: '__CONTROLLER__/getUserInfo',
                type: 'post',
                data:{user_id:uid},
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.code == 'succ') {
                        if (res.data.avatar != '') {
                            $('.user_img').attr('src', res.data.avatar);
                            $('.user_img').show();
                        }
                        if (res.data.nickname != '') {
                            $('.user_name').text(res.data.nickname);
                            $('.user_name').show();
                        }
                    }
                }
            });
        } else {
            $('.user_img').hide();
            $('.user_name').hide();
        }
    });

    layui.use(['laydate', 'upload'], function() {
        let laydate     = layui.laydate;
        let upload      = layui.upload;   
        let form        = layui.form;  

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "id": "{$short.id}",
            "user_id": "{$short.user_id}",
            "cover_url": "{$short.cover_url}",
            "media_url": "{$short.media_url}",
            "short_name": "{$short.short_name}",
            "praise_num": "{$short.praise_num}",
            "comment_num": "{$short.comment_num}",
            "forward_num": "{$short.forward_num}",
            "create_time": "{$short.create_time}"
        });

        // 日历插件
        laydate.render({
            elem: '#time', //指定元素
            type: 'datetime'
        });

        // 图片上传
        let uploadImg   = upload.render({
            elem: '#cover', //绑定元素
            url: '__CONTROLLER__/upload', //上传接口
            accept: 'images',
            data: {type:'img'},
            done: function(ret) {   //上传完毕回调
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
        let uploadVideo  = upload.render({
            elem: '#media', //绑定元素
            url: '__CONTROLLER__/upload', //上传接口
            accept: 'video',
            data: {type:'mp4'},
            done: function(ret) {   // 上传完毕回调
                if (ret.code == 'succ') {
                    $('input[name="media_url"]').val(ret.data.url);
                    $('.media_file').show();
                    $('.media_file_a').hide();
                    media_show  = ret.data.show_url;
                    media_tag   = 1;
                    layer.msg('上传成功');
                } else {
                    layer.msg(ret.msg);
                }
            }
        });

    });

    // 图片/视频预览
    function preview_mc(type) { 
        let html = '<video controls="" autoplay="" name="media" style="width: 372px!important;margin: 0 auto;display: block" >'+
                        '<source src="'+ media_show +'" type="video/mp4">'+
                   '</video>';

        if (type == 'img') {
            html = '<img style="width: 372px!important;margin: 0 auto;display: block" src="'+ cover_show +'">';
        }

        if (media_tag == 1 || type == 'img') {
            parent.layer.open({
                type: 1,
                title: false,                 //  不显示标题栏
                closeBtn: false,
                shadeClose: true,
                offset: 'auto',
                id:'preview',                  
                area: ['50%', '660px'],
                shade: 0.8,
                btnAlign: 'c',
                moveType: 1, //拖拽模式，0或者1
                content: '<div style="background-color: rgb(0, 0, 0);">'+ html +'</div>',
            });
        }
    }
</script>
</body>
</html>