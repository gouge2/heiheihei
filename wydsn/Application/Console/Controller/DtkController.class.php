<?php
namespace Console\Controller;

use Common\Model\SettingModel;
use Think\Controller;

class DtkController extends Controller
{
    #拉取一天淘宝订单
    public function todayTreatTbOrder()
    {
        //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
        $query_type=1;
        //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
        $tk_status='';
        //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
        $order_scene=1;
        $time = I('get.time');

        // 订单查询开始时间，格式：2016-05-23 12:18:22
        $now = date ( 'Y-m-d' );
        //$now='2019-09-05 10:40:00';
        $end_time=$now;
        // 当前时间往前20分钟
        $start_time = strtotime($now)-86400;
        $synTime = $this->getExecutableTime($time);
        $count = 0;
        for ($is=0;$is<count($synTime);$is++)
        {
            //记录拉取淘宝订单时间
            $start_time = $synTime[$is]['start_time'];
            $end_time = $synTime[$is]['end_time'];
            writeLog('拉取淘宝订单时间'.$start_time);

            //推广者角色类型,2:二方，3:三方，不传，表示所有角色
            $member_type='';
            $page_size=100;
            //位点，除第一页之外，都需要传递；前端原样返回。
            $position_index='';
            //跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
            $jump_type=1;

            // 循环查询10万条订单最多，10分钟内最多10万条
            $TbOrder = new \Common\Model\TbOrderModel ();
            $num = 100000 / $page_size;
            // 成功条数

            for($i = 0; $i < $num; $i ++)  {
                $page_no = $i + 1;
                // 淘宝订单接口
                $res_list=$TbOrder->pullOrder($query_type, $tk_status, $order_scene, $start_time, $end_time, $member_type, $page_no, $page_size, $position_index, $jump_type);
                if($res_list['code']==0){
                    $count+=$res_list['data']['count'];
                    $position_index=$res_list['data']['position_index'];
                    $list_num=$res_list['data']['list_num'];
                    if ($list_num == 100)  {
                        // 100条，可能还有更多订单，继续查询
                        continue;
                    } else {
                        // 不超出100条，没有更多结果
                        // 跳出循环
                        break;
                    }
                }else {
                    // 跳出循环
                    break;
                }
            }
        }
        echo '成功执行：' . $count;
    }

    #拆分时间
    public function getExecutableTime($time)
    {
        #如果参数不带时间
        if(empty($time))
        {
            $now = date ( 'Y-m-d' );
            $start_time = strtotime($now)-86400;
            $arr = ['6','11','12'];
            $arr2 = ['11','12','13','14','15','16','17','18','19','20','21','10','09','08'];
            if(in_array(date('m'),$arr) &&in_array(date('d'),$arr2))
            {
                #20分钟拆分
                #180分钟拆分
                $res = $this->splitTime($start_time,0,20,[],$start_time);
                return $res;
            }else{
                #180分钟拆分
                $res = $this->splitTime($start_time,0,180,[],$start_time);
                return $res;
            }
        }else{
            $start_time = strtotime($time);
            $arr = ['6','11','12'];
            $arr2 = ['11','12','13','14','15','16','17','18','19','20','21','10','09','08'];
            if(in_array(date('m',$time),$arr) &&in_array(date('d',$time),$arr2))
            {
                #20分钟拆分
                #180分钟拆分
                $res = $this->splitTime($start_time,0,20,[],$start_time);
                return $res;
            }else{
                #180分钟拆分
                $res = $this->splitTime($start_time,0,180,[],$start_time);
                return $res;
            }
        }
    }

    /**
     * 输出时间
     * @param $start_time       开始时间
     * @param $has              已转换时间
     * @param $minute           转换分钟数
     * @param array $arr        返回数组
     * @return mixed
     */
    public function splitTime($start_time,$has=0,$minute,array $arr,$old_time)
    {
        #计算转换时间
        $now = date ( 'Y-m-d' );
        $all_time =((strtotime($now)-$start_time)/86400)*(1440/$minute);
        if($all_time>$has)
        {
            array_push($arr,['start_time'=>date('Y-m-d H:i:s',$start_time),'end_time'=>date('Y-m-d H:i:s',$start_time+$minute*60)]);
            $has++;
            return $this->splitTime($start_time+$minute*60,$has,$minute,$arr,$old_time);
        }
        return $arr;
    }
}