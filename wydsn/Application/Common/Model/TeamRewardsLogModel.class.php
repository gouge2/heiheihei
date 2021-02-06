<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 会员授权/邀请码管理
 */
namespace Common\Model;
use Think\Model;

class TeamRewardsLogModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('user_id','require','团队分红用户ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('buy_id','require','购买商品用户ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('order_id','require','订单ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('buy_method',array('tb','jd','pdd','vip'),'请选择购买渠道！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是tb淘宝 jd京东 pdd拼多多 vip唯品会
    );

    /**
     * 生成用户团队分红记录
     * @param int $user_id:分红用户ID
     * @param int $buy_id:购买用户ID
     * @param string $order_id:订单ID
     * @param string $buy_method:订单类型 tb淘宝 jd京东 pdd拼多多 vip唯品会
     * @param int $rewards_level:分红等级 1一级团队分红 2二级团队分红
     * @return boolean
     */
    public function addLog($user_id,$buy_id,$order_id,$buy_method,$rewards_level)
    {
        $data=array(
            'user_id'=>$user_id,
            'buy_id'=>$buy_id,
            'order_id'=>$order_id,
            'buy_method'=>$buy_method,
            'rewards_level'=>$rewards_level,
            'create_time'=>date('Y-m-d H:i:s')
        );
        if(!$this->create($data)) {
            return false;
        }else {
            $res=$this->add($data);
            if($res!==false) {
                return true;
            }else {
                return false;
            }
        }
    }

    /**
     * 获取用户团队分红记录列表
     * @param int $user_id:会员ID
     * @param string $type:购买渠道 tb淘宝 jd京东 pdd拼多多 vip唯品会
     * @return array|boolean
     */
    public function getRecordList($user_id,$type='')
    {
        $where="user_id='$user_id' and buy_method='$type'";
        $list=$this->where($where)->field('order_id')->order("id desc")->select();
        if($list!==false) {
            return $list;
        }else {
            return false;
        }
    }

    /**
     * 获取用户团队分红记录
     * @param string $uid:用户ID
     * @param int $order_id:订单ID
     * @return array|boolean
     */
    public function getRecordMsg($uid,$order_id)
    {
        $msg=$this->where("user_id='$uid' and order_id='$order_id'")->find();
        if($msg)
        {
            return $msg;
        }else {
            return false;
        }
    }

}
