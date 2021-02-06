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
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css"/>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->
    <style type="text/css">
        #edui1_toolbarbox {position : relative!important;width: auto!important;}
        #edui1_iframeholder {
            max-height: 400px!important;
        }
    </style>
    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：商品管理 &raquo; 添加商品<a class="layui-btn pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}" style="margin-top: -10px">返回上一页
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#tab-1" aria-expanded="true">商品基本信息</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-2" aria-expanded="false">商品详情</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-3" aria-expanded="false">属性配置</a>
                            </li>
                        </ul>
                        <form action="__ACTION__" class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <div class="tab-content">
                                <!-- 商品基本信息  -->
                                <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="goods_name" value=""
                                                   style="width: 80%;"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品编码</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="goods_code"
                                                   style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品图片</label>
                                        <div class="layui-input-block">
                                            <input type="file" name="img" accept="image/*" class="layui-input" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">上传视频</label>
                                        <div class="layui-input-block">
                                            <input type="file" name="video" accept="video/*" class="layui-input" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">所属厂家/品牌</label>
                                        <div class="layui-input-inline">
                                            <select  name="brand_id" style="width: 80%;">
                                                <option value="">-请选择所属厂家/品牌-</option>
                                                <?php
                                                foreach ($BrandList as $l) {
                                                    echo '<option value="' . $l['brand_id'] . '">' . $l['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">参考价格</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="old_price" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">实际价格</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="price" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">精确到分，如：6.88</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">邮费</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="postage" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">精确到分，如：6.88。包邮则不填或填0</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">总库存数量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="inventory" value="1000"
                                                   style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">赠送积分</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="give_point" value="0"
                                                   style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">会员购买商品后返回相应积分，填写0代表不赠送</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">可抵扣积分</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="deduction_point" value="0"
                                                   style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item is_gift_goods">
                                        <label class="layui-form-label" style="width: 185px;">是否礼包商品</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_gift_goods" lay-filter="is_gift_goods"  value="Y" title="开启">
                                                <input type="radio" name="is_gift_goods" lay-filter="is_gift_goods"  value="N" checked title="关闭">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">是否通过购买该自营商品赠送所选会员组</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item profit_money" style="display: none;">
                                        <label class="layui-form-label" style="width: 185px;">礼包初始佣金</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="profit_money" value="{$msg['profit_money']/100}" >
                                            <span class="layui-form-mid layui-word-aux">礼包商品初始佣金额</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item fx_goods" style="display: none;">
                                        <label class="layui-form-label" style="width: 185px;">是否开启分销</label>
                                        <div>
                                            <div class="layui-input-inline" style="width: 80%;">
                                                <?php  $kqfx = (IS_DISTRIBUTION == 'N') ? 'disabled' : ''?>
                                                <input type="radio" name="is_fx_goods" lay-filter="is_fx_goods" value="Y"  title='开启'
                                                       {$kqfx}>
                                                <input type="radio" name="is_fx_goods" lay-filter="is_fx_goods" value="N" checked title='关闭'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item fx_profit_money" style="display: none;">
                                        <label class="layui-form-label" style="width: 185px;">商品分销金额</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="fx_profit_money" value="{$msg['fx_profit_money']/100}" >
                                            <span class="layui-form-mid layui-word-aux">商品初始分销金额</span>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">升级会员组</label>
                                        <div class="layui-input-inline">
                                            <select class="layui-input m-b" name="group_id" style="width: 80%;">
                                                <option value="">-请选择会员组-</option>
                                                <?php
                                                foreach ($groupList as $l) {
                                                    echo '<option value="' . $l['id'] . '">' . $l['title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="layui-form-mid layui-word-aux" style="margin-left: 5%;">会员购买时只有低于当前所选会员组才能升级，否则只是购买商品</span>
                                        </div>
                                    </div>


                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否自定义会员组期限</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_custom_time" value="Y" title="是">
                                                <input type="radio" name="is_custom_time" value="N" checked title="否">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">自定义会员组到期时间，是则优先于会员组管理定义的过期时间，否按会员组管理定义的过期时间</span>

                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">自定义会员组期限</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="custom_time" value="0" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">上升到所选会员组过期时间，0为永久，其他期限请填写实际天数，填写整数</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">开启会员等级折扣</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_discount" value="Y" checked title="开启">
                                                <input type="radio" name="is_discount" value="N" title="关闭">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">虚拟销售量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="virtual_volume"
                                                   style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">在实际销售量的基础上加上虚拟销售量</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">排序</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="sort" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">浏览量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="clicknum" value=""
                                                   style="width: 80%;"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">所属商品分类</label>
                                        <div class="layui-input-inline">
                                            <select class="layui-input m-b" name="cat_id" style="width: 80%;">
                                                <?php
                                                foreach ($catlist as $v) {
                                                    if ($v['cat_id'] == $cat_id) {
                                                        $select = 'selected';
                                                    } else {
                                                        $select = '';
                                                    }
                                                    echo '<option value="' . $v['cat_id'] . '" style="margin-left:55px;" ' . $select . '>' . $v['lefthtml'] . '' . $v['cat_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">上架/下架</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_show" value="Y" checked title="上架">
                                                <input type="radio" name="is_show" value="N" title="下架">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否推荐商品</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_top" value="Y" title="是">
                                                <input type="radio" name="is_top" value="N" checked title="否">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否特价商品</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_sale" value="Y" title="是">
                                                <input type="radio" name="is_sale" value="N" checked title="否">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 商品基本信息  -->

                                <!-- 商品详情  -->
                                <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">简要说明</label>
                                        <div class="layui-input-block">
                                            <textarea name="description" placeholder="" class="layui-input"
                                                      style="height:100px;width: 90%;"></textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">内容</label>
                                        <div class="layui-input-block pull-right">
                                            <script name="content" id="editor" type="text/plain" style="height:300px;width: 96%;"></script>
                                        </div>
                                    </div>
                                </div>
                                <!-- 商品详情  -->

                                <!-- 属性配置  -->
                                <div id="tab-3" class="tab-pane" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否开启属性配置</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_sku" value="Y" title="开启">
                                                <input type="radio" name="is_sku" value="N" checked title="不开启">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">如需配置商品属性规则，请选择开启配置</span>
                                        </div>
                                    </div>
                                    <?php
                                    $i = 0;
                                    foreach ($AttributeList as $l) {
                                        $i++;
                                        if ($i == 1) {
                                            $str = '属性配置：';
                                        } else {
                                            $str = '';
                                        }
                                        //属性值列表
                                        $vllist = '';
                                        //该条是默认选中，便于传递属性分类
                                        $vllist = '<input type="checkbox" name="attribute[' . $l['goods_attribute_id'] . '][]" value="---" checked style="display:none">';
                                        foreach ($l['valuelist'] as $vl) {
                                            $vllist .= '<input type="checkbox" name="attribute[' . $l['goods_attribute_id'] . '][]" value="' . $vl['name'] . '"> ' . $vl['name'] . '&nbsp;';
                                        }
                                        echo '<div class="layui-form-item">
                                             <label class="layui-form-label" style="width: 185px;">' . $str . '</label>
                        		             ' . $l['goods_attribute_name'] . '：
                        		             ' . $vllist . '
                        		          </div>
                        		          <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 185px;"></label>
                        		                              自定义属性值：<input type="text" class="textbox textbox_280" name="attribute_diy[' . $l['goods_attribute_id'] . ']" placeholder=""/>
                        		            
                        		          </div><span class="errorTips">可新增额外属性值，多个值之间用"-"符号连接，如：属性值1-属性值2</span>';
                                    }
                                    ?>
                                </div>
                                <!-- 属性配置  -->

                                <div class="layui-form-item layui-layout-admin">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;添加商品
                                        </button>
                                        <button class="layui-btn layui-btn-primary" type="reset"><i
                                                    class="fa fa-refresh"></i>&nbsp;重置
                                        </button>
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
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
<script>
    var status = $("input[name='is_gift_goods']:checked").val();
    if(status == 'Y')
    {
        $('.profit_money').css('display','block');
        $('.fx_goods').css('display','none');
    }else{
        $('.profit_money').css('display','none');
        $('.fx_goods').css('display','block');
    }
    var status2 = $("input[name='is_fx_goods']:checked").val();
    if(status2 == 'Y')
    {
        $('.fx_profit_money').css('display','block');
        $('.is_gift_goods').css('display','none');
    }else{
        $('.fx_profit_money').css('display','none');
        $('.is_gift_goods').css('display','block');
    }
</script>
<script>
    layui.use('form', function () {
        var form = layui.form;
        form.on('radio(is_gift_goods)', function (data) {
            console.log(data)
            if(data.value == 'Y')
            {
                $('.profit_money').css('display','block');
                $('.fx_goods').css('display','none');

            }else{
                $('.profit_money').css('display','none');
                $('.fx_goods').css('display','block');
            }
            form.render();
        });
        form.on('radio(is_fx_goods)', function (data) {
            if(data.value == 'Y')
            {
                $('.fx_profit_money').css('display','block');
                $('.is_gift_goods').css('display','none');
            }else{
                $('.fx_profit_money').css('display','none');
                $('.is_gift_goods').css('display','block');
            }
            form.render();
        });
    });
    let timer = setInterval(() => {
        let ifram =  $("#ueditor_0")
        if ( ifram ) {
            ifram.contents().find("body").css('overflow-y','auto').css('max-height','500px')
            ifram.contents().find(".edui-editor.edui-default").css('max-height','500px')
            console.log(ifram)
            clearInterval(timer)
        }
    },1000)
</script>
</body>
</html>