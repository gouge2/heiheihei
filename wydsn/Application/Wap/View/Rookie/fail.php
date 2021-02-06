<!doctype html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no" />
    <title>拉新活动</title>

    <!-- Bootstrap -->
    <link href="__WAP_CSS__/bootstrap.css" rel="stylesheet">
    <link href="__WAP_CSS__/slick.css" rel="stylesheet">
    <link href="__WAP_CSS__/style.css" rel="stylesheet">
</head>
<body>
<div class="index-back"></div>
<div class="header"><!-- <a href=""></a> --></div>
<div class="back-box">
    <a href="#rule" class="gz-link"></a>
    <div class="back-img"><img src="__WAP_IMG__/back01.png"></div>
    <div class="back-bottom text-center">
        <div class="back-date-tt">拉新活动剩余时间</div>
        <h3 style="font-size: 1.5vw">拉新活动时间已结束，下次再来吧</h3>
    </div>
</div>
</body>
<script src="__WAP_JS__/jquery.min.js"></script>
<script src="__WAP_JS__/countDown.js"></script>
<script type="text/javascript">
    $("input[name='countDown']").each(function () {
        var time_end=this.value;
        var con=$(this).next("span");
        var _=this.dataset;
        countDown(con,{
            title:_.title,//优先级最高,填充在prefix位置
            prefix:_.prefix,//前缀部分
            suffix:_.suffix,//后缀部分
            time_end:time_end//要到达的时间
        })
        //提供3个事件分别为:启动,重启,停止
            .on("countDownStarted countDownRestarted  countDownEnded ",function (arguments) {
                console.info(arguments);
            });
    });

</script>
</html>