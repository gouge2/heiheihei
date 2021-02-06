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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>
    <script type="text/javascript">
        function del(invoice_id) {
            if (invoice_id != '') {
                swal({
                    title: "确定要删除该发票信息吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: "/taokeyun.php/Invoice/del",
                        dataType: "html",
                        data: "invoice_id=" + invoice_id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "操作失败！",
                                    text: "",
                                    type: "error"
                                }, function () {
                                    location.reload();
                                })
                            }
                        }
                    });
                })
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
                    <h3>当前位置： 商城系统 &raquo; 发票信息管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">
                        <input type="hidden" name="p" value="1">
                        <!-- 发票类型：<select name="type" class="form-control">
                            <option value="">-请选择-</option>
                            <option value="1">企业</option>
                            <option value="2">个人</option>
                        </select>
                        发票抬头：<input type="text" placeholder="" name="purchaser" class="form-control" style="width:90px">
                        纳税人识别号：<input type="text" placeholder="" name="taxpayer_id" class="form-control"
                                      style="width:90px">
                        地址：<input type="text" placeholder="" name="address" class="form-control" style="width:90px">
                        所属用户：<input type="text" placeholder="" name="user_account" class="form-control"
                                    style="width:90px"> -->
                        

                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:60px'>发票类型</label>
                            <div class="layui-input-inline">
                                <select name="type" >
                                    <option value="">-请选择-</option>
                                    <option value="1">企业</option>
                                    <option value="2">个人</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">发票抬头</label>
                            <div class="layui-input-inline">
                                <input type="text" name="purchaser"  autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:90px'>纳税人识别号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="taxpayer_id"  autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:30px'>地址</label>
                            <div class="layui-input-inline">
                                <input type="text" name="address"  autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">所属用户</label>
                            <div class="layui-input-inline">
                                <input type="text" name="user_account"  autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" type="submit">查询</button>
                        </div>
                        

                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加发票</a>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>发票类型</th>
                                <th>发票抬头</th>
                                <th>纳税人识别号</th>
                                <th>开户行</th>
                                <th>账号</th>
                                <th>联系电话</th>
                                <th>地址</th>
                                <th>收件人</th>
                                <th>所属用户</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $User = new \Common\Model\UserModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['invoice_id']}</td>
                                    <td>
                                        <?php
                                        if ($l['type'] == '1') {
                                            $type = '企业';
                                        } else {
                                            $type = '个人';
                                        }
                                        echo $type;
                                        ?>
                                    </td>
                                    <td>{$l['purchaser']}</td>
                                    <td>{$l['taxpayer_id']}</td>
                                    <td>{$l['bank']}</td>
                                    <td>{$l['account']}</td>
                                    <td>{$l['contact']}</td>
                                    <td>{$l['address']}</td>
                                    <td>{$l['linkman']}</td>
                                    <td>
                                        <?php
                                        //获取用户信息
                                        $UserMsg = $User->getUserDetail($l['user_id']);
                                        echo $UserMsg['account'];
                                        ?>
                                    </td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/invoice_id/{$l.invoice_id}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$l.invoice_id});" title="删除">
                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
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