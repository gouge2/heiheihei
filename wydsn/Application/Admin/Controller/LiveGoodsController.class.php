<?php
/**
 * 直播商品管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class LiveGoodsController extends AuthController
{
	// 直播商品一些状态信息
	public static $lg_base = [
		'from_arr' 		=> [
			'tb' 	=> '淘宝',
			'jd' 	=> '京东',
			'pdd' 	=> '拼多多',
			'vip' 	=> '唯品会',
			'self' 	=> '自营',
		],
		'explain_arr'	=> [
			'not'	=> '未讲解',
			'load'	=> '正在讲解',
			'yet'	=> '已讲解',
		]
	];

	/**
	 * 假直播或短视频商品列表
	 */
	public function fsList()
	{
		$uid   				= I('uid/d', 0);
		$r_state   			= I('r_state/', 0);				// 房间状态
		$sid   				= I('sid/d', 0);				// 短视频进来标识
		$page   			= I('get.p', self::$page);

		// 搜索条件
		$whe            	= ['is_status' => 1, 'is_lose' => 1];
		if ($sid) {
			$whe['type']    = $slg_type = 'short';
			$whe['short_id']= $sid;
			$r_state        = 4;

		} else {
			$whe['user_id'] = $uid;
			$whe['type']    = 'fake';
			$slg_type       = 'ad_fake';
		}
			

		$ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();
		$Page 				= new \Common\Model\PageModel();

		// 数据
		$count 				= $ShortLiveGoods->where($whe)->count();
    	$show 				= $Page->show($count, self::$limit); 	// 分页显示输出
		$list       		= $ShortLiveGoods->where($whe)->page($page, self::$limit)->order('sort desc,is_explain asc,id asc')->select();
		$goods				= $ShortLiveGoods->getGoodsData($slg_type, $whe, 0, 0, 0);

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['from_cn']		= self::$lg_base['from_arr'][$val['from']];				// 商品来源	
				$list[$key]['explain_cn']	= self::$lg_base['explain_arr'][$val['is_explain']];	// 讲解状态

				// 商品信息
				if ($sid) {
					$list[$key]['goods']		= isset($goods[$val['short_id']]) ? $goods[$val['short_id']] : [];
				} else {
					$list[$key]['goods']		= isset($goods[$val['id']]) ? $goods[$val['id']] : [];	
				}
			}
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('uid', $uid);
		$this->assign('sid', $sid);
		$this->assign('r_state', $r_state);
	
		$this->display();
	}

	/**
	 * 假直播或短视频商品 添加
	 */
	public function fsAdd()
	{
		$uid   				= I('uid/d', 0);
		$sid   				= I('sid/d', 0);

		if (IS_POST) {
			$g_arr  		= I('post.goods');

			$Im 	        = new \Common\Controller\ImController();
			$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
			$LiveRoom 		= new \Common\Model\LiveRoomModel();

			if ($g_arr && $uid) {
				// 讲解商品
				$good_data = [];

				foreach ($g_arr as $key => $val) {
					$temp 					= trim($val);
					if ($temp) {
						// 短视频
						if ($sid) {
							$good_data 		= ['goods_id' => $temp, 'from' => (string)$key];
							break; 
							
						// 假直播
						} else {
							$good 			= explode('，', $temp);
							foreach ($good as $v) {
								$good_data[]= ['goods_id' => $v, 'from' => (string)$key];
							} 
						}
					}
				}

				if ($sid) {
					$ShortLiveGoods->shortGoodsAdd($good_data, $uid, $sid);
				} else {
					$id_arr 		= $ShortLiveGoods->fakeGoodsAdd($good_data, $uid, false);

					if ($id_arr) {
						$room_id    = $LiveRoom->where(['user_id' => $uid])->getField('room_id');
						$im_data 	= $ShortLiveGoods->getGoodsData('the', ['id' => ['in', $id_arr]], 0, 0, 0);

						// IM发送新增商品
						if ($room_id && $im_data) {
							$Im->sendGroupMsg($room_id, 'add_goods', ['goods' => $im_data]);
						}
					}
				}

				$this->ajaxSuccess();
			}

			$this->ajaxError();

		} else {

			$this->assign('uid', $uid);
			$this->assign('sid', $sid);
	
			$this->display();
		}
	}

	/**
	 * 假直播商品 修改讲解状态
	 */
	public function fakeExplainMod()
	{	
		$code = '0';

		$id  	= I('post.id/d');
		$uid  	= I('post.uid/d');
		$type  	= I('post.type/d');

		if ($id && $uid && in_array($type, [1,2])) {
			$Im 	        = new \Common\Controller\ImController();
			$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
			$LiveRoom 		= new \Common\Model\LiveRoomModel();

			$room_id        = $LiveRoom->where(['user_id' => $uid])->getField('room_id');
			$slg_one        = $ShortLiveGoods->getOne(['id' => $id]);

			// 结束讲解
			if ($type == 2) {
				$explain 	= 'yet';

			// 开始讲解	
			} elseif ($type == 1) {
				// 讲解中商品  结束讲解
				$ShortLiveGoods->where(['user_id' => $uid, 'type' => 'fake', 'is_explain' => 'load'])
				->where(['is_status' => 1, 'is_lose' => 1])
				->save(['is_explain' => 'yet']);

				$explain 	= 'load';
			}

			// 修改
			$res 			= $ShortLiveGoods->where(['id' => $id])->save(['is_explain' => $explain]);

			// 该商品所在列表顺序
			$index          = 0;
			$f_whe          = ['user_id' => $uid, 'type' => 'fake'];
			$slg_list       = $ShortLiveGoods->getVal($f_whe, 'id', true);
			if ($slg_list) {
				for ($i = 0; $i <= count($slg_list); $i++) {
					if ($slg_list[$i] == $id) {
						$index = $i;
						break;
					}
				}
			}	

			// IM发送讲解状态
			$im_data = [
				'type' 	=> ($type == 1 ? 'start' : 'end'),
				'goods' => [
					'goods_id' => $slg_one['goods_id'],
					'index'    => $index,
					'from' 	   => $slg_one['from'],
				]
			];
			$Im->sendGroupMsg($room_id, 'live_goods', $im_data);


			$code 			= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 直播商品删除
	 */
	public function slgDel($id)
	{	
		$code = '0';

		if ($id) {
			$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
			$LiveRoom 		= new \Common\Model\LiveRoomModel();

			$slg_one        = $ShortLiveGoods->getOne(['id' => $id], 'type,user_id');
			$del_tag        = false;

			if ($slg_one) {
				if ($slg_one['type'] == 'fake') {
					$r_state      	= $LiveRoom->where(['user_id' => $slg_one['user_id'], 'is_lose' => 1])->getField('is_status');

					if ($r_state) {
						if (in_array($r_state, [3, 4])) {
							$del_tag= true;
						} elseif (in_array($r_state, [1, 2])) {
							$code 	= '-1';
						}
					}
				} elseif ($slg_one['type'] == 'short') {
					$del_tag 		= true;
				}
			}

			// 删除
			if ($del_tag) {
				$res	= $ShortLiveGoods->where(['id' => $id])->save(['is_status' => 0]);
				$code 	= ($res !== false) ? '1' : '0';
			}
		}
    	
    	echo $code;
	}

	/**
	 * 修改直播商品 排序
	 */
	public function slgSort()
	{
		$code 	= '0';

		$sort  	= I('post.sort/d');
		$cid  	= I('post.cid/d');

		if (($sort || $sort == 0) && $cid) {
			$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
			$LiveRoom 		= new \Common\Model\LiveRoomModel();

			$whe            = ['id' => $cid];
			$mod_tag        = false;

			$uid            = $ShortLiveGoods->getVal($whe, 'user_id');
			if ($uid) {
				$r_state    = $LiveRoom->where(['user_id' => $uid ])->getField('is_status');

				if (in_array($r_state, [3,4])) {
					$mod_tag= true;
				} else {
					$code   = -1;
				}
			}

			if ($mod_tag) {
				$res		= $ShortLiveGoods->where($whe)->save(['sort' => $sort]);
				$code 		= ($res !== false) ? '1' : '0';
			}
		}
    	
    	echo $code;
	}
	
}