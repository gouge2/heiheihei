<?php
/**
 * by 翠花 http://livedd.com
 * 红包版块管理接口
 */

namespace App\Controller;

use App\Common\Controller\AuthController;

class LiveRedController extends AuthController
{
    /**
     * 创建红包
     */
    public function createRed()
    {
        $redModel = new \Common\Model\LiveRedModel();
        $LiveRoom = new \Common\Model\LiveRoomModel();
        $token = trim(I('post.token'));
        $type = trim(I('post.type'));
        $total_amount = trim(I('post.total_amount'));
        $blessing = trim(I('post.blessing')) ?: '恭喜发财，大吉大利';
        $room_id = trim(I('post.room_id'));
        $is_status = trim(I('post.is_status'));
        $total = trim(I('post.total'));
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $uid = $User->getUserId($token);
        // 查询是否存在正常直播间
        $where['is_status'] = array('in', [1, 2]);
        $where['room_id'] = $room_id;
        $room = $LiveRoom->where($where)->find();
        if ($total_amount < $total) $this->ajaxError(11, '发送金额不得小于红包总数');
        
        if (empty($room)) $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);

        // 查询余额是否充足
        $balance = $User->where(['uid' => $uid])->getField('ll_balance');
        if ($balance < $total_amount) {
            $gift_money_cn   = defined('GIFT_MONEY_CN') ? GIFT_MONEY_CN : '';
            $this->ajaxError(4, $gift_money_cn.'余额不足，请充值后再来发红包');
        }

        $redmoney = json_encode(getRedGift($total_amount, $total, $type));

        // 准备红包数据
        $map = [
            'user_id' => $uid,
            'type' => $type,
            'total_amount' => $total_amount,
            'total' => $total,
            'blessing' => $blessing,
            'room_id' => $room_id,
            'is_status' => $is_status,
            'red_money' => $redmoney,
            'add_time' => date('Y-m-d H:i:s'),
            'start_time' => date("Y-m-d H:i:s", strtotime("+1 day"))
        ];
        if ($type == 2) $map['total_amount'] = $total_amount * $total;
        
        if (in_array($is_status, [2, 6, 7, 8])) $map['delay_time'] = date('Y-m-d H:i:s', strtotime('+3minute'));

        // 先扣除余额再创建红包
        $deductMoney = $User->where(['uid' => $uid])->setDec('ll_balance', $total_amount);

