<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登陆</title>
    <!-- Meta tag Keywords -->
    <meta charset="UTF-8" />
    <meta name="keywords" content="登陆"
    />
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <style>
        body{
            padding: 0;
            margin: 0;
        }
        .indexLogin{
            width: 100vw;
            height: 100vh;
            position: relative;
            background-image: url("__ADMIN_IMG__/indexBg.png");
            background-size: contain;
            background-repeat: no-repeat;
            background-color:rgba(245,246,250,1);
        }
        .loginForm{
            width:370px;
            height: 380px;
            position: absolute;
            top: calc(50% - 190px);
            right: 15.76%;
        }
        .loginFormTitle{
            height:42px;
            font-size:30px;
            font-weight:600;
            color:rgba(78,103,125,1);
            line-height:42px;
            margin-bottom: 23px;
        }
        .formBox{
            width:370px;
            margin-bottom: 23px;
            position: relative;
        }
        .formInput{
            width:370px;
            height:44px;
            background-color:rgba(255,255,255,1) !important;
            box-shadow:0px 6px 12px 0px rgba(233,234,238,1);
            border-radius:22px;
            border: none;
            padding: 0 19px;
            overflow: hidden;
        }

        .indexLogin input:focus{
            outline: none;
            box-shadow: 0 0 0 2px #FDAD56;
        }
        .submitBtn{
            width:408px;
            height:50px;
            background:linear-gradient(270deg,rgba(255,85,78,1) 0%,rgba(249,193,42,1) 100%);
            box-shadow:0px 4px 4px 1px rgba(241,223,219,1);
            border-radius:25px;
            border: none;
            font-size:18px;
            font-weight:600;
            color:rgba(255,255,255,1);
        }
        .indexFoot{
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            text-align: center;
            z-index: 1;
            padding-bottom: 20px;
            color: #bfbfbf;
        }

    </style>

</head>
<?php
if($_COOKIE['remember']=='ok')
{
    $checked='checked';
}else {
    $checked="";
}
?>
<body>
<div class="indexLogin">
    <form class="loginForm" action="__CONTROLLER__/loginin" method="post">
        <div class="loginFormTitle">后台管理系统</div>
        <div class="formBox">
            <input class="formInput" placeholder="请输入账号" name="adminuser" value="<?php echo $_COOKIE['loginname'];?>" type="text" required="">
        </div>
        <div class="formBox">
            <input class="formInput" placeholder="请输入密码" name="adminpwd" value="<?php echo $_COOKIE['loginpwd'];?>" type="password" required="">
        </div>
        <div class="formBox" style="margin-bottom: 37px;">
            <input class="formInput" placeholder="请输入验证码" name="auth" type="text" required="">
            <div style="position: absolute;top: 0;right: 0;height: 100%;display: flex;align-items: center;padding: 0 20px;">
                <p class="zcode"><img src="__CONTROLLER__/verify" alt="点击刷新验证码" onclick="this.src='__CONTROLLER__/verify?'+Math.random()" style="width:92px;height:39px"/></p>
            </div>
        </div>
        <!-- checkbox -->
        <div class="formBox">
            <label class="anim">
                <input type="checkbox" class="checkbox" name="remember" value="ok" <?php echo $checked;?>/>
                <span>记住密码</span>
            </label>
            <p style="font-size:18px;color:red;margin-top:30px">{$error}</p>
        </div>
        <!-- //checkbox -->
        <input class="submitBtn" type="submit" value="登录">
    </form>
    <div class="indexFoot">
        Copyright  2019-2020 LL Inc. Powered by Lailu
    </div>
</div>
</body>
</html>