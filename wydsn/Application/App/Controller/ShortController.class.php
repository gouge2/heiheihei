<?php
/**
 * 短视频管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;


class ShortController extends AuthController
{
    /**
     * 获取短视频列表
     */
    public function getList()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $text      = trim(I('post.text'));                  // 搜索文本
        $short_id  = I('post.short_id/d', 0);               // 视频ID
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);
        $openad    = I('post.open_ad');                     //传递此参数开始插广告

        if (IS_POST) {
            $Short     = new \Common\Model\ShortModel();    // 短视频模型
            $User      = new \Common\Model\UserModel();

            // 获取用户标识
            $uid       = $User->getUserId($token);

            // 文本搜索
            if ($text) {
                // $list                     = $Short->getList(['text' => $text], $uid, $limit, $page, $platform);
                $list                        = [];

            // 推荐
            } else {
                // 获取广告列表
                if ($openad) {
                    $adver     = new \Common\Model\AdvertisingModel();    // 广告模型
                    $ad_list = $adver->getList(['is_status' => 1], $uid, $platform);
                }
                
                // 小程序分享进来
                $s_one              = [];   
                if ($short_id && $page == 1) {  
                    $s_one          = $Short->getList(['id' => $short_id], $uid, $limit, $page, $platform);
                    $limit          = $limit - 1;
                }

                $list               = $Short->getList(['is_recommend' => 1, 'getList' => 1], $uid, $limit, $page, $platform);

                // 放入首位
                if ($s_one) {
                    array_unshift($list, $s_one[0]);
                }
            }
            // 短视频插入广告
            if ($openad && !empty($ad_list)) {
                $list = addvtorandp($list, $ad_list);
            }

            $this->ajaxSuccess(['list' => $list]);
        }
        
        $this->ajaxError();
    }

    /**
     * 获取短视频上传签名
     */
    public function getUploadSign()
    {
        // 获取参数
        $goods_id  = trim(I('post.goods_id'));
        $from      = trim(I('post.from'));        // 商品来源  self:自营商城  tb:淘宝  jd:京东  pdd:拼多多  vip:唯品会  

        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        // 获取用户标识
        $uid        =  $res_token['uid'];
        $res_auth   = $User->checkAuthority('short', $uid);   // 检查视频权限

        if ($res_auth['code'] != 0) {
            // 没有权限
            $this->ajaxError($res_auth['code'], $res_auth['msg']);
        } else {
            // 确定App的云API密钥
            $secret_id             =  TENCENT_SECRETID;
            $secret_key            =  TENCENT_SECRETKEY;

            // 确定签名的当前时间和失效时间
            $current               =  $_SERVER['REQUEST_TIME'];
            $expired               =  $current + 300;         // 签名有效期：5分钟

            $goods_id              = $goods_id ? $goods_id : 0;
            $from                  = $from ? $from : 0;

            // 向参数列表填入参数
            $arg_list              =  [
                "secretId"         => $secret_id,
                "currentTimeStamp" => $current,
                "expireTime"       => $expired,
                "random"           => mt_rand(),
                "sourceContext"    => $uid .','. $goods_id .','. $from,     // 来源上下文  上传完成回调将返回该字段值
                // "sessionContext"   => 110120,                            // 会话上下文  当指定 procedure 参数后，任务流状态变更回调 将返回该字段值  
            ];

            // 计算签名
            $original              =  http_build_query($arg_list);
            $sign                  =  base64_encode(hash_hmac('SHA1', $original, $secret_key, true) . $original);

            $this->ajaxSuccess(['sign' => $sign]);
        }

        $this->ajaxError();
    }

    /**
     * 短视频点赞/取消点赞
     */
    public function handlePraise()
    {
        // 获取参数
        $short_id  = I('post.short_id/d');       // 视频ID
        $type      = I('post.type/d');           // 操作类型 1点赞 2取消点赞
        $openad    = I('post.open_ad');          //传递此参数使用广告点赞

        if ($short_id && in_array($type, [1,2])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 查询文件是否存在
            $short_whe      = ['id' => $short_id];
            $Short          = new \Common\Model\ShortModel();
            $short_one      = $Short->field('id,user_id')->where($short_whe)->find();
            if ($openad) {
                $Short = new \Common\Model\AdvertisingModel();
                $short_one      = $Short->field('id')->where($short_whe)->find();
            }

            if ($short_one ) {
                // 获取用户标识
                $uid        = $res_token['uid'];

                $UserDetail = new \Common\Model\UserDetailModel();
                $UserPraise = new \Common\Model\UserPraiseModel();

                // 点赞信息
                $praise_whe = ['short_id' => $short_one['id'], 'user_id' => $uid];

                if ($openad) {
                    $UserPraise = new \Common\Model\AdvertisingPraiseModel();
                    $praise_whe = ['ad_id' => $short_one['id'], 'user_id' => $uid];
                }
                $praise_cou = $UserPraise->where("is_status=0")->where($praise_whe)->getField('id');

                // 点赞操作
                if ($type == 1) {
                    if (!$praise_cou) {
                        // 记录数据
                        $ins_data = [
                            'user_id'  => $uid,
                            'add_time' => date('Y-m-d Hi:s'),
                        ];
                        if (!$openad) {
                            $ins_data['short_id'] = $short_id;
                        } else {
                            $ins_data['ad_id'] = $short_id;
                        }
                        // 验证通过
                        if ($UserPraise->create($ins_data)) {
                            $UserPraise->startTrans();   // 启用事务 
                            try {
                                // 记录点赞信息
                                $UserPraise->add($ins_data);

                                // 视频获赞数加
                                $Short->where($short_whe)->setInc('praise_num');

                                // 作者视频获赞总数加
                                if ($short_one['user_id'] && !$openad) {
                                    $UserDetail->where(['user_id' => $short_one['user_id']])->setInc('praise_short');
                                }

                                // 事务提交
                                $UserPraise->commit(); 
                                
                                $this->ajaxSuccess();

                            } catch(\Exception $e) {
                                // 事务回滚
                                $UserPraise->rollback();

                                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                            }

                        } else {
                            $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'], $UserPraise->getError());
                        }
                    } else {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'YET_PRAISE']);
                    }

                // 取消赞操作
                } else {
                    if ($praise_cou) {
                        $UserPraise->startTrans();   // 启用事务 
                        try {
                            // 删除点赞信息
                            $UserPraise->where($praise_whe)->delete();

                            // 视频获赞数减
                            $Short->where($short_whe)->setDec('praise_num');

                            // 作者视频获赞总数减
                            if ($short_one['user_id'] && !$openad) {
                                $UserDetail->where(['user_id' => $short_one['user_id']])->setDec('praise_short');
                            }

                            // 事务提交
                            $UserPraise->commit(); 
                            
                            $this->ajaxSuccess();

                        } catch(\Exception $e) {
                            // 事务回滚
                            $UserPraise->rollback();

                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }
                    } else {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_PRAISE']);
                    }
                }

            }  else {
                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     *  提交短视频评论
     */
    public function submitComment()
    {
        // 获取参数
        $short_id  = I('post.short_id/d');         // 视频ID
        $text      = trim(I('post.text'));         // 评论内容
        $parent_id = I('post.parent_id/d');        // 父评论ID
        $openad    = I('post.open_ad');            //传递此参数使用广告评论

        if ($short_id && $text) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $ShortComment   = new \Common\Model\ShortCommentModel();

            // 查询文件是否存在
            $short_whe      = ['id' => $short_id];
            $Short          = new \Common\Model\ShortModel();
            $short_one      = $Short->field('id,user_id,comment_num')->where($short_whe)->find();

            if ($short_one && !$openad) {
                // 获取用户标识
                $uid            = $res_token['uid'];

                // 过滤敏感词汇
                $word           = sensitive_word($text);  

                $ins_data = [
                    'short_id'  => $short_id,
                    'text'      => $word,
                    'user_id'   => $uid,
                    'level'     => 1,
                ];

                $parent_one     = 10;       // 上级评论标识

                // 有父评论ID 算是二级评论
                if ($parent_id) {
                    $ins_data['parent_id']  = $parent_id;
                    $ins_data['level']      = 2;

                    // 查询是否有上级评论
                    $parent_one             = $ShortComment->field('root_id,user_id')->where(['id' => $parent_id])->find();

                    // 根评论ID
                    $ins_data['root_id']    = ($parent_one && $parent_one['root_id']) ? $parent_one['root_id'] : $parent_id;
                    // 回复用户ID
                    $ins_data['reply_id']   = ($parent_one && $parent_one['user_id']) ? $parent_one['user_id'] : 0;

                } else {
                    // 作者评论置顶
                    $ins_data['sort']       = ($short_one['user_id'] == $uid) ? 100 : 0;
                }

                // 查询是否重复评论
                $comm_cou                   = $ShortComment->where($ins_data)->getField('id');

                $ins_data['add_time']       = date('Y-m-d H:i:s');    //  加入时间

                if (!$comm_cou && $parent_one) {
                    // 验证通过
                    if ($ShortComment->create($ins_data)) {
                        $ShortComment->startTrans();   // 启用事务 
                        try {
                            // 记录评论信息
                            $new_id = $ShortComment->add($ins_data);

                            // 视频评论数加
                            $Short->where($short_whe)->setInc('comment_num');

                            // 评论记录回复数加
                            if ($parent_id) {
                                $ShortComment->where(['id' => $parent_id])->setInc('reply_num');

                                if (isset($ins_data['root_id']) && $ins_data['root_id'] != $parent_id) {
                                    $ShortComment->where(['id' => $ins_data['root_id']])->setInc('reply_num');
                                }
                            }
                            

                            // 事务提交
                            $ShortComment->commit(); 

                            $this->ajaxSuccess(['new_comment_id' => $new_id, 'taotal_comment_num' => (int)($short_one['comment_num']*1 + 1)]);

                        } catch(\Exception $e) {
                            // 事务回滚
                            $ShortComment->rollback();

                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }

                    } else {
                        $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'], $ShortComment->getError());
                    }  
                } else {
                    if ($parent_one == 10) {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'REUSE_COMMENT']);
                    } 
                }
            
            } else if ($openad) {

                // 广告评论
                $AdverComment   = new \Common\Model\AdvertisingCommentModel();
                $Adver          = new \Common\Model\AdvertisingModel();
                $adv_one        = $Adver->field('comment_num')->where($short_whe)->find();
                // 获取用户标识
                $uid            = $res_token['uid'];

                // 过滤敏感词汇
                $word           = sensitive_word($text);

                $ins_data = [
                    'short_id'  => $short_id,
                    'text'      => $word,
                    'user_id'   => $uid,
                    'level'     => 1,
                ];

                $parent_one     = 10;       // 上级评论标识

                // 有父评论ID 算是二级评论
                if ($parent_id) {
                    $ins_data['parent_id']  = $parent_id;
                    $ins_data['level']      = 2;

                    // 查询是否有上级评论
                    $parent_one             = $AdverComment->field('root_id,user_id')->where(['id' => $parent_id])->find();

                    // 根评论ID
                    $ins_data['root_id']    = ($parent_one && $parent_one['root_id']) ? $parent_one['root_id'] : $parent_id;
                    // 回复用户ID
                    $ins_data['reply_id']   = ($parent_one && $parent_one['user_id']) ? $parent_one['user_id'] : 0;

                }

                // 查询是否重复评论
                $comm_cou                   = $AdverComment->where($ins_data)->getField('id');

                $ins_data['add_time']       = date('Y-m-d H:i:s');    //  加入时间

                if (!$comm_cou && $parent_one) {
                    // 验证通过
                    if ($AdverComment->create($ins_data)) {
                        $AdverComment->startTrans();   // 启用事务
                        try {
                            // 记录评论信息
                            $new_id = $AdverComment->add($ins_data);

                            // 视频评论数加
                            $Adver->where($short_whe)->setInc('comment_num');

                            // 评论记录回复数加
                            if ($parent_id) {
                                $AdverComment->where(['id' => $parent_id])->setInc('reply_num');

                                if (isset($ins_data['root_id']) && $ins_data['root_id'] != $parent_id) {
                                    $AdverComment->where(['id' => $ins_data['root_id']])->setInc('reply_num');
                                }
                            }


                            // 事务提交
                            $AdverComment->commit();

                            $this->ajaxSuccess(['new_comment_id' => $new_id, 'taotal_comment_num' => (int)($adv_one['comment_num']*1 + 1)]);

                        } catch(\Exception $e) {
                            // 事务回滚
                            $AdverComment->rollback();

                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }

                    } else {
                        $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'], $AdverComment->getError());
                    }
                } else {
                    if ($parent_one == 10) {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'REUSE_COMMENT']);
                    }
                }
            } else {
                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 评论点赞/取消点赞
     */
    public function commentPraise()
    {
        // 获取参数
        $comment_id = I('post.comment_id/d');     // 评论记录ID
        $type       = I('post.type/d');           // 操作类型 1点赞 2取消点赞
        $openad    = I('post.open_ad');           //传递此参数使用广告评论

        if ($comment_id && in_array($type, [1,2])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 查询评论是否存在
            $ShortComment   = new \Common\Model\ShortCommentModel();
            if ($openad) {
                $ShortComment   = new \Common\Model\AdvertisingCommentModel();
            }
            $comment_whe    = ['id' => $comment_id];
            $comment_cou    = $ShortComment->where($comment_whe)->getField('id'); 

            if ($comment_cou) {
                // 获取用户标识
                $uid                  = $res_token['uid'];

                // 查看是否已点赞
                $ShortCommentPraise   = new \Common\Model\ShortCommentPraiseModel();
                if ($openad) {
                    $ShortCommentPraise = new \Common\Model\AdvertisingCommentPraiseModel();
                }
                $praise_whe           = ['comment_id' => $comment_id, 'user_id' => $uid];
                $praise_cou           = $ShortCommentPraise->where($praise_whe)->getField('id');

                // 点赞操作
                if ($type == 1) {
                    if (!$praise_cou) {
                        $ins_data               = $praise_whe;
                        $ins_data['add_time']   = date('Y-m-d H:i:s');

                        // 验证通过
                        if ($ShortCommentPraise->create($ins_data)) {
                            $ShortCommentPraise->startTrans();   // 启用事务 
                            try {
                                // 记录点赞数据
                                $ShortCommentPraise->add($ins_data);

                                // 评论记录点赞数加
                                $ShortComment->where($comment_whe)->setInc('praise_num');

                                // 事务提交
                                $ShortCommentPraise->commit(); 
                                
                                $this->ajaxSuccess();

                            } catch(\Exception $e) {
                                // 事务回滚
                                $ShortCommentPraise->rollback(); 
                                
                                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                            }

                        } else {
                            $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],  $ShortCommentPraise->getError());
                        }

                    } else {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'YET_PRAISE']);
                    }

                // 取消点赞操作
                } else {
                    if ($praise_cou) {
                        $ShortCommentPraise->startTrans();   // 启用事务 
                        try {
                            // 删除点赞记录
                            $ShortCommentPraise->where($praise_whe)->delete();

                            // 评论记录点赞数减
                            $ShortComment->where($comment_whe)->setDec('praise_num');

                            // 事务提交
                            $ShortCommentPraise->commit(); 
                            
                            $this->ajaxSuccess();

                        } catch(\Exception $e) {
                            // 事务回滚
                            $ShortCommentPraise->rollback(); 

                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }

                    } else {
                        $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_PRAISE']);
                    }
                }

            } else {
                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_COMMENT']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取短视频评论列表
     */
    public function getCommentList()
    {
        // 获取参数
        $token     = trim(I('post.token'));
        $short_id  = I('post.short_id/d');
        $level     = I('post.level/d');                     // 层级 1级或2级
        $root_id   = I('post.root_id/d');                   // 根评论ID
        $blank_ment= I('post.blank_ment');                  // 不显示的评论ID列
        $platform  = trim(I('post.platform'));              // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);
        $openad     = I('post.open_ad');                    //传递此参数使用广告评论列表

        if ($short_id && ($level == 1 || ($level == 2 && $root_id))) {
            // 获取用户标识
            $User                  = new \Common\Model\UserModel();
            $uid                   = $User->getUserId($token);

            // 查询评论列表
            $ShortComment          = new \Common\Model\ShortCommentModel();
            if ($openad) {
                $ShortComment      = new \Common\Model\AdvertisingCommentModel();
            }
            $sc_whe                = ['short_id' => $short_id, 'level' => $level];

            // 根评论ID
            if ($root_id) {
                $sc_whe['root_id'] = $root_id;
            }

            // 不显示的评论ID列
            if ($blank_ment && is_array($blank_ment)) {
                $sc_whe['id'] = ['not in', $blank_ment];
            }

            $list                  = $ShortComment->getListData($uid, $sc_whe, $limit, $page, $platform);

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
     * 短视频转发
     */
    public function handleTransiter()
    {
        // 获取参数
        $short_id   = I('post.short_id/d');     // 视频记录ID
        $openad     = I('post.open_ad');        //传递此参数使用广告转发
        $redId      = I('post.red_id');
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $uid = $User->getUserId($token);
        if ($redId) {
            $redCond = new \Common\Model\LiveRedConditionModel();
            $map['user_id'] = $uid;
            $map['red_id'] = $redId;
            $map['share_friends'] = 1;
            $map['share_friends_time'] = date('Y-m-d H:i:s');
            if ($redCond->add($map)) {
                $this->ajaxSuccess();
            }
        }

        if ($short_id) {

            // 查询文件是否存在
            $short_whe      = ['id' => $short_id];
            $Short          = new \Common\Model\ShortModel();
            $short_one      = $Short->field('id,user_id')->where($short_whe)->find();

            if ($short_one && !$openad) {
                $Short->startTrans();   // 启用事务 
                try {
                    // 视频转发数加
                    $Short->where($short_whe)->setInc('forward_num');

                    // 事务提交
                    $Short->commit(); 
                    
                    $this->ajaxSuccess();

                } catch(\Exception $e) {
                    // 事务回滚
                    $Short->rollback();

                    $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                }

            } else if ($openad) {
                $Adver          = new \Common\Model\AdvertisingModel();
                $Adver->startTrans();   // 启用事务
                try {
                    // 视频转发数加
                    $Adver->where($short_whe)->setInc('forward_num');

                    // 事务提交
                    $Adver->commit();

                    $this->ajaxSuccess();

                } catch(\Exception $e) {
                    // 事务回滚
                    $Adver->rollback();

                    $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                }
            } else {
                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 视频操作置顶或者删除
     */
    public function handleTopDel()
    {
        // 获取参数
        $short_id   = I('post.short_id/d');                 // 视频记录ID
        $type       = trim(I('post.type'));                 // 操作类型  top：置顶  cel_top：取消置顶   del：删除 
        $platform   = trim(I('post.platform'));             // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端


        if ($short_id && $type && in_array($type, ['top', 'cel_top', 'del'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 获取用户标识
            $uid            = $res_token['uid'];

            // 查询文件是否存在
            $short_whe      = ['id' => $short_id, 'user_id' => $uid];
            $Short          = new \Common\Model\ShortModel();
            $short_one      = $Short->field('id,user_id')->where($short_whe)->where(['is_status' => 1])->find();

            if ($short_one) {
                $update = $type == 'top' ? ['self_top' => 1] : ($type == 'del' ? ['is_status' => 0] : ($type == 'cel_top' ? ['self_top' => 0] : []));

                $Short->startTrans();   // 启用事务 
                try {
                    // 视频文件修改
                    if ($update) {
                        $Short->where($short_whe)->save($update);
                    }

                    // 事务提交
                    $Short->commit(); 
                    
                    $this->ajaxSuccess();

                } catch(\Exception $e) {
                    // 事务回滚
                    $Short->rollback();

                    $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                }

            } else {
                $this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 广告点击量
     */
    public function clickVolume()
    {
        // 获取参数
        $short_id   = I('post.short_id/d');     // 广告记录ID
        $openad     = I('post.open_ad');        //传递此参数记录广告点击量

        if ($short_id && $openad) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $short_whe      = ['id' => $short_id];
            $Adver          = new \Common\Model\AdvertisingModel();
            $Adver->startTrans();   // 启用事务
            try {
                // 点击数加
                $Adver->where($short_whe)->setInc('click_num');

                // 事务提交
                $Adver->commit();

                $this->ajaxSuccess();

            } catch(\Exception $e) {
                // 事务回滚
                $Adver->rollback();

                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 广告播放量
     */
    public function playVolume()
    {
        // 获取参数
        $short_id   = I('post.short_id/d');     // 广告记录ID
        $openad     = I('post.open_ad');        //传递此参数记录广告点击量

        if ($short_id && $openad) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);
            $short_whe      = ['id' => $short_id];
            $Adver          = new \Common\Model\AdvertisingModel();
            $Adver->startTrans();   // 启用事务
            try {
                // 点击数加
                $Adver->where($short_whe)->setInc('play_num');

                // 事务提交
                $Adver->commit();

                $this->ajaxSuccess();

            } catch(\Exception $e) {
                // 事务回滚
                $Adver->rollback();

                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }
        }

        $this->ajaxError();
    }

}
?>