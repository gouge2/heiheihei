<?php
/**
 * 直播房间记录管理类
 */
namespace Common\Model;
use Think\Model;

class LiveRoomModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('user_id','require','所属用户标识不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );


    /**
     * 直播中列表数据
     */
    public function getListData($whe, $at_id, $limit, $page, $platform)
    {
        $data       = [];
        $date       = date('Y-m-d H:i:s');
        $needRecord = false;

        // 是否是回放的多条件查询
        if (isset($whe['_complex'])) {
            $needRecord             = true;
        } else {
            $whe['is_status']       = 1;
            $whe['is_recommend']    = 1;
        }

        $list   = $this
                ->field('room_id,room_name,user_id,cover_url,is_put,is_status,recent_time,sort')
                ->where($whe)
                ->page($page, $limit)
                ->order('is_status asc')
                ->select();

        // 刷选出回放
        $recordList = [];
        if ($needRecord) {
            $ttime  = isset($whe['_complex']['recent_time']) ? strtotime($whe['_complex']['recent_time'][1]) : 0;

            foreach ($list as $key => $val) {
                if (!in_array($val['is_status'], [1,2]) || ($val['is_status'] == 1 && $ttime && strtotime($val['recent_time']) > $ttime)) {
                    $recordList[$val['user_id']] = $val['room_id'];
                    unset($list[$key]);
                }
            }
        }


        if ($list) {
            // 其他模型
            $Im 	        = new \Common\Controller\ImController();
            $UserDetail     = new \Common\Model\UserDetailModel();
            $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
            $LiveSite       = new \Common\Model\LiveSiteModel();
            $UserConcern    = new \Common\Model\UserConcernModel();
            $Short          = new \Common\Model\ShortModel();

            // 其他表查询条件
            $uid_arr        = $rid_arr = [];
            $ud_list        = $ls_list = $goods_list = $uc_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $v) {
                $uid_arr[]  = $v['user_id'];
                $rid_arr[]  = $v['room_id'];
            }

            // 用户列表
            $ug_whe         = ['user_id' => ['in', $uid_arr]];
            $ud_list        = $UserDetail->where($ug_whe)->getField('user_id,nickname,avatar');

            // 直播场次获取
            $ls_whe         = ['room_id' => ['in', $rid_arr],'start_time' => ['elt', $date], 'end_time' => '0000-00-00 00:00:00'];
            $ls_list        = $LiveSite->where($ls_whe)->getField('room_id,site_id');

            if ($ls_list) {
                // 商品数据
                $slg_whe    = ['type' => 'live', 'site_id' => ['in', $ls_list]];
                $goods_list = $ShortLiveGoods->getGoodsData('live', $slg_whe, 0, null, null, $platform);
            }

            // 假直播商品列表
            $goods_list_ad  = $ShortLiveGoods->getGoodsData('fake', ['user_id' => ['in', $uid_arr], 'type' => 'fake'], 0, null, null, $platform);

            // 关注列表
            $uc_list        = $at_id ? $UserConcern->where(['by_id' => ['in', $uid_arr], 'user_id' => $at_id])->getField('by_id', true) : [];

            // 后台假直播数据列表
            $ad_list        = $Short->getAdList(['user_id' => ['in', $uid_arr]]);


            // 拉流地址信息
            $time           = date('Y-m-d H:i:s', ($Im->getLiveAgingTime() + $_SERVER['REQUEST_TIME']));

            // 循环组装参数
            foreach ($list as $key => $val) {
                $temp                   = $val;
                $ad_tag                 = $val['is_status'] == 2 ? true : false;      // 假直播标识

                // 作者信息
                $det                    = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => null];

                // 判断头像是否为第三方应用头像
                if ($det['avatar'] && !is_url($det['avatar'])) {
                    $det['avatar']      = WEB_URL . $det['avatar'];
                }

                // 房间封面空用头像
                $temp['cover_url']      = empty($temp['cover_url']) ? $det['avatar'] : (!is_url($temp['cover_url']) ? WEB_URL . $val['cover_url'] : $temp['cover_url']);

                // 商品信息
                if ($platform != 'ios') {       // 非苹果端
                    $temp['goods']      = null;
                }
                if (!$ad_tag) {             // 正常直播商品数据
                    if ($ls_list && $goods_list && isset($ls_list[$val['room_id']]) && isset($goods_list[$ls_list[$val['room_id']]])) {
                        $temp['goods']  = $goods_list[$ls_list[$val['room_id']]];
                    }
                } else {                    // 假直播商品信息
                    if (isset($goods_list_ad[$val['user_id']])) {
                        $temp['goods']  = $goods_list_ad[$val['user_id']];
                    }
                }

                // 假直播地址
                if ($ad_tag) {
                    $pull['pull']       = ['pull_rtmp' => '', 'pull_flv' => '', 'pull_m3u8' => ''];

                    if (isset($ad_list[$val['room_id']])) {
                        $pull['pull']   = [
                            'pull_rtmp' => $ad_list[$val['room_id']]['media_url'],
                            'pull_flv'  => $ad_list[$val['room_id']]['media_url'],
                            'pull_m3u8' => $ad_list[$val['room_id']]['media_url'],
                        ];
                    }

                // 拉流信息
                } else {
                    $live_domain        = $Im->getLiveDomain();
                    $room_info              = get_live_room_info($val['room_id']);
                    $pull_user = $val['user_id'];
                    if (isset($room_info['tripartite']) && $room_info['tripartite'] == 1) {
                        $pull_user = $val['user_id'].'_t';
                    }
                    $pull['pull']       = get_push_pull_url($live_domain['pull'][0], $Im->getImSdkAppid() .'_'. $pull_user, $Im->getLivekey(), $time, false);
                }

                // 关注标识
                $temp['concern_iden']   = in_array($temp['user_id'], $uc_list) ? 1 : 0;

                // 人数与点赞值
                $room_info              = get_live_room_info($val['room_id']);
                $temp['people']         = (int)$room_info['people'];
                $temp['praise_num']     = (int)$room_info['praise_num'];
                $temp['is_live']        = 'Y';
                $temp['is_fake']        = $ad_tag ? 'Y' : 'N';              // 假直播标识


                unset($temp['user_id']);
                $data['live'][]         = array_merge($temp, $det, $pull);
            }
        }
        foreach ($data['live'] as $k=>$v){
            if ($v['sort']) {
                $data['live'] =   array_sort($data['live'],'sort','desc');
            }
        }
        if ($needRecord) {
            $data['record'] = $recordList;
        }

        return $data;
    }

    /**
     * 正在直播房间直播信息
     */
    public function getIsLive($whe)
    {
        $data       = [];
        $list       = $this->field('user_id,room_id,is_status')->where($whe)->where(['is_status' => ['in', [1,2]]])->select();

        if ($list) {
            $Im 	            = new \Common\Controller\ImController();
            $Short              = new \Common\Model\ShortModel();

            // 拉流地址信息
            $time               = date('Y-m-d H:i:s', ($Im->getLiveAgingTime() + $_SERVER['REQUEST_TIME']));
            $live_domain        = $Im->getLiveDomain();

            // 后台假直播数据列表
            $ad_list            = [];
            if (isset($whe['user_id'])) {
                $ad_list        = $Short->getAdList(['user_id' => $whe['user_id']]);
            }

            foreach($list as $val) {
                $is_fake                    = 'N';
                $live_iden                  = 1;

                // 拉流信息
                if ($val['is_status'] == 1) {
                    $room_info              = get_live_room_info($val['room_id']);
                    $pull_user = $val['user_id'];
                    if (isset($room_info['tripartite']) && $room_info['tripartite'] == 1) {
                        $pull_user = $val['user_id'].'_t';
                    }
                    $pull                   = get_push_pull_url($live_domain['pull'][0], $Im->getImSdkAppid() .'_'. $pull_user, $Im->getLivekey(), $time, false);
                } else {
                    $pull['pull_rtmp']      = '';
                    $live_iden              = 0;
                    if (isset($ad_list[$val['room_id']])) {
                        $pull['pull_rtmp']  = $ad_list[$val['room_id']]['media_url'];
                        $is_fake            = 'Y';
                        $live_iden          = 1;
                    }
                }

                $data[$val['user_id']]      = ['live_iden' => $live_iden, 'live_url' => $pull['pull_rtmp'], 'room_id' => $val['room_id'], 'is_fake' => $is_fake];
            }
        }

        return $data;
    }

    /**
     * 正在直播房间列表信息
     */
    public function getLiveList($whe, $limit, $page)
    {
        $data               = [];

        $whe['is_status']   = 1;

        $list               = $this->field('room_id,user_id')->where($whe)->page($page, $limit)->select();


        if ($list) {
            $UserDetail     = new \Common\Model\UserDetailModel();

            // 其他表查询条件
            $uid_arr        = [];
            $ud_list        = [];

            // 循环组装其他表查询条件
            foreach ($list as $v) {
                $uid_arr[]  = $v['user_id'];
            }

            // 用户列表
            $ug_whe         = ['user_id' => ['in', $uid_arr]];
            $ud_list        = $UserDetail->where($ug_whe)->getField('user_id,nickname,avatar,sex');

            foreach ($list as $val)  {
                $temp               = $val;

                // 作者信息
                $det                = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => null, 'sex' => ''];

                // 判断头像是否为第三方应用头像
                if ($det['avatar'] && !is_url($det['avatar'])) {
                    $det['avatar']  = WEB_URL . $det['avatar'];
                }

                // 处理参数
                $det['sex']         = $det['sex'] == 1 ? 'man' : 'women';

                // 房间信息
                $room_info          = get_live_room_info($val['room_id']);
                $temp['people']     = $room_info['people'];
                $temp['is_action']  = $room_info['is_action'];

                $data[]             = array_merge($temp, $det);
            }
        }

        return $data;
    }

    /**
     * 直播处理回调事件
     */
    public function processFlow($param)
    {
        if ($param) {
            $date  = date('Y-m-d H:i:s');

            // 流名称分割 获取用户ID
            $uid        = -1;
            if ($param['stream_id']) {
                $arr = explode('_', $param['stream_id']);

                if ($arr && isset($arr[1])) {
                    $uid        = $arr[1];
                }
            }

            // 查询房间记录
            $whe        = ['user_id' => $uid];
            $r_one      = $this->field('room_id,user_id,room_name,cover_url')->where($whe)->find();

            $Im         = new \Common\Controller\ImController();
            $User       = new \Common\Model\UserModel();
            if ($r_one) {
                // 其他模型
                $LiveSite       = new \Common\Model\LiveSiteModel();
                $Short          = new \Common\Model\ShortModel();
                $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
                $redModel = new \Common\Model\LiveRedModel();
                $r_whe          = ['room_id' => $r_one['room_id']];
                $room_info      = get_live_room_info($r_one['room_id']);  // 房间信息

                $this->startTrans();   // 启用事务
                try {
                    // 直播推流事件
                    if ($param['event_type'] == 1) {
                        // 三方推流
                        if (isset($arr[2])) {
                            $ah 	= $User->checkAuthority('live', $uid);  // 检查开播等级是否达到
                            if ($ah['code'] == 0) {
                                get_live_room_info($r_one['room_id'], 'init');
                                // 创建IM群
                                $Im::userIdAdd($uid);
                                $Im::createLiveGroup($r_one['room_id'], $uid);
                                get_live_room_info($r_one['room_id'], 'tripartite', 1);
                            }
                        }
                        // 直播场次记录
                        $ins_site = [
                            'room_id'       => $r_one['room_id'],
                            'start_time'    => $date,
                        ];

                        // 清除无效重复直播场次
                        $LiveSite->where(['room_id'=>$r_one['room_id'],'end_time'=>'0000-00-00 00:00:00'])->delete();

                        // 添加直播场次
                        $site_id = $LiveSite->add($ins_site);

                        // 直播房间修改直播状态
                        $this->where($whe)->save(['is_status' => 1, 'recent_time' => $date]);

                        // 直播场次商品改为本场
                        if ($site_id) {
                            $slg_whe    = ['user_id' => $r_one['user_id'], 'site_id' => 0, 'type' => 'live'];
                            $slg_id     = $ShortLiveGoods->getVal($slg_whe, 'id', true);

                            if ($slg_id) {
                                $ShortLiveGoods->where(['id' => ['in', $slg_id]])->save(['site_id' => $site_id]);
                            }
                        }

                        // 清除直播间 禁言和踢出的人
                        live_room_handle_user($r_one['room_id'], 'clear');

                    // 直播断流事件
                    } elseif ($param['event_type'] == 0) {
                        // 直播房间修改直播状态
                        //$this->where($whe)->save(['is_status' => 3]);

                        // 直播场次结束
                        $last_site= $LiveSite->where($r_whe)->order('site_id desc')->getField('site_id');
                        $LiveSite->where(['site_id' => $last_site])->save([
                            'end_time'      => $date,
                            'room_heat'     => $room_info['room_heat'],
                            'praise_num'    => $room_info['praise_num'],
                            'acc_people'    => $room_info['acc_people'],
                        ]);
                        // 若该直播间存在红包没抢完情况则把红包返还给发包者
                        $red = $redModel->where($r_whe)->field('id,user_id, red_money, effective_type')->select();
                        if ($red) {
                            foreach ($red as $k => $v) {
                                $moneData = array_sum(json_decode($v['red_money'], true)) ?: 0;
                                if (!in_array($v['effective_type'], [3,4])) {
                                    $User->where(['uid' => $v['user_id']])->setInc('ll_balance', $moneData);
                                    $redModel->where(['id'=>$v['id']])->save(['effective_type' => 4,'refund'=>$moneData,'start_time'=>date("Y-m-d H:i:s")]);
                                }
                            }
                        }
                        // 清除直播间缓存
                        live_room_handle_user($r_one['room_id'], 'init');
                    // 直播录制事件
                    } elseif ($param['event_type'] == 100) {
                        // 获取直播场次
                        $ls_site = $LiveSite->where($r_whe)->order('site_id desc')->getField('site_id');

                        if ($ls_site) {
                            $ins = [
                                'user_id'       => $uid,
                                'vid'           => $param['file_id'],
                                'room_id'       => $r_one['room_id'],
                                'site_id'       => $ls_site,
                                'cover_url'     => $r_one['cover_url'],
                                'short_name'    => $r_one['room_name'],
                                'live_people'   => $room_info['acc_people'],
                                'live_heat'     => $room_info['room_heat'],
                                'live_praise'   => $room_info['praise_num'],
                                'media_url'     => $param['video_url'],
                                'is_recorded'   => 1,
                                'is_recommend'  => 0,
                            ];

                            $s_one = $Short->field('id')->where(['user_id' => $uid,'room_id'=>$r_one['room_id']])->find();
                            // 预存入录制文件
                            if ($s_one) {
                                $Short->where(['id' => $s_one['id']])->save($ins);
                            } else {
                                $Short->add($ins);
                            }

                            // 修改有直播回放状态
                            $this->where(['room_id' => $r_one['room_id']])->save(['is_put' => 'Y','is_status' => 3]);
                        }
                    }


                    // 事务提交
                    $this->commit();
                } catch(\Exception $e) {
                    // 事务回滚
                    $this->rollback();
                }
            } elseif (!$r_one && $param['event_type'] == 1 && isset($arr[2])) {
                $ah 	= $User->checkAuthority('live', $uid);  // 检查开播等级是否达到

                if ($ah['code'] == 0) {
                    $ins    = [
                        'user_id'  		=> $uid,
                        'room_name'  	=> '直播间推流检测',
                        'is_status'     => 1,
                    ];
                    $result = $this->add($ins);

                    // 创建IM群
                    if ($result) {
                        $Im::userIdAdd($uid);
                        $Im::createLiveGroup($result, $uid);
                        get_live_room_info($r_one['room_id'], 'tripartite', 1);
                    }
                }
            }
        }
    }

}
?>
