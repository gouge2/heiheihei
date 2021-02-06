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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".i-checks").iCheck({
                checkboxClass:"icheckbox_square-green",
                radioClass:"iradio_square-green",
            });

            //取消全选
            $('#unselect').click(function(){
                $("input:checkbox").removeAttr("checked");
                $(".i-checks").iCheck({
                    checkboxClass:"icheckbox_square-green",
                    radioClass:"iradio_square-green",
                });
            });
            //全选
            $('#selectall').click(function(){
                $("input:checkbox").prop("checked","checked");
                $(".i-checks").iCheck({
                    checkboxClass:"icheckbox_square-green",
                    radioClass:"iradio_square-green",
                });
            });

            //批量删除
            $('#batchdel').click(function(){
                var all_id='';
                $(":checkbox").each(function(){
                    if($(this).prop("checked"))
                    {
                        all_id+=$(this).val()+',';
                    }
                });
                if(all_id!='') {
                    swal({
                        title:"确定删除这些广告图吗？",
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
                            url:"/taokeyun.php/Banner/batchdel",
                            dataType:"html",
                            data:"all_id="+all_id+"&cat_id="+ <?php echo $cat_id;?>,
                            success:function(msg)
                            {
                                if(msg=='1')
                                {
                                    swal({
                                        title:"批量删除成功！",
                                        text:"",
                                        type:"success"
                                    },function(){location.reload();})
                                }else {
                                    swal({
                                        title:"操作失败！",
                                        text:"",
                                        type:"error"
                                    },function(){location.reload();})
                                }
                            }
                        });
                    })
                }else {
                    swal({title:"",text:"请选择需要删除的广告图！"})
                    return false;
                }
            });

        });

        function changeshow(id,status)
        {
            if(id!='')
            {
                $.ajax({
                    type:"POST",
                    url:"/taokeyun.php/Banner/changeshow",
                    dataType:"html",
                    data:"id="+id+"&status="+status+"&cat_id="+ <?php echo $cat_id;?>,
                    success:function(msg)
                    {
                        if(msg=='1')
                        {
                            swal({
                                title:"修改状态成功！",
                                text:"",
                                type:"success"
                            },function(){location.reload();})
                        }else {
                            swal({
                                title:"修改状态失败！",
                                text:"",
                                type:"error"
                            },function(){location.reload();})
                        }
                    }
                });
            }
        }

        function del(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该广告图吗？",
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
                        url:"/taokeyun.php/Banner/del",
                        dataType:"html",
                        data:"id="+id+"&cat_id="+ <?php echo $cat_id;?>,
                        success:function(msg)
                        {
                            if(msg=='1')
                            {
                                swal({
                                    title:"删除成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }else {
                                swal({
                                    title:"操作失败！",
                                    text:"",
                                    type:"error"
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
                    <h3>当前位置： 内容管理 &raquo; Banner/广告图列表 &raquo; {$cat_title}<a class="layui-btn pull-right" href="__MODULE__/BannerCat/index" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <?php
                        $add_link   = ($applet ? 'appletEdit/id/0' : 'add') .'/cat_id/'. $cat_id;
                    ?>

                    <a class="layui-btn pull-right" href="__CONTROLLER__/{$add_link}">添加Banner/广告图</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>图片</th>
                                    <?php
                                        if (!$applet) {
                                            echo '<th>超链接</th><th>类型</th><th>类型值</th>';
                                        }
                                    ?>
                                    <th>排序</th>
                                    <th>是否显示</th>
                                    <th>发布时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $Banner=new \Common\Model\BannerModel();
                                ?>
                                <foreach name="hlist" item="l">
                                    <tr>
                                        <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$l['id']}"></td>
                                        <td>{$l['id']}</td>
                                        <td>{$l['title']}</td>
                                        <td>
                                            <?php
                                            if($l['img']!='') {
                                                echo '<img src="'.$l['img'].'" height="60px" width="100px">';
                                            }
                                            ?>
                                        </td>
                                        <?php
                                            if (!$applet) {
                                                echo '<td>';
                                                if ($l['href']) {
                                                    $href   = $l['href'];
                                                    echo '<a target="_blank" href="'.$href.'">查看链接</a>';
                                                }
                                                echo '</td>';
                                                echo '<td>'. $Banner->getTypeZh($l['type']) .'</td>';
                                                echo '<td>'. $l['type_value'] .'</td>';
                                            }
                                        ?>
                                        <td class="has-warning"><input name="sort[{$l.id}]" value="{$l.sort}" class="form-control" style="width:50px;text-align:center"/></td>
                                        <td>
                                            <if condition='$l.is_show eq Y'>
                                                <button type="button" class="layui-btn layui-btn-xs" onclick="changeshow({$l.id},'N');">显示</button>
                                                <else/>
                                                <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changeshow({$l.id},'Y');">隐藏</button>
                                            </if>
                                        </td>
                                        <td>{$l['createtime']}</td>
                                        <td>
                                            <?php
                                                $edit       = $applet ? 'appletEdit' : 'edit';
                                            ?>
                                            <a href="__CONTROLLER__/{$edit}/id/{$l.id}/cat_id/{$cat_id}" title="修改/编辑留言">
                                                <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                            <a href="javascript:;" onclick="del({$l.id});" title="删除">
                                                <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                        </td>
                                    </tr>
                                </foreach>
                                <tr>
                                    <td colspan="11">
                                        <input type="submit" class="layui-btn pull-left" value="统一排序">
                                        <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                        <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                        <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
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