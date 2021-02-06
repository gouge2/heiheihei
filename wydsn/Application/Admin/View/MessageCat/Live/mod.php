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
                            <input type="hidden" name="room_id">
                            <input type="hidden" name="user_id">
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">房间名称</label>
                                <div class="layui-input-inline" style="width: 300px;margin:0">
                                    <input type="text" name="room_name" lay-verify="required" value="" placeholder="请输入房间名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">房间分类</label>
                                <div class="layui-input-inline">
                                <select class="form-control" name="cat_id" lay-verify="required">
                                    <option value="" >请选择</option>
                                    <?php
                                        foreach ($cat_list as $k => $v) {
                                            $che = ( $k == $room['cat_id']) ? 'selected="selected"' : '';
                                            echo '<option value="'. $k  .'" '. $che .' >'. $v .'</option>';
                                        }
                                    ?>
                                </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">主播uid/来鹿号</label>
                                <div class="layui-input-inline" style="width: 110px;margin:0">
                                    <input type="text" name="user_str" lay-verify="required" value="" placeholder="请输入主播uid" autocomplete="off" class="layui-input">
                                </div>
                                <img class="user_img" src="" height="35px" style="margin-left:30px; display:none;">
                                <label class="user_name" style="white-space: nowrap; padding-left:10px; display:none; text-align:left;"></label>
                                <label class="room_msg" style="padding-left:10px; color:red; display:none;"></label>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">封面图片</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <input type="text" name="cover_url" value="" placeholder="请上传视频封面图片" autocomplete="off" class="layui-input" lay-verify="required">
                                </div>
                                <button type="button" class="layui-btn" id="cover"><i class="layui-icon"></i>上传图片</button>
                                <input class="layui-upload-file" type="file">
                                <button type="button" class="layui-btn layui-btn-primary cover_file" style="margin-left: 15px;display: none;" onclick="preview_mc()">预览图片</button>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">视频流类型</label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <div class="layui-tab layui-tab-card">
                                        <ul class="layui-tab-title">
                                            <li class="layui-this">腾讯云</li>
                                            <li>上传视频</li>
                                        </ul>
                                        <div class="layui-tab-content" style="height: 100px;">
                                            <div class="layui-tab-item layui-show">默认使用腾讯云配置</div>
                                            <div class="layui-tab-item"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 130px;">虚拟人数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="lan_people" placeholder="请输入虚拟人数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 90px;">虚拟热度</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="lan_heat" placeholder="请输入虚拟热度" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 130px;">推荐</label>
                                <div class="layui-input-block">
                                    <?php
                                        foreach ($room['recommend'] as $k => $v) {
                                            $che = (isset($room['is_recommend']) && $room['is_recommend'] == $k) ? 'checked="true"' : '';
                                            echo '<input type="radio" name="is_recommend" value="'. $k .'" title="'. $v .'" '. $che .' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>'. $v .'</div>
                                                  </div>';
                                        }
                                    ?>
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
    let cover_show  = "{$room.cover_show}";   // 图片预览地址

    layui.use([], function() { 
        let form        = layui.form,
            upload      = layui.upload;  

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "room_id": "{$room.room_id}",
            "user_id": "{$room.user_id}",
            "user_str": "{$room.user_str}",
            "room_name": "{$room.room_name}",
            "cat_id": "{$room.cat_id}",
            "cover_url": "{$room.cover_url}",
            "lan_people": "{$room.lan_people}",
            "lan_heat": "{$room.lan_heat}"
        });


        // 图片上传
        let uploadImg   = upload.render({
            elem: '#cover', //绑定元素
            url: '__MODULE__/Short/upload', //上传接口
            accept: 'images',
            data: {type:'img', from:'live'},
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
    });

    // 显示图片预览
    if (cover_show != '') {
        $('.cover_file').show();
    }

    // 图片框改变
    $('input[name="cover_url"]').on("input", function(e) {
        cover_show = e.delegateTarget.value;
    });

    // 用户ID改变
    $('input[name="user_str"]').on("input", function(e) {
        let uid = e.delegateTarget.value;

        if (uid) {
            // 请求提交
            $.ajax({
                url: '__MODULE__/Short/getUserInfo',
                type: 'post',
                data:{user_id:uid,from:'live'},
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
                        if (res.data.room_msg != '') {
                            $('.room_msg').text(res.data.room_msg);
                            $('.room_msg').show();
                        }
                        if (res.data.user_id != '') {
                            $('input[name="user_id"]').val(res.data.user_id);
                        }
                    }
                }
            });
        } else {
            $('.user_img').hide();
            $('.user_name').hide();
            $('.room_msg').hide();
        }
    });

    // 图片/视频预览
    function preview_mc() { 
        let html = '<img style="width: 372px!important;margin: 0 auto;display: block" src="'+ cover_show +'">';
        
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
</script>
</body>
</html>