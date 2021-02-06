<?php


namespace Common\Model;
use Think\Model;

class HostCommissionModel extends Model
{
    // 验证规则
    protected $_validate = array(
        array('fee_service','is_natural_num','收益比例-扣税必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('fee_plantform','is_natural_num','收益比例-平台必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('fee_sell','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('broker_rate','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('broker_rate2','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('fee_host','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('fee_user','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
    );

    /**
     * 获取会员组信息
     * @param int $group_id
     */
    public function getGroupMsg($group_id = 1) {
        $msg=$this->where("id='$group_id'")->find();
        if($msg!==false)
        {
            return $msg;
        }else {
            return false;
        }
    }
}