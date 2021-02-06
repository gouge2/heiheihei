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
                    <h3>当前位置： 会员管理 &raquo; 查看会员余额变动记录</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <!-- 用户手机号码：<input type="text" placeholder="" name="phone" class="form-control"> -->
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:90px'>用户手机号码</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="phone" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                        </div>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>会员账号</th>
                                <th>订单号</th>
                                <th>金额</th>
                                <th>当前余额</th>
                                <th>订单状态</th>
                                <th>支付时间</th>
                                <th>订单类型</th>
                                <th>订单号</th>
                                <th>操作类型</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $User=new \Common\Model\UserModel();
                            $group=new \Common\Model\UserGroupModel();
                            $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <?php
                                    //会员账号
                                    $UserMsg=$User->getUserDetail($l['user_id']);
                                    $user_account=$UserMsg['account'];
                                    if(is_phone($user_account)){
                                        $user_account=hide_phone($user_account, 4, 4);
                                    }
                                    //操作类型
                                    $res_action=$UserBalanceRecord->getActionZh($l['action']);
                                    ?>
                                    <td>{$user_account}</td>
                                    <td>{$l['order_num']}</td>
                                    <td>{$res_action.action_symbol}<?php echo $l['money']/100;?> 元</td>
                                    <td><?php echo $l['all_money']/100;?> 元</td>
                                    <td>
                                        <?php
                                        if($l['status']=='1')
                                        {
                                            echo '未付款';
                                        }else {
                                            echo '已付款';
                                        }
                                        ?>
                                    </td>
                                    <td>{$l['pay_time']}</td>
                                    <td>
                                        <?php
                                        switch ($l['type'])
                                        {
                                            case '1':
                                                $type_str='淘宝';
                                                $order_url='/taokeyun.php/TbOrder/index?trade_id='.$l['order_id'];
                                                break;
                                            case '2':
                                                $type_str='京东';
                                                $order_url='/taokeyun.php/JingdongOrder/index?id='.$l['order_id'];
                                                break;
                                            case '3':
                                                $type_str='拼多多';
                                                $order_url='/taokeyun.php/PddOrder/index?order_sn='.$l['order_id'];
                                                break;
                                            case '4':
                                                $type_str='唯品会';
                                                $order_url='/taokeyun.php/VipOrder/index?orderSn='.$l['order_id'];
                                                break;
                                            default:
                                                $type_str='';
                                        }
                                        echo $type_str;
                                        ?>
                                    </td>
                                    <td><a href="{$order_url}" target="_blank" style="color: blue">{$l['order_id']}</a></td>
                                    <td>{$res_action.action_zh}</td>
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