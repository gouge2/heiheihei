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
        $(document).ready(function(){
            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 系统文章设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <h3><strong style="color:red;">友情提示：以下文章分类ID和文章ID为app使用必须，请在文章管理处保证以下分类和文章存在。如果删除了，请在重新添加后保存相应的ID！</strong></h3><br>
                        <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_system_article" value="{$msg['system_article']}">
                            <input type="hidden" name="old_common_problem" value="{$msg['common_problem']}">
                            <input type="hidden" name="old_novice_tutorial" value="{$msg['novice_tutorial']}">
                            <input type="hidden" name="old_official_announcement" value="{$msg['official_announcement']}">
                            <input type="hidden" name="old_college" value="{$msg['college']}">
                            <input type="hidden" name="old_agreement_privacy" value="{$msg['agreement_privacy']}">
                            <input type="hidden" name="old_agreement" value="{$msg['agreement']}">
                            <input type="hidden" name="old_privacy" value="{$msg['privacy']}">
                            <input type="hidden" name="old_pull_new_activities" value="{$msg['pull_new_activities']}">
                            <input type="hidden" name="old_about_us" value="{$msg['about_us']}">
                            <input type="hidden" name="old_withdrawal_rules" value="{$msg['withdrawal_rules']}">
                            <input type="hidden" name="old_zero_buy" value="{$msg['zero_buy']}">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;系统文章(分类ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="system_article" value="{$msg['system_article']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;常见问题(分类ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="common_problem" value="{$msg['common_problem']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;新手教程(分类ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="novice_tutorial" value="{$msg['novice_tutorial']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;官方公告(分类ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="official_announcement" value="{$msg['official_announcement']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;商学院(分类ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="college" value="{$msg['college']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <span class="layui-form-mid layui-word-aux">
                                    --------------------------系统文章分类下文章ID
                                </span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;用户协议和隐私条款(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="agreement_privacy" value="{$msg['agreement_privacy']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;用户协议(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="agreement" value="{$msg['agreement']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;隐私条款(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="privacy" value="{$msg['privacy']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;拉新活动规则(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="pull_new_activities" value="{$msg['pull_new_activities']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;关于我们(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="about_us" value="{$msg['about_us']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;提现规则(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="withdrawal_rules" value="{$msg['withdrawal_rules']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 250px;"><strong style="color:red;">*</strong>&nbsp;0元购(文章ID)</label>
                                <div class="layui-input-inline" style="width: 40%;">
                                    <input type="text" class="layui-input" name="zero_buy" value="{$msg['zero_buy']}" placeholder="" style="width: 80%;">
                                </div>
                                <span class="layui-form-mid layui-word-aux"></span>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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