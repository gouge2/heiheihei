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
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
    <script type="text/javascript" src="__JS__/area.js"></script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：管理员管理 &raquo; 管理员列表 &raquo; 编辑管理员<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/uid/{$msg['uid']}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">登录用户名</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="adminname" value="{$msg['adminname']}"
                                           placeholder="">
                                    <span class="layui-form-mid layui-word-aux">{$error}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">新密码</label>
                                <div class="layui-input-block">
                                    <input type="password" class="layui-input" name="password">
                                    <span class="layui-form-mid layui-word-aux">不填写则保持原有密码，长度不少于6位</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">EMAIL</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="email" value="{$msg['email']}"
                                           placeholder="">
                                    <span class="layui-form-mid layui-word-aux">{$error1}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">手机号码</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="phone" value="{$msg['phone']}"
                                           placeholder="">
                                    <span class="layui-form-mid layui-word-aux">{$error2}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">所属分组</label>
                                <div class="layui-input-block">
                                    <select class="form-control m-b" name="group_id" id="group_id">
                                        <option value="">--请选择所属分组--</option>
                                        <?php
                                        foreach ($glist as $g) {
                                            if ($g['id'] == $msg['group_id']) {
                                                $select = 'selected';
                                            } else {
                                                $select = '';
                                            }
                                            echo '<option value="' . $g['id'] . '" ' . $select . '>--' . $g['title'] . '--</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item"  id="area-picker">
                                <label class="layui-form-label" style="width: 100px;">地区</label>
                                <div class="layui-input-block">
                                    <div class="layui-input-inline">
                                        <select name="province" id="province" class="province-selector" lay-filter="province-1" data-value="{$msg['province']}"  style="width:105px"></select>
                                    </div>
                                    <div class="layui-input-inline">
                                        <select name="city" id="city"  class="city-selector" data-value="{$msg['city']}" lay-filter="city-1" style="width:100px"></select>
                                    </div>
                                    <!-- <script type="text/javascript">var opt0 = ["", ""];</script>
                                    <script type="text/javascript">setup()</script> -->
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">注册时间</label>
                                <div class="layui-input-block">
                                    <input type="text" class="form-control" value="{$msg['register_time']}" disabled
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">注册IP</label>
                                <div class="layui-input-block">
                                    <input type="text" class="form-control" value="{$msg['register_ip']}" disabled
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">最后登录时间</label>
                                <div class="layui-input-block">
                                    <input type="text" class="form-control" value="{$msg['last_login_time']}" disabled
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">最后登录IP</label>
                                <div class="layui-input-block">
                                    <input type="text" class="form-control" value="{$msg['last_login_ip']}" disabled
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">登录次数</label>
                                <div class="layui-input-block">
                                    <input type="text" class="form-control" name="login_num" value="{$msg['login_num']}"
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否禁用</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status"
                                               value="1" <?php if ($msg['status'] == '1') {
                                            echo 'checked';
                                        } ?> title="正常">
                                        <input type="radio" name="status"
                                               value="0" <?php if ($msg['status'] == '0') {
                                            echo 'checked';
                                        } ?> title="禁用">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;确认修改
                                    </button>
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
<script>
    //配置插件目录
    layui.config({
        base: '__LAYUIADMIN__/mods/'
        , version: '1.0'
    });
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'layarea'], function () {
        var layer = layui.layer
            , form = layui.form
            , layarea = layui.layarea;

        layarea.render({
            elem: '#area-picker',
            change: function (res) {
                //选择结果
                console.log(res);
            }
        });
    });
</script>
</body>
</html>