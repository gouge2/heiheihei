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
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：论坛系统 &raquo; 发布帖子<a class="layui-btn pull-right" href="__CONTROLLER__/checkPass/board_id/{$board_id}" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
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
                            <form action="__ACTION__" lay-filter="forms"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">

                                <div class="tab-content">
                                    <!-- 帖子基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">发布用户</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="username" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="title" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="img" accept="image/*" class="layui-input" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item source-item">
                                            <label class="layui-form-label" style="width: 120px;">商品来源</label>
                                            <div class="layui-input-inline">
                                                <select class="layui-input" name="source" style="width:99%" lay-filter="aihaos" required="required"" id="source">
                                                <option value="">-请选择来源-</option>
                                                <foreach name="source" item="vo">
                                                    <option value="{$vo.id}" >{$vo.name}</option>
                                                </foreach>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="layui-form-item hdlx" style="display: none">
                                            <label class="layui-form-label form-labal-tilte">活动类型</label>
                                            <div class="layui-input-block">
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="1" title="商品ID"  <?php if ($msg['diy_id'] == 1) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="2" title="分类" <?php if ($msg['diy_id'] == 2) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="3" title="活动类型" <?php if ($msg['diy_id'] == 3) echo 'checked'; ?>>
                                                <input type="radio" lay-filter="diy_radio" name="diy_id"  value="4" title="自定义" <?php if ($msg['diy_id'] == 4) echo 'checked'; ?>>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_1" style="display: none">
                                            <label class="layui-form-label form-labal-tilte">分类</label>
                                            <div class="layui-input-inline">
                                                <select name="advert_catgray" lay-filter="aihaod" class="two_cate">
                                                    <option value="">请选择类别</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_2" style="display: none">
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
                                        <div class="layui-form-item cat_3">
                                            <label class="layui-form-label form-labal-tilte">商品ID</label>
                                            <div class="layui-input-block layui-input-in">
                                                <input type="text" class="layui-input" name="tb_gid" value="" placeholder="" style="width:99%">
                                                <span class="layui-form-mid layui-word-aux">建议填入123456，123457</span>
                                            </div>
                                        </div>
                                        <div class="layui-form-item cat_4">
                                            <label class="layui-form-label form-labal-tilte">关键词</label>
                                            <div class="layui-input-block layui-input-in">
                                                <input type="text" class="layui-input" name="keyword" value="" style="width:99%">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">联系人</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="linkman" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">联系电话</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="contact" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="address" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">点击量</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="clicknum" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">分享次数</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="share_num" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">所属版块</label>
                                            <div class="layui-input-inline" >
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
                                                    <input type="radio" name="is_show" value="Y" checked title='是'>
                                                    <input type="radio" name="is_show" value="N" title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">是否置顶</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <input type="radio" name="is_top" value="Y" title='是'>
                                                    <input type="radio" name="is_top" value="N" checked title='否'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">置顶时间</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="top_day" value="0" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">移动端内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="mob_text" id="edits" style="height:150px;width:99%;margin-left: 10px;"></textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上传内容图片</label>
                                            <div class="layui-upload">
                                                <button type="button" class="layui-btn" id="uplad">上传图片</button>
                                                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;margin-left: 117px;">
                                                    预览图：
                                                    <div class="layui-upload-list" id="imgsList"></div>
                                                </blockquote>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- 帖子基本信息  -->

                                    <!-- PC端帖子详情  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">简要说明</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="height:100px;width:99%"></textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="content" id="editor" style="height:300px;width:99%;margin-left: 10px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- PC端帖子详情  -->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check" id="subt" lay-filter="sub"></i>&nbsp;发布帖子</button>
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
    let board_id = <?php echo $board_id;?>
    //实例化编辑器
    var ue = UE.getEditor('editor');
    var ues = UE.getEditor('edits');
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    layui.use(['upload','form'], function() {
        var $ = layui.$
            ,form = layui.form
            ,upload = layui.upload;
        if (board_id == 3) {
            $(".source-item").hide();
            $(".shop-item").hide();
        }
        $(function(){
            //输入框的值改变时触发
            $("#inputid").change("input",function(e){
                let forms = form.val('forms');
                $.ajax({
                    url: '/dmooo.php/BbsArticle/checkArticleShop',
                    type: 'get',
                    data: {source: forms.source,article_id: e.delegateTarget.value},
                    beforeSend: function () {
                        this.layerIndex = layer.load(0, {shade: [0.5, '#393D49']});
                    }, success: function (data) {
                        console.log(data);
                        if (data.status == 'error') {
                            layer.msg(data.msg, {icon: 5});//失败的表情
                            return;
                        } else if (data.status == 'success') {
                            layer.msg(data.msg, {
                                icon: 6,//成功的表情
                                time: 1000 //1秒关闭（如果不配置，默认是3秒）
                            }, function () {
                                location.reload();
                            });
                        }
                    },
                    complete: function () {
                        layer.close(this.layerIndex);
                    },
                });

                // console.log(forms.source);
                // //获取input输入的值
                // console.log(e.delegateTarget.value);
            });
        });
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
                console.log('------');
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
