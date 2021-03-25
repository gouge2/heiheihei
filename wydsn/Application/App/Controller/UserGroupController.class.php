<?php
/**
 * by 翠花 http://livedd.com
 * 用户组管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserGroupController extends AuthController
{
    /**
     * 获取会员组列表
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:会员组列表
     */
    public function getGroupList()
    {
        $UserGroup=new \Common\Model\UserGroupModel();
        $list=$UserGroup->where("is_freeze='N'")->order('id asc')->select();
        if($list!==false)
        {
            $data=array(
                'list'=>$list,
                'team_reward'=>array(
                    'team_reward1'=>defined('TEAM_REWARD1')?TEAM_REWARD1:0,
                    'team_reward2'=>defined('TEAM_REWARD2')?TEAM_REWARD2:0,
                    'team_reward1_virtual'=>defined('TEAM_REWARD1_VIRTUAL')?TEAM_REWARD1_VIRTUAL:0,//团队一级分红-虚拟
                    'team_reward2_virtual'=>defined('TEAM_REWARD2_VIRTUAL')?TEAM_REWARD2_VIRTUAL:0,//团队二级分红-虚拟
                )
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
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取会员组信息
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->groupMsg:会员组信息
     */
    public function getGroupMsg()
    {
        if(trim(I('post.id'))){
            $id=trim(I('post.id'));
            $UserGroup=new \Common\Model\UserGroupModel();
            $groupMsg=$UserGroup->where("id=$id")->find();
            if($groupMsg!==false)
            {
                $data=array(
                    'groupMsg'=>$groupMsg
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
	 * 获取费用配置
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param list:费用配置列表
	 */
	public function getFee()
	{
		$p1=array(
				'id'=>1,
				'title'=>UPGRADE_FEE_MONTH.'元/月',
				'fee'=>UPGRADE_FEE_MONTH
		);
		$p2=array(
				'id'=>2,
				'title'=>UPGRADE_FEE_YEAR.'元/年',
				'fee'=>UPGRADE_FEE_YEAR
		);
		$p3=array(
				'id'=>3,
				'title'=>UPGRADE_FEE_FOREVER.'元/终生',
				'fee'=>UPGRADE_FEE_FOREVER
		);
		$list=array($p1,$p2,$p3);
//		$list=array($p3);
		$data=array(
				'list'=>$list
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 会员升级
	 * @param string $token:用户身份令牌
	 * @param string $id:会员升级产品ID
	 * @param string $pay_method:支付方式 alipay支付宝支付 wxpay微信支付
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function upgrade()
	{
		if(trim(I('post.token')) and trim(I('post.id')) and trim(I('post.pay_method')))
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
				//判断用户会员组
				$msg=$User->getUserMsg($uid);
				if($msg['group_id']=='1')
				{
					$type=trim(I('post.id'));
					//下单支付
					$UserGroupRecharge=new \Common\Model\UserGroupRechargeModel();
					$res_add=$UserGroupRecharge->recharge($uid, 4,$type);
					if($res_add!==false)
					{
						//获取订单信息
						$orderMsg=$UserGroupRecharge->getOrderMsg($res_add);
						//订单生成成功，生成支付表单
						$pay_method=trim(I('post.pay_method'));
						if($pay_method=='wxpay')
						{
							//获取微信支付表单数据
							Vendor('pay.wxpay','','.class.php');
							$wxpay=new \wxpay();
							$body='会员升级';
							//订单号
							$out_trade_no='v1_'.$orderMsg['order_num'];//订单号
							//订单费用，精确到分
							$total_fee=$orderMsg['fee']*100;
							$notify_url=WEB_URL.'/app.php/WxNotify/notify_app';
							$AppParameters=$wxpay->GetAppParameters($body, $out_trade_no, $total_fee, $notify_url);
						}else if($pay_method=='alipay'){
							//订单生成成功，生成支付表单
							$orderMsg=$UserGroupRecharge->getOrderMsg($res_add);
							//获取支付宝请求参数
							Vendor('pay.alipayApp','','.class.php');
							$alipayApp=new \alipayApp();
							//订单描述
							$body='会员升级';
							//订单名称，必填
							$subject='会员升级';
							//商户订单号，商户网站订单系统中唯一订单号，必填
							$out_trade_no='v1_'.$orderMsg['order_num'];//订单号，前面加上v1来区别订单来源类型
							//付款金额，必填
							$total_amount=$orderMsg['fee'];
							$AppParameters=$alipayApp->GetParameters($body,$subject, $out_trade_no, $total_amount);
						} elseif ($pay_method == 'int_wx' || $pay_method == 'int_ali') {
                            $latipay = new LatipayController();
                            $out_trade_no       = 'v1_'.$orderMsg['order_num']; // 订单号
                            $total_amount       = $orderMsg['fee'];   // 付款金额，必填
                            $AppParameters  = $latipay->pay($out_trade_no, $total_amount,$pay_method,3);
                            $pay_parameters = $AppParameters['host_url'].'/'.$AppParameters['nonce'];
                        } elseif ($pay_method == 'paypal') {
                            $paypal = new PayPaiController();
                            $out_trade_no       = 'v1_'.$orderMsg['order_num']; // 订单号
                            $total_amount       = $orderMsg['fee'];   // 付款金额，必填
                            $AppParameters  = $paypal->pay($out_trade_no, $total_amount,$pay_method,3);
                            $pay_parameters = $AppParameters['payUrl'];
                        }
                        if (in_array($pay_method,['int_wx','int_ali','paypal'])) {
                            $data=array(
                                'AppParameters'=>$pay_parameters,
                                'order_id'=>$AppParameters['order_id']
                            );
                        } else {
                            $data=array(
                                'AppParameters'=>$AppParameters,
                            );
                        }
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
				}else {
					//只有普通会员可以升级
					$res=array(
							'code'=>$this->ERROR_CODE_USER['ONLY_ORDINARY_MEMBERS_CAN_BE_UPGRADED'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['ONLY_ORDINARY_MEMBERS_CAN_BE_UPGRADED']]
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
}
?>