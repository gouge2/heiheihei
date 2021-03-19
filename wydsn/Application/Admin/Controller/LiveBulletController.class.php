<?php
/**
 * 直播房间弹幕管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class LiveBulletController extends AuthController
{
	// 弹幕一些状态信息
	public static $bullet_base = [
		'mute' => [
			'1' => ['name' => '否', 'sel' => 0],
			'2' => ['name' => '是', 'sel' => 0],
		],
		'kikc' => [
			'1' => ['name' => '否', 'sel' => 0],
			'2' => ['name' => '是', 'sel' => 0],
		]
	];

	/**
	 * 弹幕列表
	 */
	public function index()
	{
		// 搜索的数组
		$search     		= array_merge(['room_id' => '', 'u_str' => '', 'text' => ''], self::$bullet_base);

		// 搜索的值
		$page   			= I('get.p', self::$page);
		$search['room_id'] 	= I('room_id/d');
		$search['u_str'] 	= trim(I('get.u_str', ''));
		$search['text'] 	= trim(I('get.text', ''));
		$mute 				= I('get.mute/d');
		$kikc 				= I('get.kikc/d');


		$UserDetail 		= new \Common\Model\UserDetailModel();
		$UserAuthCode 		= new \Common\Model\UserAuthCodeModel();
		$LiveBullet 		= new \Common\Model\LiveBulletModel();
		$Page 				= new \Common\Model\PageModel();

		// 条件数组
		$whe 				= [];
		$uid_arr 			= $ulist = $clist = [];

		// 搜索条件判断
		if ($search['room_id']) {
			$whe['room_id'] = $search['room_id'];
		}

		if ($search['u_str']) {
			$whe['user_id'] = $UserDetail->uIdOrDeer($search['u_str']);  // 用户ID搜索或者翠花号搜索
		}

		if ($search['text']) {
			$whe['text'] 	= ['like', '%'. $search['text'] .'%'];
		}

		if ($mute) {
			$whe['is_mute'] = $mute - 1;

			foreach ($search['mute'] as $key => $val) {
				if ($mute == $key) {
					$search['mute'][$key]['sel'] = 1;
				}
			}
		}

		if ($kikc) {
			$whe['is_kikc'] = $kikc - 1;

			foreach ($search['kikc'] as $key => $val) {
				if ($kikc == $key) {
					$search['kikc'][$key]['sel'] = 1;
				}
			}
		}


		// 数据
		$count 			= $LiveBullet->where($whe)->count();
    	$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $LiveBullet->where($whe)->page($page, self::$limit)->order('room_id desc')->select();

		if ($list) {
			foreach ($list as $key => $val) {
				if (!in_array($val['user_id'], $uid_arr)) {
					$uid_arr[] = $val['user_id'];
				}
			}

			$ulist 		= $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');
			$clist 		= $UserAuthCode->where(['user_id' => ['in', $uid_arr], 'is_used' => 'Y'])->getField('user_id,auth_code');
		}


		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('ulist', $ulist);
		$this->assign('clist', $clist);
		$this->assign('search', $search);
	
		$this->display();
	}

	/**
	 * 禁言、取消禁言、踢出房间 事件操作
	 */
	public function handle()
	{
		$code = '0';

		$id   = I('post.id/d');
		$type = I('post.type/d');

		if ($id && $type && in_array($type, [1,2,3])) {
			$LiveBullet = new \Common\Model\LiveBulletModel();
			$UserDetail = new \Common\Model\UserDetailModel();
			$whe        = ['id' => $id];
			$bull		= $LiveBullet->field('id,room_id,user_id,is_mute,is_kikc')->where($whe)->find();

			if ($bull) {
				$nick   = $UserDetail->where(['user_id' => $bull['user_id']])->getField('nickname');
				
				$LiveBullet->startTrans();   // 启用事务
				try {
					$msg  = ['user_id' =>  $bull['user_id'], 'nickname' => $nick];

					// 踢出
					if ($type == 1) {   
						$LiveBullet->where($whe)->save(['is_kikc' => 1]);
						$msg['type'] = 'kikc';

					// 禁言
					} elseif ($type == 2) {
						$LiveBullet->where($whe)->save(['is_mute' => 1]);
						$msg['type'] = 'mute';
						
					// 取消禁言
					} elseif ($type == 3) {
						$LiveBullet->where($whe)->save(['is_mute' => 0]);
						$msg['type'] = 'unmute';	
					}
					
					// 事务提交
					$LiveBullet->commit(); 

					// 发送IM 关闭直播间
					$Im 		= new \Common\Controller\ImController();
					$Im->sendGroupMsg($bull['room_id'], 'handle_user', $msg);

					$code 		= '1';

				} catch(\Exception $e) {

					// 事务回滚
					$LiveBullet->rollback();
				}
				
			}	
		}
    	
    	echo $code;
	}
	
}