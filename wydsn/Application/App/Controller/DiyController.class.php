<?php
/**
 * by 翠花 http://http://livedd.com
 * 样式管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class DiyController extends AuthController
{
	/**
	 * 获取样式设置
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->moduleList:功能模块列表
	 */
	public function set()
	{
		// 读取缓存
		$moduleList = S('diy_moduleList');

	    if ($moduleList === false) {
	        //未设置缓存，进行设置
	        $DiyModule 	= new \Common\Model\DiyModuleModel();
	        $moduleList = $DiyModule->getModuleList('Y');
	        if ($moduleList !== false) {
	            //设置缓存
	            //不设置过期时间
	            S('diy_moduleList', $moduleList, ['type' => 'file', 'expire' => 0]);
	        } else {
	            //数据库错误
				$this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
	        }
	    }

		$this->ajaxSuccess(['moduleList' => $moduleList]);
	}

	/**
	 * 获取后台自定义菜单栏显示
	 */
	public function customAppNav()
	{
		$platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

		$list  			= ['index' => 'N', 'live' => 'N', 'shoot' => 'N', 'shop' => 'N', 'my' => 'N'];

		// 默认
		$nav   			= APP_NAV ? explode(',', APP_NAV) : [];

		// 苹果端
		if ($platform == 'ios') {
			$nav = APP_NAV_IOS ? explode(',', APP_NAV_IOS) : [];
		}

		// 苹果与安卓处理
		foreach ($list as $key => $val) {
			if (in_array($key, $nav)) {
				$list[$key] = 'Y';
			}
		}

		// 小程序端
		if ($platform == 'applet') {
			$list = APP_NAV_APPLET ? explode(',', APP_NAV_APPLET) : ['首页', '直播', '商城', '我的'];
		}

		$this->ajaxSuccess(['nav' => $list]);
	}

	/**
	 * 获取用户自定义样式图标
	 */
	public function customUserStyle()
	{
		$platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $token              = trim(I('post.token'));
		$def_img 		= WEB_URL .'/Public/static/admin/img/logo.png';

		$list           = [
			[
				'broad_tag' 	=> 'not_title',
				'broad_name' 	=> '',
				'column' 		=> [
					[
						'broad_tag' 	=> 'not_title',
						'cat_tag'   	=> 'my_income',
						'cat_name'   	=> '我的收益',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/my_income.png',
                        'width'         => '44',
                        'height'        => '44',
					],
					[
						'broad_tag' 	=> 'not_title',
						'cat_tag'   	=> 'order_detail',
						'cat_name'   	=> '订单明细',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/order_detail.png',
                        'width'         => '44',
                        'height'        => '44',
					],
					[
						'broad_tag' 	=> 'not_title',
						'cat_tag'   	=> 'my_fans',
						'cat_name'   	=> '我的粉丝',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/my_fans.png',
                        'width'         => '44',
                        'height'        => '44',
					],
					[
						'broad_tag' 	=> 'not_title',
						'cat_tag'   	=> 'invite_friend',
						'cat_name'   	=> '邀请好友',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/invite_friend.png',
                        'width'         => '44',
                        'height'        => '44',
					],
                    [
                        'broad_tag' 	=> 'not_title',
                        'cat_tag'   	=> 'cargo_details',
                        'cat_name'   	=> '带货明细',
                        'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/cargo_details.png',
                        'width'         => '44',
                        'height'        => '44',
                    ]
				],
			],
            [
                'broad_tag' 	=> 'be_title',
                'broad_name' 	=> '多商户',
                'column' 		=> [
                    [
                        'broad_tag' 	=> 'be_title',
                        'cat_tag'   	=> 'open_shop',
                        'cat_name'   	=> '我要开店',
                        'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/open_shop.png',
                        'width'         => '44',
                        'height'        => '44',
                    ],
                    [
                        'broad_tag' 	=> 'be_title',
                        'cat_tag'   	=> 'margin_management',
                        'cat_name'   	=> '保证金管理',
                        'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/margin_management.png',
                        'width'         => '44',
                        'height'        => '44',
                    ],
                    [
                        'broad_tag' 	=> 'be_title',
                        'cat_tag'   	=> 'merchant_background',
                        'cat_name'   	=> '商户后台',
                        'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/merchant_background.png',
                        'width'         => '44',
                        'height'        => '44',
                    ],
                ],
            ],
			[
				'broad_tag' 	=> 'be_title',
				'broad_name' 	=> '自营订单',
				'column' 		=> [
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'self_pend_pay',
						'cat_name'   	=> '待付款',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/self_pend_pay.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'self_send',
						'cat_name'   	=> '待发货',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/self_send.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'self_receiving',
						'cat_name'   	=> '待收货',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/self_receiving.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'self_finish',
						'cat_name'   	=> '已完成',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/self_finish.png',
                        'width'         => '18',
                        'height'        => '18',
					]
				],
			],
			[
				'broad_tag' 	=> 'be_title',
				'broad_name' 	=> '常用工具',
				'column' 		=> [
					/* [
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'new_class',
						'cat_name'   	=> '新人课堂',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/new_class.png',
					], */
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'live_wallet',
						'cat_name'   	=> '直播钱包',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/live_wallet.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'put_address',
						'cat_name'   	=> '收货地址',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/put_address.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'my_favorite',
						'cat_name'   	=> '我的收藏',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/my_favorite.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'member_center',
						'cat_name'   	=> '会员中心',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/member_center.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					/* [
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'faq',
						'cat_name'   	=> '常见问题',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/faq.png',
					] */
				],
			],
			[
				'broad_tag' 	=> 'be_title',
				'broad_name' 	=> '必备工具',
				'column' 		=> [
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'exclusive_service',
						'cat_name'   	=> '专属客服',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/exclusive_service.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'communique',
						'cat_name'   	=> '官方公告',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/communique.png',
                        'width'         => '18',
                        'height'        => '18',
					],
					[
						'broad_tag' 	=> 'be_title',
						'cat_tag'   	=> 'about_us',
						'cat_name'   	=> '关于我们',
						'cat_img'   	=> WEB_URL .'/Public/Upload/User/icon/about_us.png',
                        'width'         => '18',
                        'height'        => '18',
					],
				],
			]
		];
