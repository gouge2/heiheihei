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

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 主播设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                        
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">

                            <div class="tab-content">
               
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">开直播权限</label>

                                        <div class="layui-input-block">
                                            <volist name="glist" id="vo">
                                                <input type="checkbox" name="live[]" value="{$vo.id}" title="{$vo.title}" <?php if (in_array($vo['id'], $msg['live'])) echo 'checked'; ?> >
                                                <div class="layui-unselect layui-form-checkbox">
                                                    <span>{$vo.title}</span>
                                                    <i class="layui-icon layui-icon-ok"></i>
                                                </div>
                                            </volist>
                                        </div>

                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 10%;">发布视频权限</label>

                                        <div class="layui-input-block">
                                            <volist name="glist" id="vo">
                                                <input type="checkbox" name="short[]" value="{$vo.id}" title="{$vo.title}" <?php if (in_array($vo['id'], $msg['short'])) echo 'checked'; ?> >
                                                <div class="layui-unselect layui-form-checkbox">
                                                    <span>{$vo.title}</span>
                                                    <i class="layui-icon layui-icon-ok"></i>
                                                </div>
                                            </volist>
                                        </div>

                                    </div>

                           
                            </div>

                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
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
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>