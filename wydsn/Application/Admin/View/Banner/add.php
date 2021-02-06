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
    <!--颜色拾取插件-->
    <link rel="stylesheet" type="text/css" href="__ADMIN__/color/css/jquery.bigcolorpicker.css" />
    <script type="text/javascript" src="__ADMIN__/color/js/jquery.bigcolorpicker.min.js"></script>
    <!--颜色拾取插件-->
    <script>
        $(document).ready(function(){
            //颜色拾取
            $("#c1").bigColorpicker("c1",'#00AC2B');

            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：内容管理 &raquo; Banner/广告图管理 &raquo; {$cat_title} &raquo; 添加图片<a class="layui-btn pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}" style="margin-top: -10px">返回Banner/广告图列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/cat_id/{$cat_id}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="title" placeholder="" required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传图片</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" accept="image/*" class="layui-input" required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">图片颜色</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" id="c1" name="color" value="#FFFFFF" required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort">
                                    <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">超链接</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="href">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_show" value="Y" checked title='是'>
                                        <input type="radio" name="is_show" value="N" title='否'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">功能类型</label>
                                <div>
                                    <div class="layui-input-block">
<!--                                        <input type="radio" name="type" value="1" title='网页' checked/>-->
<!--                                        <input type="radio" name="type" value="2" title='淘宝'/>-->
<!--                                        <input type="radio" name="type" value="3" title='京东'/>-->
<!--                                        <input type="radio" name="type" value="4" title='拼多多'/>-->
<!--                                        <input type="radio" name="type" value="5" title='支付宝'/>-->
<!--                                        <input type="radio" name="type" value="6" title='淘宝年货节'/>-->
<!--                                        <input type="radio" name="type" value="7" title='春节红包'/>-->
<!--                                        <input type="radio" name="type" value="8" title='新人红包'/>-->
<!--                                        <input type="radio" name="type" value="9" title='淘宝商品'/>-->
<!--                                        <input type="radio" name="type" value="10" title='拉新活动'/>-->
<!--                                        <input type="radio" name="type" value="11" title='0元购'/>-->
                                        <select class="layui-input m-b" name="type">
                                            <option value="1">--网页--</option>
                                            <option value="2">--淘宝--</option>
                                            <option value="3">--京东--</option>
                                            <option value="4">--拼多多--</option>
                                            <option value="5">--支付宝--</option>
                                            <option value="6">--淘宝年货节--</option>
                                            <option value="7">--春节红包--</option>
                                            <option value="8">--新人红包--</option>
                                            <option value="9">--淘宝商品--</option>
                                            <option value="10">--拉新活动--</option>
                                            <option value="11">--新人0元购--</option>
                                            <option value="12">--新人专区背景图--</option>
                                            <option value="13">--新手教程--</option>
                                            <option value="14">--分享淘口令--</option>
                                            <option value="15">--限时1元秒杀--</option>
                                            <option value="16">--聚划算榜单--</option>
                                            <option value="17">--超级券--</option>
                                            <option value="18">--达人说--</option>
                                            <option value="19">--必买清单--</option>
                                            <option value="20">--9.9元购--</option>
                                            <option value="21">--限时秒杀--</option>
                                            <option value="22">--拼多多--</option>
                                            <option value="23">--今日爆款--</option>
                                            <option value="24">--京东大促--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="type_value">
                                </div>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;添加</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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