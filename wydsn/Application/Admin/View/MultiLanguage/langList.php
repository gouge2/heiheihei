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

    <link href="__ADMIN_CSS__/img.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <style>
        body #preview {
            overflow: hidden !important;
        }

        #st a {
            width: 100px;
            margin-right: 61px;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：插件 &raquo; 多商户</h3>
                </div>
                <div class="ibox-content">
                
                <form  action="__MODULE__/MultiLanguage/addLang" method="get" role="form" class="form-inline pull-right">
                      <input type="hidden" name="cid" value="<?= $_GET['id']?>"/>
                      <input type="hidden"  name="name" value="<?= $_GET['name']?>"/>
                      <input type="hidden" name="lang_sign"  value="<?= $_GET['lang_sign']?>"/>
                       
                      
                        <div class="layui-inline">
                            <label class="layui-form-label" style="width:100px">选择客户端</label>
                            <div class="layui-input-inline">
                                     <select  required  lay-ignore name="client" style="height: 38px;width:150px;" >
                                      <option value="">请选择一个客户端</option>
                                      <option value="android">android</option>
                                      <option value="ios">ios</option>
                                      <option value="小程序">小程序</option>
                                      <option value="h5">h5</option>
                                    </select>    
                               </div>
                          </div>
                         <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-admin" lay-submit="" lay-filter="LAY-user-back-search">
                                添加平台语言
                            </button>
                        </div>
                
                    </form>
                                 
                       
                       
                       
                
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="width: 25%">客户端</th>
                                    <th style="width: 25%">语言标识</th>
                                    <th style="width: 25%">语言来源</th>
                                     <th style="width: 25%">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                               <?php foreach ($langList as $v):?>
                                  <tr>
                                        <td><?= $v['client_type']?></td>
                                        <td><?= $v['lang_sign']?></td>
                                        <td> <?= $v['lang_source'] =='system'? '系统内置':'用户添加'; ?></td>
                                      
                                         <td>
                                           
                                            <div>
                                                 <a style="width: 142px;" class="layui-btn pull-right"
                                                   href="__MODULE__/MultiLanguage/editLang?id=<?= $v['l_id']?>">编辑</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script type="text/javascript">
    layui.use('form', function () {
        var form = layui.form;

        // 分类显示、隐藏开关
        form.on('switch(cat_show)', function (data) {
            let sw = data.elem.checked ? 1 : 2;   // 开关是否开启，true或者false
            let cid = data.value;                  // 开关value值，也可以通过data.elem.value得到
            if (sw == 2) {
                swal({
                        title:"请谨慎执行此项操作，关闭多商户后会造成商户所上架的商品全部下架（数据不会清除，只是做商品下架处理），订单无法继续向下一步流程执行，请确认是否关闭多商户功能？",
                        text:"",
                        type:"warning",
                        showCancelButton:true,
                        confirmButtonText:"去意已决",
                        cancelButtonText:"我再想想",
                        confirmButtonColor:"#b7a7a7",
                        closeOnConfirm:false
                    },function (isConfirm) {
                        if (isConfirm) {
                            swal({
                                    title:"是否已经仔细阅读上一提示警告内容，再次确认是否关闭多商户功能?",
                                    text:"",
                                    type:"warning",
                                    showCancelButton:true,
                                    cancelButtonText:"取消",
                                    confirmButtonColor:"#b7a7a7",
                                    confirmButtonText:"确认关闭",
                                    closeOnConfirm:false
                                },function (isConfirm) {
                                    if (isConfirm) {
                                        swal({
                                            title: "关闭成功！",
                                            type: "success"
                                        }, function () {
                                            $.ajax({
                                                type: "POST",
                                                url: '__CONTROLLER__/catShow',
                                                data: {"sw": sw, "cid": cid}
                                            });
                                            document.getElementById("st").style.display = "none";
                                        })
                                    } else {
                                        swal({
                                            title: "已取消",
                                            text: "您取消了关闭操作！",
                                            type: "error"
                                        })
                                        location.reload();
                                    }}
                            )
                        } else {
                            swal({
                                title: "已取消",
                                text: "您取消了关闭操作！",
                                type: "error"
                            })
                            location.reload();
                        }}
                )

            } else {
                document.getElementById("st").style.display = "block";
                $.ajax({
                    type: "POST",
                    url: '__CONTROLLER__/catShow',
                    data: {"sw": sw, "cid": cid}
                });
            }
        });

    });
</script>
<style>
    .sweet-alert button.cancel {
        background-color: rgb(221, 107, 85);
    }
</style>
</body>
</html>