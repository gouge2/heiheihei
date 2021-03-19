<?php
/**
 * by 翠花 http://www.lailu.shop
 * 会员详细信息管理类
 */
namespace Common\Model;
use Think\Model;

class UserDetailModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','所属会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','请选择正确的会员',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('nickname','1,100','昵称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			//array('avatar','1,100','头像路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('truename','1,30','真实姓名不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('sex',array('1','2','3'),'请选择正确的性别！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1男 2女 3保密
			array('height','1,20','身高不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('weight','1,20','体重不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('blood',array('1','2','3','4','5'),'请选择正确的血型！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1A型 2B型 3AB型 4O型 5其它		
			array('birthday','is_date','出生日期格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('qq','1,12','QQ号不超过12个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过12个字符
			array('weixin','1,30','微信号不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('province','1,30','省份不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('city','1,30','城市不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('county','1,30','县名不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('detail_address','1,100','详细地址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('signature','1,200','个性签名不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
	);
	
	/**
	 * 获取会员信息
	 * @param int $user_id:用户ID
	 * @return array|false
	 */
	public function getUserDetailMsg($user_id)
	{
		$msg = $this->where("user_id='$user_id'")->find();
		if ($msg!==false) {
			return $msg;
		} else {
			return false;
		}
	}

	/**
	 * 获取会员信息（展示在短视频/直播个人主页）
	 */
	public function getUserDetailHome($user_id, $at_id, $platform)
	{
		$whe 	= ['user_id' => $user_id];
		$msg 	= $this
				->field('user_id,nickname,avatar,sex,birthday,signature,praise_short,concern_sum,fans_sum')
				->where($whe)
				->find();

		if ($msg) {
			$Short     	     	= new \Common\Model\ShortModel();    				// 短视频模型
			$UserPraise    	 	= new \Common\Model\UserPraiseModel();   			// 用户点赞记录模型
			$UserConcern        = new \Common\Model\UserConcernModel();         	// 用户关注关系模型
			$UserAuthCode       = new \Common\Model\UserAuthCodeModel();         
			$LiveRoom    	 	= new \Common\Model\LiveRoomModel();   			
			$ShortLiveGoods    	= new \Common\Model\ShortLiveGoodsModel();   			

			// 判断头像是否为第三方应用头像
			if ($msg['avatar'] && !is_url($msg['avatar'])) {
				$msg['avatar'] = WEB_URL . $msg['avatar'];
			}

			// 处理参数
			$msg['sex']      	= $msg['sex'] == 1 ? 'man': 'women';
			$msg['birthday'] 	= birthday($msg['birthday']);

			// 是否关注标识
            $conc_cou           = $UserConcern->where(['by_id' => $user_id, 'user_id' => $at_id])->getField('id');
			$msg['concern_iden']= $conc_cou ? 1 : 0;
			
			// 直播的标识
			$lv_list            = $LiveRoom->getIsLive($whe);
			$msg['live'] 		= isset($lv_list[$msg['user_id']]) ? $lv_list[$msg['user_id']] : ['live_iden' => 0, 'live_url' => '', 'room_id' => '0', 'is_fake' => 'N'];

			// 推荐好物
			$sg_whe             = $whe;
			if ($platform == 'applet') {   	// 微信小程序不要淘宝的商品
				$sg_whe['from'] = ['neq', 'tb'];
			}
			$sg_whe['is_status']= ['in', [0,1]];
			$all_list           = $ShortLiveGoods->getList($sg_whe, 'DISTINCT goods_id,from','');
			$goods_num			= $all_list ? (int)count($all_list) : 0;
			$msg['goods']      	= ['goods_num' => $goods_num, 'goods_link' => ''];

			// 标签
			$msg['tag_list']    = ['射手座', '95后'];

			// 作品/直播/喜欢数据
			$msg['works_num'] 	= (int)$Short->where($whe)->where(['is_status' => 1, 'is_recorded' => 0])->count();
			$msg['lives_num'] 	= (int)$Short->where($whe)->where(['is_status' => 1, 'is_recorded' => 1])->count();


            $likes  = $UserPraise->where($whe)->getField('short_id', true); // 喜欢短视频ID列表
            if ($likes) {
                $map['id'] = array('in', $likes);
                $map    = array_merge($map, ['is_status' => 1, 'is_recorded' => 0]);
                $like_list  = $Short->where($map)->count() ?: 0;
            }
			$msg['likes_num'] 	= (int) $like_list ?: 0;

			// 翠花号
			$ll = $UserAuthCode->getMsg($whe);
			$msg['ll_no'] 		= $ll ? $ll['auth_code'] : '';

			// 类型转换
			$msg['praise_short']= (int)$msg['praise_short'];
			$msg['concern_sum'] = (int)$msg['concern_sum'];
			$msg['fans_sum'] 	= (int)$msg['fans_sum'];
		}

		return $msg;
	}

	/**
	 * 关注用户列表/随机未关注列表
	 */
	public function getConcernList($type, $where, $limit, $page, $conc = true)
	{
		$data   = [];
		$field  = 'ud.user_id,nickname,avatar,signature,birthday,sex,fans_sum';
		$whe    = $where;

		// 加表名
		if (isset($whe['user_id'])) {
			$whe['ud.user_id'] = $whe['user_id'];
			unset($whe['user_id']);
		}

		// 关注用户列表
		if ($conc) {
			if ($type == 'live') {   // 直播页
				$list  	= $this->alias('ud')
						->join('__USER__ u ON u.uid= ud.user_id', 'LEFT')
						->join('__LIVE_ROOM__ lr ON lr.user_id= ud.user_id', 'LEFT')
						->field($field)->where($whe)->where("u.is_host='Y'")->page($page, $limit)->order('lr.is_status asc')->group('ud.user_id')->select();

			} elseif ($type == 'user') {
				$list  	= $this->alias('ud')
						->join('__LIVE_ROOM__ lr ON lr.user_id= ud.user_id', 'LEFT')
						->field($field)->where($whe)->page($page, $limit)->order('lr.is_status asc')->group('ud.user_id')->select();
			}

		// 随机未关注列表
		} else {
			if ($type == 'live') {	// 直播页
				$list  	= $this->alias('ud')
						->join('__USER__ u ON u.uid= ud.user_id', 'LEFT')
						->field($field)->where($whe)->where("u.is_host='Y'")->limit($limit)->order('rand()')->group('ud.user_id')->select();

			} elseif ($type == 'user') {
				$list  	= $this->alias('ud')->field($field)->where($whe)->limit($limit)->order('rand()')->group('ud.user_id')->select();
			}
		}

		if ($list) {
			// 正在直播列表
			$LiveRoom   = new \Common\Model\LiveRoomModel();
			$lv_list 	= $LiveRoom->getIsLive($where);

			foreach ($list as $val) {
				$temp 			    = $val;

				// 处理参数
				$temp['sex']        = $val['sex'] == 1 ? 'man' : 'women';
				$temp['birthday']   = birthday($val['birthday']);

				// 直播标识
				$temp['live']   = isset($lv_list[$val['user_id']]) ? $lv_list[$val['user_id']] : ['live_iden' => 0, 'live_url' => '', 'room_id' => '0', 'is_fake' => 'N'];

				// 类型转换
				$temp['fans_sum']   = (int)$val['fans_sum'];

				// 判断头像是否为第三方应用头像
				if ($temp['avatar'] && !is_url($temp['avatar'])) {
					$temp['avatar'] = WEB_URL . $temp['avatar'];
				}

				$data[]             = $temp;
			}
		}
	

		return $data;
	}

	/**
	 * 用户ID搜索 或者 翠花号搜索
	 */
	public function uIdOrDeer($u_str)
	{
		$res 			= 0;

		$UserAuthCode 	= new \Common\Model\UserAuthCodeModel();

		if ($u_str) {
			// 先查用户ID 有没有  在查邀请码
			$uid   					= $this->where(['user_id' => $u_str])->getField('user_id');
			if ($uid) {
				$res 	= $uid;
			} else {
				$ac_uid         	= $UserAuthCode->where(['is_used' => 'Y', 'auth_code' => $u_str])->getField('user_id');

				if ($ac_uid) {
					$res = $ac_uid;
				}
			}
		}

		return $res;
	}

	/**
	 * 查询某用户的信息
	 */
	public function getUserIntro($uid, $at_id, $room_id)
	{
		$data   = null;
		$field  = 'user_id,nickname,signature,avatar,sex,concern_sum,fans_sum';

		if ($uid) {
			$whe    = ['user_id' => $uid];

			$msg  	= $this->field($field)->where($whe)->find();

			if ($msg) {
				$UserConcern        		= new \Common\Model\UserConcernModel();
				$LiveSite        			= new \Common\Model\LiveSiteModel();
				$GiftGive        			= new \Common\Model\GiftGiveModel();

				// 处理参数
				$msg['sex']        			= $msg['sex'] == 1 ? 'man' : 'women';

				// 判断头像是否为第三方应用头像
				if ($msg['avatar'] && !is_url($msg['avatar'])) {
					$msg['avatar'] 			= WEB_URL . $msg['avatar'];
				}

				// 关注标识
				$msg['concern_iden'] 		= 0;
				if ($at_id) {
					$cid 					= $UserConcern->where(['user_id' => $at_id, 'by_id' => $uid])->getField('id');
					$msg['concern_iden']    = $cid ? 1 : 0;
				}

				// 送出鹿角
				$msg['send_out_ll']         = 0;
				if ($at_id && $room_id) {
					$ls_site 				= $LiveSite->where(['room_id' => $room_id])->order('site_id desc')->getField('site_id');

					if ($ls_site) {
						$gg_whe 			= ['site_id' => $ls_site, 'user_id' => $uid, 'is_status' => 'succ'];
						$num 				= $GiftGive->where($gg_whe)->sum('money');
						$msg['send_out_ll'] = $num ? (int)$num : 0;
					}
				}

				// 是否还在直播间中
				$room_info 					= get_live_room_info($room_id, 'group_user');
				$msg['user_in_live']        = in_array($uid, $room_info['group_user']) ? 1 : 0;

				// 禁言标识
				$handle         			= live_room_handle_user($room_id);       // 直播间禁言和踢出列表
                $msg['is_mute'] 			= in_array($uid, $handle['mute_arr']) ? 1 : 0;

				// 类型转换
				$msg['fans_sum']   			= (int)$msg['fans_sum'];
				$msg['concern_sum']   		= (int)$msg['concern_sum'];

				$data             			= $msg;	
			}
		}

		return $data;
	}

	/**
	 * 查询PK方主播信息
	 */
	public function getPkHostIntro($uid, $at_id, $room_id)
	{
		$data   = null;
		$field  = 'nickname,avatar';

		if ($uid) {
			$whe    = ['user_id' => $uid];

			$msg  	= $this->field($field)->where($whe)->find();

			if ($msg) {
				$UserConcern        		= new \Common\Model\UserConcernModel();

				// 判断头像是否为第三方应用头像
				if ($msg['avatar'] && !is_url($msg['avatar'])) {
					$msg['avatar'] 			= WEB_URL . $msg['avatar'];
				}

				// 关注标识
				$msg['concern_iden'] 		= 0;
				if ($at_id) {
					$cid 					= $UserConcern->where(['user_id' => $at_id, 'by_id' => $uid])->getField('id');
					$msg['concern_iden']    = $cid ? 1 : 0;
				}

				// 直播间信息
				$room_info 					= get_live_room_info($room_id);
				$msg['people']				= $room_info['people'];				// 人数
				$msg['room_heat']			= $room_info['room_heat'];			// 热度

				$data             			= $msg;	
			}
		}

		return $data;
	}


	/**
	 * 查询用户列表
	 */
	public function getSeekList($kw, $at_id, $limit, $page)
	{
		$data   		= [];

		$field  		= 'ud.user_id,nickname,signature,avatar,sex,fans_sum';
		$whe    		= ['ud.nickname' => ['like', '%'. $kw .'%'], 'u.auth_code' => $kw, 'lr.room_id' => $kw];
		$whe['_logic']	= 'OR';

		$list  			= $this->alias('ud')
				  		  ->join('__USER__ u ON u.uid= ud.user_id', 'LEFT')
				          ->join('__LIVE_ROOM__ lr ON lr.user_id= ud.user_id', 'LEFT')
						  ->field($field)->where($whe)->page($page, $limit)->order('lr.is_status asc')->select();

		if ($list) {
			$LiveRoom   = new \Common\Model\LiveRoomModel();
            $UserConcern= new \Common\Model\UserConcernModel();

			$uid_arr  	= [];

			foreach ($list as $val) {
				$uid_arr[]	= $val['user_id'];
			}

			// 正在直播列表
			$lv_list 	= $LiveRoom->getIsLive(['user_id' => ['in', $uid_arr]]);

			// 关注列表
            $uc_list        = $at_id ? $UserConcern->where(['by_id' => ['in', $uid_arr], 'user_id' => $at_id])->getField('by_id', true) : [];

			foreach ($list as $val) {
				$temp 			    	= $val;

				// 处理参数
				$temp['sex']        	= $val['sex'] == 1 ? 'man' : 'women';

				// 判断头像是否为第三方应用头像
				if ($temp['avatar'] && !is_url($temp['avatar'])) {
					$temp['avatar'] 	= WEB_URL . $temp['avatar'];
				}

				// 直播标识
				$temp['live']   		= isset($lv_list[$val['user_id']]) ? $lv_list[$val['user_id']] : ['live_iden' => 0, 'live_url' => '', 'room_id' => '0', 'is_fake' => 'N'];

				// 关注标识
                $temp['concern_iden']   = in_array($temp['user_id'], $uc_list) ? 1 : 0;

				// 类型转换
				$temp['fans_sum']   	= (int)$val['fans_sum'];

				$data[]             	= $temp;
			}
		}

		return $data;
	}
}