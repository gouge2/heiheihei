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
  
                            <div class="layui-form-item" style="display: flex;">
                                <label class="layui-form-label" style="width: 110px;">用户uid</label>
                                <div class="layui-input-inline" style="width: 110px;margin:0">
                                <input type="text" name="user_id" lay-verify="required|number" value="" placeholder="请输入用户uid" autocomplete="off" class="layui-input" style="width: 110%;">
                                </div>
                                <img class="user_img" src="" height="35px" style="margin-left:30px; display:none;">
                                <label class="user_name" style="white-space: nowrap; padding-left:10px; display:none; text-align:left;"></label>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">爬取条数</label>
                                <div class="layui-input-inline" style="width: 120px;">
                                    <input type="text" name="number" lay-verify="required|number" value="" placeholder="请输入爬取条数" autocomplete="off" class="layui-input">
                                </div> 
                                
                            </div>
                            <p style="padding-left:110px; color:red;">注意： 建议一次拉取100条视频</p>

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

</script>
</body>
</html>