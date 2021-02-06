<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet"> -->
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 活跃会员列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <!-- 直接推荐人数：<input type="text" placeholder="" name="" class="form-control"> -->
<!--                        	<button class="btn btn-primary" type="submit">查询</button>-->
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:100px'>直接推荐人数</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="referrer_num" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                        </div>
                        
                    </form>
                    <div class="layui-row layui-col-space17">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>所属分组</th>
                                <th>手机号码</th>
                                <th>姓名</th>
                                <th>备注</th>
                                <th>查看余额</th>
                                <th>可提现</th>
                                <th>本月预估</th>
                                <th>本月结算</th>
                                <th>推荐人数</th>
                                <th>推广位</th>
                                <th>上级/推荐</th>
                                <th>注册时间</th>
                                <th>手机归属地</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $group=new \Common\Model\UserGroupModel();
                            $User=new \Common\Model\UserModel();
                            $UserDetail=new \Common\Model\UserDetailModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['uid']}</td>
                                    <td>
                                        <?php
                                        //会员组名称
                                        $groupMsg=$group->getGroupMsg($l['group_id']);
                                        echo $groupMsg['title'];
                                        ?>
                                    </td>
                                    <td>{$l['phone']}</td>
                                    <td>
                                        <?php
                                        $detailMsg=$UserDetail->getUserDetailMsg($l['uid']);
                                        echo $detailMsg['truename'];
                                        ?>
                                    </td>
                                    <td>{$l['remark']}</td>
                                    <td>￥{$l['balance']}<a href="__MODULE__/UserBalanceRecord/index/user_id/{$l['uid']}" style="color: red">查看</a></td>
                                    <?php
                                    $res_balance=$User->getDrawBalance($l['uid']);
                                    ?>
                                    <td>￥{$res_balance.draw_balance}</td>
                                    <td>￥{$res_balance.amount_current}</td>
                                    <td>￥{$res_balance.amount_finish}</td>
                                    <td>
                                        <?php
                                        $uid=$l['uid'];
                                        $referrer_num=$User->where("referrer_id='$uid'")->count();
                                        echo '<a href="__ACTION__/referrer_phone/'.$l['phone'].'">'.$referrer_num.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($l['tb_pid_master'])
                                        {
                                            echo '1';
                                        }else {
                                            echo '0';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($l['referrer_id']) {
                                            $referrerMsg=$User->getUserDetail($l['referrer_id']);
                                            if($referrerMsg['remark']) {
                                                echo $referrerMsg['remark'];
                                            }else {
                                                echo $referrerMsg['account'];
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>{$l['register_time']}</td>
                                    <td>{$l['phone_province']}-{$l['phone_city']}</td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>