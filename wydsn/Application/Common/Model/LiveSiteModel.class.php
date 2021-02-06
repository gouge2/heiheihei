<?php
/**
 * 直播次数管理类
 */
namespace Common\Model;
use Think\Model;

class LiveSiteModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('room_id','require','f房间标识不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('start_time','require','开始时间不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );

    
}
?>