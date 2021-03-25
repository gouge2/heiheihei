<?php
/**
 * by 翠花 http://http://livedd.com
 * 用户管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Model\ShopMerchUserModel;

class UserController extends AuthController
{
    /**
     * 用户增加经验的值
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function getUserExp()
    {
        $data=array(
            'recommended_register_exp'=>USER_UPGRADE_REGISTER,
            'recommended_shopping_exp'=>USER_UPGRADE_BUY,
            'reward_type'=>TASK_INFO_AWARD_TYPE,
            'reward_value'=>TASK_INFO_AWARD_NUM,
        );
        $res=array(
            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'完善资料奖励类型，1积分，2余额，3经验',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 判断用户是否购买淘宝0元购商品
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function userIsBuyFree(){
        if (trim(I('post.token'))){
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                $msg=$User->getUserMsg($uid);
                $data=array(
                    'is_buy_free_goods'=>$msg['is_buy_free_goods'],
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 积分信息
     * @param string $token:用户身份令牌
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->order_id:订单ID
     * @return @param data->order_num:订单号
     */
    public function pointMsg(){
        if (trim(I('post.token'))){
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                $msg=$User->getUserMsg($uid);
                $data=array(
                    'point'=>intval($msg['point']),
                    'point_value'=>POINT_VALUE
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
	 * 修改密码
	 * @param string $token:用户身份令牌
	 * @param string $oldpwd:原密码
	 * @param string $pwd1:新密码
	 * @param string $pwd2:重复密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function changePwd()
	{
		if(trim(I('post.token')) and trim(I('post.oldpwd')) and trim(I('post.pwd1')) and trim(I('post.pwd2')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$oldpwd=I('post.oldpwd');
				$pwd1=I('post.pwd1');
				$pwd2=I('post.pwd2');
				//修改密码
				$res=$User->changePwd($uid, $oldpwd, $pwd1, $pwd2);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 退出登录
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function loginout()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$res_out=$User->loginout($uid);
				if($res_out!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取用户信息
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->user_msg:用户账号信息
	 * @return @param data->user_detail:用户详情
	 */
	public function getUserMsg()
	{
		// 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

		//获取用户账号信息
		$uid              = $res_token['uid'];
		$msg              = $User->getUserDetail($uid);
		$msg2 = array(
			'uid'               => $msg['uid'],
			'group_id'          => $msg['group_id'],
			'group_name'        => $msg['group_name'],
			'group_icon'        => $msg['group_icon'],
			'username'          => $msg['username'],
			'truename'          => $msg['truename'] ? $msg['truename'] : '',
			'phone'             => $msg['phone'],
			'email'             => $msg['email'],
			'balance'           => $msg['balance'],
			'balance_user'      => $msg['balance_user'],
			'balance_service'   => $msg['balance_service'],
			'balance_plantform' => $msg['balance_plantform'],
			'point'             => $msg['point']*1,
			'exp'               => $msg['exp']*1,
			'alipay_account'    => $msg['alipay_account'] ? : '',
			'expiration_date'   => $msg['expiration_date'],
			'is_forever'        => $msg['is_forever'],
			'auth_code'         => $msg['auth_code'],
			'll_no'         	=> $msg['auth_code'],      // 翠花号
			'is_share_vip'      => $msg['is_share_vip'],
			'is_complete_info'  => $msg['is_complete_info'],
			'is_host'  			=> $msg['is_host'],
			'referrer_phone'    => '',
			'll_cn'    			=> GIFT_MONEY_CN,
			'll_deer_cn'    	=> GIFT_DEER_CN,
			'll_balance'    	=> (int)$msg['ll_balance'],
			'll_deer'    		=> (int)$msg['ll_deer'],
		);

		// 添加上传视频、直播权限判断
		$live_power 			= USER_LIVE_POWER ? explode(',', USER_LIVE_POWER) : [];
		$short_power 			= USER_SHORT_POWER ? explode(',', USER_SHORT_POWER) : [];
		$live_in 				= in_array($msg['group_id'], $live_power) ? true : false;
		$short_in 				= in_array($msg['group_id'], $short_power) ? true : false;
		$msg2['short_live_iden']= ($live_in && $short_in) ? 'short_live': ($short_in ? 'short' : ($live_in ? 'live' : 'not'));

		// 直推人手机号
		if ($msg['referrer_id']) {
			$referrerMsg = $User->getUserMsg($msg['referrer_id']);
			if ($referrerMsg){
				$msg2['referrer_phone']=$referrerMsg['phone'];
			}
		}

		if ($msg['is_forever'] == 'Y') {
			//终生VIP，也不显示到期时间
			$msg2['expiration_date'] = '';
		} else {
			if ($msg['expiration_date'] and $msg['expiration_date'] != '0000-00-00 00:00:00') {
				if (strtotime($msg['expiration_date']) <= time()) {
					//到期时间小于当前时间，过期了
					$exp                              = $msg2['exp'];
					$UserGroup                        = new \Common\Model\UserGroupModel();
					$res_group                        = $UserGroup->where("exp< = $exp")->order('exp desc')->field('id,title,level_icon')->find();
					$data_referrer['group_id']        = $res_group['id'];
					$data_referrer['expiration_date'] = null;
					$data_referrer['is_forever']      = 'Y';
					$User->where("uid='$uid'")->save($data_referrer);
					$msg2['expiration_date']          = '';
					$msg2['group_id']                 = $res_group['id'];
					$msg2['group_name']               = $res_group['title'];
					$msg2['group_icon'] 			  = $res_group['level_icon'];
				} else {
					$msg2['expiration_date']          = date('Y-m-d',strtotime($msg['expiration_date']));
				}
			} else {
				$msg2['expiration_date']='';
			}
		}

		$detail = $msg['detail'];
		// 判断昵称是否经过base64编码
		if (is_base641($detail['nickname'])) {
			//将昵称解码
			$detail['nickname'] = base64_decode($detail['nickname']);
		}
		// 判断头像是否为第三方应用头像
		if ($detail['avatar']) {
			$detail['avatar']= is_url($detail['avatar']) ? $detail['avatar'] : WEB_URL . $detail['avatar'];
		} else {
			//$detail['avatar']=WEB_URL.'/Public/static/images/default_avatar.png';
		}

		//获取用户已结算的佣金
		$UserBalanceRecord  = new \Common\Model\UserBalanceRecordModel();
		$money              = $UserBalanceRecord->where("user_id = $uid and status = 2 and type in (1,2,3,4)")->sum('money');
		$msg2['commission'] = $money / 100;

		// IM信息
		$Im 			    = new \Common\Controller\ImController();
		$msg2['im_user_sig']= $Im->getUserSig($uid);
		$msg2['im_user_id'] = $uid;

		// 直播房间号
		$LiveRoom           = new \Common\Model\LiveRoomModel();
		$room            	= $LiveRoom->field('room_id,is_status')->where(['user_id' => $uid])->find();
		$msg2['room_id']    = isset($room['room_id']) ? (string)$room['room_id'] : '';
		if (isset($room['is_status']) && $room['is_status'] == 5) {   // 禁播
			$msg2['short_live_iden'] 	= 'ban';
			$msg2['short_live_ban_msg'] = '您近期直播存在违规行为，如有疑问请联系平台客服！';
		}

		// 认证状态
        #新增 如果开启无需实名开直播
		$RealName           = new \Common\Model\RealNameModel();
		$real_status        = $RealName->where(['user_id' => $uid])->getField('real_status');
		if(LIVE_HINT_USER_AUTH==0)
        {
            $real_status ='pass';
            #如果沒有直播間，新建一個直播間  && 判断是否拥有直播权限
            if(!$room && $live_in===true)
            {
                $LiveRoom->add(['room_name'=>"欢迎大家来到我的直播间棒场！",'user_id'=>$uid,'cat_id'=>0,'cover_url'=>'','is_put'=>N,'is_recommend'=>1,'is_status'=>4,'recent_time'=>null]);
                $msg2['room_id'] = $LiveRoom->getLastInsID();
            }
        }
		$msg2['real_status']= $real_status ? $real_status : 'not';

		$this->ajaxSuccess(['user_msg' => $msg2, 'user_detail' => $detail]);
	}

    /**
     * 获取用户权限信息
     */
    public function getShopApplyByUserId()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $ShopSyssetModel = new \Common\Model\ShopSyssetModel();
        $ArticleModel = new \Common\Model\ArticleModel();
        $ArticleCatModel = new \Common\Model\ArticleCatModel();
        $MultiMerchantModel = new \Common\Model\MultiMerchantModel();
        $regModel = new \Common\Model\ShopMerchRegModel();
        $BoodModel = new \Common\Model\BoodModel();
        $groupModel = new \Common\Model\UserGroupModel();

        //获取用户账号信息
        $uid              = $res_token['uid'];
        $msg              = $User->getUserDetail($uid);
        #获取认证配置
        $multi = $MultiMerchantModel->where('id=1')->find();
        $msg2['is_auth'] = 'pass';
        if(empty($multi))
        {
            $msg2['is_auth'] = 'not';
        }
        if($multi['type'] ==0)
        {
            $msg2['is_auth'] = 'not';
        }
        $is_host =1;
        #实名认证开关
        $msg2['real_status'] = 'pass';
        $msg2['is_approve']=0;
        if($multi['verified']==2)
        {
            $RealName           = new \Common\Model\RealNameModel();
            $real_status        = $RealName->where(['user_id' => $uid])->getField('real_status');
            $msg2['real_status']= $real_status ? $real_status : 'not';
            $msg2['is_approve']=1;
        }
        #用户组别权限效验
        $group = explode(',',$multi['authority']);
        $msg2['line_in'] = 'not';

        if(in_array($msg['group_id'],$group))
        {
            $msg2['line_in'] = 'pass';
        }
        $group = $groupModel->getGroupMsg($group[0]);
        $msg2['group_name'] = $group['title'];

        #保证金效验
        $msg2['bond'] = 'pass';
        $msg2['bood_money'] = $multi['total_amount'];
        $msg2['is_margin']=0;
        if($multi['margin']==2)
        {
            $msg2['bond'] = 'not';
            $msg2['is_margin']=1;
            $bood = $BoodModel->where('user_id='.$uid)->find();
            if($bood && $bood['bood']>=$multi['total_amount'])
            {
                $msg2['bond'] = 'pass';
            }
        }
        #保证金说明
        $msg2['description'] = $multi['description'];
        $msg2['introduction'] = $multi['introduction'];
        #获取协议信息
        $systemInfo = $ShopSyssetModel->where('id=1')->find();
        $applyItem = unserialize($systemInfo['plugins']);
        $msg2['setStatus'] = 'not';
        #入驻申请查询
        $regInfo = $regModel->getOne(['openid'=>'lailu_'.$res_token['uid']]);
        if($regInfo)
        {
            $msg2['setStatus'] = ($regInfo['status']==0)?'check':($regInfo['status']==1 ?'pass':'refuse');
        }
        $msg2['apply_title'] = empty($applyItem['applytitle'])?"商户入驻协议":$applyItem['applytitle'];
        $articleCat = $ArticleCatModel->where('cat_name like "%商户入驻协议%"')->find();
        $cat_id = empty($articleCat['cat_id'])?0:$articleCat['cat_id'];
        $article_id = $ArticleModel->where('cat_id='.$cat_id)->order('article_id desc')->getField('article_id');

        $msg2['article_id'] = !$article_id?0:$article_id;

        $this->ajaxSuccess($msg2);
    }

    /**
     * 获取店铺保证金额
     */
    public function getSetShopBood()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $platform           = trim(I('post.platform'));
        $MultiMerchantModel = new \Common\Model\MultiMerchantModel();
        $BoodModel = new \Common\Model\BoodModel();

        //获取用户账号信息
        $uid              = $res_token['uid'];
        $msg              = $User->getUserDetail($uid);

        $multi = $MultiMerchantModel->where('id=1')->find();
        if(empty($multi))
        {
            $this->ajaxError('','暂未开启入驻,如需申请入驻,请先联系在线客服');
        }
        if($multi['type'] == 0 || $multi['settle_in'] == 1)
        {
            $this->ajaxError('','暂未开启入驻,如需申请入驻,请先联系在线客服');
        }
        $data['bood_money'] = $multi['total_amount'];
        $data['payment'] = $multi['payment'];
        if ($platform == 'android') {
            $data['payment'] = str_replace(',wxpayMini','',$data['payment']);
        }
        $this->ajaxSuccess($data);
    }

    public function subBood()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $MultiMerchantModel = new \Common\Model\MultiMerchantModel();
        $orderModel = new \Common\Model\OrderModel();
        $BoodModel = new \Common\Model\BoodModel();
        $BoodLogModel = new \Common\Model\BoodLogModel();
        $multi = $MultiMerchantModel->where('id=1')->find();
        if(empty($multi))
        {
            $this->ajaxError('','暂未开启入驻,如需申请入驻,请先联系在线客服');
        }
        if($multi['type'] == 0 || $multi['settle_in'] == 1)
        {
            $this->ajaxError('','暂未开启入驻,如需申请入驻,请先联系在线客服');
        }
        //获取用户账号信息
        $uid              = $res_token['uid'];
        $money = trim(I('post.bood_money'));
        $use_payment = trim(I('post.payment'));
        if($multi['total_amount'] >$money)
        {
            $this->ajaxError('','请输入正确的保证金金额');
        }

        $payment = explode(',',$multi['payment']);
        if(!in_array($use_payment,$payment))
        {
            $this->ajaxError('','暂不支持该支付方式,请重新选择');
        }

        //实际付款金额
        $pay_price=$money;
        //保留2位小数-四舍五不入
        $pay_price=substr(sprintf("%.3f",$pay_price),0,-1);
        $order_num=$orderModel->generateOrderNum();
        $data['log_sn'] = $order_num;
        $data['user_id'] = $uid;
        $data['bood_money'] = $pay_price;
        $data['payment'] = $use_payment;
        $data['pay_status'] = 0;
        $data['create_time'] = date('Y-m-d H:i:s',time());

        if(!$BoodLogModel->create($data)) {
            //验证不通过
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                'msg'=>$BoodLogModel->getError()
            );
        }else {
            //验证通过
            //开启事务
            $BoodLogModel->startTrans();
            $res_add=$BoodLogModel->add($data);
            if($res_add !== false) {
                switch ($use_payment) {
                    case 'wxpay':
                        //获取微信支付表单数据
                        Vendor('pay.wxpay', '', '.class.php');
                        $wxpay = new \wxpay();
                        $body = '保证金缴纳';
                        //订单号
                        $out_trade_no = 'bp_' . $order_num;//订单号
                        //订单费用，精确到分
                        $total_fee = $pay_price * 100;
                        $notify_url = WEB_URL . '/app.php/WxNotify/notify_app';
                        $AppParameters = $wxpay->GetAppParameters($body, $out_trade_no, $total_fee, $notify_url);
                        writeLog(json_encode(['cont'=>$AppParameters]),'payInfo');
                        $pay_parameters = $AppParameters;
                        break;
                    case 'wxpayMini':
                        //获取微信支付表单数据
                        Vendor('pay.wxpay', '', '.class.php');
                        $openid = trim(I('post.openid'));
                        if(!$openid)
                        {
                            //验证不通过
                            $res=array(
                                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                                'msg'=>$BoodLogModel->getError()
                            );
                        }else{
                            $wxpay = new \wxpay();
                            $body = '保证金缴纳';
                            //订单号
                            $out_trade_no = 'bp_' . $order_num;//订单号
                            //订单费用，精确到分
                            $total_fee = $pay_price * 100;
                            $notify_url = WEB_URL . '/app.php/WxNotify/notify_app';
                            $AppParameters = $wxpay->GetMiniAppParameters($body, $out_trade_no, $total_fee, $openid, $notify_url);
                            writeLog(json_encode(['cont'=>$AppParameters]),'payInfo');
                            $pay_parameters = $AppParameters;
                        }


                        break;
                    case 'alipay':
                        //获取支付宝请求参数
                        Vendor('pay.alipayApp', '', '.class.php');
                        $alipayApp = new \alipayApp();
                        //订单描述
                        $body = '保证金缴纳';
                        //订单名称，必填
                        $subject = '保证金缴纳';
                        //商户订单号，商户网站订单系统中唯一订单号，必填
                        $out_trade_no = 'bp_' . $order_num;//订单号
                        //付款金额，必填
                        $total_amount = $pay_price;
                        $alipay_parameters = $alipayApp->GetParameters($body, $subject, $out_trade_no, $total_amount);
                        writeLog(json_encode(['cont'=>$alipay_parameters]),'payInfo1');
                        $pay_parameters = $alipay_parameters;
                        break;
                    case 'int_wx':
                    case 'int_ali':
                        $latipay = new LatipayController();
                        //商户订单号，商户网站订单系统中唯一订单号，必填
                        $out_trade_no = 'bp_' .$order_num;//订单号
                        //付款金额，必填
                        $total_amount = $pay_price;
                        $alipay_parameters = $latipay->pay($out_trade_no, $total_amount,$use_payment,1);
                        writeLog(json_encode(['cont'=>$alipay_parameters]),'payInfo2');
                        $pay_parameters = $alipay_parameters['host_url'].'/'.$alipay_parameters['nonce'];
                        break;
                    case 'paypal':
                        $paypal = new PayPaiController();
                        //商户订单号，商户网站订单系统中唯一订单号，必填
                        $out_trade_no = 'bp_' .$order_num;//订单号
                        //付款金额，必填
                        $total_amount = $pay_price;
                        $alipay_parameters = $paypal->pay($out_trade_no, $total_amount,$use_payment,1);
                        writeLog(json_encode(['cont'=>$alipay_parameters]),'payInfo3');
                        $pay_parameters = $alipay_parameters['payUrl'];
                        break;
                }
                if (in_array($use_payment,['int_wx','int_ali','paypal'])){
                    $data = array(
                        'pay_parameters' => $pay_parameters,
                        'order_id' => $alipay_parameters['order_id'],
                    );
                } else {
                    $data = array(
                        'pay_parameters' => $pay_parameters,
                    );
                }
                $res = array(
                    'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg' => '成功',
                    'data' => $data
                );
            }else{
                //回滚
                $BoodLogModel->rollback();
                //数据库错误
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                );
            }
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
        die;

    }

    public function test()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $sn = trim(I('post.sn'));
        $payment= trim(I('post.payment'));
        $boodModel = new \Common\Model\BoodModel();
        $boodModel->treatOrder($sn,$payment);
    }

    /**
	 * 编辑用户信息
	 * 没有修改的字段信息请传递原值
	 * @param string $token:用户身份令牌
	 * @param string $nickname:昵称
	 * @param string $truename:真实姓名
	 * @param string $sex:性别 1男 2女
	 * @param string $height:身高
	 * @param string $weight:体重
	 * @param string $blood:血型 1A型 2B型 3AB型 4O型 5其它
	 * @param date $birthday:生日
	 * @param string $qq:QQ号
	 * @param string $weixin:微信号
	 * @param string $province:省份
	 * @param string $city:城市
	 * @param string $county:县/区域
	 * @param string $detail_address:详细地址
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editUserMsg()
	{
		if(trim(I('request.token')) and trim(I('request.nickname'))) {
			//判断用户身份
			$token=trim(I('request.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				//获取用户账号信息
				$uid=$res_token['uid'];
				$nickname=trim(I('request.nickname'));
				//判断是否为emojo表情
				if ( haveEmojiChar($nickname) ) {
				    //做base64编码
				    $nickname=base64_encode($nickname);
				}
				//判断昵称是否重复
				$UserDetail=new \Common\Model\UserDetailModel();

				/* $res_n=$UserDetail->where("nickname='$nickname' and user_id!='$uid'")->find();
				if($res_n) {
					//该昵称已存在，不准重复
					$res=array(
							'code'=>$this->ERROR_CODE_USER['NICKNAME_ALREADY_EXISTS'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NICKNAME_ALREADY_EXISTS']]
					);
				}else { */
					//生日
					$birthday=trim(I('request.birthday'));
					if(is_date($birthday)) {

					}else {
						$birthday=null;
					}
					//昵称可以使用
					$data=array(
							'nickname'=>$nickname,
							'truename'=>trim(I('request.truename')),
							'sex'=>I('request.sex'),
							'height'=>trim(I('request.height')),
							'weight'=>trim(I('request.weight')),
							'blood'=>trim(I('request.blood')),
							'birthday'=>$birthday,
							'qq'=>trim(I('request.qq')),
							'weixin'=>trim(I('request.weixin')),
							'province'=>I('request.province'),
							'city'=>I('request.city'),
							'county'=>I('request.county'),
							'detail_address'=>trim(I('request.detail_address')),
							'signature'=>trim(I('request.signature')),
					);
					if(!$UserDetail->create($data)) {
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$UserDetail->getError()
						);
					}else {
						//验证通过
						//进行编辑
						//开启事务
						$UserDetail->startTrans();
						$res_s=$UserDetail->where("user_id='$uid'")->save($data);

						$res_u=$res_record=true;
						if($nickname  and I('request.province') and I('request.city') and I('request.county') and trim(I('request.detail_address')) and trim(I('request.weixin')) and trim(I('request.sex')) and $birthday and TASK_INFO_AWARD_NUM>0)
						{
						    //完善资料给奖励
						    //增加用户经验值
						    $userMsg=$User->getUserMsg($uid);
						    //只第一次完善给
						    if($userMsg['is_complete_info']=='N'){
						        //根据奖励类型给对应奖励
						        switch (TASK_INFO_AWARD_TYPE){
						            //积分
						            case 1:
						                $point=TASK_INFO_AWARD_NUM;
						                $data_u=array(
						                    'is_complete_info'=>'Y',
						                    'point'=>$userMsg['point']+$point
						                );
						                $res_u=$User->where("uid=$uid")->save($data_u);
						                $UserPointRecord=new \Common\Model\UserPointRecordModel();
						                $all_point=$userMsg['point']+$point;
						                $res_record=$UserPointRecord->addLog($uid, $point,$all_point, 'complete_info');
						                break;
						            //余额
						            case 2:
						                $money=TASK_INFO_AWARD_NUM;
						                $data_u=array(
						                    'is_complete_info'=>'Y',
						                    'balance'=>$userMsg['balance']+$money
						                );
						                $res_u=$User->where("uid=$uid")->save($data_u);
						                $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
						                $all_money=$userMsg['balance']+$money;
						                $res_record=$UserBalanceRecord->addLog($uid, $money,$all_money, 'complete_info');
						                break;
						            //经验值
						            case 3:
						                //判断是否可以升级
						                $exp=TASK_INFO_AWARD_NUM;
						                $new_exp=$userMsg['exp']+$exp;//新经验值
						                $data_u=array(
						                    'is_complete_info'=>'Y',
						                    'exp'=>$new_exp,
						                );
						                //判断会员应该升级到那个会员组
						                //大于当前会员组，并且小于新经验值的最大值
						                $group_id=$userMsg['group_id'];
						                $UserGroup=new \Common\Model\UserGroupModel();
						                $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                        //升级到该会员组增加条件，佣金条件
                                        //查询该用户总佣金
                                        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
                                        $amount=$UserBalanceRecord->userCommissionStatistics($uid);
                                        if($res_group['id'] and $res_group['id']>=$group_id and $amount>=$res_group['commission']){
                                            $data_u['group_id']=$res_group['id'];
                                            $data_u['expiration_date']=null;
                                            $data_u['is_forever']='Y';
                                        }
						                $res_u=$User->where("uid='$uid'")->save($data_u);

						                //保存经验值变动记录
						                $UserExpRecord=new \Common\Model\UserExpRecordModel();
						                $res_record=$UserExpRecord->addLog($uid,$exp,$new_exp,'complete_info');

						                break;
						            default:
						                break;
						        }
						    }
						}

						if($res_s!==false and $res_u!==false and $res_record!==false) {
							//提交事务
							$UserDetail->commit();
						    $res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'编辑用户信息成功'
							);
						}else {
							//数据库错误
							//回滚
							$UserDetail->rollback();
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				// }
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 编辑用户头像
	 * @param string $token:用户身份令牌
	 * @param File $user_avatar:用户头像
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editUserAvatar()
	{
		if(trim(I('post.token')) and $_FILES['user_avatar']['name'])
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				//获取用户账号信息
				$uid=$res_token['uid'];
				//上传店铺门头照片
				if(!empty($_FILES['user_avatar']['name']))
				{
					$config = array(
							'mimes'         =>  array(), //允许上传的文件MiMe类型
							'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
							'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
							'rootPath'      =>  './Public/Upload/User/avatar/', //保存根路径
							'savePath'      =>  '', //保存路径
							'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
							'subName'       =>  '',
					);
					$upload = new \Think\Upload($config);
					// 上传单个文件
					$info = $upload->uploadOne($_FILES['user_avatar']);
					if(!$info) {
						// 上传错误提示错误信息
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg'=>$upload->getError()
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}else{
						// 上传成功
						// 文件完成路径
						$user_avatar_filepath=$config['rootPath'].$info['savepath'].$info['savename'];
						$user_avatar=substr($user_avatar_filepath,1);
					}
				}
				$data=array(
						'avatar'=>$user_avatar
				);
				$UserDetail=new \Common\Model\UserDetailModel();
				$msg=$UserDetail->getUserDetailMsg($uid);
				$res_avatar=$UserDetail->where("user_id=$uid")->save($data);
				if($res_avatar!==false)
				{
					if($msg['avatar'])
					{
						//删除原图片
						$old_avatar='.'.$msg['avatar'];
						unlink($old_avatar);
					}
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'图片上传成功'
					);
				}else {
					//数据库错误
					//删除已上传的图片
					@unlink($user_avatar_filepath);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取原手机验证码
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function sendChangeBandCode()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				//获取用户账号信息
				$uid=$res_token['uid'];
				$UserMsg=$User->getUserMsg($uid);
				$phone = $UserMsg['phone']; // 手机号码
				//发送手机短信
				$sms=new \Common\Model\SmsModel();
				$content="@1@=".rand(1000,9999);
				$res=$sms->sendMessage($phone, $content, 'default');
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    /**
     * 获取原手机码
     * @param string $token:用户身份令牌
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function sendChangeBandPhone()
    {
        if(trim(I('post.token')))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0)
            {
                //用户身份不合法
                $res=$res_token;
            }else {
                //获取用户账号信息
                $uid=$res_token['uid'];
                $UserMsg=$User->getUserMsg($uid);
                $phone = $UserMsg['phone']; // 手机号码
                //发送手机号
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                    'phone'=>$phone
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

	/**
	 * 变更绑定手机号
	 * @param string $token:用户身份令牌
	 * @param string $phone:新手机号码
	 * @param string $code:手机短信验证码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function changeBandingPhone()
	{
		if(trim(I('post.token')) and trim(I('post.phone')) and trim(I('post.code')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				//获取用户账号信息
				$uid=$res_token['uid'];
				//判断手机验证码是否正确
				$code=trim(I('post.code'));
				$phone=trim(I('post.phone'));
				$Sms=new \Common\Model\SmsModel();
				$res_code=$Sms->checkCode($phone, $code);
				if($res_code['code']!=0)
				{
					//短信验证码错误
					$res=$res_code;
				}else {
					//短信验证码正确
					//进行绑定手机变更
					$data=array(
							'phone'=>$phone
					);
					if(!$User->create($data))
					{
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$User->getError()
						);
					}else {
						//验证通过
						$res_s=$User->where("uid='$uid'")->save($data);
						if($res_s!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功'
							);
						}else {
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    /**
     * 变更绑定手机号
     * @param string $token:用户身份令牌
     * @param string $old_phone:旧手机号码
     * @param string $old_code:旧手机短信验证码
     * @param string $phone:新手机号码
     * @param string $code:手机短信验证码
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function changeBandingMobPhone()
    {
        if(trim(I('post.token')) and trim(I('post.old_phone')) and trim(I('post.old_code')) and trim(I('post.phone')) and trim(I('post.code')))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0)
            {
                //用户身份不合法
                $res=$res_token;
            }else {
                //获取用户账号信息
                $uid=$res_token['uid'];
                //判断旧手机验证码是否正确
                $old_code=trim(I('post.old_code'));
                $old_phone=trim(I('post.old_phone'));
                $zone=trim(I('post.zone'));
                $old_zone=trim(I('post.old_zone'));
//                $Sms=new \Common\Model\SmsModel();
//                $res_code=$Sms->checkCode($phone, $code);
                Vendor('mob.mob','','.class.php');
                $mob=new \mob();
                $res_code=$mob->checkSmsCode($old_phone,$old_code,$old_zone);
                if ($res_code['code']!=200){
                    //旧手机验证码不正确
                    $res=array(
                        'code'=>$this->ERROR_CODE_SMS['CODE_ERROR'],
                        'msg'=>'原手机验证码错误',
                    );;
                }else{
                    //判断新手机验证码是否正确
                    $code=trim(I('post.code'));
                    $phone=trim(I('post.phone'));
//                $Sms=new \Common\Model\SmsModel();
//                $res_code=$Sms->checkCode($phone, $code);
                    Vendor('mob.mob','','.class.php');
                    $mob=new \mob();
                    $res_code=$mob->checkSmsCode($phone,$code,$zone);
                    if($res_code['code']!=200)
                    {
                        //新手机短信验证码错误
                        $res=array(
                            'code'=>$this->ERROR_CODE_SMS['CODE_ERROR'],
                            'msg'=>'新手机验证码错误',
                        );;
                    }else {
                        //短信验证码正确
                        //进行绑定手机变更
                        $data=array(
                            'phone'=>$phone
                        );
                        if(!$User->create($data))
                        {
                            //验证不通过
                            $res=array(
                                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                                'msg'=>$User->getError()
                            );
                        }else {
                            //验证通过
                            $res_s=$User->where("uid='$uid'")->save($data);
                            if($res_s!==false)
                            {
                                $res=array(
                                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                                    'msg'=>'成功'
                                );
                            }else {
                                //数据库错误
                                $res=array(
                                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                );
                            }
                        }
                    }
                }
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

	/**
	 * 获取推荐人排行
	 * @param date $date:时间，年-月格式，如2018-07
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:推荐人排行列表
	 */
	public function recommendedList()
	{
		if(trim(I('post.date')))
		{
			$date=trim(I('post.date'));
			$where=" and DATE_FORMAT(register_time,'%Y-%m')='$date'";
		}
		$sql="select a.referrer_id,count(a.uid) as num from (select uid,referrer_id from __PREFIX__user where referrer_id is not null $where) as a group by a.referrer_id order by count(a.uid) desc limit 0,10";
		$list=M()->query($sql);
		$num=count($list);
		$User=new \Common\Model\UserModel();
		$list2=array();
		for($i=0;$i<$num;$i++)
		{
			$uid=$list[$i]['referrer_id'];
			$userMsg=$User->getUserDetail($uid);
			//用户名
			if($userMsg['detail']['nickname'])
			{
				$nickname=$userMsg['detail']['nickname'];
			}else {
				$nickname=$userMsg['account'];
			}
			//头像-判断是否为网址
			$avatar=$userMsg['detail']['avatar'];
			if($avatar)
			{
				if(strpos($avatar, 'http')!==false)
				{
					//第三方头像网址
				}else {
					$avatar=WEB_URL.$avatar;
				}
			}
			$list2[]=array(
					'uid'=>$uid,
					'nickname'=>$nickname,
					'avatar'=>$avatar,
					'num'=>$num
			);
		}
		$data=array(
				'list'=>$list2
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取用户推荐信息
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->num:推荐人数
	 * @return @param data->rankimg:排名
	 */
	public function getRecommendedMsg()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取用户推荐个数和排名
				$User=new \Common\Model\UserModel();
				$recommended_num=$User->where("referrer_id='$uid'")->count();
				//排名
				$rankimg=0;
				$sql="select referrer_id from __PREFIX__user where referrer_id is not null group by referrer_id order by count(uid) desc";
				$list=M()->query($sql);
				$num=count($list);
				for($i=0;$i<$num;$i++)
				{
					if($list[$i]['referrer_id']==$uid)
					{
						$rankimg=$i+1;
					}
				}
				$data=array(
						'num'=>$recommended_num,
						'rankimg'=>$rankimg
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 绑定淘宝账号
	 * @param string $token:用户身份令牌
	 * @param string $tb_uid:淘宝用户ID后6位
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function bindingTaobao()
	{
		if(trim(I('post.token')) and trim(I('post.tb_uid')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//查询用户是否已绑定淘宝
				$userMsg=$User->getUserMsg($uid);
				if($userMsg['tb_uid'] and $userMsg['tb_pid'])
				{
					//您已绑定淘宝，请勿重复操作！
					$res=array(
							'code'=>1,
							'msg'=>'您已绑定淘宝，请勿重复操作！'
					);
				}else {
					$tb_uid=trim(I('post.tb_uid'));//淘宝用户ID后6位
					$pid_arr=json_decode(tb_pid,true);
					foreach ($pid_arr as $k=>$v)
					{
						$tb_pid=$v;
						//查询淘宝uid是否重复
						$res_exist=$User->where("tb_pid='$tb_pid' and tb_uid='$tb_uid'")->find();
						if($res_exist['uid'] and $res_exist['uid']!=$uid)
						{
							//已存在
							continue;
						}else {
							//绑定到该推广位下
							$data=array(
									'tb_uid'=>$tb_uid,
									'tb_pid'=>$tb_pid
							);
							if(!$User->create($data))
							{
								//验证不通过
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
										'msg'=>$User->getError()
								);
							}else {
								$res_save=$User->where("uid='$uid'")->save($data);
								if($res_save!==false)
								{
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'成功'
									);
									//跳出循环
									break;
								}else {
									//数据库错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 解除绑定淘宝账号
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function unbindTaobao()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//清除绑定的淘宝号
				$data=array(
						'tb_uid'=>null,
						'tb_pid'=>null
				);
				$res_save=$User->where("uid='$uid'")->save($data);
				if($res_save!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 检查是否绑定淘宝账号
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function whetherBindingTaobao()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//查询用户是否已绑定淘宝
				$userMsg=$User->getUserMsg($uid);
				if($userMsg['tb_uid'] and $userMsg['tb_pid'])
				{
					$is_binding='Y';
				}else {
					$is_binding='N';
				}
				$data=array(
						'is_binding'=>$is_binding
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 绑定淘宝渠道关系ID
	 * @param string $token:用户身份令牌
	 * @param string $tb_rid:淘宝渠道关系ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function bindingTbRid()
	{
	    if(trim(I('post.token')) and trim(I('post.tb_rid')))
	    {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //查询用户是否已绑定
	            $userMsg=$User->getUserMsg($uid);
	            if($userMsg['tb_rid']) {
	                //您已绑定淘宝渠道关系ID，请勿重复操作！
	                $res=array(
	                    'code'=>1,
	                    'msg'=>'您已绑定淘宝渠道关系ID，请勿重复操作！'
	                );
	            }else {
	                $tb_rid=trim(I('post.tb_rid'));//淘宝渠道关系ID
	                //绑定
	                $data=array(
	                    'tb_rid'=>$tb_rid,
	                );
	                if(!$User->create($data)) {
	                    //验证不通过
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
	                        'msg'=>$User->getError()
	                    );
	                }else {
	                    $res_save=$User->where("uid='$uid'")->save($data);
	                    if($res_save!==false) {
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                            'msg'=>'成功'
	                        );
	                    }else {
	                        //数据库错误
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                        );
	                    }
	                }
	            }
	        }
	    }else {
	        //参数不正确，参数缺失
	        $res=array(
	            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
	            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
	        );
	    }
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 解除绑定淘宝渠道关系ID
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function unbindTbRid()
	{
	    if(trim(I('post.token'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //清除绑定的淘宝号
	            $data=array(
	                'tb_rid'=>'',
	            );
	            $res_save=$User->where("uid='$uid'")->save($data);
	            if($res_save!==false) {
	                $res=array(
	                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                    'msg'=>'成功'
	                );
	            }else {
	                //数据库错误
	                $res=array(
	                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                );
	            }
	        }
	    }else {
	        //参数不正确，参数缺失
	        $res=array(
	            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
	            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
	        );
	    }
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 检查是否绑定淘宝渠道关系ID
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function whetherBindingTbRid()
	{
	    if(trim(I('post.token'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //查询用户是否已绑定淘宝
	            $userMsg=$User->getUserMsg($uid);
	            if($userMsg['tb_rid']) {
	                $is_binding='Y';
	            }else {
	                $is_binding='N';
	            }
	            $data=array(
	                'is_binding'=>$is_binding
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
	            );
	        }
	    }else {
	        //参数不正确，参数缺失
	        $res=array(
	            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
	            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
	        );
	    }
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    /**
     * 获取用户团队信息
     * @param string $token:用户身份令牌
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function getTeamList()
    {
        if(trim(I('post.token'))) {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            $page = intval(I('post.p'));
            if(empty($page)){
                $page = intval(I('post.page'));
            }

            if(empty($page)){
                $page = 1;
            }
            $psize = 50;

            $type = trim(I('post.type'));
            if(empty($type)){
                $type = 't1';
            }

            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                $userMsg=$User->getUserDetail($uid);
                //用户名称
                if($userMsg['detail']['nickname']) {
                    $user_name=$userMsg['detail']['nickname'];
                }else if($userMsg['detail']['truename']) {
                    $user_name=$userMsg['detail']['truename'];
                }else if($userMsg['phone']) {
                    $user_name=substr_replace($userMsg['phone'],'****',3,4);
                }else if($userMsg['username']) {
                    $user_name=$userMsg['username'];
                }
                //获取推荐人信息
                $referrer_name='无';
                if($userMsg['referrer_id'])
                {
                    $referrerMsg=$User->getUserDetail($userMsg['referrer_id']);
                    if($referrerMsg['detail']['nickname']) {
                        $referrer_name=$referrerMsg['detail']['nickname'];
                    }else if($referrerMsg['detail']['truename']) {
                        $referrer_name=$referrerMsg['detail']['truename'];
                    }else if($referrerMsg['account']) {
                        $referrer_name=$referrerMsg['account'];
                    }
                }

				$childs = M('User')->where("referrer_id=$uid")->field('uid')->select();
				$team1_num = count($childs);
                $temp_arr = array_map(function($item){
                    return $item['uid'];
                },$childs);

                $referr_ids = implode(",",$temp_arr);
                if(empty($referr_ids)){
                    $team2_num = 0;
                }
                else{
                    $team2_num = M('User')->where("referrer_id in(".$referr_ids.")")->count();
                    $team2_num = intval($team2_num);
                }

                $teamlist1=array();
                if($type=='t1'){
                    //获取直接推荐团队列表
                    $sql1="select u.uid,u.group_id,u.is_buy,u.username,u.phone,u.register_time,d.avatar,d.nickname,d.truename,g.title as group_name from __PREFIX__user u left join __PREFIX__user_detail d on u.uid=d.user_id left join __PREFIX__user_group g on u.group_id=g.id where u.referrer_id=$uid order by u.uid desc limit ".($page-1)*$psize.",".$psize;
                    $list1=M()->query($sql1);

                    foreach ($list1 as $l) {
                        $uid2=$l['uid'];
                        //全部直接推荐人
                        //名称
                        if($l['nickname']) {
                            $name=$l['nickname'];
                            //判断昵称是否经过base64编码
                            if( is_base641($name) ){
                                //将昵称解码
                                $name=base64_decode($name);
                            }
                        }else if ($l['truename']){
                            $name=$l['truename'];
                        }else if($l['phone']) {
                            //隐藏手机号码
                            $name=substr_replace($l['phone'],'****',3,4);
                        }else if($l['username']) {
                            $name=$l['username'];
                        }
                        //头像
                        if($l['avatar']) {
                            //判断头像是否为第三方应用头像
                            if(is_url($l['avatar'])) {
                                $avatar=$l['avatar'];
                            }else {
                                $avatar=WEB_URL.$l['avatar'];
                            }
                        }else {
                            //$avatar='/Public/static/img/logo.png';
                            $avatar='';
                        }
                        //用户邀请人数
                        //$referrer_num=$User->where("FIND_IN_SET($uid2,path)")->count();
                        $referrer_num=$User->where("referrer_id=$uid2")->count();
                        $teamlist1[]=array(
                            'uid'=>$l['uid'],
                            'group_id'=>$l['group_id'],
                            'is_buy'=>$l['is_buy'],
                            'name'=>$name,
                            'avatar'=>$avatar,
                            'group_name'=>$l['group_name'],
                            'register_time'=>$l['register_time'],
                            'referrer_num'=>$referrer_num,
                            'referrer_name'=>$user_name
                        );
                    }
                }
                //获取间接推荐人
                $teamlist2=array();
//                if($type=='t2'){
                    if(!empty($referr_ids)) {
                        //$referrer_allid=substr($referrer_allid, 0,-1);

                        $sql2="select u.uid,u.group_id,u.is_buy,u.username,u.phone,u.register_time,d.avatar,d.nickname,d.truename,g.title as group_name from __PREFIX__user u left join __PREFIX__user_detail d on u.uid=d.user_id left join __PREFIX__user_group g on u.group_id=g.id where u.referrer_id in ($referr_ids) order by u.uid desc limit ".($page-1)*$psize.",".$psize;
                        $list2=M()->query($sql2);
                        foreach ($list2 as $l) {
                            $uid3=$l['uid'];
                            //名称
                            if($l['nickname']) {
                                $name2=$l['nickname'];
                                //判断昵称是否经过base64编码
                                if( is_base641($name2) ){
                                    //将昵称解码
                                    $name2=base64_decode($name2);
                                }
                            }else if($l['truename']) {
                                $name2=$l['truename'];
                            }else if($l['phone']) {
                                //隐藏手机号码
                                $name2=substr_replace($l['phone'],'****',3,4);
                            }else if($l['username']) {
                                $name2=$l['username'];
                            }
                            //头像
                            if($l['avatar']) {
                                //判断头像是否为第三方应用头像
                                if(is_url($l['avatar'])) {
                                    $avatar2=$l['avatar'];
                                }else {
                                    $avatar2=WEB_URL.$l['avatar'];
                                }
                            }else {
                                //$avatar2='/Public/static/img/logo.png';
                                $avatar2='';
                            }
                            //用户邀请人数
                            //$referrer_num2=$User->where("FIND_IN_SET($uid3,path)")->count();
                            $referrer_num2=$User->where("referrer_id=$uid3")->count();
                            //邀请人
                            $referrer_name2='无';
                            if($l['referrer_id'])
                            {
                                $referrerMsg2=$User->getUserDetail($l['referrer_id']);
                                if($referrerMsg2['detail']['nickname']) {
                                    $referrer_name2=$referrerMsg2['detail']['nickname'];
                                }else if($referrerMsg2['detail']['truename']) {
                                    $referrer_name2=$referrerMsg2['detail']['truename'];
                                }else if($referrerMsg2['phone']) {
                                    $referrer_name2=substr_replace($referrerMsg2['phone'],'****',3,4);
                                }else if($referrerMsg2['username']) {
                                    $referrer_name2=$referrerMsg2['username'];
                                }
                            }
                            $teamlist2[]=array(
                                'uid'=>$l['uid'],
                                'group_id'=>$l['group_id'],
                                'is_buy'=>$l['is_buy'],
                                'name'=>$name2,
                                'avatar'=>$avatar2,
                                'group_name'=>$l['group_name'],
                                'referrer_num'=>$referrer_num2,
                                'referrer_name'=>$referrer_name2
                            );
                        }
                    }
//                }

                //总推荐人数
                //用户邀请总人数
//                $referrer_num = $User->where("FIND_IN_SET($uid,path)")->count();
//                if (!empty($referrer_num)) {
//                    $referrer_num = $referrer_num-1;
//                }
                $referrer_num=$team1_num+$team2_num;
                $data=array(
                    'referrer_num'=>$referrer_num,
                    'referrer_name'=>$referrer_name,
                    'team1_num'=>$team1_num,
                    'team2_num'=>$team2_num,
                    'teamlist1'=>$teamlist1,
                    'teamlist2'=>$teamlist2,
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
	/**
	 * 获取团队收益统计
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getTeamStatistics()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//一级团队人数
				$team_num1=$User->where("referrer_id='$uid'")->count();

				//获取团队列表-一级、二级
				$all_uid='';
				$referrerList=$User->where("referrer_id='$uid'")->field('uid')->select();
				if($referrerList)
				{
					foreach ($referrerList as $l)
					{
						$all_uid.=$l['uid'].',';
					}
				}
				if($all_uid)
				{
					//一级团队列表
					$all_uid=substr($all_uid, 0,-1);
					$all_uid1=$all_uid;//一级团队列表
					//二级团队人数
					$team_num2=$User->where("referrer_id in ($all_uid)")->count();
					//总人数
					$team_num=$team_num1+$team_num2;

					//二级团队列表
					$referrerList2=$User->where("referrer_id in ($all_uid)")->field('uid')->select();
					if($referrerList2)
					{
						foreach ($referrerList2 as $l)
						{
							$all_uid.=$l['uid'].',';
						}
						$all_uid=substr($all_uid, 0,-1);
					}

					//包含自身
					$all_uid=$uid.','.$all_uid;
					$all_uid1=$uid.','.$all_uid1;//用户自身+一级团队

					//今日注册人数
					$today_num=$User->where("referrer_id in ($all_uid1) and TO_DAYS(register_time) = TO_DAYS(NOW())")->count();

					//昨日注册人数
					$yesterday_num=$User->where("referrer_id in ($all_uid1) and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(register_time)")->count();

					//订单统计
					//淘宝总订单
					$TbOrder=new \Common\Model\TbOrderModel();
					$tb_num=$TbOrder->where("user_id in ($all_uid)")->count();
					//今日
					$tb_num_today=$TbOrder->where("user_id in ($all_uid) and TO_DAYS(create_time) = TO_DAYS(NOW())")->count();
					//昨日
					$tb_num_yesterday=$TbOrder->where("user_id in ($all_uid) and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(create_time)")->count();
					//本月
					$tb_num_month=$TbOrder->where("user_id in ($all_uid) and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
					//上月
					$tb_num_lastmonth=$TbOrder->where("user_id in ($all_uid) and date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->count();

					//拼多多总订单
					$PddOrder=new \Common\Model\PddOrderModel();
					$pdd_num=$PddOrder->where("user_id in ($all_uid)")->count();
					//今日
					$pdd_num_today=$PddOrder->where("user_id in ($all_uid) and TO_DAYS(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i')) = TO_DAYS(NOW())")->count();
					//昨日
					$pdd_num_yesterday=$PddOrder->where("user_id in ($all_uid) and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'))")->count();
					//本月
					$pdd_num_month=$PddOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(now(),'%Y-%m')")->count();
					//上月
					$pdd_num_lastmonth=$PddOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(order_create_time,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->count();

                    //京东总订单
                    $JingdongOrder=new \Common\Model\JingdongOrderDetailModel();
                    $jd_num=$JingdongOrder->where("user_id in ($all_uid)")->count();
                    //今日
                    $jd_num_today=$JingdongOrder->where("user_id in ($all_uid) and TO_DAYS(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i')) = TO_DAYS(NOW())")->count();
                    //昨日
                    $jd_num_yesterday=$JingdongOrder->where("user_id in ($all_uid) and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->count();
                    //本月
                    $jd_num_month=$JingdongOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(now(),'%Y-%m')")->count();
                    //上月
                    $jd_num_lastmonth=$JingdongOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->count();

                    //唯品会总订单
                    $VipOrder=new \Common\Model\VipOrderModel();
                    $vip_num=$VipOrder->where("user_id in ($all_uid)")->count();
                    //今日
                    $vip_num_today=$VipOrder->where("user_id in ($all_uid) and TO_DAYS(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i')) = TO_DAYS(NOW())")->count();
                    //昨日
                    $vip_num_yesterday=$VipOrder->where("user_id in ($all_uid) and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'))")->count();
                    //本月
                    $vip_num_month=$VipOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(now(),'%Y-%m')")->count();
                    //上月
                    $vip_num_lastmonth=$VipOrder->where("user_id in ($all_uid) and date_format(FROM_UNIXTIME(orderTime/1000,'%Y-%m-%d %H:%i'),'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->count();

					$order_num=array(
							'allnum'=>$tb_num+$pdd_num+$vip_num+$jd_num,
							'today'=>$tb_num_today+$pdd_num_today+$vip_num_today+$jd_num_today,
							'yesterday'=>$tb_num_yesterday+$pdd_num_yesterday+$vip_num_yesterday+$jd_num_yesterday,
							'month'=>$tb_num_month+$pdd_num_month+$vip_num_month+$jd_num_month,
							'lastmonth'=>$tb_num_lastmonth+$pdd_num_lastmonth+$vip_num_lastmonth+$jd_num_lastmonth,
					);

					//统计团队收益
					$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
					//总收益
					$all_money=$UserBalanceRecord->where("user_id in ($all_uid)")->sum('money');
					$all_money=$all_money/100;
					//今日收益
					$today_money=$UserBalanceRecord->where("user_id in ($all_uid) and action!='draw' and TO_DAYS(pay_time) = TO_DAYS(NOW())")->sum('money');
					$today_money=$today_money/100;
					//昨日收益
					$yesterday_money=$UserBalanceRecord->where("user_id in ($all_uid) and action!='draw' and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(pay_time)")->sum('money');
					$yesterday_money=$yesterday_money/100;
					//本月收益
					$month_money=$UserBalanceRecord->where("user_id in ($all_uid) and action!='draw' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
					$month_money=$month_money/100;
					//上月收益
					$lastmonth_money=$UserBalanceRecord->where("user_id in ($all_uid) and action!='draw' and date_format(pay_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('money');
					$lastmonth_money=$lastmonth_money/100;

					$money=array(
							'all'=>$all_money,
							'today'=>$today_money,
							'yesterday'=>$yesterday_money,
							'month'=>$month_money,
							'lastmonth'=>$lastmonth_money,
					);

					$data=array(
							'team_num'=>$team_num,
							'team_num1'=>$team_num1,
							'team_num2'=>$team_num2,
							'today_num'=>$today_num,
							'yesterday_num'=>$yesterday_num,
							'order_num'=>$order_num,
							'money'=>$money,
					);
				}else {
					$order_num=array(
							'allnum'=>0,
							'today'=>0,
							'yesterday'=>0,
							'month'=>0,
							'lastmonth'=>0,
					);
					$money=array(
							'all'=>0,
							'today'=>0,
							'yesterday'=>0,
							'month'=>0,
							'lastmonth'=>0,
					);
					$data=array(
							'team_num'=>0,
							'team_num1'=>0,
							'team_num2'=>0,
							'today_num'=>0,
							'yesterday_num'=>0,
							'order_num'=>$order_num,
							'money'=>$money,
					);
				}
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取用户佣金比率
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getCommissionRate()
	{
        #获取后台这是分佣比例
        $commissionModel = new \Common\Model\HostCommissionModel();
        $commission = $commissionModel->where('id=1')->find();
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取会员组佣金比率
				$sql="select g.fee_user from __PREFIX__user u,__PREFIX__user_group g where u.uid='$uid' and u.group_id=g.id";
				$res_g=M()->query($sql);
				if($res_g!==false)
				{
					$data=array(
							'commission_rate'=>$res_g[0]['fee_user'],
							'live_goods_fee_host'=>$commission['fee_host'],
							'live_goods_fee_sell'=>$commission['fee_sell']
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		} else {
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                'msg'=>'成功',
                'data'=>array(
                    'live_goods_fee_host'=>$commission['fee_host'],
                    'live_goods_fee_sell'=>$commission['fee_sell']
                )
            );
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取专属客服
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getService()
	{
		if(trim(I('post.token'))) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
			    $shop_id = trim(I('post.shop_id'))?trim(I('post.shop_id')):0;
//				$uid=$res_token['uid'];
//				$userMsg=$User->getUserMsg($uid);
                if($shop_id == 0)
                {
                    $weixin=PLATFORM_WX;
                }else{
                    $shopModel = new ShopMerchUserModel();
                    $shop = $shopModel->getOne(['id'=>$shop_id]);
                    $weixin = empty($shop)||empty($shop['cus_serve']) ? $shop['mobile'] :$shop['cus_serve'];
                }
//				if($userMsg['path'] and $userMsg['path']!=$userMsg['uid'])
//				{
//					$path=$userMsg['path'];
//					$sql="select d.weixin from __PREFIX__user u,__PREFIX__user_detail d WHERE u.uid in ($path) and u.uid!=$uid and u.group_id in (3,4) and u.uid=d.user_id order by u.uid desc";
//					$res_vip=M()->query($sql);
//					if($res_vip[0]['weixin'])
//					{
//						$weixin=$res_vip[0]['weixin'];
//					}
//				}
//				if($weixin==PLATFORM_WX)
//				{
//					//查询城市代理商
//					$phone_province=$userMsg['phone_province'];
//					$phone_city=$userMsg['phone_city'];
//					if($phone_province and $phone_city)
//					{
//						$sql2="select d.weixin from __PREFIX__user u,__PREFIX__user_detail d WHERE u.phone_province='$phone_province' and u.phone_city='$phone_city' and u.is_agent='Y' and u.uid=d.user_id";
//						$res_agent=M()->query($sql2);
//						if($res_agent[0]['weixin'])
//						{
//							$weixin=$res_agent[0]['weixin'];
//						}
//					}
//				}
				$data=array(
						'weixin'=>$weixin,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取用户所属代理商
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->agent_id:代理商ID
	 */
	public function getAgent()
	{
	    if(trim(I('post.token'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            $userMsg=$User->where("uid=$uid")->field('path')->find();
	            $agent_id=0;//代理商ID
	            if($userMsg['path']){
	                $path=$userMsg['path'];
	                $agentMsg=$User->where("uid in ($path) and is_agent='Y'")->field('uid,agent_switch')->order('uid desc')->find();
	                if ($agentMsg){
	                    if ($agentMsg['agent_switch']=='Y'){
                            $agent_id=$agentMsg['uid'];
                        }
                    }
	            }
	            $data=array(
	                'agent_id'=>$agent_id,
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
	            );
	        }
	    }else {
	        //参数不正确，参数缺失
	        $res=array(
	            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
	            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
	        );
	    }
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    public function bobao(){
        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
        $User=new \Common\Model\UserModel();
        $list = $UserBalanceRecord->field('user_id,money,pay_time')->where("status=2")->order('pay_time desc')->limit(20)->select();
        foreach($list as $k=>&$row){
            $UserMsg=$User->getUserDetail($row['user_id']);
            $row['account'] = $UserMsg['account'];
            //$row['money'] = round($row['money']/100,2);
            $row['money'] = sprintf("%.2f",$row['money']/100);
            $list[$k]['type'] = 0;
            if (COMMISSION_BROADCAST == 'Y') {
                $list[$k]['type'] = 1;
            }
        }
        $res=array(
            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'成功',
            'data'=>array(
                'list'=>$list
            )
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>
