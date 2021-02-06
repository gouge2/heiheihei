<?php
/**
 * 云直播管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Controller\ImController;
use Common\Model\LivePkRecordModel;


class LiveController extends AuthController
{
    /**
     * 获取推流地址
     */
    public function getPushPullUrl()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        // 房间记录
        $uid                    = $res_token['uid'];
        $Im 	                = new \Common\Controller\ImController();
        $LiveRoom               = new \Common\Model\LiveRoomModel();
        $live_one               = $LiveRoom->field('room_id,user_id')->where(['user_id' => $uid])->find();

        if ($live_one) {
            $time               = date('Y-m-d H:i:s', ($Im::getLiveAgingTime() + $_SERVER['REQUEST_TIME']));

            // 获取推流地址
            if ($res_token['uid'] == $live_one['user_id']) {
                $live_domain    = $Im::getLiveDomain();
                $arr            = get_push_pull_url($live_domain['push'][0], $Im->getImSdkAppid() .'_'. $uid, $Im::getLiveKey(), $time);     // 推流地址

                $this->ajaxSuccess($arr);
            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_MATCHE']);
            }
        } else {
            $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
        }

        $this->ajaxError();
    }

    /**
     * 获取直播详情
     */
    public function getLiveInfo()
    {
        $token                  = trim(I('post.token'));
        $room_id                = trim(I('post.room_id'));

        if (!$room_id) {
            $this->ajaxError();
        }

        $Im 	                = new \Common\Controller\ImController();
        $model_live             = new \Common\Model\LiveRoomModel();
        $Short                  = new \Common\Model\ShortModel();
        $User                   = new \Common\Model\UserModel();

        $info                   = $model_live->where(['room_id' => $room_id])->find();

        if (empty($info)) {
            $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
        }

        $det                    = M('user_detail')->where(['user_id'=>$info['user_id']])->find();

        // 判断头像是否为第三方应用头像
        if ($det['avatar'] && !is_url($det['avatar'])) {
            $det['avatar']      = WEB_URL . $det['avatar'];
        }
        $info                   = array_merge($info, $det);

        $ad_tag                 = $info['is_status'] == 2 ? true : false;      // 假直播标识

        // 房间封面空用头像
        $info['cover_url']      = empty($info['cover_url']) ? $det['avatar'] : (!is_url($info['cover_url']) ? WEB_URL . $info['cover_url'] : $info['cover_url']);

        // 后台假直播数据列表
        $ad_list                = $Short->getAdList(['user_id' => $info['user_id']]);

        $room_info              = get_live_room_info($info['room_id']);
        // 假直播地址
        if ($ad_tag) {
            $info['pull']       = ['pull_rtmp' => '', 'pull_flv' => '', 'pull_m3u8' => ''];

            if (isset($ad_list[$room_id])) {
                $info['pull']   = [
                    'pull_rtmp' => $ad_list[$room_id]['media_url'],
                    'pull_flv'  => $ad_list[$room_id]['media_url'],
                    'pull_m3u8' => $ad_list[$room_id]['media_url'],
                ];
            }

        // 拉流信息
        } else {
            $time               = date('Y-m-d H:i:s', ($Im->getLiveAgingTime() + $_SERVER['REQUEST_TIME']));
            $live_domain        = $Im->getLiveDomain();
            $pull_user = $info['user_id'];
            if (isset($room_info['tripartite']) && $room_info['tripartite'] == 1) {
                $pull_user = $info['user_id'].'_t';
            }
            $info['pull']       = get_push_pull_url($live_domain['pull'][0], $Im->getImSdkAppid() .'_'. $pull_user, $Im->getLivekey(), $time, false);
        }

        // 关注标识
        $info['concern_iden']   = 0;
        $uid                    = $User->getUserId($token);
        if ($uid) {
            $concern_info           = M('user_concern')->where(['user_id'=>$uid, 'by_id'=>$info['user_id']])->find();
            $info['concern_iden']   = empty($concern_info) ? 0 : 1;
        }

        $redModel = new \Common\Model\LiveRedModel();
        $red = $redModel->where(['room_id' => $room_id])->field('start_time,user_id,red_money,id,effective_type')->select();
        if ($red) {
            foreach ($red as $k => $v) {
                if ($v['start_time'] > date('Y-m-d H:i:s')) {
                    if ($v['effective_type'] == 1) {
                        $info['live_red'] = 1;
                    }
                } else {
                    $moneData = array_sum(json_decode($v['red_money'], true)) ?: 0;
                    if (!in_array($v['effective_type'], [3, 4])) {
                        $User->where(['uid' => $v['user_id']])->setInc('ll_balance', $moneData);
                        $redModel->where(['id'=>$v['id']])->save(['effective_type' => 3, 'refund' => $moneData, 'start_time' => date("Y-m-d H:i:s")]);
                    }
                }

            }
        }

        $LivePkRecord = new \Common\Model\LivePkRecordModel();
        $livemap                 = [
            '_complex'  => [
                'is_status'      => array('in', [1, 3, 4, 5, 6]),
                'room_id'        => $room_id
            ],
            '_logic'    => 'or',
            '_string'   => " `other_room` = {$room_id} and `is_status` IN (1, 3, 4, 5, 6)",
        ];
        $pklist = $LivePkRecord->where($livemap)->field('id,money,other_money,end_time,is_status,other_uid,user_id,room_id')->order('id desc')->find();

        if ($pklist) {
            $info['other_userid'] = '';
            if (in_array($pklist['is_status'],[1,3])) {
                if ($pklist['room_id'] == $room_id) {
                    $info['pkmoney'] = $pklist['money'];
                    $info['right_gift'] = $pklist['other_money'];
                    $info['other_userid'] = $pklist['other_uid'];
                } else {
                    $info['pkmoney'] = $pklist['other_money'];
                    $info['right_gift'] = $pklist['money'];
                    $info['other_userid'] = $pklist['user_id'];
                }
                $info['time_left'] = intval(strtotime($pklist['end_time']) - strtotime(date('Y-m-d H:i:s')));
                if ($info['time_left'] <= 0) {
                    $info['time_left'] = 0;
                }
            } elseif (in_array($pklist['is_status'],[4,5,6])) {
                if ($pklist['room_id'] == $room_id) {
                    $info['other_userid'] = $pklist['other_uid'];
                } else {
                    $info['other_userid'] = $pklist['user_id'];
                }
            }
        }
        
        $handle                 = live_room_handle_user($info['room_id']);
        $info['people']         = (int)$room_info['people'];
        $info['praise_num']     = (int)$room_info['praise_num'];
        $info['room_heat']      = (int)$room_info['room_heat'];
        $info['is_action']      = $room_info['is_action'];
        $info['is_live']        = 'Y';
        $info['is_fake']        = $ad_tag ? 'Y' : 'N';                          // 假直播标识
        $info['is_open']        = (int)$room_info['is_open'];                   // 主播是否开启允许连麦
        $info['is_mute']        = in_array($uid, $handle['mute_arr']) ? 1 : 0;  // 是否被禁言
        $info['is_kikc']        = in_array($uid, $handle['kikc_arr']) ? 1 : 0;  // 是否被移出房间
        $info['tripartite']     = (int)$room_info['tripartite'];
        if ($pklist && $info['is_status'] == 1 ) {
            if ($pklist['is_status'] == 1) {
                $info['is_action'] = 6;
            } else if ($pklist['is_status'] == 3 &&  $info['time_left'] > 0) {
                $info['is_action'] = 7;
            } else {
                $info['is_action'] = $room_info['is_action'];
            }
        } else {
            $info['is_action'] = $room_info['is_action'];
        }

        $this->ajaxSuccess($info);
    }

    /**
     * 获取直播推荐Banner
     */
    public function getLiveBanner()
    {
        $platform           = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if (IS_POST) {
            $Banner         = new \Common\Model\BannerModel();
            $list           = $Banner->getBannerList(31, 'Y', 0, 'title,img,href,color');

            if ($list) {
                foreach ($list as $key => $val) {
                    // 商品图片地址拼接
                    if ($val['img']) {
                        $list[$key]['img']  = is_url($list[$key]['img']) ? $list[$key]['img'] : WEB_URL . $list[$key]['img'];
                    }
                }
            }

            $this->ajaxSuccess(['list' => ($list ? $list : [])]);
        }

        $this->ajaxError();
    }

    /**
     * 直播分类列表
     */
    public function getCatList()
    {
        $platform           = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if (IS_POST) {
            $LiveCat        = new \Common\Model\LiveCatModel();
            $list           = $LiveCat->field('cat_id,cat_name')->where(['is_status' => 1])->order('sort desc')->select();

            // 首位插入全部
            array_unshift($list, [
                'cat_id'    => 'all',
                'cat_name'  => '全部',
            ]);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 直播推荐列表
     */
    public function getRecommendList()
    {
        $token                   = trim(I('post.token'));
        $cat                     = trim(I('post.cat'));                   // 分类   默认全部分类  all：全部
        $platform                = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $t                       = I('post.t/d');                         // 搜索时间
        $limit                   = I('post.limit/d', self::$limit);
        $page                    = I('post.page/d', self::$page);

        if (IS_POST) {
            $LiveRoom            = new \Common\Model\LiveRoomModel();
            $User                = new \Common\Model\UserModel();
            $Short               = new \Common\Model\ShortModel();

            // 获取用户标识
            $uid                 = $User->getUserId($token);

            // 1、查询条件
            $rtime               = $t ? date("Y-m-d H:i:s", $t) : 0;
            if (!empty($cat) && $cat != 'all') {
                $whe                 = [
                    '_complex'  => [
                        'is_status'     => 1,
                        'cat_id'        => $cat
                    ],
                    '_logic'    => 'or',
                    '_string'   => "`is_status` in (2, 3, 4) and `cat_id` = $cat",
                ];
                $whe['cat_id'] = $cat;
            } else {
                $whe                 = [
                    '_complex'  => [
                        'is_status'     => 1
                    ],
                    '_logic'    => 'or',
                    '_string'   => " `is_put` = 'Y' and `is_status` in (3, 4) ",
                ];
                // 加入假直播
                $whe['is_status']= 2;
            }

            // 加入时间搜索
            if ($rtime) {
                $whe['_complex']['recent_time'] = ['elt', $rtime];
            }


            $resList             = [];
            $list                = $LiveRoom->getListData($whe, $uid, $limit, $page, $platform);
            $resList             = $list['live'] ? $list['live'] : [];

            // 2、查询回放
            if (isset($list['record']) && count($list['record']) > 0) {
                $userList        = [];

                foreach ($list['record'] as $key => $val) {
                    array_push($userList, $key);
                }

                $ids             = $Short->getUserNewestRecord($userList);

                if (count($ids) > 0) {
                    $recordList = $list['record'];
                    $list       = $Short->getRecordList(['id' => ['in', $ids]], $uid, self::$limit, self::$page, $platform, 'self_top desc,id desc');

                    foreach ($list as $key => $val) {
                        $list[$key]['room_id'] = $recordList[$val['user_id']];
                    }

                    $resList    = array_merge($resList, $list);
                }
            }

            $this->ajaxSuccess(['list' => $resList]);
        }

        $this->ajaxError();
    }

    // 通过回放id获取回放
    public function getRecordById()
    {
        $token                   = trim(I('post.token'));
        $id                      = trim(I('post.id'));                   // 回放id
        $platform                = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($id) {
            $User               = new \Common\Model\UserModel();
            $Short              = new \Common\Model\ShortModel();

            // 获取用户标识
            $uid                = $User->getUserId($token);

            $list               = $Short->getRecordList(['id' => ['in', $id]], $uid, self::$limit, self::$page, $platform, 'self_top desc,id desc');

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 房间封面或实名认证图上传
     */
    public function roomCoverUpload()
    {
        $type                    = trim(I('post.type', ''));        // 图片类型 room:房间封面图 real:认证
        $platform                = trim(I('post.platform'));        // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        // 上传文件
        if (!empty($_FILES['cover_url']['name'])) {
            $config = [
                'mimes'         =>  [],   //允许上传的文件MiMe类型
                'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                'exts'          =>  ['jpg', 'gif', 'png', 'jpeg'], //允许上传的文件后缀
                'rootPath'      =>  './Public/Upload/'. ($type == 'real' ? 'Real' : 'Room') .'/', //保存根路径
                'savePath'      =>  '', //保存路径
                'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
            ];

            $upload = new \Think\Upload($config);
            $info   = $upload->uploadOne($_FILES['cover_url']);   // 上传单个文件

            if (!$info) {
                // 上传错误提示错误信息
                $this->ajaxError($this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'], $upload->getError());
            } else {
                // 上传成功  文件完成路径
                $filepath       = $config['rootPath'] . $info['savepath'] . $info['savename'];

                // 缓存图片路径
                $name           = 'l'. $_SERVER['REQUEST_TIME'] . mt_rand(100, 9999999);
                S($name, $filepath);

                // 保存缓存以便删除多余的图片
                $room_cover = S('room_cover');
                $room_cover[] = $filepath;
                S('room_cover', $room_cover);

                $res['cover_url']       = $name;
                $res['show_cover_url']  = '';

                $this->ajaxSuccess($res);
            }
        }

        $this->ajaxError();
    }

    /**
     * 直播选商品/修改封面与房间名称
     */
    public function bindingGoods()
    {
        $goods_list              = I('post.goods_list');             // 商品列表
        $room_name               = trim(I('post.room_name'));        // 房间名称
        $cover                   = trim(I('post.cover_url'));        // 房间封面路径
        $platform                = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $catid                   = trim(I('post.cat_id'));

        // ios端数据单独处理
        if ($platform == 'ios'  && $goods_list) {
            $data       = [];
            foreach ($goods_list as $key => $val) {
                foreach ($val as $k => $v) {
                    $data[]  = ['from' => $k, 'goods_id' => $v];
                }
            }
            unset($goods_list);
            $goods_list = $data;
        }

        if ($room_name) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $uid            = $res_token['uid'];

            // 获取缓存图片路径
            $cover          = S($cover);
            $room_cover     = S('room_cover');
            $cover_url      = substr($cover, 1);

            // 查询房间号是否存在
            $whe            = ['user_id' => $uid];
            $UserDetail     = new \Common\Model\UserDetailModel();
            $LiveRoom       = new \Common\Model\LiveRoomModel();
            $l_one          = $LiveRoom->field('room_id,cover_url')->where($whe)->find();

            if ($l_one) {
                $Im 	    = new \Common\Controller\ImController();

                $list       = [];
                $list_tag   = [];                               // 避免多次记录

                $update     = ['room_name' => $room_name];      // 房间修改的数组

                // 房间封面
                if ($cover_url) {
                    $update['cover_url']    = $cover_url;
                } else {
                    $ud                     = $UserDetail->getUserDetailMsg($uid);
                    if ($ud ) {
                        $update['cover_url']= $ud['avatar'];
                    }
                }
                if ($catid) {
                    $update['cat_id'] = $catid;
                }

                // 商品数组
                if ($goods_list && is_array($goods_list)) {
                    foreach ($goods_list as $key => $val) {
                        if (isset($val['goods_id']) && isset($val['from'])) {
                            $goods_id   = trim($val['goods_id']);
                            $from       = trim($val['from']);
                            $tem        = [$goods_id, $from];

                            if ($goods_id && $from && !in_array($tem, $list_tag)) {
                                $list[] = [
                                    'goods_id'  => $goods_id,
                                    'user_id'   => $uid,
                                    'from'      => $from,
                                    'type'      => 'live',
                                    'add_time'  => date('Y-m-d H:i:s'),
                                ];

                                $list_tag[]     = $tem;
                            }
                        }
                    }
                }


                $ShortLiveGoods     = new \Common\Model\ShortLiveGoodsModel();

                $ShortLiveGoods->startTrans();   // 启用事务
                try {
                    // 记录商品关联信息
                    if ($list) {
                        $ShortLiveGoods->addAll($list);
                    }

                    // 修改房间信息
                    $LiveRoom->where($whe)->save($update);

                    // 事务提交
                    $ShortLiveGoods->commit();

                    // 删除缓存中多余的图片
                    if ($room_cover) {
                        foreach ($room_cover as $v) {
                            if ($cover != $v) {
                                @unlink($v);
                            }
                        }
                        S('room_cover', null);
                    }

                    S($cover, null);

                    // 设置房间信息
                    get_live_room_info($l_one['room_id'], 'init');

                    // 解散后台假直播开启的IM群
                    $Im::destroyLiveGroup($l_one['room_id']);

                    $this->ajaxSuccess();

                } catch(\Exception $e) {
                    // 事务回滚
                    $ShortLiveGoods->rollback();

                    $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                }

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 进入直播间获取本场讲解的商品列表
     */
    public function theLiveGoods()
    {
        $room_id                 = trim(I('post.room_id'));          // 房间号
        $platform                = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($room_id) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 查询房间号是否存在
            $whe                            = ['room_id' => $room_id];
            $LiveRoom                       = new \Common\Model\LiveRoomModel();
            $lr_one                         = $LiveRoom->field('room_id,user_id,is_status,room_name')->where($whe)->find();

            if ($lr_one) {
                $date                       = date('Y-m-d H:i:s');
                $list                       = [];
                $ShortLiveGoods             = new \Common\Model\ShortLiveGoodsModel();

                // 用户直播中 才找商品
                if ($lr_one['is_status'] == 1) {
                    $LiveSite               = new \Common\Model\LiveSiteModel();
                    $ls_one                 = $LiveSite->where(['start_time' => ['elt', $date], 'end_time' => '0000-00-00 00:00:00'])->where($whe)->order('site_id desc')->getField('site_id');

                    $slg_whe                = ['site_id' => ($ls_one ? $ls_one : 0), 'user_id' => $lr_one['user_id'], 'type' => 'live'];
                    $list                   = $ShortLiveGoods->getGoodsData('the', $slg_whe, $res_token['uid'], null, null, $platform);

                // 假直播查找设置的商品
                } elseif ($lr_one['is_status'] == 2) {
                    $f_whe                  = ['user_id' => $lr_one['user_id'], 'type' => 'fake'];
                    $list                   = $ShortLiveGoods->getGoodsData('the', $f_whe, $res_token['uid'], null, null, $platform);
                }

                $res['list']                = $list;
                $res['room_info']           = get_live_room_info($lr_one['room_id'], null);   // 房间信息
                
                $this->ajaxSuccess($res);

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取直播间礼物列表
     */
    public function getGiftList()
    {
        $token            = trim(I('post.token'));
        $platform         = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if (IS_POST) {
            $User         = new \Common\Model\UserModel();
            $Gift         = new \Common\Model\GiftModel();

            $ll_balance   = $token ? $User->where("token='{$token}'")->getField('ll_balance') : 0;
            $list         = $Gift->getAllList();

            // 其他信息
            $res          = [
                'list'      => $list,
                'other'     => [
                    'll_cn'         => GIFT_MONEY_CN,
                    'll_balance'    => (int)$ll_balance,
                ]
            ];

            $this->ajaxSuccess($res);
        }

        $this->ajaxError();
    }

    /**
     * 获取进入直播间的公告
     */
    public function getLiveNotice()
    {
        $token                   = trim(I('post.token'));
        $room_id                 = trim(I('post.room_id'));          // 房间号
        $platform                = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($room_id) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 查询房间号是否存在
            $whe                            = ['room_id' => $room_id];
            $LiveRoom                       = new \Common\Model\LiveRoomModel();
            $lr_one                         = $LiveRoom->field('room_id,user_id')->where($whe)->find();

            if ($lr_one) {
                $Im 	                    = new \Common\Controller\ImController();

                if ($res_token['uid'] == $lr_one['user_id']) {
                    $str = LIVE_HINT_HOST;
                } else {
                    $str = LIVE_HINT_USER;
                }

                $this->ajaxSuccess(['notice' => $str]);

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 主播获取本场直播收到礼物列表
     */
    public function incomeGiftList()
    {
        $room_id            = trim(I('post.room_id'));          // 房间号
        $site_id            = trim(I('post.site_id'));          // 场次号
        $platform           = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $type               = trim(I('post.type', 0));             // 类型 0: 本场礼物  1:指定场次
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $GiftGive           = new \Common\Model\GiftGiveModel();
        $list               = [];

        if ($type == 0 && $room_id) {
            $list           = $GiftGive->getIncomeList($res_token['uid'], $room_id, $limit, $page);
        } elseif ($type == 1 && $site_id) {
            $list           = $GiftGive->getIncomeWithSiteId($site_id, $limit, $page);
        }

        $this->ajaxSuccess(['list' => $list ? $list : []]);
    }

    /**
     * 直播间送礼物
     */
    public function deductGiftCost()
    {
        $room_id                 = trim(I('post.room_id'));          // 房间号
        $gift_id                 = I('post.gift_id/d');              // 礼物标识
        $gift_num                = I('post.gift_num/d');             // 礼物数量
        $platform                = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($room_id && $gift_id && $gift_num) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $GiftGive       = new \Common\Model\GiftGiveModel();
            $data           = ['gift_id' => $gift_id, 'gift_num' => $gift_num];
            $result         = $GiftGive->giftCallback($res_token['uid'], $room_id, $data);

            if ($result['code'] == 'succ') {
                $this->ajaxSuccess(['ll_balance' => $result['msg']]);
            } else {
                if ($result['code'] == 'gift_not') {    // 礼物不存在
                    $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_GIFT']);
                }

                if ($result['code'] == 'live_room_not') {    // 房间不存在
                    $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
                }

                if ($result['code'] == 'money_not') {    // 余额不足
                    $this->ajaxError(['ERROR_CODE_USER' => 'BALANCE_INSUFFICIENT']);
                }
            }
        }

        $this->ajaxError();
    }

    /**
     * 直播间结束信息
     */
    public function endLiveInfo()
    {
        $room_id                 = trim(I('post.room_id'));          // 房间号
        $platform                = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($room_id) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $uid                = $res_token['uid'];

            // 查询直播间
            $LiveRoom           = new \Common\Model\LiveRoomModel();
            $LiveSite           = new \Common\Model\LiveSiteModel();
            $UserConcern        = new \Common\Model\UserConcernModel();
            $GiftGive           = new \Common\Model\GiftGiveModel();

            $l_one              = $LiveRoom->field('room_id,user_id')->where(['user_id' => $uid , 'room_id' => $room_id])->find();
            $ls_one             = $LiveSite->field('site_id,start_time')->where(['room_id' => $l_one['room_id']])->order('site_id desc')->find();

            if ($l_one) {
                // 房间信息
                $room_info      = get_live_room_info($l_one['room_id']);
                $live_time      = isset($ls_one['start_time']) ? ($_SERVER['REQUEST_TIME'] - strtotime($ls_one['start_time'])) : $_SERVER['REQUEST_TIME'];

                // 新增粉丝
                $new_fans       = $UserConcern->where(['by_id' => $uid, 'add_time' => [['elt', date('Y-m-d H:i:s')], ['egt', $ls_one['start_time']]]])->count();

                // 奖励信息
                $d_ratio        = GIFT_D_RATIO;         // 鹿角转来鹿币比例
                $cost           = GIFT_COST;            // 平台扣费百分比
                $give_whe       = ['host_id' => $uid, 'site_id' => $ls_one['site_id'], 'is_status' => 'succ'];
                $give_money     = $GiftGive->where($give_whe)->sum('money');

                $give_award     = $give_money ? (string)($give_money * (1 - $cost * 0.01) * $d_ratio * 0.01) : '0';
                $trad_volume    =  '0';

                $res            = [
                    'live_time'     => date('H:i:s', $live_time),               // 直播时长
                    'acc_people'    => $room_info['acc_people'],                // 累计人数
                    'give_award'    => $give_award,                             // 打赏奖励
                    'tall_heat'     => $room_info['room_heat'],                 // 最高热度
                    'new_fans'      => $new_fans ? (string)$new_fans : '0',     // 新增粉丝
                    'trad_volume'   => $trad_volume,                            // 成交金额
                    'acc_praise'    => $room_info['praise_num'],                // 累计点赞数
                ];

                $this->ajaxSuccess($res);

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取充值选项
     */
    public function getFillList()
    {
        $token              = trim(I('post.token'));
        $platform           = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if (IS_POST) {
            $User           = new \Common\Model\UserModel();
            $Fill           = new \Common\Model\FillModel();
            $field          = $platform == 'ios' ? 'apple_id as fill_id,apple_deduct as deduct,apple_redeem as redeem' : 'fill_id,deduct,redeem';
            $list           = $Fill->field($field)->select();

            if ($list) {
                foreach ($list as $key => $val) {
                    $list[$key]['deduct'] = (string)($val['deduct'] * 0.01);
                }
            }

            // 鹿角余额
            $ll_balance     = $token ? $User->where("token='{$token}'")->getField('ll_balance') : 0;

            $res            = ['other' => ['ll_cn' => GIFT_MONEY_CN, 'll_balance' => (int)$ll_balance], 'list' => $list];
            $this->ajaxSuccess($res);
        }

        $this->ajaxError();
    }

    /**
     * 立即充值下单
     */
    public function llOrder()
    {
        $ll_num                 = I('post.ll_num/d');
        $platform               = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($ll_num) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $Fill               = new \Common\Model\FillModel();
            $FillRecord         = new \Common\Model\FillRecordModel();
            $Order              = new \Common\Model\OrderModel();
            $order_num          = $Order->generateOrderNum();  // 订单号

            $deduct             = $Fill->where(['redeem' => $ll_num])->getField('deduct');

            $ins = [
                'fill_num'  => $order_num,
                'user_id'   => $res_token['uid'],
                'deduct'    => (int)($platform == 'ios' ? 0 : ($deduct ? $deduct : (($ll_num / 10) * 100))),
                'redeem'    => $ll_num,
                'is_status' => 'not',
                'add_time'  => date('Y-m-d H:i:s'),
            ];

            $order_id = $FillRecord->add($ins);

            if ($order_id !== false) {
                $this->ajaxSuccess(['order_num' => $order_num]);
            } else {
                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 充值之后获取表单支付
     */
    public function llPayFrom()
    {
        $pay_method             = trim(I('post.pay_method'));
        $order_num              = trim(I('post.order_num'));
        $platform               = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($pay_method && $order_num) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $FillRecord         = new \Common\Model\FillRecordModel();
            $fr_one             = $FillRecord->field('id,fill_num,deduct,redeem')->where(['fill_num' => $order_num, 'user_id' => $res_token['uid'], 'is_status' => 'not'])->find();

            if ($fr_one) {
                $pay_str        = '';
                $title          = GIFT_MONEY_CN .'充值';
                $pay_price      = $fr_one['deduct'] * 1;

                if ($pay_method == 'wxpay') {
                    // 获取微信支付表单数据
                    Vendor('pay.wxpay','','.class.php');
                    $wxpay              = new \wxpay();
                    $body               = $title;
                    $out_trade_no       = 'll_'. $order_num; // 订单号
                    $total_fee          = $pay_price * 1;  //订单费用，精确到分
                    $notify_url         = WEB_URL .'/app.php/WxNotify/notify_app';
                    $AppParameters      = $wxpay->GetAppParameters($body, $out_trade_no, $total_fee, $notify_url);
                    $pay_str            = $AppParameters;

                // 获取支付宝请求参数
                } elseif ($pay_method == 'alipay') {
                    Vendor('pay.alipayApp','','.class.php');
                    $alipayApp          = new \alipayApp();
                    $body               = $title;  // 订单描述
                    $subject            = $title;  // 订单名称，必填
                    $out_trade_no       = 'll_'. $order_num; // 订单号
                    $total_amount       = $pay_price * 0.01;   // 付款金额，必填
                    $alipay_parameters  = $alipayApp->GetParameters($body, $subject, $out_trade_no, $total_amount);
                    $pay_str            = $alipay_parameters;

                // 余额支付
                } elseif ($pay_method == 'balance' || $pay_method == 'banlance') {
                    $UserBalanceRecord  = new \Common\Model\UserBalanceRecordModel();

                    // 判断用户余额是否足够
                    $userMsg            = $User->getUserMsg($res_token['uid']);

                    if ($userMsg['balance'] >= ($pay_price * 0.01)) {

                        $User->startTrans();   // 启用事务
                        try {
                            // 余额减
                            $User->where(['uid' => $res_token['uid']])->setDec('balance', ($pay_price * 0.01));

                            // 鹿角加
                            $User->where(['uid' => $res_token['uid']])->setInc('ll_balance', $fr_one['redeem']);

                            // 支付修改
                            $FillRecord->where(['id' => $fr_one['id']])->save(['pay_method' => 'balance', 'is_status' => 'succ', 'pay_time' => date('Y-m-d H:i:s')]);

                            // 余额日志记录
                            $UserBalanceRecord->addLog($res_token['uid'], ($pay_price * 0.01), ($userMsg['balance'] - ($pay_price * 0.01)), 'll_add');

                            // 事务提交
                            $User->commit();

                            $pay_str    = 'bal';

                        } catch(\Exception $e) {
                            // 事务回滚
                            $User->rollback();

                            // 数据库错误
                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }
                    } else {
                        // 余额不足
                        $this->ajaxError(['ERROR_CODE_USER' => 'BALANCE_INSUFFICIENT']);
                    }
                } elseif ($pay_method == 'int_wx' || $pay_method == 'int_ali') {
                    $latipay = new LatipayController();
                    $out_trade_no       = 'll_'.$order_num; // 订单号
                    $total_amount       = $pay_price * 0.01;   // 付款金额，必填
                    $alipay_parameters  = $latipay->pay($out_trade_no, $total_amount,$pay_method,2);
                    $pay_parameters = $alipay_parameters['host_url'].'/'.$alipay_parameters['nonce'];
                    $pay_str            = $pay_parameters;
                } elseif ($pay_method == 'paypal') {
                    $paypal = new PayPaiController();
                    $out_trade_no       = 'll_'.$order_num; // 订单号
                    $total_amount       = $pay_price * 0.01;   // 付款金额，必填
                    $alipay_parameters  = $paypal->pay($out_trade_no, $total_amount,$pay_method,2);
                    $pay_str            = $alipay_parameters;
                }

                if ($pay_str) {
                    $this->ajaxSuccess(['pay_parameters' => ($pay_str == 'bal' ? '' : $pay_str)]);
                }

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_Fill_RECORD']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 验证苹果支付
     */
    public function verifyApplePay()
    {
        $receipt            = trim(I('post.receipt_data'));
        $order_num          = trim(I('post.order_num'));

        if ($receipt && $order_num) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $Fill               = new \Common\Model\FillModel();
            $FillRecord         = new \Common\Model\FillRecordModel();
            $fr_one             = $FillRecord->field('id')->where(['fill_num' => $order_num, 'user_id' => $res_token['uid'], 'is_status' => 'not'])->find();

            if ($fr_one) {

                // 苹果验证字符串
                if ($receipt) {
                    $apple_secret 	= '47cfb1c52b404ee4a1da9f38ccb57b06';
                    $data 		    = json_encode(['receipt-data' => $receipt, 'password' => $apple_secret]);

                    if (0) {
                        $url 	    = "https://buy.itunes.apple.com/verifyReceipt";         // 正式环境
                    } else {
                        $url  	    = "https://sandbox.itunes.apple.com/verifyReceipt";     // 沙盒环境
                    }

                    $result         = json_decode(https_request($url, $data), true);

                    // 验证成功处理订单
                    if (isset($result['status']) && $result['status'] == 0) {
                        $apple_id = isset($result['receipt']['in_app'][0]['product_id']) ? $result['receipt']['in_app'][0]['product_id'] : 0;
                        $f_one    = $Fill->field('apple_deduct,apple_redeem')->where(['apple_id' => $apple_id])->find();

                        if ($f_one) {

                            $User->startTrans();   // 启用事务
                            try {
                                // 鹿角加
                                $User->where(['uid' => $res_token['uid']])->setInc('ll_balance', $f_one['apple_redeem']);

                                // 支付修改
                                $FillRecord->where(['id' => $fr_one['id']])->save([
                                    'deduct'        => $f_one['apple_deduct'],
                                    'redeem'        => $f_one['apple_redeem'],
                                    'pay_method'    => 'apple',
                                    'is_status'     => 'succ',
                                    'pay_time'      => date('Y-m-d H:i:s')
                                ]);

                                // 当前鹿角余额
                                $ll_balance  = (int)$User->where(['uid' => $res_token['uid']])->getField('ll_balance');

                                // 事务提交
                                $User->commit();

                                $this->ajaxSuccess(['ll_balance' => $ll_balance]);

                            } catch(\Exception $e) {
                                // 事务回滚
                                $User->rollback();

                                // 数据库错误
                                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                            }
                        } else {
                            $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_Fill']);
                        }
                    }
                }

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_Fill_RECORD']);
            }
        }

        $this->ajacError();
    }

    /**
     * 连麦混流
     */
    public function linkMixedFlow()
    {
        $room_id            = I('post.room_id/d');             // 连麦的房间ID
        $link_id            = I('post.link_id/d');             // 对方的用户ID
        $type               = trim(I('post.type'));            // link:连麦  cancel：取消混流
        $platform           = trim(I('post.platform'));        // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($link_id && $room_id && $type && in_array($type, ['link', 'cancel'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $Tencent        = new \Common\Controller\TencentController();
            $LiveRoom       = new \Common\Model\LiveRoomModel();

            // 主播ID和用户ID
            $host_id        = $uid = 0;

            // 确定主播ID和用户ID
            $whe            = ['room_id' => $room_id, 'is_status' => 1];
            $h_room         = $LiveRoom->where(['user_id' => $res_token['uid']])->where($whe)->getField('room_id');

            if ($h_room) {
                $host_id    = (int)$res_token['uid'];
                $uid        = $link_id;

            } else {
                $u_room     = $LiveRoom->where(['user_id' => $link_id])->where($whe)->getField('room_id');

                if ($u_room) {
                    $host_id= $link_id;
                    $uid    = (int)$res_token['uid'];
                }
            }


            if ($host_id && $uid) {
                // 连麦
                if ($type == 'link') {
                    // 本房间主播才可以调起混流
                    if ($host_id == $res_token['uid']) {
                        $res= $Tencent::customWheat($host_id, $uid);
                    }

                // 取消混流
                } else {
                    $res    = $Tencent::cancelMixture($host_id, $uid);
                }

                if (isset($res) && $res == 'ok') {
                    $this->ajaxSuccess();

                // 混流失败
                } else {
                    $this->ajaxError($this->ERROR_CODE_LIVE['MIXED_FLOW_FAIL'], $this->ERROR_CODE_LIVE_ZH[$this->ERROR_CODE_LIVE['MIXED_FLOW_FAIL']],  $res);
                }
            }
        }

        $this->ajaxError();
    }

    /**
     * PK混流
     */
    public function pkMixedFlow()
    {
        $link_id            = I('post.link_id/d');             // 对方的用户ID
        $type               = trim(I('post.type'));            // pk:主播PK  cancel：取消混流
        $platform           = trim(I('post.platform'));        // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($link_id && $type && in_array($type, ['pk', 'cancel'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $Tencent        = new \Common\Controller\TencentController();

            // pk
            if ($type == 'pk') {
                $res        = $Tencent::hostPk($res_token['uid'], $link_id);

            // 取消混流
            } else {
                $res        = $Tencent::cancelMixture($res_token['uid'], $link_id);
            }

            if ($res == 'ok') {
                $this->ajaxSuccess();

            // 混流失败
            } else {
                $this->ajaxError($this->ERROR_CODE_LIVE['MIXED_FLOW_FAIL'], $this->ERROR_CODE_LIVE_ZH[$this->ERROR_CODE_LIVE['MIXED_FLOW_FAIL']],  $res);
            }
        }

        $this->ajaxError();
    }

    /**
     * 邀请连麦主播列表
     */
    public function inviteHostList()
    {
        $platform           = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        if (IS_POST) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $LiveRoom       = new \Common\Model\LiveRoomModel();
            $lr_whe         = ['user_id' => ['neq', $res_token['uid']]];
            $list           = $LiveRoom->getLiveList($lr_whe, $limit, $page);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * pk进度条
     * @param $roomid
     * @param $host_id
     * @param $type
     */
    public function pkprogressbar($roomId, $hostId, $type)
    {
        $Im 	      = new \Common\Controller\ImController();
        $LivePkRecord = new \Common\Model\LivePkRecordModel();
        $LiveRoom     = new \Common\Model\LiveRoomModel();

        $uid = $host_id =  $pkroomid = '';
        $roomid = $roomId;

        // pk状态
        if ($type == 1) {
            $roomid = $LiveRoom->where(['user_id'=>$roomId, 'is_status'=>1])->getField('room_id');
            $pkroomid = $LiveRoom->where(['user_id'=>$hostId, 'is_status'=>1])->getField('room_id');
            $uid = $roomId;
            $host_id = $hostId;
        }

        // 连麦状态
        if ($type == 3) {
            $pkroomid = $LiveRoom->where(['user_id'=>$hostId, 'is_status'=>1])->getField('room_id');
            $uid = $LiveRoom->where(['room_id'=>$roomId, 'is_status'=>1])->getField('user_id');
            $host_id = $hostId;
        }

        // 获取两个pk房间鹿角记录
        $data = $LivePkRecord->getPkList($uid, $host_id, $roomid, $pkroomid, $type) ?: '';

        if ($data) {
            $listdata = [
                'nowRoom' => $data['money'],
                'nextRoom' => $data['other_money'],
            ];
            if ($type != 3) {
                // 给当前房间发送鹿角记录消息
                $Im->sendGroupMsg($data['room_id'],'live_pk_gift', $listdata);

                // 给pk房间发送鹿角记录消息
                $Im->sendGroupMsg($data['other_room'],'live_pk_gift', $listdata);
            }
        }
    }

    // 直播pk结束
    public function pkresult()
    {
        $room_id            = I('post.room_id/d');             // 连麦的房间ID
        $type               = I('post.status/d');
        $LivePkRecord = new LivePkRecordModel();
        $room_id ?: $this->ajaxError('','请传入房间号');
        // 查询pk或者惩罚中的信息
        $where                 = [
            '_complex'  => [
                'room_id'        => $room_id
            ],
            '_logic'    => 'or',
            '_string'   => " `other_room` = {$room_id}",
        ];
        $pkList = $LivePkRecord->where($where)->field('id,money,other_money,end_time,room_id,other_room,is_status')->order('id desc')->find();
        //达到或者超过pk设定时间且还在pk状态的房间得出pk结果
        if (in_array($pkList['is_status'], [1, 2, 3, 4]) && !$type) {
            $result = $pkList['money'] - $pkList['other_money'];
            if ($result > 0) {
                if ( $room_id == $pkList['room_id']) {
                    $result = 1;
                } else {
                    $result = 2;
                }
            } elseif ($result < 0) {
                if ($room_id == $pkList['room_id']) {
                    $result = 2;
                } else {
                    $result = 1;
                }
            } else {
                $result = 3;    //平局
                $LivePkRecord->where(['id' => $pkList['id']])->save(['is_status' => 4]);
            }
            // pk结果
            if ($pkList['is_status'] == 1 ) {
                // pk惩罚
                if ($result != 3) {
                    $LivePkRecord->where(['id' => $pkList['id']])->save(['is_status' => 3, 'end_time' => date('Y-m-d H:i:s', strtotime('+5minute'))]);
                }
            }

            // 结果信息整理
            $listData = [
                'nowRoom' => $pkList['money'],
                'nextRoom' => $pkList['other_money'],
                'result' => $result
            ];
            if ($pkList['other_room'] == $room_id) {
                $listData = [
                    'nowRoom' => $pkList['other_money'],
                    'nextRoom' => $pkList['money'],
                    'result' => $result
                ];
            }
            $this->ajaxSuccess($listData);
        } else if ($type) {
            // pk惩罚结束
            $LivePkRecord->where(['id' => $pkList['id']])->save(['is_status' => 5]);
            // 结果信息整理
            $listData = [
                'nowRoom' => $pkList['money'],
                'nextRoom' => $pkList['other_money'],
                'result' => 5
            ];
            if ($pkList['other_room'] == $room_id) {
                $listData = [
                    'nowRoom' => $pkList['other_money'],
                    'nextRoom' => $pkList['money'],
                    'result' => 5
                ];
            }
            $this->ajaxSuccess($listData);
        } else {
            $this->ajaxError();
        }
    }
}
?>
