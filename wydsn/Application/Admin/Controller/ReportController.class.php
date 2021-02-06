<?php
/**
 * 短视频/房间举报管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class ReportController extends AuthController
{
	// 举报一些状态信息
	public static $report_base = [
		'status' => [
			'1' => ['name' => '未处理', 'sel' => 0],
			'2' => ['name' => '已处理', 'sel' => 0],
		]
	];

	/**
	 * 视频举报列表
	 */
	public function short()
	{
		// 搜索的数组
		$search     	= self::$report_base;

		// 搜索的值
		$page   		= I('get.p', self::$page);
		$status 		= trim(I('get.status'));
		
		// 条件数组
		$whe 			= ['is_type' => 1];
		$uid_arr 		= $ulist = [];

		if ($status) {
			$whe['is_status'] = $status;

			foreach ($search['status'] as $key => $val) {
				if ($status == $key) {
					$search['status'][$key]['sel'] = 1;
				}
			}
		}

		// 模型
		$UserDetail 	= new \Common\Model\UserDetailModel();
		$Report 		= new \Common\Model\ReportModel();
		$ReportCat 		= new \Common\Model\ReportCatModel();
		$Page 			= new \Common\Model\PageModel();

		// 数据
		$count 			= $Report->where($whe)->count();
    	$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $Report->where($whe)->page($page, self::$limit)->order('id desc')->select();
		$cat       		= $ReportCat->getAllList();

		if ($list) {
			foreach ($list as $key => $val) {
				if (!in_array($val['user_id'], $uid_arr)) {
					$uid_arr[] = $val['user_id'];
				}

				if (!in_array($val['by_id'], $uid_arr)) {
					$uid_arr[] = $val['by_id'];
				}

				// 相册处理
				if ($val['photo']) {
					$list[$key]['photo'] = json_decode($val['photo'], true);
				}
			}

			$ulist 		= $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');
		}

		// 处理投诉分类
		$cat_list 		= [];
		if ($cat) {
			foreach ($cat as $v) {
				$cat_list[$v['id']] = $v;
			}
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('ulist', $ulist);
		$this->assign('cat_list', $cat_list);
		$this->assign('search', $search);
	
		$this->display();
	}

	/**
	 * 举报修改
	 */
	public function reportMod($id)
	{
		$code = '0';

		if ($id) {
			$Report 	= new \Common\Model\ReportModel();
			$res		= $Report->where(['id' => $id])->save(['is_status' => 2]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 举报删除
	 */
	public function reportDel($id)
	{	
		$code = '0';

		if ($id) {
			$Report 	= new \Common\Model\ReportModel();
			$photo      = $Report->where(['id' => $id])->getField('photo');

			// 删除相关的图片
			if ($photo) {
				$list  	= json_decode($photo, true);

				foreach ($list as $val) {
					@unlink('.'. $val);
				}
			}

			$res		= $Report->where(['id' => $id])->delete();
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

}