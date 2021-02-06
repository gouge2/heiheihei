<?php
/*
 * xzphp域名授权系统更新助手-仅供参考
 *
 *全自动更新需要配置数据库信息 请根据自己的程序系统的数据库配置文件做一下简单处理转换
 *--------------------------------------------------------
 *需修改的配置：
 *1.应用ID，APP_ID的值需要改成您对应的appid()
 *2. 
 *
 *
 *--------------------------------------------------------
 *程序说明：
 *1.程序根目录必须包含version.txt，内容为版本号如：1.0;
 *2.升级包中更新数据库文件必须命名为upgrade.sql
 *3.您的程序在更新之前必须将所有目录改为可写权限
 */
error_reporting(0);
session_start();
//安全起见增加登录检测 需要根据您自身的程序修改
function check_login(){
    if(!isset($_SESSION['admin_id']) or !$_SESSION['admin_id']){
        return false;
    }
    return true;
}

if(!check_login()){
    exit('INVALID ACCESS');
}
define('UPGRADE_WAY', 1);//升级方式：1全自动更新自动升级 2 半自动更新表示下载到客户端服务器 3表示通过浏览器下载到用户本地
define('UPGRADE_ROOT',__DIR__.'/');
define('DATA_ROOT',__DIR__.'/Public/upgrade/');
define('UPGRADE_ROOT',DATA_ROOT.'/upgradex/');//升级文件下载目录

define('IS_SHOW_DOWNURL',1);//升级助手页面是否显示下载链接
define('UPGRADE_SQL_NAME','upgrade.sql');

define('VERSION_WAY',1);//1 表示版本文件为version.txt  2表示版本文件为/data/version.php   

//GET方式传递应用ID
if(isset($_GET['appid'])){
    define('APP_ID',intval($_GET['appid']));
}
//GET方式传递应用所在目录
if(isset($_GET['appdir'])){
    define('APP_DIR',UPGRADE_ROOT.trim(strip_tags($_GET['appdir']),'/'));
}

//必须修改的设置
//define('API_URL','http://v3.18sq.cn/');//您的授权系统URL
define('API_URL','http://safe.taokeyun.cn/');//您的授权系统URL
defined('APP_ID') or define('APP_ID',25);//应用ID 采用GET参数传递后此定义无效
defined('APP_DIR') or define('APP_DIR',UPGRADE_ROOT);//应用所在目录 默认根目录


//以下是xzphp域名授权系统的数据库配置转换样例 请根据自己的程序修改代码
//$config_file = DATA_ROOT.'config.php';//数据库配置文件
//$config = include $config_file;
$db_file = UPGRADE_ROOT.'Application/Common/Conf/config.php';
$db_info = include $db_file;

define('DB_HOST',$db_info['DB_HOST'].':'.$db_info['DB_PORT']);//数据库地址:端口
define('DB_USER',$db_info['DB_USER']);//数据库账号
define('DB_PASSWORD',$db_info['DB_PWD']);//数据库密码
define('DB_NAME',$db_info['DB_NAME']);//数据库密码
define('DB_CHARSET',$db_info['DB_CHARSET']);//数据库编码

$ac = isset($_GET['a']) ? trim($_GET['a']) : 'html';
//header('Content-type: text/html; charset=utf-8');
$sqkey = '';
$version = isset($_GET['v']) ? $_GET['v'] : '';

if(VERSION_WAY == 1){
    if(!$version){
        if(file_exists('./version.txt')){
            $version = file_get_contents('./version.txt');
        }else{
            exit('version.txt is not exists.');
        }
    }
}else{
    if(!$version){
        if(file_exists(DATA_ROOT.'version.php')){
            $version = include DATA_ROOT.'version.php';
        }else{
            exit('/data/version.php is not exists.');
        }
    }
}
/*


*/
//由于opcache缓存影响 改为读取version.txt



/*
//可以考虑验证授权码
$sqkey = '';
if(is_file(DATA_ROOT.'xzlic.php')){
	$xzlic = include DATA_ROOT.'xzlic.php';
	$sqkey = $xzlic['sqkey'];
}
*/

