<!DOCTYPE HTML>
<html lang="zxx">

<head>
    <title>登陆</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <!-- Meta tag Keywords -->
    <!-- css files -->

    <link rel="stylesheet" href="__ADMIN_CSS__/../login/css/style.css" type="text/css" media="all" />
    <!-- Style-CSS -->
    <link rel="stylesheet" href="https://www.jq22.com/jquery/font-awesome.4.7.0.css">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- //css files -->
    <!-- web-fonts -->

    <!-- //web-fonts -->
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
<!-- bg effect -->
<div id="bg">
    <!-- <canvas></canvas>
    <canvas></canvas>
    <canvas></canvas> -->
</div>
<!-- <div id="particles-js" style="display: flex;align-items: center;justify-content: center"></div> -->
<!-- //bg effect -->
<!-- title -->
<!-- <h1>Effect Login Form</h1> -->
<!-- //title -->
<!-- content -->
<div class="sub-main-w3">
    <form action="__CONTROLLER__/loginin" method="post">
        <h2>代理商管理系统
            <i class="fa fa-long-arrow-down"></i>
        </h2>
        <div class="form-style-agile">
            <label>
                <i class="fa fa-user"></i>
                账号
            </label>
            <input placeholder="请输入账号" name="adminuser" value="<?php echo $_COOKIE['loginname'];?>" type="text" required="">
        </div>
        <div class="form-style-agile">
            <label>
                <i class="fa fa-unlock-alt"></i>
                密码
            </label>
            <input placeholder="请输入密码" name="adminpwd" value="<?php echo $_COOKIE['loginpwd'];?>" type="password" required="">
        </div>
        <div class="form-style-agile">
            <label>
                <i class="fa fa-unlock-alt"></i>
                验证码
            </label>
            <div style="position: relative;">
                <input placeholder="请输入验证码" name="auth" type="text" required="">
                <div style="position: absolute;top: 0;right: 0;height: 100%;display: flex;align-items: center;padding: 0 20px;">
                    <p class="zcode"><img src="__CONTROLLER__/verify" alt="点击刷新验证码" onclick="this.src='__CONTROLLER__/verify?'+Math.random()" style="width:92px;height:39px"/></p>
                </div>
            </div>
        </div>
        <!-- checkbox -->
        <div class="wthree-text">
            <ul>
                <li>
                    <label class="anim">
                        <input type="checkbox" class="checkbox" name="remember" value="ok" <?php echo $checked;?>/>
                        <span>记住密码</span>
                    </label>
                </li>
                <li>
                    <p style="font-size:18px;color:red;margin-top:30px">{$error}</p>
                </li>
            </ul>
        </div>
        <!-- //checkbox -->
        <input type="submit" value="登录">
    </form>
</div>
<!-- //content -->

<!-- copyright -->
<div class="footer">
    <p>Copyright &copy; 2020.翠花网络科技 All rights reserved.</p>
</div>
<!-- //copyright -->

<!-- Jquery -->
<script src="__ADMIN_CSS__/../login/js/jquery-3.3.1.min.js"></script>
<!-- //Jquery -->

<!-- effect js -->
<!-- <script src="js/canva_moving_effect.js"></script> -->
<script src="__ADMIN_CSS__/../login/js/particles.js"></script>
<script src="__ADMIN_CSS__/../login/js/app.js"></script>
<!-- //effect js -->

</body>

</html>
