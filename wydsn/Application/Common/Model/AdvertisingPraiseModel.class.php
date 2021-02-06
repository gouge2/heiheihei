<?php
/**
 * 用户点赞关系管理类
 */
namespace Common\Model;
use Think\Model;

class AdvertisingPraiseModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('ad_id','require','广告标识不能为空！',self::EXISTS_VALIDATE),      //存在验证，必填
        array('user_id','require','点赞人标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('add_time','require','点赞时间不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );
}
?>