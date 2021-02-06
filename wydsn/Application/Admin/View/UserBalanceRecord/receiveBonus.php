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
    <style>
        .label-tilte {
            width: 180px;
        }
        .label-input {
            width: 70px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：营销中心 &raquo; 新人红包设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label label-tilte">已购物，领取固定红包</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input label-input" name="rated_amount" value="{$list['rated_amount']}" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label label-tilte">未购物，领取随机红包</label>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount1" value="{$list['random_amount1']}" placeholder="">
                                </div>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount2" value="{$list['random_amount2']}" placeholder="">
                                </div>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount3" value="{$list['random_amount3']}" placeholder="">
                                </div>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount4" value="{$list['random_amount4']}" placeholder="">
                                </div>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount5" value="{$list['random_amount5']}" placeholder="">
                                </div>
                                <div class="layui-input-inline" style="margin-right: -80px;">
                                    <input type="text" class="layui-input label-input" name="random_amount6" value="{$list['random_amount6']}" placeholder="">
                                </div>

                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label label-tilte">活动开始时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="start_time"  id="start_time" placeholder="yyyy-MM-dd" value="{$list['start_time']}">
                                </div>
                                <label class="layui-form-label label-tilte">活动结束时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="end_time" id="end_time" placeholder="yyyy-MM-dd" value="{$list['end_time']}">
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <label class="layui-form-label label-tilte"></label>
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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
<script>
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //日期范围
        laydate.render({
            elem: '#start_time'
        });

        laydate.render({
            elem: '#end_time'
        });
    });
</script>
</body>
</html>