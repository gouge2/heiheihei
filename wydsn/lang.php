<?php
$plats = ['android', 'ios', 'applet'];
if(!isset($_GET['plat']) || !in_array($_GET['plat'], $plats)){
    exit('请加上plat参数.并且取值范围是：'.implode('/', $plats));
}
$plat = $_GET['plat'];
$path = "./Public/lang/$plat/";
if(!is_dir($path)){
    mkdir($path);
}
$path = $path."zh.json";

if(isset($_POST['pack'])){
    $pack = $_POST['pack'];
    file_put_contents($path, $pack);
}

if(file_exists($path)){
    $language = file_get_contents($path);
}else{
    $language = '';
}


?>

<!doctype html>
<html>
<head>
    <title>设置语言包</title>
    <script src="//cdn.staticfile.org/jquery/1.12.3/jquery.min.js"></script>
</head>
<body>
<form action="/lang.php?plat=<?php echo $plat; ?>" method="post">
    <div>
        <button>保存</button>
        <a target="_blank" href="<?php echo "/Public/lang/$plat/zh.json"; ?>">地址</a>
    </div>
    <hr>
    <textarea name="pack" style="width: 80%;height: 1000px"><?php echo $language;?></textarea>
</form>

</body>

</html>


