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

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>

    <script type="text/javascript">
        function del(id) {
            if (id != '') {
                swal({
                    title: "确定要删除该实名认证信息吗？",
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
                        url: "/dmooo.php/HostAuthentication/del",
                        dataType: "html",
                        data: "id=" + id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            }
                            if (msg == '0') {
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
        function seeImg(url) {
            swal({
                title: "查看证件",
                text: "<img width='70%' src='" + url + "'></img>",
                html: true
            });
        }
    </script>

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 直播管理 &raquo; 直播实名认证列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="layui-form">
                        <!-- 订单号：<input type="text" placeholder="" name="orderId" class="form-control">
                        商品名称：<input type="text" placeholder="" name="skuName" class="form-control">
                        所属用户：<input type="text" placeholder="" name="username" class="form-control">
                        订单状态：<select class="form-control" name="validCode">
                            <option value="">请选择订单状态</option>
                            <option value="-1">未知</option>
                            <option value="2">无效-拆单</option>
                            <option value="3">无效-取消</option>
                            <option value="4">无效-京东帮帮主订单</option>
                            <option value="5">无效-账号异常</option>
                            <option value="6">无效-赠品类目不返佣</option>
                            <option value="7">无效-校园订单</option>
                            <option value="8">无效-企业订单</option>
                            <option value="9">无效-团购订单</option>
                            <option value="10">无效-开增值税专用发票订单</option>
                            <option value="11">无效-乡村推广员下单</option>
                            <option value="12">无效-自己推广自己下单</option>
                            <option value="13">无效-违规订单</option>
                            <option value="14">无效-来源与备案网址不符</option>
                            <option value="15">待付款</option>
                            <option value="16">已付款</option>
                            <option value="17">已完成</option>
                            <option value="18">已结算</option>
                        </select>
                        <button class="layui-btn" type="submit">查询</button> -->
                        <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:60px;">真实姓名</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="real_name" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="width:60px;">审核状态</label>
                                    <div class="layui-input-inline">
                                        <select class="form-control" name="real_status">
                                            <option value="">请选择审核状态</option>
                                            <option value="check">审核中</option>
                                            <option value="pass">审核通过</option>
                                            <option value="fail">审核拒绝</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <button class="layui-btn" type="submit">查询</button>
                                </div>
                            </div>
                    </form>
                    <a class="layui-btn pull-right" href="javascript:;" onclick="live_set()" style="margin-right: 15px;">相关配置</a>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>用户ID</th>
                                <th>用户昵称</th>
                                <th>用户等级</th>
                                <th>真实姓名</th>
                                <th>身份证号码</th>
                                <th>身份证正面照</th>
                                <th>身份证背面照</th>
                                <th>审核状态</th>
                                <th>申请时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $User = new \Common\Model\UserModel();
                            $UserDetail = new \Common\Model\UserDetailModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <?php
                                    //订单信息
                                    $user_id = $l['user_id'];
                                    $UserMsg = $User->where("uid=$user_id")->find();
                                    $UserDetailMsg = $UserDetail->where("user_id=$user_id")->find();
                                    ?>
                                    <td>{$l['real_id']}</td>
                                    <td>{$l['user_id']}</td>
                                    <td>{$UserDetailMsg['nickname']}</td>
                                    <td>{$UserMsg['group_id']}</td>
                                    <td>{$l['real_name']}</td>
                                    <td>{$l['real_card']}</td>
                                    <td>
                                        <?php
                                            $front_url = $l['front_url'];
                                            if ($l['front_url'] && !is_url($l['front_url'])) {
                                                $front_url = WEB_URL . $l['front_url'];
                                            }
                                            echo "<a href='javascript:seeImg(" . '"' . $front_url . '"' . ")'><img src='$front_url'></img></a>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $emblem_url = $l['emblem_url'];
                                            if ($l['emblem_url'] && !is_url($l['emblem_url'])) {
                                                $emblem_url = WEB_URL . $l['emblem_url'];
                                            }
                                            echo "<a href='javascript:seeImg(" . '"' . $emblem_url . '"' . ")'><img src='$emblem_url'></img></a>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($l['real_status'] == 'check') {
                                                echo '<font>审核中</font>';
                                            }
                                            if ($l['real_status'] == 'fail') {
                                                echo '<font color="red">未通过</font>';
                                            }
                                            if ($l['real_status'] == 'pass') {
                                                echo '<font color="green">审核通过</font>';
                                            }
                                        ?>
                                    </td>
                                    <td>{$l['add_time']}</td>
                                    <td>
                                        <a href="__CONTROLLER__/update/id/{$l.real_id}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$l.real_id});" title="删除">
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
<script>
    // 相关配置
    function live_set() {
        layer.open({
            type: 2,
            content: '__MODULE__/HostAuthentication/LiveRoomSet',
            title: '相关配置',
            area: ['40%', '350px'],
            btn: ['立即提交', '取消'],
            yes: function(index, layero) {
                var iframeWindow    = window['layui-layer-iframe'+ index],
                    submitID            = 'LAY-user-back-submit',
                    submit              = layero.find('iframe').contents().find('#'+ submitID);
                submitID            = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit('+ submitID +')', function(data) {
                    var field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__MODULE__/HostAuthentication/LiveRoomSet',
                        type: 'post',
                        data: field,
                        success: function(res) {
                            res = JSON.parse(res);
                            if (res.code == 'succ') {
                                layer.closeAll();           // 关闭弹层
                                swal({title:res.msg, text:"", type:"success"},function(){location.reload();});
                            } else {
                                swal({title:res.msg, text:"", type:"error"});
                            }
                        }
                    });

                    return false;               // 禁止跳转，否则会提交两次，且页面会刷新
                });

                // 触发提交
                submit.trigger('click');
            }
        });
    }
</script>
</body>
</html>