//		if ($list[0]['column'][4]['cat_tag'] == 'cargo_details') {
//            unset($list[0]['column'][4]);
//            $list[0]['column'] = array_merge($list[0]['column']);
//        }
        // 多商户开关
        $MerchUser = new \Common\Model\ShopMerchUserModel();
        $Bood = new \Common\Model\BoodModel();
        $User  = new \Common\Model\UserModel();
        $Multi = new \Common\Model\MultiMerchantModel();
        $multi = $Multi->where(['type' => 1])->find();
        $uid            = $User->getUserId($token);
        $metype = $MerchUser->where(['openid' => 'lailu_'.$uid])->field('status,accounttime')->find();

        $margin = $Bood->where(['user_id'=>$uid])->getField('id');

        // 原来没入驻或者已过期则不显示
        if ($metype) {
            if ($metype['status'] != 1 || date('Y-m-d',$metype['accounttime']) < date('Y-m-d')){
                unset($list[1]['column'][2]);
            } else {
                unset($list[1]['column'][0]);
            }
            if (empty($margin)) {
                unset($list[1]['column'][1]);
            }
            if (!$multi || $multi['settle_in'] == 1)
                unset($list[1]);
            else
                $list[1]['column'] = array_merge($list[1]['column']);
        } else {
            if (!$multi || $multi['settle_in'] == 1) {
                unset($list[1]);
                $list=  array_merge($list);
            } else {
                if (empty($margin)) {
                    unset($list[1]['column'][1]);
                }
                unset($list[1]['column'][2]);
                $list[1]['column'] = array_merge($list[1]['column']);
            }
        }

        // 急贝提审暂时禁用
        $jbUrl = $_SERVER['HTTP_HOST'];
        if (strpos('http://app.tywlkjyxgs.cn/',$jbUrl) !== false && $platform == 'ios') {
            unset($list[0]);
            unset($list[3]['column'][0], $list[3]['column'][3]);
            $list[3]['column'] = array_merge($list[3]['column']);
            unset($list[4]);
            $list = array_merge($list);
        }
		$this->ajaxSuccess(['list' => $list]);
	}

    /**
     * 获取积分相关配置
     */
    public function getPointsSeting()
    {
        $data = array(
            // 完善资料
            'task_info_award_type' => defined('TASK_INFO_AWARD_TYPE') ? TASK_INFO_AWARD_TYPE : '',  // 完善资料奖励类型，1积分 2余额 3成长值
            'task_info_award_num' => defined('TASK_INFO_AWARD_NUM') ? TASK_INFO_AWARD_NUM : '',     //完善资料奖励数值
            // 分享好友
            'user_upgrade_register' => defined('USER_UPGRADE_REGISTER') ? USER_UPGRADE_REGISTER : '',      //推荐注册增加经验值
            'user_upgrade_buy' => defined('USER_UPGRADE_BUY') ? USER_UPGRADE_BUY : '',        //推荐用户购物增加经验值
            // 积分系统
            'sign_award_type' => defined('SIGN_AWARD_TYPE') ? SIGN_AWARD_TYPE : '',     //签到奖励类型，1积分 2余额 3成长值
            'sign_award_model' => defined('SIGN_AWARD_MODEL') ? SIGN_AWARD_MODEL : '',   //签到奖励模式，1固定 2连续
            'sign_award_fixed_num' => defined('SIGN_AWARD_FIXED_NUM') ? SIGN_AWARD_FIXED_NUM : '',   //固定签到奖励数值
            'sign_award_continuous_num1' => defined('SIGN_AWARD_CONTINUOUS_NUM1') ? SIGN_AWARD_CONTINUOUS_NUM1 : '',   //连续签到奖励数值-第1天
            'sign_award_continuous_num2' => defined('SIGN_AWARD_CONTINUOUS_NUM2') ? SIGN_AWARD_CONTINUOUS_NUM2 : '',   //连续签到奖励数值-第2天
            'sign_award_continuous_num3' => defined('SIGN_AWARD_CONTINUOUS_NUM3') ? SIGN_AWARD_CONTINUOUS_NUM3 : '',   //连续签到奖励数值-第3天
            'sign_award_continuous_num4' => defined('SIGN_AWARD_CONTINUOUS_NUM4') ? SIGN_AWARD_CONTINUOUS_NUM4 : '',   //连续签到奖励数值-第4天
            'sign_award_continuous_num5' => defined('SIGN_AWARD_CONTINUOUS_NUM5') ? SIGN_AWARD_CONTINUOUS_NUM5 : '',   //连续签到奖励数值-第5天
            'sign_award_continuous_num6' => defined('SIGN_AWARD_CONTINUOUS_NUM6') ? SIGN_AWARD_CONTINUOUS_NUM6 : '',   //连续签到奖励数值-第6天
            'sign_award_continuous_num7' => defined('SIGN_AWARD_CONTINUOUS_NUM7') ? SIGN_AWARD_CONTINUOUS_NUM7 : '',   //连续签到奖励数值-第7天
        );

        $this->ajaxSuccess($data);
	}
}
?>
