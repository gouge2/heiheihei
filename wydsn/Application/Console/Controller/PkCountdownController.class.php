<?php

namespace Console\Controller;

use Common\Controller\ImController;
use Common\Model\LivePkRecordModel;
use Think\Controller;

class PkCountdownController extends Controller
{

    public function index()
    {
        $LivePkRecord = new LivePkRecordModel();
        $Im = new ImController();
        // 查询pk或者惩罚中的信息
        $where['is_status'] = array('in', [1, 3]);
        $pkList = $LivePkRecord->where($where)->field('id,money,other_money,end_time,room_id,other_room')->select();

        foreach ($pkList as $k => $v) {
            // 查看房间状态
            $room_info = get_live_room_info($v['room_id']);

            //达到或者超过pk设定时间且还在pk状态的房间得出pk结果
            if (intval(strtotime($v['end_time']) - strtotime(date('Y-m-d H:i:s'))) <= 0 && in_array($room_info['is_action'], [6, 7])) {

                // pk结果
                if ($room_info['is_action'] == 6) {
                    $LivePkRecord->where(['id' => $v['id']])->save(['is_status' => 2]);
                    $result = $v['money'] - $v['other_money'];
                    if ($result > 0) {
                        $result = 1;    //当前主播赢
                    } elseif ($result < 0) {
                        $result = 2;    //对方主播赢
                    } else {
                        $result = 3;    //平局
                        $LivePkRecord->where(['id' => $v['id']])->save(['is_status' => 4]);
                    }

                    // pk惩罚
                    if ($result != 3) {
                        get_live_room_info($v['room_id'], 'mic_pk', 7);
                        get_live_room_info($v['other_room'], 'mic_pk', 7);
                        $LivePkRecord->where(['id' => $v['id']])->save(['is_status' => 3, 'end_time' => date('Y-m-d H:i:s', strtotime('+5minute'))]);
                    }

                    // 结果信息整理
                    $listData = [
                        'nowRoom' => $v['money'],
                        'nextRoom' => $v['other_money'],
                        'result' => $result
                    ];

                    // 给pk房间发送pk结果消息
                    $Im->sendGroupMsg($v['room_id'], 'live_pk_result', $listData);
                    $Im->sendGroupMsg($v['other_room'], 'live_pk_result', $listData);
                } else {
                    // pk惩罚结束
                    if (intval(strtotime($v['end_time']) - strtotime(date('Y-m-d H:i:s'))) <= 0) {
                        get_live_room_info($v['room_id'], 'mic_pk', 8);
                        get_live_room_info($v['other_room'], 'mic_pk', 8);
                        $LivePkRecord->where(['id' => $v['id']])->save(['is_status' => 5]);
                        // 结果信息整理
                        $listData = [
                            'nowRoom' => $v['money'],
                            'nextRoom' => $v['other_money'],
                            'result' => 4
                        ];
                        $Im->sendGroupMsg($v['room_id'], 'live_pk_start', $listData);
                        $Im->sendGroupMsg($v['other_room'], 'live_pk_start', $listData);
                    }
                }
            }
        }
    }

}