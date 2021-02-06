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
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：会员管理 &raquo; 会员组管理 &raquo; 编辑会员组<a class="layui-btn pull-right" href="__CONTROLLER__/index" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/group_id/{$msg['id']}"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">会员组名</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="title" value="{$msg['title']}" placeholder="" style="width: 94%;">
                                    <div style="color: red">{$error1}</div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">等级图标</label>
                                <div class="layui-input-block">
                                    <img src="{$msg['level_icon']}" width="100px">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">上传新图标</label>
                                <div class="layui-input-block">
                                    <input type="file" class="layui-input" name="level_icon" accept="image/*" value="" style="width: 94%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">等级必要经验</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="exp" value="{$msg['exp']}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>从下一级会员组升级为本级会员组所需的最低经验，请填写正整数</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">等级必要佣金</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="commission" value="{$msg['commission']}" placeholder="" style="width: 94%">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>
                                    从下一级会员组升级为本级会员组所需的最低佣金，0为该等级不需要佣金限制。精确到分，如：8.88，单位元<br>
                                    佣金为淘京拼唯等平台购买商品已结算订单返利的佣金总和，不限制于自身返利
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否礼包升级</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_gift"  value="Y" <?php if($msg['is_gift']=='Y') {echo 'checked';} ?> title='开启'>
                                    <input type="radio" name="is_gift"  value="N" <?php if($msg['is_gift']=='N') {echo 'checked';} ?> title='关闭'>
                                </div>
                                <div class='layui-form-mid layui-word-aux'>通过购买购买自营商城商品礼包升级为本级会员组</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">礼包一级提成比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="gift_referrer_tate" value="{$msg.gift_referrer_tate}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>会员购买礼包上级提成，请填写整数，5代表5%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">礼包二级提成比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="gift_referrer_tate2" value="{$msg.gift_referrer_tate2}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>会员购买礼包上上级提成，请填写整数，3代表3%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">等级期限</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="time_limit" value="{$msg.time_limit}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>上升到本级会员组过期时间，0为永久，其他期限请填写实际天数，填写整数</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">享受的折扣率</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="discount" value="{$msg.discount}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>自营商城商品原价基础上打折，如：0.95代表95折</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">收益比例-用户</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_user" value="{$msg.fee_user}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>请填写整数，60代表60%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">收益虚拟比例-用户</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_user_virtual" value="{$msg.fee_user_virtual}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>该参数用于提升app虚拟数据，不参与真实计算。请填写整数，10代表10%，不提升则填0</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">收益比例-扣税</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_service" value="{$msg.fee_service}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>请填写整数，10代表10%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">收益比例-平台</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_plantform" value="{$msg.fee_plantform}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>请填写整数，30代表30%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">直推佣金比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate" value="{$msg.referrer_rate}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>请填写整数，30代表30%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">直推虚拟佣金比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate_virtual" value="{$msg.referrer_rate_virtual}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>该参数用于提升app虚拟数据，不参与真实计算。请填写整数，10代表10%，不提升则填0</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">间推佣金比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate2" value="{$msg.referrer_rate2}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>请填写整数，30代表30%</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">间推虚拟佣金比例</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate2_virtual" value="{$msg.referrer_rate2_virtual}" placeholder="" style="width: 94%;">
                                </div>
                                <div class='layui-form-mid layui-word-aux'>该参数用于提升app虚拟数据，不参与真实计算。请填写整数，10代表10%，不提升则填0</div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">组别描述</label>
                                <div class="layui-input-block">
                                    <textarea name="introduce" placeholder="" class="layui-input" style="height:100px;width: 94%;">{$msg['introduce']}</textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否冻结</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_freeze" value="N" <?php if($msg['is_freeze']=='N') {echo 'checked';} ?> title='正常使用'>
                                    <input type="radio" name="is_freeze" value="Y" <?php if($msg['is_freeze']=='Y') {echo 'checked';} ?> title='冻结'>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block" style="width: 94%">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑会员组</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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
</body>
</html>