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

    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 订单管理 &raquo; 已付款订单</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <!-- 订单号：<input type="text" placeholder="" name="order_num" class="form-control">
                        商品名称：<input type="text" placeholder="" name="title" class="form-control">
                        收件人：<input type="text" placeholder="" name="consignee" class="form-control">
                        联系电话：<input type="text" placeholder="" name="contact_number" class="form-control">
                        <button class="layui-btn" type="submit">查询</button> -->
                        <div class="layui-inline">
                            <label class="layui-form-label">订单类型</label>
                            <div class="layui-input-inline">
                                <select  class="form-control" name="type">
                                    <option value="" >请选择</option>
                                    <?php
                                    foreach ($search['status'] as $k => $v) {
                                        $che = $v['sel'] ? 'selected="selected"' : '';
                                        echo '<option value="'. $k .'" '. $che .' >'. $v['name'] .'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">订单号</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="order_num" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">商品名称</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="title" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">收件人</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="consignee" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">联系电话</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="contact_number" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" type="submit">查询</button>
                        </div>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>订单号</th>
                                <th width="10%">订单名称</th>
                                <th>总价</th>
                                <th>收件人</th>
                                <th>联系电话</th>
                                <th width="10%">收货地址</th>
                                <th>邮政编码</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <foreach name="orderlist" item="o">
                                <tr>
                                    <td height="28">{$o['id']}</td>
                                    <td>{$o['order_num']}</td>
                                    <td>{$o['title']}</td>
                                    <td>￥<?php echo $o['allprice'] / 100; ?></td>
                                    <td>{$o['consignee']}</td>
                                    <td>{$o['contact_number']}</td>
                                    <td>{$o['address']}</td>
                                    <td>{$o['postcode']}</td>
                                    <td>已付款、待发货</td>
                                    <td>
                                        <a href="__CONTROLLER__/paidPro/id/{$o.id}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
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
</body>
</html>
