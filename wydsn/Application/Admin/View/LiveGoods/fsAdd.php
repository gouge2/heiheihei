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
                            <input type="hidden" name="uid" value="{$uid}">
                            <input type="hidden" name="sid" value="{$sid}">
                            
                            <div class="layui-form-item goods_div">
                                <label class="layui-form-label" style="width: 130px;">
                                    <?php echo $sid ? '绑定商品' : '讲解商品'; ?>
                                </label>
                                <div class="layui-input-inline" style="width: 60%">
                                    <div class="layui-tab layui-tab-card">
                                        <ul class="layui-tab-title">
                                            <li class="layui-this">淘宝</li>
                                            <li>京东</li>
                                            <li>拼多多</li>
                                            <li>唯品会</li>
                                            <li>自营</li>
                                        </ul>
                                        <div class="layui-tab-content" style="height: 100px;">
                                            <div class="layui-tab-item layui-show">
                                                <label class="layui-form-label" style="width: 90px;">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[tb]" value="" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label" style="width: 90px;">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[jd]" value="" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label" style="width: 90px;">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[pdd]" value="" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label" style="width: 90px;">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[vip]" value="" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <label class="layui-form-label" style="width: 90px;">商品ID</label>
                                                <div class="layui-input-inline" style="width: 80%;">
                                                    <input type="text" name="goods[self]" value="" placeholder="商品ID" autocomplete="off" class="layui-input">
                                                </div>
                                            </div>
                                            <div style="float:left; color:red;">
                                                <?php echo $sid ? '注意：只能绑定一个商品，按顺序拿第一个商品ID' : '注意：多个以中文的 “，” 逗号分开'; ?>
                                            </div>
                                        </div>
                                    </div>
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
</body>
</html>