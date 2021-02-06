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
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：管理员管理 &raquo; 权限管理 &raquo; 编辑权限<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/rule_id/{$msg['id']}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">权限名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="title" value="{$msg['title']}"
                                           placeholder="" style="width: 98%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">控制器/方法</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="name" value="{$msg['name']}"
                                           placeholder="" style="width: 98%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">父级分类</label>
                                <div class="layui-input-inline">
                                    <select class="form-control m-b" name="pid" style="width: 98%;">
                                        <option value="0">--默认顶级--</option>
                                        <?php
                                        foreach ($admin_rule as $v) {
                                            if ($v['id'] != $msg['id']) {
                                                if ($v['id'] == $msg['pid']) {
                                                    $select = 'selected';
                                                } else {
                                                    $select = '';
                                                }
                                                echo '<option value="' . $v['id'] . '" style="margin-left: 55px;" ' . $select . '>' . $v['lefthtml'] . '' . $v['title'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">是否开启</label>
                                <div class="layui-input-block" style="width: 98%;">
                                        <input type="radio" name="status"
                                                   value="1" <?php if ($msg['status'] == '1') echo 'checked'; ?> title="是">
                                            <input type="radio" name="status"
                                                   value="0" <?php if ($msg['status'] == '0') echo 'checked'; ?> title="否">
                                    </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" value="{$msg['sort']}" style="width: 98%;">
                                </div>
                                <div class="layui-form-mid layui-word-aux">数字越大越排在前</div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑权限
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