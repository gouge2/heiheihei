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

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 会员登录设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_invite_code" value="{$msg['invite_code']}">
                            <input type="hidden" name="old_seconds_verify" value="{$msg['seconds_verify']}">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">注册是否强制邀请码</label>
                                <div>
                                    <div class="layui-input-block" style="width: 80%;padding-top: 5px">
                                        <input type="radio" name="invite_code" value="1" <?php if($msg['invite_code']=='1') echo 'checked'; ?> title='是'>
                                        <input type="radio" name="invite_code" value="2" <?php if($msg['invite_code']=='2') echo 'checked'; ?> title='否'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">登录是否开启mob秒验</label>
                                <div class="layui-input-block" style="width: 80%;padding-top: 5px">
                                    <input type="radio" name="seconds_verify" value="1" <?php if($msg['seconds_verify']=='1') echo 'checked'; ?> title='是'>
                                    <input type="radio" name="seconds_verify" value="2" <?php if($msg['seconds_verify']=='2') echo 'checked'; ?> title='否'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 10%;">登录方式</label>
                                <div class="layui-input-block">
                                    <?php if(!empty($msg['login_methods'])) $msg['login_method'] = explode(",",$msg['login_methods']); ?>
                                    <input type="checkbox" name="login_method[wx_login]" title="微信登录" <?php if(in_array('wx_login',$msg['login_method'])) echo 'checked'; ?>>
                                    <input type="checkbox" name="login_method[appl_login]" title="苹果登录" <?php if(in_array('appl_login',$msg['login_method'])) echo 'checked'; ?>>
                                    <input type="checkbox" name="login_method[one_login]" title="一键登录" <?php if(in_array('one_login',$msg['login_method'])) echo 'checked'; ?>>
                                    <if condition='$msg[twitter_type] eq 1'>
                                        <input type="checkbox" name="login_method[twi_login]" title="Twitter登录" <?php if(in_array('twi_login',$msg['login_method'])) echo 'checked'; ?>>
                                    </if>
                                    <if condition='$msg[facebook_type] eq 1'>
                                        <input type="checkbox" name="login_method[face_login]" title="Facebook登录" <?php if(in_array('face_login',$msg['login_method'])) echo 'checked'; ?>>
                                    </if>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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