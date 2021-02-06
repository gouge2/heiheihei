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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->

    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

        function deloldimg(cat_id)
        {
            if(cat_id!=''){
                swal({
                    title:"确定删除原分类图片吗？",
                    text:"",
                    type:"warning",
                    showCancelButton:true,
                    cancelButtonText:"取消",
                    confirmButtonColor:"#DD6B55",
                    confirmButtonText:"删除",
                    closeOnConfirm:false
                },function(){
                    $.ajax({
                        type:"POST",
                        url:'/taokeyun.php/ArticleCat/deloldimg',
                        dataType:"html",
                        data:"cat_id="+cat_id,
                        success:function(msg)
                        {
                            if(msg=='1') {
                                swal({
                                    title:"删除原分类图片成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }else {
                                swal({
                                    title:"删除失败！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }
                        }
                    });
                })
            }
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：内容管理 &raquo; 编辑文章分类<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">文章分类基本信息</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">文章分类详情</a>
                                </li>
                            </ul>
                            <form action="__ACTION__/cat_id/{$msg['cat_id']}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="oldimg" value="{$msg['img']}">
                                <input type="hidden" name="oldcontent" value='{$msg.content}'>

                                <div class="tab-content">
                                    <!-- 文章分类基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">分类名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="cat_name" value="{$msg['cat_name']}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">分类图片</label>
                                            <div class="layui-input-block">
                                                <?php
                                                if($msg['img']){
                                                    echo '<img src="'.$msg['img'].'" height="100"/>
                                        <button class="btn btn-primary" type="button" onclick="deloldimg('.$msg['cat_id'].')">删除原图片</button>';
                                                }else {
                                                    echo '暂无';
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传新图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="img" accept="image/*" class="layui-input">
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
                                            <label class="layui-form-label" style="width: 100px;">父级分类</label>
                                            <div class="layui-input-inline" style='margin-left:10px'>
                                                <select class="layui-input m-b" name="parent_id">
                                                    <option value="0">--默认顶级--</option>
                                                    <?php
                                                    foreach ($catlist as $v)
                                                    {
                                                        if(in_array($v['cat_id'], $subarr))
                                                        {
                                                            $disabled='disabled="disabled"';
                                                        }else {
                                                            $disabled='';
                                                        }
                                                        //无法选择自身作为父级
                                                        if($v['cat_id']==$msg['cat_id'])
                                                        {
                                                            $disabled='disabled="disabled"';
                                                        }
                                                        if($v['cat_id']==$msg['parent_id'])
                                                        {
                                                            $select='selected';
                                                        }else {
                                                            $select='';
                                                        }
                                                        echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;" '.$select.' '.$disabled.'>'.$v['lefthtml'].''.$v['cat_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> title='是'>
                                                    <input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?>  title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">关键词</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="keywords" value="{$msg['keywords']}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">简要说明</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="height:100px;">{$msg['description']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章分类基本信息  -->

                                    <!-- 文章分类详情  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">内容</label>
                                            <div class="layui-input-block">
                                                <script name="content" id="editor" type="text/plain" style="height:300px;"><?php echo htmlspecialchars_decode(html_entity_decode($msg['content']));?></script>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章分类详情  -->

<!--                                    <div class="form-group">-->
<!--                                        <div class="col-sm-4 col-sm-offset-2">-->
<!--                                            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑文章分类</button>-->
<!--                                            <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑文章分类</button>
                                            <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>

</body>
</html>