<?php
/* 提现参数配置文件 */

//提现方式 1人工审核  2后台审核 3自动转账
define('DRAW_METHOD','2');
//自动转账金额
define('DRAW_AUTO_MONEY','300');
//自动转账-大额提现后台审核是否自动转账
define('DRAW_AUTO_TYPE','N');
//可提现起始日期
define('DRAW_START_DATE','1');
//可提现截止日期
define('DRAW_END_DATE','31');
//最低提现金额
define('DRAW_LIMIT_MONEY','1');

//最低提现金额
define('DRAW_FEE','0');

//订单返利方式 1每月某天  2订单结算后多少天
define('REBATE_METHOD','1');

//订单返利时间
define('REBATE_TIME','25');
?>