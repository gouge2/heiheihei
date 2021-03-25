<?php
/**
 * by 翠花 http://http://livedd.com
 * 拉新活动
 */
namespace Common\Model;
use Think\Model;

class RookieModel extends Model
{
    // 验证规则
    protected $_validate = array(
        array('name','require','活动名称不能为空'), // 存在验证，必填
        array('start_time','require','活动开始时间不能为空'), // 存在验证，必填
        array('start_time','is_datetime','活动开始时间格式不正确！',2,'function'),  // 值不为空验证，必须是正确的时间格式
        array('end_time','require','活动结束时间不能为空'), // 存在验证，必填
        array('end_time','is_datetime','活动结束时间格式不正确！',2,'function'),  // 值不为空验证，必须是正确的时间格式
        array('exs_time','require','兑换开始时间不能为空'), // 存在验证，必填
        array('exs_time','is_datetime','兑换开始时间格式不正确！',2,'function'),  // 值不为空验证，必须是正确的时间格式
        array('exe_time','require','兑换结束时间不能为空'), // 存在验证，必填
        array('exe_time','is_datetime','兑换结束时间格式不正确！',2,'function'),  // 值不为空验证，必须是正确的时间格式
        array('lv_num','require','等级不能为空'), // 存在验证，必填
        array('lv_num','is_positive_int','等级必须为正整数',2,'function'), // 值不为空验证，等级必须为正整数
    );
    
    /**
     * 获取最新的活动
     * @return array|boolean
     */
    public function getLastRookie()
    {
        $msg=$this->where("exe_time>NOW()")->order('id desc')->find();
        if($msg){
            //获取活动奖项设置
            $RookieDetails=new \Common\Model\RookieDetailsModel();
            $msg['rewardSet']=$RookieDetails->getRewardSet($msg['id']);
            return $msg;
        }else {
            return false;
        }
    }

    /**
     * 获取兑换的最后时间
     * @return false
     */
    public function getLastExchangeRookie()
    {
        $msg=$this->where("exe_time>NOW()")->order('id desc')->find();
        if($msg){
            //获取活动奖项设置
            $RookieDetails=new \Common\Model\RookieDetailsModel();
            $msg['rewardSet']=$RookieDetails->getRewardSet($msg['id']);
            return $msg;
        }else {
            return false;
        }
    }
    
    /**
     * 获取活动信息
     * @param int $id:活动ID
     * @return array|boolean
     */
    public function getActivityMsg($id)
    {
        $msg=$this->where("id=$id")->find();
        if($msg){
            return $msg;
        }else {
            return false;
        }
    }
    
    /**
     * 获取活动详情
     * @param int $id:活动ID
     * @param string $order:奖项设置排序方式，asc、desc
     * @return array|boolean
     */
    public function getActivityDetail($id,$order='asc')
    {
        $msg=$this->getActivityMsg($id);
        if($msg){
            //获取活动奖项设置
            $RookieDetails=new \Common\Model\RookieDetailsModel();
            $msg['rewardSet']=$RookieDetails->getRewardSet($msg['id'],$order);
            return $msg;
        }else {
            return false;
        }
    }

    public function exChangeUserProift($uid,$rid,$data)
    {
        $userModel = new \Common\Model\UserModel();
        $rookieDetailModel = new \Common\Model\RookieDetailsModel();
        $rookieUserModel = new \Common\Model\RookieUserModel();

        $where="u.referrer_id=$uid";
        $start_time=$data['start_time'];
        $end_time=$data['end_time'];
        $where="u.referrer_id=$uid and u.register_time between '$start_time' and '$end_time'";
        $sql="select u.uid from __PREFIX__user u,__PREFIX__user_detail d where $where and u.uid=d.user_id order by u.uid asc";
        $userList=M()->query($sql);
        if(count($userList)>0)
        {
            #如果邀请人数大于0的情况下，才发放奖励
            $usermsg = $userModel->getUserMsg($uid);

            #计算邀请奖励
            $setting = $rookieDetailModel->where('start_interval <='.count($userList).' and end_interval >='.count($userList))->find();
            $ref = $setting['reward_num']*count($userList);
            $this->startTrans();
            switch ($ref['reward_type'])
            {
                case 2:#发放积分
                    $userPointRecordModel = new \Common\Model\UserPointRecordModel();
                    $all_money = $usermsg['point']+$ref;
                    $res_point_log = $userPointRecordModel->addLog($uid,$ref,$all_money,'buy');
                    if($res_point_log === false)
                    {
                        $this->rollback();
                        return false;
                    }
                    //增加推荐人余额
                    $data_balance=array(
                        'point'=>$all_money,
                    );
                    $res_balance=$userModel->where("uid='$uid'")->save($data_balance);
                    #记录已发放
                    $res_rookie_user_log = $rookieUserModel->create(['user_id'=>$uid,'rid'=>$rid,'exchange'=>date('Y-m-d H:i:s',time()),'num'=>count($userList),'is_ex'=>'Y']);
                    if($res_balance === false || $res_rookie_user_log === false)
                    {
                        $this->rollback();
                        return false;
                    }
                    //成功，提交事务
                    $this->commit();
                    return array('ref'=>$ref);
                    break;
                default:#发放余额
                    $userBlanceModel = new \Common\Model\UserBalanceRecordModel();
                    $all_money = $usermsg['balance']+$ref;
                    $res_balance_log = $userBlanceModel->addLog($uid, $ref, $all_money, 'bonus3','2',0,0);
                    if($res_balance_log === false)
                    {
                        $this->rollback();
                        return false;
                    }
                    //增加推荐人余额
                    $data_balance=array(
                        'balance'=>$all_money,
                    );
                    $res_balance=$userModel->where("uid='$uid'")->save($data_balance);
                    #记录已发放
                    $res_rookie_user_log = $rookieUserModel->add(['user_id'=>$uid,'rid'=>$rid,'exchange'=>date('Y-m-d H:i:s',time()),'num'=>count($userList),'is_ex'=>'Y']);
                    if($res_balance === false || $res_rookie_user_log === false)
                    {
                        $this->rollback();
                        return false;
                    }
                    //成功，提交事务
                    $this->commit();
                    return array('ref'=>$ref);
                    break;
            }
        }
    }
}
