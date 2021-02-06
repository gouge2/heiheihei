<?php
/**
 * IM通信回调-服务器通知
*/
namespace App\Controller;

use Think\Controller;

class ImNotifyController extends Controller
{
	/**
     * 服务器通知处理
     */
	public function dispose()
	{
		// 获取返回数据
		$param 		= [];
		$raw_xml 	= file_get_contents("php://input");
		
		// 数据还原
		if ($raw_xml) {
			//写日志
			// if (APP_DEBUG === true) {
				writeLog($raw_xml, 'test150');
			// }

			$param 		= json_decode($raw_xml, true);
		}

		
		$Im 			= new \Common\Controller\ImController();					// IM控制器
		$ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
		$LiveBullet 	= new \Common\Model\LiveBulletModel();
		$UserDetail 	= new \Common\Model\UserDetailModel();
        $live           = new LiveController();
        $LiveRoom       = new \Common\Model\LiveRoomModel();

		// 判断回调方法
		if ($param && isset($param['CallbackCommand'])) {

			//// 群消息发送之前回调
			if ($param['CallbackCommand'] == 'Group.CallbackBeforeSendMsg' && isset($param['MsgBody'])) {
				foreach ($param['MsgBody'] as $key => $val) {
					if (isset($val['MsgType']) && $val['MsgType'] == 'TIMCustomElem') {  	// 自定义消息 类型
						$mc_data 	= json_decode($val['MsgContent']['Data'], true);

						// 自定义消息体
						if ($mc_data && isset($mc_data['cmd']) && $mc_data['cmd'] == 'CustomCmdMsg') {

							if (isset($mc_data['data']['cmd'])) {
								$g_data 			= json_decode($mc_data['data']['msg'], true);

								//// 直播过程中点赞  
								if ($mc_data['data']['cmd'] == 'live_praise') {
									if ($g_data['praise_num'] > 0) {
										$im_res = get_live_room_info($param['GroupId'], 'praise', $g_data['praise_num']);   // 获取缓存数据
										
										// IM发送房间信息
										$Im->sendGroupMsg($param['GroupId'], 'room_info', $im_res);
									}

								////  主播是否开启允许连麦
								} elseif ($mc_data['data']['cmd'] == 'linkmic_open') {
									get_live_room_info($param['GroupId'], 'mic_open', $g_data['is_open']);  		// 设置缓存

								////  主播连麦的动作
								} elseif ($mc_data['data']['cmd'] == 'live_pk') {
                                    if ($g_data['action'] == 5) {
                                        $live->pkprogressbar($param['GroupId'], $g_data['user_id'], 2);
                                        $pkroom = $LiveRoom->where(['user_id' => $param['From_Account'],'is_status'=>1])->getField('room_id') ?: '';
                                        if ($pkroom) {
                                            get_live_room_info($pkroom, 'mic_pk', $g_data['action']);              // 设置缓存
                                        }
                                    } else {
                                        $live->pkprogressbar($param['GroupId'], $g_data['user_id'], 3);
                                    }
									get_live_room_info($param['GroupId'], 'mic_pk', $g_data['action']);  			// 设置缓存

								////  用户 禁言/取消禁言/踢出直播间
								} elseif ($mc_data['data']['cmd'] == 'handle_user') {
									live_room_handle_user($param['GroupId'], $g_data['type'], $g_data['user_id']);

								//// 其他处理
								} else {
									$ShortLiveGoods->callback($param['GroupId'], $param['From_Account'], $g_data, $mc_data['data']['cmd']);
								}
							}
						}

					// 弹幕内容保存
					} elseif (isset($val['MsgType']) && $val['MsgType'] == 'TIMTextElem') {
						$text = isset($val['MsgContent']['Text']) ? trim($val['MsgContent']['Text']) : '';
						$LiveBullet->callAdd($param['From_Account'], $param['GroupId'], $text);
					}
				}


			//// 新成员入群之后回调	
			} elseif ($param['CallbackCommand'] == 'Group.CallbackAfterNewMemberJoin') {
				// 入群成员列表
				$new_count	= count($param['NewMemberList']);

				if ($new_count) {
					// 发送入群消息
					$uid_arr   	= $u_arr = [];
					
					foreach ($param['NewMemberList'] as $val) {
						$uid_arr[] = $val['Member_Account'];
					}

					if ($uid_arr) {
						$u_arr     = $UserDetail->field('user_id,nickname')->where(['user_id' => ['in', $uid_arr]])->select();
					}

					// IM发送入群用户入群
					if ($u_arr) {
						$Im->sendGroupMsg($param['GroupId'], 'user_into', $u_arr);
					}


					$im_res = get_live_room_info($param['GroupId'], 'into', $new_count, $param['NewMemberList']); 	// 房间信息
					$thr 	= live_room_ranking_list($param['GroupId']);											// 房间排行榜

					// IM发送房间信息
					$Im->sendGroupMsg($param['GroupId'], 'room_info', $im_res);
					$Im->sendGroupMsg($param['GroupId'], 'room_ranking', $thr['three']);
				}


			//// 群成员离开群之后回调	
			} elseif ($param['CallbackCommand'] == 'Group.CallbackAfterMemberExit') {
				// 群成员离开列表
				$exit_count = count($param['ExitMemberList']);

				if ($exit_count) {
					$im_res = get_live_room_info($param['GroupId'], 'leave', $exit_count, $param['ExitMemberList']);   // 获取缓存数据

					// IM发送房间信息
					$Im->sendGroupMsg($param['GroupId'], 'room_info', $im_res);
				}

			} elseif ($param['CallbackCommand'] == 'C2C.CallbackAfterSendMsg' && isset($param['MsgBody'])) {
                foreach ($param['MsgBody'] as $key => $val) {
                    if (isset($val['MsgType']) && $val['MsgType'] == 'TIMCustomElem') {  	// 自定义消息 类型
                        $mc_data 	= json_decode($val['MsgContent']['Data'], true);
                        // 自定义消息体
                        if ($mc_data && isset($mc_data['cmd']) && $mc_data['cmd'] == 'CustomCmdMsg') {

                            if (isset($mc_data['data']['cmd'])) {
                                $g_data = json_decode($mc_data['data']['msg'], true);
                                if ($mc_data['data']['cmd'] == 'live_pk') {
                                    if ($g_data['action'] == 2) {
                                        $live->pkprogressbar($param['From_Account'], $param['To_Account'], 1);
                                        $pkRoomList = $LiveRoom->where(['user_id'=>$param['From_Account'], 'is_status'=>1])->field('room_id,other_room')->find();
                                    }
                                }
                            }
                        }
                    }
                }
            }
		}
	}

}
?>