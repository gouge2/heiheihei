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
    <!-- <script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script> -->

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 导出会员列表</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="tmp" value="1">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">所属分组</label>
                                <div class="layui-input-inline">
                                    <select  name="group_id">
                                        <option value="">--请选择所属分组--</option>
                                        <?php
                                        foreach($glist as $g) {
                                            if($g['id']==$group_id) {
                                                $select='selected';
                                            }else {
                                                $select='';
                                            }
                                            echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">会员名</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="username" placeholder="" style="width: 92%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">手机号码</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="phone" placeholder="" style="width: 92%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">邮箱</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="email" placeholder="" style="width: 92%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">注册开始日期</label>
                                <div class="layui-input-block">
                                    <input id="begin_time" class="layui-input" name="begin_time" lay-verify="date"  placeholder="" style="width: 92%;" >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">注册结束日期</label>
                                <div class="layui-input-block">
                                    <input id="end_time" class="layui-input" name="end_time" lay-verify="date" style="width: 92%;" placeholder="">
                                </div>
                            </div>
<!--                            <div class="layui-form-item">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;导出会员</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;导出会员</button>
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

layui.use(['form', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,laydate = layui.laydate;
  
  //日期
  laydate.render({
    elem: '#begin_time'
  });
  laydate.render({
    elem: '#end_time'
  });
})
</script>
</body>
</html>