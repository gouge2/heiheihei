<?php
/**
 * 短视频文件管理类
 */
namespace Common\Model;
use Think\Model;

class ShortModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('vid','require','文件标识不能为空！',self::EXISTS_VALIDATE),              //存在验证，必填
        array('short_name','1,255','文件名称不符合！',self::VALUE_VALIDATE,'length'),   //值不为空的时候验证，不超过255个字符
        array('create_time','require','记录时间不能为空！',self::EXISTS_VALIDATE),      //存在验证，必填
        array('media_url','1,255','文件链接不符合！',self::VALUE_VALIDATE,'length'),    //值不为空的时候验证，不超过255个字符
    );
    

    /**
     * 获取短视频列表 
     */
    public function getList($whe, $at_id, $limit, $page, $platform = '', $sort = 'sort desc,id desc')
    {
        $data   = [];
        $ip     = getIp();
        $not    = S('short_list_1'. $ip);

        $field  = 'id as short_id,is_status as review_status,user_id,short_name,cat_name,cover_url,media_url,short_tag,praise_num,comment_num,forward_num,width,height,self_top,create_time as add_time';
        $whe    = array_merge($whe, ['is_status' => 1, 'is_recorded' => 0]);
        
        // 非精确查询且第一页查询 随机获取记录
        if (!isset($whe['id']) && $page == 1 && isset($whe['getList'])) {
            unset($whe['getList']);
            $list   = $this->field($field)->where($whe)->limit($limit)->order('rand()')->select();
            
        } else {
            if ($not && $page > 1  && isset($whe['getList'])) {
                $whe['id'] = ['not in', $not];
            }

            unset($whe['getList']);
            $list   = $this->field($field)->where($whe)->page($page, $limit)->order($sort)->select();
        }
        

        if ($list) {
            // 其他模型
            $UserDetail     = new \Common\Model\UserDetailModel();
            $UserPraise     = new \Common\Model\UserPraiseModel();
            $UserConcern    = new \Common\Model\UserConcernModel();
            $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
            $LiveRoom       = new \Common\Model\LiveRoomModel();


            // 其他表查询条件
            $uid_arr = $sid_arr = [];
            $ud_list = $up_list = $uc_list = $sg_list = $lv_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $v) {
                $sid_arr[]      = $v['short_id'];

                if ($v['user_id'] && !in_array($v['user_id'], $uid_arr)) {
                    $uid_arr[]  = $v['user_id'];
                }  
            }

            if ($uid_arr) {
                // 查询用户列表
                $ud_list = $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');

                // 关注列表
                $uc_list = $at_id ? $UserConcern->where(['by_id' => ['in', $uid_arr], 'user_id' => $at_id])->getField('by_id', true) : [];
            }

            // 点赞列表
            $up_list = $at_id ? $UserPraise->where(['short_id' => ['in', $sid_arr], 'user_id' => $at_id])->getField('short_id', true) : [];

            // 正在直播列表
            $lv_list = $LiveRoom->getIsLive(['user_id' => ['in', $uid_arr]]);

            // 关联商品列表
            $sg_whe     = ['short_id' => ['in', $sid_arr], 'type' => 'short'];
            $sg_list = $ShortLiveGoods->getGoodsData('short', $sg_whe, $at_id, null, null, $platform);

            // 组装其他信息
            foreach ($list as $val) {
                $temp                       = $val;

                // 视频与封面判断
                $temp['cover_url']          = is_url($val['cover_url']) ? $val['cover_url'] : WEB_URL . $val['cover_url'];
                $temp['media_url']          = is_url($val['media_url']) ? $val['media_url'] : WEB_URL . $val['media_url'];

                // 作者信息
                $det                        = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => null];
                if (isset($det['user_id'])) {
                    unset($det['user_id']);     // 删除多余的字段
                }

                // 判断头像是否为第三方应用头像
                if ($det['avatar'] && !is_url($det['avatar'])) {
                    $det['avatar'] = WEB_URL . $det['avatar'];
                }

                // 点赞标识
                $temp['praise_iden']        = in_array($temp['short_id'], $up_list) ? 1 : 0;

                // 关注标识
                $temp['concern_iden']       = in_array($temp['user_id'], $uc_list) ? 1 : 0;

                // 直播标识
                $temp['live']               = isset($lv_list[$temp['user_id']]) ? $lv_list[$temp['user_id']] : ['live_iden' => 0, 'live_url' => '', 'room_id' => '0', 'is_fake' => 'N'];

                // 商品信息 
                if ($platform == 'ios') {                                   // 苹果端
                    if (isset($sg_list[$temp['short_id']])) {
                        $temp['goods']      = $sg_list[$temp['short_id']];
                    }
                } else {
                    $temp['goods']          = isset($sg_list[$temp['short_id']]) ? $sg_list[$temp['short_id']] : null;
                }
                
                // 时间转时间戳
                $temp['add_time']           = strtotime($val['add_time']);

                // 标题
                $temp['description']        = $val['short_name'];

                // 类型转换
                $temp['praise_num']         = (int)$val['praise_num'];
                $temp['comment_num']        = (int)$val['comment_num'];
                $temp['forward_num']        = (int)$val['forward_num'];
                $temp['width']              = (int)$val['width'];
                $temp['height']             = (int)$val['height'];
                $temp['self_top']           = (int)$val['self_top'];
                
                $data[]                     = array_merge($temp, $det);
            }

            if (!isset($whe['id']) && $page == 1) {
                S('short_list_1'. $ip, $sid_arr);
            }
        }
        
        return $data;
    }

    /**
     * 获取回放视频
     * @param $where
     * @param $at_id
     * @param $limit
     * @param $page
     * @param string $platform
     * @param string $sort
     * @return array
     */
    public function getRecordList($where, $at_id, $limit, $page, $platform = '', $sort = 'sort desc,id desc') {
        $data   = [];

        $list   = $this
            ->field('id as short_id,room_id,site_id,is_status as review_status,user_id,short_name,cat_name,cover_url,media_url,short_tag,praise_num,comment_num,forward_num,width,height,self_top,create_time as add_time,live_people,live_heat,live_praise')
            ->where($where)
            ->where(['is_status' => 1, 'is_recorded' => 1])
            ->page($page, $limit)
            ->order($sort)
            ->select();

        if ($list) {
            // 加载模块
            $UserDetail = new \Common\Model\UserDetailModel();
            $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
            $LiveRoom = new \Common\Model\LiveRoomModel();
            $UserConcern = new \Common\Model\UserConcernModel();
            $UserPraise = new \Common\Model\UserPraiseModel();

            // 其他表查询条件
            $uid_arr = $sid_arr = [];
            $ud_list = $up_list = $uc_list = $sg_list = $lv_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $k => $v) {
                $sid_arr[]      = $v['site_id'];

                if ($v['user_id'] && !in_array($v['user_id'], $uid_arr)) {
                    $uid_arr[]  = $v['user_id'];
                }
            }

            // 查询主播信息
            if ($uid_arr) {
                // 查询用户列表
                $ud_list = $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');

                // 关注列表
                $uc_list = $at_id ? $UserConcern->where(['by_id' => ['in', $uid_arr], 'user_id' => $at_id])->getField('by_id', true) : [];
            }

            // 点赞列表
            $up_list = $at_id ? $UserPraise->where(['short_id' => ['in', $sid_arr], 'user_id' => $at_id])->getField('short_id', true) : [];

            // 关联商品列表
            $sg_whe     = ['site_id' => ['in', $sid_arr], 'type' => 'live'];
            $sg_list = $ShortLiveGoods->getGoodsData('record', $sg_whe, $at_id, null, null, $platform);

            // 组装其他信息
            foreach ($list as $key => $val) {
                $temp                       = $val;

                // 作者信息
                $det                        = isset($ud_list[$temp['user_id']]) ? $ud_list[$temp['user_id']] : ['nickname' => '', 'avatar' => null];
                if (isset($det['user_id'])) {
                    unset($det['user_id']);     // 删除多余的字段
                }

                // 判断头像是否为第三方应用头像
                if ($det['avatar'] && !is_url($det['avatar'])) {
                    $det['avatar'] = WEB_URL . $det['avatar'];
                }

                // 判断封面是否为用户自己上传
                if ($temp['cover_url'] && !is_url($temp['cover_url'])) {
                    $temp['cover_url'] = WEB_URL . $temp['cover_url'];
                }

                // 点赞标识
                $temp['praise_iden']        = in_array($temp['short_id'], $up_list) ? 1 : 0;

                // 关注标识
                $temp['concern_iden']       = in_array($temp['user_id'], $uc_list) ? 1 : 0;

                // 直播标识
                $temp['live']               = isset($lv_list[$temp['user_id']]) ? $lv_list[$temp['user_id']] : ['live_iden' => 0, 'live_url' => '', 'room_id' => $temp['room_id'], 'is_fake' => 'N'];

                // 商品信息
                if ($platform == 'ios') {                                   // 苹果端
                    if (isset($sg_list[$temp['site_id']])) {
                        $temp['goods']      = $sg_list[$temp['site_id']];
                    }
                } else {
                    $temp['goods']          = isset($sg_list[$temp['site_id']]) ? $sg_list[$temp['site_id']] : null;
                }

                // 时间转时间戳
                $temp['add_time']           = strtotime($val['add_time']);

                // 标题
                $temp['description']        = $val['short_name'];

                // 类型转换
                $temp['praise_num']         = (int)$val['praise_num'];
                $temp['comment_num']        = (int)$val['comment_num'];
                $temp['forward_num']        = (int)$val['forward_num'];
                $temp['width']              = (int)$val['width'];
                $temp['height']             = (int)$val['height'];
                $temp['self_top']           = (int)$val['self_top'];
                $temp['live_people']        = (int)$val['live_people'];
                $temp['live_heat']          = (int)$val['live_heat'];
                $temp['live_praise']        = (int)$val['live_praise'];
                $temp['is_live']            = 'N';

                $data[]                     = array_merge($temp, $det);
            }
        }

        return $data;
    }

    /**
     * 获取用户分组最新回放记录
     * @param $uids
     * @return array
     */
    public function getUserNewestRecord($uids) {
        $resList = array();
        if (count($uids) > 0) {
            $uids = implode(",", $uids);
            $where = "user_id in ($uids) and is_recorded ='1'";
            $list = $this->query("select id from (select id,user_id,is_recorded from lailu_short where $where order by id desc) a group by user_id");

            if (count($list) > 0) {
                foreach ($list as $key => $val) {
                    $resList[] = $val['id'];
                }
            }
        }

        return $resList;
    }

    /**
     * 获取后台直播数据列表
     */
    public function getAdList($whe)
    {
        $list       = [];

        if ($whe) {
            $list   = $this
                    ->where($whe)
                    ->where(['is_status' => 1, 'is_recorded' => 2])
                    ->getField('room_id,id as short_id,is_status as review_status,user_id,short_name,cover_url,media_url,create_time as add_time');
            
            if ($list) {
                foreach ($list as $key => $val) {
                    $list[$key]['cover_url'] = is_url($val['cover_url']) ? $val['cover_url'] : WEB_URL . $val['cover_url'];
                    $list[$key]['media_url'] = is_url($val['media_url']) ? $val['media_url'] : WEB_URL . $val['media_url'];
                }
            }
        }

        return $list;
    }

    /**
     * 回调添加记录 
     */
    public function callbacksAdd($info, $data)
    {
        if ($info && isset($info['Vid'])) {
            $vid   = $info['Vid'];
            $s_id = $this->where("vid='{$vid}'")->getField('id');

            // 其他模型
            $ShortLiveGoods     = new \Common\Model\ShortLiveGoodsModel();

            $insert         = [
                'vid'          => $vid,
                'short_name'   => $info['Name'],
                'description'  => $info['Description'],
                'create_time'  => date('Y-m-d H:i:s', strtotime($info['CreateTime'])),
                'update_time'  => date('Y-m-d H:i:s', strtotime($info['UpdateTime'])),
                'expiret_time' => $info['ExpireTime'] == '9999-12-31T23:59:59Z' ? '9999-12-31 23:59:59' : date('Y-m-d H:i:s', strtotime($info['ExpireTime'])),
                'cat_id'       => $info['ClassId'],
                'cat_name'     => $info['ClassName'],
                'cover_url'    => $info['CoverUrl'],
                'short_type'   => $info['Type'],
                'media_url'    => $info['MediaUrl'],
                'source_info'  => $info['SourceInfo'] ? json_encode($info['SourceInfo']) : '',
                'short_region' => $info['StorageRegion'],
                'short_tag'    => $info['TagSet'] ? json_encode($info['TagSet']) : '',
            ];

            // 宽高设置
            if (isset($data['Width'])) {
                $insert['width']    = $data['Width'];
            }
            if (isset($data['Height'])) {
                $insert['height']   = $data['Height'];
            }

            // 是否是直播录像
            if ($info['SourceInfo'] && isset($info['SourceInfo']['SourceType']) && $info['SourceInfo']['SourceType'] == 'Record') {
                $insert['is_recorded']  = 1;
                
                // 不要回调的封面与名称
                unset($insert['short_name']);
                unset($insert['cover_url']);
            }

            // 透传的用户数据
            if (isset($info['SourceInfo']['SourceContext'])) {
                $text = explode(',', $info['SourceInfo']['SourceContext']);

                if ($text) {
                    // 用户id
                    if (isset($text[0]) && $text[0] > 0) {
                        $insert['user_id'] = $text[0];
                    }

                    // 商品数据
                    if (isset($text[1]) && isset($text[2]) && $text[1] && $text[2]) {
                        $goods_id   = $text[1];
                        $from       = $text[2];
                    }
                }
            }

            $this->startTrans();   // 启用事务
            try {
                // 添加/编辑记录
                if ($s_id) {
                    $this->where(['id' => $s_id])->save($insert);
                    $short_id = $s_id;
                } else {
                    $short_id = $this->add($insert);
                }

                if ($short_id) {

                    // 记录商品关联表
                    if (isset($goods_id) && isset($from) && isset($insert['user_id'])) {
                        $goods = [
                            'short_id' => $short_id, 
                            'goods_id' => $goods_id, 
                            'user_id'  => $insert['user_id'], 
                            'from'     => $from,
                            'type'     => 'short',
                            'add_time' => date('Y-m-d H:i:s'),
                        ];

                        // 添加记录
                        $ShortLiveGoods->add($goods);
                    }
                }

                // 事务提交
                $this->commit(); 
            } catch(\Exception $e) {
                // 事务回滚
                $this->rollback();
            }
        }
    }

    
    /**
     * 回调删除记录 
     */
    public function callbacksDel($data)
    {
        if ($data) {            
            $UserPraise         = new \Common\Model\UserPraiseModel();
            $ShortComment       = new \Common\Model\ShortCommentModel();
            $ShortCommentPraise = new \Common\Model\ShortCommentPraiseModel();
            $ShortLiveGoods     = new \Common\Model\ShortLiveGoodsModel();

            // 是否存在短视频记录
            $sid_arr            = $this->where(['vid' => ['in', $data]])->getField('id', true);

            if ($sid_arr) {
                $this->startTrans();   // 启用事务
                try {
                    $val            = ['in', $sid_arr];
                    // 删除短视频记录
                    $this->where(['id' => $val])->delete();

                    // 删除点赞记录
                    $UserPraise->where(['short_id' => $val])->delete();

                    // 删除评论记录
                    $scid_arr       = $ShortComment->where(['short_id' => $val])->getField('id', true);
                    $ShortComment->where(['short_id' => $val])->delete();

                    // 删除评论点赞记录
                    if ($scid_arr) {
                        $ShortCommentPraise->where(['comment_id' => ['in', $scid_arr]])->delete();
                    }

                    // 删除关联商品记录
                    $ShortLiveGoods->where(['short_id' => $val, 'type' => 'short', 'is_lose' => 1])->save(['is_status' => 0]);

                    
                    // 事务提交
                    $this->commit(); 
                } catch(\Exception $e) {
                    // 事务回滚
                    $this->rollback();
                }
            }
        }
    }


    /**
     * 回调修改记录
     */
    public function callbacksMod($data)
    {
        if ($data) {
            $whe    = ['vid' => $data['FileId']];
            $count  = $this->where($whe)->getField('id');

            if ($count) {
                // 添加视频的高度宽度
                $save = ['width' => $data['MetaData']['Width'], 'height' => $data['MetaData']['Height']];
                $this->where($whe)->save($save);
            }

        }
    }

    /**
     * 假直播创建或者修改记录
     */
    public function adDateSave($Im, $data, $room_id, $lan=false)
    {
        $res            = false;
            
        if ($data && $room_id) {
            $User       = new \Common\Model\UserModel();
            $s_id       = $this->where(['room_id' => $room_id, 'is_recorded' => $data['is_recorded']])->getField('id');
            
            if ($s_id) {  	 	// 编辑
                unset($data['create_time']);
                $this->where(['id' => $s_id])->save($data);

            } else {			// 添加	
                $this->add($data);
            }
            
            if (!$lan) {
                // 用户主播字段修改
                $User->where(['uid' => $data['user_id'], 'is_host' => 'N'])->save(['is_host' => 'Y']);

                // 房间信息初始化
                get_live_room_info($room_id, 'init');

                // 先导入IM用户(多次导入只会创建一个IM-userId) 后创建IM房间群
                if ($room_id) {
                    $Im::userIdAdd($data['user_id']);
                    $res    = $Im::createLiveGroup($room_id, $data['user_id']);
                }
            }
        }

        return $res;
    }
    
}
?>