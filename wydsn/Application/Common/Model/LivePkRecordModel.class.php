<?php
/**
 * 直播pk记录管理类
 */

namespace Common\Model;

use Think\Model;

class LivePkRecordModel extends Model
{

    /**
     * 主播同意pk时初始化记录
     * @param $uid
     * @param $pkuid
     * @param $roomid
     * @param $pkroomid
     * @param $type
     * @return false|int|mixed|string
     */
    public function addPkLive($uid, $pkuid, $roomid, $pkroomid, $type)
    {
        $map = [
            'user_id' => $uid,
            'other_uid' => $pkuid,
            'room_id' => $roomid,
            'other_room' => $pkroomid,
            'is_status' => $type,
            'add_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+5minute'))
        ];
        return $this->add($map);
    }

    /**
     * 查询两主播收到礼物鹿角
     * @param $uid
     * @param $pkuid
     * @param $roomid
     * @param $pkroomid
     * @param $type
     * @return LivePkRecordModel|void
     */
    public function getPkList($uid, $pkuid, $roomid, $pkroomid, $type)
    {

        if ($type == 2) {
            $map                 = [
                '_complex'  => [
                    'is_status'      => array('in', [1, 6]),
                    'room_id'        => $roomid
                ],
                '_logic'    => 'or',
                '_string'   => " `other_room` = {$roomid} and `is_status` IN (1, 6)",
            ];
        } else {
            $map = [
                'user_id'   => $uid,
                'other_uid' => $pkuid,
                'is_status' => 1
            ];
        }


        $data = $this->where($map)->field('id,money,other_money,end_time')->find();

        // 若无鹿角记录则写入初始记录
        if (empty($data) && in_array($type, [1, 2, 3])) {
            if ($type == 2) {
                return;
            }
            if ($type == 3) {
                $type = 6;
            }
            $this->addPkLive($uid, $pkuid, $roomid, $pkroomid, $type);
        }
        if ($data && ($type == 2 || $data['end_time'] <= date('Y-m-d H:i:s'))) {
            $this->pkEnd($data['id']);
        }
        $map['is_status'] = $type;
        return $this->where($map)->field('id,money,other_money,other_room,room_id')->find();
    }

    /**
     * pk结束时改状态
     * @param $id
     * @return bool|int|string
     */
    public function pkEnd($id)
    {
        return $this->where(['id' => $id])->save(['is_status' => 2]);
    }

}
