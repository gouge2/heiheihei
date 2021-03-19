<?php
/**
 * 翠花币兑换记录管理类
 */
namespace Common\Model;
use Think\Model;

class DeerExtractModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('user_id','require','兑换用户标识不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
        array('deer','require','扣除的翠花币金额不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('rmb','require','得到人民币金额不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );


    /**
     * 获取提取记录
     */
    public function getExtract($uid, $limit, $page, $sort = 'id desc')
    {
        $data           = [];

        if ($uid) {
            $field      = 'balance as money,add_time';
            $whe        = ['user_id' => $uid, 'is_status' => 1];

            $list       = $this->field($field)->where($whe)->page($page, $limit)->order($sort)->select();

            if ($list) {
                foreach ($list as $val) {
                    $temp               = $val;

                    // 时间转时间戳
                    $temp['add_time']   = strtotime($val['add_time']);

                    $data[]             = $temp;
                }
            }
        }

        return $data;
    }

}
?>