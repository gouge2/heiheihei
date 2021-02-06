<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>
    <script>
        function changeIndexShow(id, status) {
            if (id != '') {
                $.ajax({
                    type: "POST",
                    url: '__CONTROLLER__/mod',
                    dataType: "html",
                    data: "id=" + id + "&status=" + status,
                    success: function (msg) {
                        if (msg == '1') {
                            if (status == 1) {
                                swal({
                                    title: "已审核！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "已拒绝！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            }

                        } else {
                            swal({
                                title: "审核失败！",
                                text: "",
                                type: "error"
                            }, function () {
                                location.reload();
                            })
                        }
                    }
                });
            }
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：商城系统 &raquo; 提现管理</h3>
                </div>
                <div class="ibox-content">
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商户ID</th>
                                    <th>用户昵称</th>
                                    <th>提现金额</th>
                                    <th>打款账户</th>
                                    <th>打款名称</th>
                                    <th>打款时间</th>
                                    <th>打款类型</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <foreach name="list" item="l">
                                    <tr>
                                        <td>{$l['id']}</td>
                                        <td>{$l['meid']}</td>
                                        <td>{$l['name']}</td>
                                        <td>{$l['bood_money']}</td>
                                        <td>{$l['account_mobile']}</td>
                                        <td>{$l['account_name']}</td>
                                        <td>{$l['pay_time']}</td>
                                        <td>
                                            <?php
                                            if ($l[payment] == 'wxpay') {
                                                $payment = '微信';
                                            } else if ($l[payment] == 'alipay') {
                                                $payment = '支付宝';
                                            }else {
                                                $payment = '小程序支付';
                                            }
                                            ?>
                                            {$payment}
                                        </td>

                                        <td>

                                            <if condition='$l[pay_status] eq 0'>
                                                <button type="button" class="layui-btn layui-btn-xs"
                                                        onclick="changeIndexShow({$l.id},'1');">审核
                                                </button>
                                                <button type="button" class="layui-btn layui-btn-danger layui-btn-xs"
                                                        onclick="changeIndexShow({$l.id},'2');">&nbsp;拒绝&nbsp;&nbsp;
                                                </button>
                                                <else/>
                                                已审核
                                            </if>
                                        </td>
                                    </tr>
                                </foreach>
                                </tbody>
                            </table>
                            <div class="pages">{$page}</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
