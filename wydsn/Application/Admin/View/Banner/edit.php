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
                    <h3>当前位置：内容管理 &raquo; Banner/广告图管理 &raquo; 编辑图片<a class="layui-btn pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}" style="margin-top: -10px">返回Banner/广告图列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$id}/cat_id/{$cat_id}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="title" value="{$msg['title']}" placeholder=""  required>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">原图片</label>
                                <div class="layui-input-block">
                                    <?php if($msg['img']){echo '<img src="'.$msg['img'].'" height="100"/>';}else {echo '暂无';} ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传图片</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" accept="image/*" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">图片颜色</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" id="c1" name="color" value="{$msg['color']}" required>
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
                                <label class="layui-form-label" style="width: 100px;">超链接</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="href" value="{$msg['href']}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> title='是'>
                                        <input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> title='否'>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">功能类型</label>
                                <div>
                                    <div class="layui-input-block">
                                        <select class="layui-input m-b" name="type" lay-filter="aihaos" required="required"" id="source">
                                        <foreach name="advertSource" item="vo">
                                            <option value="{$vo.id}" <?php echo ($vo['id'] == $msg['type']) ? 'selected' : ''; ?> >{$vo.name}</option>
                                        </foreach>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="type_value" value="{$msg.type_value}">
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
                                    <input type="text" class="layui-input" name="advert_cat_id" value="{$msg['advert_cat_id']}">
                                    <span class="layui-form-mid layui-word-aux">建议填入123456，123457</span>
                                </div>
                            </div>
                            <div class="layui-form-item cat_4">
                                <label class="layui-form-label form-labal-tilte">关键词</label>
                                <div class="layui-input-block layui-input-in">
                                    <input type="text" class="layui-input" name="advert_word" value="{$msg['advert_word']}">
                                </div>
                            </div>
                            <!--                            <div class="form-group">-->
                            <!--                                <div class="col-sm-4 col-sm-offset-2">-->
                            <!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
                            <!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    layui.use(['form', 'upload'], function() {
        var $ = layui.$;
        var form = layui.form;
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
                    form.render('select');
                    $(".hdlx").css("display","block");
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
