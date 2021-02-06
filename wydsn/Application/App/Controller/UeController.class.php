<?php
/**
 * 直播/短视频用户管理
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UeController extends AuthController
{
    /**
     * 关注/取关
     */
    public function handleConcern()
    {
        // 获取参数
        $by_id     = I('post.by_id/d');          // 被关注人ID
        $type      = I('post.type/d');           // 操作类型 1关注 2取关

        if ($by_id) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 获取用户标识
            $uid            = $res_token['uid'];

            if ($uid != $by_id) {
                if (in_array($type, [1,2])) {
                    // 查询被关注人是否存在
                    $by_cou         = $User->where("uid='{$by_id}'")->getField('uid');

                    
                    $UserDetail = new \Common\Model\UserDetailModel();
                    $UserConcer     = new \Common\Model\UserConcernModel();

                    // 关注记录
                    $conc_whe       = ['by_id' => $by_id, 'user_id' => $uid];
                    $conc_cou       = $UserConcer->where($conc_whe)->getField('id');

                    if ($by_cou) {
                        // 关注操作
                        if ($type == 1) {
                            if (!$conc_cou) {
                                $ins_data = [
                                    'by_id'     => $by_id,
                                    'user_id'   => $uid,
                                    'add_time'  => date('Y-m-d H:i:s'),
                                ];

                                // 验证通过
                                if ($UserConcer->create($ins_data)) {
                                    $UserConcer->startTrans();   // 启用事务 
                                    try {
                                        // 添加关注记录
                                        $UserConcer->add($ins_data);

                                        // 被关注人粉丝数加
                                        $UserDetail->where("user_id='{$by_id}'")->setInc('fans_sum');

                                        // 关注人关注数加
                                        $UserDetail->where("user_id='{$uid}'")->setInc('concern_sum');

                                        // 事务提交
                                        $UserConcer->commit(); 

                                        $this->ajaxSuccess();

                                    } catch(\Exception $e) {
                                        // 事务回滚
                                        $UserConcer->rollback(); 

                                        $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                                    }
                                
                                } else {
                                    $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'], $UserConcer->getError());
                                }

                            } else {
                                $this->ajaxError(['ERROR_CODE_SHORT' => 'YET_CONCERN']);
                            }

                        //  取关操作
                        } else {
                            if ($conc_cou) {
                                $UserConcer->startTrans();   // 启用事务 
                                try {
                                    // 删除关注记录
                                    $UserConcer->where($conc_whe)->delete();

                                    // 被关注人粉丝数减
                                    $UserDetail->where("user_id='{$by_id}'")->setDec('fans_sum');

                                    // 关注人关注数减
                                    $UserDetail->where("user_id='{$uid}'")->setDec('concern_sum');

                                    // 事务提交
                                    $UserConcer->commit(); 

                                    $this->ajaxSuccess();

                                } catch(\Exception $e) {
                                    // 事务回滚
                                    $UserConcer->rollback(); 

                                    $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                                }
                            } else {
                                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_CONCERN']); 
                            }
                        }

                    } else {
                        $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']); 
                    }
                }
            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'NOT_CONCERN_SELF']);  
            }
        }

        $this->ajaxError();
    }

    /**
     * 短视频-关注页面
     */
    public function focusOnPage()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $type      = I('post.type/d');                      // 单独操作类型  1关注用户视频列表  2推荐关注用户列表
        $vid       = trim(I('post.file_id'));               // 上传成功后返回带的参数 
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);

        if (IS_POST) {
            $User           = new \Common\Model\UserModel();     // 用户模型

            // 获取用户标识
            $uid            = $User->getUserId($token);

            $UserConcern    = new \Common\Model\UserConcernModel();
            $UserDetail     = new \Common\Model\UserDetailModel();  // 用户详情模型
            $Short          = new \Common\Model\ShortModel();       // 短视频模型

            // 查询关注用户列表、视频列表
            $s_list         = [];
            $u_conc         = $UserConcern->where(['user_id' => $uid])->getField('by_id', true);
            $whe            = $u_conc ? ['user_id' => ['in', $u_conc]] : [];
            if ($u_conc) {
                $s_list     = $Short->where($whe)->getField('id');
            }

            // 关注用户短视频列表 #去掉条件$s_list 有短视频的情况下才返回推荐列表
            if (($u_conc && (empty($type) || $type == 1)) || $vid) {
                $one                       = [];

                // 上传成功返回的参数
                if ($vid) {
                    $o_whe                 = ['vid' => $vid];
                    $s_cou                 = $Short->where($o_whe)->getField('id');

                    // 没有新增数据 调腾讯云SDK获取
                    if (!$s_cou) {
                        $Tencent           = new \Common\Controller\TencentController();
                        $Tencent::queryShortAddOne($vid);           // 查询并插入数据
                    }

                    $one                   = $vid ? $Short->getList($o_whe, $uid, 1, 1, $platform) : [];
                } 

                $limit                     = ($vid && $one) ? $limit - 1 : $limit; 
                $list                      = (empty($type) || $type == 1) ? $Short->getList($whe, $uid, $limit, $page, $platform): [];
                $ulist                     = empty($type) ? $UserDetail->getConcernList('user', $whe, $limit, $page) : [];

                // 插入首位
                if ($one) {
                    array_unshift($list, $one[0]);
                }
                    
                $res['list']               = $list;
                $res['ulist']              = $ulist;
                $res['ulist_iden']         = 'concern';

            // 推荐关注用户列表
            } else {
                $r_whe                     = $uid ? ['user_id' => ['neq', $uid]] : [];

                if ($u_conc) {
                    $u_conc[]              = $uid;
                    $r_whe                 = ['user_id' => ['not in', $u_conc]];
                }

                $rlist                     = $UserDetail->getConcernList('user', $r_whe, $limit, 1, false);

                $res['list']               = [];
                $res['ulist']              = $rlist;
                $res['ulist_iden']         = 'referrer';
            }   
 
            $this->ajaxSuccess($res); 
        }

        $this->ajaxError();
    }

    /**
     * 直播-关注页面
     */
    public function LiveFocusOn()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $type      = trim(I('post.type'));                  // 获取类型  live：关注主播直播列表  rec_live：推荐关注主播列表
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $t         = trim(I('post.t'));                     // 搜索时间
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);

        if (IS_POST) {
            $User           = new \Common\Model\UserModel();     // 用户模型

            // 获取用户标识
            $uid            = $User->getUserId($token);

            $UserConcern    = new \Common\Model\UserConcernModel();
            $UserDetail     = new \Common\Model\UserDetailModel();      // 用户详情模型
            $LiveRoom       = new \Common\Model\LiveRoomModel();
            $Short          = new \Common\Model\ShortModel();

            // 查询关注主播列表、关注主播直播列表
            $u_conc         = $UserConcern->where(['user_id' => $uid])->getField('by_id', true);
            $whe            = $u_conc ? ['user_id' => ['in', $u_conc]] : [];

            // 关注主播 关注主播直播列表
            if (($u_conc && (empty($type) || $type == 'live'))) {

                $ulist      = empty($type) ? $UserDetail->getConcernList('live', $whe, $limit, $page) : [];
                $whe['_complex'] = [
                    '_complex'  => ['is_status' => 1],
                    /* '_logic'    => 'or',
                    '_string'   => " `is_put` = 'Y' and `is_status` in (3, 4) ", */
                ];

                // 时间戳
                if ($t) {
                    $whe['_complex']['_complex']['recent_time'] = ['elt', date('Y-m-d H:i:s', $t)];
                }

                // 1、直播数据
                $resList = [];
                $list    = (empty($type) || $type == 'live') ? $LiveRoom->getListData($whe, $uid, $limit, $page, $platform): [];
                $resList = $list['live'] ? $list['live'] : [];

                /* // 2、回放数据
                if (count($list['record']) > 0) {
                    $userList = [];

                    foreach ($list['record'] as $key => $val) {
                        array_push($userList, $key);
                    }

                    $ids = $Short->getUserNewestRecord($userList);
                    if (count($ids) > 0) {
                        $recordList = $list['record'];
                        $list       = $Short->getRecordList(['id' => ['in', $ids]], $uid, self::$limit, self::$page, $platform, 'self_top desc,id desc');

                        foreach ($list as $key => $val) {
                            $list[$key]['room_id'] = $recordList[$val['user_id']];
                        }

                        $resList    = array_merge($resList, $list);
                    }
                } */
                    
                $res['list']        = $resList;
                $res['ulist']       = $ulist;
                $res['ulist_iden']  = 'live';

            // 推荐关注主播列表
            } else {
                $r_whe              = $uid ? ['user_id' => ['neq', $uid]] : [];
                if ($u_conc) {
                    $u_conc[]       = $uid;
                    $r_whe          = ['user_id' => ['not in', $u_conc]];
                }

                $rlist              = $UserDetail->getConcernList('live', $r_whe, $limit, 1, false);

                $res['list']        = [];
                $res['ulist']       = $rlist;
                $res['ulist_iden']  = 'rec_live';
            }   
            
            $this->ajaxSuccess($res);
        }

        $this->ajaxError();
    }
    
    /**
     * 关注用户/关注主播列表
     */
    public function getConcList()
    {
        // 获取参数
        $type               = trim(I('post.type'));                  // 获取类型  user：关注用户列表  live：关注主播列表
        $limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);

        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        // 获取用户标识
        $uid                = $res_token['uid'];

        $UserConcern        = new \Common\Model\UserConcernModel();
        $UserDetail         = new \Common\Model\UserDetailModel(); // 用户详情模型

        // 查询关注列表
        $u_conc             = $UserConcern->where(['user_id' => $uid])->getField('by_id', true);

        $list               = [];
        if ($u_conc) {
            $whe            = ['user_id' => ['in', $u_conc]];
            if (empty($type) || $type == 'user') {
                $list           = $UserDetail->getConcernList('user', $whe, $limit, $page);
            } elseif ($type == 'live') {
                $list           = $UserDetail->getConcernList('live', $whe, $limit, $page);
            }
        }

        $this->ajaxSuccess(['list' => $list]); 
    }

    /**
     * 个人页/某用户页
     */
    public function getUserHome()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $view_id   = I('post.view_id/d');                   // 查看某用户的ID
        $type      = I('post.type/d');                      // 查询类型 默认作品  1作品 2直播 3喜欢
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        // 个人页
        if($view_id) {
            $User                       = new \Common\Model\UserModel();         // 用户模型
            $UserDetail                 = new \Common\Model\UserDetailModel();   // 用户详情模型
            $Short                      = new \Common\Model\ShortModel();        // 短视频模型

            // 查询用户是否存在
            $user_cou                   = $User->where("uid='{$view_id}'")->getField('uid');

            if ($user_cou) {
                // 获取用户标识
                $uid                    = $User->getUserId($token);

                // 获取数据
                $one                    = $UserDetail->getUserDetailHome($view_id, $uid, $platform);
                // 作品数据
                if (empty($type) || $type == 1)  {
                    $one['works_list']  = $Short->getList(['user_id' => $view_id], $uid, self::$limit, self::$page, $platform, 'self_top desc,id desc');
                
                // 直播数据
                } elseif ($type == 2) {
                    $one['works_list']  = $Short->getRecordList(['user_id' => $view_id], $uid, self::$limit, self::$page, $platform, 'self_top desc,id desc');

                // 喜欢数据
                } elseif ($type == 3) {
                    $UserPraise         = new \Common\Model\UserPraiseModel();             // 用户点赞视频模型
                    $likes              = $UserPraise->where(['user_id' => $view_id])->getField('short_id', true); // 喜欢短视频ID列表
                    $one['works_list']  = $likes ? $Short->getList(['id' => ['in', $likes]], $uid, self::$limit, self::$page, $platform) : [];
                }

                $this->ajaxSuccess(['list' => $one]);

            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']); 
            }
        }

        $this->ajaxError(); 
    }

    /**
     * 个人页-获取-作品/直播/喜欢数据列表
     */
    public function getWorkLiveLike()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $view_id   = I('post.view_id/d');                   // 查看某用户的ID
        $type      = I('post.type/d');                      // 查询类型 1作品 2直播 3喜欢
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);


        if ($view_id && in_array($type, [1,2,3])) {
            $Short          = new \Common\Model\ShortModel();           // 短视频模型
            // 获取用户标识
            $User           = new \Common\Model\UserModel();            // 用户模型
            $uid            = $User->getUserId($token);
    
            // 作品 
            if ($type == 1) {
                $work_list                  = $Short->getList(['user_id' => $view_id], $uid, $limit, $page, $platform, 'self_top desc,id desc');
                $res['list']                = $work_list;
                $res['list_iden']           = 'works';

            // 直播
            } elseif ($type == 2) {
                $live_list                  = $Short->getRecordList(['user_id' => $view_id], $uid, $limit, $page, $platform, 'self_top desc,id desc');;
                $res['list']                = $live_list;
                $res['list_iden']           = 'lives'; 

            // 喜欢
            } elseif ($type == 3) {
                $UserPraise                 = new \Common\Model\UserPraiseModel();             // 用户点赞视频模型
                $likes                      = $UserPraise->where(['user_id' => $view_id])->getField('short_id', true); // 喜欢短视频ID列表
                $like_list                  = $likes ? $Short->getList(['id' => ['in', $likes]], $uid, $limit, $page, $platform) : [];
                $res['list']                = $like_list;
                $res['list_iden']           = 'likes';
            }

            $this->ajaxSuccess($res);
        }

        $this->ajaxError();
    }

    /**
     * 点击头像获取用户简介信息
     */
    public function getLiveUserIntro()
    {
        $token              = trim(I('post.token'));
        $view_id            = I('post.view_id/d');                   // 查看某用户的ID
        $room_id            = I('post.room_id/d', 0);                // 直播房间号
        $platform           = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($view_id) {
            $User           = new \Common\Model\UserModel(); 
            $UserDetail     = new \Common\Model\UserDetailModel();

            // 获取用户标识
            $uid            = $User->getUserId($token);

            $one            = $UserDetail->getUserIntro($view_id, $uid, $room_id);
            
            if ($one) {
                $this->ajaxSuccess(['user' => $one]);
                
            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 我的橱窗/我推荐的好物
     */
    public function getGoodThing()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $view_id   = I('post.view_id/d');                   // 查看某用户的ID
        $sort      = trim(I('post.sort'));                  // 排序规则  shelves_desc：上架时间降序  sales_desc：销量降序  price_desc： 价格降序
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);


        if ($view_id) {
            $User                   = new \Common\Model\UserModel();
            $UserDetail             = new \Common\Model\UserDetailModel();
            $ShortLiveGoods         = new \Common\Model\ShortLiveGoodsModel();
            $Merch                  = new \Common\Model\MultiMerchantModel();
            $MerchUser              = new \Common\Model\ShopMerchUserModel();
            $Goods 			        = new \Common\Model\GoodsModel();
            // 查询被查看人是否存在
            $v_cou                  = $User->getUserMsg($view_id);

            if ($v_cou) {
                $whe                = ['user_id' => $view_id];
                $list               = $ShortLiveGoods->getGoodsData('thing', $whe, $view_id, $limit, $page, $platform);

                // 个人信息  判断头像是否为第三方应用头像
                $ud                 = $UserDetail->where($whe)->field('nickname,avatar')->find();
                if ($ud['avatar'] && !is_url($ud['avatar'])) {
                    $ud['avatar']   = WEB_URL . $ud['avatar'];
                }

                // 总数量调换
                $ud['total_num']     = $list ? $list['total_num'] : 0;
                unset($list['total_num']);

                // 是否开启多商户
                $mectype = $Merch->where(['type'=>1,'settle_in'=>2])->find();

                // 多商户标识
                $ud['merchant'] = 0;
                if ($mectype) {
                    $ud['merchant'] = 1;
                }

                $shopuser = $MerchUser->where(['openid'=>'lailu_'.$view_id, 'status'=>1])->field('accounttime,desc,id')->find();

                // 店铺标识
                $ud['shop'] = 0;
                if (date('Y-m-d',$shopuser['accounttime']) > date('Y-m-d')) {
                    $ud['shop'] = 1;
                    // 公告
                    $ud['desc1'] = $shopuser['desc'];
                    $lists = $Goods->where(['shop_id'=> $shopuser['id'],'is_show'=>'Y'])->select();
                    $ud['shop_num']  = $lists ? (int)count($lists) : 0;
                }

                $this->ajaxSuccess(['user' => $ud, 'glist' => $list]);

            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取实名认证状态
     */
    public function getRealState()
    {
        $platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        // 获取用户标识
        $uid                = $res_token['uid'];
        $RealName           = new \Common\Model\RealNameModel();
        $real               = $RealName->where(['user_id' => $uid])->field('real_status,fail_explain')->find();

        $res                = ['real_status' => ($real ? $real['real_status'] : 'not'), 'fail_explain' => ($real ? $real['fail_explain'] : '')];

        $this->ajaxSuccess($res);
    
    }

    /**
     * 实名认证第一步验证身份证号
     */
    public function realNameOne()
    {
        $real_card      = trim(I('post.real_card'));

        if ($real_card) {
            // 身份证验证开关
            $userNumAuth = defined('LIVE_USER_NUMBER_AUTH') ? LIVE_USER_NUMBER_AUTH : 1;
            if ($userNumAuth == 1) {
                import("Org.Util.IdentityCard");    // 引入身份证验证相关的类
                $ide_obj 	= new \IdentityCard();

                if ($ide_obj::isValid($real_card)) {
                    $this->ajaxSuccess();
                } else {
                    $this->ajaxError(['ERROR_CODE_USER' => 'IDE_WRONG']);
                }
            }
            $this->ajaxSuccess();
        }

        $this->ajaxError();
    }

    /**
     * 实名认证信息
     */
    public function realNameAuth()
    {
        $real_name      = trim(I('post.real_name'));
        $real_card      = trim(I('post.real_card'));
        $front          = trim(I('post.front'));
        $emblem         = trim(I('post.emblem'));
        $platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        // 获取缓存图片路径
        $front_s        = S($front);
        $emblem_s       = S($emblem);
        $room_cover     = S('room_cover');
        $front_url      = substr($front_s, 1);
        $emblem_url     = substr($emblem_s, 1);

        if ($real_name && $real_card && $front_url && $emblem_url) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            import("Org.Util.IdentityCard");    // 引入身份证验证相关的类
            $ide_obj 	= new \IdentityCard();

//            if ($ide_obj::isValid($real_card)) {
                // 获取用户标识
                $uid                = $res_token['uid'];

                $RealName           = new \Common\Model\RealNameModel();
                $r_one              = $RealName->field('real_id,real_status,front_url,emblem_url')->where(['user_id' => $uid])->find();

                if (!$r_one || ($r_one && $r_one['real_status'] == 'fail')) {

                    // 记录数据数据
                    if ($front && $emblem) {
                        $ins = [
                            'user_id'       => $uid,
                            'real_name'     => $real_name,
                            'real_card'     => $real_card,
                            'front_url'     => $front_url,
                            'emblem_url'    => $emblem_url,
                        ];

                        if ($r_one) {
                            // 删除原图片
                            @unlink('.'. $r_one['front_url']);
                            @unlink('.'. $r_one['emblem_url']);

                            $ins['fail_explain']= '';
                            $ins['fail_time']   = '';
                            $ins['real_status'] = 'check';

                            // 修改认证记录
                            $RealName->where(['real_id' => $r_one['real_id']])->save($ins);

                        } else {  
                            $ins['add_time'] = date('Y-m-d H:i:s');

                            // 记录认证记录
                            $RealName->add($ins);
                        }

                        // 删除缓存中多余的图片
                        if ($room_cover) {
                            foreach ($room_cover as $v) {
                                if ($front_s != $v && $emblem_s != $v) {
                                    @unlink($v);
                                }  
                            }
                            S('room_cover', null);
                        }

                        S($front, null);
                        S($emblem, null);

                        $this->ajaxSuccess(['real_status' => 'check']);
                    }

                } else {
                    $this->ajaxError(['ERROR_CODE_USER' => 'REAL_CENTRE']);
                }
//            } else {
//                $this->ajaxError(['ERROR_CODE_USER' => 'IDE_WRONG']);
//            }
        }

        $this->ajaxError();
    }

    /**
     * 获取分享信息
     */
    public function getShare()
    {
        $short_id       = I('post.short_id/d');
        $room_id        = I('post.room_id/d');
        $goods_id       = trim(I('post.goods_id'));
        $platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        $share_code     = S('share_code_arr');              // 缓存的小程序码数组
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        

        $UserDetail = new \Common\Model\UserDetailModel();
        $uid        = $res_token['uid'];
        $user_info  = $User->getUserMsg($uid);
        $auth_code  = $user_info['auth_code'];
        $path       = 'pages/index/index?auth_code='. $auth_code;


        // 短视频
        if ($short_id) {
            $path      = "pages/index/index?short_id=$short_id&auth_code=$auth_code";
            // 短视频记录
            $Short     = new \Common\Model\ShortModel();
            $s_one     = $Short->field('description,cover_url,user_id,is_recorded')->where(['id' => $short_id])->find();
            $name      = (isset($s_one['description']) && $s_one['description']) ? $s_one['description'] : '';
            $url       = (isset($s_one['cover_url']) && $s_one['cover_url']) ? (is_url($s_one['cover_url']) ? $s_one['cover_url'] : WEB_URL . $s_one['cover_url']) : '';

            // 直播回放的路径
            if (isset($s_one['is_recorded']) && $s_one['is_recorded'] == 1) {
                $path  = "live/live_back_on/live_back_on?type=back&short_id=$short_id&auth_code=$auth_code";
            }
        }

        // 直播间
        if ($room_id) {
            // 直播间记录
            $LiveRoom  = new \Common\Model\LiveRoomModel();
            $l_one     = $LiveRoom->field('room_name,cover_url,user_id,is_status')->where(['room_id' => $room_id])->find();
            $name      = (isset($l_one['room_name']) && $l_one['room_name']) ? $l_one['room_name'] : '';
            $url       = (isset($l_one['cover_url']) && $l_one['cover_url']) ? (is_url($l_one['cover_url']) ? $l_one['cover_url'] : WEB_URL . $l_one['cover_url']) : '';

            // 加入假直播标识
            $is_fake   = (isset($l_one['is_status']) && $l_one['is_status'] == 2) ? 1 : 0;
            $path      = "live/live_room/live_room?room_id=$room_id&auth_code=$auth_code&is_fake=$is_fake";
        }

        if ($goods_id) {
            $type      = trim(I('post.type'));
            if ( $type == 'self') {
                $path      = "mall/proprietary_mall_detail/proprietary_mall_detail?goods_id=$goods_id&type=$type&auth_code=$auth_code";
            } else {
                $path      = "mall/goods_detail/goods_detail?goods_id=$goods_id&type=$type&auth_code=$auth_code";
            }
        }

        // 用户信息
        $u_one         = $UserDetail->field('nickname,avatar')->where(['user_id' => $uid])->find();

        // 短视频信息或直播房间信息
        $user = [
            'main_img'      => ((isset($url) && $url) ? $url : WEB_URL .'/Public/static/admin/img/logo.png'),
            'title'         => ((isset($name) && $name) ? $name : '欢迎您的加入'),
            'nickname'      => ((isset($u_one['nickname']) && $u_one['nickname']) ? $u_one['nickname'] : '至尊主播'),
            'avatar'        => ((isset($u_one['avatar']) && $u_one['avatar']) ? (is_url($u_one['avatar']) ? $u_one['avatar'] : WEB_URL . $u_one['avatar']) : WEB_URL .'/Public/static/admin/img/logo.png'),
        ];

        // 获取图片并保存 返回路径  微信小程序参数
        $param              = ['path'  => $path];
        $param['is_hyaline']= true;                         // 透明底
        $code_img           = get_applet_wxacode($param);

        if ($code_img) {
            $user['img']  = WEB_URL . $code_img;

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
            $share_code[]   = $code_img;
            S('share_code_arr', $share_code);

            $this->ajaxSuccess($user);

        } else {
            $this->ajaxError(['ERROR_CODE_COMMON' => 'SHARE_CODE_GENERATION_FAIL']);
        }
    }

    /**
     * 获取某个地址的文件（base64加密）
     */
    public function getFile()
    {
        $url            = trim(I('post.url'));

        if ($url && is_url($url)) {
            $file       = https_request($url);

            if ($file) {
                $this->ajaxSuccess(['file' => base64_encode($file)]);
            }
        }

        $this->ajaxError();
    }

    /**
     * 确认邀请码
     */
    public function confirmInviteCode()
    {
        $code               = trim(I('post.code'));

        if ($code) {
            $UserAuthCode   = new \Common\Model\UserAuthCodeModel();
            $UserDetail     = new \Common\Model\UserDetailModel();

            // 查询邀请码是否存在
            $c_one          = $UserAuthCode->where(['auth_code' => $code, 'is_used' => 'Y'])->getField('user_id');

            if ($c_one) {
                $ud_one     = $UserDetail->getUserDetailMsg($c_one);

                if ($ud_one) {
                    $res['detail']  = [
                        'nickname'  => $ud_one['nickname'], 
                        'avatar'    => (is_url($ud_one['avatar']) ? $ud_one['avatar'] : WEB_URL . $ud_one['avatar'])
                    ];

                    $this->ajaxSuccess($res);
                    
                } else {
                    $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
                }
            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 搜索 昵称/来鹿号/房间号 查询用户
     */
    public function getSearchUser()
    {
        $kw             = trim(I('post.kw'));
        $token          = trim(I('post.token'));
        $platform       = trim(I('post.platform'));         // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit          = I('post.limit/d', self::$limit);
        $page           = I('post.page/d', self::$page);

        if ($kw) {
            $User       = new \Common\Model\UserModel();
            $UserDetail = new \Common\Model\UserDetailModel();

            // 获取用户标识
            $uid        = $User->getUserId($token);
            
            $list       = $UserDetail->getSeekList($kw, $uid, $limit, $page);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 获取PK方的房间信息
     */
    public function getPkRoomInfo()
    {
        $token              = trim(I('post.token'));
        $host_id            = I('post.host_id/d');                   // 查看某用户的ID
        $platform           = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if ($host_id) {
            $LiveRoom       = new \Common\Model\LiveRoomModel(); 
            $User           = new \Common\Model\UserModel(); 
            $UserDetail     = new \Common\Model\UserDetailModel(); 

            // 查询是否有房间信息
            $lroom          = $LiveRoom->field('room_id')->where(['user_id' => $host_id])->find();

            if ($lroom) {
                // 获取用户标识
                $uid            = $User->getUserId($token);

                $one            = $UserDetail->getPkHostIntro($host_id, $uid, $lroom['room_id']);
                $one['room_id'] = $lroom['room_id'];        
                
                if ($one) {
                    $this->ajaxSuccess(['user' => $one]);
                    
                } else {
                    $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']);
                }

            } else {
                $this->ajaxError(['ERROR_CODE_LIVE' => 'USER_NOT_HOST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 用户关注列表
     */
    public function userAttention()
    {
        $host_id = I('post.host_id/d');                   // 查看某用户的ID
        $type = I('post.type');
        $limit = I('post.limit/d', self::$limit);
        $page = I('post.page/d', self::$page);
        $this->verifyUserToken($token, $User, $res_token);
        $UserConcern = new \Common\Model\UserConcernModel();
        $UserDetail  = new \Common\Model\UserDetailModel();   // 用户详情模型
        // 主播资料
        $anfield = 'd.nickname';
        $Anchor = $UserConcern->alias('a')
            ->join('__USER__ u ON u.uid= a.by_id', 'LEFT')
            ->join('__USER_DETAIL__ d ON d.user_id= a.by_id', 'LEFT')
            ->field($anfield)
            ->where("u.is_host='Y'")
            ->where("a.by_id={$host_id}")
            ->find();

        $usid = $hostId = '';
        if ($type == 1) {
            $usid = 'user_id';
            $hostId = 'by_id';
        } else if ($type == 0) {
            $usid = 'by_id';
            $hostId = 'user_id';
        } else {
            $this->ajaxError();
        }
        $list = $this->concernSum($usid,$host_id,$hostId,$page,$limit,$type);
        $Anchor['concern_sum'] = count($this->concernSum('by_id',$host_id,'user_id',$page,$limit,1,true));
        $Anchor['fans_sum'] = count($this->concernSum('user_id',$host_id,'by_id',$page,$limit,2,true));
        $UserDetail->where(['user_id'=>$host_id])->save(['concern_sum'=>$Anchor['concern_sum'],'fans_sum'=>$Anchor['fans_sum']]);
        $this->ajaxSuccess(['head' => $Anchor,'list' => $list]);
    }

    protected function concernSum($usid,$host_id,$hostId,$page,$limit,$type,$st = false)
    {
        $UserConcern = new \Common\Model\UserConcernModel();
        $field = "a.{$usid},d.avatar,d.nickname,d.signature";
        $u_conc             = $UserConcern->where([$usid => $host_id])->getField($hostId, true);
        if ($st) {
            $list = $UserConcern->alias('a')
                ->join("__USER__ u ON u.uid= a.{$usid}", 'LEFT')
                ->join("__USER_DETAIL__ d ON d.user_id= a.{$usid}", 'LEFT')
                ->field($field)
                ->where("u.is_host='Y'")
                ->where("a.{$hostId}={$host_id} and d.avatar!=''")
                ->order("{$usid}")
                ->select();
        } else {
            $list = $UserConcern->alias('a')
                ->join("__USER__ u ON u.uid= a.{$usid}", 'LEFT')
                ->join("__USER_DETAIL__ d ON d.user_id= a.{$usid}", 'LEFT')
                ->field($field)
                ->where("u.is_host='Y'")
                ->where("a.{$hostId}={$host_id} and d.avatar!=''")
                ->page($page, $limit)
                ->order("{$usid}")
                ->select();
        }

        foreach ($list as $k => $v) {
            // 用户头像处理
            $list[$k]['avatar'] = $list[$k]['avatar'] ? (is_url($v['avatar']) ? $v['avatar'] : WEB_URL . $v['avatar']) : $list[$k]['avatar'];

            if ($type == 1) {
                // 粉丝列表
                if (in_array($v[$usid], $u_conc)) {
                    //互相关注
                    $list[$k]['mutual'] = 1;
                } else {
                    //回关
                    $list[$k]['mutual'] = 2;
                }
                $list[$k]['by_id'] = $v['user_id'];
                unset($list[$k]['user_id']);
            } else {
                // 关注列表
                if (in_array($v[$usid], $u_conc)) {
                    //互相关注
                    $list[$k]['mutual'] = 1;
                } else {
                    //已关注
                    $list[$k]['mutual'] = 3;
                }
            }
        }
        return $list;
    }

    // 商品烟雾测试
    public function abc()
    {
        $Im 			= new \Common\Controller\ImController();

        /* $smoke          = [
            "goods" => [
                "goods_id"  => "1",
                "from"      => "self",
                "smokes"    => [
                    [
                        "phone"     => "198****1114",
                        "action_id" => "3",
                        "action"    => "购买了宝贝"
                    ],
                    [
                        "phone"     => "155****2761",
                        "action_id" => "2",
                        "action"    => "分享了宝贝"
                    ],
                    [
                        "phone"     => "156****2788",
                        "action_id" => "1",
                        "action"    => "领取了优惠券"
                    ]
                ]
            ]
        ];

        // IM发送房间信息
        $Im->sendGroupMsg('10008', 'goods_smokes', $smoke); */
        
        /* $user = [
            'user_id'   => '4',
            'nickname'  => '我是赵大爷',
            'type'      => 'apply',
        ];

        // 申请连麦私发
        $Im->sendUserMsg('8', 'apply_mic', $user); */
    }

    public function merchantConfig()
    {
        $host_id = I('post.shop_id/d');
        if ($host_id) {
            $Merch = new \Common\Model\ShopMerchUserModel();
            $list = $Merch->where(['id'=>$host_id])->field('mobile,address,accounttime,merchname')->find();
            $list['accounttime'] = date('Y-m-d',$list['accounttime']);
            $this->ajaxSuccess($list);
        }
        $this->ajaxError();

    }
}
?>    