<?php
/**
 * 直播房间弹幕管理类
 */
namespace Common\Model;
use Think\Model;

class LiveBulletModel extends Model
{
    // 验证规则
    protected $_validate =array(
        array('room_id','require','房间号不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
        array('user_id','require','发送者不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );


    /**
	 * 弹幕回调添加记录
	 */
    public function callAdd($uid, $rid, $text)
    {
        if ($uid && $rid && $text) {
            $LiveSite 	= new \Common\Model\LiveSiteModel();
            $site       = $LiveSite->field('site_id')->where(['room_id' => $rid])->order('site_id desc')->find();
            $ins        = [];

            if ($site) {
                $ins    = [
                    'room_id'   => $rid,
                    'site_id'   => $site['site_id'],
                    'user_id'   => $uid,
                    'text'      => $text,
                    'add_time'  => date('Y-m-d H:i:s'),
                ];
            }
     
            // 记录弹幕信息
            if ($ins) {
                $this->add($ins);
            }
        }
    }

}
?>