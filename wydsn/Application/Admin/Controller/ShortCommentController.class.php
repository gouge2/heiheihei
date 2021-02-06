<?php
/**
 * 视频评论管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class ShortCommentController extends AuthController
{

	// 评论记录一些状态信息
	public static $comm_base = [
		'status' => [
			'1' => ['name' => '已发布', 'sel' => 0],
			'2' => ['name' => '未审核', 'sel' => 0],
			'3' => ['name' => '审核中', 'sel' => 0],
		]
	];


    /**
	 * 视频评论列表
	 */
	public function index()
	{
		// 搜索的数组
		$search     		= array_merge(['short_id' => '', 'uid' => '', 'text' => '', 'pid' => ''], self::$comm_base);

		// 搜索的值
		$page   			= I('get.p', self::$page);
		$search['short_id'] = trim(I('get.short_id', ''));
		$search['uid'] 		= trim(I('get.uid', ''));
		$search['text'] 	= trim(I('get.text', ''));
		$search['pid'] 		= I('get.pid', '');
		$status 			= trim(I('get.status'));

		// 条件数组
		$whe        		= ['level' => 1];
		$uid_arr 			= $ulist = [];


		// 搜索条件判断
		if ($search['short_id']) {
			$whe['short_id'] 	= $search['short_id'];
		}

		if ($search['uid']) {
			$whe['user_id'] 	= $search['uid'];
		}

		if ($search['des']) {
			$whe['text'] 		= ['like', '%'. $search['text'] .'%'];
		}

		// 查看回复  找二级评论的
		if ($search['pid']) {
			$whe['_string'] = "parent_id = '{$search['pid']}' OR root_id = '{$search['pid']}'";
			$whe['level']   = 2;
		}

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
		$ShortComment 	= new \Common\Model\ShortCommentModel();
		$Page 			= new \Common\Model\PageModel();

		// 数据
		$count 			= $ShortComment->where($whe)->count();
    	$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $ShortComment->where($whe)->page($page, self::$limit)->order('id desc')->select();
		
		if ($list) {
			foreach ($list as $key => $val) {
				if (!in_array($val['user_id'], $uid_arr)) {
					$uid_arr[] = $val['user_id'];
				}
			}

			$ulist 		= $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname');
		}


		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('ulist', $ulist);
		$this->assign('search', $search);
	
		$this->display();
	}

	/**
	 * 新增/编辑 视频评论
	 */
	public function mod()
	{
		$id     		= I('id/d', 0);
		$user_id    	= I('post.user_id/d');
		$short_id    	= I('post.short_id/d');
		$text 			= trim(I('post.text'));
		$praise_num 	= I('post.praise_num/d');
		$reply_num 		= I('post.reply_num/d');
		$is_status 		= I('post.is_status/d');

		$Short 			= new \Common\Model\ShortModel();
		$ShortComment 	= new \Common\Model\ShortCommentModel();

		if (IS_POST) {
			if ($user_id && $short_id && $text) {
				// 必选参数
				$data = [
					'user_id'    	=> $user_id,
					'short_id'   	=> $short_id,
					'text'  		=> $text,
				];

				// 可选参数
				if ($praise_num) {
					$data['praise_num'] 	= $praise_num;
				}

				if ($reply_num) {
					$data['reply_num'] 		= $reply_num;
				}

				if ($is_status) {
					$data['is_status'] 		= $is_status;
				}

				$ShortComment->startTrans();   // 启用事务 
				try {
					// 添加与编辑
					if ($id) {
						$ShortComment->where(['id' => $id])->save($data);
					} else {
						$data['add_time'] 	= date('Y-m-d H:i:s');
						$new_id 			= $ShortComment->add($data); 

						// 短视频评论数加
						$Short->where(['id' => $data['short_id']])->setInc('comment_num');
					}
				
					// 事务提交
					$ShortComment->commit();

					$this->ajaxSuccess();

				} catch(\Exception $e) {
					// 事务回滚
					$ShortComment->rollback();

					$this->ajaxError('数据库操作错误');
				}
			}

			$this->ajaxError();

		} else {
			$whe    = ['id' => $id];

			$sc_one  = $ShortComment->where($whe)->find();
			$sc_one  = $sc_one ? $sc_one : ['id' => 0, 'user_id' => '', 'short_id' => '', 'text' => '', 'praise_num' => 0, 'reply_num' => 0, 'is_status' => 1];
	
			$sc_one  = array_merge($sc_one, self::$comm_base);
			$this->assign('comm', $sc_one);

			$this->display();
		}
	}

	/**
	 * 视频评论删除
	 */
	public function del($id)
	{
		$code = '0';

		if ($id) {
			$Short 			= new \Common\Model\ShortModel();
			$ShortComment 	= new \Common\Model\ShortCommentModel();
			$one			= $ShortComment->where(['id' => $id])->find();

			// 删除相关的操作
			if ($one) {
				$ShortComment->startTrans();   // 启用事务 
				try {
					// 短视频评论数减
					$Short->where(['id' => $one['short_id']])->setDec('comment_num');

					// 评论记录删除
					$ShortComment->where(['id' => $one['id']])->delete();

					// 父评论和根评论回复数减
					if ($one['parent_id']) {
						$ShortComment->where(['id' => $one['parent_id']])->setDec('reply_num');

						if ($one['root_id'] && $one['root_id'] != $one['parent_id']) {
							$ShortComment->where(['id' => $one['root_id']])->setDec('reply_num');
						}
					}

					// 事务提交
					$ShortComment->commit();

					$code 	= '1';

				} catch(\Exception $e) {
					// 事务回滚
					$ShortComment->rollback();
				}
			}
		}
    	
    	echo $code;
	}


	/**
	 * 查询视频记录是否存在
	 */
	public function isShort($id)
	{
		$id 				= I('post.id/d');

		if ($id) {
			$Short     		= new \Common\Model\ShortModel();
			$one           = $Short->where(['id' => $id])->find();

			if ($one && isset($one['short_name']) && isset($one['user_id'])) {
				$res['short_name']  = "（用户uid：{$one['user_id']}） 内容：". $one['short_name'];
				$this->ajaxSuccess($res);
			}
		}

		$this->ajaxError();
	}

}