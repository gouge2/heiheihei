<?php
// 应用入口文件--多入口文件--Admin模块入口文件
// 自动加载
require_once './Public/inc/autoload.php';

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);
// 开启日志调试模式，不记录日志设为false
define('APPLOG_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 绑定Admin模块到当前入口文件
define('BIND_MODULE','Console');

//设置gzip压缩
/* define ('GZIP_ENABLE',function_exists ('ob_gzhandler'));
ob_start (GZIP_ENABLE ? 'ob_gzhandler' : null ); */

if(!isset($argv[0]) || $argv[0] !== "console.php"){
    exit;
}

$c = "Help";
$a = "index";
if(isset($argv[1])){
    $to = explode(':', $argv[1]);
    $c = ((isset($to[0]) && $to[0]) ? $to[0] : $c);
    $a = ((isset($to[1]) && $to[1]) ? $to[1] : $a);
}

define('BIND_CONTROLLER', $c);
define('BIND_ACTION', $a);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';