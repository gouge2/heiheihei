<?php
/**
 * by 翠花 http://www.lailu.shop
 * 618大数据活动看板
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

header('Access-Control-Allow-Origin: *');
class ActivityBoardController extends AuthController
{
    public function index()
    {
        layout(false);
        $this->display();
    }

    //618活动面板数据统计
    public function activity()
    {
        $User=new \Common\Model\UserModel();
        $TbOrder=new \Common\Model\TbOrderModel();
        $JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
        $PddOrder = new \Common\Model\PddOrderModel();
        $VipOrder = new \Common\Model\VipOrderModel();
        //左边顶部数据-用户
        $user_num=$User->count();
        $user_vip_num=$User->where("group_id in (3,4)")->count();
        $user_buy_num=$User->where("is_buy='Y'")->count();
        $user_buy_proportion=($user_buy_num/$user_num)*100;
        $user_buy_proportion=round($user_buy_proportion,2);
        $left_top=array(
            'user_num' => $user_num,
            'user_vip_num' => $user_vip_num,
            'user_buy_num' => $user_buy_num,
            'user_buy_proportion' => $user_buy_proportion,
        );
        //左边中间数据-订单
        //获取淘京拼唯订单50条，按时间倒序
        $tb_sql = "
            SELECT t.num_iid goods_id,t.item_title goods_title,t.alipay_total_price pay_price,u.phone user_phone,t.order_type type,u.phone_province province,t.create_time
            FROM lailu_tb_order as t
            LEFT JOIN lailu_user as u
            ON t.user_id = u.uid
            WHERE t.user_id!='' and t.user_id!='null' and u.phone!='' and u.phone!='null'
            order by create_time desc
            LIMIT 50
            ";
        $tb_list=M()->query($tb_sql);
        $tb_list_new=array();
        foreach ($tb_list as $v){
            if ($v['type']=='天猫'){
                $v['type']=5;
            }else{
                $v['type']=1;
            }
            $v['user_phone']=substr_replace($v['user_phone'],'****',3,4);
            $tb_list_new[]=$v;
        }

        $jd_sql = "
            SELECT j.skuid goods_id,j.skuname goods_title,j.estimatecosprice pay_price,u.phone user_phone,u.phone_province province,FROM_UNIXTIME(j.orderTime/1000, '%Y-%m-%d %H:%i:%s') create_time
            FROM lailu_jingdong_order_detail as j
            LEFT JOIN lailu_user as u
            ON j.user_id = u.uid
            WHERE j.user_id!='' and j.user_id!='null' and u.phone!='' and u.phone!='null'
            order by FROM_UNIXTIME(j.orderTime/1000, '%Y-%m-%d %H:%i:%s') desc
            LIMIT 50
            ";
        $jd_list=M()->query($jd_sql);
        $jd_list_new=array();
        foreach ($jd_list as $v){
            $v['type']=2;
            $v['user_phone']=substr_replace($v['user_phone'],'****',3,4);
            $jd_list_new[]=$v;
        }

        $pdd_sql = "
            SELECT p.goods_id goods_id,p.goods_name goods_title,p.order_amount/100 pay_price,u.phone user_phone,u.phone_province province,FROM_UNIXTIME(p.order_create_time, '%Y-%m-%d %H:%i:%s') create_time
            FROM lailu_pdd_order as p
            LEFT JOIN lailu_user as u
            ON p.user_id = u.uid
            WHERE p.user_id!='' and p.user_id!='null' and u.phone!='' and u.phone!='null'
            order by FROM_UNIXTIME(p.order_create_time, '%Y-%m-%d %H:%i:%s') desc
            LIMIT 50
            ";
        $pdd_list=M()->query($pdd_sql);
        $pdd_list_new=array();
        foreach ($pdd_list as $v){
            $v['type']=3;
            $v['user_phone']=substr_replace($v['user_phone'],'****',3,4);
            $pdd_list_new[]=$v;
        }

        $vip_sql = "
            SELECT v.goodsId goods_id,v.goodsName goods_title,v.commissionTotalCost pay_price,u.phone user_phone,u.phone_province province,FROM_UNIXTIME(v.orderTime/1000, '%Y-%m-%d %H:%i:%s') create_time
            FROM lailu_vip_order as v
            LEFT JOIN lailu_user as u
            ON v.user_id = u.uid
            WHERE v.user_id!='' and v.user_id!='null' and u.phone!='' and u.phone!='null'
            order by FROM_UNIXTIME(v.orderTime/1000, '%Y-%m-%d %H:%i:%s') desc
            LIMIT 50
            ";
        $vip_list=M()->query($vip_sql);
        $vip_list_new=array();
        foreach ($vip_list as $v){
            $v['type']=4;
            $v['user_phone']=substr_replace($v['user_phone'],'****',3,4);
            $vip_list_new[]=$v;
        }
        //对订单进行按时间倒序排序
        $order_list=array_merge($tb_list_new,$jd_list_new,$pdd_list_new,$vip_list_new);
        $date=array_column($order_list,'create_time');
        array_multisort($date,SORT_DESC,$order_list);

        //统计所有订单金额-销售总额
        $tb_order_money=$TbOrder->where("tk_status!='13' and order_type!='天猫'")->sum('alipay_total_price');
        $tm_order_money=$TbOrder->where("tk_status!='13' and order_type='天猫'")->sum('alipay_total_price');
        $jd_order_money=$JingdongOrderDetail->where("validCode in (15,16,17,18)")->sum('estimateCosPrice');
        $pdd_order_money=$PddOrder->where("order_status in (-1,0,1,2,3,5,8)")->sum('order_amount');
        $pdd_order_money/=100;
        $vip_order_money=$VipOrder->where("orderSubStatusName!='已失效'")->sum('commissionTotalCost');

        $tb_order_money=$tb_order_money?$tb_order_money:0;
        $tm_order_money=$tm_order_money?$tm_order_money:0;
        $jd_order_money=$jd_order_money?$jd_order_money:0;
        $vip_order_money=$vip_order_money?$vip_order_money:0;

        //销售总额
        $all_order_money=$tb_order_money+$tm_order_money+$jd_order_money+$pdd_order_money/100+$vip_order_money;
        $all_order_money=$all_order_money?$all_order_money:0;

        //统计淘京拼维订单收益-佣金总额
        $amount_tb=$TbOrder->where("tk_status!='13' and order_type!='天猫'")->sum('tb_commission');
        $amount_tm=$TbOrder->where("tk_status!='13' and order_type='天猫'")->sum('tb_commission');
        $amount_jd=$JingdongOrderDetail->where("validCode in (15,16,17,18)")->sum('actualFee');
        $amount_pdd=$PddOrder->where("order_status in (-1,0,1,2,3,5,8)")->sum('pdd_commission');
        $amount_pdd/=100;
        $amount_vip=$VipOrder->where("orderSubStatusName!='已失效'")->sum('vipCommission');

        $amount_tb=$amount_tb?$amount_tb:0;
        $amount_tm=$amount_tm?$amount_tm:0;
        $amount_jd=$amount_jd?$amount_jd:0;
        $amount_vip=$amount_vip?$amount_vip:0;

        //佣金总额
        $all_order_amount=$amount_tb+$amount_tm+$amount_pdd+$amount_jd+$amount_vip;
        $all_order_amount=$all_order_amount?$all_order_amount:0;

        //统计淘京拼维订单数
        $tb_order_num=$TbOrder->where("tk_status!='13' and order_type!='天猫'")->count();
        $tm_order_num=$TbOrder->where("tk_status!='13' and order_type='天猫'")->count();
        $jd_order_num=$JingdongOrderDetail->where("validCode in (15,16,17,18)")->count();
        $pdd_order_num=$PddOrder->where("order_status in (-1,0,1,2,3,5,8)")->count();
        $vip_order_num=$VipOrder->where("orderSubStatusName!='已失效'")->count();

        $tb_order_num=$tb_order_num?$tb_order_num:0;
        $tm_order_num=$tm_order_num?$tm_order_num:0;
        $jd_order_num=$jd_order_num?$jd_order_num:0;
        $vip_order_num=$vip_order_num?$vip_order_num:0;

        //订单总数
        $all_order_num=$tb_order_num+$tm_order_num+$jd_order_num+$pdd_order_num+$vip_order_num;
        $all_order_num=$all_order_num?$all_order_num:0;

        $order_statistical=array(
            'all' => array(
                'money' => $all_order_money,
                'amount' => $all_order_amount,
                'num' => $all_order_num,
            ),
            'tb' => array(
                'money' => $tb_order_money,
                'amount' => $amount_tb,
                'num' => $tb_order_num,
            ),
            'tm' => array(
                'money' => $tm_order_money,
                'amount' => $amount_tm,
                'num' => $tm_order_num,
            ),
            'jd' => array(
                'money' => $jd_order_money,
                'amount' => $amount_jd,
                'num' => $jd_order_num,
            ),
            'pdd' => array(
                'money' => $pdd_order_money,
                'amount' => $amount_pdd,
                'num' => $pdd_order_num,
            ),
            'vip' => array(
                'money' => $vip_order_money,
                'amount' => $amount_vip,
                'num' => $vip_order_num,
            ),
        );

        //统计省份用户注册排行榜前四
        $province_num_sql = "select phone_province,count(uid) as num from lailu_user where phone_province!='' and phone_province!='null' group by phone_province order by count(uid) desc limit 0,4";
        $province_num=M()->query($province_num_sql);

        //统计出单用户榜前三
        $user_order_num_sql = "select u.phone,count(b.user_id) as num 
                                    from lailu_user_balance_record_tmp b 
                                    left join lailu_user u 
                                    on b.user_id=u.uid 
                                    where b.user_id!='' and b.user_id!='null' 
                                    group by b.user_id 
                                    order by count(b.user_id) desc 
                                    limit 0,3";
        $user_order_num=M()->query($user_order_num_sql);
        $user_order_num_new=array();
        foreach ($user_order_num as $v){
            $v['phone']=substr_replace($v['phone'],'****',3,4);
            $user_order_num_new[]=$v;
        }

        $data=array(
            'left_top'=>$left_top,
            'left_middle'=>$order_list,
            'order_statistical'=>$order_statistical,
            'province_num'=>$province_num,
            'user_order_num_new'=>$user_order_num_new,

        );
        $res=array(
            'code'=>0,
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    //统计省份出单数前十
    public function provinceOrderNum()
    {
        //统计省份出单数前十
        $province_user_num_sql = "select phone_province from lailu_user where phone_province!='' and phone_province!='null' group by phone_province order by count(uid) desc limit 0,10";
        $province_user_num=M()->query($province_user_num_sql);
        $province_user_order_num=array();
        foreach ($province_user_num as $v){
            $province_user_sql = "select uid from lailu_user where phone_province='{$v['phone_province']}'";
            $province_user=M()->query($province_user_sql);
            $province_user_order_num[$v['phone_province']]=0;
            foreach ($province_user as $vv){
                $tb_user_order_num_sql = "select count(user_id) as num from lailu_tb_order where user_id='{$vv['uid']}'";
                $tb_user_order_num=M()->query($tb_user_order_num_sql);
                $province_user_order_num[$v['phone_province']]+=$tb_user_order_num[0]['num'];

                $jd_user_order_num_sql = "select count(user_id) as num from lailu_jingdong_order_detail where user_id='{$vv['uid']}'";
                $jd_user_order_num=M()->query($jd_user_order_num_sql);
                $province_user_order_num[$v['phone_province']]+=$jd_user_order_num[0]['num'];

                $pdd_user_order_num_sql = "select count(user_id) as num from lailu_pdd_order where user_id='{$vv['uid']}'";
                $pdd_user_order_num=M()->query($pdd_user_order_num_sql);
                $province_user_order_num[$v['phone_province']]+=$pdd_user_order_num[0]['num'];

                $vip_user_order_num_sql = "select count(user_id) as num from lailu_vip_order where user_id='{$vv['uid']}'";
                $vip_user_order_num=M()->query($vip_user_order_num_sql);
                $province_user_order_num[$v['phone_province']]+=$vip_user_order_num[0]['num'];
            }

        }
        $province_user_order_num_new=array();
        $a=0;
        foreach ($province_user_order_num as $k=>$v){
            $province_user_order_num_new[$a]['province']=$k;
            $province_user_order_num_new[$a]['order_num']=$v;
            $a++;
        }
        //对订单按数量倒序排序
        $date=array_column($province_user_order_num_new,'order_num');
        array_multisort($date,SORT_DESC,$province_user_order_num_new);

        $data=array(
            'province_user_order_num'=>$province_user_order_num_new,

        );
        $res=array(
            'code'=>0,
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    //订单前十五天数量/销售额/佣金统计
    public function orderStatistical()
    {
        $TbOrder=new \Common\Model\TbOrderModel();
        $JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
        $PddOrder = new \Common\Model\PddOrderModel();
        $VipOrder = new \Common\Model\VipOrderModel();
        $order_fifteen_date=array();
        $order_num_fifteen=array();
        $order_money_fifteen=array();
        $order_amount_fifteen=array();
        for ($i=15;$i>=1;$i--){
            //时间
            $date=date('m-d',time()-60*60*24*$i);
            $order_fifteen_date[]=$date;

            //订单数前十五天
            $tb_order_num=$TbOrder->where("tk_status!='13' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(create_time)")->count();
            $jd_order_num=$JingdongOrderDetail->where("validCode in (15,16,17,18) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->count();
            $pdd_order_num=$PddOrder->where("order_status in (-1,0,1,2,3,5,8) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'))")->count();
            $vip_order_num=$VipOrder->where("orderSubStatusName!='已失效' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->count();
            //订单总数
            $order_num_fifteen[]=$tb_order_num+$jd_order_num+$pdd_order_num+$vip_order_num;

            //订单销售额前十五天
            $tb_order_money=$TbOrder->where("tk_status!='13' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(create_time)")->sum('alipay_total_price');
            $jd_order_money=$JingdongOrderDetail->where("validCode in (15,16,17,18) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->sum('estimateCosPrice');
            $pdd_order_money=$PddOrder->where("order_status in (-1,0,1,2,3,5,8) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'))")->sum('order_amount');
            $pdd_order_money/=100;
            $vip_order_money=$VipOrder->where("orderSubStatusName!='已失效' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->sum('commissionTotalCost');
            //销售总数
            $order_money_fifteen[]=$tb_order_money+$jd_order_money+$pdd_order_money/100+$vip_order_money;

            //订单佣金总额前十五天
            $amount_tb=$TbOrder->where("tk_status!='13' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(create_time)")->sum('tb_commission');
            $amount_jd=$JingdongOrderDetail->where("validCode in (15,16,17,18) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->sum('actualFee');
            $amount_pdd=$PddOrder->where("order_status in (-1,0,1,2,3,5,8) and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'))")->sum('pdd_commission');
            $amount_pdd/=100;
            $amount_vip=$VipOrder->where("orderSubStatusName!='已失效' and DATE_SUB(CURDATE(), INTERVAL {$i} DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->sum('vipCommission');
            //佣金总额
            $order_amount_fifteen[]=$amount_tb+$amount_pdd+$amount_jd+$amount_vip;
        }

        $data=array(
            'order_fifteen_date'=>$order_fifteen_date,
            'order_num_fifteen'=>$order_num_fifteen,
            'order_money_fifteen'=>$order_money_fifteen,
            'order_amount_fifteen'=>$order_amount_fifteen,

        );
        $res=array(
            'code'=>0,
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>