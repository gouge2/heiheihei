<?php
namespace Common\Model;
use Think\Model;
class ShopOrderModel extends Model{
    protected $tableName='ewei_shop_order';

    public function statusInfo($status)
    {
        switch ($status)
        {
            case 1:
                $status=0;
                break;
            case 2:
                $status=1;
                break;
            case 3:
                $status=2;
                break;
            case 4:
                $status=3;
                break;
            case 5:
                $status=3;
                break;
            case 6:
                break;
            case 7:
                break;
            case 8:
                break;
        }
        return $status;
    }

    public function getOne($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }
//$orderstatus = array(
//'-1' => array('css' => 'default', 'name' => '已关闭'),
//'0' => array('css' => 'danger', 'name' => '待付款'),
//'1' => array('css' => 'info', 'name' => '待发货'),
//'2' => array('css' => 'warning', 'name' => '待收货'),
//'3' => array('css' => 'success', 'name' => '已完成')
//);
}