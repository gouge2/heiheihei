<?php


namespace Common\Model;
use Think\Model;

class HostUserBrowseModel extends Model
{
    // 验证规则
    protected $_validate = array(
        array('host_id','is_natural_num','收益比例-扣税必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('user_id','is_natural_num','收益比例-平台必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('goods_id','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('live_id','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('from','require','商品来源不能为空！',self::EXISTS_VALIDATE),  //值不为空的时候验证 ，必须是自然数
        array('site_id','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('create_time','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
    );

    public function getUserBrowse($goods_id, $user_id) {
        $msg=$this->where("goods_id='$goods_id' and user_id='$user_id'")->find();
        if($msg!==false)
        {
            return $msg;
        }else {
            return false;
        }
    }
}