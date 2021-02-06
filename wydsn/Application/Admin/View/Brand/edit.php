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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css"/>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->
    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

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
                    <h3>当前位置：商城系统 &raquo; 品牌商管理&raquo; 编辑品牌商<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回品牌管理 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#tab-1" aria-expanded="true">品牌基本信息</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-2" aria-expanded="false">品牌详情</a>
                            </li>
                        </ul>
                        <form action="__ACTION__/brand_id/{$msg['brand_id']}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">

                            <div class="tab-content">
                                <!-- 品牌基本信息  -->
                                <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">品牌名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="name" value="{$msg['name']}"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">品牌logo</label>
                                        <div class="layui-input-block">
                                            <?php
                                            if ($msg['logo']) {
                                                echo '<img src="' . $msg['logo'] . '" height="100"/>';
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
                                        <label class="layui-form-label" style="width: 100px;">公司网站</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="url" value="{$msg['url']}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">联系人</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="contractor"
                                                   value="{$msg['contractor']}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">联系电话</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="phone"
                                                   value="{$msg['phone']}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">公司地址</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="address"
                                                   value="{$msg['address']}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">排序</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="sort" value="{$msg['sort']}">
                                            <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="is_show" value="Y" <?php if ($msg['is_show'] == 'Y') echo 'checked'; ?> title='是'>
                                            <input type="radio" name="is_show" value="N" <?php if ($msg['is_show'] == 'N') echo 'checked'; ?> title='否'>
                                        </div>
                                    </div>
                                </div>
                                <!-- 品牌基本信息  -->

                                <!-- 品牌详情  -->
                                <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">内容</label>
                                        <div class="layui-input-block">
                                            <script name="content" id="editor" type="text/plain"
                                                    style="height:300px;"><?php echo htmlspecialchars_decode(html_entity_decode($msg['introduce'])); ?></script>
                                        </div>
                                    </div>
                                </div>
                                <!-- 品牌详情  -->

                                <div class="layui-form-item layui-layout-admin">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑品牌
                                        </button>
                                        <button class="layui-btn layui-btn-primary" type="reset"><i
                                                    class="fa fa-refresh"></i>&nbsp;重置
                                        </button>
                                    </div>
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