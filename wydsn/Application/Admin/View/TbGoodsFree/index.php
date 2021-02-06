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


    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

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
                        title:"确定删除这些商品吗？",
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
                            url:"/taokeyun.php/TbGoodsFree/batchdel",
                            dataType:"html",
                            data:"all_id="+all_id,
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
                    swal({title:"",text:"请选择需要删除的商品！"})
                    return false;
                }
            });

        });

        function del(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该商品吗？",
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
                        url:"/taokeyun.php/TbGoodsFree/del",
                        dataType:"html",
                        data:"id="+id,
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
                <div class="ibox-title">
                    <h3>当前位置： 营销中心  &raquo; 淘宝0元购商品管理</h3>
                </div>
                <div class="ibox-content">
                    <h3><strong style="color:red;">补贴金额=商品价格-优惠券价格-用户所得佣金</strong></h3>
                    <h3><strong style="color:red;">用户所得佣金=（商品价格-优惠券价格）X佣金比率X用户所得佣金比率</strong></h3>
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <!-- 商品名称：<input type="text" placeholder="" name="goods_name" class="form-control"> -->
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:60px'>验证手机</label>
                            <div class="layui-input-inline">
                                <input type="text" placeholder="" name="goods_name" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                        </div>
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加淘宝商品</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>商品ID</th>
                                    <th>商品主图</th>
                                    <th>淘宝商品名称</th>
                                    <th>商品价格</th>
                                    <th>佣金比率</th>
                                    <th>优惠券金额</th>
                                    <th>销量</th>
                                    <th>补贴金额</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($list as $l) {
                                    echo '<tr>
                                                <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="'.$l['id'].'"></td>
			                                    <td>'.$l['goods_id'].'</td>
                                                <td><img src="'.$l['pict_url'].'" height="50px"></td>
			                                    <td>'.$l['goods_name'].'</td>
                                                <td>'.$l['zk_final_price'].'元</td>
			                                    <td>'.$l['commission_rate'].'%</td>
			                                    <td>'.$l['coupon_amount'].'元</td>
                                                <td>'.$l['volume'].'</td>
                                                <td><font color="red">'.$l['subsidy_amount'].'元</font></td>
                                                <td class="has-warning"><input name="sort['.$l['id'].']" value="'.$l['sort'].'" class="form-control" style="width:50px;text-align:center"/></td>
			                                    <td>
		                                          <a href="__CONTROLLER__/edit/id/'.$l['id'].'" title="修改">
			                                         <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
      		                                      </a>
		                                          <a href="javascript:;" onclick="del('.$l['id'].');" title="删除">
			                                         <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
		                                          </a>
		                                      </td>
       		                               </tr>';
                                }
                                ?>
                                <tr>
                                    <td colspan="11">
                                        <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                        <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                        <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
                                        <input type="submit" class="layui-btn pull-left" value="统一排序">
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