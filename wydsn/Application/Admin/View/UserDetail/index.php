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
<script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
<script>
$(document).ready(function(){
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
                    <?php
                        if (empty($group_id)){
                            $group_id=0;
                        }
                    ?>
						<h3>当前位置：会员管理 &raquo; 会员列表 &raquo; 编辑会员详情<a class="layui-btn pull-right" href="__MODULE__/User/index/group_id/{$group_id}" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/uid/{$msg.user_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">昵称</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="nickname" value="{$msg['nickname']}" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">头像</label>
                                 <div class="layui-input-block">
                                    <?php 
                                    if(!empty($msg['avatar'])) {
                                        echo '<img src="'.$msg['avatar'].'" height="100px">';
                                    }else {
                                        echo '暂无';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传新头像</label>
                                 <div class="layui-input-block">
                                    <input type="file" name="img" accept="image/*" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">真实姓名</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="truename" value="{$msg['truename']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">性别</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                        	<input type="radio" name="sex" value="1" <?php if($msg['sex']=='1'){echo 'checked';} ?> /> <i></i>男
                                        	<input type="radio" name="sex" value="2" <?php if($msg['sex']=='2'){echo 'checked';} ?> /> <i></i>女
                                        	<input type="radio" name="sex" value="3" <?php if($msg['sex']=='3'){echo 'checked';} ?> /> <i></i>保密
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">身高</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="height" value="{$msg['height']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">体重</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="weight" value="{$msg['weight']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">血型</label>
                                 <div class="layui-input-block">
                                    <select class="layui-input m-b" name="blood">
           								<option value="">请选择血型</option>
           								<option value="1" <?php if($msg['blood']=='1'){echo 'selected';}?> >A型</option>
           								<option value="2" <?php if($msg['blood']=='2'){echo 'selected';}?> >B型</option>
           								<option value="3" <?php if($msg['blood']=='3'){echo 'selected';}?> >AB型</option>
           								<option value="4" <?php if($msg['blood']=='4'){echo 'selected';}?> >0型</option>
           								<option value="5" <?php if($msg['blood']=='5'){echo 'selected';}?> >其它</option>
        	 						</select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">出生日期</label>
                                 <div class="layui-input-block">
                                    <input class="layui-input layer-date" name="birthday" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                                    <span class="layui-form-mid layui-word-aux">格式：1990-09-19</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">QQ</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="qq" value="{$msg['qq']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">微信</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="weixin" value="{$msg['weixin']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">详细地址</label>
                                 <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="detail_address" value="{$msg['detail_address']}"> 
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">个性签名</label>
                                 <div class="layui-input-block">
                                    <textarea name="signature" placeholder="" class="layui-input" style="height:100px;">{$msg.signature}</textarea> 
                                </div>
                            </div>
<!--                            <div class="layui-form-item">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;确认修改</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;确认修改</button>
                                    <button class="layui-btn" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
</body>
</html>