$download_type = UPGRADE_WAY <= 2 ? 'download' : 'download_v2';
$apiurl = API_URL.'check.php?xz=1&v='.$version;


$host = $_SERVER['HTTP_HOST'];
$hosts = $host . '|' . $_SERVER['SERVER_NAME'];
$time = time();
$host_url = 'http://' .$host. '/';


$site_url_md5 = substr(md5($host_url),-16,-6);

$appid = APP_ID;
$token = md5($time . '|' . $hosts . '|xzphp|'.$site_url_md5);
$apiurl.= '&appid='.$appid.'&h=' . $hosts . '&t=' . $time . '&token=' . $token . '&v=' . $version.'&sqkey='.$sqkey;


function message($msg,$status = 0, $data = array()){
    exit(json_encode(array('status'=>$status,'msg'=>$msg,'data'=>$data)));
}
if($ac == 'html'){
    $url = $apiurl . '&a=upgrade';
    $html = file_get_contents($url);
    if (!$html) {
        message('请求失败，请刷新试试!');
    }
    $data = json_decode($html, true);
    $appname = isset($data['data']['appdata']) ? $data['data']['appdata']['name'] : '';

    if ($data['status'] != 1) {
        $pdata['appname'] = $appname;
        $pdata['old_version'] = $version;
        $pdata['status'] = $data['status'];
        $pdata['msg'] = $data['msg'];
        show_html($pdata);
        exit;
        message($data['msg'],0,array('notice'=>$data['data']['notice']));
    }
    $dtoken = $data['data']['dtoken'];
    $pdata = $data['data'];
    $pdata['appname'] = $appname;
    $pdata['status'] = 1;
    $pdata['download_url'] = $host_url . 'upgrade.php?a=' . $download_type . '&dtoken=' . $dtoken;
    $pdata['old_version'] = $version;
    $_SESSION['dtoken'] = $dtoken;
    $_SESSION['upgrade_data'] = $data['data'];
    show_html($pdata);
}elseif($ac == 'upgrade_submit'){
    set_time_limit(3600);
    ignore_user_abort(true);
    if(!isset($_SESSION['dtoken']) || !isset($_SESSION['upgrade_data'])){
        exit('数据错误，无法获取升级文件!');
    }
    $upgrade_data = $_SESSION['upgrade_data'];
    $dtoken = $_SESSION['dtoken'];
    //$download_url = $host_url . 'upgrade.php?a=' . $download_type . '&dtoken=' . $dtoken;
    $url = $apiurl . '&a=download&dtoken=' . $dtoken;
    $upgrade_file = get_file($url);
    if(!$upgrade_file){
        exit('下载升级文件失败!');
    }
    //先删除之前的数据库文件
    if(file_exists(__DIR__.'/upgrade.sql')){
        unlink(__DIR__.'/upgrade.sql');
    }
    unzip_file($upgrade_file,APP_DIR);//解压到目录
    $upgrade_sql_file = __DIR__.'/'.UPGRADE_SQL_NAME;
    $msg = '';
    if(file_exists($upgrade_sql_file)){
        $flag = upgrade_sql($upgrade_sql_file);
        if($flag){
            $msg .= '更新sql文件成功!';
        }else{
            $msg .= '更新sql文件失败!';
        }
    }
    //提交api 
    $time = time();
    $sign = md5($upgrade_data['appid'].'_'.$time.'_'.$upgrade_data['version'].'_'.$host);
    $url = API_URL.'check.php?a=upgradeok&v='.$upgrade_data['version'].'&appid='.$upgrade_data['appid'].'&domain='.$host.'&t='.$time.'&sign='.$sign;
    $html = curl_get($url);
    $msg .= '升级'.$upgrade_data['version'].'成功!';
    show_result($msg,1);
    //echo '<script>setTimeout(\'location.href="/";\',1500);</script>';
}elseif ($ac == 'js') {
    //JS直接显示版本对比信息 可以根据具体情况修改文字或者样式 载入方式：<script src="客户端网站/upgrade.php?a=html"></script>
    $time = time();

    if(isset($_COOKIE['check_upgrade']) && ($time - $_COOKIE['check_upgrade']) < 3600){
        exit('//');//缓存检测更新 一个小时检测一次
    }

    header('Content-type: text/javascript');
    $url = $apiurl . '&a=upgrade';
    $html = file_get_contents($url);

    if (!$html) {
        message('请求失败，请刷新试试!');
    }
    $data = json_decode($html, true);
    if ($data['status'] != 1) {
        $pdata['old_version'] = $version;
        $js = '您当前版本是'.$version.',已是最新版无需升级';
        setcookie('check_upgrade',$time,$time + 7200,'/');
    }else{
        $dtoken = $data['data']['dtoken'];
        $pdata = $data['data'];
        $pdata['status'] = 1;
        $pdata['download_url'] = $host_url . 'upgrade.php?a=' . $download_type . '&dtoken=' . $dtoken;
        $pdata['old_version'] = $version;
        $upgrade_url = $host_url.'upgrade.php?a=html&v='.$version;
        $js = '您当前版本是V'.$version.',系统最新版本是V'.$data['data']['version'].',<a target="_blank" href="'.$upgrade_url.'">请进入升级助手页面升级!</a>';
    }


    echo 'document.write(\''.$js.'\');';
    exit;
}elseif ($ac == 'jsajax') {
    //JS异步检测版本信息

}elseif ($ac == 'json') {
    //返回JSON对比信息，根据返回的数据自行定制化输出内容
    $url = $apiurl . '&a=upgrade';
    $html = file_get_contents($url);
    if (!$html) {
        message('请求失败，请刷新试试!');
    }
    $data = json_decode($html, true);
    if ($data['status'] != 1) {
        $pdata['status'] = 0;
        $pdata['old_version'] = $version;
    }else{
        $dtoken = $data['data']['dtoken'];

        $pdata = array(
            'status'=>1,
            'version'=> $data['data']['version'],
            'old_version'=> $version,
            'download_url' => $host_url . 'upgrade.php?a=' . $download_type . '&dtoken=' . $dtoken,
            'note'=>$data['data']['note']
        );
    }
    exit(json_encode($pdata));
}elseif ($ac == 'check') {
    $url = $apiurl . '&a=upgrade';
    $html = file_get_contents($url);
    if (!$html) {
        message('请求失败，请刷新试试!');
    }

    $data = json_decode($html, true);
    if ($data['status'] != 1) {
        message($data['msg'],0,array('notice'=>$data['data']['notice']));
    }

    $dtoken = $data['data']['dtoken'];
    //$download_url = $host_url . 'upgrade.php?a=' . $download_type . '&dtoken=' . $dtoken;
    $upgrade_url = $host_url.'upgrade.php?a=html';
    message('<a style="color:red" id="xz-upgrade" href="' . $upgrade_url . '" target="_blank"><font color="red">您当前版本'.$version.',系统最新版本<b>' . $data['data']['version'] . '</b>,点击这里进入升级助手页面</a></font>',1,array('note'=>$data['data']['note'],'notice'=>$data['data']['notice']));
    //message('<font color="red">您当前版本'.$version.',系统最新版本<b>' . $data['data']['version'] . '</b>,<a style="color:red" id="xz-upgrade" href="javascript:void(0)" onclick="upgrade_download()" data-href="' . $download_url . '" target="_blank">请点击这里下载更新</a></font>',1,array('note'=>$data['data']['note'],'notice'=>$data['data']['notice']));

} elseif ($ac == 'download') {
    //直接下载更新包保存到服务器
    $dtoken = isset($_GET['dtoken']) ? trim($_GET['dtoken']) : '';
    if (!$dtoken) {
        exit('非法请求!');
    }
    $url = $apiurl . '&a=download&dtoken=' . $dtoken;
    /*
        $headers = get_headers($url, 1);

        if (strpos($headers['Content-Type'],'text/html') !== false) {
            $html = file_get_contents($url);

            $data = json_decode($html, true);
            exit($data['msg']);
        }*/
    $upgrade_file = get_file($url);
    //echo '下载最新版本更新包成功!：' . $upgrade_file;
    echo '下载最新版本更新包成功,请自行去程序更新目录看最新文件!';
    exit;
} elseif ($ac == 'download_v2') {
    //浏览器下载更新包到用户本地
    $dtoken = isset($_GET['dtoken']) ? trim($_GET['dtoken']) : '';
    if (!$dtoken) {
        exit('非法请求!');
    }
    $url = $apiurl . '&a=download&dtoken=' . $dtoken;

    /*
    $headers = get_headers($url, 1);   
    if (strpos($headers['Content-Type'],'text/html') !== false) {
        $html = file_get_contents($url);
        $data = json_decode($html, true);
        exit($data['msg']);
    }
    */
    ob_end_clean();
    $filename = date('Ymd') . '_' . rand(100, 100000) . uniqid() . '.zip';
    header("Cache-Control: max-age=0");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename=' . $filename);
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");
    //header ( 'Content-Length: ' . filesize ( $file));
    readfile($url);
    flush();
    ob_flush();
    exit;
}
function get_file($url, $folder = './data/upgradex/') {
    set_time_limit(24 * 60 * 60);
    $target_dir = $folder . '';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $newfname = date('Ymd') . rand(1000, 10000000) . uniqid() . '.zip';
    $newfname = $target_dir . $newfname;
    $file = fopen($url, "rb");
    if ($file) {
        $newf = fopen($newfname, "wb");
        if ($newf) while (!feof($file)) {
            $buf = fread($file, 1024 * 8);
            if(strpos($buf,'{"status":0') === 0){
                $data = json_decode($buf, true);
                exit($data['msg']);
            }
            fwrite($newf, $buf, 1024 * 8);
        }
    }
    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
    }
    return $newfname;
}

