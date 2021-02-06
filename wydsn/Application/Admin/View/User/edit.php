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
    <script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
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
                    <h3>当前位置：会员管理 &raquo; 会员列表 &raquo; 编辑会员<a class="layui-btn pull-right" href="__CONTROLLER__/index/group_id/{$msg.group_id}" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/uid/{$msg['uid']}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="oldemail" value="{$msg['email']}">
                            <input type="hidden" name="oldphone" value="{$msg['phone']}">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">登录用户名</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="username" value="{$msg['username']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">新密码</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="password" value="" placeholder="" style="width: 90%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">不填写则保持原有密码，长度不少于6位</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">EMAIL</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="email" value="{$msg['email']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">手机号码</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="phone" value="{$msg['phone']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">备注姓名</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="remark" value="{$msg['remark']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">授权码</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="auth_code" value="{$msg['auth_code']}" placeholder="" style="width: 90%;" readonly="true">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">积分</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="point" value="{$msg['point']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">余额</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="balance" value="{$msg['balance']}" placeholder="" style="width: 90%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">涉及到客户账户余额，请谨慎修改</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">推荐人手机号码</label>
                                <?php
                                if($msg['referrer_id']) {
                                    $User=new \Common\Model\UserModel();
                                    $referrerMsg=$User->getUserMsg($msg['referrer_id']);
                                }
                                ?>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_phone" value="{$referrerMsg['phone']}" placeholder="" style="width: 90%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">已有上级推荐人的用户不能更改推荐人</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">淘宝账号</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="tb_uid" value="{$msg['tb_uid']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">淘宝推广位</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="tb_pid_master" value="{$msg['tb_pid_master']}" placeholder="" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">经验值</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="exp" value="{$msg['exp']}" placeholder="" style="width: 90%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">涉及到客户账户经验值，请谨慎修改</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">所属分组</label>
                                <div class="layui-input-inline">
                                    <select class="layui-input m-b" name="group_id" style="width: 90%;">
                                        <option value="">--请选择所属分组--</option>
                                        <?php
                                        foreach($glist as $g) {
                                            if($g['id']==$msg['group_id']) {
                                                $select='selected';
                                            }else {
                                                $select='';
                                            }
                                            echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="layui-form-mid layui-word-aux" id="gAjax"></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否冻结</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_freeze" value="N" <?php if($msg['is_freeze']=='N'){echo 'checked';} ?> title='正常使用'>
                                    <input type="radio" name="is_freeze" value="Y" <?php if($msg['is_freeze']=='Y'){echo 'checked';} ?> title='冻结'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否终生会员</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_forever" value="Y" <?php if($msg['is_forever']=='Y'){echo 'checked';} ?> title='是'>
                                    <input type="radio" name="is_forever" value="N" <?php if($msg['is_forever']=='N'){echo 'checked';} ?> title='否'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否代理商</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_agent" value="N" <?php if($msg['is_agent']=='N'){echo 'checked';} ?> title='否'>
                                    <input type="radio" name="is_agent" value="Y" <?php if($msg['is_agent']=='Y'){echo 'checked';} ?> title='是'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否为分享VIP</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_share_vip" value="Y" <?php if($msg['is_share_vip']=='Y'){echo 'checked';} ?> title='是'>
                                    <input type="radio" name="is_share_vip" value="N" <?php if($msg['is_share_vip']=='N'){echo 'checked';} ?> title='否'>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">会员到期时间</label>
                                <div class="layui-input-block">
                                    <input class="layui-input layer-date" name="expiration_date" value="{$msg['expiration_date']}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">注册时间</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['register_time']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">注册IP</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['register_ip']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">最后登录时间</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['last_login_time']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">最后登录IP</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['last_login_ip']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">登录次数</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['login_num']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">第三方应用类型</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['third_type']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">第三方应用ID</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="" value="{$msg['openid']}" placeholder="" disabled style="width: 90%;">
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit" id="sub"><i class="fa fa-check"></i>&nbsp;编辑会员</button>
                                    <button class="layui-btn" type="reset" id="sub"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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