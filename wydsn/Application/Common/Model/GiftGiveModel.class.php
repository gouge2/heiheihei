<?php
/**
 * 直播礼物打赏记录管理类
 */
namespace Common\Model;
use Think\Model;

class GiftGiveModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('gift_id','require','礼物标识不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('user_id','require','打赏用户标识不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
        array('money','require','花费的鹿角金额不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('gift_num','require','打赏数量不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );


    /**
     * 收入的礼物列表
     */
    public function getIncomeList($uid, $room_id, $limit, $page, $sort = ' add_time desc ')
    {
        $data = [];

        if ($uid) {
            // 查询直播间
            $LiveRoom               = new \Common\Model\LiveRoomModel(); 
            $l_one                  = $LiveRoom->field('room_id')->where(['user_id' => $uid, 'room_id' => $room_id])->find();

            if ($l_one) {
                // 最后一次直播场次
                $date               = date('Y-m-d H:i:s');
                $LiveSite           = new \Common\Model\LiveSiteModel();
                $s_whe              = ['room_id' => $l_one['room_id']];
                $s_one              = $LiveSite->field('site_id')->where($s_whe)->order('site_id desc')->find();

                if ($s_one) {
                    $data           = $this->getIncomeWithSiteId($s_one['site_id'], $limit, $page, $sort);
                }
            }
            
        }
        
        return $data;
    }

    /**
     * 通过场次id获取商品列表
     * @param $site_id
     * @param $limit
     * @param $page
     * @param string $sort
     * @return mixed
     */
    public function getIncomeWithSiteId($site_id, $limit, $page, $sort = ' add_time desc ') {
        $whe            = ['site_id' => $site_id, 'is_status' => 'succ'];
        $list           = $this->field('gift_id,user_id,gift_num,add_time')->where($whe)->page($page, $limit)->order($sort)->select();

        if ($list) {
            $uid_arr    = $gfid_arr = [];
            $ud_list    = $gf_list = [];

            $UserDetail = new \Common\Model\UserDetailModel();
            $Gift       = new \Common\Model\GiftModel();

            // 循环组装查询条件
            foreach ($list as $key => $val) {
                if (!in_array($val['user_id'], $uid_arr)) {
                    $uid_arr[]  = $val['user_id'];
                }

                if (!in_array($val['gift_id'], $gfid_arr)) {
                    $gfid_arr[] = $val['gift_id'];
                }
            }

            // 查询用户列表
            $ud_list = $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');

            // 查询礼物列表
            $gf_list = $Gift->where(['gift_id' => ['in',  $gfid_arr]])->getField('gift_id,gift_name,gift_cover');

            // 循环组装数据
            foreach ($list as $val) {
                $temp       = $val;

                // 用户信息
                $det        = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => null];
                if (isset($det['user_id'])) {
                    unset($det['user_id']);     // 删除多余的字段
                }

                // 判断头像是否为第三方应用头像
                if ($det['avatar'] && !is_url($det['avatar'])) {
                    $det['avatar'] = WEB_URL . $det['avatar'];
                }

                // 礼物信息
                $gift      = isset($gf_list[$temp['gift_id']]) ? $gf_list[$temp['gift_id']] : ['gift_name' => '', 'gift_cover' => ''];
                if (isset($gift['gift_id'])) {
                    unset($gift['gift_id']);     // 删除多余的字段
                }

                // 礼物路径加域名
                if ($gift['gift_cover']) {
                    $gift['gift_cover'] = WEB_URL . $gift['gift_cover'];
                }

                // 时间转时间戳
                $temp['add_time']           = strtotime($val['add_time']);

                $data[]                     = array_merge($temp, $det, $gift);
            }
        }

        return $data;
    }
    
    /**
     * 送出礼物处理
     */
    public function giftCallback($uid, $room_id, $data)
    {
        $result     = ['code' => 'fail', 'msg' => 0];
        $date       = date('Y-m-d H:i:s');

        $deer_ratio = GIFT_D_RATIO;                                   // 鹿角转来鹿币比例

        $User 	    = new \Common\Model\UserModel();
        $UserDetail = new \Common\Model\UserDetailModel();
        $LiveRoom 	= new \Common\Model\LiveRoomModel();
        $LiveSite 	= new \Common\Model\LiveSiteModel();
        $Gift 	    = new \Common\Model\GiftModel();

        $Im 	    = new \Common\Controller\ImController();

        // 房间是否存在
        $lr_one      = $LiveRoom->field('room_id,user_id')->where(['room_id' => $room_id])->find();
        $ls_one      = $LiveSite->where(['room_id' => $room_id])->where(['start_time' => ['elt', $date], 'end_time' => '0000-00-00 00:00:00'])->order(' site_id desc ')->getField('site_id');

        // 用户是否存在
        $u_one      = $User->field('uid,ll_balance')->where(['uid' => $uid])->find();
        $ud_one     = $UserDetail->field('nickname,avatar')->where(['user_id' => $uid])->find();

        // 礼物是否存在
        $g_one      = $Gift->field('gift_id,gift_name,gift_price,gift_cover,gift_url,gift_luxury')->where(['gift_id' => $data['gift_id']])->find();

        // 存在在处理
        if ($u_one && $g_one && $lr_one) {
            $money          = $data['gift_num'] * $g_one['gift_price'];     // 鹿角金额

            if ($u_one['ll_balance'] >= $money) {
                // IM返回结果
                $im_res         = [
                    'gift_id'       => $data['gift_id'], 
                    'gift_name'     => $g_one['gift_name'],
                    'gift_number'   => $data['gift_num'],
                    'gift_cover'    => ($g_one['gift_cover'] ? WEB_URL . $g_one['gift_cover'] : ''),
                    'gift_url'      => ($g_one['gift_url'] ? WEB_URL . $g_one['gift_url'] : ''),
                    'gift_luxury'   => $g_one['gift_luxury'],
                    'user_name'     => $ud_one['nickname'],
                    'user_id'       => $u_one['uid'],
                    'user_avatar'   => (!is_url($ud_one['avatar']) ? WEB_URL . $ud_one['avatar'] : $ud_one['avatar']),
                ];

                // 排行榜新增数据
                $ranking        = [
                    'user_id'       => $u_one['uid'],
                    'avatar'        => $im_res['user_avatar'],
                    'num'           => ($data['gift_num'] * $money),
                ];

                // 新增的数据
                $ins = [
                    'gift_id'       => $g_one['gift_id'],
                    'user_id'       => $u_one['uid'],
                    'host_id'       => $lr_one['user_id'],
                    'site_id'       => ($ls_one ? $ls_one : 0),
                    'gift_num'      => $data['gift_num'],
                    'money'         => $money,
                    'deer'          => ($money * $deer_ratio),
                    'is_status'     => 'succ',
                    'add_time'      => $date,
                ];

                $LivePkRecord = new \Common\Model\LivePkRecordModel();
                $livemap                 = [
                    '_complex'  => [
                        'is_status'     => 1,
                        'room_id'        => $room_id
                    ],
                    '_logic'    => 'or',
                    '_string'   => " `other_room` = {$room_id} and `is_status` = 1",
                ];
                $pklist = $LivePkRecord->where($livemap)->field('id,user_id,other_uid,room_id,other_room')->find();
                if ($pklist){
                    if ($pklist['user_id'] == $lr_one['user_id']) {
                        $pkmoney = 'money';
                    } else {
                        $pkmoney = 'other_money';
                    }
                    $where = ['id' => $pklist['id'], 'is_status' => 1, 'end_time' => array("GT", date("Y-m-d H:i:s"))];
                    $LivePkRecord->where($where)->setInc($pkmoney, $money);
                    $pkdata = $LivePkRecord->where($where)->field('money,other_money')->find();
                    if ($pkdata) {
                        $listdata = [
                            'nowRoom' => $pkdata['money'],
                            'nextRoom' => $pkdata['other_money'],
                        ];
                        $listdatas = [
                            'nowRoom' => $pkdata['other_money'],
                            'nextRoom' => $pkdata['money'],
                        ];

                        $Im->sendGroupMsg($pklist['room_id'],'live_pk_gift', $listdata);
                        $Im->sendGroupMsg($pklist['other_room'],'live_pk_gift', $listdatas);
                    }
                }

                $this->startTrans();   // 启用事务
                try {
                    // 用户鹿角减少
                    $User->where(['uid' => $uid])->setDec('ll_balance', $money);

                    // 主播来鹿币加
                    $User->where(['uid' => $lr_one['user_id']])->setInc('ll_deer', ($money * $deer_ratio));

                    // 记录打赏记录
                    $this->add($ins);

                    // 事务提交
                    $this->commit();

                    // 排行榜改变
                    $thr = live_room_ranking_list($lr_one['room_id'], 'add', $ranking);

                    $result['code']  = 'succ';
                    $result['msg']   = (int)($u_one['ll_balance'] - $money);

                    // IM群发送群消息
                    $Im->sendGroupMsg($lr_one['room_id'], 'give_gift', $im_res);
                    $Im->sendGroupMsg($lr_one['room_id'], 'room_ranking', $thr['three']);

                } catch(\Exception $e) {
                    // 事务回滚
                    $this->rollback();
                }

            // 余额不足
            } else {
                $result['code']  = 'money_not';
            }

        } else {
            if (!$g_one) {      // 礼物不存在
                $result['code']  = 'gift_not';
            }

            if (!$lr_one) {    // 房间不存在
                $result['code']  = 'live_room_not';
            }
        }
          
        return $result;
    }

    /**
     * 送出礼物主播分组显示
     */
    public function giveGroupList($type, $uid, $limit, $page, $sort = 'id desc')
    {
        $data = [];

        if ($uid) {
            $start      = ($page - 1) * $limit;

            // 礼物记录
            if ($type == 'gift') {
                $list   = $this->query("SELECT * from (SELECT `host_id` as `user_id`,SUM(money) as money,MAX(id) as id,MAX(add_time) as add_time,COUNT(id) as num FROM __GIFT_GIVE__ WHERE `user_id` = {$uid} AND `is_status` = 'succ' GROUP BY `host_id`) t ORDER BY {$sort} limit {$start},{$limit}");

            // 来鹿币记录
            } elseif ($type == 'deer') {
                $list   = $this->query("SELECT * from (SELECT `user_id`,SUM(deer) as money,MAX(id) as id,MAX(add_time) as add_time,COUNT(id) as num FROM __GIFT_GIVE__ WHERE `host_id` = {$uid} AND `is_status` = 'succ' GROUP BY `user_id`) t ORDER BY {$sort} limit {$start},{$limit}");
            }

            if ($list) {
                $hid_arr    = [];
                $ud_list    = [];

                $UserDetail = new \Common\Model\UserDetailModel();

                // 循环组装查询条件
                foreach ($list as $val) {
                    if (!in_array($val['host_id'], $hid_arr)) {
                        $hid_arr[]  = $val['user_id'];
                    }
                }

                // 查询主播/用户列表
                $ud_list    = $UserDetail->where(['user_id' => ['in', $hid_arr]])->getField('user_id,nickname,avatar');


                foreach ($list as $val) {
                    $temp               = $val;
                    unset($temp['id']);

                    // 用户信息
                    $det                = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => ''];
                    if (isset($det['user_id'])) {
                        unset($det['user_id']);     // 删除多余的字段
                    }

                    // 判断头像是否为第三方应用头像
                    if ($det['avatar'] && !is_url($det['avatar'])) {
                        $det['avatar']  = WEB_URL . $det['avatar'];
                    }

                    // 时间转时间戳
                    $temp['add_time']   = strtotime($val['add_time']);

                    $data[]             = array_merge($temp, $det);
                }
            }
        }

        return $data;
    }

    /**
     * 送出礼物明细
     */
    public function giveList($type, $at_id, $uid, $limit, $page, $sort = 'id desc')
    {
        $data                   = [];
        $field                  = 'gift_id,'. ($type == 'deer' ? 'deer as money' : 'money' ) .',gift_num,add_time';
        
        if ($at_id && $uid) {
            $whe                = $type == 'deer' ? ['user_id' => $uid, 'host_id' => $at_id] : ['user_id' => $at_id, 'host_id' => $uid];
            $whe['is_status']   = 'succ';

            $list               = $this->field($field)->where($whe)->page($page, $limit)->order($sort)->select();

            if ($list) {
                $gid_arr        = [];
                $gf_list        = [];

                $Gift           = new \Common\Model\GiftModel();

                // 循环组装查询条件
                foreach ($list as $val) {
                    if (!in_array($val['gift_id'], $gid_arr)) {
                        $gid_arr[]  = $val['gift_id'];
                    }
                }

                // 查询礼物列表
                $gf_list    =  $Gift->where(['gift_id' => ['in', $gid_arr]])->getField('gift_id,gift_name,gift_cover');


                foreach ($list as $val) {
                    $temp               = $val;

                    // 礼物信息
                    $gift               = isset($gf_list[$temp['gift_id']]) ? $gf_list[$temp['gift_id']] : ['gift_name' => '', 'gift_cover' => ''];

                    // 图片路径加域名
                    if ($gift['gift_cover'] && !is_url($gift['gift_cover'])) {
                        $gift['gift_cover'] = WEB_URL . $gift['gift_cover'];
                    }

                    // 类型转换
                    $temp['gift_num']   = (int)$val['gift_num'];

                    // 时间转时间戳
                    $temp['add_time']   = strtotime($val['add_time']);

                    $data[]             = array_merge($temp, $gift);
                }
            }
        }

        return $data;
    }
}
?>