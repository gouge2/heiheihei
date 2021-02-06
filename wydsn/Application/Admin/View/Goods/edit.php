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

    <style type="text/css">
        #edui1_toolbarbox {position : relative!important;width: auto!important;}
        #edui1_iframeholder {
            max-height: 400px!important;
        }
    </style>

    <!-- ueditor -->
    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
        function deloldvideo(goods_id) {
            if (goods_id != '') {
                swal({
                    title: "确定删除原视频吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: '/taokeyun.php/Goods/deloldvideo',
                        dataType: "html",
                        data: "goods_id=" + goods_id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除原视频成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "删除失败！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
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
                    <h3>当前位置：商品管理 &raquo; 编辑商品<a class="layui-btn pull-right" href="__CONTROLLER__/index/cat_id/{$msg['cat_id']}" style="margin-top: -10px">返回上一页
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
                        <form action="__ACTION__/goods_id/{$msg['goods_id']}" class="form-horizontal layui-form" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="oldimg" value="{$msg['img']}">
                            <input type="hidden" name="oldcontent" value='{$msg.content}'>
                            <div class="tab-content">
                                <!-- 商品基本信息  -->
                                <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="goods_name"
                                                   value="{$msg['goods_name']}" placeholder="" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品编码</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="goods_code"
                                                   value="{$msg['goods_code']}" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">商品图片</label>
                                        <div class="layui-input-block" style="width: 80%;">
                                            <?php
                                            if ($msg['img']) {
                                                echo '<img src="' . $msg['img'] . '" height="100"/>';
                                            } else {
                                                echo '暂无';
                                            } ?>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">上传新图片</label>
                                        <div class="layui-input-block">
                                            <input type="file" name="img" accept="image/*" class="layui-input" style="width: 80%;">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">视频</label>
                                        <div class="layui-input-block" style="width: 80%;">
                                            <?php
                                            if ($msg['video']) {
                                                echo '<a target="_blank" href="' . $msg['video'] . '">' . '视频地址' . '</a>
                                        <button class="layui-btn" type="button" onclick="deloldvideo(' . $msg['goods_id'] . ')">删除原视频</button>';
                                            } else {
                                                echo '暂无';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">上传新视频</label>
                                        <div class="layui-input-block">
                                            <input type="file" name="video" accept="video/*" class="layui-input" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">所属厂家/品牌</label>
                                        <div class="layui-input-inline">
                                            <select class="layui-input m-b" name="brand_id" style="width: 70%;">
                                                <option value="">-请选择所属厂家/品牌-</option>
                                                <?php
                                                foreach ($BrandList as $l) {
                                                    if ($l['brand_id'] == $msg['brand_id']) {
                                                        $select_b = 'selected';
                                                    } else {
                                                        $select_b = '';
                                                    }
                                                    echo '<option value="' . $l['brand_id'] . '" ' . $select_b . '>' . $l['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">参考价格</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="old_price"
                                                   value="{$msg['old_price']}" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">实际价格</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="price"
                                                   value="{$msg['price']}" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">精确到分，如：6.88</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">邮费</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="postage" value="{$msg['postage']}" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">精确到分，如：6.88。包邮则不填或填0</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">总库存数量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="inventory"
                                                   value="{$msg['inventory']}" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">赠送积分</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="give_point"
                                                   value="{$msg['give_point']}" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">会员购买商品后返回相应积分，填写0代表不赠送</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">可抵扣积分</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="deduction_point"
                                                   value="{$msg['deduction_point']}" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item is_gift_goods">
                                        <label class="layui-form-label" style="width: 185px;">是否礼包商品</label>
                                        <div>
                                            <div class="layui-input-inline" style="width: 80%;">
                                                <input type="radio" name="is_gift_goods" lay-filter="is_gift_goods" value="Y" <?php if ($msg['is_gift_goods'] == 'Y') echo 'checked'; ?> title='开启'>
                                                <input type="radio" name="is_gift_goods" lay-filter="is_gift_goods" value="N" <?php if ($msg['is_gift_goods'] == 'N') echo 'checked'; ?> title='关闭'>
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
                                                <input type="radio" name="is_fx_goods" lay-filter="is_fx_goods" value="Y" <?php if ($msg['is_fx_goods'] == 'Y') echo 'checked'; ?> title='开启' {$kqfx}>
                                                <input type="radio" name="is_fx_goods" lay-filter="is_fx_goods" value="N" <?php if ($msg['is_fx_goods'] == 'N') echo 'checked'; ?> title='关闭'>
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
                                                    if ($l['id'] == $msg['group_id']) {
                                                        $select_c = 'selected';
                                                    } else {
                                                        $select_c = '';
                                                    }
                                                    echo '<option value="' . $l['id'] . '" ' . $select_c . '>' . $l['title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="layui-form-mid layui-word-aux">会员购买时只有低于当前所选会员组才能升级，否则只是购买商品</span>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否自定义会员组期限</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_custom_time" value="Y" <?php if ($msg['is_custom_time'] == 'Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="is_custom_time" value="N" <?php if ($msg['is_custom_time'] == 'N') echo 'checked'; ?> title="否">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">自定义会员组到期时间，是则优先于会员组管理定义的过期时间，否按会员组管理定义的过期时间</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">自定义会员组期限</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="custom_time" value="{$msg['custom_time']}" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">上升到所选会员组过期时间，0为永久，其他期限请填写实际天数，填写整数</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">开启会员等级折扣</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_discount"
                                                       value="Y" <?php if ($msg['is_discount'] == 'Y') echo 'checked'; ?> title="开启">
                                                <input type="radio" name="is_discount"
                                                       value="N" <?php if ($msg['is_discount'] == 'N') echo 'checked'; ?> title="关闭">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">虚拟销售量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="virtual_volume"
                                                   value="{$msg['virtual_volume']}" style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">在实际销售量的基础上加上虚拟销售量</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">排序</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="sort" value="{$msg['sort']}"
                                                   style="width: 80%;">
                                            <span class="layui-form-mid layui-word-aux">数字越大越排在前</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">浏览量</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="clicknum"
                                                   value="{$msg['clicknum']}" placeholder="" style="width: 80%;">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">所属商品分类</label>
                                        <div class="layui-input-inline">
                                            <select class="layui-input m-b" name="cat_id" style="width: 80%;">
                                                <?php
                                                foreach ($catlist as $v) {
                                                    if ($v['cat_id'] == $msg['cat_id']) {
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
                                                <input type="radio" name="is_show"
                                                       value="Y" <?php if ($msg['is_show'] == 'Y') echo 'checked'; ?> title="上架">
                                                <input type="radio" name="is_show"
                                                       value="N" <?php if ($msg['is_show'] == 'N') echo 'checked'; ?> title="下架">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否推荐商品</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_top"
                                                       value="Y" <?php if ($msg['is_top'] == 'Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="is_top"
                                                       value="N" <?php if ($msg['is_top'] == 'N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($msg['isverify']!=0){?>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 185px;">审核商品</label>
                                            <div>
                                                <div class="layui-input-block" style="width: 80%;">
                                                    <input type="radio" name="isverify"
                                                           value="Y" title="通过">
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 185px;">是否特价商品</label>
                                        <div>
                                            <div class="layui-input-block" style="width: 80%;">
                                                <input type="radio" name="is_sale"
                                                       value="Y" <?php if ($msg['is_sale'] == 'Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="is_sale"
                                                       value="N" <?php if ($msg['is_sale'] == 'N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 商品基本信息  -->

                                <!-- 商品详情  -->
                                <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">简要说明</label>
                                        <div class="layui-input-block" style="width: 80%;">
                                            <textarea name="description" placeholder="" class="layui-input"
                                                      style="height:100px;width: 90%;">{$msg['description']}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 120px;">内容</label>
                                        <div class="layui-input-block pull-right">
                                            <script name="content" id="editor" type="text/plain" style="height:300px;width: 96%;"><?php echo htmlspecialchars_decode(html_entity_decode($msg['content'])); ?></script>
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
                                                <input type="radio" name="is_sku"
                                                       value="Y" <?php if ($msg['is_sku'] == 'Y') {
                                                    echo 'checked';
                                                } ?> title="开启">
                                                <input type="radio" name="is_sku"
                                                       value="N" <?php if ($msg['is_sku'] == 'N') {
                                                    echo 'checked';
                                                } ?> title="不开启">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux"
                                                  style="width: 300px">如需配置商品属性规则，请选择开启配置</span>
                                        </div>
                                    </div>
                                    <?php
                                    $sku = json_decode($msg['sku_str'], true);
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
                                        foreach ($sku as $sl) {
                                            if ($l['goods_attribute_id'] == $sl['attribute_id']) {
                                                //已配置属性值列表
                                                $value_list = $sl['value_list'];
                                            }
                                        }
                                        //该条是默认选中，便于传递属性分类
                                        $vllist = '<input type="checkbox" name="attribute[' . $l['goods_attribute_id'] . '][]" value="---" checked style="display:none">';
                                        foreach ($l['valuelist'] as $vl) {
                                            //判断已选中的属性值
                                            if (in_array($vl['name'], $value_list)) {
                                                //已选中
                                                $check = 'checked';
                                                $check_value_arr[] = $vl['name'];
                                            } else {
                                                //未选中
                                                $check = '';
                                            }
                                            $vllist .= '<input type="checkbox" name="attribute[' . $l['goods_attribute_id'] . '][]" value="' . $vl['name'] . '" ' . $check . '> ' . $vl['name'] . '&nbsp;';
                                        }
                                        //自定义属性值
                                        if ($check_value_arr) {
                                            $value_diy_arr = array_diff($value_list, $check_value_arr);
                                        } else {
                                            $value_diy_arr = $value_list;
                                        }
                                        $value_diy = implode('-', $value_diy_arr);
                                        echo '<div class="layui-form-item">
                     <label class="layui-form-label" style="width: 185px;">' . $str . '</label>
		             ' . $l['goods_attribute_name'] . '：
		             ' . $vllist . '
		          </div>
		          <div class="layui-form-item">
                    <label class="layui-form-label" style="width: 185px;"></label>
		                              自定义属性值：<input type="text" class="textbox textbox_280" name="attribute_diy[' . $l['goods_attribute_id'] . ']" value="' . $value_diy . '" placeholder=""/>
		            
		          </div>
		          <span class="errorTips">可新增额外属性值，多个值之间用"-"符号连接，如：属性值1-属性值2</span>';
                                    }
                                    ?>
                                </div>
                                <!-- 属性配置  -->

                                <div class="layui-form-item layui-layout-admin">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑商品
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