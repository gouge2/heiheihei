<?php
/**
 * 直播房间禁播日志
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class LiveBanController extends AuthController
{

	/**
	 * 日志列表
	 */
	public function index()
	{
		// 搜索的值
		$page   			= I('get.p', self::$page);
		$room_id   			= I('room_id/d');


		$Admin 				= new \Admin\Model\AdminModel();
		$LiveBan 			= new \Common\Model\LiveBanModel();
		$Page 				= new \Common\Model\PageModel();

		// 条件数组
		$whe 				= [];
		$aid_arr 			= $alist = [];

		// 搜索条件
		if ($room_id) {
			$whe['room_id'] = $room_id;
		}


		// 数据
		$count 				= $LiveBan->where($whe)->count();
    	$show 				= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 				= $LiveBan->where($whe)->page($page, self::$limit)->order('id desc')->select();

		if ($list) {
			foreach ($list as $val) {
				if (!in_array($val['admin_id'], $aid_arr)) {
					$aid_arr[] = $val['admin_id'];
				}
			}

			$alist 		= $Admin->where(['uid' => ['in', $aid_arr]])->getField('uid,adminname');
		}


		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('alist', $alist);
	
		$this->display();
	}
	
}