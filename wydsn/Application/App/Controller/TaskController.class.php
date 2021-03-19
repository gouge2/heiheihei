<?php
/**
 * by 翠花 http://www.lailu.shop
 * 任务管理接口
 */
namespace App\Controller;

class TaskController
{

    // 处理淘宝临时订单
    // 每1分钟执行一次
    // 网址：http://taobao.mjuapp.com/app.php/Task/treatTbOrder
    // 自动执行命令：*/1 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatTbOrder
    public function treatTbOrder()
    {
        //记录处理淘宝临时订单时间
        $now=date('Y-m-d H:i:s');
        writeLog('记录处理淘宝临时订单时间：'.$now);

        //保存到临时表中完成，
        $TbOrderTmp=new \Common\Model\TbOrderTmpModel();
        $TbOrderTmp->treat();

        //自动执行-淘宝订单返利
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $TbOrder=new \Common\Model\TbOrderModel();
                    $orderList=$TbOrder->where("user_id!='' and user_id!='null' and status=1 and tk_status=3 and date_format(earning_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('trade_id,total_commission_fee')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){

                            $TbOrder->treat ( $l['trade_id'], $l['total_commission_fee'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $TbOrder=new \Common\Model\TbOrderModel();
                $orderList=$TbOrder->where("user_id!='' and user_id!='null' and status=1 and tk_status=3 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(earning_time)")->field('trade_id,total_commission_fee')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $TbOrder->treat ( $l['trade_id'], $l['total_commission_fee'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }
    }

    //自动执行-淘宝订单返利--合并到处理淘宝临时订单里面执行，不用单独加任务
    public function rebateTbOrder()
    {
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $TbOrder=new \Common\Model\TbOrderModel();
                    $orderList=$TbOrder->where("user_id!='' and user_id!='null' and status=1 and tk_status=3 and date_format(earning_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('trade_id,total_commission_fee')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $TbOrder->treat ( $l['trade_id'], $l['total_commission_fee'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $TbOrder=new \Common\Model\TbOrderModel();
                $orderList=$TbOrder->where("user_id!='' and user_id!='null' and status=1 and tk_status=3 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(earning_time)")->field('trade_id,total_commission_fee')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $TbOrder->treat ( $l['trade_id'], $l['total_commission_fee'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }
    }

	/**
	 * 拉取淘宝常规订单-已付款
	 * 每5分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrder
	 * 自动执行命令：0,5,10,15,20,25,30,35,40,45,50,55,58 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrder&day=-1&hour=-1
	 */
    public function treatOrder()
	{
	    $day = I('get.day/d');
        $hour = I('get.hour/d');
        $minute = I('get.minute/d');

        $day = !empty($day) ? $day : "";
        $hour = !empty($hour) ? $hour : "";
        $minute = !empty($minute) ? $minute : "-20";

	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-09-05 10:40:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', $day, $hour, $minute);
	    //记录拉取淘宝订单时间
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
		$count = 0;
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
		echo '成功执行：' . $count;
	}

	/**
	 * 拉取淘宝常规订单-已结算
	 * 每6分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrder2
	 * 自动执行命令：1,7,13,19,25,31,37,43,49,55 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrder2
	 */
	public function treatOrder2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=3;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝结算订单时间'.$start_time);

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
		$count = 0;
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
		echo '成功执行：' . $count;
	}

	/**
	 * 拉取淘宝渠道订单-已付款
	 * 每5分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderR
	 * 自动执行命令：0,5,10,15,20,25,30,35,40,45,50,55,58 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderR
	 */
	public function treatOrderR()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道订单时间'.$start_time);

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
	    $count = 0;
	    for($i = 0; $i < $num; $i ++) {
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
	    echo '成功执行：' . $count;
	}

	/**
	 * 拉取淘宝渠道订单-已结算
	 * 每10分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderR2
	 * 自动执行命令：1,7,13,19,25,31,37,43,49,55 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderR2
	 */
	public function treatOrderR2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=3;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道结算订单时间'.$start_time);

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
	    $count = 0;
	    for($i = 0; $i < $num; $i ++)
	    {
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
	    echo '成功执行：' . $count;
	}

	// 拉取前一天所有淘宝订单
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderYesterday
	// 每天00:10分执行一次
	// 自动执行命令：10 0 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderYesterday
	public function treatOrderYesterday()
	{
		writeLog('拉取前一天所有淘宝订单');

		//查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
		$query_type=2;
		//淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
		$tk_status='';
		//场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
		$order_scene=1;

		//推广者角色类型,2:二方，3:三方，不传，表示所有角色
		$member_type='';
		$page_size=100;

		//跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
		$jump_type=1;
		//拉取前一天所有淘宝订单
		$Time = new \Common\Model\TimeModel ();
		$now=date("Y-m-d",strtotime("-2 day")).' 23:40:00';
		$start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '+20');

		$TbOrder = new \Common\Model\TbOrderModel ();
		$page_size = 100;
		//循环72次=24小时*3
		for($ti=0;$ti<72;$ti++) {
			//每20分钟加一次
		    $end_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+20');

			// 循环查询10万条订单最多，10分钟内最多10万条
			$num = 100000 / $page_size;
			// 成功条数
			$count = 0;
			for($i = 0; $i < $num; $i ++) {
				$page_no = $i + 1;
				// 淘宝订单接口
				//位点，除第一页之外，都需要传递；前端原样返回。
				$position_index='';
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

			$start_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+20');

		}
	}

	/**
	 * 拉取淘宝常规失效订单
	 * 每3小时执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderClose
	 * 自动执行命令：0 0,2,4,6,8,10,12,14,16,18,20,22 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderClose
	 */
	public function treatOrderClose()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    // 当前时间往前1天
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '-1');
	    //3小时后
	    $end_time = $Time->getAfterDateTime($now,'2','','','','+3');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝常规失效订单时间'.$start_time);

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
	    $count = 0;
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
	    echo '成功执行：' . $count;
	}

	/**
	 * 拉取淘宝渠道失效订单
	 * 每3小时执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderClose2
	 * 自动执行命令：0 0,2,4,6,8,10,12,14,16,18,20,22 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderClose2
	 */
	public function treatOrderClose2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;

	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    // 当前时间往前1天
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '-1');
	    //3小时后
	    $end_time = $Time->getAfterDateTime($now,'2','','','','+3');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道失效订单时间'.$start_time);

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
	    $count = 0;
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
	    echo '成功执行：' . $count;
	}

	// 自动执行订单-10分钟一次
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatPddOrder
	// 每10分钟执行一次
	// 自动执行命令：0,9,18,27,36,45,54 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatPddOrder
	public function treatPddOrder()
	{
		// 订单查询截止时间
		$end_update_time=time();
		// 订单查询开始时间
		$Time = new \Common\Model\TimeModel ();
		$now = date ( 'Y-m-d H:i:s' );
		// 当前时间往前30分钟
		$start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '-30');
		$start_update_time = strtotime($start_time);
		$page_size = 100;
		// 循环查询10万条订单最多，30分钟内最多10万条
		$PddOrder = new \Common\Model\PddOrderModel();
		$User = new \Common\Model\UserModel ();
        $UserGroup=new \Common\Model\UserGroupModel();
        $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
        $UserExpRecord=new \Common\Model\UserExpRecordModel();
        $HostTreatModel = new \Common\Model\HostTreatModel();
		// 拼多多订单接口
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$num = 100000 / $page_size;
		// 成功条数
		$count = 0;
		for($i = 0; $i < $num; $i ++) {
			$page = $i + 1;
			$res_pdd=$pdd->getOrderList($start_update_time,$end_update_time,$page_size,$page,'false');
			if ($res_pdd ['data']['order_list']) {
				// 本次查询有结果
				// 处理所有的订单
				foreach ( $res_pdd ['data']['order_list'] as $l ) {
					// 判断订单是否存在，存在不处理
					$order_sn = $l ['order_sn'];
					$res_exist = $PddOrder->where ( "order_sn='$order_sn'" )->find ();
                    $user_id = $l ['custom_parameters'];
                    $host = $HostTreatModel->getHost($user_id, $l ['goods_id']);
					if ($res_exist) {
						// 存在
						// 修改订单的一些重要参数
						$data_o = array (
								'user_id' => $user_id,
								'order_sn' => $l ['order_sn'],
								'goods_id' => $l ['goods_id'],
								'goods_name' => $l ['goods_name'],
								'goods_thumbnail_url' => $l ['goods_thumbnail_url'],
								'goods_quantity' => $l ['goods_quantity'],
								'goods_price' => $l ['goods_price'],
								'order_amount' => $l ['order_amount'],
								'promotion_rate' => $l ['promotion_rate'],
								'promotion_amount' => $l ['promotion_amount'],//平台佣金（分）
								'pdd_commission' => $l ['promotion_amount'],//拼多多佣金（分）
								'batch_no' => $l ['batch_no'],
								'order_status' => $l ['order_status'],
								'order_status_desc' => $l ['order_status_desc'],
								'order_pay_time' => $l ['order_pay_time'],
								'order_group_success_time' => $l ['order_group_success_time'],
								'order_receive_time' => $l ['order_receive_time'],
								'order_verify_time' => $l ['order_verify_time'],
								'order_settle_time' => $l ['order_settle_time'],
								'order_modify_at' => $l ['order_modify_at'],
								'custom_parameters' => $l ['custom_parameters'],
								'pid' => $l ['pid'],
                                'host_id' => $host['host_id'],
                                'referrer1_id' => $host['referrer1_id'],
                                'referrer2_id' => $host['referrer2_id'],
						);
						$res_order = $PddOrder->where ( "order_sn='$order_sn'" )->save ( $data_o );

                        //如果之前没有所属用户，再做一次预估统计和消息推送
                        $res_exist['user_id'] = '';
                        if ($user_id and $res_exist['user_id']=='') {
                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            //给直接推荐人加经验值
                            if($userMsg['referrer_id']) {
                                //是否购物
                                $old_is_buy=$userMsg['is_buy'];
                                if($old_is_buy=='N') {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    //判断推荐人是否可以升级为VIP
                                    $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                    $data_referrer=array(
                                        'exp'=>$new_exp
                                    );
                                    //判断推荐人应该升级到那个会员组
                                    //大于当前会员组，并且小于新经验值的最大值
                                    $group_id=$referrerMsg['group_id'];
                                    $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                    //升级到该会员组增加条件，佣金条件
                                    //查询该用户总佣金
                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                    $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                    if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                        $data_referrer['group_id']=$res_group['id'];
                                        $data_referrer['expiration_date']=null;
                                        $data_referrer['is_forever']='Y';
                                    }
                                    $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                    //设置用户为已购物
                                    $data_user=array(
                                        'is_buy'=>'Y',//是否购物，Y是
                                    );
                                    $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                    //保存经验值变动记录-首次购物
                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                }
                            }

                            //极光推送消息
                            Vendor('jpush.jpush','','.class.php');
                            $jpush=new \jpush();
                            $alias=$user_id;//推送别名
                            $title=APP_NAME.'通知您有新订单';
                            $content='您有一笔新订单：'.$l ['goods_name'];
                            $key='order';
                            $value='pdd';
                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            if($userMsg['group_id']=='1') {
                                //普通会员订单，才给上级推送
                                if($userMsg['referrer_id']) {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    if($referrerMsg['group_id']!='1') {
                                        $alias=$referrer_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$l ['goods_name'];
                                        $key='order';
                                        $value='pdd1';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                                    }

                                    if($referrerMsg['referrer_id']) {
                                        $referrer_id2=$referrerMsg['referrer_id'];
                                        $referrerMsg2=$User->getUserMsg($referrer_id2);
                                        if($referrerMsg2['group_id']!='1') {
                                            $alias=$referrer_id2;//推送别名
                                            $title=APP_NAME.'通知您有新订单';
                                            $content='您有一笔新订单：'.$l ['goods_name'];
                                            $key='order';
                                            $value='pdd2';
                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                        }
                                    }
                                }
                            }

                            //对订单做预估收入处理
                            $res_treat_tmp = $PddOrder->treat_tmp ( $l['order_sn'], $l ['promotion_amount'] );
                        }

//						// 判断订单状态，如果尚未结算，给用户返利
//						// 原来未结算，现在结算的订单进行返利
//						if ($res_exist ['status'] == '1' and $l ['order_status'] == '5') {
//							// 尚未结算，给用户返利
//							if($user_id) {
//								// 用户存在，给对应用户返利
//								$res_treat = $PddOrder->treat ( $order_sn, $l ['promotion_amount'] );
//							}else {
//								// 不存在对应用户，不去处理
//							}
//						} else {
//							// 已结算，不处理
//						}

                        //针对已经存在，失效的订单，删除预估记录
                        if($l ['order_status'] == '4'){
                            $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$order_sn' and type='3'")->delete();
                        }

						// 成功执行次数
						$count ++;
					} else {
						//不存在
						//$user_id = $l['custom_parameters'];
						$data = array (
								'user_id' => $user_id,
								'order_sn' => $l ['order_sn'],
								'goods_id' => $l ['goods_id'],
								'goods_name' => $l ['goods_name'],
								'goods_thumbnail_url' => $l ['goods_thumbnail_url'],
								'goods_quantity' => $l ['goods_quantity'],
								'goods_price' => $l ['goods_price'],
								'order_amount' => $l ['order_amount'],
								'promotion_rate' => $l ['promotion_rate'],
								'promotion_amount' => $l ['promotion_amount'],//平台佣金（分）
								'pdd_commission' => $l ['promotion_amount'],//拼多多佣金（分）
								'batch_no' => $l ['batch_no'],
								'order_status' => $l ['order_status'],
								'order_status_desc' => $l ['order_status_desc'],
								'order_create_time' => $l ['order_create_time'],
								'order_pay_time' => $l ['order_pay_time'],
								'order_group_success_time' => $l ['order_group_success_time'],
								'order_receive_time' => $l ['order_receive_time'],
								'order_verify_time' => $l ['order_verify_time'],
								'order_settle_time' => $l ['order_settle_time'],
								'order_modify_at' => $l ['order_modify_at'],
								'match_channel' => $l ['match_channel'],
								'type' => $l ['type'],
								'group_id' => $l ['group_id'],
								'auth_duo_id' => $l ['auth_duo_id'],
								'zs_duo_id' => $l ['zs_duo_id'],
								'custom_parameters' => $l ['custom_parameters'],
								'cps_sign' => $l ['cps_sign'],
								'url_last_generate_time' => $l ['url_last_generate_time'],
								'point_time' => $l ['point_time'],
								'return_status' => $l ['return_status'],
								'pid' => $l ['pid'],
								'status' => '1',  // 是否结算给用户，1未结算，2已结算
                                'host_id' => $host['host_id'],
                                'referrer1_id' => $host['referrer1_id'],
                                'referrer2_id' => $host['referrer2_id'],
						);
						// 保存订单
						$res_add = $PddOrder->add ( $data );
//						// 给用户返利
//						if ($l ['order_status'] == '5') {
//							// 只有结算订单才给用户返利
//							if ($user_id) {
//								// 用户存在，给对应用户返利
//								$res_treat = $PddOrder->treat ( $l['order_sn'], $l ['promotion_amount'] );
//							}
//						}

						if ($user_id) {
                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            //给直接推荐人加经验值
                            if($userMsg['referrer_id']) {
                                //是否购物
                                $old_is_buy=$userMsg['is_buy'];
                                if($old_is_buy=='N') {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    //判断推荐人是否可以升级为VIP
                                    $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                    $data_referrer=array(
                                        'exp'=>$new_exp
                                    );
                                    //判断推荐人应该升级到那个会员组
                                    //大于当前会员组，并且小于新经验值的最大值
                                    $group_id=$referrerMsg['group_id'];
                                    $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                    //升级到该会员组增加条件，佣金条件
                                    //查询该用户总佣金
                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                    $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                    if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                        $data_referrer['group_id']=$res_group['id'];
                                        $data_referrer['expiration_date']=null;
                                        $data_referrer['is_forever']='Y';
                                    }
                                    $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                    //设置用户为已购物
                                    $data_user=array(
                                        'is_buy'=>'Y',//是否购物，Y是
                                    );
                                    $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                    //保存经验值变动记录-首次购物
                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                }
                            }

							//极光推送消息
							Vendor('jpush.jpush','','.class.php');
							$jpush=new \jpush();
							$alias=$user_id;//推送别名
							$title=APP_NAME.'通知您有新订单';
							$content='您有一笔新订单：'.$l ['goods_name'];
							$key='order';
							$value='pdd';
							$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

							//给推荐人推送
							$userMsg=$User->getUserMsg($user_id);
							if($userMsg['group_id']=='1') {
								//普通会员订单，才给上级推送
								if($userMsg['referrer_id']) {
									$referrer_id=$userMsg['referrer_id'];
									$referrerMsg=$User->getUserMsg($referrer_id);
									if($referrerMsg['group_id']!='1') {
										$alias=$referrer_id;//推送别名
										$title=APP_NAME.'通知您有新订单';
										$content='您有一笔新订单：'.$l ['goods_name'];
										$key='order';
										$value='pdd1';
										$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

									}

									if($referrerMsg['referrer_id']) {
										$referrer_id2=$referrerMsg['referrer_id'];
										$referrerMsg2=$User->getUserMsg($referrer_id2);
										if($referrerMsg2['group_id']!='1') {
											$alias=$referrer_id2;//推送别名
											$title=APP_NAME.'通知您有新订单';
											$content='您有一笔新订单：'.$l ['goods_name'];
											$key='order';
											$value='pdd2';
											$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
										}
									}
								}
							}

							//对订单做预估收入处理
							$res_treat_tmp = $PddOrder->treat_tmp ( $l['order_sn'], $l ['promotion_amount'] );
						}
						// 成功次数
						$count ++;
					}
				}
				$list_num = count ( $res_pdd ['data']['order_list'] );
				if ($list_num == 100) {
					// 100条，可能还有更多订单，继续查询
					continue;
				} else {
					// 不超出100条，没有更多结果
					// 跳出循环
					break;
				}
			} else {
				// 本次查询无结果
				// 跳出循环
				break;
			}
		}

        //自动执行-拼多多订单返利
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $PddOrder = new \Common\Model\PddOrderModel();
                    $orderList=$PddOrder->where("user_id!='' and user_id!='null' and status=1 and order_status=5 and date_format(FROM_UNIXTIME(order_settle_time,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('order_sn,pdd_commission')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $PddOrder->treat ( $l['order_sn'], $l['pdd_commission'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $PddOrder = new \Common\Model\PddOrderModel();
                $orderList=$PddOrder->where("user_id!='' and user_id!='null' and status=1 and order_status=5 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(FROM_UNIXTIME(order_settle_time,'%Y-%m-%d %H:%i'))")->field('order_sn,pdd_commission')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $PddOrder->treat ( $l['order_sn'], $l['pdd_commission'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }

		echo '成功执行：' . $count;
	}

    //自动执行-拼多多订单返利--合并到订单拉取里面执行，不用单独加任务
    public function rebatePddOrder()
    {
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $PddOrder = new \Common\Model\PddOrderModel();
                    $orderList=$PddOrder->where("user_id!='' and user_id!='null' and status=1 and order_status=5 and date_format(FROM_UNIXTIME(order_settle_time,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('order_sn,pdd_commission')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $PddOrder->treat ( $l['order_sn'], $l['pdd_commission'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $PddOrder = new \Common\Model\PddOrderModel();
                $orderList=$PddOrder->where("user_id!='' and user_id!='null' and status=1 and order_status=5 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(FROM_UNIXTIME(order_settle_time,'%Y-%m-%d %H:%i'))")->field('order_sn,pdd_commission')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $PddOrder->treat ( $l['order_sn'], $l['pdd_commission'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }
    }

	// 自动执行订单-10分钟一次
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatJdOrder
	// 每10分钟执行一次
	// 自动执行命令：0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatJdOrder
	public function treatJdOrder()
	{
		// 订单查询开始时间
		$Time = new \Common\Model\TimeModel ();
		$now = date ( 'Y-m-d H:i:s' );
		// 当前时间往前10分钟
		$start_time = $Time->getAfterDateTime ( $now, $type = '2', $year = '', $month = '', $day = '', $hour = '', $minute = '-10', $second = '', $week = '' );
		$start_update_time = strtotime($start_time);
		$time=date('YmdH',$start_update_time);
		//$time='2018120922';
		$page_size = 500;
		// 循环查询10万条订单最多，1小时内最多10万条
		$JingdongOrder = new \Common\Model\JingdongOrderModel();
		$JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
		$User = new \Common\Model\UserModel ();
        $UserGroup=new \Common\Model\UserGroupModel();
        $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
        $UserExpRecord=new \Common\Model\UserExpRecordModel();
		$num = 100000 / $page_size;
		// 成功条数
		$count = 0;
		Vendor('JingDong.JdUnion','','.class.php');
		$JdUnion=new \JdUnion();
		for($i = 0; $i < $num; $i ++)
		{
			$page = $i + 1;
			// 京东订单接口
			//$res_jd=$JindDong->queryOrderList($time,$page,$page_size);

			$res_jd=$JdUnion->queryOpenOrders($time,$page,$page_size);
			if ($res_jd ['data'])
			{
				// 本次查询有结果
				// 处理所有的订单
				foreach ( $res_jd ['data'] as $l )
				{
					// 判断订单是否存在，存在不处理
					$orderId = $l ['orderId'];
					$res_exist = $JingdongOrder->where ( "orderId='$orderId'" )->find ();
					if ($res_exist)
					{
						// 存在
						// 修改订单的一些重要参数
						$data = array (
								'finishTime' => $l ['finishTime'],
								'orderEmt' => $l ['orderEmt'],
								'orderTime' => $l ['orderTime'],
								'parentId' => $l ['parentId'],
								'payMonth' => $l ['payMonth'],
								'plus' => $l ['plus'],
								'popId' => $l ['popId'],
								'unionId' => $l ['unionId'],
								'ext1' => $l ['unionUserName'],
								'validCode' => $l ['validCode'],
						);
						// 保存订单
						$res_save = $JingdongOrder->where("orderId='$orderId'")->save( $data );
						// 查询订单详情
						$order_id=$res_exist['id'];
						$detailList=$JingdongOrderDetail->where("order_id='$order_id'")->select();
						$num2=count($detailList);
						for($j=0;$j<$num2;$j++)
						{
							if($detailList[$j]['status']=='1')
							{
								//未结算订单才进行处理
								// 修改订单的一些重要参数
								$gl=$l['skuList'][$j];
								$jd_pid = $gl['positionId'];
								$user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
								$data_detail=array(
										'user_id'=>$user_id,
										'actualCosPrice'=>$gl['actualCosPrice'],
										'actualFee'=>$gl['actualFee'],
										'commissionRate'=>$gl['commissionRate'],
										'estimateCosPrice'=>$gl['estimateCosPrice'],
										'estimateFee'=>$gl['estimateFee'],
										'finalRate'=>$gl['finalRate'],
										'cid1'=>$gl['firstLevel'],
										'frozenSkuNum'=>$gl['frozenSkuNum'],
										'pid'=>$gl['pid'],
										'positionId'=>$gl['positionId'],
										'price'=>$gl['price'],
										'payPrice'=>$gl['payPrice'],
										'cid2'=>$gl['secondLevel'],
										'siteId'=>$gl['siteId'],
										'skuId'=>$gl['skuId'],
										'skuName'=>$gl['skuName'],
										'skuNum'=>$gl['skuNum'],
										'skuReturnNum'=>$gl['skuReturnNum'],
										'subSideRate'=>$gl['subSideRate'],
										'subsidyRate'=>$gl['subsidyRate'],
										'cid3'=>$gl['thirdLevel'],
										'unionAlias'=>$gl['unionAlias'],
										'unionTag'=>$gl['unionTag'],
										'unionTrafficGroup'=>$gl['unionTrafficGroup'],
										'validCode'=>$gl['validCode'],
										'subUnionId'=>$gl['subUnionId'],
										'traceType'=>$gl['traceType'],
										'payMonth'=>$gl['payMonth'],
										'popId'=>$gl['popId'],
										'ext1'=>$gl['ext1'],
										'orderTime' => $l ['orderTime'],
								);
								$order_detail_id=$detailList[$j]['id'];
								//开启事务
								$JingdongOrder->startTrans();
								$res_od=$JingdongOrderDetail->where("id='$order_detail_id'")->save($data_detail);
								if($res_od!==false)
								{
//									// 给用户返利
//								    if (($gl ['validCode'] == '17' or $gl ['validCode'] == '18') and !empty($l ['payMonth']) ) {
//								        //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
//										// 只有结算订单才给用户返利
//										if ($user_id) {
//											// 用户存在，给对应用户返利
//											$res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
//										}
//									}

                                    //如果之前没有所属用户，再做一次预估统计和消息推送
                                    if ($user_id and $res_exist['user_id']=='')
                                    {
                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        //给直接推荐人加经验值
                                        if($userMsg['referrer_id']) {
                                            //是否购物
                                            $old_is_buy=$userMsg['is_buy'];
                                            if($old_is_buy=='N') {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                //判断推荐人是否可以升级为VIP
                                                $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                                $data_referrer=array(
                                                    'exp'=>$new_exp
                                                );
                                                //判断推荐人应该升级到那个会员组
                                                //大于当前会员组，并且小于新经验值的最大值
                                                $group_id=$referrerMsg['group_id'];
                                                $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                                //升级到该会员组增加条件，佣金条件
                                                //查询该用户总佣金
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                                if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                                    $data_referrer['group_id']=$res_group['id'];
                                                    $data_referrer['expiration_date']=null;
                                                    $data_referrer['is_forever']='Y';
                                                }
                                                $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                                //设置用户为已购物
                                                $data_user=array(
                                                    'is_buy'=>'Y',//是否购物，Y是
                                                );
                                                $res_buy=$User->where("uid='$user_id'")->save($data_user);
                                                $checkFirst = $JingdongOrderDetail->where("user_id='$user_id'")->select();
                                                if (!$checkFirst) {
                                                    //保存经验值变动记录-首次购物
                                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                                }
                                            }
                                        }

                                        //极光推送消息
                                        Vendor('jpush.jpush','','.class.php');
                                        $jpush=new \jpush();
                                        $alias=$user_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$gl['skuName'];
                                        $key='order';
                                        $value='jingdong';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        if($userMsg['group_id']=='1' or $userMsg['group_id']=='2')
                                        {
                                            //普通会员订单，才给上级推送
                                            if($userMsg['referrer_id'])
                                            {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                if($referrerMsg['group_id']!='1')
                                                {
                                                    $alias=$referrer_id;//推送别名
                                                    $title=APP_NAME.'通知您有新订单';
                                                    $content='您有一笔新订单：'.$gl['skuName'];
                                                    $key='order';
                                                    $value='jingdong1';
                                                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                }

                                                if($referrerMsg['referrer_id'])
                                                {
                                                    $referrer_id2=$referrerMsg['referrer_id'];
                                                    $referrerMsg2=$User->getUserMsg($referrer_id2);
                                                    if($referrerMsg2['group_id']!='1')
                                                    {
                                                        $alias=$referrer_id2;//推送别名
                                                        $title=APP_NAME.'通知您有新订单';
                                                        $content='您有一笔新订单：'.$gl['skuName'];
                                                        $key='order';
                                                        $value='jingdong2';
                                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                    }
                                                }
                                            }
                                        }

                                        //对订单做预估收入处理-付款时候得不到最终佣金，只能用预估佣金去计算
                                        $res_treat_tmp = $JingdongOrderDetail->treat_tmp ( $order_detail_id, $gl ['estimateFee'] );

                                    }

                                    //针对已经存在，失效的订单，删除预估记录
                                    if($gl ['validCode'] != '15' and $gl ['validCode'] != '16' and $gl ['validCode'] != '17' and $gl ['validCode'] != '18'){
                                        $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$order_detail_id' and type='2'")->delete();
                                    }

									//提交事务
									$JingdongOrder->commit();
								}else {
									//回滚
									$JingdongOrder->rollback();
									exit();
								}
							}
						}
						// 成功执行次数
						$count ++;
					} else {
						//不存在
						$data = array (
								'finishTime' => $l ['finishTime'],
								'orderEmt' => $l ['orderEmt'],
								'orderId' => $l ['orderId'],
								'orderTime' => $l ['orderTime'],
								'parentId' => $l ['parentId'],
								'payMonth' => $l ['payMonth'],
								'plus' => $l ['plus'],
								'popId' => $l ['popId'],
								'unionId' => $l ['unionId'],
								'ext1' => $l ['unionUserName'],
								'validCode' => $l ['validCode'],
						);
						//开启事务
						$JingdongOrder->startTrans();
						// 保存订单
						$res_add = $JingdongOrder->add ( $data );
						if($res_add!==false)
						{
							//保存订单详情
							$order_id=$res_add;
							foreach ($l['skuList'] as $gl)
							{
								$jd_pid = $gl['positionId'];
								$user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
								$data_detail=array(
										'order_id'=>$order_id,
										'user_id'=>$user_id,
										'actualCosPrice'=>$gl['actualCosPrice'],
										'actualFee'=>$gl['actualFee'],
										'commissionRate'=>$gl['commissionRate'],
										'estimateCosPrice'=>$gl['estimateCosPrice'],
										'estimateFee'=>$gl['estimateFee'],
										'finalRate'=>$gl['finalRate'],
										'cid1'=>$gl['firstLevel'],
										'frozenSkuNum'=>$gl['frozenSkuNum'],
										'pid'=>$gl['pid'],
										'positionId'=>$gl['positionId'],
										'price'=>$gl['price'],
										'payPrice'=>$gl['payPrice'],
										'cid2'=>$gl['secondLevel'],
										'siteId'=>$gl['siteId'],
										'skuId'=>$gl['skuId'],
										'skuName'=>$gl['skuName'],
										'skuNum'=>$gl['skuNum'],
										'skuReturnNum'=>$gl['skuReturnNum'],
										'subSideRate'=>$gl['subSideRate'],
										'subsidyRate'=>$gl['subsidyRate'],
										'cid3'=>$gl['thirdLevel'],
										'unionAlias'=>$gl['unionAlias'],
										'unionTag'=>$gl['unionTag'],
										'unionTrafficGroup'=>$gl['unionTrafficGroup'],
										'validCode'=>$gl['validCode'],
										'subUnionId'=>$gl['subUnionId'],
										'traceType'=>$gl['traceType'],
										'payMonth'=>$gl['payMonth'],
										'popId'=>$gl['popId'],
										'ext1'=>$gl['ext1'],
										'status'=>'1',//未结算
										'orderTime' => $l ['orderTime'],
								);
								$res_od=$JingdongOrderDetail->add($data_detail);
								if($res_od!==false)
								{
									$order_detail_id=$res_od;
//									// 给用户返利
//									if (($gl ['validCode'] == '17' or $gl ['validCode'] == '18') and !empty($l ['payMonth']) ) {
//									    //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
//										// 只有结算订单才给用户返利
//										if ($user_id) {
//											// 用户存在，给对应用户返利
//											$res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
//										}
//									}

									if ($user_id)
									{
                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        //给直接推荐人加经验值
                                        if($userMsg['referrer_id']) {
                                            //是否购物
                                            $old_is_buy=$userMsg['is_buy'];
                                            if($old_is_buy=='N') {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                //判断推荐人是否可以升级为VIP
                                                $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                                $data_referrer=array(
                                                    'exp'=>$new_exp
                                                );
                                                //判断推荐人应该升级到那个会员组
                                                //大于当前会员组，并且小于新经验值的最大值
                                                $group_id=$referrerMsg['group_id'];
                                                $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                                //升级到该会员组增加条件，佣金条件
                                                //查询该用户总佣金
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                                if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                                    $data_referrer['group_id']=$res_group['id'];
                                                    $data_referrer['expiration_date']=null;
                                                    $data_referrer['is_forever']='Y';
                                                }
                                                $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                                //设置用户为已购物
                                                $data_user=array(
                                                    'is_buy'=>'Y',//是否购物，Y是
                                                );
                                                $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                                $checkFirst = $JingdongOrderDetail->where("user_id='$user_id'")->select();
                                                if (!$checkFirst) {
                                                    //保存经验值变动记录-首次购物
                                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                                }
                                            }
                                        }

										//极光推送消息
										Vendor('jpush.jpush','','.class.php');
										$jpush=new \jpush();
										$alias=$user_id;//推送别名
										$title=APP_NAME.'通知您有新订单';
										$content='您有一笔新订单：'.$gl['skuName'];
										$key='order';
										$value='jingdong';
										$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

										//给推荐人推送
										$userMsg=$User->getUserMsg($user_id);
										if($userMsg['group_id']=='1' or $userMsg['group_id']=='2')
										{
											//普通会员订单，才给上级推送
											if($userMsg['referrer_id'])
											{
												$referrer_id=$userMsg['referrer_id'];
												$referrerMsg=$User->getUserMsg($referrer_id);
												if($referrerMsg['group_id']!='1')
												{
													$alias=$referrer_id;//推送别名
													$title=APP_NAME.'通知您有新订单';
													$content='您有一笔新订单：'.$gl['skuName'];
													$key='order';
													$value='jingdong1';
													$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
												}

												if($referrerMsg['referrer_id'])
												{
													$referrer_id2=$referrerMsg['referrer_id'];
													$referrerMsg2=$User->getUserMsg($referrer_id2);
													if($referrerMsg2['group_id']!='1')
													{
														$alias=$referrer_id2;//推送别名
														$title=APP_NAME.'通知您有新订单';
														$content='您有一笔新订单：'.$gl['skuName'];
														$key='order';
														$value='jingdong2';
														$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
													}
												}
											}
										}

										//对订单做预估收入处理-付款时候得不到最终佣金，只能用预估佣金去计算
										$res_treat_tmp = $JingdongOrderDetail->treat_tmp ( $order_detail_id, $gl ['estimateFee'] );

									}

									//提交事务
									$JingdongOrder->commit();
								}else {
									//回滚
									$JingdongOrder->rollback();
									exit();
								}
							}
							// 成功次数
							$count ++;
						}else {
							//回滚
							$JingdongOrder->rollback();
							exit();
						}
					}
				}
				if ($res_jd['hasMore'])
				{
					// 500条，可能还有更多订单，继续查询
					continue;
				} else {
					// 不超出500条，没有更多结果
					// 跳出循环
					break;
				}
			} else {
				// 本次查询无结果
				// 跳出循环
				break;
			}
		}

        //自动执行-京东订单返利
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                    $orderList=$JdOrderDetail->where("user_id!='' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and date_format(payMonth,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('id,actualFee')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                $orderList=$JdOrderDetail->where("user_id!='0' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(payMonth)")->field('id,actualFee')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }

		echo '成功执行：' . $count;
	}


    // 自动执行订单-2天一次
    // 网址：http://taobao.mjuapp.com/app.php/Task/treatJdOrdertwo
    // 每10分钟执行一次
    // 自动执行命令：0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatJdOrdertwo
    public function treatJdOrdertwo()
    {
        // 订单查询开始时间
        $Time = new \Common\Model\TimeModel ();
        $now = date ( 'Y-m-d H:i:s' );
        // 当前时间往前2天
        $start_time = $Time->getAfterDateTime ( $now, $type = '2', $year = '', $month = '', $day = '-2', $hour = '', $minute = '', $second = '', $week = '' );
        $start_update_time = strtotime($start_time);
        $time=date('YmdH',$start_update_time);
        //$time='2018120922';
        $page_size = 500;
        // 循环查询10万条订单最多，1小时内最多10万条
        $JingdongOrder = new \Common\Model\JingdongOrderModel();
        $JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
        $User = new \Common\Model\UserModel ();
        $UserGroup=new \Common\Model\UserGroupModel();
        $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
        $UserExpRecord=new \Common\Model\UserExpRecordModel();
        $num = 100000 / $page_size;
        // 成功条数
        $count = 0;
        Vendor('JingDong.JdUnion','','.class.php');
        $JdUnion=new \JdUnion();
        for($i = 0; $i < $num; $i ++)
        {
            $page = $i + 1;
            // 京东订单接口
            //$res_jd=$JindDong->queryOrderList($time,$page,$page_size);
            $res_jd=$JdUnion->queryOpenOrders($time,$page,$page_size);
            if ($res_jd ['data'])
            {
                // 本次查询有结果
                // 处理所有的订单
                foreach ( $res_jd ['data'] as $l )
                {
                    // 判断订单是否存在，存在不处理
                    $orderId = $l ['orderId'];
                    $res_exist = $JingdongOrder->where ( "orderId='$orderId'" )->find ();
                    if ($res_exist)
                    {
                        // 存在
                        // 修改订单的一些重要参数
                        $data = array (
                            'finishTime' => $l ['finishTime'],
                            'orderEmt' => $l ['orderEmt'],
                            'orderTime' => $l ['orderTime'],
                            'parentId' => $l ['parentId'],
                            'payMonth' => $l ['payMonth'],
                            'plus' => $l ['plus'],
                            'popId' => $l ['popId'],
                            'unionId' => $l ['unionId'],
                            'ext1' => $l ['unionUserName'],
                            'validCode' => $l ['validCode'],
                        );
                        // 保存订单
                        $res_save = $JingdongOrder->where("orderId='$orderId'")->save( $data );
                        // 查询订单详情
                        $order_id=$res_exist['id'];
                        $detailList=$JingdongOrderDetail->where("order_id='$order_id'")->select();
                        $num2=count($detailList);
                        for($j=0;$j<$num2;$j++)
                        {
                            if($detailList[$j]['status']=='1')
                            {
                                //未结算订单才进行处理
                                // 修改订单的一些重要参数
                                $gl=$l['skuList'][$j];
                                $jd_pid = $gl['positionId'];
                                $user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
                                $data_detail=array(
                                    'user_id'=>$user_id,
                                    'actualCosPrice'=>$gl['actualCosPrice'],
                                    'actualFee'=>$gl['actualFee'],
                                    'commissionRate'=>$gl['commissionRate'],
                                    'estimateCosPrice'=>$gl['estimateCosPrice'],
                                    'estimateFee'=>$gl['estimateFee'],
                                    'finalRate'=>$gl['finalRate'],
                                    'cid1'=>$gl['firstLevel'],
                                    'frozenSkuNum'=>$gl['frozenSkuNum'],
                                    'pid'=>$gl['pid'],
                                    'positionId'=>$gl['positionId'],
                                    'price'=>$gl['price'],
                                    'payPrice'=>$gl['payPrice'],
                                    'cid2'=>$gl['secondLevel'],
                                    'siteId'=>$gl['siteId'],
                                    'skuId'=>$gl['skuId'],
                                    'skuName'=>$gl['skuName'],
                                    'skuNum'=>$gl['skuNum'],
                                    'skuReturnNum'=>$gl['skuReturnNum'],
                                    'subSideRate'=>$gl['subSideRate'],
                                    'subsidyRate'=>$gl['subsidyRate'],
                                    'cid3'=>$gl['thirdLevel'],
                                    'unionAlias'=>$gl['unionAlias'],
                                    'unionTag'=>$gl['unionTag'],
                                    'unionTrafficGroup'=>$gl['unionTrafficGroup'],
                                    'validCode'=>$gl['validCode'],
                                    'subUnionId'=>$gl['subUnionId'],
                                    'traceType'=>$gl['traceType'],
                                    'payMonth'=>$gl['payMonth'],
                                    'popId'=>$gl['popId'],
                                    'ext1'=>$gl['ext1'],
                                    'orderTime' => $l ['orderTime'],
                                );
                                $order_detail_id=$detailList[$j]['id'];
                                //开启事务
                                $JingdongOrder->startTrans();
                                $res_od=$JingdongOrderDetail->where("id='$order_detail_id'")->save($data_detail);
                                if($res_od!==false)
                                {
//                         // 给用户返利
//                          if (($gl ['validCode'] == '17' or $gl ['validCode'] == '18') and !empty($l ['payMonth']) ) {
//                              //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
//                            // 只有结算订单才给用户返利
//                            if ($user_id) {
//                               // 用户存在，给对应用户返利
//                               $res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
//                            }
//                         }

                                    //如果之前没有所属用户，再做一次预估统计和消息推送
                                    if ($user_id and $res_exist['user_id']=='')
                                    {
                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        //给直接推荐人加经验值
                                        if($userMsg['referrer_id']) {
                                            //是否购物
                                            $old_is_buy=$userMsg['is_buy'];
                                            if($old_is_buy=='N') {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                //判断推荐人是否可以升级为VIP
                                                $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                                $data_referrer=array(
                                                    'exp'=>$new_exp
                                                );
                                                //判断推荐人应该升级到那个会员组
                                                //大于当前会员组，并且小于新经验值的最大值
                                                $group_id=$referrerMsg['group_id'];
                                                $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                                //升级到该会员组增加条件，佣金条件
                                                //查询该用户总佣金
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                                if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                                    $data_referrer['group_id']=$res_group['id'];
                                                    $data_referrer['expiration_date']=null;
                                                    $data_referrer['is_forever']='Y';
                                                }
                                                $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                                //设置用户为已购物
                                                $data_user=array(
                                                    'is_buy'=>'Y',//是否购物，Y是
                                                );
                                                $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                                //保存经验值变动记录-首次购物
                                                $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                            }
                                        }

                                        //极光推送消息
                                        Vendor('jpush.jpush','','.class.php');
                                        $jpush=new \jpush();
                                        $alias=$user_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$gl['skuName'];
                                        $key='order';
                                        $value='jingdong';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        if($userMsg['group_id']=='1' or $userMsg['group_id']=='2')
                                        {
                                            //普通会员订单，才给上级推送
                                            if($userMsg['referrer_id'])
                                            {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                if($referrerMsg['group_id']!='1')
                                                {
                                                    $alias=$referrer_id;//推送别名
                                                    $title=APP_NAME.'通知您有新订单';
                                                    $content='您有一笔新订单：'.$gl['skuName'];
                                                    $key='order';
                                                    $value='jingdong1';
                                                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                }

                                                if($referrerMsg['referrer_id'])
                                                {
                                                    $referrer_id2=$referrerMsg['referrer_id'];
                                                    $referrerMsg2=$User->getUserMsg($referrer_id2);
                                                    if($referrerMsg2['group_id']!='1')
                                                    {
                                                        $alias=$referrer_id2;//推送别名
                                                        $title=APP_NAME.'通知您有新订单';
                                                        $content='您有一笔新订单：'.$gl['skuName'];
                                                        $key='order';
                                                        $value='jingdong2';
                                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                    }
                                                }
                                            }
                                        }

                                        //对订单做预估收入处理-付款时候得不到最终佣金，只能用预估佣金去计算
                                        $res_treat_tmp = $JingdongOrderDetail->treat_tmp ( $order_detail_id, $gl ['estimateFee'] );

                                    }

                                    //针对已经存在，失效的订单，删除预估记录
                                    if($gl ['validCode'] != '15' and $gl ['validCode'] != '16' and $gl ['validCode'] != '17' and $gl ['validCode'] != '18'){
                                        $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$order_detail_id' and type='2'")->delete();
                                    }

                                    //提交事务
                                    $JingdongOrder->commit();
                                }else {
                                    //回滚
                                    $JingdongOrder->rollback();
                                    exit();
                                }
                            }
                        }
                        // 成功执行次数
                        $count ++;
                    } else {
                        //不存在
                        $data = array (
                            'finishTime' => $l ['finishTime'],
                            'orderEmt' => $l ['orderEmt'],
                            'orderId' => $l ['orderId'],
                            'orderTime' => $l ['orderTime'],
                            'parentId' => $l ['parentId'],
                            'payMonth' => $l ['payMonth'],
                            'plus' => $l ['plus'],
                            'popId' => $l ['popId'],
                            'unionId' => $l ['unionId'],
                            'ext1' => $l ['unionUserName'],
                            'validCode' => $l ['validCode'],
                        );
                        //开启事务
                        $JingdongOrder->startTrans();
                        // 保存订单
                        $res_add = $JingdongOrder->add ( $data );
                        if($res_add!==false)
                        {
                            //保存订单详情
                            $order_id=$res_add;
                            foreach ($l['skuList'] as $gl)
                            {
                                $jd_pid = $gl['positionId'];
                                $user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
                                $data_detail=array(
                                    'order_id'=>$order_id,
                                    'user_id'=>$user_id,
                                    'actualCosPrice'=>$gl['actualCosPrice'],
                                    'actualFee'=>$gl['actualFee'],
                                    'commissionRate'=>$gl['commissionRate'],
                                    'estimateCosPrice'=>$gl['estimateCosPrice'],
                                    'estimateFee'=>$gl['estimateFee'],
                                    'finalRate'=>$gl['finalRate'],
                                    'cid1'=>$gl['firstLevel'],
                                    'frozenSkuNum'=>$gl['frozenSkuNum'],
                                    'pid'=>$gl['pid'],
                                    'positionId'=>$gl['positionId'],
                                    'price'=>$gl['price'],
                                    'payPrice'=>$gl['payPrice'],
                                    'cid2'=>$gl['secondLevel'],
                                    'siteId'=>$gl['siteId'],
                                    'skuId'=>$gl['skuId'],
                                    'skuName'=>$gl['skuName'],
                                    'skuNum'=>$gl['skuNum'],
                                    'skuReturnNum'=>$gl['skuReturnNum'],
                                    'subSideRate'=>$gl['subSideRate'],
                                    'subsidyRate'=>$gl['subsidyRate'],
                                    'cid3'=>$gl['thirdLevel'],
                                    'unionAlias'=>$gl['unionAlias'],
                                    'unionTag'=>$gl['unionTag'],
                                    'unionTrafficGroup'=>$gl['unionTrafficGroup'],
                                    'validCode'=>$gl['validCode'],
                                    'subUnionId'=>$gl['subUnionId'],
                                    'traceType'=>$gl['traceType'],
                                    'payMonth'=>$gl['payMonth'],
                                    'popId'=>$gl['popId'],
                                    'ext1'=>$gl['ext1'],
                                    'status'=>'1',//未结算
                                    'orderTime' => $l ['orderTime'],
                                );
                                $res_od=$JingdongOrderDetail->add($data_detail);
                                if($res_od!==false)
                                {
                                    $order_detail_id=$res_od;
//                         // 给用户返利
//                         if (($gl ['validCode'] == '17' or $gl ['validCode'] == '18') and !empty($l ['payMonth']) ) {
//                             //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
//                            // 只有结算订单才给用户返利
//                            if ($user_id) {
//                               // 用户存在，给对应用户返利
//                               $res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
//                            }
//                         }

                                    if ($user_id)
                                    {
                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        //给直接推荐人加经验值
                                        if($userMsg['referrer_id']) {
                                            //是否购物
                                            $old_is_buy=$userMsg['is_buy'];
                                            if($old_is_buy=='N') {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                //判断推荐人是否可以升级为VIP
                                                $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                                $data_referrer=array(
                                                    'exp'=>$new_exp
                                                );
                                                //判断推荐人应该升级到那个会员组
                                                //大于当前会员组，并且小于新经验值的最大值
                                                $group_id=$referrerMsg['group_id'];
                                                $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                                //升级到该会员组增加条件，佣金条件
                                                //查询该用户总佣金
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                                if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                                    $data_referrer['group_id']=$res_group['id'];
                                                    $data_referrer['expiration_date']=null;
                                                    $data_referrer['is_forever']='Y';
                                                }
                                                $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                                //设置用户为已购物
                                                $data_user=array(
                                                    'is_buy'=>'Y',//是否购物，Y是
                                                );
                                                $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                                //保存经验值变动记录-首次购物
                                                $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                            }
                                        }

                                        //极光推送消息
                                        Vendor('jpush.jpush','','.class.php');
                                        $jpush=new \jpush();
                                        $alias=$user_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$gl['skuName'];
                                        $key='order';
                                        $value='jingdong';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                                        //给推荐人推送
                                        $userMsg=$User->getUserMsg($user_id);
                                        if($userMsg['group_id']=='1' or $userMsg['group_id']=='2')
                                        {
                                            //普通会员订单，才给上级推送
                                            if($userMsg['referrer_id'])
                                            {
                                                $referrer_id=$userMsg['referrer_id'];
                                                $referrerMsg=$User->getUserMsg($referrer_id);
                                                if($referrerMsg['group_id']!='1')
                                                {
                                                    $alias=$referrer_id;//推送别名
                                                    $title=APP_NAME.'通知您有新订单';
                                                    $content='您有一笔新订单：'.$gl['skuName'];
                                                    $key='order';
                                                    $value='jingdong1';
                                                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                }

                                                if($referrerMsg['referrer_id'])
                                                {
                                                    $referrer_id2=$referrerMsg['referrer_id'];
                                                    $referrerMsg2=$User->getUserMsg($referrer_id2);
                                                    if($referrerMsg2['group_id']!='1')
                                                    {
                                                        $alias=$referrer_id2;//推送别名
                                                        $title=APP_NAME.'通知您有新订单';
                                                        $content='您有一笔新订单：'.$gl['skuName'];
                                                        $key='order';
                                                        $value='jingdong2';
                                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                    }
                                                }
                                            }
                                        }

                                        //对订单做预估收入处理-付款时候得不到最终佣金，只能用预估佣金去计算
                                        $res_treat_tmp = $JingdongOrderDetail->treat_tmp ( $order_detail_id, $gl ['estimateFee'] );

                                    }

                                    //提交事务
                                    $JingdongOrder->commit();
                                }else {
                                    //回滚
                                    $JingdongOrder->rollback();
                                    exit();
                                }
                            }
                            // 成功次数
                            $count ++;
                        }else {
                            //回滚
                            $JingdongOrder->rollback();
                            exit();
                        }
                    }
                }
                if ($res_jd['hasMore'])
                {
                    // 500条，可能还有更多订单，继续查询
                    continue;
                } else {
                    // 不超出500条，没有更多结果
                    // 跳出循环
                    break;
                }
            } else {
                // 本次查询无结果
                // 跳出循环
                break;
            }
        }

        //自动执行-京东订单返利
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                    $orderList=$JdOrderDetail->where("user_id!='' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and date_format(payMonth,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('id,actualFee')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                $orderList=$JdOrderDetail->where("user_id!='0' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(payMonth)")->field('id,actualFee')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }

        echo '成功执行：' . $count;
    }

    //自动执行-京东订单返利--合并到订单拉取里面执行，不用单独加任务
    public function rebateJdOrder()
    {
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                    $orderList=$JdOrderDetail->where("user_id!='' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and date_format(payMonth,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('id,actualFee')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $JdOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                $orderList=$JdOrderDetail->where("user_id!='0' and user_id!='null' and status=1 and validCode in (17,18) and payMonth!='0' and payMonth!='null' and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(payMonth)")->field('id,actualFee')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $JdOrderDetail->treat ( $l['id'], $l['actualFee'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }
    }

    // 自动执行订单-10分钟一次
    // 网址：http://taobao.mjuapp.com/app.php/Task/treatVipOrder
    // 每10分钟执行一次
    // 自动执行命令：0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatVipOrder
    public function treatVipOrder(){
        // 订单查询开始时间
        $end_update_time=time();
        // 订单查询截止时间
        $Time = new \Common\Model\TimeModel ();
        $now = date ( 'Y-m-d H:i:s' );
        // 当前时间往前30分钟
        $start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '-30');
        $start_update_time = strtotime($start_time);

        $page_size = 100;
        // 循环查询10万条订单最多，30分钟内最多10万条
        $VipOrder = new \Common\Model\VipOrderModel();
        $User = new \Common\Model\UserModel ();
        $UserGroup=new \Common\Model\UserGroupModel();
        $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
        $UserExpRecord=new \Common\Model\UserExpRecordModel();
        // 拼多多订单接口
        Vendor('vip.vip','','.class.php');
        $vip=new \vip();
        $num = 100000 / $page_size;
        // 成功条数
        $count = 0;
        for($i = 0; $i < $num; $i ++) {
            $page = $i + 1;
            $res_vip=$vip->getOrderList($page_size,$page,$start_update_time,$end_update_time);
            if ($res_vip ['data']['order_list']) {
                // 本次查询有结果
                // 处理所有的订单
                foreach ( $res_vip ['data']['order_list'] as $l ) {
                    // 判断订单是否存在，存在不处理
                    $order_sn = $l ['orderSn'];
                    $res_exist = $VipOrder->where ( "orderSn='$order_sn'" )->find ();
                    if ($res_exist) {
                        // 存在
                        // 修改订单的一些重要参数
                        $pid = explode('_', $l['pid']);
                        $user_id = $pid[1];
                        $data_o = array (
                            'user_id' => $user_id,
                            'orderSn' => $l ['orderSn'],
                            'goodsId' => $l ['detailList'][0]['goodsId'],
                            'goodsName' => $l ['detailList'][0]['goodsName'],
                            'goodsThumb' => $l ['detailList'][0]['goodsThumb'],
                            'goodsCount' => $l ['detailList'][0]['goodsCount'],
                            'commissionTotalCost' => $l ['detailList'][0]['commissionTotalCost'],
                            'vipCommission' => $l ['commission'],
                            'commission' => $l ['detailList'][0]['commission'],
                            'commissionRate' => $l ['detailList'][0]['commissionRate'],
                            'commCode' => $l ['detailList'][0]['commCode'],
                            'commName' => $l ['detailList'][0]['commName'],
                            'vipStatus' => $l ['status'],
                            'settled' => $l ['settled'],
                            'orderSubStatusName' => $l ['orderSubStatusName'],
                            'newCustomer' => $l ['newCustomer'],
                            'selfBuy' => $l ['selfBuy'],
                            'channelTag' => $l ['channelTag'],
                            'orderSource' => $l ['orderSource'],
                            'isPrepay' => $l ['isPrepay'],
                            'pid' => $l ['pid'],
                            'orderTime' => $l ['orderTime'],
                            'signTime' => $l ['signTime'],
                            'settledTime' => $l ['settledTime'],
                            'lastUpdateTime' => $l ['lastUpdateTime'],
                            'commissionEnterTime' => $l ['commissionEnterTime'],
                            'afterSaleChangeCommission' => $l ['afterSaleChangeCommission'],
                            'afterSaleChangeGoodsCount' => 111,
                        );
                        $res_order = $VipOrder->where ( "orderSn='$order_sn'" )->save ( $data_o );

                        //如果之前没有所属用户，再做一次预估统计和消息推送
                        if($user_id and $res_exist['user_id']==''){
                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            //给直接推荐人加经验值
                            if($userMsg['referrer_id']) {
                                //是否购物
                                $old_is_buy=$userMsg['is_buy'];
                                if($old_is_buy=='N') {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    //判断推荐人是否可以升级为VIP
                                    $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                    $data_referrer=array(
                                        'exp'=>$new_exp
                                    );
                                    //判断推荐人应该升级到那个会员组
                                    //大于当前会员组，并且小于新经验值的最大值
                                    $group_id=$referrerMsg['group_id'];
                                    $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                    //升级到该会员组增加条件，佣金条件
                                    //查询该用户总佣金
                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                    $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                    if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                        $data_referrer['group_id']=$res_group['id'];
                                        $data_referrer['expiration_date']=null;
                                        $data_referrer['is_forever']='Y';
                                    }
                                    $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                    //设置用户为已购物
                                    $data_user=array(
                                        'is_buy'=>'Y',//是否购物，Y是
                                    );
                                    $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                    //保存经验值变动记录-首次购物
                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                }
                            }

                            //极光推送消息
                            Vendor('jpush.jpush','','.class.php');
                            $jpush=new \jpush();
                            $alias=$user_id;//推送别名
                            $title=APP_NAME.'通知您有新订单';
                            $content='您有一笔新订单：'.$data_o ['goodsName'];
                            $key='order';
                            $value='vip';
                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            if($userMsg['group_id']=='1' or $userMsg['group_id']=='2') {
                                //普通会员订单，才给上级推送
                                if($userMsg['referrer_id']) {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    if($referrerMsg['group_id']!='1') {
                                        $alias=$referrer_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$data_o ['goodsName'];
                                        $key='order';
                                        $value='vip1';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                    }

                                    if($referrerMsg['referrer_id']) {
                                        $referrer_id2=$referrerMsg['referrer_id'];
                                        $referrerMsg2=$User->getUserMsg($referrer_id2);
                                        if($referrerMsg2['group_id']!='1') {
                                            $alias=$referrer_id2;//推送别名
                                            $title=APP_NAME.'通知您有新订单';
                                            $content='您有一笔新订单：'.$data_o ['goodsName'];
                                            $key='order';
                                            $value='vip2';
                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                        }
                                    }
                                }
                            }

                            //对订单做预估收入处理
                            $res_treat_tmp = $VipOrder->treat_tmp ( $order_sn, $l ['commission'] );
                        }

//                        // 判断订单状态，如果尚未结算，给用户返利
//                        // 原来未结算，现在结算的订单进行返利
//                        if ($res_exist ['status'] == '1' and $l ['settled'] == 1) {
//                            // 尚未结算，给用户返利
//                            if($user_id) {
//                                // 用户存在，给对应用户返利
//                                $res_treat = $VipOrder->treat ( $order_sn, $l['commission'] );
//                            }else {
//                                // 不存在对应用户，不去处理
//                            }
//                        } else {
//                            // 已结算，不处理
//                        }

                        //针对已经存在，失效的订单，删除预估记录
                        if($l ['orderSubStatusName'] == '已失效'){
                            $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$order_sn' and type='4'")->delete();
                        }

                        // 成功执行次数
                        $count ++;
                    } else {
                        //不存在
                        $pid = explode('_', $l['pid']);
                        $user_id = $pid[1];
                        $data = array (
                            'user_id' => $user_id,
                            'orderSn' => $l ['orderSn'],
                            'goodsId' => $l ['detailList'][0]['goodsId'],
                            'goodsName' => $l ['detailList'][0]['goodsName'],
                            'goodsThumb' => $l ['detailList'][0]['goodsThumb'],
                            'goodsCount' => $l ['detailList'][0]['goodsCount'],
                            'commissionTotalCost' => $l ['detailList'][0]['commissionTotalCost'],
                            'vipCommission' => $l ['commission'],
                            'commission' => $l ['detailList'][0]['commission'],
                            'commissionRate' => $l ['detailList'][0]['commissionRate'],
                            'commCode' => $l ['detailList'][0]['commCode'],
                            'commName' => $l ['detailList'][0]['commName'],
                            'vipStatus' => $l ['status'],
                            'settled' => $l ['settled'],
                            'orderSubStatusName' => $l ['orderSubStatusName'],
                            'newCustomer' => $l ['newCustomer'],
                            'selfBuy' => $l ['selfBuy'],
                            'channelTag' => $l ['channelTag'],
                            'orderSource' => $l ['orderSource'],
                            'isPrepay' => $l ['isPrepay'],
                            'pid' => $l ['pid'],
                            'orderTime' => $l ['orderTime'],
                            'signTime' => $l ['signTime'],
                            'settledTime' => $l ['settledTime'],
                            'lastUpdateTime' => $l ['lastUpdateTime'],
                            'commissionEnterTime' => $l ['commissionEnterTime'],
                            'afterSaleChangeCommission' => $l ['afterSaleChangeCommission'],
                            'afterSaleChangeGoodsCount' => $l ['afterSaleChangeGoodsCount'],
                            'status' => '1'  // 是否结算给用户，1未结算，2已结算
                        );
                        // 保存订单
                        $res_add = $VipOrder->add ( $data );
//                        // 给用户返利
//                        if ($l ['settled'] == 1) {
//                            // 只有结算订单才给用户返利
//                            if ($user_id) {
//                                // 用户存在，给对应用户返利
//                                $res_treat = $VipOrder->treat ( $order_sn, $l ['commission'] );
//                            }
//                        }

                        if ($user_id) {
                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            //给直接推荐人加经验值
                            if($userMsg['referrer_id']) {
                                //是否购物
                                $old_is_buy=$userMsg['is_buy'];
                                if($old_is_buy=='N') {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    //判断推荐人是否可以升级为VIP
                                    $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
                                    $data_referrer=array(
                                        'exp'=>$new_exp
                                    );
                                    //判断推荐人应该升级到那个会员组
                                    //大于当前会员组，并且小于新经验值的最大值
                                    $group_id=$referrerMsg['group_id'];
                                    $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                    //升级到该会员组增加条件，佣金条件
                                    //查询该用户总佣金
                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                    $amount=$UserBalanceRecord->userCommissionStatistics($referrer_id);
                                    if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                        $data_referrer['group_id']=$res_group['id'];
                                        $data_referrer['expiration_date']=null;
                                        $data_referrer['is_forever']='Y';
                                    }
                                    $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
                                    //设置用户为已购物
                                    $data_user=array(
                                        'is_buy'=>'Y',//是否购物，Y是
                                    );
                                    $res_buy=$User->where("uid='$user_id'")->save($data_user);

                                    //保存经验值变动记录-首次购物
                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
                                }
                            }

                            //极光推送消息
                            Vendor('jpush.jpush','','.class.php');
                            $jpush=new \jpush();
                            $alias=$user_id;//推送别名
                            $title=APP_NAME.'通知您有新订单';
                            $content='您有一笔新订单：'.$data ['goodsName'];
                            $key='order';
                            $value='vip';
                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);

                            //给推荐人推送
                            $userMsg=$User->getUserMsg($user_id);
                            if($userMsg['group_id']=='1' or $userMsg['group_id']=='2') {
                                //普通会员订单，才给上级推送
                                if($userMsg['referrer_id']) {
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    if($referrerMsg['group_id']!='1') {
                                        $alias=$referrer_id;//推送别名
                                        $title=APP_NAME.'通知您有新订单';
                                        $content='您有一笔新订单：'.$data ['goodsName'];
                                        $key='order';
                                        $value='vip1';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                    }

                                    if($referrerMsg['referrer_id']) {
                                        $referrer_id2=$referrerMsg['referrer_id'];
                                        $referrerMsg2=$User->getUserMsg($referrer_id2);
                                        if($referrerMsg2['group_id']!='1') {
                                            $alias=$referrer_id2;//推送别名
                                            $title=APP_NAME.'通知您有新订单';
                                            $content='您有一笔新订单：'.$data ['goodsName'];
                                            $key='order';
                                            $value='vip2';
                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                        }
                                    }
                                }
                            }

                            //对订单做预估收入处理
                            $res_treat_tmp = $VipOrder->treat_tmp ( $order_sn, $l ['commission'] );
                        }
                        // 成功次数
                        $count ++;
                    }
                }
                $list_num = count ( $res_vip ['data']['order_list'] );
                if ($list_num == 100) {
                    // 100条，可能还有更多订单，继续查询
                    continue;
                } else {
                    // 不超出100条，没有更多结果
                    // 跳出循环
                    break;
                }
            } else {
                // 本次查询无结果
                // 跳出循环
                break;
            }
        }

        //自动执行-唯品会订单返利
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $VipOrder = new \Common\Model\VipOrderModel();
                    $orderList=$VipOrder->where("user_id!='' and user_id!='null' and status=1 and settled=1 and date_format(FROM_UNIXTIME(settledTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('orderSn,vipCommission')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $VipOrder->treat ( $l['ordersn'], $l['vipcommission'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
                //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $VipOrder = new \Common\Model\VipOrderModel();
                $orderList=$VipOrder->where("user_id!='' and user_id!='null' and status=1 and settled=1 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(FROM_UNIXTIME(settledTime/1000,'%Y-%m-%d %H:%i'))")->field('orderSn,vipCommission')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $VipOrder->treat ( $l['ordersn'], $l['vipcommission'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }

        echo '成功执行：' . $count;
    }

    //自动执行-唯品会订单返利--合并到订单拉取里面执行，不用单独加任务
    public function rebateVipOrder()
    {
        if (defined('REBATE_METHOD') and defined('REBATE_TIME')){
            //每月某天返利已结算订单
            if (REBATE_METHOD==1){
                if (date('d') == REBATE_TIME) {
                    $VipOrder = new \Common\Model\VipOrderModel();
                    $orderList=$VipOrder->where("user_id!='' and user_id!='null' and status=1 and settled=1 and date_format(FROM_UNIXTIME(settledTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->field('orderSn,vipCommission')->select();
                    if(!empty($orderList)){
                        $count=0;
                        foreach ($orderList as $l){
                            $VipOrder->treat ( $l['ordersn'], $l['vipcommission'] );
                            // 成功执行次数
                            $count ++;
                        }
                        echo $count;
                    }
                }
            //订单结算后多少天返利已结算订单
            }else{
                $rebate_time=REBATE_TIME;
                $VipOrder = new \Common\Model\VipOrderModel();
                $orderList=$VipOrder->where("user_id!='' and user_id!='null' and status=1 and settled=1 and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(FROM_UNIXTIME(settledTime/1000,'%Y-%m-%d %H:%i'))")->field('orderSn,vipCommission')->select();
                if(!empty($orderList)){
                    $count=0;
                    foreach ($orderList as $l){
                        $VipOrder->treat ( $l['ordersn'], $l['vipcommission'] );
                        // 成功执行次数
                        $count ++;
                    }
                    echo $count;
                }
            }
        }
    }

    /**
     * 同步自营商户订单处理
     */
    public function rebateShopOrder()
    {
        $shopOrderModel = new \Common\Model\OrderModel();
        $renOrderModel = new \Common\Model\ShopOrderModel();
        $addressModel = new \Common\Model\ConsigneeAddressModel();
        $orderGoodsModel = new \Common\Model\OrderDetailModel();
        $goodsModel = new \Common\Model\GoodsModel();
        $optionModel = new \Common\Model\ShopGoodsOptionModel();
        $shopOrderGoodsModel = new \Common\Model\ShopOrderGoodsModel();
        // 订单查询截止时间
        $end_update_time=time();
        // 订单查询开始时间
        $Time = new \Common\Model\TimeModel ();
        $now =date ( 'Y-m-d H:i:s' );#date ( 'Y-m-d H:i:s' )
        // 当前时间往前30分钟
        $start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '-7200');
        $start_update_time = strtotime($start_time);
        $list = $shopOrderModel->where('shop_id >0 and `create_time` >="'.$start_time.'" and `create_time` <="'.$now.'"')->select();
        $count = 0;
        for ($i=0;$i<count($list);$i++)
        {
            if(!empty($list[$i]['shop_id']))
            {
                $order_info  = $renOrderModel->where('id='.$list[$i]['ren_order_id'])->find();

                #如果已经存在数据了，更新里面的支付信息等等就好了
                if(empty($order_info) && $list[$i]['shop_id']>0)
                {
                    $order['ismerch'] =1;
                    $order['parentid'] =0;
                    $order['uniacid'] =1;
                    $order['openid'] ='lailu_'.$list[$i]['user_id'];
                    $order['ordersn'] =$list[$i]['order_num'];
                    $order['price'] =$list[$i]['allprice']/100;
                    $order['oldprice'] =$list[$i]['allprice']/100;
                    $order['grprice'] =$list[$i]['allprice']/100;
                    $order['taskdiscountprice'] =0;
                    $order['lotterydiscountprice'] =0;
                    $order['discountprice'] =0;
                    $order['isdiscountprice'] =0;
                    $order['merchisdiscountprice'] =0;
                    $order['cash'] =0;
                    $order['status'] =0;
                    $order['remark'] =$list[$i]['remark'];
                    $order['addressid'] =1;
                    $order['goodsprice'] =0;
                    $order['dispatchprice'] =0;
                    $order['dispatchtype'] =0;
                    $order['dispatchid'] =0;
                    $order['storeid'] =0;
                    $order['paytime'] =empty($list[$i]['pay_time'])?0:strtotime($list[$i]['pay_time']);
                    $order['carrier'] ="a:0:{}";
                    $order['createtime'] =strtotime($list[$i]['create_time']);
                    $order['olddispatchprice'] =$list[$i]['freight'];
                    $order['contype'] =0;
                    $order['couponid'] =0;
                    $order['wxid'] =0;
                    $order['wxcardid'] =0;
                    $order['wxcode'] =0;
                    $order['couponmerchid'] =0;
                    $order['ordersn_trade']='';
                    $order['tradepaytype']='';

                    $paytype =1;
                    if($list[$i]['pay_method'] =='alipay')
                    {
                        $paytype=22;
                    }
                    if($list[$i]['pay_method'] =='wxpay')
                    {
                        $paytype=21;
                    }
                    $order['paytype'] =$paytype;
                    $order['deductprice'] =0;
                    $order['deductcredit'] =0;
                    $order['deductcredit2'] =0;
                    $order['deductenough'] =0;
                    $order['merchdeductenough'] =0;
                    $order['couponprice'] =0;
                    $order['merchshow'] =0;
                    $order['buyagainprice'] =0;
                    $order['ispackage'] =0;
                    $order['packageid'] =0;
                    $order['seckilldiscountprice'] =0;
                    $order['quickid'] =0;
                    $order['officcode'] =0;
                    $order['dispatchid'] =0;
                    $order['liveid'] =0;
                    $order['merchid'] =$list[$i]['shop_id'];
                    $order['isparent'] =0;
                    $order['transid'] =0;
                    $order['isverify'] =0;
                    $order['verifytype'] =0;
                    $order['verifyendtime'] =0;
                    $order['verifycode'] =0;
                    $order['verifycodes'] =0;
                    $order['verifyinfo'] ='a:0:{}';
                    $order['virtual'] =0;
                    $order['isvirtual'] =0;
                    $order['isvirtualsend'] =0;
                    $order['invoicename'] =0;
                    $order['coupongoodprice'] =0;
                    $order['city_express_state'] =0;
                    $order['address'] =serialize(array('id'=>0,'uniacid'=>1,'openid'=>'','realname'=>$list[$i]['consignee'],'mobile'=>$list[$i]['contact_number'],'province'=>$list[$i]['province'],'city'=>$list[$i]['city'],'area'=>$list[$i]['area'],'address'=>$list[$i]['address']));
                    //开启事务
                    $renOrderModel->startTrans();
                    $ren_add = $renOrderModel->add($order);
                    writeLog(json_encode(['sql'=>$renOrderModel->getLastSql()]),'synOrder1');
                    if($ren_add !== false)
                    {
                        $order_id = $renOrderModel->getLastInsID();
                        $goodsList = $orderGoodsModel->where('order_id='.$list[$i]['id'])->select();
                        $goodsPirce = 0;
                        for ($s=0;$s<count($goodsList);$s++)
                        {
                            $order_goods['merchid'] = $list[$i]['shop_id'];
                            $order_goods['merchsale'] = 1;
                            $order_goods['uniacid'] = 1;
                            $order_goods['orderid'] = $order_id;
                            $goods = $goodsModel->getGoodsMsg( $goodsList[$s]['goods_id']);
                            $order_goods['goodsid'] = $goods['ren_good_id'];

                            $order_goods['total'] = $goodsList[$s]['num'];
                            $option = $optionModel->getGoodsOptionById($goods['ren_good_id'],$goodsList[$s]['sku']);
                            $order_goods['optionid'] = $option['id'];
                            $order_goods['createtime'] = strtotime($list[$i]['create_time']);
                            $order_goods['optionname'] = $option['title'];
                            $order_goods['title'] = $goods['goods_name'];
                            $order_goods['goodssn'] = '';
                            $order_goods['productsn'] = '';
                            $order_goods['realprice'] = empty($option['marketprice'])?round(($goodsList[$s]['price']/100*$goodsList[$s]['num']),2):($option['marketprice']*$goodsList[$s]['num']);
                            $order_goods['price'] =empty($option['marketprice'])?round(($goodsList[$s]['price']/100*$goodsList[$s]['num']),2):($option['marketprice']*$goodsList[$s]['num']);
                            $order_goods['consume'] = 'a:0:{}';
                            $order_goods['fullbackid'] = 0;
                            $order_goods['oldprice'] = $goodsList[$s]['price'];
                            $order_goods['isdiscountprice'] = 0;
                            $order_goods['openid'] = 1;
                            $order_goods['diyformid'] = 0;
                            $order_goods['diyformdata'] = 'a:0:{}';
                            $order_goods['diyformfields'] = 'a:0:{}';
                            $order_goods['expresscom'] = '';
                            $order_goods['expresssn'] = '';
                            $order_goods['express'] = '';
                            $order_goods['sendtime'] = '';
                            $order_goods['finishtime'] ='';
                            $order_goods['remarksend']= empty($list[$i]['remark'])?'':$list[$i]['remark'];
                            $order_goods['storeid'] = 0;
                            $order_goods['optime'] = '';
                            $goodsPirce+=$order_goods['realprice'];
                            //开启事务
                            $ren_goods_add = $shopOrderGoodsModel->add($order_goods);

                            if($ren_goods_add !== false)
                            {
                                //提交事务
                                $shopOrderModel->where('id='.$list[$i]['id'])->save(['ren_order_id'=>$order_id]);
                            }else{
                                //回滚
                                $shopOrderGoodsModel->rollback();
                                writeLog(json_encode(array('order_sql'=>$renOrderModel->getLastSql(),'order_goods_sql'=>$shopOrderGoodsModel->getLastSql())),'synOrderError');
                            }
                        }
                        //提交事务

                        $renOrderModel->where(['id'=>$order_id])->save(['goodsprice'=>$goodsPirce]);
                        $renOrderModel->commit();

                        writeLog(json_encode(array('order_sql'=>$renOrderModel->getLastSql(),'order_goods_sql'=>$shopOrderGoodsModel->getLastSql())),'synOrderSuccess');
                    }else{
                        //回滚
                        $renOrderModel->rollback();
                        writeLog(json_encode(array('order_sql'=>$renOrderModel->getLastSql(),'order_goods_sql'=>$shopOrderGoodsModel->getLastSql())),'synOrderError');
                    }
                    $count++;
                }
                if(!empty($order_info) && $list[$i]['shop_id']>0){
                    $expressModel = new \Common\Model\ExpressModel();
                    $express = $expressModel->getExpressList();
                    $data['expresscom'] = empty($list[$i]['logistics'])?'':$express[$list[$i]['logistics']]['name'];
                    $data['expresssn'] = empty($list[$i]['express_number'])?'':$list[$i]['express_number'];
                    $data['paytime'] = empty($list[$i]['pay_time'])?0:strtotime($list[$i]['pay_time']);
                    $data['olddispatchprice'] = $list[$i]['freight'];

                    $paytype =1;
                    if($list[$i]['pay_method'] =='alipay')
                    {
                        $paytype=22;
                    }
                    if($list[$i]['pay_method'] =='wxpay')
                    {
                        $paytype=21;
                    }
                    $order['paytype'] =$paytype;
                    #如果订单状态是退款的情况下
                    if($list[$i]['status']==6)
                    {
                        #先不弄了
                        #开始处理
                        $shopOrderRefundMoel = new \Common\Model\ShopOrderRefundModel();
                        #查看是否有相同的售后状态数据
                        $refund_info = $shopOrderRefundMoel->where(['orderid'=>$order_info['id'],'status'=>0])->find();
                        if(!$refund_info)
                        {
                            $refund = array(
                                'uniacid' => 1,
                                'merchid' => $order_info['shop_id'],
                                'applyprice' => round($list[$i]['allprice']/100,2),
                                'rtype' => 0,
                                'reason' => $list[$i]['drawback_reason'],
                                'content' => trim($list[$i]['content']),
                                'imgs' => serialize($list[$i]['drawback_img']),
                                'price' => round($list[$i]['allprice']/100,2),
                                'merchid'=>$list[$i]['shop_id']
                            );
                            //新建一条退款申请
                            $refund['createtime'] = empty($list[$i]['refund_time'])?time():strtotime($list[$i]['refund_time']);
                            $refund['orderid'] = $order_info['id'];
                            $refund['orderprice'] = round($list[$i]['allprice']/100,2);
                            $refund['refundno'] = $shopOrderModel->generateOrderNum();
                            $shopOrderRefundMoel->add($refund);
                            $refundid = $shopOrderRefundMoel->getLastInsID();
                            $renOrderModel->where(array('id' => $order_info['id']))->save(array('refundid' => $refundid, 'refundstate' => 1));
                        }
                    }else{
                        $status = $renOrderModel->statusInfo($list[$i]['status']);
                        $data['status'] = $status;
                    }

                    $renOrderModel->where('id='.$list[$i]['ren_order_id'])->save($data);
                    writeLog(json_encode(['sql'=>$renOrderModel->getLastSql()]),'synOrder');
                    $count++;
                }
            }
        }

        echo '成功执行：' . $count;
    }

    #同步商户期限
    public function synShopGoods()
    {
        $shopGoodsModel = new \Common\Model\ShopGoodsModel();
        $shopMerchUserModel = new \Common\Model\ShopMerchUserModel();
        $goodsModel = new \Common\Model\GoodsModel();
        $shopList = $shopMerchUserModel->where(time().' > accounttime')->select();
        for ($i=0;$i<count($shopList);$i++)
        {
            #更新商户端的商品状态
            $shopGoodsModel->where(['merchid'=>$shopList[$i]['id']])->save(['status'=>0]);
            #更新后台商品状态
            $goodsModel->where(['shop_id'=>$shopList[$i]['id']])->save(['is_show'=>'N']);
            #更新商户的保证金为可提取
            $boodModel = new \Common\Model\BoodModel();
            $bood = $boodModel->getOne(['user_id'=>substr($shopList[$i]['openid'],6)]);
            if($bood)
            {
                $data['bood'] = 0;
                $data['bood_change']= $bood['bood_change']+$bood['bood'];
                $data['update_time'] = date('Y-m-d H:i:s');
                $boodModel->where(['user_id'=>$bood['user_id']])->save($data);
            }
        }
    }

    #自营订单结算    以订单结算后N天进行返利
    public function TreatselfOrder()
    {
        $rebate_time=REBATE_TIMES;
        $TbOrder=new \Common\Model\OrderModel();
        $orderList=$TbOrder->where("user_id!='' and user_id!='null' and status in(4,5) and DATE_SUB(CURDATE(), INTERVAL {$rebate_time} DAY) = date(finish_time)")->select();
        if(!empty($orderList)){
            $count=0;
            foreach ($orderList as $l){
                $TbOrder->treat ($l );
                // 成功执行次数
                $count ++;
            }
            echo $count;
        }
    }

    #自营订单预估结算
    public function TreatSelfOrderTmp()
    {
        $OrderModel = new \Common\Model\OrderModel();
        $userBalanceRecordTmpModel = new \Common\Model\UserBalanceRecordTmpModel();
        $list = $OrderModel->select();
        if (!empty($list)) {
            $count = 0;
            foreach ($list as $l) {
                #根据订单状态进行处理   1失效的订单（关闭的订单）删除预估佣金表的数据  2正常的订单写入预估佣金，如果已存在预估数据，跳过
                if($l['status']=='-1' || $l['status'] == 7)
                {
                    #删除预估数据
                    $userBalanceRecordTmpModel->where(['order_id'=>$l['order_num']])->delete();
                }

                $has = $userBalanceRecordTmpModel->where(['order_id'=>$l['order_num']])->field('user_id')->find();
                if($has)
                {
                    if ($userBalanceRecordTmpModel->where(['user_id'=>$has['user_id'],'order_id'=>$l['order_num']])->find()) {
                        continue;
                    }
                }
                $OrderModel->treatSelfTmp ($l);
                // 成功执行次数
                $count ++;
            }
            echo $count;
        }
    }
}
?>
