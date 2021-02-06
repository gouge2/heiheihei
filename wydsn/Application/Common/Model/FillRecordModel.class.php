<?php
/**
 * 直播充值记录管理类
 */
namespace Common\Model;
use Think\Model;

class FillRecordModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('user_id','require','充值用户标识不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
        array('deduct','require','抵扣的人民币金额不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('redeem','require','兑换的鹿角金额不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );


    /**
     * 支付宝/微信 支付回调处理
     */
    public function treatFill($order_num, $type)
    {
        $res = false;

        if ($order_num && $type && in_array($type, ['alipay', 'wxpay'])) {
            // 充值订单
            $fr_one  = $this->where(['fill_num' => $order_num, 'is_status' => 'not'])->find();

            if ($fr_one) {
                $User      = new \Common\Model\UserModel();

                $this->startTrans();   // 启用事务
                try {
                    // 鹿角加
                    $User->where(['uid' => $fr_one['user_id']])->setInc('ll_balance', $fr_one['redeem']);

                    // 支付修改
                    $this->where(['id' => $fr_one['id']])->save(['pay_method' => $type, 'is_status' => 'succ', 'pay_time' => date('Y-m-d H:i:s')]);

                    // 事务提交
                    $this->commit();

                    $res    = true;

                } catch(\Exception $e) {
                    // 事务回滚
                    $this->rollback(); 
                }
            }
        }

        return $res;
    }  

    /**
     * 获取充值记录
     */
    public function getFill($uid, $limit, $page, $sort = 'id desc')
    {
        $data       = [];

        if ($uid) {
            $field  = 'deduct as money,add_time';
            $whe    = ['user_id' => $uid, 'is_status' => 'succ'];

            $list   = $this->field($field)->where($whe)->page($page, $limit)->order($sort)->select();

            if ($list) {
                foreach ($list as $val) {
                    $temp               = $val;
                    $temp['money']      = (string)($val['money'] * 0.01);

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