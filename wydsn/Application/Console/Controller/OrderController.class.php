<?php
namespace Console\Controller;

use Console\Common\BaseController;
use Common\Model\OrderModel;
use Common\Model\ShopOrderModel;

class OrderController extends BaseController
{
    #自动关闭未付款订单
    public function closeOrder()
    {
        $order = new OrderModel();
        $shopOrder = new ShopOrderModel();
        $rebate_time=CLOSEORDER;
        if(empty($rebate_time))
        {
            echo '执行成功0';
            die;
        }
        $list = $order->where("status=1 and DATE_ADD(create_time, INTERVAL {$rebate_time} DAY) <= CURDATE()")->select();
        for ($i=0;$i<count($list);$i++)
        {
            #改为关闭状态
            $order->startTrans();
            $order_id = $list[$i]['id'];
            $res = $order->where('id={$order_id}')->save(['status'=>-1]);
            if(false === $res)
            {
                $order->rollback();
                continue;
            }
            #如果是商户端的订单 还要在更新一下商户端的状态
            if($list[$i]['ren_order_id']>0)
            {
                $shop_order_id = $list[$i]['ren_order_id'];
                $res1 = $shopOrder->where('id={$shop_order_id}')->save(['status'=>-1]);
                if($res1 === false)
                {
                    $order->rollback();
                    continue;
                }
                $order->commit();
                $count++;
            }else{
                $order->commit();
                $count++;
            }
        }
        echo '执行成功'.$count;
    }

    #自动对订单进行确认收货
    public function receiveOrder()
    {
        $order = new OrderModel();
        $shopOrder = new ShopOrderModel();
        $rebate_time=RECEIVE;
        if(empty($rebate_time))
        {
            echo '执行成功0';
            die;
        }
        $list = $order->where("status=3 and DATE_ADD(deliver_time, INTERVAL {$rebate_time} DAY) <= CURDATE()")->select();
        for ($i=0;$i<count($list);$i++)
        {
            #改为确认收货状态
            $order->startTrans();
            $order_id = $list[$i]['id'];
            $res = $order->where('id={$order_id}')->save(['finish_time'=>date('Y-m-d H:i:s'),'status'=>4]);
            if(false === $res)
            {
                $order->rollback();
                continue;
            }
            #如果是商户端的订单 还要在更新一下商户端的状态
            if($list[$i]['ren_order_id']>0)
            {
                $shop_order_id = $list[$i]['ren_order_id'];
                $res1 = $shopOrder->where('id={$shop_order_id}')->save(['status'=>3,'finishtime'=>time()]);
                if($res1 === false)
                {
                    $order->rollback();
                    continue;
                }
                $order->commit();
                $count++;
            }else{
                $order->commit();
                $count++;
            }
        }
    }
}