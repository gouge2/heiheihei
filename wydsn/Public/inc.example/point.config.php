<?php
/* 积分系统配置文件 */
//每个积分价值金额
define('POINT_VALUE','0.01');

//注册赠送积分
define('POINT_REGISTER','0');
//推荐注册赠送积分
define('POINT_RECOMMEND_REGISTER','0');

//签到奖励类型，1积分 2余额 3成长值
define('SIGN_AWARD_TYPE','1');
//签到奖励模式，1固定 2连续
define('SIGN_AWARD_MODEL','2');

//固定签到奖励数值
define('SIGN_AWARD_FIXED_NUM','1');

//连续签到奖励数值-第1天
define('SIGN_AWARD_CONTINUOUS_NUM1','1');
//连续签到奖励数值-第2天
define('SIGN_AWARD_CONTINUOUS_NUM2','2');
//连续签到奖励数值-第3天
define('SIGN_AWARD_CONTINUOUS_NUM3','3');
//连续签到奖励数值-第4天
define('SIGN_AWARD_CONTINUOUS_NUM4','4');
//连续签到奖励数值-第5天
define('SIGN_AWARD_CONTINUOUS_NUM5','5');
//连续签到奖励数值-第6天
define('SIGN_AWARD_CONTINUOUS_NUM6','6');
//连续签到奖励数值-第7天
define('SIGN_AWARD_CONTINUOUS_NUM7','7');

//完善资料奖励类型，1积分 2余额 3成长值
define('TASK_INFO_AWARD_TYPE','3');
//完善资料奖励数值
define('TASK_INFO_AWARD_NUM','30');