function unzip_file($zipName,$dest)
{
    if (!is_file($zipName)) {
        return false;
    }
    if (!is_dir($dest)) {
        mkdir($dest, 0777, true);
    }
    $zip = new ZipArchive();
    if ($zip->open($zipName)) {
        $zip->extractTo($dest);
        $zip->close();
        return true;
    } else {
        return false;
    }
}
function upgrade_sql($filename = 'upgrade.sql'){
    if(!file_exists($filename)){
        return false;
    }
    $link = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
    $error = $link->connect_error;
    if (!is_null($error)) {
        exit('数据库链接失败!');
        return false;
    }
    $link->query("SET NAMES '".DB_CHARSET."'");
    if (!$link->select_db(DB_NAME)) {
        exit('数据库'.DB_NAME.'不存在!');
        $create_sql = 'CREATE DATABASE IF NOT EXISTS ' . $database . ' DEFAULT CHARACTER SET utf8;';
        $link->query($create_sql) or die('创建数据库失败');
        $link->select_db($database);
    }
    $sql = file_get_contents($filename);
    if(!$sql){
        return false;
    }
    $sqlarr = explode(';', $sql);

    foreach ($sqlarr as $key => $val) {
        if ($val) {
            $link->query($val);
        }
    }
    return true;
}
function curl_get($url, $timeout = 10){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_4 like Mac OS X)AppleWebKit/600.1.4 (KHTML, like Gecko) Mobile/12H143 MicroMessenger/6.3.9)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $res  =  curl_exec($ch);
    if(!$res){
        $res  =  curl_exec($ch);
    }
    curl_close($ch);
    return $res;
}
function show_html($pdata){
    echo '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />  
<title>升级更新助手</title>
<meta name="author" content="">
<script src="//cdn.staticfile.org/jquery/1.12.3/jquery.min.js"></script>
<script src="//cdn.bootcdn.net/ajax/libs/layer/1.8.5/layer.min.js"></script>
<style>
body{font-family:arial;background-color:#f2f2f2}
h1,h2,h3{font-family:inherit;font-weight:500;line-height:1.1;color:inherit}
.container{box-shadow:0 1px 3px #333;border:3px solid #98a5a5;background-color:#fff;border-radius:36px;padding:10px 20px;margin:0 auto}
.jumbotron{padding:30px 15px;margin-bottom:30px;color:inherit;background-color:#f5f5f5;border-radius:10px}
a{text-decoration:none;color:#5aa2d7}
a:hover{color:red}
.btn{border:none;border-radius:4px;margin:10px 0;cursor:pointer;font-size:20px}
.btn:hover{opacity:.8}
.btn-success{background-color:#40b877;color:#fff}
.btn-lg{width:300px;height:45px;line-height:45px}
@media (min-width:1200px){.container{width:1170px}
}
@media (min-width:992px){.container{width:970px}
}
</style>
</head>
<body>
    <div class="container" style="margin-top:9%;">
        <center><h1>系统更新升级助手</h1><h3>'.$pdata['appname'].'</3></center>';
    if($pdata['status'] != 1){
        $old_version = $pdata['old_version'];
        echo '
                <div class="jumbotron">
                  <p><h3 style="color:#40b877">'.$pdata['msg'].'</h3></p>
                </div>
            </div>
        </body>
        </html>';
        exit;
    }
    $url = IS_SHOW_DOWNURL ? '<br/><center><a href="'.$pdata['download_url'].'">点击这里下载升级补丁手工升级</a></center>' : '';
    echo '
        <div class="jumbotron">
          <p><h3>您当前版本是<font color="red">V'.$pdata['old_version'].'</font>，系统最新版本是<font color="red">V'.$pdata['version'].'</font>，请立即升级!</h3></p>
          <div class="note">'.$pdata['note']. '</div>
        </div>
        <form action="upgrade_old.php?a=upgrade_submit" method="post" onsubmit="return showloading()">
        <div class="xtip" style="color:red;font-size:16px;font-weight:bold">请确认在升级之前做好程序备份和数据库备份，并确认程序所有目录具备可写权限！</div>
        <center><button type="submit" name="submit" class="btn btn-success btn-lg">立即升级</button></center>
        ' .$url.'
        </form>
    </div>
</body>
        <script>
        function showloading(){
           layer.msg("正在升级中，请稍等", {
              icon: 16
              ,shade: 0.01
            });
           return true;
        }

</script>
</html>';
    exit;
}

function show_result($msg = '',$status = 1){
    echo '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />  
<title>升级更新助手-xzphp域名授权系统</title>
<meta name="author" content="xzphp域名授权系统，18sq.cn QQ1517293109">
<style>
body{font-family:arial;background-color:#f2f2f2}
h1,h2,h3{font-family:inherit;font-weight:500;line-height:1.1;color:inherit}
.container{box-shadow:0 1px 6px #333;border:5px solid #98a5a5;background-color:#fff;border-radius:36px;padding:10px 20px;margin:0 auto}
.jumbotron{padding:30px 15px;margin-bottom:30px;color:inherit;background-color:#f5f5f5;border-radius:10px}
a{text-decoration:none;color:#5aa2d7}
a:hover{color:red}
.btn{border:none;border-radius:4px;margin:10px 0;cursor:pointer;font-size:20px}
.btn:hover{opacity:.8}
.btn-success{background-color:#40b877;color:#fff}
.btn-lg{width:300px;height:45px;line-height:45px}
@media (min-width:1200px){.container{width:1170px}
}
@media (min-width:992px){.container{width:970px}
}
</style>
</head>
<body>
    <div class="container" style="margin-top:9%;">
        <center><h1>系统更新升级助手</h1></center>
                <div class="jumbotron">
                  <div class="panel panel-success" style="font-size:22px;color:#40b877;"><div class="panel-heading"><h1>'.$msg.'</h1></div></div>
                </div>
            </div>
        </body>
        </html>';
    exit;
}
?>

