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
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：唯品会管理系统 &raquo; 唯品会订单管理 &raquo; 查看订单详情<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回订单列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" style="overflow: hidden;">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">所属用户</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.user_account}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单编号</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.ordersn}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">商品id</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.goodsid}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.goodsname}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">商品缩略图</label>
                            <div class="layui-input-block">
                                <img src="{$msg['goodsthumb']}" height="50px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">商品数量</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.goodscount}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">商品价格</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="￥{$msg.commissiontotalcost}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">佣金比例</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.commissionrate}%" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">平台佣金</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="￥{$msg.commission}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">唯品会佣金</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="￥{$msg.vipcommission}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">佣金编码</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.commcode}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">佣金方案名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.commname}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单状态</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['vipstatus']) {
                                    case 0:
                                        $vipStatus='不合格';
                                        break;
                                    case 1:
                                        $vipStatus='待定';
                                        break;
                                    case 2:
                                        $vipStatus='已完结';
                                        break;
                                    default:
                                        $vipStatus='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$vipStatus}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单结算状态</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['settled']) {
                                    case 0:
                                        $settled='未结算';
                                        break;
                                    case 1:
                                        $settled='已结算';
                                        break;
                                    default:
                                        $settled='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$settled}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单子状态：流转状态</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.ordersubstatusname}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">新老客</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['newcustomer']) {
                                    case 0:
                                        $newCustomer='待定';
                                        break;
                                    case 1:
                                        $newCustomer='新客';
                                        break;
                                    case 2:
                                        $newCustomer='老客';
                                        break;
                                    default:
                                        $newCustomer='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$newCustomer}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">是否自推自买</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['selfbuy']) {
                                    case 0:
                                        $selfBuy='否';
                                        break;
                                    case 1:
                                        $selfBuy='是';
                                        break;
                                    default:
                                        $selfBuy='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$selfBuy}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">渠道标识</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.channeltag}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单来源</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.ordersource}"style="width: 92%;" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">是否预付订单</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['isprepay']) {
                                    case '0':
                                        $isPrepay='否';
                                        break;
                                    case '1':
                                        $isPrepay='是';
                                        break;
                                    default:
                                        $isPrepay='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$isPrepay}" style="width: 92%;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">推广PID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.pid}"style="width: 92%;" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">下单时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="<?php echo date('Y-m-d H:i:s',$msg['ordertime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">签收时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="<?php echo date('Y-m-d H:i:s',$msg['signtime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">结算时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="<?php echo date('Y-m-d H:i:s',$msg['settledtime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">订单上次更新时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="<?php echo date('Y-m-d H:i:s',$msg['lastupdatetime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">入账时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="<?php echo date('Y-m-d H:i:s',$msg['commissionentertime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">售后订单佣金变动</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="{$msg.aftersalechangecommission}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">售后订单总商品数量变动</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled style="width: 92%;" value="{$msg.aftersalechangegoodscount}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="layui-form-label" style="width: 190px;">是否结算给用户</label>
                            <div class="layui-input-block">
                                <?php
                                if($msg['status']=='2') {
                                    $status_str='已结算';
                                }else {
                                    $status_str='未结算';
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$status_str}" style="width: 92%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>