<?php
/**
 * 直播禁播日志管理类
 */
namespace Common\Model;
use Think\Model;

class LiveBanModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('room_id','require','房间号不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );

    
}
?>