        if ($deductMoney && $redModel->add($map)) $this->ajaxSuccess();
        $this->ajaxError(5, '创建红包失败');
    }

    /**
     * 获取红包列表
     */
    public function getRedList()
    {
        $token = trim(I('post.token'));
        $room_id = trim(I('post.room_id'));
        $redModel = new \Common\Model\LiveRedModel();
        $redCond = new \Common\Model\LiveRedConditionModel();
        $redDetails = new \Common\Model\LiveRedDetailsModel();
        $uid = $this->tokenUser($token);

        //查询有效红包列表
        $field = 'id,user_id,is_status,total,delay_time,effective_type,blessing';
        $where['effective_type'] = array('in', [1, 2]);
        $where['room_id'] = $room_id;
        $where['start_time'] = array("GT", date("Y-m-d H:i:s"));
        $data = $redModel->where($where)->field($field)->order('id desc')->select();
        $list = [];
        if (!empty($data)) {
            //处理返回数据
            $totalSum = '';
            foreach ($data as $k => $v) {
                $user = $this->redUser($v['user_id']);
                $data[$k]['nickname'] = $user['nickname'];
                $data[$k]['avatar'] = $user['avatar'];
                $data[$k]['effective_type'] = $v['effective_type'];
                $data[$k]['blessing'] = $v['blessing'];

                //剩余时间秒，若时间已到则返回0
                if (in_array($v['is_status'], [2, 6, 7, 8])) {
                    $data[$k]['time_left'] = intval(strtotime($v['delay_time']) - strtotime(date('Y-m-d H:i:s')));
                    if ($data[$k]['time_left'] <= 0) $data[$k]['time_left'] = 0;
                }
                // 没抢
                $Grabbed_type = $redDetails->where(['user_id' => $uid, 'red_id' => $v['id']])->find();
                if ($v['effective_type'] == 1 && empty($Grabbed_type)) $data[$k]['effective_type'] = 6;

                // 没抢到
                if ($v['effective_type'] == 2 && $Grabbed_type['amount'] == 0) {
                    $data[$k]['grabbed'] = 0;
                    $data[$k]['effective_type'] = 5;
                }

                // 抢到金额
                if (!empty($Grabbed_type['amount'])) {
                    $data[$k]['grabbed'] = $Grabbed_type['amount'];
                    $data[$k]['effective_type'] = 5;
                }

                // 是否已分享
                if (in_array($v['is_status'], [4, 5, 7, 8])) {
                    if ($redCond->where(['user_id' => $uid, 'red_id' => $v['id']])->getField('share_friends') == 1) $data[$k]['red_share'] = 1;
                }

                $totalSum += (int)$v['total'];
                $list = $data;
            }

            $last_names = array_column($list,'effective_type');
            array_multisort($last_names,SORT_DESC,$list);
            $this->ajaxSuccess(['list' => $list, 'tatal_sum' => $totalSum]);
        }
        $this->ajaxError(10, '暂无红包列表',[]);

    }

    /**
     * 查询发红包者头像/昵称
     * @param $uid
     * @return array|false|mixed|string|null
     */
    protected function redUser($uid)
    {
        $UserDetail = new \Common\Model\UserDetailModel();
        $user = $UserDetail->where(['user_id' => $uid])->field('nickname,avatar')->find();
        $user['avatar'] = is_url($user['avatar']) ? $user['avatar'] : WEB_URL . $user['avatar'];
        return $user;
    }

    /**
     * 通過token查詢uid
     * @param $token
     * @return array|int|mixed|string
     */
    protected function tokenUser($token)
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        return $User->getUserId($token);
    }

    /**
     * 抢红包
     */
    public function grabRed()
    {
        $redId = trim(I('post.red_id'));
        $token = trim(I('post.token'));
        $User = new \Common\Model\UserModel();
        $redModel = new \Common\Model\LiveRedModel();
        $redDetails = new \Common\Model\LiveRedDetailsModel();
        $uid = $User->getUserId($token);
        $redMoneyJson = $redModel->where(['id' => $redId])->field('red_money,type,user_id')->find();
        $slowHand = $redDetails->where(['red_id' => $redId, 'user_id' => $uid])->field('amount')->find();
        $username = $this->redUser($redMoneyJson['user_id'])['nickname'];
        if ($slowHand) {
            if (empty($slowHand['amount'])) $this->ajaxSuccess( ['money' => 0, 'type' => $redMoneyJson['type'], 'nickname' => $username],'手慢了~');
            $this->ajaxError(7, '您已经抢过该红包了');
        }
        $userList = $this->redUser($uid);
        if ($redMoneyJson && $redMoneyJson['red_money'] <> '[]') {
            $moneData = json_decode($redMoneyJson['red_money'], true);
            $grabbedMoney = $moneData[0];
            $map = [
                'red_id' => $redId,
                'user_id' => $uid,
                'amount' => $grabbedMoney,
                'name' => $userList['nickname'],
                'avatar' => $userList['avatar'],
                'add_time' => date('Y-m-d H:i:s')
            ];
            //写入红包详情记录
            $redDetails->add($map);
            array_shift($moneData);
            // 用户抢到鹿角加入余额
            $User->where(['uid' => $uid])->setInc('ll_balance', $grabbedMoney);
            // 更新剩余红包金额
            $redModel->where(['id' => $redId])->save(['red_money' => json_encode($moneData)]);
            if (empty($moneData)) {
                $redModel->where(['id' => $redId])->save(['effective_type' => 2]);
                $redtype = $redDetails->where(['red_id' => $redId, 'user_id' => $uid])->find();
                if (empty($redtype)) {
                    $map['amount'] = 0;
                    $redDetails->add($map);
                }
            }
            $this->ajaxSuccess(['money' => $grabbedMoney, 'type' => $redMoneyJson['type'], 'nickname' => $username]);
        } else {
            $map = [
                'red_id' => $redId,
                'user_id' => $uid,
                'amount' => 0,
                'name' => $userList['nickname'],
                'avatar' => $userList['avatar'],
                'add_time' => date('Y-m-d H:i:s')
            ];
            $redDetails->add($map);
            $this->ajaxSuccess( ['money' => 0, 'type' => $redMoneyJson['type'], 'nickname' => $username],'手慢了~');
        }

    }

    /**
     * 红包详情
     */
    public function redDetails()
    {
        $redId = trim(I('post.red_id'));
        $token = trim(I('post.token'));
        $redDetails = new \Common\Model\LiveRedDetailsModel();
        $redModel = new \Common\Model\LiveRedModel();
        $data = $redModel->where(['id' => $redId])->field('user_id,type,total,red_money')->find();
        $userlist = $this->redUser($data['user_id']);
        $uid = $this->tokenUser($token);
        $whe['red_id'] = $redId;
        $whe['amount'] = array("GT","0");
        $red_Details = $redDetails->where($whe)->field('user_id,amount,avatar as user_avatar,name as user_name,add_time')->select();
        $userlist['type'] = $data['type'];
        $userlist['received_red'] = $data['total'] - count(json_decode($data['red_money'], true)) . '/' . $data['total'];
        $whe['user_id'] = $uid;
        $userlist['grabbed'] = $redDetails->where($whe)->getField('amount');
        if ($userlist && $red_Details) $this->ajaxSuccess(['list' => $red_Details, 'head' => $userlist]);
        $this->ajaxError(8,'暂无红包信息');
    }

}
