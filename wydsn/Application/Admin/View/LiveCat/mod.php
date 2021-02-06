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
    <script src="__ADMIN_JS__/userRegister.js"></script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action=""  class="form-horizontal layui-form" lay-filter="mod_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="cat_id">
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">分类名称</label>
                                <div class="layui-input-inline" style="width: 300px;margin:0">
                                    <input type="text" name="cat_name" lay-verify="required" value="" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">排序</label>
                                <div class="layui-input-inline" style="width: 180px;margin:0">
                                    <input type="text" name="sort" lay-verify="number" value="" placeholder="请输入排序值，最高100" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 110px;">状态</label>
                                <div class="layui-input-block">
                                    <?php
                                        foreach ($cat['status'] as $k => $v) {
                                            $che = (isset($cat['is_status']) && $cat['is_status'] == $k) ? 'checked="true"' : '';
                                            echo '<input type="radio" name="is_status" value="'. $k .'" title="'. $v['name'] .'" '. $che .' >
                                                  <div class="layui-unselect layui-form-radio layui-form-radioed">
                                                    <i class="layui-anim layui-icon"></i><div>'. $v['name'] .'</div>
                                                  </div>';
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit" id="LAY-user-back-submit" value="提交">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    layui.use([], function() { 
        let form        = layui.form;  

        // 给表单赋值
        form.val("mod_form", { //formTest 即 class="layui-form" 所在元素属性 lay-filter="" 对应的值
            "cat_id": "{$cat.cat_id}",
            "cat_name": "{$cat.cat_name}",
            "sort": "{$cat.sort}"
        });

    });
</script>
</body>
</html>