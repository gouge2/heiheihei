<?php


namespace Common\Model;
use Think\Exception;
use Think\Model;

class HostTreatModel extends Model
{

    private $platform;
    private $isTmp;

    public function __construct($platform = "pdd", $isTmp = false) {
        $modelName = $platform . "_order";
        if ($platform == 'jingdong') {
            $modelName .= '_detail';
        }
        parent::__construct($modelName);
        $this->platform = $platform;
        $this->isTmp = $isTmp;
    }

    /**
     * 主播分佣
     * @param $host_id int 主播id
     * @param $param string 第三方订单表订单编号字段名
     * @param $order_sn string 订单编号
     * @param $money int 分佣金额
     */
    public function submit($host_id, $param, $order_sn, $money) {
        $msg=$this->where("$param='$order_sn'")->find();
        if (empty($msg)) {
            return false;
        }

        $uid=$msg['user_id'];
        //$uid = 14;
        $User=new \Common\Model\UserModel();
        $path = $this->getPath($uid);

        // 1、查看主播分佣比例
        $HostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostCommission = $HostCommissionModel->getGroupMsg();
        /*$treatList = array( 0 => array(
            'third_tax' => 20,
            'platform' => 10,
            'live_sell' => 70,
            'referrer_rate' => 13,
            'referrer_rate2' => 7,
            'host_commission' => 60,
            'user_commission' => 20
        ));*/ // 模拟数据
        $treatList = array( 0 => $hostCommission);

        writeLog(json_encode(['money'=>$money,'uid'=>$uid]),'commission_host_log');
        try {
            $User->startTrans();
            // 2、主播经纪人返利
            $money = $money * $treatList[0]['fee_sell'] / 100; // 直播销售分佣金额
            $this->broker($order_sn, $host_id, $treatList, $money);

            // 3、主播自己返利
            //$hostMoney = $money * $treatList[0]['fee_host'] / 100; // 主播佣金
            $this->host($order_sn, $host_id, $treatList, $money);

            // 4、购买用户返利
            $money = $money * $treatList[0]['fee_user'] / 100; // 普通用户分佣金额
            $this->general($order_sn, $uid, $money);
        } catch (Exception $e) {
            $User->rollback();
            return false;
        }

        $User->commit();
        return true;
    }
    /**
     * 主播经纪人返利
     * @param $order_sn
     * @param $uid
     * @param $treatList
     * @param $money
     */
    public function broker($order_sn, $uid, $treatList, $money) {
        $User=new \Common\Model\UserModel();

        $path = $this->getPath($uid);

        // 1、取经纪人分佣用户
        $path = array_slice($path,0, 2);
        $teamList = $User->getReferrer($path);
        $orderDetail = array(
            'order_sn' => $order_sn, // 订单编号
            'teamList' => $teamList, // 分佣用户
            'groupList' => $treatList,  // 分佣比例信息
            'money' => $money,  // 分佣金额
            'push_val' => '1',  // push推送value
            'ref_field' => 'broker_rate', // 佣金比例字段
        );
        return $this->calandsave($vip_num, $orderDetail);
    }

    /**
     * 主播佣金
     * @param $order_sn
     * @param $uid
     * @param $treatList
     * @param $money
     * @return bool
     * @throws Exception
     */
    public function host($order_sn, $uid, $treatList, $money) {
        $User=new \Common\Model\UserModel();

        $msg = $User->getUserMsg($uid);

        $orderDetail = array(
            'order_sn' => $order_sn,
            'teamList' => array(0 => $msg),
            'groupList' => $treatList,
            'money' => $money,
            'push_val' => '0',
            'ref_field' => 'fee_host',
        );

        return $this->calandsave($vip_num, $orderDetail);
    }

    /**
     * 普通用户分佣
     * @param $order_sn
     * @param $uid
     * @param $treatList
     * @param $money
     */
    public function general($order_sn, $uid, $money) {
        $User=new \Common\Model\UserModel();

        // 获取会员分组分佣数据
        $HostUserGroup = new \Common\Model\HostUserGroupModel();
        $groupList = $HostUserGroup->getGroupList();

        // 2、计算直接/间接分佣
        $path = $this->getPath($uid);
        $two_level_path = array_slice($path,0, 2);
        $teamList = $User->getReferrer($two_level_path);

        $vip_num = 0;
        $orderDetail = array(
            'order_sn' => $order_sn,
            'teamList' => $teamList,
            'groupList' => $groupList,
            'money' => $money,
            'push_val' => '1',
            'ref_field' => 'referrer_rate'
        );
        $this->calandsave($vip_num, $orderDetail);

        // 1、计算用户分佣
        $msg = $User->getUserMsg($uid);
        $orderDetail['teamList'] = array(0 => $msg);
        $orderDetail['push_val'] = '0';
        $orderDetail['ref_field'] = 'fee_user';
        $this->calandsave($not_num, $orderDetail);

        if ($vip_num >= 2) {
            return true;
        }

        // 3、计算团队1/2级分佣
        $teamList = $User->getReferrer($path, true, 2 - $vip_num);
        $orderDetail['teamList'] = $teamList;
        $orderDetail['push_val'] = '2';
        $orderDetail['ref_field'] = 'team_rate';
        $this->calandsave($vip_num, $orderDetail);

        return true;
    }

