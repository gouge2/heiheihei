<?php
/**
 * 直播房间分类管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class LiveCatController extends AuthController
{

	/**
	 * 直播分类列表
	 */
	public function index()
	{
		$LiveCat 	= new \Common\Model\LiveCatModel();

		$list       = $LiveCat->order('sort desc')->select();

		$this->assign('list', $list);
	
		$this->display();
	}

	/**
	 * 直播分类 添加/编辑
	 */
	public function mod()
	{
		$cid        = I('cat_id/d', 0);
		$cat_name   = trim(I('cat_name'));
		$sort       = I('sort/d');

		$LiveCat 	= new \Common\Model\LiveCatModel();

		// 提交
		if (IS_POST) {
			if ($cat_name) {
				$ins = ['cat_name' => $cat_name];

				if ($sort) {
					$ins['sort'] = $sort;
				}

				// 新增/编辑
				if ($cid) {
					$result = $LiveCat->where(['cat_id' => $cid])->save($ins);
				} else {
					$result = $LiveCat->add($ins);
				}

				if ($result !== false) {
					$this->ajaxSuccess();
				} else {
					$this->ajaxError('数据库操作错误！'); 
				}
			}

			$this->ajaxError(); 

		} else {
			$cat = $LiveCat->where(['cat_id' => $cid])->find();
			$cat = $cat ? $cat : ['cat_id' => 0, 'cat_name' => '', 'sort' => '', 'is_status' => 1];

			$this->assign('cat', array_merge($cat, ['status' => ['1' => ['name' => '显示'], '0' => ['name' => '隐藏']]]));

			$this->display();
		}
	}

	/**
	 * 直播分类删除
	 */
	public function catDel($id)
	{	
		$code = '0';

		if ($id) {
			$LiveCat 	= new \Common\Model\LiveCatModel();
			$res		= $LiveCat->where(['cat_id' => $id])->delete();
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 修改直播分类 显示、隐藏
	 */
	public function catShow()
	{
		$code = '0';

		$sw   = I('post.sw/d');
		$cid  = I('post.cid/d');

		if ($sw && $cid) {
			$LiveCat 	= new \Common\Model\LiveCatModel();
			$res		= $LiveCat->where(['cat_id' => $cid])->save(['is_status' => ($sw == 1 ? 1 : 0)]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 修改直播分类 排序
	 */
	public function catSort()
	{
		$code = '0';

		$sort  = I('post.sort/d');
		$cid  = I('post.cid/d');

		if (($sort || $sort == 0) && $cid) {
			$LiveCat 	= new \Common\Model\LiveCatModel();
			$res		= $LiveCat->where(['cat_id' => $cid])->save(['sort' => $sort]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}
	
}