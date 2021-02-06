<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 小程序支付管理
 */
namespace Common\Model;

use Think\Model;

class UserPayModel extends Model
{
    protected $_validate = array(
        array('pay_type',array(1,2),'值的范围不正确！',2,'in'),
        array('pay_number','1,20','手机号码不超过20个字符！',self::VALUE_VALIDATE,'length'),
        array('pay_name','require','名称不能为空！',self::EXISTS_VALIDATE)

    );

    protected $_auto = array (
        array('add_time','time',1,'function'),
        array('update_time','time',2,'function'),
    );

    /**
     * 获取支付账号
     * @param $uid
     * @param $pay_type
     * @return bool|mixed
     */
    public function getPay($uid, $pay_type)
    {
        $res = $this->field(['pay_type','pay_number','pay_name'])->where(['uid' => $uid, 'pay_type' => $pay_type])->find();
        if (!$res) {
            return false;
        }
        return $res;
    }

    /**
     * 添加更新支付账号
     * @param mixed|string $uid
     * @param array $pay_type
     * @param bool $pay_number
     * @param $pay_name
     * @return bool|mixed
     */
    public function addPay($uid, $pay_type, $pay_number, $pay_name)
    {
        $res = $this->where(['uid' => $uid,'pay_type' => $pay_type])->find();
        $data = array(
            'pay_number' => $pay_number,
            'pay_name' => $pay_name
        );
        if ($res) {
            $resources = $this->where(['uid' =>$uid,'pay_type' => $pay_type])->save($data);
        } else {
            $data['uid'] = $uid;
            $data['pay_type'] = $pay_type;
            $resources = $this->add($data);
        }

        if (!$resources) {
            return false;
        }

        return $resources;
    }
}