    /**
     * 获取团队成员列表
     * @param $uid
     * @return false|string[]
     */
    private function getPath($uid) {

        $User=new \Common\Model\UserModel();
        $UserMsg=$User->getUserMsg($uid);
        $path = $UserMsg['path'];
        $path = explode(",", $path);
        array_pop($path); // 移除最后一个，移除自己
        $path = array_reverse($path); // 倒序

        return $path;
    }

    /**
     * 计算佣金，并保存
     * @param int $vip_num 会员人数
     * @param array $orderDetail 订单相关内容
     * @return bool
     * @throws Exception
     */
    public function calandsave(&$vip_num, $orderDetail) {
        $User=new \Common\Model\UserModel();
        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
        $UserBalanceRecordTmp = new \Common\Model\UserBalanceRecordTmpModel();
        $vip_num = 0;

        // 开始计算分佣金额，并添加佣金
        foreach ($orderDetail['teamList'] as $key => $val) {
            $index = $val['group_id'] - 1 < 0 || count($orderDetail['groupList']) == 1 ? 0 : $val['group_id'] - 1;
            $referrer_money = round($orderDetail['money'] * $orderDetail['groupList'][$index][$orderDetail['ref_field'] . ($key == 0 ? "" : "2")] / 100.00, 2);

            if ($referrer_money > 0) {
                // 1、增加团队推荐人用户余额
                $referrer_tid = $val['uid'];
                $res_balance_rt = true;
                if (!$this->isTmp)
                    $res_balance_rt=$User->where("uid='$referrer_tid'")->setInc('balance',$referrer_money);
                // 2、保存余额变动记录
                $all_money_rt=$val['balance']+$referrer_money;
                $res_record_rt = true;
                if (!$this->isTmp)
                    $res_record_rt=$UserBalanceRecord->addLog($referrer_tid, $referrer_money, $all_money_rt, $this->platform . '_rt','2',$orderDetail['order_sn'],'3');
                else {
                    $type = '1';
                    if ($this->platform == 'pdd')
                        $type = '3';
                    if ($this->platform == 'jingdong')
                        $type = '2';
                    $res_record_rt = $UserBalanceRecordTmp->addLog($referrer_tid, $referrer_money, $this->platform, $orderDetail['order_sn'], $type, date('Y-m-d H:i:s'));
                }

                if (!$res_balance_rt || !$res_record_rt) {
                    throw new Exception("佣金添加失败", 500);
                }

                // 3、判断是不是会员
                if (in_array((int)$val['group_id'], array(3,4))) {
                    $vip_num++;
                }

                // 4、推送
                if (!$this->isTmp)
                    $this->push($referrer_tid, $referrer_money, $orderDetail['push_val']);
            }
        }

        return true;
    }

    /**
     * 到账消息退货
     * @param $uid
     * @param $money
     * @param $value
     */
    public function push($uid, $money, $value) {
        $value = $value == '0' ? $this->platform : $this->platform . $value;

        //极光推送消息
        Vendor('jpush.jpush','','.class.php');
        $jpush=new \jpush();
        $alias=$uid;//推送别名
        $title='收入通知';
        $content='您有一笔'.$money.'元收入，请查收！';
        $key='banlance';
        //$value= "pdd";
        $res_push=$jpush->push($alias,$title,$content,'',$msg_title='',$msg_content='',$key,$value);
    }

