<?php
/**
 * 直播管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;
use Common\Controller\TencentController;

class LiveController extends AuthController
{

	// 直播一些状态信息
	public static $live_base = [
		'status' => [
			'1' => ['name' => '用户直播', 'sel' => 0],
			'2' => ['name' => '后台直播', 'sel' => 0],
			'3' => ['name' => '回放', 'sel' => 0],
			'4' => ['name' => '未开播', 'sel' => 0],
			'5' => ['name' => '禁播', 'sel' => 0],
		],
		'recommend' => [
			'1' => '已推荐',
			'0' => '未显示推荐',
		]
	];


    /**
	 * 直播间列表
	 */
	public function index()
	{
		// 搜索的数组
		$search     		= array_merge(['room_id' => '', 'u_str' => '', 'cat_id' => ''], self::$live_base);

		// 搜索的值
		$page   			= I('get.p', self::$page);
		$search['room_id'] 	= trim(I('get.room_id', ''));
		$search['u_str'] 	= trim(I('get.u_str', ''));
		$search['cat_id'] 	= trim(I('get.cat_id', ''));
		$status 			= trim(I('get.status'));


		$Im 	            = new \Common\Controller\ImController();
		// 模型
		$UserDetail 		= new \Common\Model\UserDetailModel();
		$UserAuthCode 		= new \Common\Model\UserAuthCodeModel();
		$LiveRoom 			= new \Common\Model\LiveRoomModel();
		$Page 				= new \Common\Model\PageModel();
		$LiveCat 			= new \Common\Model\LiveCatModel();


		// 条件数组
		$whe        	= [];
		$uid_arr 		= $ulist = $clist = [];

		// 搜索条件判断
		if ($search['room_id']) {
			$whe['room_id'] = $search['room_id'];
		}

		if ($search['u_str']) {
			$whe['user_id'] = $UserDetail->uIdOrDeer($search['u_str']);  // 用户ID搜索或者来鹿号搜索
		}

		if ($status) {
			$whe['is_status'] = $status;

			foreach ($search['status'] as $key => $val) {
				if ($status == $key) {
					$search['status'][$key]['sel'] = 1;
				}
			}
		}


		// 数据
		$count 			= $LiveRoom->where($whe)->count();
    	$show 			= $Page->show($count, self::$limit); 	// 分页显示输出
		$list 			= $LiveRoom->where($whe)->page($page, self::$limit)->order('room_id desc')->select();

		if ($list) {
			$live_domain= $Im::getLiveDomain();  // 推流信息
			$time       = date('Y-m-d H:i:s', ($Im::getLiveAgingTime() + $_SERVER['REQUEST_TIME']));

			foreach ($list as $key => $val) {
				if (!in_array($val['user_id'], $uid_arr)) {
					$uid_arr[] = $val['user_id'];
				}

				// 推流地址
                $room_info              = get_live_room_info($val['room_id']);
                $pull_user = $val['user_id'];
                if (isset($room_info['tripartite']) && $room_info['tripartite'] == 1) {
                    $pull_user = $val['user_id'].'_t';
                }
				$push          = get_push_pull_url($live_domain['push'][0], $Im->getImSdkAppid() .'_'. $pull_user, $Im::getLiveKey(), $time);
				if ($push && isset($push['push'])) {
					$list[$key]['push'] = $push['push'];
				}
			}

			$ulist 		= $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');
			$clist 		= $UserAuthCode->where(['user_id' => ['in', $uid_arr], 'is_used' => 'Y'])->getField('user_id,auth_code');
		}

		// 直播分类
		$cat_list       = $LiveCat->order('sort desc')->getField('cat_id,cat_name');

        $app_name = defined('APP_NAME')?APP_NAME:'';

        $this->assign('app_name', $app_name);
		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->assign('ulist', $ulist);
		$this->assign('clist', $clist);
		$this->assign('cat_list', $cat_list);
		$this->assign('search', $search);
	
		$this->display();
	}

	/**
	 * 修改显示推荐
	 */
	public function recommendMod()
	{
		$code = '0';

		$sw   = I('post.sw/d');
		$rid  = I('post.rid/d');

		if ($sw && $rid) {
			$LiveRoom 	= new \Common\Model\LiveRoomModel();
			$res		= $LiveRoom->where(['room_id' => $rid])->save(['is_recommend' => ($sw == 1 ? 1 : 0)]);
			if ($sw ==2) $LiveRoom->where(['room_id' => $rid])->save(['sort'=>0]);
			$code 		= ($res !== false) ? '1' : '0';
		}
    	
    	echo $code;
	}

	/**
	 * 房间 添加/编辑
	 */
	public function mod()
	{
		$rid   				= I('room_id/d', 0);
		$user_id  			= I('post.user_id/d');
		$room_name  		= trim(I('post.room_name'));
		$cat_id  			= I('post.cat_id/d');
		$cover_url  		= trim(I('post.cover_url'));
		$media_url  		= trim(I('post.media_url'));
		$is_recommend  		= I('post.is_recommend/d');
		$is_status  		= I('post.is_status/d');
		$g_arr  			= I('post.goods');
		$lan_people  		= I('post.lan_people');
		$lan_heat  			= I('post.lan_heat');

		// 上传路径缓存
		$short_url_arr  	= S('short_url_arr');

		$Im 	        	= new \Common\Controller\ImController();
		$User 				= new \Common\Model\UserModel();
		$UserDetail 		= new \Common\Model\UserDetailModel();
		$LiveRoom 			= new \Common\Model\LiveRoomModel();
		$LiveCat 			= new \Common\Model\LiveCatModel();
		$Short 				= new \Common\Model\ShortModel();
		$ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();
		

		if (IS_POST) {
			$date      		= date('Y-m-d H:i:s');

			if ($user_id && $room_name && $cover_url) {
				// 假直播需要填视频地址
				if (!$is_status || $is_status == 4 || ($is_status == 2 && $media_url)) {
					// 改用户是否有房间记录
					$room 	= $LiveRoom->where(['user_id' => $user_id])->find();

					$ins    = [
						'user_id'  		=> $user_id,
						'room_name'  	=> $room_name,
						'cover_url'  	=> $cover_url,
					];

					if ($cat_id) {
						$ins['cat_id']  		= $cat_id;
					}

					if ($is_recommend) {
						$ins['is_recommend']  	= $is_recommend;
					}

					if ($is_status) {
						$ins['is_status']  		= $is_status;
					}
					
					$result 	= false;
					$res_tag 	= false;
					$ad_data    = [						// 假直播数据
						'user_id' 	  => $user_id,
						'short_name'  => $room_name,
						'create_time' => $date,
						'cover_url'   => str_replace("amp;", "", $cover_url),
						'media_url'   => str_replace("amp;", "", $media_url),
						'is_recorded' => 2,
                        'lan_people'  => $lan_people,
                        'lan_heat'    => $lan_heat,
					];

					// 假直播地址不正确
					if ($is_status && $is_status == 2 && $media_url && isset($ad_data['media_url'])) {
						$mp4_str 	= substr($ad_data['media_url'], -4, 4);
						$m3u8_str 	= substr($ad_data['media_url'], -5, 5);

						if (!in_array($mp4_str, ['.mp4', '.MP4']) && !in_array($m3u8_str, ['.m3u8', '.M3U8'])) {
							$this->ajaxError('视频地址不正确，请填.mp4或者.m3u8结尾的地址！！！');
						}
					}


					// 讲解商品
					$fake_data = [];
					if ($g_arr) {
						foreach ($g_arr as $key => $val) {
							$temp 					= trim($val);

							if ($temp) {
								$good 				= explode('，', $temp);

								foreach ($good as $v) {
									$fake_data[] 	= ['goods_id' => $v, 'from' => (string)$key];
								} 
							}
						}
					}

					
					$res_tag  = 'not';

					$LiveRoom->startTrans();   // 启用事务 
                    try {
						// 添加、编辑
						if ($rid && $room && $room['room_id'] && $rid == $room['room_id']) {
							if (in_array($room['is_status'], [3,4]) || ($room['is_status'] == 2 && $is_status && $is_status == 4)) {
								$result 	= $LiveRoom->where(['room_id' => $rid])->save($ins);

								if ($is_status) {
									// 如果是假直播  添加/修改假直播记录
									if ($is_status == 2) {	
										$ad_data['room_id'] = $rid;
										$res = $Short->adDateSave($Im, $ad_data, $rid);

										// 假直播商品修改
										$ShortLiveGoods->fakeGoodsAdd($fake_data, $user_id);

										// 创建IM房间成功 事务提交
										if ($res) {
											$LiveRoom->commit(); 
											$res_tag = 'ok';
										} else {
											$LiveRoom->rollback();
											$res_tag = 'room_fail';
										}

									} elseif ($is_status == 4) {   // 未开播关闭假直播IM群
									    $Im::destroyLiveGroup($rid);
										// 事务提交
										$LiveRoom->commit(); 
										$res_tag = 'ok';
									}
								} else {
                                    $LiveRoom->commit();
                                    $res_tag = 'ok';
                                    
								}

							} else {
                                $res_tag 	= 'not_st';
								if ($lan_people || $lan_heat) {
                                    $ad_data['room_id'] = $rid;
                                    $ad_data['is_recorded'] = $is_status ? 2 : 1;
                                    $Short->adDateSave($Im, $ad_data, $rid,true);
                                    $res_tag = 'ok';
                                }
                                $LiveRoom->commit();
							}
						} else {
							if (!$room) {
								$ah 		= $User->checkAuthority('live', $user_id);  // 检查开播等级是否达到

								if ($ah['code'] == 0) {
									$result 	= $LiveRoom->add($ins);

									if ($is_status) {
										// 如果是假直播  添加/修改假直播记录
										if ($is_status == 2) {	
											$ad_data['room_id'] = $result;
											$res = $Short->adDateSave($Im, $ad_data, $result);

											// 假直播商品添加
											$ShortLiveGoods->fakeGoodsAdd($fake_data, $user_id);

											// 创建IM房间成功 事务提交
											if ($res) {
												$LiveRoom->commit(); 
												$res_tag = 'ok';
											} else {
												$LiveRoom->rollback();
												$res_tag = 'room_fail';
											}

										} else {
											// 事务提交
											$LiveRoom->commit(); 
											$res_tag = 'ok';
										}
									} else {
										// 事务提交
										$LiveRoom->commit(); 
										$res_tag = 'ok';
									}
								} else {
									// 事务提交
									$LiveRoom->commit(); 
									$res_tag = 'not_ah';
								}

							} else {
								$LiveRoom->commit(); 
								$res_tag 	= 'not';
							}
						}
					
					} catch(\Exception $e) {
						// 事务回滚
						$LiveRoom->rollback();
					}

					if ($res_tag == 'ok') {
						// 缓存路径文件删除
						if ($short_url_arr) {
							foreach ($short_url_arr as $v) {
								if ($v != $cover_url) {
									@unlink('.'. $v);
								}
							}
		
							S('short_url_arr', null);
						}
		
						$this->ajaxSuccess();

					} else {
						if ($res_tag == 'not') { 
							$this->ajaxError('该用户已有房间，不可这样操作');
						} elseif ($res_tag == 'not_st') {
							$this->ajaxError('该房间正在直播中或者禁播，操作失败');
						} elseif ($res_tag == 'room_fail') {
							$this->ajaxError('IM房间创建失败，请将房间改为未开播在重试');
						} elseif ($res_tag == 'not_ah') {
							$this->ajaxError('用户等级不足，不可以创建直播间');
						} else {
							$this->ajaxError('数据库操作错误');
						}
					}
				}
			}

			$this->ajaxError();

		} else {
			$room           	= $LiveRoom->where(['room_id' => $rid])->find();
			$room           	= $room ? $room : ['room_id' => 0, 'user_id' => '', 'cat_id' => '', 'room_name' => '', 'cover_url' => '', 'media_url' => '', 'is_recommend' => 1, 'is_status' => 4];
			$room['user_str']   = $room['user_id'] ? $room['user_id'] : '';

			// 封面显示
			$room['cover_show'] = '';  
			if ($rid) {
				if ($room['cover_url']) {
					$room['cover_show'] 	= is_url($room['cover_url']) ? $room['cover_url'] : WEB_URL . $room['cover_url'];
				} else {
					$avatar 				= $UserDetail->where(['user_id' => $room['user_id']])->getField('avatar');

					if ($avatar) {
						$room['cover_show'] = is_url($avatar) ? $avatar : WEB_URL . $avatar;
					}
				}
			}

			// 直播分类
			$cat_list       		= $LiveCat->order('sort desc')->getField('cat_id,cat_name');

			// 假直播链接
			if ($room['room_id']) {
				$room_media 		= $Short->where(['room_id' => $room['room_id'], 'is_recorded' => 2])->getField('media_url');
				$room['media_url'] 	= $room_media ? $room_media : '';
			}

			// 假直播商品
			$goods = ['tb' => '', 'jd' => '', 'pdd' => '', 'vip' => '', 'self' => ''];
			if ($room['user_id']) {
				$l_good = $ShortLiveGoods->getList(['user_id' => $room['user_id'], 'type' => 'fake'], 'goods_id,from');
				
				if ($l_good) {
					foreach ($l_good as $val) {
						if ($val['from'] == 'tb') {
							$goods['tb'] 	.= $goods['tb'] ? '，'. $val['goods_id'] : $val['goods_id']; 
						} elseif ($val['from'] == 'jd') {
							$goods['jd'] 	.= $goods['jd'] ? '，'. $val['goods_id'] : $val['goods_id']; 
						} elseif ($val['from'] == 'pdd') {
							$goods['pdd'] 	.= $goods['pdd'] ? '，'. $val['goods_id'] : $val['goods_id']; 
						} elseif ($val['from'] == 'vip') {
							$goods['vip'] 	.= $goods['vip'] ? '，'. $val['goods_id'] : $val['goods_id']; 
						} elseif ($val['from'] == 'self') {
							$goods['self'] 	.= $goods['self'] ? '，'. $val['goods_id'] : $val['goods_id']; 
						}
					}
				}
			}
            $is_recorded = 2;
			if ($room['is_status'] == 1) {
                $is_recorded = 1;
            }
            $ll = $Short->where(['room_id'=>$room['room_id'],'user_id'=>$room['user_id'],'short_name'=>$room['room_name'],'is_recorded'=>$is_recorded,'lan_people'=>['gt',0]])->field('lan_people,lan_heat')->find();
			if (!$ll) {
                $ll['lan_people'] = $ll['lan_heat'] = 0;
            }
			// 不可编辑状态移除
			$ass_room       = array_merge($room, self::$live_base, ['lan_people' => $ll['lan_people'], 'lan_heat' => $ll['lan_heat']], ['goods' => $goods]);
			unset($ass_room['status']['1']);
			unset($ass_room['status']['3']);
			unset($ass_room['status']['5']);

			$this->assign('room', $ass_room);
			$this->assign('cat_list', $cat_list);

			$this->display();
		}
	}

	/**
	 * 禁播、启用
	 */
	public function roomBan()
	{
		$code = '0';

		$rid  = I('post.rid/d');

		if ($rid) {
			$LiveRoom 	= new \Common\Model\LiveRoomModel();
			$LiveBan 	= new \Common\Model\LiveBanModel();
            $redModel = new \Common\Model\LiveRedModel();
            $User = new \Common\Model\UserModel();
			$whe        = ['room_id' => $rid];
			$room		= $LiveRoom->field('room_id,is_status,user_id')->where($whe)->find();

			if ($room) {
				if ($room['is_status'] == 5) {		// 启用
					$res        = $LiveRoom->where($whe)->save(['is_status' => 4]);
					$code 		= ($res !== false) ? '1' : '0';

				} else {							// 禁播
					$LiveRoom->startTrans();   // 启用事务
                	try {
						// 修改状态
						$LiveRoom->where($whe)->save(['is_status' => 5]);

						// 若该直播间存在红包没抢完情况则把红包返还给发包者
                        $red = $redModel->where($whe)->field('id,user_id,red_money,effective_type')->select();
                        if ($red) {
                            foreach ($red as $k => $v) {
                                $moneData = array_sum(json_decode($v['red_money'], true)) ?: 0;
                                if (!in_array($v['effective_type'], [3,4])) {
                                    $User->where(['uid' => $v['user_id']])->setInc('ll_balance', $moneData);
                                    $redModel->where(['id' => $v['id']])->save(['effective_type' => 4,'refund'=>$moneData,'start_time'=>date("Y-m-d H:i:s")]);
                                }

                            }
                        }


						// 禁播日志
						$LiveBan->add(['room_id' => $rid, 'admin_id' => $_SESSION['admin_id'], 'add_time' => date('Y-m-d H:i:s')]);
						
					    // 事务提交
						$LiveRoom->commit(); 

						// 发送IM 关闭直播间
						$Im 		= new \Common\Controller\ImController();
						$Im->sendGroupMsg($room['room_id'], 'room_blacklist', ['msg' => '直播间已被管理员禁播！', 'time' => 5]);

						$code 		= '1';

						//第三方流断开操作
                        $room_info              = get_live_room_info($room['room_id']);
						if ($room_info['tripartite'] == 1) {
                            $Tencent  = new TencentController();
                            $params = array(
                                "StreamName" => TENCENT_IM_SDKAPPID."_{$room['user_id']}_t",
                                "DomainName" => TENCENT_LIVE_PUSH_DOMAIN,
                                "AppName" => "live"
                            );
                            $Tencent->tripartite($params);
                        }

					} catch(\Exception $e) {

						// 事务回滚
						$LiveRoom->rollback();
					}
				}
			}	
		}
    	
    	echo $code;
	}

	/**
	 * 警告提示 列表
	 */
	public function warnList()
	{
		$rid       	= I('rid/d', 0);

		$LiveWarn 	= new \Common\Model\LiveWarnModel();

		// 搜索条件
		$whe        = [];

		if ($rid) {
			$whe['room_id'] = $rid;
		}

		$list    	= $LiveWarn->where($whe)->order('id desc')->select();

		$this->assign('list', $list);
		$this->assign('rid', $rid);

		$this->display();
	}

	/**
	 * 发送警告提醒
	 */
	public function warnSend()
	{
		if (IS_POST) {
			$rid   		= I('post.rid/d');
			$text   	= trim(I('post.text'));

			$LiveRoom 	= new \Common\Model\LiveRoomModel();
			$LiveWarn 	= new \Common\Model\LiveWarnModel();
			$Im 		= new \Common\Controller\ImController();

			if ($rid && $text) { 
				// 房间记录
				$room 	= $LiveRoom->field('user_id')->where(['room_id' => $rid])->find();

				if ($room) {
					$ins 	= ['room_id' => $rid, 'text' => $text, 'send_time' => date('Y-m-d H:i:s')];

					$result = $LiveWarn->add($ins);
					
					if ($result !== false) {
						// 发送警示语
						$Im->sendGroupMsg($rid, 'host_hint', ['user_id' => $room['user_id'], 'hint_msg' => $text, 'hint_time' => 30]);

						$this->ajaxSuccess();

					} else {
						$this->ajaxError('数据库操作错误');
					}
				} else {
					$this->ajaxError('房间不存在');
				}
			}

			$this->ajaxError();

		} else {

			$this->display();
		}
	}

	/**
	 * 获取直播间小程序码
	 */
	public function getAppletCode()
	{
		$rid  			= I('post.rid/d');

		$share_code     = S('share_code_arr');              // 缓存的小程序码数组

		if ($rid) {
			$LiveRoom 	= new \Common\Model\LiveRoomModel();
			$room		= $LiveRoom->field('room_id,is_status')->where(['room_id' => $rid])->find();

			if ($room) {
				// 加入假直播标识
				$is_fake   = $room['is_status'] == 2 ? 1 : 0;
				$path      = "live/live_room/live_room?room_id=$rid&is_fake=$is_fake";

				// 获取图片并保存 返回路径  微信小程序参数
				$param              = ['path'  => $path];
				$param['is_hyaline']= true;                         // 透明底
				$code_img           = get_applet_wxacode($param);
		
				if ($code_img) {
					$img_url  		= WEB_URL . $code_img;
		
					// 删除上次缓存的图片
					if ($share_code) {
						foreach ($share_code as $v) {
							@unlink('.'. $v);
						}
						$share_code = [];
					} else {
						$share_code = [];
					}
		
					// 路径缓存 下次删除图片
					$share_code[]   	= $code_img;
					S('share_code_arr', $share_code);

					$this->ajaxSuccess(['img_url' => $img_url]);
				}
			} else {
				$this->ajaxError('房间不存在');
			}
		}
    	
    	$this->ajaxError();
	}

    /**
     * 修改直播推荐 排序
     */
    public function catSort()
    {
        $code = '0';

        $sort  = I('post.sort/d');
        $cid  = I('post.cid/d');

        if (($sort || $sort == 0) && $cid) {
            $LiveRoom 	= new \Common\Model\LiveRoomModel();
            $res		= $LiveRoom->where(['room_id' => $cid])->save(['sort' => $sort]);
            $code 		= ($res !== false) ? '1' : '0';
        }

        echo $code;
    }

}