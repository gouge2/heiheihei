<?php
/**
 * 认证记录管理类
 */
namespace Common\Model;
use Think\Model;

class RealNameModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('user_id','require','认证用户标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('add_time','require','认证时间不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );
    
    public function getList($where) {
        $list = $this->where($where)->order("add_time desc")->select();
        if($list!==false)
        {
            return $list;
        }else {
            return false;
        }
    }
}
?>