<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-10">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 站点设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">APP配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">网站配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-3" aria-expanded="false">其他配置</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal layui-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="old_app_name" value="{$msg['app_name']}">
                                <input type="hidden" name="old_version_ios" value="{$msg['version_ios']}">
                                <input type="hidden" name="old_version_android" value="{$msg['version_android']}">
                                <input type="hidden" name="old_down_ios" value="{$msg['down_ios']}">
                                <input type="hidden" name="old_down_android" value="{$msg['down_android']}">
                                <input type="hidden" name="old_update_content_ios" value="{$msg['update_content_ios']}">
                                <input type="hidden" name="old_update_content_android" value="{$msg['update_content_android']}">

                                <input type="hidden" name="old_platform_wx" value="{$msg['platform_wx']}">
                                <input type="hidden" name="old_share_url" value="{$msg['share_url']}">
                                <input type="hidden" name="old_share_url_register" value="{$msg['share_url_register']}">
                                <input type="hidden" name="old_share_url_vip" value="{$msg['share_url_vip']}">

                                <input type="hidden" name="old_web_url" value="{$msg['web_url']}">
                                <input type="hidden" name="old_web_record_number" value="{$msg['web_record_number']}">
                                <input type="hidden" name="old_web_title" value="{$msg['web_title']}">
                                <input type="hidden" name="old_keywords" value="{$msg['keywords']}">
                                <input type="hidden" name="old_description" value="{$msg['description']}">
                                <input type="hidden" name="old_copyright" value="{$msg['copyright']}">
                                <input type="hidden" name="old_web_title_en" value="{$msg['web_title_en']}">
                                <input type="hidden" name="old_keywords_en" value="{$msg['keywords_en']}">
                                <input type="hidden" name="old_description_en" value="{$msg['description_en']}">
                                <input type="hidden" name="old_copyright_en" value="{$msg['copyright_en']}">

                                <input type="hidden" name="old_to_update" value="{$msg['to_update']}">
                                <input type="hidden" name="old_to_update_ios" value="{$msg['to_update_ios']}">

                                <input type="hidden" name="old_pay_method" value="{$msg['pay_methods']}">

                                <div class="tab-content">
                                    <!-- APP配置  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">App名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="app_name" value="{$msg['app_name']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">苹果版本号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="version_ios" value="{$msg['version_ios']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">ios是否显示更新</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="to_update_ios" value="Y" <?php if($msg['to_update_ios']=='Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="to_update_ios" value="N" <?php if($msg['to_update_ios']=='N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">是否开启分销</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="is_distribution" value="Y" <?php if($msg['is_distribution']=='Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="is_distribution" value="N" <?php if($msg['is_distribution']=='N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">安卓版本号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="version_android" value="{$msg['version_android']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">android是否显示更新</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="to_update" value="Y" <?php if($msg['to_update']=='Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="to_update" value="N" <?php if($msg['to_update']=='N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">苹果下载地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="down_ios" value="{$msg['down_ios']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">安卓下载地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="down_android" value="{$msg['down_android']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">上传APK包</label>
                                            <div class="layui-input-block">
                                                <input type="file" class="layui-input" name="apk" accept=".apk" value="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">苹果新版本更新内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="update_content_ios" placeholder="" class="layui-input" style="width: 90%;">{$msg['update_content_ios']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">安卓新版本更新内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="update_content_android" placeholder="" class="layui-input" style="width: 90%;">{$msg['update_content_android']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">平台微信号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="platform_wx" value="{$msg['platform_wx']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">分享淘宝商品网址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="share_url" value="{$msg['share_url']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">分享注册下载网址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="share_url_register" value="{$msg['share_url_register']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">VIP专用分享网址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="share_url_vip" value="{$msg['share_url_vip']}" placeholder="" style="width: 90%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">平台邀请码名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="platform_invite_cn" value="{$msg['platform_invite_cn']}" placeholder="" style="width: 20%;">
                                                <span class="layui-form-mid layui-word-aux">参考“来鹿号”</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">支付方式</label>
                                            <div class="layui-input-block">
                                                <?php if(!empty($msg['pay_methods'])) $msg['pay_method'] = explode(",",$msg['pay_methods']); ?>
                                                <input type="checkbox" name="pay_method[banlance]" title="余额支付" <?php if(in_array('banlance',$msg['pay_method'])) echo 'checked'; ?>>
                                                <input type="checkbox" name="pay_method[alipay]" title="支付宝" <?php if(in_array('alipay',$msg['pay_method'])) echo 'checked'; ?>>
                                                <input type="checkbox" name="pay_method[wxpay]" title="微信" <?php if(in_array('wxpay',$msg['pay_method'])) echo 'checked'; ?>>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">佣金播报</label>
                                            <div class="layui-input-block">
                                                <input type="radio" name="commission_broadcast" value="Y" <?php if($msg['commission_broadcast']=='Y') echo 'checked'; ?> title="是">
                                                <input type="radio" name="commission_broadcast" value="N" <?php if($msg['commission_broadcast']=='N') echo 'checked'; ?> title="否">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- APP配置  -->

                                    <!-- 网站配置  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">网站标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_title" value="{$msg['web_title']}" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">网站logo</label>
                                            <div class="layui-input-block">
                                                <img src="__ADMIN_IMG__/logo.png" width="72">
                                            </div>
                                            <span class="layui-form-mid layui-word-aux">格式要求PNG，文件大小72x72px</span>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">上传新logo</label>
                                            <div class="layui-input-block">
                                                <input type="file" class="layui-input" name="logo" accept="image/*" value="" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">英文网站标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_title_en" value="{$msg['web_title_en']}" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">官网网址/IP地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_url" value="{$msg['web_url']}" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">网站备案号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_record_number" value="{$msg['web_record_number']}" style="width: 90%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">SEO关键字(keyword)</label>
                                            <div class="layui-input-block">
                                                <textarea name="keywords" placeholder="" class="layui-input" style="width: 90%;">{$msg['keywords']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">SEO描述(description)</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="width: 90%;">{$msg['description']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">SEO版权(copyright)</label>
                                            <div class="layui-input-block">
                                                <textarea name="copyright" placeholder="" class="layui-input" style="width: 90%;">{$msg['copyright']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">英文SEO关键字(keyword)</label>
                                            <div class="layui-input-block">
                                                <textarea name="keywords_en" placeholder="" class="layui-input" style="width: 90%;">{$msg['keywords_en']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">英文SEO描述(description)</label>
                                            <div class="layui-input-block">
                                                <textarea name="description_en" placeholder="" class="layui-input" style="width: 90%;">{$msg['description_en']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 10%;">英文SEO版权(copyright)</label>
                                            <div class="layui-input-block">
                                                <textarea name="copyright_en" placeholder="" class="layui-input" style="width: 90%;">{$msg['copyright_en']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 网站配置  -->

                                    <!-- 其他配置  -->
                                    <div id="tab-3" class="tab-pane" style="padding-top: 10px">

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原直播间ID背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['live_romm_logo']) { echo '<img src="'. $msg['live_romm_logo'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传新直播间ID背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="live_romm_logo" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸144*60像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原分享小程序背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['share_applet_bg']) { echo '<img src="'. $msg['share_applet_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传分享小程序背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="share_applet_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸670*920像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原提示类弹窗背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['tips_bg']) { echo '<img src="'. $msg['tips_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传提示类弹窗背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="tips_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸600*306像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原登录界面logo图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['login_bg']) { echo '<img src="'. $msg['login_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传登录界面logo图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="login_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸314*128像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原主播与用户互动弹窗背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['user_tip_bg']) { echo '<img src="'. $msg['user_tip_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传主播与用户互动弹窗背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="user_tip_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸600*320像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原主播与主播互动背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['anchor_tip_bg']) { echo '<img src="'. $msg['anchor_tip_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传主播与主播互动背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="anchor_tip_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸600*320像素</span>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原白色背景下 空状态背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['white_null_bg']) { echo '<img src="'. $msg['white_null_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传白色背景下 空状态背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="white_null_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸356*356像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原黑色背景图空状态背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['black_null_bg']) { echo '<img src="'. $msg['black_null_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传黑色背景图空状态背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="black_null_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸356*356像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原黑商品详情升级背景图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['goods_vip_bg']) { echo '<img src="'. $msg['goods_vip_bg'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传商品详情升级背景图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="goods_vip_bg" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸674*104像素</span>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">原专属客服logo图</label>
                                            <div class="layui-input-block">
                                                <?php if ($msg['sevrice_logo']) { echo '<img src="'. $msg['sevrice_logo'] .'" height="100"/>'; } else { echo '暂无'; } ?>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">上传专属客服logo图</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="sevrice_logo" accept="image/png" class="layui-input">
                                                <span class="layui-form-mid layui-word-aux">建议尺寸180*180像素</span>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- 其他配置  -->

                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                            <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>