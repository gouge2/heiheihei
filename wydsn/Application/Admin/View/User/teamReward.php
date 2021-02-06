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

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：会员管理 &raquo; 会员团队分红设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        
                        <form action="__ACTION__"  class="form-horizontal " method="post" enctype="multipart/form-data">
<!--                            <input type="hidden" name="old_is_team_reward" value="{$msg['is_team_reward']}">-->
                        <h3><strong style="color:red;">友情提示：团队分红配置为除上级和上上级推荐人外，直系团队VIP上级获得分佣比例！</strong></h3><br>
                        <h3><strong style="color:red;">友情提示：如果不进行团队分红则值都填0即可！</strong></h3><br>
                            <input type="hidden" name="old_team_reward1" value="{$msg['team_reward1']}">
                            <input type="hidden" name="old_team_reward2" value="{$msg['team_reward2']}">
                            <input type="hidden" name="old_team_reward1_virtual" value="{$msg['team_reward1_virtual']}">
                            <input type="hidden" name="old_team_reward2_virtual" value="{$msg['team_reward2_virtual']}">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">团队一级分红</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="team_reward1" value="{$msg['team_reward1']}" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">团队一级分红提成，请填写整数，5代表5%</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">团队二级分红</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="team_reward2" value="{$msg['team_reward2']}" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">团队二级分红提成，请填写整数，3代表3%</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">团队一级分红-虚拟</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="team_reward1_virtual" value="{$msg['team_reward1_virtual']}" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">该参数用于提升app虚拟数据，不参与真实计算。请填写整数，5代表5%，不提升则填0</span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 180px;">团队二级分红-虚拟</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="team_reward2_virtual" value="{$msg['team_reward2_virtual']}" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux">该参数用于提升app虚拟数据，不参与真实计算。请填写整数，3代表3%，不提升则填0</span>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
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
</body>
</html>