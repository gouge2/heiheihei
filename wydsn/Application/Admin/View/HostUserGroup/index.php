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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <script type="text/javascript">
        function changestatus(id,status)
        {
            if(id!='')
            {
                $.ajax({
                    type:"POST",
                    url:"/dmooo.php/UserGroup/changestatus",
                    dataType:"html",
                    data:"id="+id+"&status="+status,
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
                    title:"确定要删除该分组吗？",
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
                        url:"/dmooo.php/HostUserGroup/del",
                        dataType:"html",
                        data:"id="+id,
                        success:function(msg)
                        {
                            if(msg=='2')
                            {
                                swal({
                                    title:"该会员组下存在会员，不准直接删除会员组！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            }
                            if(msg=='1')
                            {
                                swal({
                                    title:"删除成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }
                            if(msg=='0'){
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
                    <h3>当前位置： 会员管理  &raquo; 直播带货佣金管理</h3>
                </div>
                <div class="ibox-content">
                    <!--<div style="display: flow-root;">
                        <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加会员组</a>
                    </div>-->
                    <strong>初始佣金设置</strong>
                    <hr/>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/update"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">第三方扣税</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="fee_service" value="{$hostCommission.fee_service}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议20%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">平台自留</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="fee_plantform" value="{$hostCommission.fee_plantform}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议5~10%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">直播带货</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="fee_sell" value="{$hostCommission.fee_sell}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议70%</span>
                                </div>
                            </div>
                            <button class="layui-btn pull-right" type="submit">修改</button>
                        </form>
                    </div>
                    <strong>直播分润设置</strong>
                    <hr/>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/update"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">直接经纪人</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="broker_rate" value="{$hostCommission.broker_rate}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议13%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">间接经纪人</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="broker_rate2" value="{$hostCommission.broker_rate2}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议7%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">直播佣金</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="fee_host" value="{$hostCommission.fee_host}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议60%</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">购买者体系</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" name="fee_user" value="{$hostCommission.fee_user}">
                                </div>
                                <div class="col-sm-2">
                                    <span class="help-block m-b-none text-danger">建议20%</span>
                                </div>
                            </div>
                            <button class="layui-btn pull-right" type="submit">修改</button>
                        </form>
                    </div>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>会员组名</th>
                                <th>自购比例</th>
                                <th>直推</th>
                                <th>间推</th>
                                <th>团队一级</th>
                                <th>团队二级</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $user=new \Common\Model\UserModel();
                            ?>
                            <foreach name="glist" item="g">
                                <tr>
                                    <td>{$g['id']}</td>
                                    <td>{$g['title']}</td>
                                    <td>{$g['fee_user']}%</td>
                                    <td>{$g['referrer_rate']}%</td>
                                    <td>{$g['referrer_rate2']}%</td>
                                    <td>{$g['team_rate']}%</td>
                                    <td>{$g['team_rate2']}%</td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/group_id/{$g.id}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$g.id});" title="删除">
                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>