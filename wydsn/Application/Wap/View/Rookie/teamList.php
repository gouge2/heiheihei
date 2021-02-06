<!doctype html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no" />
    <title>{$web_title}-<?php echo WEB_TITLE;?></title>
    <meta name="keywords" content="{$web_keywords}" />
    <meta name="description" content="{$web_description}" />

    <!-- Bootstrap -->
    <link href="__WAP_CSS__/bootstrap.css" rel="stylesheet">
    <link href="__WAP_CSS__/slick.css" rel="stylesheet">
    <link href="__WAP_CSS__/style.css" rel="stylesheet">
</head>

<body>
    <div class="index-back"></div>
    <div class="header"><a href="__CONTROLLER__/index/uid/{$uid}"></a></div>
    <div class="back-box">
        <div class="back-img"><img src="__WAP_IMG__/back01.png"></div>
        <div class="back-bottom text-center">
            <div class="back-date-tt">拉新活动兑换奖励时间</div>
            <p class="p-sm"><span class="payment-time"><span class="active-time pull-right"><em class="time_d">{$start_m}</em>月<em class="time_h">{$start_d}</em>—<em class="time_d">{$end_m}</em>月<em class="time_s">{$end_d}</em></span></span></p>
        </div>
    </div>
    <div class="tt-img"><img src="__WAP_IMG__/tt03.png"></div>
    <input type="hidden" id="uid" value="{$uid}">
    <div class="back-rs">
        <ul class="rs-list text-center">
            <?php 
            $i=0;
            foreach ($userList as $l){
                $i++;
                //手机号码
                $phone=substr_replace($l['phone'],'****',3,4);
                //用户头像
                $avatar='__WAP_IMG__/logo.png';
                if($l['avatar']){
                    $avatar=$l['avatar'];
                }
                //注册时间
                $register_date=date('Y.m.d',strtotime($l['register_time']));
                echo '<li>
                <div class="name">NO.'.$i.'</div>
                <div class="rs-img"><img src="'.$avatar.'"></div>
                <div class="rs-phone">'.$phone.'</div>
                <div class="rs-date">'.$register_date.'</div>
            </li>';
            }
            ?>
        </ul>
    </div>
    <div class="text-center btn-p">
        <?php if($is_change==0){?>
        <a href="javascript:;" onclick="sub()" title="兑换">
            <img src="__WAP_IMG__/btn-02.png">
            <p style="font-size: 2.2rem;color:#ff6699;position: relative;margin: auto; bottom: 4.1rem;">兑换奖励 <span><?php echo $ref;?>元</span></p>
        </a>
        <?php }else{?>
            <a href="javascript:;" title="已兑换">
                <img src="__WAP_IMG__/btn-1.png">
            </a>
        <?php }?>
    </div>
    <div><img src="__WAP_IMG__/bottom.png"></div>
    <input type="hidden" id="url" value="__CONTROLLER__/exChangePorift">
    <input type="hidden" id="rid" value="{$rid}">
    <div class="mask" id="mask">
        <div class="box">
            <div class="duihuan">
                <div class="succees">兑换成功</div>
                <div class="price">兑换金额为<?php echo $ref;?>元,请前往余额查看</div>
                <div class="btn" id="close">知道了</div>
            </div>
        </div>
    </div>

</body>
<style>
    .mask{
        height: 100vh;
        background-color: rgba(0,0,0,.5);
        width: 100%;
        top: 0;
        left: 0;
        position: fixed;
        overflow: hidden;
        display: none;
    }
    .box .duihuan{
        position: absolute;
        top: 40%;
        left: 10%;
        background-color: #fff;
        height: 180px;
        width: 300px;
        border-radius: 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
    }
    .box .duihuan .succees{
        margin-top: 10px;
        font-size: 18px;
        font-family: PingFangSC-Regular, PingFang SC;
        font-weight: 600;
        color: #FF6699;
    }
    .box .duihuan .price{
        font-size: 16px;
        font-family: PingFangSC-Regular, PingFang SC;
        font-weight: 500;
        color: #333333;
    }
    .box .duihuan .btn{
        height: 44px;
        width: 150px;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        background-color: #FF6699;
    }
</style>
<script src="__WAP_JS__/jquery.min.js"></script>
<script type="text/javascript">
    function sub()
    {
        var url = "__CONTROLLER__/exChangePorift";
        var uid = $('#uid').val();
        var rid = $('#rid').val();

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: "uid=" + uid +"&rid="+rid,
            success: function (msg) {
                if(msg.code==0)
                {
                    alert(msg.msg);
                }else{
                    $('.mask').css('display','block');
                    let tops = $(document).scrollTop();//当页面滚动时，把当前距离赋值给页面，这样保持页面滚动条不动
                    $(document).bind("scroll",function (){
                        $(document).scrollTop(tops);
                    });
                }
            }
        });

        $("#close").click(function (){
            $(document).unbind("scroll");
            $(".mask").hide();
            window.location.reload();
        })
    }

</script>

</html>