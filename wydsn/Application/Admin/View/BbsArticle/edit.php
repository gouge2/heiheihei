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
    <!-- ueditor -->

    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');
        var ues = UE.getEditor('edits');

        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

        function deloldimg(id)
        {
            if(id!=''){
                swal({
                    title:"确定删除原图片吗？",
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
                        url:'/taokeyun.php/BbsArticle/deloldimg',
                        dataType:"html",
                        data:"id="+id,
                        success:function(msg)
                        {
                            if(msg=='1') {
                                swal({
                                    title:"删除原图片成功！",
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

        function delmobimg(img,id)
        {
            if(img){
                swal({
                    title:"确定删除该图片吗？",
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
                        url:"/taokeyun.php/BbsArticle/delmobimg",
                        dataType:"html",
                        data:"img="+img+'&id='+id,
                        success:function(msg)
                        {
                            if(msg=='1') {
                                swal({
                                    title:"删除原图片成功！",
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
                    <h3>当前位置：论坛系统 &raquo; 编辑帖子<a class="layui-btn pull-right" href="__CONTROLLER__/checkPass/board_id/{$board_id}" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">帖子基本信息</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">PC端帖子详情</a>
                                </li>
                            </ul>
                            <form action="__ACTION__/id/{$msg['id']}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="oldimg" value="{$msg['img']}">
                                <input type="hidden" name="oldcontent" value='{$msg.content}'>

                                <div class="tab-content">
                                    <!-- 帖子基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">发布用户</label>
                                            <div class="layui-input-block">
                                                <?php
                                                $uid=$msg['uid'];
                                                $User=new \Common\Model\UserModel();
                                                $UserMsg=$User->getUserMsg($uid);
                                                ?>
                                                <input type="text" class="layui-input" name="" value="{$UserMsg['phone']}" disabled placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="title" value="{$msg['title']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题图片</label>
                                            <div class="layui-input-block">
                                                <?php
                                                if($msg['img']){
                                                    echo '<img src="'.$msg['img'].'" height="100"/>
                                        <button class="btn btn-primary" type="button" onclick="deloldimg('.$msg['id'].')">删除原图片</button>';
                                                }else {
                                                    echo '暂无';
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上传新图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="img" accept="image/*" class="layui-input" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item source-item">
                                            <label class="layui-form-label" style="width: 120px;">商品来源</label>
                                            <div class="layui-input-inline">
                                                <select class="layui-input m-b" name="source" style="width:99%" lay-filter="aihaos" required="required"" id="source">
                                                <option value="">-请选择来源-</option>
                                                <foreach name="source" item="vo">
                                                    <option value="{$vo.id}" <?php echo ($vo['id'] == $msg['source']) ? 'selected' : ''; ?> >{$vo.name}</option>
                                                </foreach>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item source-item hdlx" style="display: none">
                                            <label class="layui-form-label form-labal-tilte">活动类型</label>
                                            <div class="layui-input-block">
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="1" title="商品ID"  <?php if ($msg['diy_id'] == 1) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="2" title="分类" <?php if ($msg['diy_id'] == 2) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="3" title="活动类型" <?php if ($msg['diy_id'] == 3) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="4" title="自定义" <?php if ($msg['diy_id'] == 4) echo 'checked'; ?>>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_1 source-item" style="display: none">
                                            <label class="layui-form-label form-labal-tilte">分类</label>
                                            <div class="layui-input-inline">
                                                <select name="advert_catgray" lay-filter="aihaod" class="two_cate">
                                                    <option value="">请选择类别</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_2 source-item" style="display: none">
                                            <label class="layui-form-label form-labal-tilte">活动</label>
                                            <div class="layui-input-inline">
                                                <select name="advert_cat" lay-filter="aihao" id="advert_cat_dis">
                                                    <option value="">请选择活动</option>
                                                    <option value="1" id="tb_option_1" disabled=disabled>快抢商品（淘宝）</option>
                                                    <option value="2" id="jd_option_2" disabled=disabled>9块9专场（京东）</option>
                                                    <option value="3" id="jd_option_3" disabled=disabled>精选好货（京东）</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_3 source-item">
                                            <label class="layui-form-label form-labal-tilte">商品ID</label>
                                            <div class="layui-input-block layui-input-in">
                                                <input type="text" class="layui-input" name="tb_gid" value="{$msg['tb_gid']}" placeholder="" style="width:99%">
                                                <span class="layui-form-mid layui-word-aux">建议填入123456，123457</span>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_4">
                                            <label class="layui-form-label form-labal-tilte">关键词</label>
                                            <div class="layui-input-block layui-input-in">
                                                <input type="text" class="layui-input" name="keyword" value="{$msg['keyword']}" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">联系人</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="linkman" value="{$msg['linkman']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">联系电话</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="contact" value="{$msg['contact']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="address" value="{$msg['address']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">点击量</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="clicknum" value="{$msg['clicknum']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">分享次数</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="share_num" value="{$msg['share_num']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">所属版块</label>
                                            <div class="layui-input-inline">
                                                <select class="layui-input m-b select-board" name="board_id" style="width:99%">
                                                    <option value="">-请选择所属版块-</option>
                                                    <?php
                                                    foreach ($boardlist as $l) {
                                                        if($l['board_id']==$board_id) {
                                                            $select='selected';
                                                        }else {
                                                            $select='';
                                                        }
                                                        echo '<option value="'.$l['board_id'].'" style="margin-left:55px;" '.$select.'>'.$l['lefthtml'].''.$l['board_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">是否显示</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> title='是'>
                                                    <input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">是否置顶</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="is_top" value="Y" <?php if($msg['is_top']=='Y') echo 'checked'; ?> title='是'>
                                                    <input type="radio" name="is_top" value="N" <?php if($msg['is_top']=='N') echo 'checked'; ?> title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">置顶时间</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="top_day" value="{$msg['top_day']}" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">移动端内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="mob_text" id="edits" style="height:150px;width:99%;margin-left: 10px;">{$msg.mob_text}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">已上传图片</label>
                                            <div class="layui-input-block">
                                                <!--imgContainer-->
                                                <div class="imgContainer">
                                                    <ul class="clearfix">
                                                        <?php
                                                        if($msg['mob_img']) {
                                                            $img_arr=$msg['mob_img_arr'];
                                                            $img_num=count($img_arr);
                                                            for ($i=0;$i<$img_num;$i++) {
                                                                echo '<li>
                                                    <span class="imgbox"><img src="'.$img_arr[$i].'"/></span>
					                               <span class="del" onclick="delmobimg(\''.$img_arr[$i].'\','.$msg['id'].')"><em>×</em>删除</span>
                                                </li>';
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <!--imgContainer-->
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上传内容图片</label>
                                            <div class="layui-upload">
                                                <button type="button" class="layui-btn" id="uplad">上传图片</button>
                                                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;margin-left: 117px;">
                                                    预览图：
                                                    <div class="layui-upload-list" id="imgsList">
                                                    </div>
                                                </blockquote>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">审核结果</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="check_result" value="Y" <?php if($msg['check_result']=='Y') echo 'checked'; ?> title='是'>
                                                    <input type="radio" name="check_result" value="N" <?php if($msg['check_result']=='N') echo 'checked'; ?> title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">审核不通过原因</label>
                                            <div class="layui-input-block">
                                                <textarea name="check_reason" placeholder="" class="layui-input" style="height:100px;width: 99%;">{$msg['check_reason']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 帖子基本信息  -->

                                    <!-- PC端帖子详情  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">简要说明</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="height:100px;width: 99%;">{$msg['description']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="content" id="editor" style="height:300px;width:99%;margin-left: 10px;">{$msg['content']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- PC端帖子详情  -->

                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑帖子</button>
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
<script>
    let board_id = <?php echo $board_id;?>;
    //实例化编辑器
    // var ue = UE.getEditor('editor');
    //
    layui.use(['upload','form'], function() {
        var $ = layui.$
            ,form = layui.form
            ,upload = layui.upload;

        if (board_id == 3) {
            $(".source-item").hide();
            $(".shop-item").hide();
            $(".select-board").attr("disabled","disabled");
            form.render('select');
        }
        var demoListView = $('#imgsList')
        uploadListIns = upload.render({
            elem: '#uplad'
            ,url: '__ACTION__' //改成您自己的上传接口
            ,multiple: true
            ,number: 4
            ,field:'imglist[]'
            ,auto: false
            ,bindAction: '#subt'
            ,choose: function (obj) {
                var files = obj.pushFile();
                obj.preview(function(index, file, result){
                    var span = $(['<span id="upload-'+ index +'">' +
                    '<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">' +
                    '<button style="margin:-110px 0 0 -20px;" class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>' +
                    '</span>'].join(''));
                    span.find('.demo-delete').on('click', function(){
                        delete files[index]; //删除对应的文件
                        span.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });
                    demoListView.append(span);
                });
            }
        });
        let tvalue = '';
        form.on('select(aihaos)', function(data){
            switch (parseInt(data.value)) {
                case 2:
                    tvalue = 'tb';
                    $("#tb_option_1").attr("disabled",false);
                    $("#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                case 3:
                    tvalue = 'jd';
                    $("#tb_option_1").attr("disabled",true);
                    $("#jd_option_2,#jd_option_3").attr("disabled",false);
                    $(".hdlx").css("display","block");
                    form.render('select');
                    break;
                case 4:
                    tvalue = 'pdd';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                case 25:
                    tvalue = 'self';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","block");
                    break;
                default:
                    tvalue = '';
                    $("#tb_option_1,#jd_option_2,#jd_option_3").attr("disabled",true);
                    $(".hdlx").css("display","none");
                    break;
            }

            if (tvalue) {
                let url = '/dmooo.php/System/getSourceList?type=' + tvalue;
                $.get(url,function(data) {
                    if (data.length !== 0) {
                        let arrs = eval(data);
                        $(".two_cate").empty();
                        $.each(arrs,function(index,item){
                            $(".two_cate").append(new Option(item.name,item.cat_id));
                        });
                        form.render("select");
                    }
                    tvalue = '';
                });
            } else $(".two_cate").empty();form.render("select");

        });

        form.on('radio(diy_radio)', function(data){
            let source = $("#source option:selected").val();
            let source_type = '';
            switch (parseInt(source)) {
                case 2:
                    source_type = 'tb';
                    break;
                case 3:
                    source_type = 'jd';
                    break;
                case 4:
                    source_type = 'pdd';
                    break;
                case 25:
                    source_type = 'self';
                    break;
            }
            switch (parseInt(data.value)) {
                case 1:
                    $(".cat_1,.cat_2,.cat_4").css("display", "none");
                    $(".cat_3").css("display", "block");
                    $(".two_cate").empty();
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_word":""});
                    break;
                case 2:
                    $(".cat_1").css("display", "block");
                    $(".cat_2,.cat_3,.cat_4").css("display", "none");
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_cat_id":""});
                    if (source_type) {
                        let url = '/dmooo.php/System/getSourceList?type=' + source_type;
                        $.get(url,function(data){
                            if (data.length !== 0) {
                                let arrs = eval(data);
                                $(".two_cate").empty();
                                $.each(arrs,function(index,item){
                                    $(".two_cate").append(new Option(item.name,item.cat_id));
                                    $('.two_cate option[value="{$msg['advert_catgray']}"]').attr("selected", "selected");
                                });
                                layui.form.render("select");
                            }
                        });
                    } else $(".two_cate").empty();layui.form.render("select");
                    break;
                case 3:
                    $(".cat_1,.cat_3,.cat_4").css("display", "none");
                    $(".cat_2").css("display", "block");
                    $(".two_cate").empty();
                    break;
                default:
                    $(".cat_1,.cat_2,.cat_3").css("display", "none");
                    $(".cat_4").css("display", "block");
                    $(".two_cate").empty();
                    form.val('forms',{"advert_cat":""});
                    form.val('forms',{"advert_cat_id":""});
                    break;
            }
        });
    });
</script>
</body>
</html>
