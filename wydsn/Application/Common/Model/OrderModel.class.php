<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 订单管理类
 */
namespace Common\Model;
use Think\Model;

class OrderModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','购买用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','购买用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('order_num','require','订单号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_num','1,30','订单号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('title','1,200','订单名称不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('allprice','require','订单总价不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('allprice','is_natural_num','订单总价不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是货币格式
			array('give_point','is_natural_num','赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('address','1,200','收货地址不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('company','1,200','收件人单位名称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('consignee','1,30','收件人不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过30个字符
			array('contact_number','1,30','联系电话不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过30个字符
			array('postcode','1,10','邮政编码不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过10个字符
			array('logistics',array(1,18),'快递公司不存在！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-18的状态值
			array('express_number','1,20','快递单号不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过20个字符
			array('remark','1,200','备注不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('status',array(1,10),'订单状态不正确！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-8的状态值
			array('create_time','require','下单时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('create_time','is_datetime','下单时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('pay_time','is_datetime','订单支付时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('deliver_time','is_datetime','发货时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('finish_time','is_datetime','确认收货时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('comment_time','is_datetime','订单评论时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_time','is_datetime','申请退款时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_success_time','is_datetime','退款成功时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_fail_time','is_datetime','拒绝退款时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('pay_method',array('alipay','wxpay','balance','point','offline'),'支付方式类型不正确！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，支付方式只能是 alipay支付宝 wxpay微信支付 balance余额支付 point积分抵用 offline线下支付
			array('point','is_natural_num','抵用积分数必须是不小于零的整数',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数
	);

	/**
	 * 生成唯一订单号
	 * @return string:订单号
	 */
	public function generateOrderNum()
	{
	    $num=uniqid().rand(100, 999);
	    return $num;
	}

	/**
	 * 获取订单状态
	 * @param int $status:订单状态
	 * @return string
	 */
	public function getStatusZh($status)
	{
	    switch ($status) {
	        case '1':
	            $status_zh='未付款';
	            break;
	        case '2':
	            $status_zh='已付款、待发货';
	            break;
	        case '3':
	            $status_zh='已发货，待收货';
	            break;
	        case '4':
	            $status_zh='待评价';
	            break;
	        case '5':
	            $status_zh='已完成';
	            break;
	        case '6':
	            $status_zh='申请退款';
	            break;
	        case '7':
	            $status_zh='退款成功';
	            break;
	        case '8':
	            $status_zh='拒绝退款';
	            break;
	        default:
	            $status_zh='';
	    }
	    return $status_zh;
	}

	/**
	 * 获取订单信息
	 * @param int $id:订单ID
	 * @return array|false
	 */
	public function getOrderMsg($id)
	{
		$msg=$this->where("id=$id")->find();

		if($msg) {
            $main_order = self::getMainOrderById($msg['id']);
			//订单总价
			$msg['allprice']=$msg['allprice']/100;
			//订单状态
			$msg['status_zh']=$this->getStatusZh($msg['status']);
			return $msg;
		}else {
			return false;
		}
	}

	/**
	 * 获取订单详情
	 * @param int $id:订单ID
	 * @return array|false
	 */
	public function getOrderDetail($id)
	{
		$msg=$this->getOrderMsg($id);
		if($msg!==false) {
			$OrderDetail=new \Common\Model\OrderDetailModel();
			$detail=$OrderDetail->getOrderDetail($id);
			$shopModel = new \Common\Model\ShopMerchUserModel();
			$shop = $shopModel->getOne(['id'=>$msg['shop_id']]);
			$msg['detail']=$detail;
			$msg['you']=$msg['freight'];
			$is_gift_godos = 'N';
			if(!empty($detail) && $detail[0]['is_gift_goods']=='Y')
            {
                $is_gift_godos = 'Y';
            }
            $logo = $shopModel->getLogo();
            if($is_gift_godos == 'Y')
            {
                $msg['shopInfo'] =['merchname'=>'会员礼包','logo'=>$logo[2]['logo'],'shop_id'=>0,'is_gift_goods'=>'Y'];
            }else{
                $msg['shopInfo'] = empty($shop)?['merchname'=>'平台自营','logo'=>$logo[1]['logo'],'shop_id'=>0,'is_gift_goods'=>'N']:['merchname'=>$shop['merchname'],'logo'=>$logo[3]['logo'],'shop_id'=>$shop['id'],'is_gift_goods'=>'N'];
            }
			return $msg;
		}else {
			return false;
		}
	}

	/**
	 * 根据订单号获取订单信息
	 * @param int $order_num:订单号
	 * @return array|false
	 */
	public function getOrderMsgByOrderNum($order_num)
	{
		$msg=$this->where("order_num='$order_num'")->find();
		if($msg) {
			//订单总价
			$msg['allprice']=$msg['allprice']/100;
			//订单状态
			$msg['status_zh']=$this->getStatusZh($msg['status']);
			return $msg;
		}else {
			return false;
		}
	}

	/**
	 * 根据订单号获取订单详情
	 * @param int $order_num:订单号
	 * @return array|false
	 */
	public function getOrderDetailByOrderNum($order_num)
	{
		$msg=$this->getOrderMsgByOrderNum($order_num);
		if($msg) {
			$OrderDetail=new \Common\Model\OrderDetailModel();
			$detail=$OrderDetail->getOrderDetail($msg['id']);
            unset($detail['is_gift_goods']);
			$msg['detail']=$detail;
			return $msg;
		}else {
			return false;
		}
	}

	/**
	 * 生成消费码
	 * 当天日期加上第几个订单数字
	 * @return string:消费码
	 */
	public function generateConsumerCode()
	{
		//计算当天有多少订单
		$today=date('Ymd');
		$num=$this->where("TO_DAYS(create_time) = TO_DAYS(NOW())")->count();
		$code=$today.($num+1);
		return $code;
	}

	/**
	 * 获取用户订单列表
	 * @param int $user_id:用户ID
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
     * @param int $type:查询类型 0自营订单 1店铺订单
	 * @return array|boolean
	 */
	public function getOrderListByUid($user_id,$status='',$p=1,$per=6,$type=0)
	{
	    $where['user_id'] = $user_id;
		if($status) {
		    if ($status == 6) {
                $where['status'] = array('in', [6,7,8]);
            } else {
                $where['status'] = $status;
            }
		}
		if($type)
        {
            $where['shop_id'] = ['gt',0];
        }else{
		    $where['shop_id'] = 0;
        }
		$where['is_delete'] = 0;
        $where['main_order_id'] = ['gt',0];
		$list=$this->where($where)->order('id desc')->page($p,$per)->select();
		$data = [];
		if($list!==false) {
			//获取订单详情
			$num=count($list);
			$OrderDetail=new \Common\Model\OrderDetailModel();
            $shopModel=new \Common\Model\ShopMerchUserModel();
			for($i=0;$i<$num;$i++) {
			    //订单总价
			    $list[$i]['allprice']=$list[$i]['allprice']/100;
				$detailList=$OrderDetail->getOrderDetail($list[$i]['id']);
				$list[$i]['detail']=$detailList;
                $list[$i]['you']=$list[$i]['freight'];
                $list[$i]['is_gift_goods'] = 'N';
				if(!empty($detailList) && $detailList[0]['is_gift_goods']=='Y')
                {
                    $list[$i]['is_gift_goods'] = 'Y';
                }
                $shop = $shopModel->getOne(['id'=>$list[$i]['shop_id']]);
                $list[$i]['shop_name'] =
                    ($list[$i]['is_gift_goods']=='Y'&&empty($shop)) ? '会员商品':($list[$i]['is_gift_goods']=='N'&&empty($shop) ? '平台自营': $shop['merchname']);
                $logo = $shopModel->getLogo();
                $list[$i]['logo'] = ($list[$i]['is_gift_goods']=='Y'&&empty($shop)) ? $logo[2]['logo']:($list[$i]['is_gift_goods']=='N'&&empty($shop) ? $logo[1]['logo']: $logo[3]['logo']);
                $list[$i]['shop_id'] = empty($shop)?0:$shop['id'];
                if ($list[$i]['drawback_img'])  {
                    $list[$i]['drawback_img'] = (is_url($list[$i]['drawback_img']) ? $list[$i]['drawback_img'] : WEB_URL . $list[$i]['drawback_img']);
                }
                $data = $list[$i];
			}
			return $list;
		}else {
			return false;
		}
	}

    /**
     * 获取用户订单列表
     * @param int $user_id :用户ID
     * @param int $status :订单状态
     * @param int $p :页码，默认第1页
     * @param int $per :每页条数，默认6条
     * @param int $type :查询类型 1自己带货，2直邀，3间邀
     * @param string $order_id : 订单号
     * @param string $from :商城类型 自营 ：self、淘宝：tb、京东：jd、拼多多：pdd
     * @return array|boolean
     */
    public function getOrderListByHostId($user_id, $order_id = '', $status = 0, $p = 1, $per = 6, $type = 0, $from)
    {
        if ($status) {
            switch ($status) {
                case 2:
                    $where['u.status'] = ['in', [2,4,5]];
                    $tbwhere['tk_status'] = '12';
                    $jdwhere['validCode'] = '16';
                    $pddwhere['order_status'] = '0';
                    break;
                case 3:
                    $where['u.status'] = '10';
                    $tbwhere['tk_status'] = '3';
                    $jdwhere['validCode'] = '18';
                    $pddwhere['order_status'] = '5';
                    break;
                case 7:
                    $where['u.status'] = '7';
                    $tbwhere['tk_status'] = '13';
                    $jdwhere['validCode'] = ['in',[-1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]];
                    $pddwhere['order_status'] = '4';
                    break;
            }

        } else {
            $where['u.status'] = ['in',[2,4,5,7,10]];
            $tbwhere['tk_status'] = ['in',[3,12,13]];
            $jdwhere['validCode'] = ['in',[-1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18]];
            $pddwhere['order_status'] = ['in',[0,5,4]];
        }
        if ($order_id) {
            $where['u.order_num'] = $order_id;
            $tbwhere                 = [
                '_complex'  => [
                    'trade_id'        => $order_id
                ],
                '_logic'    => 'or',
                '_string'   => " `trade_parent_id` = $order_id",
            ];
            $pddwhere['order_sn='] = $order_id;
            $JingdongOrder = new \Common\Model\JingdongOrderModel();
            $res_o = $JingdongOrder->where("orderId='$order_id' or parentId='$order_id'")->select();
            if($res_o!==false && $from == 'jd')
            {
                $order_allid='';
                foreach ($res_o as $l)
                {
                    $order_allid.=$l['id'].',';
                }
                if($order_allid)
                {
                    $order_allid = substr($order_allid, 0,-1);
                    $jdwhere['order_id'] = ['in', [$order_allid]];
                }else {
                    $data=array(
                        'list'=>array()
                    );
                    $res=array(
                        'code'=>1,
                        'msg'=>'查询的订单号不存在',
                        'data'=>$data
                    );
                    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        }
        $userModel = new \Common\Model\UserModel();

        switch ($type) {
            #直推主播带货订单
            case 2:
                $res = $userModel->getHostUserLevel1($user_id, [0]);
                $wheres['host_id'] = ['in', $res];
                break;
            #间推主播带货ID
            case 3:
                $res = $userModel->getHostUserLevel2($user_id);
                $wheres['host_id'] = ['in', $res];
                break;
            default:
                $wheres['host_id'] = $user_id;
        }

        $wheres['from'] = $from;
        $UserBrowseModel = new \Common\Model\HostUserBrowseModel();
        $shopModel = new \Common\Model\ShopMerchUserModel();
        $userbrow = $UserBrowseModel->where($wheres)->field('user_id,goods_id')->limit($p,$per)->select();
        foreach ($userbrow as $k => $v) {
            switch ($from) {
                case 'self':
                    //获取订单详情
                    $OrderDetail = new \Common\Model\OrderDetailModel();
                    // 查询数据
                    $list[] = $OrderDetail->table("lailu_order_detail r")
                        ->join("__ORDER__ u on r.order_num = u.order_num")
                        ->field('u.create_time,u.allprice,u.order_num,u.user_id,u.shop_id,u.freight,id,status')
                        ->where("u.user_id={$v['user_id']} and r.order_num = u.order_num and r.goods_id={$v['goods_id']}")->where($where)->find();
                    break;
                case 'tb':
                    $TbOrder = new \Common\Model\TbOrderModel();
                    $list[] = $TbOrder->where(['user_id'=>$v['user_id'],'num_iid'=>$v['goods_id']])->where($tbwhere)->field('user_id,num_iid,trade_id,pay_price,item_title,item_num,create_time,earning_time,tk_status,pub_share_pre_fee')->find();
                    break;
                case 'jd':
                    $JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
                    $list[] = $JingdongOrderDetail->where(['user_id'=>$v['user_id'],'skuId'=>$v['goods_id']])->where($jdwhere)->field('id,user_id,order_id,actualCosPrice,actualFee,estimateCosPrice,estimateFee,payPrice,skuId,skuName,validCode,orderTime,price,skuNum')->find();
                    break;
                case 'pdd':
                    $PddOrder=new \Common\Model\PddOrderModel();
                    $list[]=$PddOrder->where(['user_id'=>$v['user_id'],'goods_id'=>$v['goods_id']])->where($pddwhere)->find();
                    break;
            }
        }

        $list  = array_merge(array_filter($list));
        $data = $lists =[];
        if ($list !== false) {
            $num = count($list);
            $userBalanceRecordTmpModel = new \Common\Model\UserBalanceRecordTmpModel();
            for ($i = 0; $i < $num; $i++) {
                switch ($from) {
                    case 'self':
                        switch ($list[$i]['status']) {
                            case 2:
                            case 4:
                            case 5:
                                $order_status = 2;
                                break;
                            case 10:
                                $order_status = 3;
                                break;
                            default:
                                $order_status = 7;
                                break;
                        }
                        $detailList = $OrderDetail->getOrderDetail($list[$i]['id']);
                        $lists[$i]['goods_id'] = $detailList[0]['goods_id'];
                        $lists[$i]['goods_name'] = $detailList[0]['goods_name'];
                        $lists[$i]['item_num'] = $detailList[0]['num'];
                        $lists[$i]['order_sn'] = $detailList[0]['order_num'];
                        $lists[$i]['create_time'] = $list[$i]['create_time'];
                        $lists[$i]['pay_price'] = $list[$i]['allprice'] / 100;
                        $lists[$i]['order_status'] = $order_status;
                        $lists[$i]['goods_img'] = $detailList[0]['img'];
                        $lists[$i]['commission'] = '';
                        $lists[$i]['shipping'] = $list[$i]['freight'];
                        if (!empty($detailList) && $detailList[0]['is_gift_goods'] == 'Y') {
                            $lists[$i]['is_gift_goods'] = 'Y';
                        }
                        #预估金额
                        $estimate_money = $userBalanceRecordTmpModel->where(['order_id'=>$list[$i]['order_num'],'user_id'=>$list[$i]['user_id'],'type'=>4])->getField('money');
                        $lists[$i]['commission'] = 0;
                        if ($estimate_money) $lists[$i]['commission'] = $estimate_money/100;
                        $shop = $shopModel->getOne(['id' => $list[$i]['shop_id']]);
                        $lists[$i]['shop_name'] =
                            ($lists[$i]['is_gift_goods'] == 'Y' && empty($shop)) ? '会员商品' : ($lists[$i]['is_gift_goods'] == 'N' && empty($shop) ? '平台自营' : $shop['merchname']);
                        $logo = $shopModel->getLogo();
                        $lists[$i]['logo'] = ($lists[$i]['is_gift_goods'] == 'Y' && empty($shop)) ? $logo[2]['logo'] : ($lists[$i]['is_gift_goods'] == 'N' && empty($shop) ? $logo[1]['logo'] : $logo[3]['logo']);
                        $lists[$i]['shop_id'] = empty($shop) ? 0 : $shop['id'];
                        if ($lists[$i]['drawback_img']) {
                            $lists[$i]['drawback_img'] = (is_url($lists[$i]['drawback_img']) ? $lists[$i]['drawback_img'] : WEB_URL . $lists[$i]['drawback_img']);
                        }
                        break;
                    case 'tb':
                        Vendor('tbk.tbk','','.class.php');
                        $tbk=new \tbk();
                        $res_goods=$tbk->getItemInfo($list[$i]['num_iid'],'2','');
                        if ($res_goods['code']==0)
                        {
                            $list[$i]['goods_img'] = $res_goods['data']['pict_url'];
                        }
                        $lists[$i]['goods_id'] = $list[$i]['num_iid'];
                        $lists[$i]['goods_name'] = $list[$i]['item_title'];
                        $lists[$i]['item_num'] = $list[$i]['item_num'];
                        $lists[$i]['order_sn'] = $list[$i]['trade_id'];
                        $lists[$i]['create_time'] = $list[$i]['create_time'];
                        $lists[$i]['pay_price'] = $list[$i]['pay_price'];
                        $lists[$i]['order_status'] = '';
                        $lists[$i]['commission'] = $list[$i]['pub_share_pre_fee'];
                        $lists[$i]['goods_img'] = $list[$i]['goods_img']?:'';
                        break;
                    case 'jd':
                        Vendor('JingDong.JdUnion','','.class.php');
                        $JdUnion=new \JdUnion();
                        $res_g = $JdUnion->getGoodsInfo($list[$i]['skuid']);
                        $lists[$i]['goods_id'] = $list[$i]['skuid'];
                        $lists[$i]['goods_name'] = $list[$i]['skuname'];
                        $lists[$i]['item_num'] = $list[$i]['skunum'];
                        $lists[$i]['order_sn'] = $list[$i]['order_id'];
                        $lists[$i]['create_time'] = date('Y-m-s h:i:s', $list[$i]['ordertime']);
                        $lists[$i]['pay_price'] = $list[$i]['payprice']?:0;
                        $lists[$i]['order_status'] = '';
                        $lists[$i]['commission'] = $list[$i]['actualcosprice'];
                        $lists[$i]['goods_img'] = $res_g['data'][0]['imgUrl'];
                        // 时间戳转换
                        $msectime = $list[$i]['ordertime'] * 0.001;
                        if(strstr($msectime,'.')){
                            sprintf("%01.3f",$msectime);
                            list($usec, $sec) = explode(".",$msectime);
                            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
                        }else{
                            $usec = $msectime;
                            $sec = "000";
                        }
                        $date = date("Y-m-d H:i:s",$usec);
                        $lists[$i]['create_time'] = str_replace('x', $sec, $date);
                        break;
                    case 'pdd':
                        $lists[$i]['goods_id'] = $list[$i]['goods_id'];
                        $lists[$i]['goods_name'] = $list[$i]['goods_name'];
                        $lists[$i]['item_num'] = $list[$i]['goods_quantity'];
                        $lists[$i]['order_sn'] = $list[$i]['order_sn'];
                        $lists[$i]['create_time'] = date('Y-m-s h:i:s', $list[$i]['order_create_time']);
                        $lists[$i]['pay_price'] = $list[$i]['order_amount'];
                        $lists[$i]['order_status'] = '';
                        $lists[$i]['commission'] = $list[$i]['pdd_commission']/100;
                        $lists[$i]['goods_img'] = $list[$i]['goods_thumbnail_url'];
                        break;
                }
                if ($from != 'self') {
                    switch ($lists[$i]['order_status']) {
                        case '0':
                        case '16':
                        case '12':
                            $lists[$i]['order_status'] = 2;
                            break;
                        case '5':
                        case '18':
                        case '3':
                            $lists[$i]['order_status'] = 3;
                            break;
                        default:
                            $lists[$i]['order_status'] = 7;
                            break;
                    }
                }
                if ($lists[$i]['goods_id']) $data = $lists;

            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 获取自营订单搜索
     * @param $where :条件
     * @param $user_id :用户id
     * @param int $status : 订单状态
     * @param int $p :分页数
     * @param int $per :条数
     * @param int $type : 订单类型
     * @return array
     */
    public function getOrderListBySelfId($where, $user_id, $status = 0, $p = 1, $per = 6, $type = 1)
    {
        $User = new \Common\Model\UserModel();
        if ($status) {
            switch ($status) {
                case 2:
                    $where .= " and status in (2,4,5)";
                    break;
                case 3:
                    $where .= " and status=10";
                    break;
                case 7:
                    $where .= " and status=7";
                    break;
            }
        } else {
            $where .= " and status in (2,4,5,7,10)";
        }
        switch ($type) {
            //本身
            case '1':
                $where .= " and user_id='$user_id'";
                break;
            //直接
            case '2':
                //获取团队列表-一级，过滤掉VIP
                $all_uid = '';
                $referrerList = $User->where("referrer_id='$user_id' and group_id in (1,2)")->field('uid')->select();
                if ($referrerList) {
                    foreach ($referrerList as $l) {
                        $all_uid .= $l['uid'] . ',';
                    }
                }
                if ($all_uid) {
                    //一级团队列表
                    $all_uid = substr($all_uid, 0, -1);
                    $where .= " and user_id in ($all_uid)";
                } else {
                    $where = '';
                    $data = array(
                        'list' => array()
                    );
                    $res = array(
                        'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg' => '成功',
                        'data' => $data
                    );
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit();
                }
                break;
            //间接
            case '3':
                //获取团队列表-一级、二级
                $all_uid = '';
                $referrerList = $User->where("referrer_id='$user_id'")->field('uid')->select();
                if ($referrerList) {
                    foreach ($referrerList as $l) {
                        $all_uid .= $l['uid'] . ',';
                    }
                }
                if ($all_uid) {
                    //一级团队列表
                    $all_uid = substr($all_uid, 0, -1);
                    //二级团队列表，过滤掉VIP
                    $all_uid2 = '';
                    $referrerList2 = $User->where("referrer_id in ($all_uid) and group_id in (1,2)")->field('uid')->select();
                    if ($referrerList2) {
                        foreach ($referrerList2 as $l) {
                            $all_uid2 .= $l['uid'] . ',';
                        }
                        $all_uid2 = substr($all_uid2, 0, -1);
                        $where .= " and user_id in ($all_uid2)";
                    } else {
                        $where = '';
                        $data = array(
                            'list' => array()
                        );
                        $res = array(
                            'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                            'msg' => '成功',
                            'data' => $data
                        );
                        echo json_encode($res, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                } else {
                    $where = '';
                    $data = array(
                        'list' => array()
                    );
                    $res = array(
                        'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg' => '成功',
                        'data' => $data
                    );
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit();
                }
                break;
            case 4:
                //获取团队分红订单列表
                $all_order_id='';
                //保存团队分红订单记录
                $TeamRewardsLog=new \Common\Model\TeamRewardsLogModel();
                $res_reward_list=$TeamRewardsLog->getRecordList($user_id,'self');
                if($res_reward_list)
                {
                    foreach ($res_reward_list as $l)
                    {
                        $all_order_id.=$l['order_id'].',';
                    }
                }
                if ($all_order_id) {
                    //团队分红订单列表
                    $all_order_id = substr($all_order_id, 0, -1);
                    $where .= " and order_num in ($all_order_id)";
                } else {
                    $where = '';
                    $data = array(
                        'list' => array()
                    );
                    $res = array(
                        'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg' => '成功',
                        'data' => $data
                    );
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit();
                }
                break;
        }
        $list = $this->where($where)->page($p, $per)->order("id desc")->select();
        if ($list !== false) {
            $Goods = new \Common\Model\GoodsModel();
            $UserMsg = $User->getUserMsg($user_id);
            //根据观看用户所在的组获取相应收益比例
            $UserGroup = new \Common\Model\UserGroupModel();
            $groupMsg = $UserGroup->getGroupMsg($UserMsg['group_id']);
            $num = count($list);
            $lists = [];
            for ($i = 0; $i < $num; $i++) {
                $orderdetail = $this->orderdeteail($list[$i]['order_num']);
                $fxstatus = $Goods->where(['goods_id'=>$orderdetail['goods_id']])->field('fx_profit_money,is_fx_goods')->find();
                if ($fxstatus['is_fx_goods'] == 'Y' && $fxstatus['fx_profit_money'] > 0) {
                    $lists[$i]['goods_id'] = $orderdetail['goods_id'];
                    $lists[$i]['goods_name'] = $orderdetail['goods_name'];
                    $lists[$i]['item_num'] = $orderdetail['num'] ? : 0;
                    $lists[$i]['order_sn'] = $list[$i]['order_num'];
                    $lists[$i]['create_time'] = $list[$i]['create_time'];
                    $lists[$i]['pay_price'] = $list[$i]['allprice']/100;
                    switch ($list[$i]['status']) {
                        case 2:
                        case 4:
                        case 5:
                            $lists[$i]['order_status'] = 2;
                            break;
                        case 10:
                            $lists[$i]['order_status'] = 3;
                            break;
                        default:
                            $lists[$i]['order_status'] = 7;
                            break;
                    }
                    $goodsMsg = $Goods->getGoodsMsg($orderdetail['goods_id']);
                    $lists[$i]['goods_img'] = $goodsMsg['tmp_img'];
                    $fxstatus['fx_profit_money'] = $fxstatus['fx_profit_money']/100;
                    //佣金、佣金比率
                    switch ($type) {
                        case 2:
                            //直推订单佣金
                            $lists[$i]['commission'] = $fxstatus['fx_profit_money'] * $groupMsg['referrer_rate']/100;
                            break;
                        case 3:
                            //间推订单佣金
                            $lists[$i]['commission'] = $fxstatus['fx_profit_money'] * $groupMsg['referrer_rate2']/100;
                            break;
                        case 4:
                            //获取团队分红订单记录
                            $TeamRewardsLog = new \Common\Model\TeamRewardsLogModel();
                            $res_reward = $TeamRewardsLog->getRecordMsg($user_id,$lists[$i]['order_num']);
                            if ($res_reward['rewards_level'] == 1){
                                $lists[$i]['commission'] = $fxstatus['fx_profit_money'] * TEAM_REWARD1/100;
                            } else {
                                $lists[$i]['commission'] = $fxstatus['fx_profit_money'] * TEAM_REWARD2/100;
                            }
                            break;
                        default:
                            //自己订单
                            $lists[$i]['commission'] = $fxstatus['fx_profit_money'] * $groupMsg['fee_user']/100;
                            break;
                    }
                    //四舍五入
                    $lists[$i]['commission'] = round($lists[$i]['commission'], 2);
                }
            }
            $lists =  array_merge($lists);
            $data = array(
                'list' => $lists ?: []
            );
            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $data
            );
        } else {
            //数据库错误
            $res = array(
                'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
            );
        }
        return $res;
    }

    protected function orderdeteail($order){
        $orderdetail = new \Common\Model\OrderDetailModel();
        return $orderdetail->where(['order_num'=>$order])->field('goods_id,goods_name,num')->find();
    }

	/**
	 * 获取同一订单下订单列表
	 * @param string $order_num:订单号
	 * @return array|boolean
	 */
	public function getOrderListByOrderNum($order_num)
	{
		$list=$this->where("order_num='$order_num'")->select();
		if($list!==false) {
			return $list;
		}else {
			return false;
		}
	}

	/**
	 * 获取订单总价
	 * @param string $order_num:订单号
	 * @return number|boolean
	 */
	public function calculateOrderPrice($order_num)
	{
		$allprice=$this->where("order_num='$order_num'")->sum('allprice');
		if($allprice!==false) {
			return $allprice;
		}else {
			return false;
		}
	}

	/**
	 * 获取用户订单统计
	 * @param int $user_id:用户ID
	 * @return multitype:unknown
	 */
	public function statistics($user_id)
	{
		$sql="SELECT count(id) as num,status FROM __PREFIX__order WHERE user_id='$user_id' GROUP BY status";
		$list=M()->query($sql);
		$num1=$num2=$num3=$num4=$num5=$num6=$num7=$num8=0;
		for ($i=1;$i<=8;$i++) {
			$num_str='num'.$i;
			foreach ($list as $l)
			{
				if($l['status']==$i)
				{
					$$num_str=(int)$l['num'];
				}
			}
			//总数
			$allnum+=$$num_str;
		}
		//退换货数量
		$num_refund=$num6+$num7+$num8;
		$res=array(
				'allnum'=>$allnum,
				'num1'=>$num1,
				'num2'=>$num2,
				'num3'=>$num3,
				'num4'=>$num4,
				'num5'=>$num5,
				'num6'=>$num6,
				'num7'=>$num7,
				'num8'=>$num8,
				'num_refund'=>$num_refund
		);
		return $res;
	}

	/**
	 * 处理已付款订单
	 * 扣除抵扣积分
	 * @param string $order_num:订单号
	 * @param string $pay_method:支付方式，wxpay微信支付、alipay支付宝支付、balance余额支付、offline线下支付
	 * @return boolean
	 */
	public function treatOrder($order_num,$pay_method)
	{
		$msg=$this->getOrderDetailByOrderNum($order_num);
		if($msg) {
			if($msg['status']=='1') {
                //只有未付款订单可以处理
                $data=array(
                    'status'=>'2',//已付款
                    'pay_time'=>date('Y-m-d H:i:s'),
                    'pay_method'=>$pay_method
                );
                if(!$this->create($data)) {
                    //验证不通过
                    return false;
				}else {
					//开启事务
					$this->startTrans();
					$res=$this->where("order_num='$order_num'")->save($data);
                    #查看是否有子订单
                    if($msg['main_order_id'] ==0)
                    {
                        $order_main = self::getMainOrderById($msg['id']);
                    }

                    $shopGoodsModel = new \Common\Model\ShopGoodsModel();
					if(empty($order_main))
                    {
                        if($res!==false) {
                            //订单详情
                            $detaillist=$msg['detail'];
                            //修改记录成功，改变用户会员组,增加会员时长
                            $uid=$msg['user_id'];
                            $User=new \Common\Model\UserModel();
                            $UserGroup=new \Common\Model\UserGroupModel();
                            $userMsg=$User->getUserMsg($uid);

                            // 查询自营商品时候是会员商品添加相应的会员组
                            $Goods=new \Common\Model\GoodsModel();
                            $goods_id=$detaillist[0]['goods_id'];
                            $goodsMsg=$Goods->where("goods_id='$goods_id'")->find();
                            //商品是不是礼包商品
                            if ($goodsMsg['is_gift_goods'] == 'Y'){
                                $groupMsg=$UserGroup->getGroupMsg($goodsMsg['group_id']);
                                //商品是否自定义会员组期限
                                if ($goodsMsg['is_custom_time'] == 'Y'){
                                    //是不是购买永久期限
                                    if ($goodsMsg['custom_time'] == 0){
                                        $add_date='+20 year';
                                        $is_forever='Y';
                                    }else{
                                        $add_date='+'.$goodsMsg['custom_time'].' day';
                                        $is_forever='N';
                                    }
                                }else{
                                    //是不是购买永久期限
                                    if ($groupMsg['time_limit'] == 0){
                                        $add_date='+20 year';
                                        $is_forever='Y';
                                    }else{
                                        $add_date='+'.$groupMsg['time_limit'].' day';
                                        $is_forever='N';
                                    }
                                }
                                //判断会员当前等级和购买的等级,只有小于等于购买的会员组才加时间，购买比自己等级低的只是购买商品
                                //购买同等级会员组商品
                                if ($userMsg['group_id']==$goodsMsg['group_id']){
                                    //增加会员时长
                                    if($userMsg['expiration_date'] and $userMsg['expiration_date']!='0000-00-00 00:00:00')
                                    {
                                        if(strtotime($userMsg['expiration_date'])>time())
                                        {
                                            //到期时间大于当前时间，延长到期时间
                                            $expiration_date=date('Y-m-d H:i:s',strtotime($add_date,strtotime($userMsg['expiration_date'])));
                                        }else {
                                            //已到期
                                            $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
                                        }
                                    }else {
                                        //未设置到期时间
                                        $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
                                    }

                                    //用户信息
                                    $data_u=array(
                                        'group_id'=>$goodsMsg['group_id'],
                                        'expiration_date'=>$expiration_date,//会员到期时间
                                        'is_forever'=>$is_forever,
                                    );
                                    if(!$User->create($data_u))
                                    {
                                        //验证不通过
                                        //回滚
                                        $this->rollback();
                                        return false;
                                    }else {
                                        //修改会员组
                                        $res_u=$User->where("uid='$uid'")->save($data_u);
                                        if($res_u==false)
                                        {
                                            //回滚
                                            $this->rollback();
                                            return false;
                                        }
                                        //极光推送消息
                                        Vendor('jpush.jpush','','.class.php');
                                        $jpush=new \jpush();
                                        $alias=$uid;//推送别名
                                        $title=APP_NAME.'通知您会员升级啦';
                                        $content='恭喜您，升级成为：'.$groupMsg['title'].'。有效期至：'.$expiration_date;
                                        $key='mall';
                                        $value='upgrade';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                    }
                                }
                                elseif ($userMsg['group_id']<$goodsMsg['group_id']){
                                    //增加会员时长
                                    $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));

                                    //用户信息
                                    $data_u=array(
                                        'group_id'=>$goodsMsg['group_id'],
                                        'expiration_date'=>$expiration_date,//会员到期时间
                                        'is_forever'=>$is_forever,
                                    );
                                    if(!$User->create($data_u))
                                    {
                                        //验证不通过
                                        //回滚
                                        $this->rollback();
                                        return false;
                                    }else {

                                        //修改会员组
                                        $res_u=$User->where("uid='$uid'")->save($data_u);
                                        if($res_u==false)
                                        {
                                            //回滚
                                            $this->rollback();
                                            return false;
                                        }
                                        //极光推送消息
                                        Vendor('jpush.jpush','','.class.php');
                                        $jpush=new \jpush();
                                        $alias=$uid;//推送别名
                                        $title=APP_NAME.'通知您会员升级啦';
                                        $content='恭喜您，升级成为：'.$groupMsg['title'].'。有效期至：'.$expiration_date;
                                        $key='mall';
                                        $value='upgrade';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                    }
                                }
                                #佣金发放
                                #查找推荐人

                                if($userMsg['referrer_id']>0)
                                {

                                    #获取推荐人信息
                                    $referrer_id=$userMsg['referrer_id'];
                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                    $userGroupMsg=$UserGroup->getGroupMsg($referrerMsg['group_id']);
                                    #开始计算佣金
                                    $all_ref = $goodsMsg['profit_money'];
                                    $ref_1 = $all_ref*$userGroupMsg['gift_referrer_tate']/10000;
                                    //增加推荐人余额
                                    $data_balance=array(
                                        'balance'=>$referrerMsg['balance']+$ref_1,
                                    );
                                    if($ref_1 >0)
                                    {
                                        $res_balance=$User->where("uid='$referrer_id'")->save($data_balance);
                                        //保存余额变动记录
                                        $all_money=$referrerMsg['balance']+$ref_1;

                                        $res_balance_log=$UserBalanceRecord->addLog($referrer_id, $ref_1, $all_money, 'recommend1');

                                        if($res_balance!==false and $res_balance_log!==false)
                                        {
                                            if($referrerMsg['referrer_id'] >0)
                                            {
                                                #获取推荐人信息
                                                $referrer_id2=$referrerMsg['referrer_id'];
                                                $referrerMsg2=$User->getUserMsg($referrer_id2);
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $userGroupMsg=$UserGroup->getGroupMsg($referrerMsg2['group_id']);
                                                $ref_2 = $all_ref*$userGroupMsg['gift_referrer_tate2']/10000;
                                                if($ref_2>0)
                                                {
                                                    //增加推荐人余额
                                                    $data_balance2=array(
                                                        'balance'=>$referrerMsg2['balance']+$ref_2,
                                                    );
                                                    $res_balance2=$User->where("uid='$referrer_id2'")->save($data_balance2);
                                                    //保存余额变动记录
                                                    $all_money2=$referrerMsg2['balance']+$ref_2;
                                                    $res_balance_log2=$UserBalanceRecord->addLog($referrer_id2, $ref_2, $all_money2, 'recommend2');

                                                    if($res_balance2==false or $res_balance_log2==false)
                                                    {
                                                        //回滚
                                                        $this->rollback();
                                                        return false;
                                                    }
                                                }
                                            }
                                        }else{
                                            $this->rollback();
                                            return false;
                                        }
                                    }
                                }
                            }

//						$Goods=new \Common\Model\GoodsModel();
                            $GoodsSku=new \Common\Model\GoodsSkuModel();
                            foreach ($detaillist as $dl) {
                                //增加商品销量、减少库存
                                $goods_id=$dl['goods_id'];
                                $goods_num=$dl['num'];
                                $GoodsMsg=$Goods->getGoodsMsg($goods_id);
                                $shopGoods = $shopGoodsModel->getGoodsInfo(['id'=>$GoodsMsg['ren_good_id']]);
                                $res_goods_sales_volume=$Goods->where("goods_id='$goods_id'")->setInc('sales_volume',$goods_num);
                                if($goods_num<=$GoodsMsg['inventory'])
                                {
                                    $inventory_dec=$goods_num;
                                }else {
                                    $inventory_dec=$GoodsMsg['inventory'];
                                }
                                #如果是付款减库存
//                                $shopGoodsModel->synStock($shopGoods['id'],-$goods_num);
//                                if($dl['goods_sku'])
//                                {
//                                    $shopGoodsModel->synStock($shopGoods['id'],-$goods_num);
//                                }else{
//                                    $shopGoodsOptionModel = new \Common\Model\ShopGoodsOptionModel();
//                                    $shopGoodsOptionModel->synStock($shopGoods['id'],-$goods_num,$dl['goods_sku']);
//                                }

                                $res_goods_inventory=$Goods->where("goods_id='$goods_id'")->setDec('inventory',$inventory_dec);
                                //如果存在属性配置商品，则相应减少该配置的商品库存
                                if($dl['sku'])
                                {
                                    $sku=$dl['sku'];
                                    $skuMsg=$GoodsSku->getSkuMsg($sku,$goods_id);
                                    if($skuMsg)
                                    {
                                        if($goods_num<=$skuMsg['inventory'])
                                        {
                                            $inventory_dec=$goods_num;
                                        }else {
                                            $inventory_dec=$skuMsg['inventory'];
                                        }
                                        $res_goods_sku=$GoodsSku->where("goods_id='$goods_id' and sku='$sku'")->setDec('inventory',$inventory_dec);
                                    }else {
                                        $res_goods_sku=true;
                                    }
                                }else {
                                    $res_goods_sku=true;
                                }
                                if($res_goods_sales_volume!==false and $res_goods_inventory!==false and $res_goods_sku!==false)
                                {
                                    //继续
                                    continue;
                                }else {
                                    //修改商品库存、销量失败
                                    //回滚
                                    $this->rollback();
                                    return false;
                                }
                            }
                            if($pay_method=='balance')
                            {
                                //减少用户余额
                                $User=new \Common\Model\UserModel();
                                $uid=$msg['user_id'];
                                $userMsg=$User->getUserMsg($uid);
                                $money=$msg['allprice'];
                                $res_balance=$User->where("uid='$uid'")->setDec('balance',$money);
                                //保存余额变动记录
                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                $all_money=$userMsg['balance']-$money;
                                $res_balance_record=$UserBalanceRecord->addLog($uid, $money, $all_money, 'shop_buy');
                            }else {
                                $res_balance=true;
                                $res_balance_record=true;
                            }
                            if($res_balance!==false and $res_balance_record!==false){
                                //如果使用了抵扣积分，扣除用户相应积分
                                if($msg['deduction_point']>0) {
                                    //减少用户积分
                                    $point=$msg['deduction_point'];
                                    $uid=$msg['user_id'];
                                    $User=new \Common\Model\UserModel();
                                    $userMsg=$User->getUserMsg($uid);
                                    $res_point=$User->where("uid='$uid'")->setDec('point',$point);
                                    //保存积分变动记录
                                    $UserPointRecord=new \Common\Model\UserPointRecordModel();
                                    $all_point=$userMsg['point']-$point;
                                    $res_point_record=$UserPointRecord->addLog($uid, $point,$all_point, 'buy_d');
                                    if($res_point!==false and $res_point_record!==false) {
                                        //成功，提交事务
                                        $this->commit();
                                        $this->synStock($msg['id'],1);
                                        return true;
                                    }else {
                                        //修改用户积分失败
                                        //回滚
                                        $this->rollback();
                                        return false;
                                    }
                                }else {
                                    //成功，提交事务
                                    $this->commit();
                                    $this->synStock($msg['id'],1);
                                    return true;
                                }
                            }else {
                                //成功，提交事务
                                $this->commit();
                                $this->synStock($msg['id'],1);
                                return true;
                            }
                        }
                        else {
                            //修改订单状态失败
                            //回滚
                            $this->rollback();
                            return false;
                        }
                    }
					else{
					    for ($i=0;$i<count($order_main); $i++)
                        {
                            $msg=$this->getOrderDetailByOrderNum($order_main[$i]['order_num']);

                            if($msg) {
                                if ($msg['status'] == '1') {
                                    //只有未付款订单可以处理
                                    $data = array(
                                        'status' => '2',//已付款
                                        'pay_time' => date('Y-m-d H:i:s'),
                                        'pay_method' => $pay_method
                                    );
                                    if (!$this->create($data)) {
                                        //验证不通过
                                        return false;
                                    } else {
                                        //开启事务
                                        $this->startTrans();
                                        $order_num = $order_main[$i]['order_num'];
                                        $res=$this->where("order_num='$order_num'")->save($data);
                                        if($res!==false) {
                                            //订单详情
                                            $detaillist=$msg['detail'];
                                            //修改记录成功，改变用户会员组,增加会员时长
                                            $uid=$msg['user_id'];
                                            $User=new \Common\Model\UserModel();
                                            $UserGroup=new \Common\Model\UserGroupModel();
                                            $userMsg=$User->getUserMsg($uid);

                                            // 查询自营商品时候是会员商品添加相应的会员组
                                            $Goods=new \Common\Model\GoodsModel();
                                            $goods_id=$detaillist[0]['goods_id'];
                                            $goodsMsg=$Goods->where("goods_id='$goods_id'")->find();
                                            //商品是不是礼包商品
                                            if ($goodsMsg['is_gift_goods'] == 'Y'){
                                                $groupMsg=$UserGroup->getGroupMsg($goodsMsg['group_id']);
                                                //商品是否自定义会员组期限
                                                if ($goodsMsg['is_custom_time'] == 'Y'){
                                                    //是不是购买永久期限
                                                    if ($goodsMsg['custom_time'] == 0){
                                                        $add_date='+20 year';
                                                        $is_forever='Y';
                                                    }else{
                                                        $add_date='+'.$goodsMsg['custom_time'].' day';
                                                        $is_forever='N';
                                                    }
                                                }else{
                                                    //是不是购买永久期限
                                                    if ($groupMsg['time_limit'] == 0){
                                                        $add_date='+20 year';
                                                        $is_forever='Y';
                                                    }else{
                                                        $add_date='+'.$groupMsg['time_limit'].' day';
                                                        $is_forever='N';
                                                    }
                                                }
                                                //判断会员当前等级和购买的等级,只有小于等于购买的会员组才加时间，购买比自己等级低的只是购买商品
                                                //购买同等级会员组商品
                                                if ($userMsg['group_id']==$goodsMsg['group_id']){
                                                    //增加会员时长
                                                    if($userMsg['expiration_date'] and $userMsg['expiration_date']!='0000-00-00 00:00:00')
                                                    {
                                                        if(strtotime($userMsg['expiration_date'])>time())
                                                        {
                                                            //到期时间大于当前时间，延长到期时间
                                                            $expiration_date=date('Y-m-d H:i:s',strtotime($add_date,strtotime($userMsg['expiration_date'])));
                                                        }else {
                                                            //已到期
                                                            $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
                                                        }
                                                    }else {
                                                        //未设置到期时间
                                                        $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
                                                    }

                                                    //用户信息
                                                    $data_u=array(
                                                        'group_id'=>$goodsMsg['group_id'],
                                                        'expiration_date'=>$expiration_date,//会员到期时间
                                                        'is_forever'=>$is_forever,
                                                    );
                                                    if(!$User->create($data_u))
                                                    {
                                                        //验证不通过
                                                        //回滚
                                                        $this->rollback();
                                                        return false;
                                                    }else {
                                                        //修改会员组
                                                        $res_u=$User->where("uid='$uid'")->save($data_u);
                                                        if($res_u==false)
                                                        {
                                                            //回滚
                                                            $this->rollback();
                                                            return false;
                                                        }
                                                        //极光推送消息
                                                        Vendor('jpush.jpush','','.class.php');
                                                        $jpush=new \jpush();
                                                        $alias=$uid;//推送别名
                                                        $title=APP_NAME.'通知您会员升级啦';
                                                        $content='恭喜您，升级成为：'.$groupMsg['title'].'。有效期至：'.$expiration_date;
                                                        $key='mall';
                                                        $value='upgrade';
                                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                    }
                                                }elseif ($userMsg['group_id']<$goodsMsg['group_id']){
                                                    //增加会员时长
                                                    $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));

                                                    //用户信息
                                                    $data_u=array(
                                                        'group_id'=>$goodsMsg['group_id'],
                                                        'expiration_date'=>$expiration_date,//会员到期时间
                                                        'is_forever'=>$is_forever,
                                                    );
                                                    if(!$User->create($data_u))
                                                    {
                                                        //验证不通过
                                                        //回滚
                                                        $this->rollback();
                                                        return false;
                                                    }else {
                                                        //修改会员组
                                                        $res_u=$User->where("uid='$uid'")->save($data_u);
                                                        if($res_u==false)
                                                        {
                                                            //回滚
                                                            $this->rollback();
                                                            return false;
                                                        }
                                                        //极光推送消息
                                                        Vendor('jpush.jpush','','.class.php');
                                                        $jpush=new \jpush();
                                                        $alias=$uid;//推送别名
                                                        $title=APP_NAME.'通知您会员升级啦';
                                                        $content='恭喜您，升级成为：'.$groupMsg['title'].'。有效期至：'.$expiration_date;
                                                        $key='mall';
                                                        $value='upgrade';
                                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                                    }
                                                }

                                                #佣金发放
                                                #查找推荐人
                                                if($userMsg['referrer_id']>0)
                                                {

                                                    #获取推荐人信息
                                                    $referrer_id=$userMsg['referrer_id'];
                                                    $referrerMsg=$User->getUserMsg($referrer_id);
                                                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                    $userGroupMsg=$UserGroup->getGroupMsg($referrerMsg['group_id']);
                                                    #开始计算佣金
                                                    $all_ref = $goodsMsg['profit_money'];
                                                    $ref_1 = $all_ref*$userGroupMsg['gift_referrer_tate']/10000;
                                                    //增加推荐人余额
                                                    $data_balance=array(
                                                        'balance'=>$referrerMsg['balance']+$ref_1,
                                                    );
                                                    if($ref_1 >0)
                                                    {
                                                        $res_balance=$User->where("uid='$referrer_id'")->save($data_balance);
                                                        //保存余额变动记录
                                                        $all_money=$referrerMsg['balance']+$ref_1;
                                                        $res_balance_log=$UserBalanceRecord->addLog($referrer_id, $ref_1, $all_money, 'recommend1');
                                                        if($res_balance!==false and $res_balance_log!==false)
                                                        {
                                                            if($referrerMsg['referrer_id'] >0)
                                                            {
                                                                #获取推荐人信息
                                                                $referrer_id2=$referrerMsg['referrer_id'];
                                                                $referrerMsg2=$User->getUserMsg($referrer_id2);
                                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                                $userGroupMsg=$UserGroup->getGroupMsg($referrerMsg2['group_id']);
                                                                $ref_2 = $all_ref*$userGroupMsg['gift_referrer_tate2']/10000;
                                                                if($ref_2>0)
                                                                {
                                                                    //增加推荐人余额
                                                                    $data_balance2=array(
                                                                        'balance'=>$referrerMsg2['balance']+$ref_2,
                                                                    );
                                                                    $res_balance2=$User->where("uid='$referrer_id2'")->save($data_balance2);
                                                                    //保存余额变动记录
                                                                    $all_money2=$referrerMsg2['balance']+$ref_2;
                                                                    $res_balance_log2=$UserBalanceRecord->addLog($referrer_id2, $ref_2, $all_money2, 'recommend2');

                                                                    if($res_balance2==false or $res_balance_log2==false)
                                                                    {
                                                                        //回滚
                                                                        $this->rollback();
                                                                        return false;
                                                                    }
                                                                }
                                                            }
                                                        }else{
                                                            $this->rollback();
                                                            return false;
                                                        }
                                                    }
                                                }
                                            }

//						$Goods=new \Common\Model\GoodsModel();
                                            $GoodsSku=new \Common\Model\GoodsSkuModel();
                                            foreach ($detaillist as $dl) {
                                                //增加商品销量、减少库存
                                                $goods_id=$dl['goods_id'];
                                                $goods_num=$dl['num'];
                                                $GoodsMsg=$Goods->getGoodsMsg($goods_id);
                                                $res_goods_sales_volume=$Goods->where("goods_id='$goods_id'")->setInc('sales_volume',$goods_num);
                                                if($goods_num<=$GoodsMsg['inventory'])
                                                {
                                                    $inventory_dec=$goods_num;
                                                }else {
                                                    $inventory_dec=$GoodsMsg['inventory'];
                                                }
                                                #如果是付款减库存
                                                if($goodsMsg['ren_good_id'] >0 )
                                                {
                                                    $shopGoods = $shopGoodsModel->getGoodsInfo(['id'=>$GoodsMsg['ren_good_id']]);
                                                    if($shopGoods['totalcnf']==1)
                                                    {
                                                        $shopGoodsModel->synStock($shopGoods['id'],-$goods_num);
                                                        if($dl['goods_sku'])
                                                        {
                                                            $shopGoodsModel->synStock($shopGoods['id'],-$goods_num);
                                                        }else{
                                                            $shopGoodsOptionModel = new \Common\Model\ShopGoodsOptionModel();
                                                            $shopGoodsOptionModel->synStock($shopGoods['id'],-$goods_num,$dl['goods_sku']);
                                                        }
                                                    }
                                                }
                                                $res_goods_inventory=$Goods->where("goods_id='$goods_id'")->setDec('inventory',$inventory_dec);
                                                //如果存在属性配置商品，则相应减少该配置的商品库存
                                                if($dl['sku'])
                                                {
                                                    $sku=$dl['sku'];
                                                    $skuMsg=$GoodsSku->getSkuMsg($sku,$goods_id);
                                                    if($skuMsg)
                                                    {
                                                        if($goods_num<=$skuMsg['inventory'])
                                                        {
                                                            $inventory_dec=$goods_num;
                                                        }else {
                                                            $inventory_dec=$skuMsg['inventory'];
                                                        }
                                                        $res_goods_sku=$GoodsSku->where("goods_id='$goods_id' and sku='$sku'")->setDec('inventory',$inventory_dec);
                                                    }else {
                                                        $res_goods_sku=true;
                                                    }
                                                }else {
                                                    $res_goods_sku=true;
                                                }
                                                if($res_goods_sales_volume!==false and $res_goods_inventory!==false and $res_goods_sku!==false)
                                                {
                                                    //继续
                                                    continue;
                                                }else {
                                                    //修改商品库存、销量失败
                                                    //回滚
                                                    $this->rollback();
                                                    return false;
                                                }
                                            }
                                            if($pay_method=='balance')
                                            {
                                                //减少用户余额
                                                $User=new \Common\Model\UserModel();
                                                $uid=$msg['user_id'];
                                                $userMsg=$User->getUserMsg($uid);
                                                $money=$msg['allprice'];
                                                $res_balance=$User->where("uid='$uid'")->setDec('balance',$money);
                                                //保存余额变动记录
                                                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                                $all_money=$userMsg['balance']-$money;
                                                $res_balance_record=$UserBalanceRecord->addLog($uid, $money, $all_money, 'shop_buy');
                                            }else {
                                                $res_balance=true;
                                                $res_balance_record=true;
                                            }
                                            if($res_balance!==false and $res_balance_record!==false){
                                                //如果使用了抵扣积分，扣除用户相应积分
                                                if($msg['deduction_point']>0) {
                                                    //减少用户积分
                                                    $point=$msg['deduction_point'];
                                                    $uid=$msg['user_id'];
                                                    $User=new \Common\Model\UserModel();
                                                    $userMsg=$User->getUserMsg($uid);
                                                    $res_point=$User->where("uid='$uid'")->setDec('point',$point);
                                                    //保存积分变动记录
                                                    $UserPointRecord=new \Common\Model\UserPointRecordModel();
                                                    $all_point=$userMsg['point']-$point;
                                                    $res_point_record=$UserPointRecord->addLog($uid, $point,$all_point, 'buy_d');
                                                    if($res_point!==false and $res_point_record!==false) {
                                                        //成功，提交事务
                                                        $this->commit();
                                                        continue;
                                                    }else {
                                                        //修改用户积分失败
                                                        //回滚
                                                        $this->rollback();
                                                        return false;
                                                    }
                                                }else {
                                                    //成功，提交事务
                                                    $this->commit();

                                                }
                                            }else {
                                                //成功，提交事务
                                                $this->commit();

                                            }
                                        }
                                        else {
                                            //修改订单状态失败
                                            //回滚
                                            $this->rollback();
                                            return false;
                                        }
                                    }
                                }else{
                                    return false;
                                }
                            }else{
                                return false;
                            }
                        }
					    $this->synStock($msg['id'],1);
                        return true;
                    }
				}
			}else {
				return false;
			}
		}else {
			return false;
		}
	}

	/**
	 * 处理确认收货订单
	 * 赠送积分
	 * @param string $order_num:订单号
	 * @return boolean
	 */
	public function confirmOrder($order_num)
	{
	    $msg=$this->getOrderDetailByOrderNum($order_num);
	    if($msg) {
	        if($msg['status']=='3') {
	            //只有已发货订单可以处理
	            $data=array(
	                'status'=>'4',//已确认收货
	                'finish_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)) {
	                //验证不通过
	                return false;
	            }else {
	                //开启事务
	                $this->startTrans();
	                $res=$this->where("order_num='$order_num'")->save($data);
	                //如果赠送积分，给用户添加相应积分
	                if($msg['give_point']>0) {
	                    //增加用户积分
	                    $point=$msg['give_point'];
	                    $uid=$msg['user_id'];
	                    $User=new \Common\Model\UserModel();
	                    $userMsg=$User->getUserMsg($uid);
	                    $res_point=$User->where("uid='$uid'")->setInc('point',$point);
	                    //保存积分变动记录
	                    $UserPointRecord=new \Common\Model\UserPointRecordModel();
	                    $all_point=$userMsg['point']+$point;
	                    $res_point_record=$UserPointRecord->addLog($uid, $point,$all_point, 'buy');
	                }else {
	                    $res_point=$res_point_record=true;
	                }
	                #如果是商户订单，同步商户端信息
                    $shopOrderModel = new \Common\Model\ShopOrderModel();
                    $res_shop_order =true;
	                if($msg['ren_order_id'] >0)
                    {
                        $shopOrder = $shopOrderModel->getOne(['id'=>$msg['ren_order_id']]);
                        if($shopOrder)
                        {
                            $res_shop_order = $shopOrderModel->where(['id'=>$msg['ren_order_id']])->save(['status'=>3,'finishtime'=>time()]);
                        }
                    }
	                if( $res!==false and $res_point!==false and $res_point_record!==false and $res_shop_order !== false) {
	                    //成功，提交事务
	                    $this->commit();
	                    return true;
	                }else {
	                    //修改订单状态失败
	                    //回滚
	                    $this->rollback();
	                    return false;
	                }
	            }
	        }else {
	            return false;
	        }
	    }else {
	        return false;
	    }
	}

	/**
	 * 处理退款订单
	 * @param int $id:订单ID
	 * @param char $check_result:审核结果，Y通过、N不通过
	 * @param string $drawback_refuse_reason:拒绝退款理由
	 */
	public function refund($id,$check_result,$drawback_refuse_reason='')
	{
	    $msg=$this->getOrderMsg($id);
	    if($msg['status']=='6'){
	        //申请退款状态可以处理
	        if($check_result=='Y'){
	            //同意
	            $data=array(
	                'status'=>'7',//同意退款
	                'refund_success_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)){
	                //验证不通过
	                return false;
	            }else {
	                //开启事务
	                $this->startTrans();
	                $res_save=$this->where("id=$id")->save($data);
	                if($res_save!==false){
	                    //将订单金额退还给用户
	                    $user_id=$msg['user_id'];
	                    $money=$msg['allprice'];
	                    $User=new \Common\Model\UserModel();
	                    $userMsg=$User->getUserMsg($user_id);
	                    $res_balance=$User->where("uid=$user_id")->setInc('balance',$money);
	                    //保存余额记录
	                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
	                    $all_money=$userMsg['balance']-$money;
                        if ($all_money < 0) $all_money = 0;
	                    $res_balance_record=$UserBalanceRecord->addLog($user_id, $money, $all_money, 'goods_back');
	                    if($res_balance!==false and $res_balance_record!==false){
	                        //如果使用了抵扣积分，扣除用户相应积分
	                        if($msg['deduction_point']>0) {
	                            //减少用户积分
	                            $point=$msg['deduction_point'];
	                            $res_point=$User->where("uid='$user_id'")->setInc('point',$point);
	                            //保存积分变动记录
	                            $UserPointRecord=new \Common\Model\UserPointRecordModel();
	                            $all_point=$userMsg['point']+$point;
	                            $res_point_record=$UserPointRecord->addLog($user_id, $point,$all_point, 'buy_refund');
	                            if($res_point!==false and $res_point_record!==false) {

	                            }else {
	                                //修改用户积分失败
	                                //回滚
	                                $this->rollback();
	                                return false;
	                            }
	                        }
	                        //提交事务
	                        $this->commit();
	                        return true;
	                    }else {
	                        //回滚
	                        $this->rollback();
	                        return false;
	                    }
	                }else {
	                    //回滚
	                    $this->rollback();
	                    return false;
	                }
	            }
	        }else {
	            //拒绝
	            $data=array(
	                'status'=>'8',//拒绝
	                'drawback_refuse_reason'=>$drawback_refuse_reason,
	                'refund_fail_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)){
	                //验证不通过
	                return false;
	            }else {
	                $res_save=$this->where("id=$id")->save($data);
	                if($res_save!==false){
	                    return true;
	                }else {
	                    return false;
	                }
	            }
	        }
	    }else {
	        return false;
	    }
	}

    /**
     * 自营返利处理
     * @param $order array  订单数组
     */
	public function treat($order)
    {
        $userModel = new \Common\Model\UserModel();
        $orderGoodsModel = new \Common\Model\OrderDetailModel();
//        $groupModel = new \Common\Model\UserGroupModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
//        $group = $groupModel->getGroupMsg($userMsg['group_id']);
        $hostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostGroup = $hostCommissionModel->where('id=1')->find();
        if($userMsg)
        {
            #基于订单类型     如果是带货订单，走带货佣金处理
            $order_goods = $orderGoodsModel->getOrderDetail($order['id']);
            $fx_proify_money = 0;
            foreach ($order_goods as $l)
            {
                $fx_proify_money += $l['fx_profit_money']/100;
            }
            if($order['is_host'] == 'Y' && $fx_proify_money>0)
            {
                self::host_commission($fx_proify_money,$order);
            }
            else{
                if($fx_proify_money >0)
                {
                    self::self_commission($fx_proify_money,$order);
                }else{
                    return  true;
                }
            }
        }else{
            return false;
        }
    }

    #自购返佣
    public function self_commission($fx_proify_money,$order)
    {
        $userModel = new \Common\Model\UserModel();
        $orderGoodsModel = new \Common\Model\OrderDetailModel();
        $groupModel = new \Common\Model\UserGroupModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
        $group = $groupModel->getGroupMsg($userMsg['group_id']);
        #--------------------------------------  自购返佣  --------------------------------------------------
        #给自己返佣
        $u_proify_money = $fx_proify_money*$group['fee_user']/100;
        $uid = $userMsg['uid'];
        //开启事务
        $userModel->startTrans();

        //四舍五入
        $money_user=round($u_proify_money, 2);
        //佣金-扣税
        $money_service=$fx_proify_money*$group['fee_service']/100;
        //四舍五入
        $money_service=round($money_service, 2);
        //佣金-平台
        $money_plantform=$fx_proify_money*$group['fee_plantform']/100;
        //四舍五入
        $money_plantform=round($money_plantform, 2);
        $data_user=array(
            //'balance'=>$UserMsg['balance']+$money_user,
            'balance'=>$userMsg['balance']+$money_user
        );
        writeLog(json_encode(['money'=>$fx_proify_money,'userGroup'=>$group,'uid'=>$uid]),'commission_log1');
        //增加用户余额
        $res_balance=$userModel->where("uid='$uid'")->save($data_user);
        //保存余额变动记录
        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
        $all_money=$userMsg['balance']+$money_user;
        $res_record=$UserBalanceRecord->addLog($uid, $money_user, $all_money, 'self','2',$order['order_num'],'4');
        $this->where(['order_num'=>$order['order_num'],'user_id'=>$order['user_id']])->save(['status'=>10]);
        $is_vip = 0;
        #如果有推荐人的情况
        if($res_balance !== false && in_array($userMsg['group_id'],['1','2']) && $res_record!==false)
        {
            if(empty($userMsg['referrer_id']))
            {
                $userModel->commit();
                return true;
            }
            $refMsg1 = $userModel->getUserMsg($userMsg['referrer_id']);
            if($refMsg1)
            {
                $group1 = $groupModel->getGroupMsg($refMsg1['group_id']);
                if($refMsg1['group_id']>2)
                {
                    $is_vip = 1;
                }
                #推荐人返佣
                $ref1_proify_money = round($fx_proify_money*($group['fee_service']/100+$group['fee_plantform']/100)*($group1['referrer_rate']/100),2);
                if($ref1_proify_money>0)
                {
                    //增加用户余额
                    $res_balance1=$userModel->where("uid='{$refMsg1['uid']}'")->save(['balance'=>$ref1_proify_money]);

                    #如果还有推荐人的情况下
                    if($res_balance1!==false)
                    {
                        if(empty($refMsg1['referrer_id']))
                        {
                            $userModel->commit();
                            return true;
                        }
                        //保存余额变动记录
                        $all_money1=$refMsg1['balance']+$ref1_proify_money;
                        $res_record1=$UserBalanceRecord->addLog($refMsg1['uid'], $ref1_proify_money, $all_money1, 'self_r','2',$order['order_num'],'4');

                        $refMsg2 = $userModel->getUserMsg($refMsg1['referrer_id']);
                        if($res_record1!==false && $refMsg2)
                        {
                            $group2 = $groupModel->getGroupMsg($refMsg2['group_id']);
                            if($refMsg1['group_id']>2)
                            {
                                $is_vip += 1;
                            }
                            #推荐人返佣
                            $ref2_proify_money = $fx_proify_money*($group['fee_service']/100+$group['fee_plantform']/100)*($group2['referrer_rate2']/100);
                            if($ref2_proify_money >0)
                            {
                                //增加用户余额
                                $res_balance2=$userModel->where("uid='{$refMsg2['uid']}'")->save(['balance'=>$ref2_proify_money]);
                                if($res_balance2!==false)
                                {
                                    //保存余额变动记录
                                    $all_money2=$refMsg2['balance']+$ref1_proify_money;
                                    $res_record2=$UserBalanceRecord->addLog($refMsg2['uid'], $ref2_proify_money, $all_money2, 'self_r2','2',$order['order_num'],'4');
                                    //提交事务
                                    if($res_record2 !== false)
                                    {
                                        if($refMsg2['referrer_id']>0)
                                        {
                                            $teamList = $userModel->getUserInfoByGroup($refMsg2['referrer_id']);
                                            if(!empty($teamList))
                                            {
                                                #如果没有走完团队分红
                                                if($is_vip<2)
                                                {
                                                    for ($t=0;count($teamList);$t++)
                                                    {
                                                        $team_id = $teamList[$t];
                                                        $team1Msg = $userModel->getUserMsg($team_id);
                                                        if($is_vip==1 && $is_vip<2)
                                                        {
                                                            $referrer_money_team=$fx_proify_money*(defined('TEAM_REWARD2')?TEAM_REWARD2:0)/100;
                                                            if($referrer_money_team>0 && $team1Msg)
                                                            {
                                                                //增加用户余额
                                                                $team_balance1=$userModel->where("uid='{$team1Msg['uid']}'")->save(['balance'=>$referrer_money_team]);
                                                                //保存余额变动记录
                                                                $team1_money=$refMsg2['balance']+$referrer_money_team;
                                                                $team_record1=$UserBalanceRecord->addLog($team1Msg['uid'], $referrer_money_team, $team1_money, 'tbk_rt','2',$order['order_num'],'4');
                                                                if($team_record1 !== false && $team_balance1 !== false)
                                                                {
                                                                    $userModel->commit();
                                                                    $is_vip++;
                                                                    continue;
                                                                    return true;
                                                                }else{
                                                                    $userModel->rollback();
                                                                    break;
                                                                    return false;
                                                                }
                                                            }
                                                            $userModel->commit();
                                                            return true;
                                                        }else{
                                                            $referrer_money_team=$fx_proify_money*(defined('TEAM_REWARD1')?TEAM_REWARD1:0)/100;
                                                            if($referrer_money_team>0 && $team1Msg)
                                                            {
                                                                //增加用户余额
                                                                $team_balance1=$userModel->where("uid='{$team1Msg['uid']}'")->save(['balance'=>$referrer_money_team]);
                                                                //保存余额变动记录
                                                                $team1_money=$refMsg2['balance']+$referrer_money_team;
                                                                $team_record1=$UserBalanceRecord->addLog($team1Msg['uid'], $referrer_money_team, $team1_money, 'tbk_rt','2',$order['order_num'],'4');
                                                                if($team_record1 !== false && $team_balance1 !== false)
                                                                {
                                                                    $userModel->commit();
                                                                    $is_vip++;
                                                                    continue;
                                                                }else{
                                                                    $userModel->rollback();
                                                                    break;
                                                                    return false;
                                                                }
                                                            }
                                                            $userModel->commit();
                                                            return true;
                                                        }
                                                    }
                                                    $userModel->commit();
                                                    return true;
                                                }else{
                                                    $userModel->commit();
                                                    return true;
                                                }
                                            }else{
                                                $userModel->commit();
                                                return true;
                                            }
                                        }else{
                                            $userModel->commit();
                                            return true;
                                        }
                                    }else{
                                        $userModel->rollback();
                                        return false;
                                    }
                                }else{
                                    //回滚
                                    $userModel->rollback();
                                    return false;
                                }
                            }
                        }else{
                            //回滚
                            $userModel->rollback();
                            return false;
                        }
                    }else{
                        //回滚
                        $userModel->rollback();
                        return false;
                    }
                }else{
                    $userModel->commit();
                    return true;
                }
            }else{
                $userModel->commit();
                return true;
            }
        }else{
            //回滚
            $userModel->rollback();
            return false;
        }
    }

    #带货返佣
    public function host_commission($fx_proify_money,$order)
    {
        $userModel = new \Common\Model\UserModel();
        $orderGoodsModel = new \Common\Model\OrderDetailModel();
        $groupModel = new \Common\Model\UserGroupModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
        $hostMsg = $userModel->getUserMsg($order['host_id']);
        $group = $groupModel->getGroupMsg($userMsg['group_id']);
        $hostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostGroup = $hostCommissionModel->where('id=1')->find();
        #没有用户信息直接返回
        if ($userMsg || $hostMsg) {
            return false;
        }
        #--------------------------------------  带货返佣  --------------------------------------------------
        #给主播返佣
        $host_proify_money = $fx_proify_money * (1 - ($hostGroup['fee_service'] / 100 + $hostGroup['fee_plantform'] / 100));
        #带货佣金
        $zhu_proify_money = $host_proify_money * $hostGroup['fee_host'] / 100;
        $host_user = $userModel->getUserMsg($order['host_id']);
        #一级经纪人佣金
        $jingjiren1 = $host_proify_money * $hostGroup['broker_rate'] / 100;
        #二级经纪人佣金
        $jingjiren2 = $host_proify_money * $hostGroup['broker_rate2'] / 100;
        #用户返佣佣金
        $user_proify_money = $host_proify_money * $hostGroup['fee_user'] / 100;
        #团队分红情况
        $is_vip = 0;

        #------------------------   开始发放佣金   ---------------------------
        #先发放带货的佣金
        $data_user = ['balance' => $hostMsg['balance'] + $host_proify_money];
        $host_id = $hostMsg['uid'];
        $res_balance = $userModel->where("uid='{$host_id}'")->save($data_user);
        //保存余额变动记录
        $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
        $all_money = $hostMsg['balance'] + $host_proify_money;
        $res_record = $UserBalanceRecord->addLog($host_id, $host_proify_money, $all_money, 'self', '2', $order['order_num'], '4');
        $this->where(['order_num'=>$order['order_num'],'user_id'=>$order['user_id']])->save(['status'=>10]);
        $userModel->startTrans();
        if ($res_record === false || $res_balance === false) {
            $userModel->rollback();
            return false;
        }
        #找带货的一级经纪人
        $host1Msg = $userModel->getUserMsg($hostMsg['referrer_id']);
        if ($host1Msg && $jingjiren1 > 0) {
            $data1_user = ['balance' => $host1Msg['balance'] + $jingjiren1];
            $host1_id = $host1Msg['uid'];
            $res_balance1 = $userModel->where("uid='{$host1_id}'")->save($data1_user);
            //保存余额变动记录
            $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
            $all_money1 = $host1Msg['balance'] + $jingjiren1;
            $res_record1 = $UserBalanceRecord->addLog($host1_id, $jingjiren1, $all_money1, 'self', '2', $order['order_num'], '4');
            if ($res_record1 === false || $res_balance1 === false) {
                $userModel->rollback();
                return false;
            }
            #找带货的二级经纪人
            $host2Msg = $userModel->getUserMsg($host1Msg['referrer_id']);
            if ($host2Msg && $jingjiren2 > 0) {
                $data2_user = ['balance' => $host1Msg['balance'] + $jingjiren2];
                $host2_id = $host2Msg['uid'];
                $res_balance2 = $userModel->where("uid='{$host2_id}'")->save($data2_user);
                //保存余额变动记录
                $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
                $all_money2 = $host2Msg['balance'] + $jingjiren2;
                $res_record2 = $UserBalanceRecord->addLog($host2_id, $jingjiren2, $all_money2, 'self', '2', $order['order_num'], '4');
                if ($res_record2 === false || $res_balance2 === false) {
                    $userModel->rollback();
                    return false;
                }
            }
        }
        #发放自购佣金    $user_proify_money
        $data_users = ['balance' => $userMsg['balance'] + $user_proify_money];
        $uid = $userMsg['uid'];
        $res_balances = $userModel->where("uid='{$uid}'")->save($data_users);
        $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
        $all_moneys = $userMsg['balance'] + $user_proify_money;
        $res_records = $UserBalanceRecord->addLog($uid, $user_proify_money, $all_moneys, 'self', '2', $order['order_num'], '4');
        if ($res_balances === false || $res_records === false) {
            $userModel->rollback();
            return false;
        }
        #如果不是普通会员级别 直接提交跑路
        if (!in_array($userMsg['group_id'], ['1', '2'])) {
            $userModel->commit();
            return true;
        }
        #发放自购推荐人
        $ref1_msg = $userModel->getUserMsg($userMsg['referrer_id']);
        $group1 = $groupModel->getGroupMsg($ref1_msg['group_id']);
        $user_proify_money1 = $user_proify_money * ($group1['referrer_rate'] / 100);
        if ($ref1_msg && $user_proify_money1 > 0) {
            $data_users = ['balance' => $ref1_msg['balance'] + $user_proify_money1];
            $uid = $ref1_msg['uid'];
            $res_balances = $userModel->where("uid='{$uid}'")->save($data_users);
            $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
            $all_moneys = $ref1_msg['balance'] + $user_proify_money1;
            $res_records = $UserBalanceRecord->addLog($uid, $user_proify_money1, $all_moneys, 'self', '2', $order['order_num'], '4');
            if ($res_balances === false || $res_records === false) {
                $userModel->rollback();
                return false;
            }
            if ($ref1_msg['group_id'] > 2) {
                $is_vip ++;
            }
            #发放自购推荐人
            $ref2_msg = $userModel->getUserMsg($ref1_msg['referrer_id']);
            $group2 = $groupModel->getGroupMsg($ref2_msg['group_id']);
            $user_proify_money2 = $user_proify_money * ($group2['referrer_rate'] / 100);
            if ($ref2_msg && $user_proify_money2 > 0) {
                $data_users = ['balance' => $ref1_msg['balance'] + $user_proify_money2];
                $uid = $ref2_msg['uid'];
                $res_balances = $userModel->where("uid='{$uid}'")->save($data_users);
                $UserBalanceRecord = new \Common\Model\UserBalanceRecordModel();
                $all_moneys = $ref1_msg['balance'] + $user_proify_money2;
                $res_records = $UserBalanceRecord->addLog($uid, $user_proify_money2, $all_moneys, 'self', '2', $order['order_num'], '4');
                if ($res_balances === false || $res_records === false) {
                    $userModel->rollback();
                    return false;
                }
            }
            if ($ref2_msg['group_id'] > 2) {
                $is_vip++;
            }
            #如果没有走完团队分红的情况下
            if ($ref2_msg['referrer_id'] > 0 && $is_vip < 2) {
                $teamList = $userModel->getUserInfoByGroup($ref2_msg['referrer_id']);
                if ($teamList) {
                    for ($t = 0; $t < 2; $t++) {
                        $team_id = $teamList[$t];
                        $team_msg1 = $userModel->getUserMsg($team_id);
                        if ($is_vip == 1) {
                            $referrer_money_team = $user_proify_money * (defined('TEAM_REWARD2') ? TEAM_REWARD2 : 0) / 100;
                            //增加用户余额
                            $team_balance1 = $userModel->where("uid='{$team_msg1['uid']}'")->save(['balance' => $referrer_money_team]);
                            //保存余额变动记录
                            $team1_money = $team_msg1['balance'] + $referrer_money_team;
                            $team_record1 = $UserBalanceRecord->addLog($team_msg1['uid'], $referrer_money_team, $team1_money, 'self_rt', '2', $order['order_num'], '4');
                            if ($team_record1 === false && $team_balance1 === false) {
                                $userModel->rollback();
                                break;
                                return false;
                            }
                            break;
                        }
                        if ($is_vip == 0) {
                            $referrer_money_team = $user_proify_money * (defined('TEAM_REWARD1') ? TEAM_REWARD1 : 0) / 100;
                            //增加用户余额
                            $team_balance1 = $userModel->where("uid='{$team_msg1['uid']}'")->save(['balance' => $referrer_money_team]);
                            //保存余额变动记录
                            $team1_money = $team_msg1['balance'] + $referrer_money_team;
                            $team_record1 = $UserBalanceRecord->addLog($team_msg1['uid'], $referrer_money_team, $team1_money, 'self_rt', '2', $order['order_num'], '4');
                            if ($team_record1 === false && $team_balance1 === false) {
                                $userModel->rollback();
                                break;
                                return false;
                            }
                            $is_vip++;
                            continue;
                        }
                    }
                }
            }
        }
        $userModel->commit();
        return true;
    }

    #获取子订单
    public function getMainOrderById($order_id)
    {
        return $this->where('main_order_id='.$order_id)->select();
    }

    /**
     * 自营返利处理预估   订单创建之后执行，写入预估金额，并在订单列表中输出
     * @param $order array  订单数组
     * @return bool
     */
    public function treatSelfTmp($order)
    {
        $userModel = new \Common\Model\UserModel();
        $orderGoodsModel = new \Common\Model\OrderDetailModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
        if ($userMsg) {
            #基于订单类型     如果是带货订单，走带货佣金处理
            $order_goods = $orderGoodsModel->getOrderDetail($order['id']);
            $fx_proify_money = 0;
            foreach ($order_goods as $l) {
                $fx_proify_money += $l['fx_profit_money']/100;
            }

            if ($fx_proify_money <= 0) return true;

            if ($order['is_host'] == 'Y') {
                self::host_commissionTmp($fx_proify_money, $order);
            } else {
                self::self_commissionTmp($fx_proify_money, $order);
            }
        } else {
            return false;
        }
    }

    #带货预估   user_balance_record_tmp  新增自营的类型  已订单ID+用户ID为条件进行查找输出   参考上面
    public function host_commissionTmp($fx_proify_money, $order)
    {
        $userModel = new \Common\Model\UserModel();
        $hostCommissionModel = new \Common\Model\HostCommissionModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
        $hostMsg = $userModel->getUserMsg($order['host_id']);
        if ($userMsg || $hostMsg) {
            return false;
        }
        $hostGroup = $hostCommissionModel->where('id=1')->find();
        #------------------计算佣金-------------------------------
        #给主播返佣
        $host_proify_money = $fx_proify_money * (1 - ($hostGroup['fee_service'] / 100 + $hostGroup['fee_plantform'] / 100));

        #一级经纪人佣金
        $jingjiren1 = $host_proify_money * $hostGroup['broker_rate'] / 100;
        #二级经纪人佣金
        $jingjiren2 = $host_proify_money * $hostGroup['broker_rate2'] / 100;
        #-------------------给带货人返佣----------------------------
        #先发放带货的佣金
        $userBalanceRecordTmpModel = new \Common\Model\UserBalanceRecordTmpModel();

        #------------------给一级二级经纪人返佣---------------------------
        $host1Msg = $userModel->getUserMsg($hostMsg['referrer_id']);
        $userModel->startTrans();
        if ($host1Msg && $jingjiren1 > 0) {
            $host1_id = $host1Msg['uid'];
            $res_record1 = $userBalanceRecordTmpModel->addLog($host1_id, $jingjiren1,'self',$order['order_num'],'4',$order['create_time']);
            if ($res_record1 === false) {
                $userModel->rollback();
                return false;
            }
            #找带货的二级经纪人
            $host2Msg = $userModel->getUserMsg($host1Msg['referrer_id']);
            if ($host2Msg && $jingjiren2 > 0) {
                $host2_id = $host2Msg['uid'];
                $res_record2 = $userBalanceRecordTmpModel->addLog($host2_id, $jingjiren2,'self', $order['order_num'], '4',$order['create_time']);
                if ($res_record2 === false ) {
                    $userModel->rollback();
                    return false;
                }
            }
        }
        #自购返佣
        self::self_commissionTmp($fx_proify_money, $order);
    }
    #自购预估
    public function self_commissionTmp($fx_proify_money, $order)
    {
        $userModel = new \Common\Model\UserModel();
        $groupModel = new \Common\Model\UserGroupModel();
        $userBalanceRecordTmpModel = new \Common\Model\UserBalanceRecordTmpModel();
        $userMsg = $userModel->getUserMsg($order['user_id']);
        $group = $groupModel->getGroupMsg($userMsg['group_id']);
        #--------------------------------------  自购返佣  --------------------------------------------------
        #给自己返佣
        $u_proify_money = $fx_proify_money*$group['fee_user']/100;
        $uid = $userMsg['uid'];
        //开启事务
        $userBalanceRecordTmpModel->startTrans();

        //四舍五入
        $money_user=round($u_proify_money, 2);
        //佣金-扣税
        $money_service=$fx_proify_money*$group['fee_service']/100;
        //四舍五入
        $money_service=round($money_service, 2);
        //佣金-平台
        $money_plantform=$fx_proify_money*$group['fee_plantform']/100;
        //四舍五入
        $money_plantform=round($money_plantform, 2);
        #团队分红情况 为0没有执行过，大于2的情况下不执行了
        $is_vip=0;

        #给自己写入预估数据
        $res_tmp = $userBalanceRecordTmpModel->addLog($uid,$money_user,'self',$order['order_num'],'4',$order['create_time']);
        #给推荐人操作写入预估
        if($res_tmp !== false && $userMsg['referrer_id']>0)
        {
            $ref1 = $userModel->getUserMsg($userMsg['referrer_id']);
            if($ref1)
            {
                $group1 = $groupModel->getGroupMsg($ref1['group_id']);
                if($ref1['group_id']>2)
                {
                    $is_vip ++;
                }
                #推荐人返佣
                $ref1_proify_money = round($fx_proify_money*($group['fee_service']/100+$group['fee_plantform']/100)*($group1['referrer_rate']/100),2);
                if($ref1_proify_money>0) {
                    #写入预估数据
                    $ref1_tmp = $userBalanceRecordTmpModel->addLog($ref1['uid'],$ref1_proify_money,'self',$order['order_num'],'4',$order['create_time']);
                    if($ref1_tmp === false)
                    {
                        $userBalanceRecordTmpModel->rollback();
                        return false;
                    }
                    #给间推返佣
                    $ref2 = $userModel->getUserMsg($ref1['referrer_id']);
                    if($ref2)
                    {
                        $group2 = $groupModel->getGroupMsg($ref2['group_id']);
                        if($ref2['group_id']>2)
                        {
                            $is_vip ++;
                        }
                        #推荐人返佣
                        $ref2_proify_money = $fx_proify_money*($group['fee_service']/100+$group['fee_plantform']/100)*($group2['referrer_rate']/100);
                        if($ref2_proify_money>0) {
                            #写入预估数据
                            $ref1_tmp = $userBalanceRecordTmpModel->addLog($ref2['uid'], $ref2_proify_money, 'self', $order['order_num'], '4', $order['create_time']);
                            if ($ref1_tmp === false) {
                                $userBalanceRecordTmpModel->rollback();
                                return false;
                            }
                        }
                        #团队分红
                        $teamList = $userModel->getUserInfoByGroup($ref2['referrer_id']);
                        $TeamRewardsLog = new \Common\Model\TeamRewardsLogModel();
                        switch ($is_vip)
                        {
                            case 1:
                                #执行一次直接返回
                                $team_id = $teamList[0];
                                $team1Msg = $userModel->getUserMsg($team_id);
                                $referrer_money_team=$fx_proify_money*(defined('TEAM_REWARD1')?TEAM_REWARD1:0)/100;
                                if($referrer_money_team>0 && $team1Msg)
                                {
                                    #写入预估数据
                                    $ref2_tmp = $userBalanceRecordTmpModel->addLog($team1Msg['uid'], $referrer_money_team, 'self', $order['order_num'], '4', $order['create_time']);
                                    //保存团队分红订单记录
                                    $TeamRewardsLog->addLog($team1Msg['uid'], $userMsg['uid'], $order['order_num'], 'self',$is_vip);
                                    if ($ref2_tmp === false) {
                                        $userBalanceRecordTmpModel->rollback();
                                        return false;
                                    }
                                }
                                $userBalanceRecordTmpModel->commit();
                                return true;
                            case 0:
                                #执行两次
                                for ($l=0;$l<2;$l++)
                                {
                                    $team_id = $teamList[$l];
                                    $team1Msg = $userModel->getUserMsg($team_id);
                                    $referrer_money_team=$fx_proify_money*(defined('TEAM_REWARD2')?TEAM_REWARD2:0)/100;
                                    if($referrer_money_team>0 && $team1Msg)
                                    {
                                        #写入预估数据
                                        $team_tmp = $userBalanceRecordTmpModel->addLog($team1Msg['uid'], $referrer_money_team, 'self', $order['order_num'], '4', $order['create_time']);
                                        //保存团队分红订单记录
                                        $TeamRewardsLog->addLog($team1Msg['uid'], $userMsg['uid'], $order['order_num'], 'self',$is_vip);
                                        if($team_tmp === false)
                                        {
                                            $this->rollback();
                                            return false;
                                        }
                                        continue;
                                    }
                                }
                                $userBalanceRecordTmpModel->commit();
                                return true;
                        }
                    }else{
                        $this->commit();
                        return true;
                    }
                }
                $this->commit();
                return true;
            }
        }
    }

    /**
     * #同步库存以及销量
     * @param $order_id    订单ID
     * @
     */
    public function synStock($order_id,$is_pay=0)
    {
        #如果是主订单
        $order = $this->getOrderMsg($order_id);
        if($order['main_order_id'] >0)
        {
            $order = $this->getOrderMsg($order_id);
            if($order)
            {
                $orderGoodsModel = new \Common\Model\OrderDetailModel();
                $goodsModel = new \Common\Model\GoodsModel();
                $shopGoodsOptionModel = new \Common\Model\ShopGoodsOptionModel();
                $shopGoodsModel = new \Common\Model\ShopGoodsModel();
                $orderGoodsList = $orderGoodsModel->getOrderDetail($order_id);
                foreach ($orderGoodsList as $item)
                {
                    $goods = $goodsModel->getGoodsMsg($item['goods_id']);
                    #如果是商户端商品
                    if($goods && $goods['ren_good_id']>0)
                    {
                        $shopGoods = $shopGoodsModel->getGoodsInfo(['id'=>$goods['ren_good_id']]);
                        #如果是下单减库存
                        if($shopGoods['totalcnf']==0 && $is_pay==0)
                        {
                            #如果没有规格库存
                            if(empty($item['sku']))
                            {
                                $shopGoodsModel->synStock($goods['ren_good_id'],-$item['num']);
                            }else{
                                $shopGoodsOptionModel->synStock($goods['ren_good_id'],-$item['num'],$item['sku']);
                            }
                            $shopGoodsModel->synSales($goods['ren_good_id'],$item['num']);
                        }
                        #如果是付款减库存
                        if($shopGoods['totalcnf']==1 && $is_pay==1)
                        {
                            #如果没有规格库存
                            if(empty($item['sku']))
                            {
                                $shopGoodsModel->synStock($goods['ren_good_id'],-$item['num']);
                            }else{
                                $shopGoodsOptionModel->synStock($goods['ren_good_id'],-$item['num'],$item['sku']);
                            }
                            $shopGoodsModel->synSales($goods['ren_good_id'],$item['num']);
                        }
                    }
                }
            }
        }else{
            $orderList = $this->where(['main_order_id'=>$order_id])->select();
            for($i=0;$i<count($orderList);$i++)
            {
                $order_id = $orderList[$i]['id'];
                $order = $this->getOrderMsg($orderList[$i]['id']);
                if($order)
                {
                    $orderGoodsModel = new \Common\Model\OrderDetailModel();
                    $goodsModel = new \Common\Model\GoodsModel();
                    $shopGoodsOptionModel = new \Common\Model\ShopGoodsOptionModel();
                    $shopGoodsModel = new \Common\Model\ShopGoodsModel();
                    $orderGoodsList = $orderGoodsModel->getOrderDetail($order_id);
                    foreach ($orderGoodsList as $item)
                    {
                        $goods = $goodsModel->getGoodsMsg($item['goods_id']);
                        #如果是商户端商品
                        if($goods && $goods['ren_good_id']>0)
                        {
                            $shopGoods = $shopGoodsModel->getGoodsInfo(['id'=>$goods['ren_good_id']]);
                            #如果是下单减库存
                            if($shopGoods['totalcnf']==0 && $is_pay==0)
                            {
                                #如果没有规格库存
                                if(empty($item['sku']))
                                {
                                    $shopGoodsModel->synStock($goods['ren_good_id'],-$item['num']);
                                }else{
                                    $shopGoodsOptionModel->synStock($goods['ren_good_id'],-$item['num'],$item['sku']);
                                }
                                $shopGoodsModel->synSales($goods['ren_good_id'],$item['num']);
                            }
                            #如果是付款减库存
                            if($shopGoods['totalcnf']==1 && $is_pay==1)
                            {
                                #如果没有规格库存
                                if(empty($item['sku']))
                                {
                                    $shopGoodsModel->synStock($goods['ren_good_id'],-$item['num']);
                                }else{
                                    $shopGoodsOptionModel->synStock($goods['ren_good_id'],-$item['num'],$item['sku']);
                                }
                                $shopGoodsModel->synSales($goods['ren_good_id'],$item['num']);
                            }
                        }
                    }
                }
            }
        }
    }

}
?>