    /**
     * 获取会员分组
     * @param $groupMsg
     * @param $userBrowseList
     * @param $orderList
     * @param $UserMsg
     * @param $param
     */
    public function groupListForOrder(&$groupMsg, &$userBrowseList, $orderList, $UserMsg, $param) {
        // 1、查看没有直播的分佣比例
        $UserGroup=new \Common\Model\UserGroupModel();
        $groupMsg1=$UserGroup->getGroupMsg($UserMsg['group_id']);

        // 2、查询主播分佣基本配置
        $HostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostCommission = $HostCommissionModel->getGroupMsg();

        // 3、查询直播分佣用户分组比例
        $HostUserGroup = new \Common\Model\HostUserGroupModel();
        $groupMsg2 = $HostUserGroup->getGroupMsg($UserMsg['group_id']);
        // 循环所有比例*用户该有的百分比
        $changeList = array('referrer_rate', 'referrer_rate2', 'team_rate', 'team_rate2', 'fee_user');
        foreach ($groupMsg2 as $key => $val) {
            if (in_array($key, $changeList))
                $groupMsg2[$key] = $val * $hostCommission['fee_sell'] * $hostCommission['fee_user'] / 100 / 100;
        }

        // 4、循环商品列表
        $idArr = array();
        foreach ($orderList as $key => $val) {
            array_push($idArr, $val[$param]);
        }

        // 5、查看用户浏览记录
        if (count($idArr) > 0) {
            $idArr = implode(",", $idArr);
            $user_id = $UserMsg['uid'];
            $HostUserBrowse = new \Common\Model\HostUserBrowseModel();
            $list = $HostUserBrowse->where("user_id='$user_id' and goods_id in ($idArr)")->field("goods_id")->select();

            $userBrowseList = array();
            foreach ($list as $key => $val) {
                array_push($userBrowseList, $val['goods_id']);
            }
        }
        // 6、封装结果集
        $groupMsg = array(
            -1 => $hostCommission,
            0 => $groupMsg1,
            1 => $groupMsg2
        );
    }

    /**
     * 更新第三方订单时，添加主播和经纪人信息
     * @param $uid
     * @param $goods_id
     * @return array
     */
    public function getHost($uid, $goods_id) {
        $HostUserBrowse = new \Common\Model\HostUserBrowseModel();

        $data = array(
            'host_id' => '',
            'referrer1_id' => '',
            'referrer2_id' => '',
        );
        $browse = $HostUserBrowse->where("user_id='$uid' and goods_id in ($goods_id)")->find();

        // 找到了用户浏览记录，去找主播的经纪人信息
        if (!empty($browse)) {
            $path = $this->getPath($browse['host_id']);
            $path = array_slice($path,0, 2);

            // 循环成员
            foreach ($path as $key => $val) {
                $data["referrer" . ($key+1) . "_id"] = $val;
            }
            $data['host_id'] = $browse['host_id'];
        }

        return $data;
    }

    /**
     * 获取最新的主播的分佣比例
     */
    public function getHostCommission($list, $type, $group_id, $good_param) {
        // 查询基本分佣记录
        $HostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostCommission = $HostCommissionModel->getGroupMsg();
        // 查询直播分佣用户分组比例
        $HostUserGroup = new \Common\Model\HostUserGroupModel();
        $groupMsg = $HostUserGroup->getGroupMsg(1);

        // 循环商品列表
        $ids = array();
        if ($good_param) {
            foreach ($list as $key => $val) {
                $ids[] = $val[$good_param];
            }
        } else {
            $ids = $list;
        }

        $ids                = implode(",", $ids);
        $ShortLiveGoodsModel= new \Common\Model\ShortLiveGoodsModel();
        $slg_whe            = ["goods_id" => ["in" => $ids], "`from`" => $type, 'is_status' => ['in', [0,1]]];
        $goodsList          = $ShortLiveGoodsModel->getList($slg_whe, 'goods_id');
        $newGoodsList       = array();
        foreach ($goodsList as $key => $val) {
            $newGoodsList[] = $val['goods_id'];
        }

        $commission = 1;
        $commission = $commission * $hostCommission['fee_sell'] / 100; // 直播销售分佣金额
        $userCommission = $commission * $hostCommission['fee_user'] / 100; // 用户分佣金额
        $commission = $commission * $hostCommission['fee_host'] / 100; // 主播佣金比例

        return array(
            'userCommission' => $userCommission * $groupMsg['fee_user'] / 100,
            'commission' => $commission,
            'goodsList' => $newGoodsList
        );
    }

    /**
     * 分佣算法
     * 一、礼包体系（不考虑自购）
     *     计算规则如下：
     *          1、可用分佣金额 =礼包实际利润金额;
     *          2、直推：可用分佣金额 * Z（获佣会员所属分组比一级提成）
     *          3、间推：可用分佣金额 * Z（获佣会员所属分组比二级提成）
     *
     **** 统一计算   实际分成佣金 - X（佣金*20%） - Y（佣金*10%） ***
     *
     * 二、导购体系（考虑自购）  ** 当自购用户属于 VIP（一、二级）时，只发放自购佣金 ；当满足双次VIP佣金发放时，退出 **
     *     计算规则如下：
     *          1、自购佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          2、直推佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          3、间推佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          4、团队一级：实际分成佣金 * X（VIP会员且按照所属VIP等级比例）
     *          5、团队二级：实际分成佣金 * Y（VIP会员且按照所属VIP等级比例）
     * 三、直播体系（考虑自购）
     *      *** 1、会员点击锁佣30分钟，超时释放；2、基于选品商品分佣获取实际分佣金额（三方佣金 * L（平台直播带货佣金比）：P）；***
     *     计算规则如下：
     *          ———— 带货机制 ————
     *          1、购买佣金：（P -(X+Y)）* M（直播佣金比）
     *          2、直推佣金：（P -(X+Y)）* N（直推佣金比）
     *          3、间推佣金：（P -(X+Y)）* O（间推佣金比）
     *          ———— 导购机制 ————
     *          4、实际分成佣金 = （P -(X+Y)）* K（购买体系佣金比）
     */

