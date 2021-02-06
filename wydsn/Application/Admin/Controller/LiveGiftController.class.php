<?php
/**
 * 直播礼物管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class LiveGiftController extends AuthController
{

	/**
	 * 直播礼物列表
	 */
	public function index()
	{
		$page   			= I('get.p', self::$page);

		$whe                = ['is_status' => 1];

		$Gift 				= new \Common\Model\GiftModel();
		$Page 				= new \Common\Model\PageModel();

		$count 				= $Gift->where($whe)->count();
    	$show 				= $Page->show($count, self::$limit); 	// 分页显示输出
		$list       		= $Gift->where($whe)->page($page, self::$limit)->order('sort desc')->select();

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['show_url'] = is_url($val['gift_url']) ? $val['gift_url'] : WEB_URL . $val['gift_url']; 
			}
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
	
		$this->display();
	}

	/**
	 * 直播礼物 添加/编辑
	 */
	public function mod()
	{
		$gid        	= I('gid/d', 0);
		$gift_name   	= trim(I('gift_name'));
		$gift_price   	= I('gift_price/d');
		$gift_cover   	= trim(I('gift_cover'));
		$gift_url   	= trim(I('gift_url'));
		$join_num       = I('join_num/a');
		$is_show       	= I('is_show/d');
		$sort       	= I('sort/d');

		// 上传路径缓存
		$short_url_arr  = S('short_url_arr');

		$Gift 			= new \Common\Model\GiftModel();

		// 提交
		if (IS_POST) {
			if ($gift_name && $gift_price && $gift_cover && $gift_url) {
				$ins = [
					'gift_name' 	=> $gift_name,
					'gift_price' 	=> $gift_price,
					'gift_cover' 	=> $gift_cover,
					'gift_url' 		=> $gift_url,
				];

				if ($sort || $sort == 0) {
					$ins['sort'] 	= $sort;
				}

				if ($is_show || $is_show == 0) {
					$ins['is_show'] = $is_show;
				}

				// 连送数量
				$join 				= '';
				if ($join_num) {
					foreach ($join_num as $val) {
						if ($val) {
							$join .= $join ? ','. $val : $val;
						}
					}
				}

				// 豪华礼物判断
				$svga_str 	= substr($gift_url, -5, 5);
				if ($svga_str == '.svga') {
					$join           	= '1';
					$ins['gift_luxury'] = 'Y';
				}


				$ins['gift_join']	= $join ? $join : '1';

				// 新增/编辑
				if ($gid) {
					$result = $Gift->where(['gift_id' => $gid])->save($ins);
				} else {
					$ins['add_time']= date('Y-m-d H:i:s');
					$result = $Gift->add($ins);
				}

				if ($result !== false) {
					// 缓存路径文件删除
					if ($short_url_arr) {
						foreach ($short_url_arr as $v) {
							if ($v != $gift_cover && $v != $gift_url) {
								@unlink('.'. $v);
							}
						}

						S('short_url_arr', null);
					}

					$this->ajaxSuccess();
				} else {
					$this->ajaxError('数据库操作错误！');
				}
			}

			$this->ajaxError(); 

		} else {

			$one 		= $Gift->where(['gift_id' => $gid])->find();
			$one 		= $one ? $one : ['gift_id' => 0, 'gift_name' => '', 'gift_cover' => '', 'gift_url' => '', 'sort' => 0, 'is_show' => 1];
			$join_num 	= [0,0,0,0];

			// 连送数量
			if (isset($one['gift_join']) && $one['gift_join']) {
				$arr    = explode(',', $one['gift_join']);
				foreach ($arr as $key => $val) {
					$join_num[$key] = (int)$val;
				}
			}

			// 地址处理
			$one['show_cover'] 	= $one['gift_cover'] ? (is_url($one['gift_cover']) ? $one['gift_cover'] : WEB_URL . $one['gift_cover']) : '';
			$one['show_url'] 	= $one['gift_url'] ? (is_url($one['gift_url']) ? $one['gift_url'] : WEB_URL . $one['gift_url']) : '';

			$g_one 				= array_merge($one, ['join_num' => $join_num], ['show' => ['1' => ['name' => '是'], '0' => ['name' => '否']]]);
			$this->assign('gift', $g_one);

			$this->display();
		}
	}

	/**
	 * 直播礼物删除
	 */
	public function giftDel($id)
	{	
		$code = '0';

		if ($id) {
			$Gift 		= new \Common\Model\GiftModel();
			$res		= $Gift->where(['gift_id' => $id])->save(['is_status' => 0]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 修改直播礼物 显示、隐藏
	 */
	public function giftShow()
	{
		$code = '0';

		$sw   = I('post.sw/d');
		$gid  = I('post.gid/d');

		if ($sw && $gid) {
			$Gift 		= new \Common\Model\GiftModel();
			$res		= $Gift->where(['gift_id' => $gid])->save(['is_show' => ($sw == 1 ? 1 : 0)]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 修改直播礼物 排序
	 */
	public function giftSort()
	{
		$code 	= '0';

		$sort  	= I('post.sort/d');
		$gid   	= I('post.gid/d');

		if (($sort || $sort == 0) && $gid) {
			$Gift 		= new \Common\Model\GiftModel();
			$res		= $Gift->where(['gift_id' => $gid])->save(['sort' => $sort]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 查看svga动图
	 */
	public function lookSvga()
	{
		$img_url 	= base64_decode(trim(I('img_url')));
		$url 		= str_replace("https://", "http://", $img_url);

		$this->assign('img_url', $url);
	
		$this->display();
	}
	
}