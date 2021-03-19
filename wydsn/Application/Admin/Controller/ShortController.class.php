<?php
/**
 * 视频管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class ShortController extends AuthController
{

	// 短视频一些状态信息
	public static $short_base = [
		'status' => [
			'1' => ['name' => '已发布', 'sel' => 0],
			'2' => ['name' => '未审核', 'sel' => 0],
			'3' => ['name' => '审核中', 'sel' => 0],
			'4' => ['name' => '禁止', 'sel' => 0],
		],
		'recommend' => [
			'1' => '已推荐',
			'0' => '未显示推荐',
		]
	];


    /**
	 * 视频列表
	 */
	public function index()
	{
		// 搜索的数组
		$search     	= array_merge(['id' => '', 'uid' => '', 'des' => ''], self::$short_base);

		// 搜索的值
		$page   		= I('get.p', self::$page);
		$search['id'] 	= trim(I('get.id', ''));
		$search['uid'] 	= trim(I('get.uid', ''));
		$search['des'] 	= trim(I('get.des', ''));
		$status 		= trim(I('get.status'));

		// 条件数组
		$whe        = ['is_status' => ['neq', 0], 'is_recorded' => 0];
		$uid_arr 	= $ulist = [];

		// 搜索条件判断
		if ($search['id']) {
			$whe['id'] = $search['id'];
		}

		if ($search['uid']) {
			$whe['user_id'] = $search['uid'];
		}

		if ($search['des']) {
			$whe['description'] = ['like', '%'. $search['des'] .'%'];
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
		$Short 			= new \Common\Model\ShortModel();
		$Page 			= new \Common\Model\PageModel();

		// 数据
		$count 			= $Short->where($whe)->count();
    	$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $Short->where($whe)->page($page, self::$limit)->order('id desc')->select();

		//清除沒有商品的視頻
//        $ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();
//        $goodslist = $ShortLiveGoods->field('short_id as id')->select();
//		$liveid = $Short->field('id')->select();
//		$diff = $this->fun($liveid, $goodslist);
//        foreach ($diff as $k=>$v) {
//            $Short->where(['id'=>$v['id']])->delete();
//        }
		if ($list) {
			foreach ($list as $key => $val) {
				if (!in_array($val['user_id'], $uid_arr)) {
					$uid_arr[] = $val['user_id'];
				}

				// 地址处理
				if ($val['media_url']) {
					$list[$key]['cover_show'] 	= is_url($val['cover_url']) ? $val['cover_url'] : WEB_URL . $val['cover_url'];

					// 非mp4格式 是抖音抓下来的
					$list[$key]['media_show'] 	= is_url($val['media_url']) ? $val['media_url'] : WEB_URL . $val['media_url'];
					$list[$key]['media_tag'] 	= (strpos($list[$key]['media_show'], '.mp4') !== false) ? 1 : 0;
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

    function fun($arr_1,$arr_2)
    {
        foreach ($arr_1 as $key => $val)
        {
            $bool = false;
            foreach ($arr_2 as $k => $v)
            {
                if($bool = ($val == $v))
                {
                    break;
                }
            }
            if(!$bool)
            {
                $diff[$key] = $val;
            }
        }
        return $diff;
    }

	/**
	 * 新增/编辑 视频
	 */
	public function mod()
	{
		$id     		= I('id/d', 0);
		$user_id    	= I('post.user_id/d');
		$cover_url  	= trim(I('post.cover_url'));
		$media_url  	= trim(I('post.media_url'));
		$short_name 	= trim(I('post.short_name'));
		$praise_num 	= I('post.praise_num/d');
		$comment_num 	= I('post.comment_num/d');
		$forward_num 	= I('post.forward_num/d');
		$create_time 	= trim(I('post.create_time'));
		$is_recommend 	= I('post.is_recommend/d');
		$is_status 		= I('post.is_status/d');
		$g_arr  		= I('post.goods');

		// 上传路径缓存
		$short_url_arr  = S('short_url_arr');

		$Short 			= new \Common\Model\ShortModel();
		$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();

		if (IS_POST) {
			if ($user_id && $cover_url && $media_url && $short_name) {
				// 必选参数
				$data = [
					'user_id'    => $user_id,
					'cover_url'  => str_replace("amp;", "", $cover_url),
					'media_url'  => str_replace("amp;", "", $media_url),
					'short_name' => $short_name,
				];

				// 可选参数
				if ($praise_num) {
					$data['praise_num'] 	= $praise_num;
				}

				if ($comment_num) {
					$data['comment_num'] 	= $comment_num;
				}

				if ($forward_num) {
					$data['forward_num'] 	= $forward_num;
				}

				if ($is_recommend || $is_recommend == 0) {
					$data['is_recommend'] 	= $is_recommend;
				}

				if ($is_status || $is_status == 0) {
					$data['is_status'] 		= $is_status;
				}

				// 绑定商品
				$good_data 					= [];
				if ($g_arr) {
					foreach ($g_arr as $key => $val) {
						$temp 				= trim($val);
						if ($temp) {
							$good_data 		= ['goods_id' => $temp, 'from' => (string)$key];
							break;
						}
					}
				}

				$data['create_time'] 		= $create_time ? $create_time : date('Y-m-d H:i:s');

				// 添加与编辑
				if ($id) {
					$data['update_time']    = date('Y-m-d H:i:s');
					$result 				= $Short->where(['id' => $id])->save($data);
					$ShortLiveGoods->shortGoodsAdd($good_data, $user_id, $id);				// 绑定的商品
				} else {
					$result 				= $Short->add($data);
					$ShortLiveGoods->shortGoodsAdd($good_data, $user_id, $result);			// 绑定的商品
				}

				if ($result !== false) {
					// 缓存路径文件删除
					if ($short_url_arr) {
						foreach ($short_url_arr as $v) {
							if ($v != $cover_url && $v != $media_url) {
								@unlink('.'. $v);
							}
						}

						S('short_url_arr', null);
					}

					$this->ajaxSuccess();

				} else {
					$this->ajaxError('数据库操作错误');
				}
			}

			$this->ajaxError();

		} else {
			$whe    					= ['id' => $id];

			$s_one  					= $Short->where($whe)->find();
			$s_one  					= $s_one ? $s_one : ['id' => 0, 'user_id' => '', 'cover_url' => '', 'media_url' => '', 'short_name' => '', 'praise_num' => 0, 'comment_num' => 0, 'forward_num' => 0, 'create_time' => '', 'is_recommend' => 1, 'is_status' => 1];

			// 地址处理
			$s_one['cover_show'] 		= is_url($s_one['cover_url']) ? $s_one['cover_url'] : WEB_URL . $s_one['cover_url'];
			$s_one['media_show'] 		= $s_one['media_url'] ? (is_url($s_one['media_url']) ? $s_one['media_url'] : WEB_URL . $s_one['media_url']) : '';
			$s_one['media_tag'] 		= (strpos($s_one['media_show'], '.mp4') !== false) ? 1 : 0;

			// 绑定商品
			$goods 						= ['tb' => '', 'jd' => '', 'pdd' => '', 'vip' => '', 'self' => ''];
			$sg_one                 	= $ShortLiveGoods->getOne(['short_id' => $id, 'type' => 'short'], 'goods_id,from');
			if ($sg_one) {
				$goods[$sg_one['from']] = $sg_one['goods_id'];
			}

			$this->assign('short', array_merge($s_one, self::$short_base, ['goods' => $goods]));

			$this->display();
		}
	}

	/**
	 * 视频封面与视频上传
	 */
	public function upload()
	{
		$type 			= trim(I('post.type'));
		$from 			= trim(I('post.from'));

		if ($type && in_array($type, ['img', 'mp4'])) {
			$root_str   		=  $from ? ($from == 'live' ? 'Room' : ($from == 'gift' ? 'Room/Live' : 'Short')) : 'Short';

			$config = [
				'mimes'         =>  [], //允许上传的文件MiMe类型
				'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
				'exts'          =>  ['jpg', 'gif', 'png', 'jpeg', 'mp4', 'svga'], //允许上传的文件后缀
				'subName'       =>  '', //子目录创建方式，为空
				'rootPath'      =>  './Public/Upload/'. $root_str .'/', //保存根路径
				'savePath'      =>  '', //保存路径
				'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
			];

			// 上传封面图与视频
			$upload = new \Think\Upload($config);
			$info 	= $upload->uploadOne($_FILES['file']);  // 上传单个文件

			if (!$info) {
				// 上传错误提示错误信息
				$this->ajaxError($upload->getError());
			} else {
				// 上传成功  文件完成路径
				$filepath 	= $config['rootPath'] . $info['savepath'] . $info['savename'];
				$img 		= substr($filepath, 1);

				// 保存路径到缓存
				$short_url_arr  = S('short_url_arr');
				$short_url_arr[]= $img;
				S('short_url_arr', $short_url_arr);

				$this->ajaxSuccess(['url' => $img, 'show_url' => WEB_URL . $img]);
			}
		}

		$this->ajaxError();
	}

	/**
	 * 抖音视频播放
	 */
	public function shortLook()
	{
		$id 		= I('tag/d');
		$str 		= '';

		if ($id) {
			$Short 		= new \Common\Model\ShortModel();
			$media_url  = $Short->where(['id' => $id])->getField('media_url');
			$str 		= $media_url ? url_gain_conver($media_url) : '';
		}

		$this->assign('str', $str);

		$this->display();
	}

	/**
	 * 视频删除
	 */
	public function del($id)
	{
		$code = '0';

		if ($id) {
			$Short 	= new \Common\Model\ShortModel();
            $res	= $Short->where(['id' => $id])->save(['is_status' => 0,'description'=>'管理员已删除此文件']);

			$code 	= ($res !== false) ? '1' : '0';
		}

    	echo $code;
	}

	/**
	 * 一键爬取抖音短视频
	 */
	public function crawling()
	{
		$number 	= I('post.number/d');
		$uid 		= I('post.user_id/d');

		if (IS_POST) {
			if ($number && $uid) {
				S('get_tiktok_video_number', $number);
				S('get_tiktok_video_uid', $uid);

				$this->ajaxSuccess();
			}

			$this->ajaxError();

		} else {
			$this->display();
		}
	}

	/**
	 * 获取某个用户的头像与
	 */
	public function getUserInfo()
	{
		$uid 				= trim(I('post.user_id'));
		$from 				= trim(I('post.from'));

		if ($uid) {
			$User     		= new \Common\Model\UserModel();
			$UserDetail     = new \Common\Model\UserDetailModel();
			$LiveRoom     	= new \Common\Model\LiveRoomModel();
			$UserAuthCode 	= new \Common\Model\UserAuthCodeModel();

			$user           = $UserDetail->getUserDetailMsg($uid);

			// 翠花号搜索
			if (!$user) {
				$user_id    = $UserAuthCode->where(['auth_code' => $uid, 'is_used' => 'Y'])->getField('user_id');
				if ($user_id) {
					$user   = $UserDetail->getUserDetailMsg($user_id);
				}
			}

			if ($user && isset($user['nickname']) && isset($user['avatar'])) {
				$res      			= [];
				$res['nickname']    = $user['nickname'];
				$res['avatar']    	= $user['avatar'] ? (is_url($user['avatar']) ? $user['avatar'] : WEB_URL . $user['avatar']) : '';

				// 直播查询房间是否存在
				if ($from && $from == 'live') {
					// 用户权限够不够开播
					$power_tag  			= false;
					$msg  					= $User->getUserMsg($user['user_id']);

					if ($msg) {
						$live_power 		= USER_LIVE_POWER ? explode(',', USER_LIVE_POWER) : [];
						$power_tag 			= in_array($msg['group_id'], $live_power) ? true : false;
					}

					// 是否有创建直播间
					$room                   = $LiveRoom->field('room_id')->where(['user_id' => $user['user_id']])->find();
					$room_tag   			= $room ? true : false;

					$res['room_msg']        = ($power_tag ? '' : '未达到主播等级，不可创建房间 --- ') . ($room_tag ? '已有房间不可创建房间' : '未有房间可创建房间');
					$res['user_id']   		= $user['user_id'];
				}
			}

			$this->ajaxSuccess($res);
		}

		$this->ajaxError();
	}

	/**
	 * 回放列表
	 */
	public function getPutList()
	{
		// 搜索的值
		$page   		= I('get.p', self::$page);
		$uid   			= I('user_id/d');

		// 搜索条件
		$whe            = ['is_status' => ['neq', 0], 'is_recorded' => 1];
		$sid_arr        = [];
		$slist        	= [];

		// 搜索值
		if ($uid) {
			$whe['user_id'] = $uid;
		}

		// 模型
		$Short 			= new \Common\Model\ShortModel();
		$LiveSite 		= new \Common\Model\LiveSiteModel();
		$Page 			= new \Common\Model\PageModel();

		// 数据
		$field          = 'id,user_id,site_id,short_name,cover_url,media_url';
		$count 			= $Short->where($whe)->count();
		$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $Short->field($field)->where($whe)->page($page, self::$limit)->order('create_time desc,id desc')->select();

		if ($list) {
			foreach ($list as $key => $val) {
				if (!in_array($val['site_id'], $sid_arr)) {
					$sid_arr[] = $val['site_id'];
				}

				if ($val['cover_url']) {
					$list[$key]['cover_url'] = is_url($val['cover_url']) ? $val['cover_url'] : WEB_URL . $val['cover_url'];
				}
			}

			$slist     	= $LiveSite->where(['site_id' => ['in', $sid_arr]])->getField('site_id,room_id,start_time,end_time');
		} else {
            $delmsg = $Short->where(['is_status'=>0,'user_id'=>$uid])->count() ? '该回放文件已被管理员删除' : '';
            $this->assign('delmsg', $delmsg);
        }


		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('slist', $slist);

		$this->display();
	}

	/**
	 * 直播回放查看
	 */
	public function putLook()
	{
		$id 		= I('tag/d');
		$str 		= '';

		if ($id) {
			$Short 		= new \Common\Model\ShortModel();
			$str  = $Short->where(['id' => $id])->getField('media_url');
            if (strpos($str,'https') == false){
                $str = str_replace('http','https', $str);
            }
		}

		$this->assign('str', $str);

		$this->display();
	}

    /**
     * 批量删除
     * @param $all_id
     */
    public function batchdel($all_id)
    {
        $all_id = substr($all_id, 0, -1);
        $Short 	= new \Common\Model\ShortModel();
        $ShortCommon = new \Common\Model\ShortCommentModel();
        $ShortCommonPraise = new \Common\Model\ShortCommentPraiseModel();
        $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
        $Userdetail = new \Common\Model\UserDetailModel();
        $UserPraise = new \Common\Model\UserPraiseModel();
        // 删除视频文件
        $Short->where("id in ($all_id)")->delete();

        $praise = $ShortCommon->where("short_id in ($all_id)")->field('id,user_id')->select();

        $num = count($praise);

        if (!empty($praise)) {
            foreach ($praise as $k => $v) {
                // 删除视频评论点赞
                $ShortCommonPraise->where(['id'=>$v['id']])->delete();
                $praise_short = $Userdetail->where(['user_id'=>$v['user_id']])->getField('praise_short');
                $praiseShort = $praise_short - $num;
                if ($praise_short < $num) {
                    $praiseShort = 0;
                }
                // 更新用户点赞数
                $Userdetail->where(['user_id'=>$v['user_id']])->save(['praise_short'=>$praiseShort]);
            }
        }
        // 删除视频评论
        $res = $ShortCommon->where("short_id in ($all_id)")->delete();
        // 删除视频点赞记录
        $UserPraise->where("short_id in ($all_id)")->delete();
        // 删除短视频关联商品
        $ShortLiveGoods->where("short_id in ($all_id) and type = 'short'")->delete();
        $code = ($res !== false) ? '1' : '0';
        echo $code;
    }

}
