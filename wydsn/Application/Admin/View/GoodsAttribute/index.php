<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">-->
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">

    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->
    <script type="text/javascript">
        function changeshow(id, status) {
            if (id != '') {
                $.ajax({
                    type: "POST",
                    url: '/taokeyun.php/GoodsAttribute/changeshow',
                    dataType: "html",
                    data: "id=" + id + "&status=" + status,
                    success: function (msg) {
                        if (msg == '1') {
                            swal({
                                title: "修改状态成功！",
                                text: "",
                                type: "success"
                            }, function () {
                                location.reload();
                            })
                        } else {
                            swal({
                                title: "修改状态失败！",
                                text: "",
                                type: "error"
                            }, function () {
                                location.reload();
                            })
                        }
                    }
                });
            }
        }

        function del(goods_attribute_id) {
            if (goods_attribute_id != '') {
                swal({
                    title: "确定要删除该条商品分类属性吗？",
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
                        url: "/taokeyun.php/GoodsAttribute/del",
                        dataType: "html",
                        data: "goods_attribute_id=" + goods_attribute_id,
                        success: function (msg) {
                            if (msg == '2') {
                                swal({
                                    title: "该商品分类属性已配置属性值，需要先删除已配置的属性值！",
                                    text: "",
                                    type: "error"
                                }, function () {
                                    location.reload();
                                })
                            }
                            if (msg == '1') {
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            }
                            if (msg == '0') {
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
                    <h3>当前位置： 商城系统 &raquo; {$GoodsCatMsg.cat_name} &raquo; 商品分类属性配置<a class="layui-btn pull-right"
                                                                                      href="__MODULE__/GoodsCat/index" style="margin-top: -10px">返回商品分类列表
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <form action="__CONTROLLER__/add" method="post" role="form" class="form-inline pull-left">
                        <input type="hidden" name="goods_cat_id" value="{$goods_cat_id}">
                        <div class="layui-inline">
                            <label class="layui-form-label">属性名称</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="goods_attribute_name" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="sort" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">是否显示</label>
                            <div class="layui-input-inline">
                                <select class="form-control" name="is_show">
                                    <option value="Y">显示</option>
                                    <option value="N">不显示</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" type="submit">查询</button>
                        </div>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort/goods_cat_id/{$goods_cat_id}" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商品分类属性名称</th>
                                    <th>属性值设置</th>
                                    <th>是否显示</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $GoodsAttributeValue = new \Common\Model\GoodsAttributeValueModel();
                                foreach ($list as $l) {
                                    //属性下属性值个数
                                    $goods_attribute_id = $l['goods_attribute_id'];
                                    $num = $GoodsAttributeValue->getValueNum($goods_attribute_id);
                                    //是否显示
                                    if ($l['is_show'] == 'Y') {
                                        $show_str = '<button type="button" class="layui-btn btn-primary btn-sm" onclick="changeshow(' . $goods_attribute_id . ',\'N\');">显示</button>';
                                    } else {
                                        $show_str = '<button type="button" class="layui-btn btn-danger btn-sm" onclick="changeshow(' . $goods_attribute_id . ',\'Y\');">不显示</button>';
                                    }
                                    echo '<tr>
                                		          <td>' . $goods_attribute_id . '</td>
                                				  <td>' . $l['goods_attribute_name'] . '</td>
                                				  <td><a href="__MODULE__/GoodsAttributeValue/index/goods_attribute_id/' . $goods_attribute_id . '/goods_cat_id/' . $goods_cat_id . '" title="属性值设置" class="layui-btn">点击进行属性值设置（' . $num . '）</a></td>
                                				  <td>' . $show_str . '</td>
                                				  <td class="has-warning"><input name="sort[' . $goods_attribute_id . ']" value="' . $l['sort'] . '" class="form-control" style="width:50px;text-align:center"/></td>
                                				  <td>
                                					<a href="__CONTROLLER__/edit/goods_attribute_id/' . $goods_attribute_id . '" title="修改">
                                					  <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                			        </a>
                                			        <a href="javascript:;" onclick="del(' . $goods_attribute_id . ');" title="删除">
                                			          <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                			        </a>
                                			     </td>
                                		       </tr>';
                                }
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <input type="submit" class="layui-btn pull-right" value="统一排序">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>