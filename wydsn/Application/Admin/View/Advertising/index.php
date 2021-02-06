<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <style>
        body #preview {
            overflow: hidden !important;
        }

        .room_manage {
            display: inline-block;
            margin-left: 10px;
            position: relative;
            width: 120px;
        }

        .room_manage ul {
            position: absolute;
            top: 20px;
            width: 120px;
            background: #fff;
            padding: 5px 0;
            z-index: 99;
            border: 1px solid rgba(0, 0, 0, .3);
            border-radius: 5px;
        }

        .room_manage ul li {
            margin: 3px 0;
            text-indent: 1em;
            height: 25px;
            padding: 3px 0;
        }

        .room_manage ul li:hover {
            background: #eee;
        }

        .none {
            display: none;
        }
    </style>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：视频管理 &raquo; 短视频广告</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">

                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">状态</label>
                            <div class="layui-input-inline">
                                <select class="form-control" name="status">
                                    <option value="" >请选择</option>
                                    <?php
                                    foreach ($search['status'] as $k => $v) {
                                        $che = $v['sel'] ? 'selected="selected"' : '';
                                        echo '<option value="'. $k .'" '. $che .' >'. $v['name'] .'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                            </div>
                        </div>

                    </form>
                    <a class="layui-btn pull-right" href="javascript:" onclick="short_mod('0')">上传广告</a>
                    <div class="layui-row layui-col-space17">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>头像</th>
                                <th>标题</th>
                                <th>广告有效时间-开始/结束</th>
                                <th>广告投放渠道/跳转链接</th>
                                <th>播放量</th>
                                <th>评论量</th>
                                <th>点赞量</th>
                                <th>分享量</th>
                                <th>点击量</th>
                                <th>状态</th>
                                <th style="width:300px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <foreach name="list" item="l">
                                <tr>
                                    <td style="text-align: center"><input class="checkbox i-checks" type="checkbox"
                                                                          id="allid[]" value="{$l['id']}"></td>
                                    <td>{$l['id']}</td>
                                    <td>
                                        <img src="{$l['avatar']}" height="40px">
                                    </td>
                                    <td>{$l['title']}</td>
                                    <td>{$l['start_time']}~{$l['end_time']}</td>
                                    <td>{$l['channel_link']}</td>
                                    <td>{$l['play_num']}</td>
                                    <td>{$l['comment_num']}</td>
                                    <td>{$l['praise_num']}</td>
                                    <td>{$l['forward_num']}</td>
                                    <td>{$l['click_num']}</td>
                                    <td>
                                        <?php
                                        echo $l['is_status'] ? '上架中' : '已下架';
                                        ?>
                                    </td>
                                    <td>
                                        <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;"
                                           onclick="short_mod('{$l.id}')">编辑</a>
                                        <a class="layui-btn layui-btn-primary layui-btn-xs" href="javascript:;"
                                           onclick="del('{$l.id}');">删除</a>

                                    </td>
                                </tr>

                            </foreach>

                            <tr>
                                <td colspan="14">
                                    <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                    <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                    <input type="button" class="layui-btn pull-left" id="reverse" value="反选">
                                    <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
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
        //反选
        $("#reverse").click(function () {
            $("input:checkbox").each(function () {
                this.checked = !this.checked;
            });
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
                    title: "确定删除这些商品吗？",
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
                        url: "__CONTROLLER__/batchdel",
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
                swal({title: "", text: "请选择需要删除的广告！"})
                return false;
            }
        });

    });
    // 删除事件
    function del(id) {
        if (id != '') {
            swal({
                    title: "确定要删除该广告吗？删除后将无法恢复！！！",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: "POST",
                        url: '__CONTROLLER__/del',
                        dataType: "html",
                        data: "id=" + id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({title: "删除成功！", text: "", type: "success"}, function () {
                                    location.reload();
                                })
                            } else {
                                swal({title: "操作失败！", text: "", type: "error"}, function () {
                                    location.reload();
                                })
                            }
                        }
                    });
                })
        }
    }

    // 预览视频
    function preview_short(v_url) {
        layer.open({
            type: 1,
            title: false,                   //  不显示标题栏
            closeBtn: false,
            shadeClose: true,
            offset: 'auto',
            id: 'preview',
            area: ['50%', '660px'],
            shade: 0.8,
            btnAlign: 'c',
            moveType: 1, //拖拽模式，0或者1
            content: '<div style="background-color: rgb(0, 0, 0);">' +
                '<video controls="" autoplay="" name="media" style="width: 372px!important;margin: 0 auto;display: block" >' +
                '<source src="' + v_url + '" type="video/mp4">' +
                '</video>' +
                '</div>',
        });
    }

    // 添加/编辑
    function short_mod(id) {
        layer.open({
            type: 2,
            content: '__CONTROLLER__/mod/id/' + id,
            title: '上传/编辑广告',
            area: ['75%', '700px'],
            btn: ['立即提交', '取消'],
            yes: function (index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index],
                    submitID = 'LAY-user-back-submit',
                    submit = layero.find('iframe').contents().find('#' + submitID);
                submitID = 'LAY-user-front-submit';

                // 监听提交
                iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
                    var field = data.field;     // 获取提交的字段
                    // 请求提交
                    $.ajax({
                        url: '__CONTROLLER__/mod',
                        type: 'post',
                        data: field,
                        success: function (res) {
                            res = JSON.parse(res);
                            if (res.code == 'succ') {
                                layer.closeAll();           // 关闭弹层
                                swal({title: res.msg, text: "", type: "success"}, function () {
                                    location.reload();
                                });
                            } else {
                                swal({title: res.msg, text: "", type: "error"});
                            }
                        }
                    });

                    return false;               // 禁止跳转，否则会提交两次，且页面会刷新
                });

                // 触发提交
                submit.trigger('click');
            }
        });
    }

    // 查看评论
    function look_comm(url) {
        layer.open({
            type: 2,
            content: url,
            title: '查看评论',
            area: ['90%', '90%']
        });
    }

    // 商品管理
    function good_mange(url) {
        layer.open({
            type: 2,
            content: url,
            title: '商品管理',
            area: ['90%', '90%']
        });
    }

</script>
</body>
</html>