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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

    <script type="text/javascript">
        function changestatus(uid,status)
        {
            if(uid!='')
            {
                $.ajax({
                    type:"POST",
                    url:"/taokeyun.php/User/changestatus",
                    dataType:"html",
                    data:"uid="+uid+"&status="+status,
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

        function del(uid)
        {
            if(uid!='') {
                swal({
                    title:"确定要删除该用户吗？删除后将无法恢复！！！",
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
                        url:'/taokeyun.php/User/del',
                        dataType:"html",
                        data:"uid="+uid,
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

        function changesphone()
        {
            $.ajax({
                type:"POST",
                url:"/taokeyun.php/User/changePhone",
                dataType:"html",
                success:function(msg)
                {
                    if(msg>0)
                    {
                        swal({
                            title:"更新成功！",
                            text:"",
                            type:"success"
                        },function(){location.reload();})
                    }else {
                        swal({
                            title:"无需更新！",
                            text:"",
                            type:"error"
                        },function(){location.reload();})
                    }
                }
            });
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 会员列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left layui-form">
                        <input type="hidden" name="p" value="1">
                        <input type="hidden" name="group_id" value="{$group_id}">
                        <!-- 用户名/邮箱、手机号码：<input type="text" placeholder="" name="search" class="form-control" style="width:100px">
                        备注姓名：<input type="text" placeholder="" name="remark" class="form-control" style="width:80px">
                        城市：<input type="text" placeholder="" name="city" class="form-control" style="width:80px">
                        会员组：<select class="form-control" name="group_id">
                            <option value="">请选择会员组</option>
                            <?php
                            foreach ($glist as $l) {
                                echo '<option value="'.$l['id'].'">'.$l['title'].'</option>';
                            }
                            ?>
                        </select>
                        推荐人手机：<input type="text" placeholder="" name="referrer_phone" class="form-control" style="width:80px">
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button> -->
                        <div class="layui-inline">
                            <label class="layui-form-label" style='width:150px'>用户名/邮箱/手机号码</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="search" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">备注</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="remark" class="layui-input" >
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">城市</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="city" class="layui-input" >
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 50px">会员组</label>
                            <div class="layui-input-inline">
                            <select class="form-control" name="group_id">
                                <option value="">请选择会员组</option>
                                <?php
                                foreach ($glist as $l) {
                                    echo '<option value="'.$l['id'].'">'.$l['title'].'</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">推荐人手机</label>
                            <div class="layui-input-inline" style="width: 100px">
                                <input type="text" placeholder="" name="referrer_phone" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>
                            </div>
                        </div>
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add/group_id/{$group_id}">添加会员</a>
                    <a class="layui-btn pull-right"  style="margin-right: 2px" href="javascript:;" onclick="changesphone();" title="更新无归属地">更新无归属地</a>
                    <div class="layui-row layui-col-space17">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>所属分组</th>
                                <th>是否冻结</th>
                                <th>头像</th>
                                <th>手机号码</th>
                                <th>姓名</th>
                                <th>备注</th>
                                <th>查看余额</th>
                                <th>可提现</th>
                                <th>本月预估</th>
                                <th>本月结算</th>
                                <th>推荐人数</th>
                                <th>推广位</th>
                                <th>详情</th>
                                <th>上级/推荐</th>
                                <th>注册时间</th>
                                <th>手机归属地</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $group=new \Common\Model\UserGroupModel();
                            $User=new \Common\Model\UserModel();
                            $UserDetail=new \Common\Model\UserDetailModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['uid']}</td>
                                    <td>
                                        <?php
                                        //会员组名称
                                        $groupMsg=$group->getGroupMsg($l['group_id']);
                                        echo $groupMsg['title'];
                                        ?>
                                    </td>
                                    <td>
                                        <if condition='$l[is_freeze] eq N'>
                                            <button type="button" class="layui-btn layui-btn-xs" onclick="changestatus({$l.uid},'Y');">正常</button>
                                            <else/>
                                            <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changestatus({$l.uid},'N');">冻结</button>
                                        </if>
                                    </td>
                                    <td>
                                        <?php
                                        $detailMsg=$UserDetail->getUserDetailMsg($l['uid']);
                                        echo '<img src="'.$detailMsg['avatar'].'" height="40px">';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        //隐藏手机号码
                                        $phone=hide_phone($l['phone'], 4, 4);
                                        if($l['is_buy']=='Y')
                                        {
                                            echo '<font color="red">'.$phone.'</font>';
                                        }else {
                                            echo $phone;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $detailMsg=$UserDetail->getUserDetailMsg($l['uid']);
                                        echo $detailMsg['truename'];
                                        ?>
                                    </td>
                                    <td>{$l['remark']}</td>
                                    <td>￥{$l['balance']}<a href="__MODULE__/UserBalanceRecord/index/user_id/{$l['uid']}/group_id/{$group_id}" style="color: red">查看</a></td>
                                    <?php
                                    $res_balance=$User->getDrawBalance($l['uid']);
                                    ?>
                                    <td>￥{$res_balance.draw_balance}</td>
                                    <td>￥{$res_balance.amount_current}</td>
                                    <td>￥{$res_balance.amount_finish}</td>
                                    <td>
                                        <?php
                                        $uid=$l['uid'];
                                        $referrer_num=$User->where("referrer_id='$uid'")->count();
                                        echo '<a href="__ACTION__/referrer_phone/'.$l['phone'].'">'.$referrer_num.'</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($l['tb_pid_master'])
                                        {
                                            echo '1';
                                        }else {
                                            echo '0';
                                        }
                                        ?>
                                    </td>
                                    <td><a href="__MODULE__/UserDetail/index/uid/{$l['uid']}/group_id/{$group_id}" style="color: red">详情</a></td>
                                    <td>
                                        <?php
                                        if($l['referrer_id'])
                                        {
                                            $referrerMsg=$User->getUserDetail($l['referrer_id']);
                                            if($referrerMsg['remark'])
                                            {
                                                echo $referrerMsg['remark'];
                                            }else {
                                                //隐藏手机号码
                                                if(is_phone($referrerMsg['account'])){
                                                    echo hide_phone($referrerMsg['account'], 4, 4);
                                                }else {
                                                    echo $referrerMsg['account'];
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>{$l['register_time']}</td>
                                    <td>{$l['phone_province']}-{$l['phone_city']}</td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/uid/{$l.uid}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$l.uid});" title="删除">
                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            </foreach>
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

</body>
</html>