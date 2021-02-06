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
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>当前位置：代理商管理 &raquo; 启用代理系统</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h3><strong style="color:red;">友情提示：请在配置好首页广告图、分享海报、淘宝推荐商品功能后开启代理商系统，不配置将导致代理商属对应的下级用户app产生错误！</strong></h3>
                        <h3><strong style="color:red;">友情提示：至少配置一张首页广告图、一张分享海报且都为显示状态后开启代理商系统！</strong></h3>
                        <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_agent_switch" value="{$msg['agent_switch']}">
                            <br><div class="form-group">
                                <label class="col-sm-2 control-label">是否开启代理商系统</label>
                                <div>
                                    <div class="radio i-checks">
                                        <input type="radio" name="agent_switch" value="Y" <?php if($msg['agent_switch']=='Y'){echo 'checked';}?>> <i></i>是&nbsp;&nbsp;
                                        <input type="radio" name="agent_switch" value="N" <?php if($msg['agent_switch']=='N'){echo 'checked';}?>> <i></i>否
                                    </div>
                                </div>
                            </div><br>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-1">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;修改</button>
                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>