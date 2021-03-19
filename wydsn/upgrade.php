<?php
define('AUTH_DOMAIN', 'auth.lailu.live');

$act = getParam('act');
if(!$act){
    exit();
}

if (!defined('SERVER_VERSION')) {
    define('SERVER_VERSION', '');
}
if (!defined('LAILU_APP_KEY')) {
    define('LAILU_APP_KEY', '');
}

$download_url = "http://".AUTH_DOMAIN."/upgrade/download/".SERVER_VERSION."?app_key=".LAILU_APP_KEY;
//下载更新包
if($act == 'check'){
    $url = "http://".AUTH_DOMAIN."/upgrade/last";
    $data = httpGet($url);
    if($data['code'] == 100){
        exit('授权失败');
    }
    if(empty($data['data'])){
        exit('已经是最新版本');
    }
    $str = '有新版本更新：当前版本：'.SERVER_VERSION.' 最新版本是：'.(isset($data['data']['version']) ? $data['data']['version'] : '');
    $str .= "<a target='_blank' href='/upgrade.php?act=info'>请进入升级助手升级</a>";

    exit($str);
}

if($act == 'info'){
    $url = "http://".AUTH_DOMAIN."/upgrade/check/".SERVER_VERSION;
    $data = httpGet($url);
    if($data['code'] == 100){
        exit('授权失败');
    }
    $list = $data['data'];
    $is_update = true;
    if(empty($list)){
        $is_update = false;
    }else{
        $note = '';
        $first = current($list);
        $last_version = $first['version'];
        foreach ($list as $item){
            $note .= $item['description'].PHP_EOL;
        }

        $note = str_replace(PHP_EOL, '<br>', $note);
    }
}

if($act == 'submit'){
    $pack = file_get_contents($download_url);
    $zip_dir = './Public/upgrade';
    file_put_contents("$zip_dir/upgrade.zip", $pack);

    if(!is_dir($zip_dir)){
        mkdir($zip_dir);
    }

    if(!is_dir($zip_dir.'/upgrade')){
        mkdir($zip_dir.'/upgrade');
    }

    unzip($zip_dir.'/upgrade.zip', $zip_dir.'/upgrade');
    copy_dir($zip_dir.'/upgrade/', __DIR__);

    $database = $zip_dir.'/upgrade/databases';
    if(is_dir($database)){
        include './Public/inc/db.config.php';
        $dsn="mysql:dbname=".DB_NAME.";host=".DB_HOST;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $files = scandir($database);
        foreach ($files as $file){
            if($file == '.' || $file == '..'){
                continue;
            }
            $sql = file_get_contents($database.'/'.$file);
            try {
                $pdb = $pdo->prepare($sql);
                $pdb->execute();
            }catch (Exception $exception){
                continue;
            }
        }
    }

    delDir($zip_dir.'/upgrade');
    $is_update = false;
}

/**
 * 发起http get请求
 * @param $url
 * @return false|string
 */
function httpGet($url){
    $url .= "?app_key=".LAILU_APP_KEY;
    $data = file_get_contents($url);
    $data = json_decode($data, true);
    return $data;
}


/**
 * 获取参数
 * @param $key
 * @return mixed|string
 */
function getParam($key){
    return isset($_GET[$key]) ? $_GET[$key] : '';
}

/**
 * 解压文件
 * @param $filePath
 * @param $path
 * @return bool
 */
function unzip($filePath, $path) {
    if (empty($path) || empty($filePath)) {
        return false;
    }

    $zip = new ZipArchive();

    if ($zip->open($filePath) === true) {
        $zip->extractTo($path);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

function copy_dir($src, $des)
{
    $dir = opendir($src);
    @mkdir($des);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $des . '/' . $file);
            } else {
                copy($src . '/' . $file, $des . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function delDir($dir) {
    //先删除目录下的文件：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }

    closedir($dh);
    //删除当前文件夹：
    if(rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

?>

<!doctype html>
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
    <center><h1>系统更新升级助手</h1><h3>翠花</h3></center>
    <div class="jumbotron">
        <?php if($is_update){ ?>
            <p><h3>您当前版本是<font color="red"><?php echo SERVER_VERSION;?></font>，系统最新版本是<font color="red"><?php echo $last_version; ?></font>，请立即升级!</h3></p>
            <div class="note"><?php echo $note; ?></div>
        <?php }else{ ?>
            <p><h3>您当前已经是最新版本，无需升级</h3></p>
        <?php } ?>
    </div>
    <?php if($is_update){ ?>
        <form action="/upgrade.php?act=submit" method="post" onsubmit="return showloading()">
            <div class="xtip" style="color:red;font-size:16px;font-weight:bold">请确认在升级之前做好程序备份和数据库备份，并确认程序所有目录具备可写权限！</div>
            <center><button type="submit" name="submit" class="btn btn-success btn-lg">立即升级</button></center>
            <br/><center><a target="_blank" href="<?php echo $download_url ?>">点击这里下载升级补丁手工升级</a></center>
        </form>
    <?php } ?>
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
</html>
