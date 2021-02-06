<?php
// 设置页面编码
header("Content-type:text/html;charset=utf-8");
//设置时区
date_default_timezone_set('Asia/Shanghai');

/* APP参数配置文件 */
define('APP_NAME','来鹿');     //App名称
define('VERSION_IOS','1.2.5');     //苹果版本号
define('VERSION_ANDROID','1.4.4.28');     //安卓版本号
define('DOWN_IOS','https://fir.im/lmub');     //苹果下载地址
define('DOWN_ANDROID','https://fir.im/lmub');     //安卓下载地址
//苹果新版本更新内容
define('UPDATE_CONTENT_IOS','1、品牌精选
2、生活券');
//安卓新版本更新内容
define('UPDATE_CONTENT_ANDROID','1、抖券好货');

/* 网站参数配置文件 */
define('WEB_URL','http://tao.lailu.live');//网址
define('WEB_TITLE','来鹿自营版后台');     //网站标题
define('seo_keywords','来鹿购物平台');       //网站关键字
define('seo_description','来鹿云购物平台'); //网站描述
define('seo_copyright','来鹿云购物平台');     //网站版权信息

define('WEB_TITLE_EN','来鹿测试平台');     //英文网站标题
define('seo_keywords_en','来鹿云购物平台');       //英文网站关键字
define('seo_description_en','来鹿云购物平台'); //英文网站描述
define('seo_copyright_en','来鹿云购物平台');     //英文网站版权信息

define('QQ_CSS','1'); //客服样式
define('CONTACT_PHONE','400-6166-025'); //客服咨询电话
//平台微信号
define('PLATFORM_WX','carsll');

//分享淘宝商品网址
define('SHARE_URL','http://tao.lailu.live');

//分享注册下载网址
define('SHARE_URL_REGISTER','http://tao.lailu.live');

//VIP专用分享网址
define('SHARE_URL_VIP','http://tao.lailu.live');

//网站备案号
define('WEB_RECORD_NUMBER','');

//seo_copyright_en
define('to_update','N');

//to_update_ios
define('to_update_ios','N');

//pay_methods
define('PAY_METHODS','banlance,alipay,wxpay');
?>