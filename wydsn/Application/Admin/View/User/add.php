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
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：会员管理 &raquo; 会员列表 &raquo; 添加会员<a class="layui-btn pull-right" href="__CONTROLLER__/index/group_id/{$group_id}" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action=""  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">登录用户名</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="username" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">密码</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="password" require minlength='6' placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">重复密码</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="password2" require minlength='6' placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">用户昵称</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="nickname" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">EMAIL</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input class="layui-input" name="email" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">手机号码</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input class="layui-input" name="phone" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">备注姓名</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input class="layui-input" name="remark" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">所属分组</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <select class="layui-input m-b" name="group_id">
                                        <option value="">--请选择所属分组--</option>
                                        <?php
                                        foreach($glist as $g) {
                                            if($g['id']==$group_id) {
                                                $select='selected';
                                            }else {
                                                $select='';
                                            }
                                            echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="layui-form-mid layui-word-aux"></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否冻结</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="radio" name="is_freeze" value="N" checked title='正常使用'>
                                    <input type="radio" name="is_freeze" value="Y" title='冻结'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">推荐人手机</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input class="layui-input" name="referrer_phone" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;注册</button>
                                </div>
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