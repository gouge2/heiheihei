<?php
/**
 * 短视频评论管理类
 */
namespace Common\Model;
use Think\Model;

class ShortCommentModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('short_id','require','短视频标识不能为空！',self::EXISTS_VALIDATE),      //存在验证，必填
        array('user_id','require','评论人标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('text','1,255','评论内容不为空或者超过长度限制！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过255个字符
        array('add_time','require','评论时间不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );
    

    /**
     * 获取评论列表 多维数组形式
     */
    public function getListData($uid, $where, $limit, $page, $platform = '', $parent_id = 0, $sort = ' sort desc,id desc ')
    {
        $data               = [];

        // 评论列表
        $list               = $this->field('id as comment_id,short_id,parent_id,reply_id,user_id,text,praise_num,add_time')
                            ->where($where)->where(['is_status' => 1])->page($page, $limit)->order($sort)->select();

        if ($list) {
            // 其他模型
            $Short              = new \Common\Model\ShortModel();
            $UserDetail         = new \Common\Model\UserDetailModel();
            $ShortCommentPraise = new \Common\Model\ShortCommentPraiseModel();

            // 其他表查询条件
            $uid_arr = $cid_arr = $sid_arr = [];
            $ud_list = $scp_list= $su_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $k => $v) {
                $cid_arr[]     = $v['comment_id'];
                $sid_arr[]     = $v['short_id'];

                if ($v['reply_id'] && !in_array($v['reply_id'], $uid_arr)) {
                    $uid_arr[] = $v['reply_id'];
                }

                if ($v['user_id'] && !in_array($v['user_id'], $uid_arr)) {
                    $uid_arr[] = $v['user_id'];
                }
            }

            if ($uid_arr) {
                // 查询用户列表
                $ud_list = $UserDetail->where(['user_id' => ['in', $uid_arr]])->getField('user_id,nickname,avatar');
            }

            // 点赞列表
            $scp_list = $uid ? $ShortCommentPraise->where(['comment_id' => ['in', $cid_arr], 'user_id' => $uid])->getField('comment_id', true) : [];

            // 作者列表
            $su_list  = $Short->where(['id' => ['in', $sid_arr]])->getField('id,user_id');


            foreach ($list as $key => $val) {
                $temp                = $val;

                // 评论用户、回复用户信息
                $reply['reply_nickname']    = isset($ud_list[$temp['reply_id']]) ? $ud_list[$temp['reply_id']]['nickname'] : '';
                $users                      = ['user_nickname' => '', 'user_avatar' => ''];
                if (isset($ud_list[$temp['user_id']])) {
                    $users                  = [
                        'user_nickname'     => $ud_list[$temp['user_id']]['nickname'], 
                        'user_avatar'       => $ud_list[$temp['user_id']]['avatar'],
                    ] ;
                }

                // 判断头像是否为第三方应用头像
                if ($users['user_avatar'] && !is_url($users['user_avatar'])) {
                    $users['user_avatar']   = WEB_URL . $users['user_avatar'];
                }

                // 是否点赞过该评论  
                $temp['praise_iden']        = in_array($temp['comment_id'], $scp_list) ? 1 : 0;

                // 是否是作者
                $temp['user_author_iden']   = 0;
                $temp['reply_author_iden']  = 0;
                if (isset($su_list[$temp['short_id']])) {
                    $temp['user_author_iden']   = $temp['user_id'] == $su_list[$temp['short_id']]['user_id'] ? 1 : 0;
                    $temp['reply_author_iden']  = $temp['reply_id'] == $su_list[$temp['short_id']]['user_id'] ? 1 : 0;
                }

                // 查询子列表
                $temp['child']              = [];
                if (isset($where['level']) && $where['level'] == 1) {
                    $c_whe                  = ['level' => 2, 'root_id' => $temp['comment_id']];
                    if ($platform == 'android') {   // 安卓端
                        $limit = 3;
                    }

                    $temp['child']          = $this->getListData($uid, $c_whe, $limit, 1, $platform, $temp['comment_id']);
                    // 子列表总数
                    $temp['child_sum']      = (int)$this->where($c_whe)->count();
                }

                // 是否显示回复与回复之后的昵称
                $temp['show_reply_iden']    = ($temp['parent_id'] && $temp['parent_id'] != $parent_id) ? 1 : 0;

                // 时间转时间戳
                $temp['add_time']           = strtotime($val['add_time']);

                // 类型转换
                $temp['praise_num']         = (int)$val['praise_num'];
                
 
                $data[]                     = array_merge($temp, $reply, $users);
            }
        }

        return $data;
    }


}
?>