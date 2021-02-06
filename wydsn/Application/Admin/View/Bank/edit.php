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

    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });

        function deloldimg(bank_id) {
            if (bank_id != '') {
                swal({
                    title: "确定删除原标题图片吗？",
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
                        url: '/taokeyun.php/Bank/deloldimg',
                        dataType: "html",
                        data: "bank_id=" + bank_id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除原图片成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "删除失败！",
                                    text: "",
                                    type: "success"
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
                <div class="ibox-content">
                    <h3>当前位置：商城系统 &raquo; 银行管理 &raquo; 编辑银行<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/bank_id/{$msg.bank_id}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">银行名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="bank_name" value="{$msg.bank_name}"
                                           placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">银行logo</label>
                                <div class="layui-input-block">
                                    <?php
                                    if ($msg['icon']) {
                                        echo '<img src="' . $msg['icon'] . '" height="100"/>
                                        <button class="layui-btn" type="button" onclick="deloldimg(' . $msg['bank_id'] . ')">删除原图片</button>';
                                    } else {
                                        echo '暂无';
                                    } ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传新logo</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" accept="image/*" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">背景图</label>
                                <div class="layui-input-block">
                                    <?php
                                    if ($msg['bg_img']) {
                                        echo '<img src="' . $msg['bg_img'] . '" height="100"/>';
                                    } else {
                                        echo '暂无';
                                    } ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传背景图</label>
                                <div class="layui-input-block">
                                    <input type="file" name="bg_img" accept="image/*" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" value="{$msg.sort}">
                                    <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_show"
                                               value="Y" <?php if ($msg['is_show'] == 'Y') {
                                            echo 'checked';
                                        } ?> title='是'>
                                        <input type="radio" name="is_show"
                                               value="N" <?php if ($msg['is_show'] == 'N') {
                                            echo 'checked';
                                        } ?> title='否'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑银行
                                    </button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i
                                                class="fa fa-refresh"></i>&nbsp;重置
                                    </button>
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