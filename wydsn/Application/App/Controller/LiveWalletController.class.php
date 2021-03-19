<?php
/**
 * 直播钱包管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;


class LiveWalletController extends AuthController
{
    
    /**
     * 直播钱包说明
     */
    public function getExplain()
    {
        $token               = trim(I('post.token'));

        if (IS_POST) {
            $d_ratio    = GIFT_D_RATIO;             // 鹿角转翠花币比例
            $r_ratio    = GIFT_R_RATIO;             // 余额转翠花币比例
            $cost       = GIFT_COST;                // 平台扣费百分比
            $convert_min= GIFT_CONVERT_MIN;         // 兑换鹿角最少的金额
            $extract_min= GIFT_EXTRACT_MIN;         // 提取最少的金额

            // 用户余额
            $User       = new \Common\Model\UserModel();
            $one        = $token ? $User->field('ll_balance,ll_deer')->where(['token' => $token])->find() : null;

            $data       = [
                'll'            => GIFT_MONEY_DSC,  
                'deer'          => GIFT_DEER_DSC,  
                'wd'            => GIFT_WD_DSC,  
                'll_cn'         => GIFT_MONEY_CN,
                'll_deer_cn'    => GIFT_DEER_CN,
                'll_balance'    => (isset($one['ll_balance']) ? (int)$one['ll_balance'] : 0),
                'll_deer'       => (isset($one['ll_deer']) ? (int)$one['ll_deer'] : 0),
                'convert_min'   => $convert_min,
                'convert_base'  => ($d_ratio * $cost * 0.0001),
                'extract_min'   => $extract_min,
                'extract_base'  => ($r_ratio * $cost * 0.000001),
            ];
            $data['wd_balance'] = floor($data['ll_deer'] * $data['extract_base']);      // 舍去法取整

            $this->ajaxSuccess(['explain' => $data]);
        }

        $this->ajaxError();
    }

    /**
     * 送出礼物记录 / 翠花币记录
     */
    public function getGiveList()
    {
        $type               = trim(I('post.type'));             // 查询类型  gift礼物记录  deer翠花币记录                  
        $platform           = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        if ($type && in_array($type, ['gift', 'deer'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $uid                = $res_token['uid'];
            $GiftGive           = new \Common\Model\GiftGiveModel();
            
            $list               = $GiftGive->giveGroupList($type, $uid, $limit, $page);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }


    /**
     * 送出礼物详情 / 翠花币记录详情
     */
    public function getGiveDetail()
    {
        $view_id            = I('post.view_id/d');
        $type               = trim(I('post.type'));             // 查询类型  gift礼物记录  deer翠花币记录
        $platform           = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        if ($view_id && $type && in_array($type, ['gift', 'deer'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $uid            = $res_token['uid'];
            $GiftGive       = new \Common\Model\GiftGiveModel();
            
            $list           = $GiftGive->giveList($type, $uid, $view_id, $limit, $page);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 兑换
     */
    public function deerConvert()
    {
        $money       = I('post.money/d');  // 兑换鹿角金额

        if ($money) {
            if ($money < GIFT_CONVERT_MIN) {
                $this->ajaxError(10000, '兑换金额不可以少于'. GIFT_CONVERT_MIN . GIFT_MONEY_CN);
            }

            if (!is_int($money)) {
                $this->ajaxError(10001, '请输入整数的兑换金额');
            }

            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $uid            = $res_token['uid'];

            $d_ratio        = GIFT_D_RATIO;                // 鹿角转翠花币比例
            $cost           = GIFT_COST;                   // 平台扣费百分比
            $deer           = ceil(($money * $d_ratio * 100) / (100 - $cost));      // 要扣除的翠花币金额 进一法取整
            $deduct         = $deer - ($money * $d_ratio);                          // 扣除的翠花币手续费

            $one            = $User->field('ll_balance,ll_deer')->where(['uid' => $uid])->find();

            if (!$one) {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
            } 

            if ($one['ll_deer'] < $deer) {
                $this->ajaxError(10002, '您的'. GIFT_DEER_CN .'余额不足');
            }

            $DeerConvert    = new \Common\Model\DeerConvertModel();

            $ins            = [
                'user_id'   => $uid,
                'deer'      => $deer,
                'money'     => $money,
                'deduct'    => $deduct,
                'is_status' => 1,
                'add_time'  => date('Y-m-d H:i:s'),
            ];

            $update         = [
                'll_balance'=> ($one['ll_balance'] + $money),
                'll_deer'   => ($one['ll_deer'] - $deer),
            ];

            $User->startTrans();   // 启用事务
            try {
                // 用户翠花币减 鹿角加
                $User->where(['uid' => $uid, 'll_deer' => ['egt', $deer]])->save($update);

                // 添加兑换记录
                $DeerConvert->add($ins);

                // 事务提交
                $User->commit();

                $this->ajaxSuccess();

            } catch(\Exception $e) {
                // 事务回滚
                $User->rollback();

                // 数据库错误
                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 提取
     */
    public function deerExtract()
    {
        $money       = I('post.money/d');  // 提取金额

        if ($money) {
            if ($money < GIFT_EXTRACT_MIN) {
                $this->ajaxError(10010, '提取金额不可以少于'. GIFT_EXTRACT_MIN .'元');
            }

            if (!is_int($money)) {
                $this->ajaxError(10011, '请输入整数的提取金额');
            }

            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $uid            = $res_token['uid'];

            $r_ratio        = GIFT_R_RATIO;                 // 余额转翠花币比例
            $cost           = GIFT_COST;                    // 平台扣费百分比
            $deer           = ceil(($money * $r_ratio * 100) / (100 - $cost));      // 要扣除的翠花币金额 进一法取整
            $deduct         = $deer - ($money * $r_ratio);                          // 扣除的翠花币手续费

            $one            = $User->field('balance,ll_deer')->where(['uid' => $uid])->find();

            if (!$one) {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
            } 

            if ($one['ll_deer'] < $deer) {
                $this->ajaxError(10002, '您的'. GIFT_DEER_CN .'余额不足');
            }

            $DeerExtract        = new \Common\Model\DeerExtractModel();
            $UserBalanceRecord  = new \Common\Model\UserBalanceRecordModel();

            $ins            = [
                'user_id'   => $uid,
                'deer'      => $deer,
                'balance'   => $money,
                'deduct'    => $deduct,
                'is_status' => 1,
                'add_time'  => date('Y-m-d H:i:s'),
            ];

            $update         = [
                'balance'   => ($one['balance'] + $money),
                'll_deer'   => ($one['ll_deer'] - $deer),
            ];

            $User->startTrans();   // 启用事务
            try {
                // 用户翠花币减
                $User->where(['uid' => $uid, 'll_deer' => ['egt', $deer]])->save($update);

                // 添加提取记录
                $DeerExtract->add($ins);

                // 余额日志记录
                $UserBalanceRecord->addLog($uid, $money, ($one['balance'] + $money), 'deer_take');

                // 事务提交
                $User->commit();

                $this->ajaxSuccess();

            } catch(\Exception $e) {
                // 事务回滚
                $User->rollback();

                // 数据库错误
                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 我的账单
     */
    public function myBill()
    {
        $type               = trim(I('post.type'));            // recharge充值   convert兑换   extract提取
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        if ($type && in_array($type, ['recharge', 'convert', 'extract'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $uid            = $res_token['uid'];

            // 充值
            if ($type == 'recharge') {
                $FillRecord = new \Common\Model\FillRecordModel();
                $list       = $FillRecord->getFill($uid, $limit, $page);

            // 兑换
            } elseif ($type == 'convert') {
                $DeerConvert= new \Common\Model\DeerConvertModel();
                $list       = $DeerConvert->getConvert($uid, $limit, $page);

             // 提取
            } elseif ($type == 'extract') {
                $DeerExtract= new \Common\Model\DeerExtractModel();
                $list       = $DeerExtract->getExtract($uid, $limit, $page);
            }

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 鹿角记录列表
     */
    public function antler_ecord()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);
        //红包部分
        $uid            = $res_token['uid'];
        $redModel = new \Common\Model\LiveRedModel();
        $UserDetail = new \Common\Model\UserDetailModel();
        $redDetails = new \Common\Model\LiveRedDetailsModel();

        // 发出红包
        $issue = $redModel->where(['user_id' => $uid])->field('total_amount,add_time,refund,start_time')->page($page, $limit)->order('add_time desc')->select();
        foreach ($issue as $k => $v) {
            $issue[$k]['add_time'] = strtotime($issue[$k]['add_time']);
            $issue[$k]['start_time'] = strtotime($issue[$k]['start_time']);
            if (empty($v['refund'])) {
                unset($issue[$k]['refund'],$issue[$k]['start_time']);
            } else {
                unset($issue[$k]['add_time']);
            }
            //记录类型
            $issue[$k]['record_type'] = 2;
        }

        $limit = $limit -  count($issue);
        if ($limit != 0) {
            //接受红包
            $whe['user_id'] = $uid;
            $whe['amount'] = array("GT", "0");
            $accept = $redDetails->where($whe)->field('amount,red_id,add_time')->page($page, $limit)->order('add_time desc')->select();
            foreach ($accept as $k => $v) {
                $userType = $redModel->where(['id' => $v['red_id']])->field('user_id,type')->find();
                $accept[$k]['user_id'] = $userType['user_id'];
                $accept[$k]['type'] = $userType['type'];
                $accept[$k]['add_time'] = strtotime($accept[$k]['add_time']);
                $accept[$k]['nickname'] = $UserDetail->where(['user_id' => $userType['user_id']])->getField('nickname');
                //记录类型
                $accept[$k]['record_type'] = 3;
                unset($accept[$k]['user_id'], $accept[$k]['red_id']);
            }

            $limit = $limit - count($accept);
            if ($limit != 0) {
                // 打赏部分
                $data = $reward = [];
                $giftGive = new \Common\Model\GiftGiveModel();
                $gifnum = $giftGive->Distinct(true)->where(['user_id' => $uid, 'is_status' => 'succ'])->field('host_id')->select();

                foreach ($gifnum as $k => $v) {
                    $reward[] = $giftGive->where(['user_id' => $uid, 'is_status' => 'succ', 'host_id' => $v['host_id']])->limit($page, $limit)->field('money,add_time,host_id')->order('id desc')->select();
                    foreach ($reward as $kk => $vv) {
                        $data[$kk]['sum_money'] = array_sum(array_column($vv, 'money'));
                        $data[$kk]['record_type'] = 1;
                        foreach ($vv as $k1 => $v1) {
                            $user = $UserDetail->where(['user_id' => $v1['host_id']])->field('nickname,avatar')->find();
                            //被打赏者头像
                            $data[$kk]['avatar'] = $user['avatar'] ?: '';
                            //被打赏者昵称
                            $data[$kk]['nickname'] = $user['nickname'] ?: '';
                            //打赏时间
                            $data[$kk]['add_time'] = strtotime($v1['add_time']);
                            //被打赏者uid
                            $data[$kk]['user_id'] = $v1['host_id'];
                        }
                    }
                }
                foreach ($data as $k => $v) {
                    $list[] = $v;
                }
            }
        }
        unset($v,$k);
        $list['issue'] = $issue;
        $list['accept'] = $accept;
        array_merge($list,$issue,$accept);
        foreach ($list['issue'] as $k => $v) {
            $list[] = $v;
        }
        foreach ($list['accept'] as $k => $v) {
            $list[] = $v;
        }
        unset($list['issue'],$list['accept']);
        $this->ajaxSuccess(['list' => $list]);
    }

}
?>