    /**
     * 导购分销
     * @param $id 订单ID
     */
    public function dgCommission($id)
    {

    }

    /**
     * 佣金二次计算
     * @param $uid          发放的用户ID
     * @param $money        订单的佣金
     * @param $goods_id     商品ID
     * @param $type         归属的平台  1：tb 2：pdd 3：jd 4：vip 5：self
     * @param $create_time  订单下单时间
     */
    public function computeCommission($uid,$money,$goods_id,$type,$create_time)
    {
        $userModel = new \Common\Model\UserModel();
        $userGroupModel = new \Common\Model\UserGroupModel();
        $commissionModel = new \Common\Model\HostCommissionModel();
        $shortGoodsModel = new \Common\Model\ShortLiveGoodsModel();

        $userMsg = $userModel->getUserMsg($uid);
        if($userMsg)
        {
            $group = $userGroupModel->getGroupMsg($userMsg['group_id']);
            #根据平台取得商品并且确认是否符合带货标准
            $where['goods_id'] = $goods_id;
            if(is_int($create_time))
            {
                $where = ['exp',' add_time >='.date('Y-m-d H:i:s',$create_time-(30*60))];
            }else{
                $where = ['exp',' add_time >='.date('Y-m-d H:i:s',strtotime($create_time)-(30*60))];
            }
            switch ($type)
            {
                case 5:
                    $where['from'] = 'self';
                    break;
                case 4:
                    $where['from'] = 'vip';
                    break;
                case 3:
                    $where['from'] = 'jd';
                    break;
                case 2:
                    $where['from'] = 'pdd';
                    break;
                default:
                    $where['from'] = 'tb';
                    break;
            }
            $item = $shortGoodsModel->where($where)->find();
            print_r($item);die;
        }
    }

    /**
     * 获取实际佣金
     * @param $uid      用户ID
     * @param $is_has   是否存在库存
     * @param $money    实际分佣
     * @param $group_id 最高分组ID
     */
    public function getCommissionByUser($uid,$is_has=0,$money,$group_id=0)
    {
        #获取用户信息
        $userModel = new \Common\Model\UserModel();
        $userMsg = $userModel->getUserMsg($uid);
        #获取后台这是分佣比例
        $commissionModel = new \Common\Model\HostCommissionModel();
        $commission = $commissionModel->where('id=1')->find();
        #获取分组信息
        $userGroupModel = new \Common\Model\HostUserGroupModel();
        $userGroup = $userGroupModel->getGroupMsg($userMsg['group_id']);
        $vipGroup = $userGroupModel->getGroupMsg($group_id);
        if($is_has == 1)
        {
            #用户自购佣金  (总佣金*(平台收益比+平台扣税比))*直播用户收益比
            $userHasCommission = substr(sprintf("%.3f",( $money* ($commission['fee_host']/100) *($commission['fee_user']/100)*($userGroup['fee_user']/100))),0,-1);
            #带货赚   总佣金*直播佣金比
            $hostUserCommission =substr(sprintf("%.3f",$money * ($commission['fee_host']/100)*($commission['fee_sell']/100)),0,-1);
            if($vipGroup)
            {
                $vipCommission = substr(sprintf("%.3f",( $money* ($commission['fee_host']/100) *($commission['fee_user']/100)*($vipGroup['fee_user']/100))),0,-1);
            }
        }else{
            #用户自购佣金   （总佣金-(总佣金*（平台收益比+平台扣税比）)*用户收益比）
            $userHasCommission = substr(sprintf("%.3f",$money* ($userGroup['fee_user']/100)),0,-1);
            #带货赚   总佣金*直播佣金比
            $hostUserCommission =substr(sprintf("%.3f",$money * ($commission['fee_host']/100)*($commission['fee_sell']/100)),0,-1);
            if($vipGroup) {
                $vipCommission = substr(sprintf("%.3f", $money * ($vipGroup['fee_user'] / 100)), 0, -1);
            }
        }

        return array('hostUserCommission'=>empty($hostUserCommission)?0:$hostUserCommission,'userHasCommission'=>empty($userHasCommission)?0:$userHasCommission,'vipHasCommission'=>empty($vipCommission)?0:$vipCommission);
    }
}
?>