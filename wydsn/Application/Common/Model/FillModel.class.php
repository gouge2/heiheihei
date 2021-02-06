<?php
/**
 * 直播充值选项管理类
 */
namespace Common\Model;
use Think\Model;

class FillModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('deduct','require','抵扣的人民币金额不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('redeem','require','兑换的鹿角金额不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );

    
}
?>