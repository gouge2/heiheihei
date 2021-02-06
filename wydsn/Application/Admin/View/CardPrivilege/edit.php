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
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->

    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function(){
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
                    <h3>当前位置：黑卡管理 &raquo; 修改商品<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">商品基本信息</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{$msg['id']}"/>
                                <div class="tab-content">
                                    <!-- 商品基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">商品名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="title" value="{$msg['title']}" placeholder="" style="width: 97%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">简单描述</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sub_title" value="{$msg['sub_title']}" style="width: 97%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">跳转链接</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="url" value="{$msg['url']}" style="width: 97%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">logo</label>
                                            <div class="layui-input-block">
                                                <?php
                                                if($msg['logo']){
                                                    echo '<img src="'.$msg['logo'].'" height="100"/>';
                                                }else {
                                                    echo '暂无';
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上传新logo</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="logo" accept="image/*" class="layui-input" style="width: 97%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">排序</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sort" value="{$msg['sort']}" style="width: 97%;">
                                                <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">所属商品分类</label>
                                            <div class="layui-input-inline">
                                                <select class="layui-input m-b" name="cate_id" style="width: 97%;">
                                                    <option value="">请选择</option>
                                                    <?php
                                                    foreach ($catlist as $v) {
                                                        if($msg['cate_id']==$v['id']){
                                                            $select = 'selected';
                                                        }else{
                                                            $select='';
                                                        }


                                                        echo '<option value="'.$v['id'].'" style="margin-left:55px;" '.$select.'>'.$v['category_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上架/下架</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="status" value="Y" <if condition="$msg['status'] eq 'Y'">checked</if> title='是'>
                                                    <input type="radio" name="status" value="N" <if condition="$msg['status'] eq 'N'">checked</if> title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">是否特权商品</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="tequan" value="Y" <if condition="$msg['tequan'] eq 'Y'">checked</if> title='是'>
                                                    <input type="radio" name="tequan" value="N" <if condition="$msg['tequan'] eq 'N'">checked</if> title='否'>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- 商品基本信息  -->

                                    <!--                                <div class="layui-form-item">-->
                                    <!--                                    <div class="col-sm-4 col-sm-offset-2">-->
                                    <!--                                        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑商品</button>-->
                                    <!--                                        <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
                                    <!--                                    </div>-->
                                    <!--                                </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑商品</button>
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