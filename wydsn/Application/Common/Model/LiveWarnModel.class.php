<?php
/**
 * 直播房间警告记录
 */
namespace Common\Model;
use Think\Model;

class LiveWarnModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('room_id','require','房间标识不能为空！',self::EXISTS_VALIDATE),              //存在验证，必填
        array('text','require','内容不能为空！',self::EXISTS_VALIDATE),                     //存在验证，必填
    );

    
}
?>