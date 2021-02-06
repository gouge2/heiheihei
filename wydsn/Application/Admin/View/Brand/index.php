<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet"> -->
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

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".i-checks").iCheck({
                checkboxClass: "icheckbox_square-green",
                radioClass: "iradio_square-green",
            });

            //取消全选
            $('#unselect').click(function () {
                $("input:checkbox").removeAttr("checked");
                $(".i-checks").iCheck({
                    checkboxClass: "icheckbox_square-green",
                    radioClass: "iradio_square-green",
                });
            });
            //全选
            $('#selectall').click(function () {
                $("input:checkbox").prop("checked", "checked");
                $(".i-checks").iCheck({
                    checkboxClass: "icheckbox_square-green",
                    radioClass: "iradio_square-green",
                });
            });

            //批量删除
            $('#batchdel').click(function () {
                var all_id = '';
                $(":checkbox").each(function () {
                    if ($(this).prop("checked")) {
                        all_id += $(this).val() + ',';
                    }
                });
                if (all_id != '') {
                    swal({
                        title: "确定删除这些品牌吗？",
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
                            url: "/taokeyun.php/Brand/batchdel",
                            dataType: "html",
                            data: "all_id=" + all_id,
                            success: function (msg) {
                                if (msg == '1') {
                                    swal({
                                        title: "批量删除成功！",
                                        text: "",
                                        type: "success"
                                    }, function () {
                                        location.reload();
                                    })
                                } else {
                                    swal({
                                        title: "操作失败！",
                                        text: "",
                                        type: "error"
                                    }, function () {
                                        location.reload();
                                    })
                                }
                            }
                        });
                    })
                } else {
                    swal({title: "", text: "请选择需要删除的品牌！"})
                    return false;
                }
            });

        });

        function changeshow(id, status) {
            if (id != '') {
                $.ajax({
                    type: "POST",
                    url: '/taokeyun.php/Brand/changeshow',
                    dataType: "html",
                    data: "brand_id=" + id + "&status=" + status,
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

        function del(id) {
            if (id != '') {
                swal({
                    title: "确定要删除该品牌吗？",
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
                        url: '/taokeyun.php/Brand/del',
                        dataType: "html",
                        data: "brand_id=" + id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "操作失败！",
                                    text: "",
                                    type: "error"
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
                <div class="ibox-title">
                    <h3>当前位置： 商城系统 &raquo; 厂家/品牌管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <!-- 品牌名称：<input type="text" placeholder="" name="search" class="form-control"> -->
                        <div class="layui-inline">
                            <label class="layui-form-label">品牌名称</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="search" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" type="submit">查询</button>
                        </div>
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加品牌商</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>品牌名称</th>
                                    <th>品牌logo</th>
                                    <th>是否显示</th>
                                    <th>联系人</th>
                                    <th>联系方式</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <foreach name="brandlist" item="bl">
                                    <tr>
                                        <td style="text-align: center"><input class="checkbox i-checks" type="checkbox"
                                                                              id="allid[]" value="{$bl['brand_id']}">
                                        </td>
                                        <td>{$bl['brand_id']}</td>
                                        <td>{$bl['name']}</td>
                                        <td>
                                            <?php
                                            if ($bl['logo']) {
                                                echo '<img src="' . $bl['logo'] . '" height="50px">';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <if condition='$bl[is_show] eq Y'>
                                                <button type="button" class="layui-btn layui-btn-xs"
                                                        onclick="changeshow({$bl.brand_id},'N');">显示
                                                </button>
                                                <else/>
                                                <button type="button" class="layui-btn layui-btn-primary layui-btn-xs"
                                                        onclick="changeshow({$bl.brand_id},'Y');">隐藏
                                                </button>
                                            </if>
                                        </td>
                                        <td>{$bl['contractor']}</td>
                                        <td>{$bl['phone']}</td>
                                        <td class="has-warning"><input name="sort[{$bl.brand_id}]" value="{$bl.sort}"
                                                                       class="form-control"
                                                                       style="width:50px;text-align:center"/></td>
                                        <td>
                                            <a href="__CONTROLLER__/edit/brand_id/{$bl.brand_id}" title="修改">
                                                <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                            <a href="javascript:;" onclick="del({$bl.brand_id});" title="删除">
                                                <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                        </td>
                                    </tr>
                                </foreach>
                                <tr>
                                    <td colspan="9">
                                        <input type="submit" class="layui-btn" value="统一排序">
                                        <input type="button" class="layui-btn" id="unselect" value="取消选择">
                                        <input type="button" class="layui-btn" id="selectall" value="全选">
                                        <input type="button" class="layui-btn" id="batchdel" value="批量删除">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>