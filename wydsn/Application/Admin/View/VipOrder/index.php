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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 唯品会管理系统 &raquo; 唯品会订单管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="layui-form">
                        <!-- 订单号：<input type="text" placeholder="" name="orderSn" class="form-control">
                        商品名称：<input type="text" placeholder="" name="goodsName" class="form-control">
                        所属用户：<input type="text" placeholder="" name="username" class="form-control">
                        订单状态：<select class="form-control" name="orderSubStatusName">
                            <option value="">请选择订单状态</option>
                            <option value="已下单">已下单</option>
                            <option value="已付款">已付款</option>
                            <option value="已签收">已签收</option>
                            <option value="待结算">待结算</option>
                            <option value="已结算">已结算</option>
                            <option value="已失效">已失效</option>
                        </select>
                        <button class="layui-btn" type="submit">查询</button> -->
                        <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:50px;">订单号</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="orderSn" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:60px;">商品名称</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="goodsName" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:60px;">所属用户</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="username" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:60px;">订单状态</label>
                                    <div class="layui-input-inline">
                                        <select class="form-control" name="orderSubStatusName">
                                            <option value="">请选择订单状态</option>
                                            <option value="已下单">已下单</option>
                                            <option value="已付款">已付款</option>
                                            <option value="已签收">已签收</option>
                                            <option value="待结算">待结算</option>
                                            <option value="已结算">已结算</option>
                                            <option value="已失效">已失效</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <button class="layui-btn" type="submit">查询</button>
                                </div>
                            </div>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th width="20%">订单名称</th>
                                <th>商品缩略图</th>
                                <th>所属用户</th>
                                <th>总价</th>
                                <th>平台佣金</th>
                                <th>唯品会佣金</th>
                                <th>佣金比例</th>
                                <th>下单时间</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $User=new \Common\Model\UserModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['ordersn']}</td>
                                    <td>{$l['goodsname']}</td>
                                    <td><img src="{$l['goodsthumb']}" height="50px"></td>
                                    <td>
                                        <?php
                                        if($l['user_id']) {
                                            $userMsg=$User->getUserMsg($l['user_id']);
                                            echo $userMsg['phone'];
                                        }
                                        ?>
                                    </td>
                                    <td>￥{$l['commissiontotalcost']}</td>
                                    <td>￥{$l['commission']}</td>
                                    <td>￥{$l['vipcommission']}</td>
                                    <td>{$l['commissionrate']}%</td>
                                    <td><?php echo date('Y-m-d H:i:s',$l['ordertime']/1000)?></td>
                                    <td>{$l['ordersubstatusname']}</td>
                                    <td>
                                        <a href="__CONTROLLER__/msg/id/{$l.id}" title="查看详情">
                                            <i class="layui-icon layui-icon-read" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>