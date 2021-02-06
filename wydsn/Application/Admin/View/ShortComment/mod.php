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
                                <label class="layui-form-label" style="width: 110px;">视频id</label>
                                <div class="layui-input-inline" style="width: 110px;margin:0">
                                    <input type="text" name="short_id" lay-verify="required|number" value="" placeholder="请输入视频id" autocomplete="off" class="layui-input">
                                </div>
                                <label class="short_text" style="white-space: nowrap; padding-left:10px; display:none; text-align:left;"></label>
                                <label class="short_text_red" style="white-space: nowrap; padding-left:10px; display:none; text-align:left;color:red;">该视频记录不存在！！！</label>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">评论内容</label>
                                <div class="layui-input-block">
                                    <textarea type="text" name="text" placeholder="请输入评论内容" autocomplete="off" class="layui-textarea" lay-verify="required"></textarea>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width: 110px;">点赞数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="praise_num" placeholder="请输入点赞数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                
                                <div class="layui-inline">
                                    <label class="layui-form-label">回复数</label>
                                    <div class="layui-input-inline" style="width: 80px;">
                                    <input type="text" name="reply_num" placeholder="请输入回复数" lay-verify="number" value="0" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">状态</label>
                                <div class="layui-input-block">
                                    <?php
                                        foreach ($comm['status'] as $k => $v) {
                                            $che = (isset($comm['is_status']) && $comm['is_status'] == $k) ? 'checked="true"' : '';
                                            echo '<input type="radio" name="is_status" value="'. $k .'" title="'. $v['name'] .'" '. $che .' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>'. $v['name'] .'</div>
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

    // 用户ID改变
    $('input[name="user_id"]').on("input", function(e) {
        let uid = e.delegateTarget.value;

        if (uid > 0) {
            // 请求提交
            $.ajax({
                url: '__MODULE__/Short/getUserInfo',
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

    // 视频ID改变
    $('input[name="short_id"]').on("input", function(e) {
        let sid = e.delegateTarget.value;

        if (sid > 0) {
            // 请求提交
            $.ajax({
                url: '__CONTROLLER__/isShort',
                type: 'post',
                data:{id:sid},
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.code == 'succ') {
                        if (res.data.short_name != '') {
                            $('.short_text_red').hide();
                            $('.short_text').text(res.data.short_name);
                            $('.short_text').show();
                        }
                    } else {
                        $('.short_text_red').show()
                    }
                }
            });
        } else {
            $('.short_text').hide();
            $('.short_text_red').hide();
        }
    });

    layui.use([], function() { 
        let form        = layui.form;  

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "id": "{$comm.id}",
            "user_id": "{$comm.user_id}",
            "short_id": "{$comm.short_id}",
            "text": "{$comm.text}",
            "praise_num": "{$comm.praise_num}",
            "reply_num": "{$comm.reply_num}"
        });


    });
</script>
</body>
</html>